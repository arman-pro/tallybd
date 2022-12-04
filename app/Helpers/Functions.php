<?php

function new_number_format($number)
{
    $number = (float)$number;
    $float = '.00';
    if(!is_numeric($number) || is_infinite($number))
        return false;
    if(is_float($number)) 
    {
        $arr = explode(".", $number);
        $number = $arr[0];
        if(isset($arr[1]))
            $float = '.'.substr($arr[1], 0, 2);
    }
    $num = strrev(abs($number));
    $new_ = '';
    $len = strlen($num);
    for ($i = 0; $i < $len; $i++) {
        if($i == 3 || $i == 5 || $i == 7)
            $new_ .= ',';
        $new_ .= $num[$i];
    }
    if($number < 0)
        $new_ = $new_.'-';
    return strrev($new_) . $float;
}

function make_action_btn($link_arr) {
    return '<div class="btn-group">
    <button type="button" class="btn btn-secondary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <b>Action</b>
    </button>
    <div class="dropdown-menu" style="margin: 0px;">'.collect($link_arr)->implode('').'</div></div>';
}

/**
 * get number to word
 * @param int $amount
 * @return string
 */
function number_to_word($amount) 
{
    return App\Helpers\Helper::NoToWord($amount);
}