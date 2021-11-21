<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    public $table = 'roles';

    protected $dates = [
        'created_at',
        'updated_at',
        
    ];

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}