<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    public $table = 'columns';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TYPE_SELECT = [
        'none' => 'None',
        'allowance' => 'Allowance',
        'deduction' => 'Deduction',
        'recovery'  => 'Recovery',
    ];

    protected $fillable = [
        'type',
        'head_id',
        'spark_title',
        'title',
        'fieldmapping',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function head()
    {
        return $this->belongsTo(Head::class, 'head_id');
    }
}
