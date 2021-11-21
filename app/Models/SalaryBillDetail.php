<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;

class SalaryBillDetail extends Model
{
    use MultiTenantModelTrait;

    public $table = 'salary_bill_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'salary',
        'da',
        'hra',
        'other',
        'ota',
        'year_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}