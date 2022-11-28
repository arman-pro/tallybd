<?php

namespace App\CustomTrait;
trait GlobalScope
{

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearching($query, $fieldName, $searchTerm)
    {
        if($fieldName){
            return $query->where($fieldName, 'LIKE', "%{$searchTerm}%");
        }
        return $query->where('name', 'LIKE', "%{$searchTerm}%");
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, $fieldName=null)
    {

        if( $fieldName){
            return $query->where($fieldName, true);
        }
        return $query->where('status', true);
    }

    public function scopeDeactive($query, $fieldName=null)
    {
        if( $fieldName){
            return $query->where($fieldName, false);
        }
        return $query->where('status', false);
    }

}
