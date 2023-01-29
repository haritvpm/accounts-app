<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Model;

class Td extends Model
{
    use MultiTenantModelTrait;

    public $table = 'tds';

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['date'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pan',
        'pen',
        'name',
        'gross',
        'tds',
        'date_id',
        'created_at',
        'slno',
        'remarks',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public function date()
    {
        return $this->belongsTo(TaxEntry::class, 'date_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
