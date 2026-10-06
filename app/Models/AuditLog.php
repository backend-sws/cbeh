<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\AuditLoggerService;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'auditable_type',
        'auditable_id',
        'record_title',
        'old_values',
        'new_values',
        'changed_fields',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship with the user who triggered the audit event.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Polymorphic relation to the affected model.
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Quick static helper to record custom audit logs from controllers/services.
     */
    public static function record(string $action, string $module, string $description, ?Model $model = null, ?array $old = null, ?array $new = null): ?self
    {
        return app(AuditLoggerService::class)->logManual($action, $module, $description, $model, $old, $new);
    }
}
