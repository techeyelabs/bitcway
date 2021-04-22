<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DerivativeSell extends Model
{
    use HasFactory;
    protected $table = 'derivative_sells';
    protected $primaryKey = 'id';
}
