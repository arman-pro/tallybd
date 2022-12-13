@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Day Book Report")


@section('admin_content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <form action="{{url('/day_book_report')}}" method="get">
            <input type="hidden" name="report" value="1" />
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Day Book</h4>
                </div>
                <div class="card-body">                
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cono1" class="control-label col-form-label">Transaction Type</label>
                                <div>
                                    <select name="type_name" id="" class="form-control">
                                        <option value="" hidden>Select Transaction Type</option>
                                        {{-- <option value="" @if(request()->type_name == '') selected @endif>Select All</option> --}}
                                        <option value="Pr" {{ request()->type_name == 'Pr' ? 'Selected': ' ' }}>Purchase
                                        </option>
                                        <option value="PuR" {{ request()->type_name == 'PuR' ? 'Selected': ' ' }}>Purchase
                                            Return</option>
                                        <option value="Sl" {{ request()->type_name == 'Sl' ? 'Selected': ' ' }}>Sale
                                        </option>
                                        <option value="SlR" {{ request()->type_name == 'SlR' ? 'Selected': ' ' }}>Sale
                                            Return</option>
                                        <option value="Re" {{ request()->type_name == 'Re' ? 'Selected': ' ' }}>Receive
                                        </option>
                                        <option value="Pa" {{ request()->type_name == 'Pa' ? 'Selected': ' ' }}>Payment
                                        </option>
                                        <option value="Co" {{ request()->type_name == 'Co' ? 'Selected': ' ' }}>Contra
                                        </option>
                                        <option value="Jo" {{ request()->type_name == 'Jo' ? 'Selected': ' ' }}>Journal
                                        </option>
                                        <option value="EJo" {{ request()->type_name == 'EJo' ? 'Selected': ' ' }}>Employee
                                            Journal</option>
    
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cono1" class="control-label col-form-label">Created By</label>
                                <div>
                                    <select name="created_by" id="" class="form-control">
                                        <option value="" hidden>Select User</option>
                                        <option value="">All User</option>
                                        {{-- <option value="" @if(request()->created_by == '') selected @endif >Select All</option> --}}
                                        @forelse ($users as $user)
                                        <option value="{{ $user->id }}" @if(request()->created_by == $user->id) selected @endif>{{ $user->name }}</option>
                                        @empty    
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cono1" class="control-label col-form-label">From</label>
                                <div>
                                    <input type="Date" class="form-control" name="form_date"
                                        value='{{  request()->form_date}}'>
                                </div>
                            </div>
                        </div>
    
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cono1" class="control-label col-form-label">To</label>
                                <div>
                                    <input type="Date" class="form-control" name="to_date" value='{{  request()->to_date}}'>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success btn-lg fw-bold text-light" ><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    {{-- report start --}}
    @if(request()->report)
    <style type="text/css" media="print">
        /* @media print{@page {size: landscape}} */
    </style>
    <div style="background: #fff;">
        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <div class="col-md-12" style="text-align: center;">
                    <table class="table" style="border: 1px solid #a59c9c;text-align: center;border-collapase:collapse;" id="main_table">
                        <thead>
                            <tr>
                                <th colspan="7" style="text-align: center;">
                                    @php
                                    $from = null;
                                    $to = null;
                                    if(request()->form_date && request()->to_date){
                                    $from = date('d-m-Y', strtotime(request()->form_date));
                                    $to = date('d-m-Y', strtotime(request()->to_date));
                                    }
                                    // $company = App\Companydetail::first();
                                    $total_amount_dr=0;
                                    $total_amount_cr=0;
    
                                    @endphp
    
    
                                    <h3 style="font-weight: 800;padding:0;margin:0;">{{$company->company_name}}</h3>
                                    <strong style=>{{$company->company_address}}<br>{{$company->phone}} Call:
                                        {{$company->mobile_number}}</strong><br>
                                    <strong>Day Book</strong><br>
                                    <strong>From : {{$from}} TO : {{ $to }} </strong>
                                </th>
                            </tr>
    
                            <tr style="font-size:14px;font-weight: 800;">
                                <th style="border: 1px solid #a59c9c;padding: 5px 5px;width:80px ;">Date</th>
                                <th style="border: 1px solid #a59c9c;padding: 5px 5px;width:80px ;">Type</th>
                                <th style="border: 1px solid #a59c9c;padding: 5px 5px;width: 80px;">Vch.No</th>
                                <th style="border: 1px solid #a59c9c;padding: 5px 5px;width: 300px; text-align: center;">Account</th>
                                <th style="border: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: center;">Debit(Tk)</th>
                                <th style="border: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: center;">Credit(TK)</th>
                                <th style="border: 1px solid #a59c9c;padding: 5px 5px;width:150px ;text-align: center;">created By</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $dataRow)
                       
                       
                            <tr style="font-size:14px;">
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;border-bottom:1px solid #a59c9c">{{ date('d-m-y',
                                    strtotime($dataRow->date))}}</td>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;border-bottom:1px solid #a59c9c">
                                    @php
                                    $accountLedgerTransaction=
                                    App\AccountLedgerTransaction::where('account_ledger__transaction_id',$dataRow->account_ledger__transaction_id)->
                                    first();
                                    $tranjection_pur =
                                    App\PurchasesAddList::with(['account_ledger_transaction'])->where('product_id_list',$dataRow->account_ledger__transaction_id)
                                    ->first();
                                    $tranjection_pur_return =
                                    App\PurchasesReturnAddList::where('product_id_list',$dataRow->account_ledger__transaction_id)->
                                    first();
                                    $tranjection_sale_return =
                                    App\SalesReturnAddList::where('product_id_list',$dataRow->account_ledger__transaction_id)
                                    ->first();
                                    $tranjection_sale =
                                    App\SalesAddList::where('product_id_list',$dataRow->account_ledger__transaction_id)
                                    ->first();
                                    $tranjection_recevie =
                                    App\Receive::where('vo_no',$dataRow->account_ledger__transaction_id)
                                    ->first();
                                    $tranjection_payment =
                                    App\Payment::where('vo_no',$dataRow->account_ledger__transaction_id)
                                    ->first();
                                    $tranjection_con =
                                    App\Journal::where('vo_no',$dataRow->account_ledger__transaction_id)->
                                    where('page_name','contra')->first();
                                    $tranjection_jo= App\Journal::where('vo_no',$dataRow->account_ledger__transaction_id)->
                                    where('page_name','journal')->first();
                                    $empoyee_jour= App\EmployeeJournal::where('vo_no',$dataRow->account_ledger__transaction_id)
                                    ->first();
                                    $salary_generate= App\Salary::where('vo_no',$dataRow->account_ledger__transaction_id)
                                    ->first();
                                    $salary_payment= App\SalaryPayment::where('vo_no',$dataRow->account_ledger__transaction_id)
                                    ->first();
    
                                    if($tranjection_pur){
                                    echo "Pur";
                                    }elseif($tranjection_pur_return){
                                    echo "Pur-Re";
                                    }elseif($salary_generate){
                                    echo "Sa/Gen";
    
                                    }elseif($salary_payment){
                                    echo "Sa/Pay";
                                    }
                                    elseif($tranjection_sale_return){
                                    echo "Sale-Re";
                                    }elseif($tranjection_sale){
                                    echo "Sale";
                                    }elseif($tranjection_recevie){
                                    echo "Rec";
                                    }elseif($tranjection_payment){
                                    echo "Pay";
                                    }elseif($tranjection_con){
                                    echo "Con";
                                    }elseif($tranjection_jo){
                                    echo "Jon";
                                    }elseif($empoyee_jour){
                                    echo "EmpJon";
                                    }elseif($accountLedgerTransaction){
                                    echo "A/C Opening";
                                    };
                                    @endphp
                                </td>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;border-bottom:1px solid #a59c9c">
                                    {{$dataRow->account_ledger__transaction_id}}
                                </td>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 200px; text-align: left;border-bottom:1px solid #a59c9c">
                                    @php
    
                                    if($tranjection_pur){
                                   
                                    echo ($tranjection_pur->main_ledger()->account_name ?? "N/A") .'</br>';
                                    $itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                                    foreach($itemDetails as $itemDetails_row){
                                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                                        echo $item_row->name." , ".$itemDetails_row->qty."
                                        (".optional($item_row->unit)->name.")@ ".$itemDetails_row->price."</br>";
                                    }
                                    echo ($tranjection_pur->expense_ledger()->accountName->account_name ?? "").' @ '.($tranjection_pur->expense_ledger()->debit ?? 0);
                                   echo $tranjection_pur->delivered_to_details ? "(".$tranjection_pur->delivered_to_details.")" : "";
                                   
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
    
                                    echo $tranjection_sale->main_ledger()->accountName->account_name.'</br>';
                                    $itemDetails
                                    =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                                    foreach($itemDetails as $itemDetails_row){
                                    $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                                    echo $item_row->name." , ".$itemDetails_row->qty."
                                    (".optional($item_row->unit)->name.")@ ".$itemDetails_row->price."</br>";
    
                                    }
                                    if($tranjection_sale->expense_ledger()){
                                        echo ($tranjection_sale->expense_ledger()->accountName->account_name ?? null) . ' @ '.($tranjection_sale->expense_ledger()->credit ?? 0);
                                        
                                    }
                                    echo $tranjection_sale->delivered_to_details ? "(".$tranjection_sale->delivered_to_details . ")" : "";
                                    
                                    }elseif($tranjection_recevie){
                                    echo $tranjection_recevie->paymentMode->account_name .'</br>';
                                    echo $tranjection_recevie->accountMode->account_name ;
                                        if($tranjection_recevie->description) echo ' ('.$tranjection_recevie->description.')';
                                    }elseif($tranjection_payment){
                                        echo $tranjection_payment->paymentMode->account_name .'</br>';
                                        echo $tranjection_payment->accountMode->account_name; 
                                        if($tranjection_payment->description) echo ' ('.$tranjection_payment->description.')';
                                    }elseif($tranjection_con){
                                    $under_journal =
                                    App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                    foreach($under_journal as $under_journal_row){
                                    echo optional($under_journal_row->ledger)->account_name."</br>";
                                    }
                                    }elseif($tranjection_jo){
    
                                    $under_journal =
                                    App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                    foreach($under_journal as $under_journal_row){
                                        echo optional($under_journal_row->ledger)->account_name;
                                        if($under_journal_row->note) echo " (".$under_journal_row->note.")";
                                        echo "<br/>";
                                    }
                                    }
                                    elseif ($empoyee_jour) {
                                    $under_journal =
                                    App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                    foreach($under_journal as $under_journal_row){
                                    echo optional($under_journal_row->ledger)->account_name."</br>";
                                    echo optional($under_journal_row->employee)->name;
                                    }
    
                                    }
                                    elseif($salary_generate){
                                    foreach ($salary_generate->details as $key => $data) {
                                    echo optional($data->employee)->name."</br>";
                                    } ;
                                    }
                                    elseif($salary_payment){
                                    echo optional($salary_payment->employee)->name."</br>";
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
                                <td style="padding: 5px 5px;border-right: 1px solid #ebebeb;text-align: right;border:1px solid #a59c9c">
                                    @foreach ($under_contra->where('drcr', 'Dr') as $under_contra_row)
                                    <?php
                                        $total_amount_dr += $under_contra_row->amount ?? 0;
                                    ?>
                                    {{ $under_contra_row->amount != 0? new_number_format($under_contra_row->amount):'' }} <br>
                                    @endforeach
                                </td>
                                <td style="border-right: 1px solid #ebebeb;text-align: right;padding:20px 5px;border:1px solid #a59c9c">
                                    @foreach ($under_contra->where('drcr', 'Cr') as $under_contra_row)
                                    <?php
                                        $total_amount_cr += $under_contra_row->amount ?? 0;
                                    ?>
                                    {{ $under_contra_row->amount != 0? new_number_format($under_contra_row->amount):'' }} <br>
                                    @endforeach
                                </td>
    
                                @elseif ($tranjection_jo)
                                @php
                                $under_Journal =
                                App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                @endphp
                                <td style="padding: 5px 5px;border-right: 1px solid #ebebeb;text-align: right;border:1px solid #a59c9c">
                                    @foreach ($under_Journal->where('drcr', 'Dr') as $under_journal_row)
                                    <?php
                                        $total_amount_dr += $under_journal_row->amount ?? 0;
                                    ?>
                                    {{ $under_journal_row->amount != 0? new_number_format($under_journal_row->amount):'' }} <br>
                                    @endforeach
                                </td>
                                <td style="border-right: 1px solid #ebebeb;text-align: right;padding:20px 5px;border:1px solid #a59c9c">
                                    @foreach ($under_Journal->where('drcr', 'Cr') as $under_journal_row)
                                    <?php
                                        $total_amount_cr += $under_journal_row->amount ?? 0;
                                    ?>
                                    {{ $under_journal_row->amount != 0? new_number_format($under_journal_row->amount):'' }} <br>
                                    @endforeach
                                </td>
    
                                @elseif($empoyee_jour)
                                @php
                                $under_empjournal =
                                App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                                @endphp
    
                                <td style="border-right: 1px solid #ebebeb;text-align: right;border:1px solid #a59c9c">
                                    @foreach ($under_empjournal->where('drcr', 1) as $empjournal_row)
                                    <?php
                                        $total_amount_dr += $empjournal_row->amount ?? 0;
                                    ?>
                                    {{ $empjournal_row->amount != 0? new_number_format($empjournal_row->amount):'' }} <br>
                                    @endforeach
                                </td>
                                <td style="padding:25px 5px;border-right: 1px solid #ebebeb;text-align: right;border:1px solid #a59c9c">
                                    @foreach ($under_empjournal->where('drcr', 2) as $empjournal_row)
                                    <?php
                                        $total_amount_cr += $empjournal_row->amount ?? 0;
                                    ?>
                                    {{ $empjournal_row->amount != 0? new_number_format($empjournal_row->amount):'' }} <br>
                                    @endforeach
                                </td>
                                @elseif($tranjection_pur)
                                
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    
                                </td>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    <?php
                                        $total_amount_cr += $tranjection_pur->main_ledger()->credit ?? 0;
                                    ?>
                                    {{new_number_format($tranjection_pur->main_ledger()->credit ?? "0")}}
                                </td>
                               
                                @elseif($tranjection_sale)
                                    <?php
                                        $total_amount_dr += $tranjection_sale->main_ledger()->debit ?? 0;
                                    ?>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    {{new_number_format($tranjection_sale->main_ledger()->debit ?? 0)}} 
                                </td>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    
                                </td>
                                @elseif($salary_payment)
                                    <?php
                                        $total_amount_dr += $dataRow->credit ?? 0;
                                    ?>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    {{ $dataRow->credit != 0 ? new_number_format($dataRow->credit):'' }}
                                </td>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    
                                </td> 
                                @else
                                    <?php
                                        $total_amount_dr += $dataRow->debit ?? 0;
                                    ?>
                                    <?php
                                        $total_amount_cr += $dataRow->credit ?? 0;
                                    ?>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    {{ $dataRow->debit != 0? new_number_format($dataRow->debit):'' }}
                                </td>
                                <td style="border-right: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: right;border:1px solid #a59c9c">
                                    {{ $dataRow->credit !=0? new_number_format($dataRow->credit):' ' }}
                                </td>
                                @endif
                                <td style="border-Center: 1px solid #a59c9c;padding: 5px 5px;width: 150px;text-align: Centert;border:1px solid #a59c9c">
                                       {{ optional($dataRow->createdBy)->name??' ' }}
    
                                </td>
    
                            </tr>
            
                    @endforeach
                        </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="font-size:16px;padding: 5px 5px;width: 100px;text-align:right"> Total <strong> </strong></td>
                            <td style="font-size:16px;padding: 5px 5px;width: 100px;text-align:right">{{new_number_format($total_amount_dr)}}<strong> </strong></td>
                            <td style="font-size:16px;padding: 5px 5px;width: 100px;text-align:center">{{new_number_format($total_amount_cr)}}<strong> 
                                </strong></td>
                        </tr>
                    </tfoot>
            
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body text-center">
                    <a href="javascript:void(0);" onclick="printData()" class="btn btn-success fw-bold text-light"><i class="fa fa-print"></i> Print</a>
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary fw-bold text-light"><i class="fas fa-file-pdf"></i> Pdf</a>
                </div>
            </div>
        </div>
    </div>
    @endif
    {{-- report end --}}
    
</div>

@if(request()->report)
<script lang='javascript'>
    function printData(){
        var print_ = document.getElementById("main_table");
        var body = $('body').html();
        $('body').html(print_);
        window.print();
        $('body').html(body);
    }
</script>
@endif
@endsection

@push('css')

@endpush