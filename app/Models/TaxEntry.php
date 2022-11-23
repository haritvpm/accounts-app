<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class TaxEntry extends Model
{
    use MultiTenantModelTrait;


    const STATUS_SELECT = [
        'draft' => 'Draft',
        'approved' => 'Approved',
    ];

    public $table = 'tax_entries';

    protected $appends = [
        'innerfile',
        'deductionfile',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date',
        'status',
        'acquittance',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
       
    ];

    public function dateTds()
    {
        return $this->hasMany(Td::class, 'date_id', 'id');
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}