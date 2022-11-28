<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsOption extends Model
{
    protected $table = 'sms_options';
    
    public function sms() 
    {
        return $this->belongsTo(SMS::class);
    }
}
