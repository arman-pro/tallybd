<?php

namespace App\Http\Controllers\MBCorporation;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\SMS;
use App\SmsOption;
use App\Traits\SMS as TraitsSMS;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    use TraitsSMS;
    public function index()
    {
        $sms_s = SMS::all();
        $sms = SMS::first();
        // $smsBalance=   $this->checkBalance();
        $smsBalance=  0;
        return view('sms.index', compact('sms', 'smsBalance', 'sms_s'));
    }
    
    public function create()
    {
        return view('sms.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required',
            'value' => 'required',
            'name' => 'required',
            'provider_url' => 'required',
        ]);
        
        $sms = new SMS();
        $sms->provider = $request->provider;
        $sms->provider_url = $request->provider_url;
        $sms->sms_check_url = $request->sms_check_url;
        $sms->apiKey = 'null';
        $sms->senderId = 'null';
        $sms->smsType = 'null';
        $sms->save();
        
        foreach($request->name as $key => $name) {
            $sms_option = new SmsOption();
            $sms_option->sms_id = $sms->id;
            $sms_option->name = $name;
            $sms_option->value = $request->value[$key];
            $sms_option->save();
        }
        
        return redirect()->route('sms')->with('message', 'SMS Create Successfully');
        
    }
    
    public function sms_active($id) 
    {
        foreach(SMS::all() as $sms) {
            $sms->is_active = false;
            $sms->save();
        }
        
        $sms = SMS::find($id);
        $sms->is_active = true;
        $sms->save();
        return redirect()->route('sms')->with('message', 'SMS Active Successfull!');
    }
    
    public function edit($id) 
    {
        $sms = SMS::find($id);
        return view('sms.edit', compact('sms'));
    }
    
    public function update(Request $request, $id) 
    {
         $request->validate([
            'provider' => 'required',
            'value' => 'required',
            'name' => 'required',
            'provider_url' => 'required',
        ]);
        
        $sms = SMS::find($id);
        $sms->provider = $request->provider;
        $sms->sms_check_url = $request->sms_check_url;
        $sms->provider_url = $request->provider_url;
        $sms->save();
        
        // delete sms options
        foreach($sms->sms_options as $sms_option) {
            $sms_option->delete();
        }
        
         foreach($request->name as $key => $name) {
            $sms_option = new SmsOption();
            $sms_option->sms_id = $sms->id;
            $sms_option->name = $name;
            $sms_option->value = $request->value[$key];
            $sms_option->save();
        }
        
        return redirect()->route('sms')->with('message', 'SMS Updated Successfully');
    }
    
    public function destroy($id)
    {
        $sms = SMS::find($id);
        // delete all options
        foreach($sms->sms_options as $sms_option) {
            $sms_option->delete();
        }
        $sms->delete();
       return redirect()->route('sms')->with('message', 'SMS Delete Successfully');
    }
    
    public function settingSms(Request $request)
    {
        $data = $request->except('_token');
        SMS::updateOrCreate(
            ['id' => 1],
            [
                'apiKey' =>$data['apiKey'],
                'senderId' =>$data['senderId'],
                'smsType' =>$data['smsType'],
            ]
        );
        (new LogActivity)->addToLog('SMS Setting .');

    return back();
    }


    public function sendSms(Request $request)
    {
        try {
            $body = TraitsSMS::sendSMS($request->number, $request->smsBody);
            return back()->with('success', 'send sms')->with('sms_body', $body);
        } catch (\Throwable $th) {
            throw $th;
        }
        return back()->with('success', 'send sms');

    }
}
