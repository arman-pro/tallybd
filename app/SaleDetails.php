<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    use AutoTimeStamp;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function saleable()
    {
        return $this->morphTo();
    }
}
