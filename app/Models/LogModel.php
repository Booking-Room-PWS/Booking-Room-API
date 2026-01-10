<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DateTimeInterface;

class LogModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'log';
    protected $primaryKey = 'log_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'log_method',
        'log_url',
        'log_ip',
        'log_request',
        'log_response'
    ];

    protected $hidden = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}