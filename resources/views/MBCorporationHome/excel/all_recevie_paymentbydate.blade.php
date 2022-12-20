<table>
    <thead>
        <tr>
            <th colspan="3">Receive</th>
        </tr>
        <tr>
            <td>Date</td>
            <td>Account Ledger</td>
            <td>Amount(TK)</td>
        </tr>
    </thead>
    <tbody>
    <?php
        $total_rec = 0;
        $Receive = App\Receive::whereBetween('date',[$formdate,$todate])->get();
    ?>
    @foreach($Receive as $Receive_row)
    <tr>
        <td>{{ date('d-m-Y', strtotime($Receive_row->date)) }}</td>
        <td>
        <?php
            $total_rec += $Receive_row->amount;
            $account_name =
            App\AccountLedger::where('id',$Receive_row->account_name_ledger_id)->first();
        ?>
            {{$account_name->account_name}}
        </td>
        <td>{{new_number_format($Receive_row->amount)}}</td>
    </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total</th>
            <th>{{$total_rec}}.00</th>
        </tr>
    </tfoot>
</table>
<table>
    <thead>
        <tr>
            <th colspan="3">Payment</th>
        </tr>
        <tr>
            <td>Date</td>
            <td>Account Ledger</td>
            <td>Amount(TK)</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $total_pay = 0;
            $Receive = App\Payment::whereBetween('date',[$formdate,$todate])->get();
        ?>
        @foreach($Receive as $Receive_row)
        <tr>
            <td>{{ date('d-m-Y', strtotime($Receive_row->date)) }}</td>
            <td>
            <?php
                $total_pay = $total_pay + $Receive_row->amount;

                $account_name =
                App\AccountLedger::where('id',$Receive_row->account_name_ledger_id)->first();
            ?>
                {{$account_name->account_name}}
            </td>
            <td>{{$Receive_row->amount}}.00</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total</th>
            <th>{{$total_pay}}.00</th>
        </tr>
    </tfoot>
</table>