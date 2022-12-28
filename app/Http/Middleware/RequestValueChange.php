<?php

namespace App\Http\Middleware;

use Closure;

class RequestValueChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // change reuqest value here, if need to change
        // here is change only date value
        $dates = ['date', 'form_date', 'to_date', 'fromDate', 'toDate', 'salary_date', 'from_date'];
        if($request->hasAny($dates)) {
            foreach($dates as $date) {
                if($request->has($date)) {
                    $request->merge([
                        $date => date('Y-m-d', strtotime($request->$date)),
                    ]);
                    
                    request()->merge([
                        $date => date('Y-m-d', strtotime($request->$date)),
                    ]);
                }
            }
        }
       
        return $next($request);
    }
}
