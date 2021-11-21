<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    public $table = 'years';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'financial_year',
        'active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function yearAllocations()
    {
        return $this->hasMany(Allocation::class, 'year_id', 'id');
    }
}