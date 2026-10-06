<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditLoggerService
{
    /**
     * Default attributes to exclude from change tracking.
     */
    protected array $defaultExcludedAttributes = [
        'password',
        'remember_token',
        'updated_at',
        'created_at',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Log an Eloquent model lifecycle event (created, updated, deleted, restored).
     */
    public function logModelEvent(string $action, Model $model): ?AuditLog
    {
        try {
            $excluded = array_merge(
                $this->defaultExcludedAttributes,
                property_exists($model, 'auditExclude') && is_array($model->auditExclude) ? $model->auditExclude : []
            );

            $oldValues = null;
            $newValues = null;
            $changedFields = [];
            $description = '';

            $module = $this->resolveModule($model);
            $recordTitle = $this->resolveTitle($model);

            if ($action === 'created') {
                $rawAttributes = $model->getAttributes();
                $newValues = array_diff_key($rawAttributes, array_flip($excluded));
                $changedFields = array_keys($newValues);
                $description = "Created {$module} '{$recordTitle}'";
            } elseif ($action === 'updated') {
                $dirty = $model->getDirty();
                // Filter out excluded attributes from dirty keys
                $dirtyFiltered = array_diff_key($dirty, array_flip($excluded));

                if (empty($dirtyFiltered)) {
                    // Nothing meaningful changed (e.g. only updated_at)
                    return null;
                }

                $original = $model->getOriginal();
                $oldValues = [];
                $newValues = [];

                foreach ($dirtyFiltered as $key => $newValue) {
                    $oldValue = $original[$key] ?? null;
                    // Skip if value didn't actually change (type-cast check)
                    if ((string)$oldValue === (string)$newValue) {
                        continue;
                    }
                    $oldValues[$key] = $oldValue;
                    $newValues[$key] = $newValue;
                    $changedFields[] = $key;
                }

                if (empty($changedFields)) {
                    return null;
                }

                $changesList = [];
                foreach (array_slice($changedFields, 0, 4) as $field) {
                    $prettyField = ucwords(str_replace('_', ' ', $field));
                    $oldVal = is_scalar($oldValues[$field]) ? (string)$oldValues[$field] : json_encode($oldValues[$field]);
                    $newVal = is_scalar($newValues[$field]) ? (string)$newValues[$field] : json_encode($newValues[$field]);
                    $changesList[] = "{$prettyField}: '{$oldVal}' → '{$newVal}'";
                }

                $description = "Updated {$module} '{$recordTitle}' (" . implode(', ', $changesList) . (count($changedFields) > 4 ? ', ...' : '') . ")";
            } elseif ($action === 'deleted') {
                $rawAttributes = $model->getAttributes();
                $oldValues = array_diff_key($rawAttributes, array_flip($excluded));
                $description = "Deleted {$module} '{$recordTitle}'";
            }

            return $this->persist([
                'user_id' => Auth::id(),
                'user_name' => $this->resolveUserName(),
                'user_role' => $this->resolveUserRole(),
                'action' => $action,
                'module' => $module,
                'auditable_type' => get_class($model),
                'auditable_id' => (string) $model->getKey(),
                'record_title' => $recordTitle,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'changed_fields' => $changedFields,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'description' => $description,
            ]);
        } catch (\Throwable $e) {
            Log::error('AuditLoggerService model event error: ' . $e->getMessage(), [
                'action' => $action,
                'model' => get_class($model),
                'id' => $model->getKey(),
                'exception' => $e,
            ]);
            return null;
        }
    }

    /**
     * Log a manual or custom action.
     */
    public function logManual(string $action, string $module, string $description, ?Model $model = null, ?array $old = null, ?array $new = null): ?AuditLog
    {
        try {
            $changedFields = [];
            if ($old && $new) {
                $changedFields = array_keys(array_diff_assoc($new, $old));
            } elseif ($new) {
                $changedFields = array_keys($new);
            }

            return $this->persist([
                'user_id' => Auth::id(),
                'user_name' => $this->resolveUserName(),
                'user_role' => $this->resolveUserRole(),
                'action' => $action,
                'module' => $module,
                'auditable_type' => $model ? get_class($model) : null,
                'auditable_id' => $model ? (string)$model->getKey() : null,
                'record_title' => $model ? $this->resolveTitle($model) : null,
                'old_values' => $old,
                'new_values' => $new,
                'changed_fields' => $changedFields,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'description' => $description,
            ]);
        } catch (\Throwable $e) {
            Log::error('AuditLoggerService manual log error: ' . $e->getMessage(), ['exception' => $e]);
            return null;
        }
    }

    /**
     * Persist record to audit_logs table.
     */
    protected function persist(array $data): ?AuditLog
    {
        return AuditLog::create($data);
    }

    /**
     * Resolve user name or System / Guest.
     */
    protected function resolveUserName(): string
    {
        $user = Auth::user();
        if ($user) {
            return $user->name ?? $user->email ?? ('User #' . $user->id);
        }
        return app()->runningInConsole() ? 'System / Console' : 'Guest';
    }

    /**
     * Resolve user role.
     */
    protected function resolveUserRole(): string
    {
        $user = Auth::user();
        if (!$user) {
            return app()->runningInConsole() ? 'CLI' : 'Guest';
        }

        if (method_exists($user, 'getRoleNames')) {
            $roles = $user->getRoleNames();
            if ($roles && $roles->isNotEmpty()) {
                return $roles->implode(', ');
            }
        }

        return $user->role ?? 'User';
    }

    /**
     * Resolve module name from model or model property.
     */
    protected function resolveModule(Model $model): string
    {
        if (property_exists($model, 'auditModule') && !empty($model->auditModule)) {
            return $model->auditModule;
        }

        $className = class_basename($model);

        // Friendly mapping for common HMS models
        $map = [
            'Medicine' => 'Pharmacy',
            'PurchaseMedicine' => 'Pharmacy Purchase',
            'PurchasedMedicine' => 'Pharmacy Purchase Item',
            'MedicineBill' => 'Pharmacy Billing',
            'MedicineBillPayment' => 'Pharmacy Payment',
            'SaleMedicine' => 'Pharmacy Sale',
            'MedicineStockDump' => 'Pharmacy Dump',
            'Patient' => 'Patients',
            'IpdPatient' => 'IPD Patient',
            'OpdPatient' => 'OPD Patient',
            'Addmission' => 'Admissions',
            'IpdDischarge' => 'IPD Discharge',
            'Prescription' => 'Prescriptions',
            'PrescriptionMedicine' => 'Prescription Medicine',
            'PatientMedication' => 'MAR Medication',
            'MedicationAdministration' => 'MAR Administration',
            'IpdVital' => 'IPD Vitals',
            'PatientVital' => 'Patient Vitals',
            'IpdProgressNote' => 'IPD Progress Notes',
            'OpdProgressNote' => 'OPD Progress Notes',
            'Billing' => 'Billing',
            'IpdPayment' => 'IPD Payment',
            'OpdPayment' => 'OPD Payment',
            'PaymentCollection' => 'Cash Collections',
            'Appointment' => 'Appointments',
            'Operation' => 'Operations',
            'User' => 'User Management',
            'Doctor' => 'Doctors',
        ];

        return $map[$className] ?? preg_replace('/(?<!\ )[A-Z]/', ' $0', $className);
    }

    /**
     * Resolve a friendly title/label for the record.
     */
    protected function resolveTitle(Model $model): string
    {
        if (method_exists($model, 'getAuditTitle')) {
            return $model->getAuditTitle();
        }

        if (property_exists($model, 'auditTitleAttribute') && !empty($model->auditTitleAttribute)) {
            $attr = $model->auditTitleAttribute;
            if (!empty($model->{$attr})) {
                return (string) $model->{$attr};
            }
        }

        // Try standard identifying columns
        foreach (['name', 'title', 'medicine', 'patient_name', 'bill_number', 'purchase_no', 'bill_no', 'case_id', 'ipd_id', 'opd_id', 'email'] as $col) {
            if (!empty($model->{$col})) {
                return (string) $model->{$col};
            }
        }

        return '#' . $model->getKey();
    }
}
