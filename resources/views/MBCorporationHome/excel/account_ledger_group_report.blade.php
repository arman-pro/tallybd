<?php
    $company = App\Companydetail::first();
    $dr = 0;
    $cr = 0;
?>
<table>
    <thead>
        <tr>
            <th colspan='5'>
                Name: {{ $company->company_name }}
            </th>
        </tr>
        <tr>
            <th colspan='5'>
                Address: {{ $company->company_address }}
            </th>
        </tr>
        <tr>
            <th colspan='5'>
                Account Ledger
            </th>
        </tr>
        <tr>
            <th colspan='5'>
                Account Group Name :{{ $account_group_list->account_group_name }}
            </th>
        </tr>
        <tr>
            <th colspan='5'>
                From : {!! $formDate . ' to ' . $toDate !!}
            </th>
        </tr>
        <tr>
            <th>Sl No. </th>
            <th>Party Name/ Ledger Name </th>
            <th>Mobile Number </th>
            <th>Debit(Dr)</th>
            <th>Credit(Cr)</th>
        </tr>
    </thead>

    <tbody>
        <?php
            $number = 0;
        ?>
        @foreach ($groupAccount_ledger as $key => $item)
        <?php
            $i = 0;
            $x = 0;

            $account_tran = App\AccountLedgerTransaction::where('ledger_id', $item->id)
                // ->whereBetween('date', [$formDate, $toDate])
                //->where('date', '>=', $formDate)
                ->where('date', '<=', $toDate)
                ->get()
                ->unique('account_ledger__transaction_id');
            $result = $account_tran->sum('debit') - $account_tran->sum('credit');
            if ($result > 1) {
                $dr += $result;
            } else {
                $cr += $result;
            }
        ?>
        @if($filter == 'filter' && $result != 0)
        <tr>
            <td>{{$number += 1}}</td>
            <td>{{ $item->account_name }}</td>
            <td>{{ $item->account_ledger_phone ?? 'N/A' }}</td>
            @if ($result > 0)
                <td>{{ new_number_format($result) }} </td>
            @else
                <td>{{ '-' }}</td>
            @endif
            @if ($result < 0)
                <td>
                    {{ new_number_format($result * -1) }} </td>
            @else
                <td>
                    {{ '-' }}</td>
            @endif
        </tr>
      
        @endif
        
        @if($filter == 'all')
         <tr>
            <td>{{$number += 1}}</td>
            <td>{{ $item->account_name }}</td>
            <td>{{ $item->account_ledger_phone ?? 'N/A' }}</td>
            @if ($result > 0)
                <td>{{ new_number_format($result) }} </td>
            @else
                <td>{{ '-' }}</td>
            @endif
            @if ($result < 0)
                <td>{{ new_number_format($result * -1) }} </td>
            @else
                <td>{{ '-' }}</td>
            @endif
        </tr>
        @endif
    @endforeach
    
    @if($account_group_list->groupUnders->isNotEmpty())
        @foreach($account_group_list->groupUnders as $group_under)
            <?php
                $account_group_ids = $group_under->get_all_under_group_id($group_under);

                $account_tran_ = App\AccountLedgerTransaction::whereIn('ledger_id', function($query)use($account_group_ids){
                    return $query->from('account_ledgers')->select("id")->whereIn('account_group_id', $account_group_ids);
                })
                ->where('date', '<=', $toDate)
                ->get()
                ->unique('account_ledger__transaction_id');
                $result = $account_tran_->sum('debit') - $account_tran_->sum('credit');
                if ($result > 1) {
                    $dr += $result;
                } else {
                    $cr += $result;
                }
            ?>                                    
            <tr>
                <td>{{$number += 1}}</td>
                <td>{{ $group_under->account_group_name }}</td>
                <td>{{ $group_under->account_ledger_phone ?? 'N/A' }}</td>
                @if ($result > 0)
                    <td>{{ new_number_format($result) }} </td>
                @else
                    <td>{{ '-' }}</td>
                @endif
                @if ($result < 0)
                    <td>
                        {{ new_number_format($result * -1) }} </td>
                @else
                    <td>
                        {{ '-' }}</td>
                @endif
            </tr>
        @endforeach                                
    @endif
    <tr>
        <td colspan="3" class="text-right">Grand Total</td>
        <td width="30%"
            style="text-align:right;font-size: x-large;padding: 5px 5px;">
            {{ new_number_format($dr) }} </td>
        <td d width="30%"
            style="text-align:right;font-size: x-large;padding: 5px 5px;">
            {{ new_number_format(-1 * $cr) }} </td>
    </tr>
    </tbody>
</table>