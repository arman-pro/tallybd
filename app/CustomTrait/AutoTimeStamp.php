<?php

namespace App\CustomTrait;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait AutoTimeStamp
{

    private static $baseClass = (self::class);

    public static function bootAutoTimeStamp()
    {
        static::creating(function ($model) {
            $req = request();
            if($req->date){
                $date = date('Y-m-d', strtotime($req->date));
            }else{
                $date = date('Y-m-d');
            }
            
            // if have salary date override it 
            if($req->salary_date) {
                $date = date('Y-m-d', strtotime($req->salary_date));
            }
            
            $model->fill([
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'date'          =>  $date,
            ]);

            if (Schema::hasColumn(self::getBaseTable(), 'created_by')) {
                $model->fill([
                    'created_by' => auth()->id(),
                ]);
            }
        });
        static::updating(function ($model) {

            $model->fill([
                'updated_at' => Carbon::now(),
            ]);

            if (Schema::hasColumn(self::getBaseTable(), 'updated_by')) {
                $model->fill([
                    'updated_by' => auth()->id(),
                ]);
            }
        });
    }

    public static function getBaseTable()
    {
        $baseClass  = self::$baseClass;
        $obj        = new $baseClass;
        $table      = $obj->getTable();
        return $table;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }


}
