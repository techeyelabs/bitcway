<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function latestMessage()
    {
        return $this->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->first();
    }
    public function unread()
    {
        return $this->where('user_id', $this->user_id)->where('read_by_admin', 'false')->count();
    }
}
