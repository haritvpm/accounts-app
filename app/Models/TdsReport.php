<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TdsReport extends Model
{
    const PERIOD_RADIO = [
        '0' => 'Apr,May,Jun',
        '1' => 'Jul,Aug,Sep',
        '2' => 'Oct,Nov,Dec',
        '3' => 'Jan,Feb,Mar',
    ];

    public $table = 'tds_reports';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'year',
        'period',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
