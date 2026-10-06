<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Services\AuditLoggerService;

trait Auditable
{
    /**
     * Boot the Auditable trait for the model.
     */
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            app(AuditLoggerService::class)->logModelEvent('created', $model);
        });

        static::updated(function ($model) {
            app(AuditLoggerService::class)->logModelEvent('updated', $model);
        });

        static::deleted(function ($model) {
            app(AuditLoggerService::class)->logModelEvent('deleted', $model);
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                app(AuditLoggerService::class)->logModelEvent('restored', $model);
            });
        }
    }

    /**
     * Polymorphic relation to audit logs for this specific model instance.
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable')->latest('created_at');
    }

    /**
     * Manually log a custom action on this model.
     */
    public function logAuditAction(string $action, string $description, ?array $old = null, ?array $new = null): ?AuditLog
    {
        $service = app(AuditLoggerService::class);
        $module = property_exists($this, 'auditModule') ? $this->auditModule : class_basename($this);
        return $service->logManual($action, $module, $description, $this, $old, $new);
    }
}
