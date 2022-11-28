<?php

namespace App;

use App\CustomTrait\GlobalScope;
use Illuminate\Database\Eloquent\Model;

class Companydetail extends Model
{
    use GlobalScope;

    protected $fillable = [
        'company_name',
        'contry_name',
        'mailing_name',
        'email_id',
        'website_name',
        'phone',
        'mobile_number',
        'booking_date',
        'company_address',
        'company_des',
        'company_logo',
        'financial_year_id'
    ];


    public function financial_year()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
