<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronTrack extends Model
{
    use HasFactory;
    protected $table = 'cron_test';
    protected $primaryKey = 'id';
}
