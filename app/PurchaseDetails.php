<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use AutoTimeStamp;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function purchaseable()
    {
        return $this->morphTo();
    }
}
