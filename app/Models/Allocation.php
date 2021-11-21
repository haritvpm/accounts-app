<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
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
    ];

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }
}