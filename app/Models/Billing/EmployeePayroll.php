<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;

class EmployeePayroll extends Model
{
    protected $table = 'employee_payrolls'; // Table name

    protected $fillable = [
        'sr_no',
        'payroll_id',
        'role',
        'employee_id',
        'month',
        'year',
        'status',
        'basic_salary',
        'allowance',
        'deduction',
        'net_salary'
    ];
    
}
