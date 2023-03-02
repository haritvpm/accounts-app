<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenantModelTrait;

class Allocation extends Model
{
    use MultiTenantModelTrait;

    public $table = 'allocations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pay',
        'da',
        'hra',
        'ota',
        'other',
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
