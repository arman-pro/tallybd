<?php

namespace App;

use App\CustomTrait\AutoTimeStamp;
use App\CustomTrait\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use App\CustomTrait\HasRecursiveRelationships;

class AccountGroup extends Model
{
    use AutoTimeStamp, GlobalScope,HasRecursiveRelationships,GlobalScope;
    protected $table = 'account_groups';
    protected $guarded=['id'];

    public function getParentKeyName()
    {
        return 'account_group_under_id';
    }

    public function accountLedgers()
    {
        return $this->hasMany(AccountLedger::class, 'account_group_id', 'id');
    }

    public function groupUnder()
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_under_id', 'id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(self::class, $this->getParentKeyName())->with('children.accountLedgers.summary');
    }



    public function groupsUnder()
    {
        return $this->hasMany(AccountGroup::class, 'account_group_under_id', 'id');
    }
    public function groupUnders()
    {
        return $this->hasMany(AccountGroup::class, 'account_group_under_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


}
