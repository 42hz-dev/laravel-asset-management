<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $guarded = [];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function employeesWithoutLeader(): HasMany
    {
        return $this->hasMany(Employee::class)
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->whereColumn('departments.employee_id', '!=', 'employees.id')
            ->select('employees.*');
    }
}
