@php
    $company = App\Companydetail::first();
    $leftSide =0;
    $rightSide =0;
@endphp
<table>
    <thead>
        <tr>
            <th colspan="2">Receivable (TK)</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($ledger as $item)
    <?php
        $amount = $item->received_payment_amount($endMonth);
    ?>
    @if($amount > 0)
    @php
        $leftSide += $amount??0;
    @endphp
    <tr>
        <td>
            {{$item->account_name}}
        </td>
        <td>
            {{ new_number_format( $amount??0.00)}} (DR)
        </td>
    </tr>
    @endif
    @empty
    @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            <th>{{new_number_format($leftSide)}} (DR)</th>
        </tr>
    </tfoot>
</table>

<table>
    <thead>
        <tr>
            <th colspan="2">Payable (TK)</th>
        </tr>
    </thead>
    @forelse ($ledger as $payable_item)
    <?php
        $amount = $payable_item->received_payment_amount($endMonth);
    ?>
    @if($amount < 0) @php $rightSide +=($amount * (-1)) ?? 0;
        @endphp
        <tr>
            <td>
                {{$payable_item->account_name}}
            </td>
            <td>
                {{ new_number_format( $amount??0.00)}} (CR)
            </td>
        </tr>
        @endif
        @empty
    @endforelse
    <tfoot>
        <tr>
            <th>Total</th>
            <th>{{new_number_format($rightSide)}} (CR)</th>
        </tr>
    </tfoot>
</table>