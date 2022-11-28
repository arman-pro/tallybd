
@if ($property->accountLedgers)
    @foreach($property->accountLedgers  as $ledgers)
    <li>
        <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>&nbsp;   {{$ledgers->account_name??' '}}
    </li>
    @endforeach
@endif
