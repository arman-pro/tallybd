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

    /**
     * get group id with all ledger group id under this ledger
     * @param object $this
     * @return array
     */
    public function get_all_under_group_id($group_name, $arr = []) 
    {
        array_push($arr, $group_name->id);
        if($group_name->groupUnders->isEmpty()) {
            return $arr;
        }

        if($group_name->groupUnders->isNotEmpty()) {
            foreach($group_name->groupUnders as $group_under) {
                array_push($arr, $group_under->id);
                $group_name->get_all_under_group_id($group_under, $arr);
            }
        }
        return array_merge([], ...$arr);
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
