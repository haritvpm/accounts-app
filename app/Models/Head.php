<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Head extends Model
{
    public $table = 'heads';

    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $fillable = [
        'head',
        'created_at',
        'updated_at',

    ];

    public function headAllocations()
    {
        return $this->hasMany(Allocation::class, 'head_id', 'id');
    }
}
