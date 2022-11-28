<?php

namespace App\Traits;

use App\SMS as AppSMS;
use GuzzleHttp\Client;

trait SMS
{
    public static function sendSMS($mobile_no, $text)
    {
        
        $active_sms =  AppSMS::where('is_active', true)->first();
        $url = $active_sms->provider_url;
        $data = [];
        foreach($active_sms->sms_options as $sms_option) {
            $data[$sms_option->name] = $sms_option->value;
        }
        $phone = $data['phone'];
        $message = $data['message'];
        
        unset($data['phone']);
        unset($data['message']);
        
        $data[$phone] = '88' . $mobile_no;
        $data[$message] = $text;
        
        
        $query = collect($data)->map(function ($value, $key) {
            return urlencode($key) . '=' . urlencode($value);
        })->join('&');
        
        $url = $url. "?". $query;
        
        // $otp_config =  AppSMS::first();
        // $url = 'https://bulksms.ahmedtechbd.com/smsapi/sendsms?apikey='.':apiKey'.'&smstype='.':smsType'.'&msisdn='.':MOBILE'.'&senderid='.':senderId'.'&msg='.':TEXT';

        // $url = str_replace(':apiKey',  $otp_config->apiKey, $url);
        // $url = str_replace(':smsType',  $otp_config->smsType, $url);
        // $url = str_replace(':senderId', $otp_config->senderId, $url);
        // $url = str_replace(':MOBILE', '88' . $mobile_no, $url);
        // $url = str_replace(':TEXT', urlencode($text), $url);

        try {
            return (new Client)->get($url)->getBody();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public  static function checkBalance()
    {
        $otp_config =  AppSMS::first();
        $url =  'https://bulksms.ahmedtechbd.com/smsapi/api?apikey=:apiKey';
        $url = str_replace(':apiKey',  $otp_config->apiKey, $url);
        // $url  = 'https://bulksms.ahmedtechbd.com/smsapi/api?apikey=mastertrade81631165616';
        try {
            return (new Client)->get($url)->getBody();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }
}

