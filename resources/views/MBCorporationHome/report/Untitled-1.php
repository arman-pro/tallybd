@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Day Book</h3>
    <div class="row">



        <form action="{{ url('/day_book_report') }}" method="get">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">Created By</label>
                        <div>
                            <select name="created_by" id="" class="form-control">
                                <option value="{{ null }}">--select user--</option>
                                @forelse ($users as $user)
                                <option value="{{ $user->id }}" {{ request()->created_by == $user->id ? 'Selected' : ' '
                                    }}>{{ $user->name }}
                                </option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">From :</label>
                        <div>
                            <input type="Date" class="form-control" name="form_date" value='{{ request()->form_date }}'>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">To :</label>
                        <div>
                            <input type="Date" class="form-control" name="to_date" value='{{ request()->to_date }}'>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="text-align: center;">
                    <br>
                    <button type="submit" class="btn btn-success"
                        style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
                </div>
            </div>
        </form>


        <script lang='javascript'>
            function printData() {
                    var print_ = document.getElementById("main_table");
                    win = window.open("");
                    win.document.write(print_.outerHTML);
                    win.print();
                    win.close();
                }
        </script>
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <style type="text/css">
                .source_file_list {
                    height: 35px;
                    float: right;
                    background-color: #99A3A4;

                    padding: 5px;
                }

                .source_file_list a {
                    text-decoration: none;
                    padding: 5px 20px;
                    color: #fff;
                    font-size: 18px;

                }

                .source_file_list a:hover {
                    background-color: #D6DBDF;
                    color: #fff;
                }
            </style>
            {{-- <div class="source_file_list">
                <a style="color: #fff;" type="sumit" onclick="printData()">Print</a>
                <a href="">PDF</a>
                <a href="">Excal</a>
            </div> --}}
        </div>
        <div class="col-md-12" style="text-align: center;">
            <br>
            <table class="table" style="border: 1px solid #eee;text-align: center;margin-left: 20px;margin-right: 50px;"
                id="example">
                <tr>
                    <td colspan="7" style="text-align: center;">
                        @php
                        $form = null;
                        $to = null;
                        if (request()->form_date && request()->to_date) {
                        $form = date('Y-m-d', strtotime(request()->form_date));
                        $to = date('Y-m-d', strtotime(request()->to_date));
                        }
                        $company = App\Companydetail::first();

                        @endphp


                        <h3 style="font-weight: 800;">{{ $company->company_name }}</h3>
                        <p>{{ $company->company_address }}, Tel: {{ $company->phone }}, Call:
                            {{ $company->mobile_number }}</p>

                        <h4>Day Book</h4>
                    </td>
                </tr>

                <tr style="font-size:14px;font-weight: 800;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Date</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 120px;">Type</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Vo.No</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 200px; text-align: center;">Account
                    </td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">Debit</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">Credit
                    </td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;text-align: left;">Short
                        Narration</td>
                </tr>

                @foreach ($transactions as $dataRow)
                <tr style="font-size:14px;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                        {{ date('d-m-y', strtotime($dataRow->date)) }}</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                        @php
                        $accountLedgerTransaction =
                        App\AccountLedgerTransaction::where('account_ledger__transaction_id',
                        $dataRow->account_ledger__transaction_id)->first();
                        $tranjection_pur = App\PurchasesAddList::where('product_id_list',
                        $dataRow->account_ledger__transaction_id)->first();
                        $tranjection_pur_return = App\PurchasesReturnAddList::where('product_id_list',
                        $dataRow->account_ledger__transaction_id)->first();
                        $tranjection_sale_return = App\SalesReturnAddList::where('product_id_list',
                        $dataRow->account_ledger__transaction_id)->first();
                        $tranjection_sale = App\SalesAddList::where('product_id_list',
                        $dataRow->account_ledger__transaction_id)->first();
                        $tranjection_recevie = App\Receive::where('vo_no',
                        $dataRow->account_ledger__transaction_id)->first();
                        $tranjection_payment = App\Payment::where('vo_no',
                        $dataRow->account_ledger__transaction_id)->first();
                        $tranjection_con = App\Journal::where('vo_no', $dataRow->account_ledger__transaction_id)
                        ->where('page_name', 'contra')
                        ->first();
                        $tranjection_jo = App\Journal::where('vo_no', $dataRow->account_ledger__transaction_id)
                        ->where('page_name', 'journal')
                        ->first();
                        $empoyee_jour = App\EmployeeJournal::where('vo_no', $dataRow->account_ledger__transaction_id)
                        // where('page_name','journal')
                        ->first();

                        if ($tranjection_pur) {
                        echo 'Pur';
                        } elseif ($tranjection_pur_return) {
                        echo 'Pur-Re';
                        } elseif ($tranjection_sale_return) {
                        echo 'Sale-Re';
                        } elseif ($tranjection_sale) {
                        echo 'Sale';
                        } elseif ($tranjection_recevie) {
                        echo 'Rec';
                        } elseif ($tranjection_payment) {
                        echo 'Pay';
                        } elseif ($tranjection_con) {
                        echo 'Con';
                        } elseif ($tranjection_jo) {
                        echo 'Jon';
                        } elseif ($empoyee_jour) {
                        echo 'EmpJon';
                        } elseif ($accountLedgerTransaction) {
                        echo 'A/C Opening';
                        }
                        @endphp
                    </td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                        {{ $dataRow->account_ledger__transaction_id }}
                    </td>
                    <td colspan="3"
                        style="border-right: 1px solid #eee;padding: 5px 5px;width: 200px; text-align: left;">

                        <table class="table" style="font-size: 12px;">
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">
                                        @php
                                        if($tranjection_pur){

                                            echo $dataRow->accountName->account_name.'</br>';
                                            $itemDetails
                                            =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                                            foreach($itemDetails as $itemDetails_row){

                                            $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                                            echo $item_row->name." - ".$itemDetails_row->qty."
                                            (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                                        }

                                        }elseif($tranjection_pur_return){

                                        echo $dataRow->accountName->account_name.'</br>';
                                        $itemDetails
                                        =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                                        foreach($itemDetails as $itemDetails_row){

                                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                                        echo $item_row->name." - ".$itemDetails_row->qty."
                                        (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                                        }
                                        }elseif($tranjection_sale_return){

                                        echo $dataRow->accountName->account_name.'</br>';
                                        $itemDetails
                                        =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                                        foreach($itemDetails as $itemDetails_row){

                                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                                        echo $item_row->name." - ".$itemDetails_row->qty."
                                        (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                                        }
                                        }elseif($tranjection_sale){

                                        echo $dataRow->accountName->account_name.'</br>';
                                        $itemDetails
                                        =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                                        foreach($itemDetails as $itemDetails_row){
                                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                                        echo $item_row->name." - ".$itemDetails_row->qty."
                                        (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                                        }
                                        }elseif($tranjection_recevie){
                                        echo $tranjection_recevie->paymentMode->account_name .'</br>';
                                        echo $tranjection_recevie->accountMode->account_name;
                                        }elseif($tranjection_payment){

                                        echo $tranjection_payment->paymentMode->account_name .'</br>';
                                        echo $tranjection_payment->accountMode->account_name;
                                        }elseif($tranjection_con){
                                        $under_journal =
                                        App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                        foreach($under_journal as $under_journal_row){
                                        echo optional($under_journal_row->ledger)->account_name. '</br>';
                                        }
                                        }elseif($tranjection_jo){

                                        $under_journal =
                                        App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                        foreach($under_journal as $under_journal_row){
                                        echo optional($under_journal_row->ledger)->account_name.'</br>';
                                        }
                                        }
                                        elseif ($empoyee_jour) {
                                        $under_journal =
                                        App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                        foreach($under_journal as $under_journal_row){

                                        echo optional($under_journal_row->ledger)->account_name??' '."</br>";
                                        echo optional($under_journal_row->employee)->name??'-'."</br>";
                                        }

                                        }
                                        elseif($accountLedgerTransaction){
                                        echo "A/C Opening";
                                        };

                                        @endphp
                                    </td>


                                    @if ($tranjection_con)
                                    @php
                                        $under_contra =
                                        App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                    @endphp
                                    <td style="text-align: right;">
                                        @foreach ($under_contra->where('drcr', 'Dr') as $under_contra_row)

                                        {{ $under_contra_row->amount != 0? number_format($under_contra_row->amount,2):''
                                        }} <br>
                                        @endforeach
                                    </td>
                                    <td style="text-align: right;padding:20px 5px">
                                        @foreach ($under_contra->where('drcr', 'Cr') as $under_contra_row)

                                        {{ $under_contra_row->amount != 0? number_format($under_contra_row->amount,2):''
                                        }} <br>
                                        @endforeach
                                    </td>
                                    @elseif($tranjection_jo)
                                    @php
                                    $under_Journal =
                                    App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                    @endphp
                                    <td style="text-align: right;">
                                        @foreach ($under_Journal->where('drcr', 'Dr') as $under_journal_row)

                                        {{ $under_journal_row->amount != 0?
                                        number_format($under_journal_row->amount,2):'' }} <br>
                                        @endforeach
                                    </td>
                                    <td style="text-align: right;padding:20px 5px">
                                        @foreach ($under_Journal->where('drcr', 'Cr') as $under_journal_row)

                                        {{ $under_journal_row->amount != 0?
                                        number_format($under_journal_row->amount,2):'' }} <br>
                                        @endforeach
                                    </td>

                                    @else
                                    <td style="text-align: right;"> {{ $dataRow->debit != 0 ?
                                        number_format($dataRow->debit,2):' ' }}</td>
                                    <td style="text-align: right;padding:20px 5px"> {{ $dataRow->credit !=0 ?
                                        number_format($dataRow->credit,2):' ' }}</td>
                                    @endif
                                </tr>


                            </tbody>
                        </table>

                        {{-- @php
                        if($tranjection_pur){

                        echo $dataRow->accountName->account_name.'</br>';
                        $itemDetails
                        =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                        foreach($itemDetails as $itemDetails_row){

                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        echo $item_row->name." - ".$itemDetails_row->qty."
                        (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                        }

                        }elseif($tranjection_pur_return){

                        echo $dataRow->accountName->account_name.'</br>';
                        $itemDetails
                        =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                        foreach($itemDetails as $itemDetails_row){

                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        echo $item_row->name." - ".$itemDetails_row->qty."
                        (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                        }
                        }elseif($tranjection_sale_return){

                        echo $dataRow->accountName->account_name.'</br>';
                        $itemDetails
                        =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                        foreach($itemDetails as $itemDetails_row){

                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        echo $item_row->name." - ".$itemDetails_row->qty."
                        (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                        }
                        }elseif($tranjection_sale){

                        echo $dataRow->accountName->account_name.'</br>';
                        $itemDetails
                        =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                        foreach($itemDetails as $itemDetails_row){
                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        echo $item_row->name." - ".$itemDetails_row->qty."
                        (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price."</br>";

                        }
                        }elseif($tranjection_recevie){
                        echo $tranjection_recevie->paymentMode->account_name .'</br>';
                        echo $tranjection_recevie->accountMode->account_name;
                        }elseif($tranjection_payment){

                        echo $tranjection_payment->paymentMode->account_name .'</br>';
                        echo $tranjection_payment->accountMode->account_name;
                        }elseif($tranjection_con){
                        $under_journal =
                        App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                        foreach($under_journal as $under_journal_row){
                        echo optional($under_journal_row->ledger)->account_name. '</br>';
                        }
                        }elseif($tranjection_jo){

                        $under_journal =
                        App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                        foreach($under_journal as $under_journal_row){
                        echo optional($under_journal_row->ledger)->account_name.'</br>';
                        }
                        }
                        elseif ($empoyee_jour) {
                        $under_journal =
                        App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                        foreach($under_journal as $under_journal_row){

                        echo optional($under_journal_row->ledger)->account_name??' '."</br>";
                        echo optional($under_journal_row->employee)->name??'-'."</br>";
                        }

                        }
                        elseif($accountLedgerTransaction){
                        echo "A/C Opening";
                        };
                        @endphp --}}
                    </td>
                    {{-- @if ($tranjection_con)
                    @php
                    $under_contra =
                    App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                    @endphp
                    <td style="padding: 5px 5px;border-right: 1px solid #ebebeb;text-align: right;">
                        @foreach ($under_contra->where('drcr', 'Dr') as $under_contra_row)

                        {{ $under_contra_row->amount != 0? number_format($under_contra_row->amount,2):'' }} <br>
                        @endforeach
                    </td>
                    <td style="border-right: 1px solid #ebebeb;text-align: right;">
                        @foreach ($under_contra->where('drcr', 'Cr') as $under_contra_row)
                        {{ $under_contra_row->amount != 0? number_format($under_contra_row->amount,2):'' }} <br>
                        @endforeach
                    </td>

                    @elseif ($tranjection_jo)
                    @php
                    $under_Journal =
                    App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                    @endphp
                    <td style="padding: 5px 5px;border-right: 1px solid #ebebeb;text-align: right;">
                        @foreach ($under_Journal->where('drcr', 'Dr') as $under_journal_row)

                        {{ $under_journal_row->amount != 0? number_format($under_journal_row->amount,2):'' }} <br>
                        @endforeach
                    </td>
                    <td style="border-right: 1px solid #ebebeb;text-align: right;">
                        @foreach ($under_Journal->where('drcr', 'Cr') as $under_journal_row)
                        {{ $under_journal_row->amount != 0? number_format($under_journal_row->amount,2):'' }} <br>
                        @endforeach
                    </td>

                    @elseif($empoyee_jour)
                    @php
                    $under_empjournal =
                    App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                    @endphp

                    <td style="padding: 5px 5px;border-right: 1px solid #ebebeb;text-align: right;">
                        @foreach ($under_empjournal->where('drcr', 1) as $empjournal_row)
                        {{ $empjournal_row->amount != 0? number_format($empjournal_row->amount,2):'' }} <br>
                        @endforeach
                    </td>
                    <td style="border-right: 1px solid #ebebeb;text-align: right;">
                        @foreach ($under_empjournal->where('drcr', 2) as $empjournal_row)
                        {{ $empjournal_row->amount != 0? number_format($empjournal_row->amount,2):'' }} <br>
                        @endforeach
                    </td>


                    @else
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
                        {{ $dataRow->debit != 0? number_format($dataRow->debit,2):'' }}
                    </td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
                        {{ $dataRow->credit !=0? number_format($dataRow->credit,2):' ' }}
                    </td>
                    @endif --}}

                </tr>
                @endforeach



            </table>
        </div>

    </div>
</div>
@endsection
