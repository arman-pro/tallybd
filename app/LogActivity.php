<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $fillable = [
        'subject', 'url', 'method', 'ip', 'agent', 'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
