<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminWithdrawMessage extends Model
{
    use HasFactory;
    protected $table = 'admin_withdraw_messages';
    protected $primaryKey = 'id';
}
