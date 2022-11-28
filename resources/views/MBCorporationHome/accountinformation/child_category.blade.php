<li>
    <a href="#">{{$childCategory->account_group_name }}</a>
    @if ($child_category->children)
    @foreach ($child_category->children as $childCategory)
        <ul>

            @include('MBCorporationHome.accountinformation.child_category', ['child_category' => $childCategory])
            @foreach($childCategory->accountLedgers  as $ledgers)
            <li>
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>&nbsp; {{$ledgers->account_name??' '}}
            </li>
            @endforeach
        </ul>

    @endforeach

    @endif
</li>

