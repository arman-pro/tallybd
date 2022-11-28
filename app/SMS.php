<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class SMS extends Model
{
    use AutoTimeStamp;
    protected $guarded=['id'];
    
    public function sms_options() {
        return $this->hasMany(SmsOption::class, 'sms_id');
    }
    
    public function get_balance($url)
    {
        if($url) {
            try {
                return (new Client)->get($url)->getBody();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }else {
            return 0;
        }
    }
    
}
