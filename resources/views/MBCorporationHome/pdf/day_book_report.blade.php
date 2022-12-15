@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Day Book Report")

@push('css')
<style>
    body,html {
        /* width: 8.3in;
        height: 11.7in; */
        margin: 0;
        padding: 0;
    }
    .content_area {
        /*width: 8.3in;*/
        /*height: 11.7in;*/
        /* margin: auto; */
        /*display: block;*/
    }

     @page {
        margin: 0.3in;
        margin: 10px;
    } 
    
    .pdf-table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
    }

    .pdf-table tr, .pdf-table  th, .pdf-table  td, .pdf-table thead {
        border: 1px solid black;
        padding: 5px 3px;
    }

    .text-center {
        text-align: center;
    }
</style>
@endpush

@section('pdf_content')
<div class="container-fluid">
<div class="row">
    <div class="col-sm-12 p-0 content_area" >
        <div>
        <table class="pdf-table" id="main_table">
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
                    <th>Date</th>
                    <th>Type</th>
                    <th>Vch.No</th>
                    <th>Account</th>
                    <th>Debit(Tk)</th>
                    <th>Credit(TK)</th>
                    <th>created By</th>
                </tr>
            </thead>
            <tbody>
            @foreach($transactions as $dataRow)               
                <tr style="font-size:14px;">
                    <td class="text-center">{{ date('d-m-y',
                        strtotime($dataRow->date))}}</td>
                    <td class="text-center">
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
                    <td class="text-center">
                        {{$dataRow->account_ledger__transaction_id}}
                    </td>
                    <td>
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
                    <td class="text-left">
                        @foreach ($under_contra->where('drcr', 'Dr') as $under_contra_row)
                        <?php
                            $total_amount_dr += $under_contra_row->amount ?? 0;
                        ?>
                        {{ $under_contra_row->amount != 0? new_number_format($under_contra_row->amount):'' }} <br>
                        @endforeach
                    </td>
                    <td>
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
                    <td>
                        @foreach ($under_Journal->where('drcr', 'Dr') as $under_journal_row)
                        <?php
                            $total_amount_dr += $under_journal_row->amount ?? 0;
                        ?>
                        {{ $under_journal_row->amount != 0? new_number_format($under_journal_row->amount):'' }} <br>
                        @endforeach
                    </td>
                    <td>
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

                    <td>
                        @foreach ($under_empjournal->where('drcr', 1) as $empjournal_row)
                        <?php
                            $total_amount_dr += $empjournal_row->amount ?? 0;
                        ?>
                        {{ $empjournal_row->amount != 0? new_number_format($empjournal_row->amount):'' }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($under_empjournal->where('drcr', 2) as $empjournal_row)
                        <?php
                            $total_amount_cr += $empjournal_row->amount ?? 0;
                        ?>
                        {{ $empjournal_row->amount != 0? new_number_format($empjournal_row->amount):'' }} <br>
                        @endforeach
                    </td>
                    @elseif($tranjection_pur)
                    
                    <td>
                        
                    </td>
                    <td>
                        <?php
                            $total_amount_cr += $tranjection_pur->main_ledger()->credit ?? 0;
                        ?>
                        {{new_number_format($tranjection_pur->main_ledger()->credit ?? "0")}}
                    </td>
                   
                    @elseif($tranjection_sale)
                        <?php
                            $total_amount_dr += $tranjection_sale->main_ledger()->debit ?? 0;
                        ?>
                    <td>
                        {{new_number_format($tranjection_sale->main_ledger()->debit ?? 0)}} 
                    </td>
                    <td>
                        
                    </td>
                    @elseif($salary_payment)
                        <?php
                            $total_amount_dr += $dataRow->credit ?? 0;
                        ?>
                    <td>
                        {{ $dataRow->credit != 0 ? new_number_format($dataRow->credit):'' }}
                    </td>
                    <td>
                        
                    </td> 
                    @else
                        <?php
                            $total_amount_dr += $dataRow->debit ?? 0;
                        ?>
                        <?php
                            $total_amount_cr += $dataRow->credit ?? 0;
                        ?>
                    <td>
                        {{ $dataRow->debit != 0? new_number_format($dataRow->debit):'' }}
                    </td>
                    <td>
                        {{ $dataRow->credit !=0? new_number_format($dataRow->credit):' ' }}
                    </td>
                    @endif
                    <td class="text-center">
                        {{ optional($dataRow->createdBy)->name??' ' }}
                    </td>
                </tr>

        @endforeach
            </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="font-size:16px;text-align:right"><strong>Total </strong></td>
                <td style="font-size:16px;text-align:right">
                    <strong>{{new_number_format($total_amount_dr)}}</strong>
                </td>
                <td style="font-size:16px;text-align:center">
                    <strong>{{new_number_format($total_amount_cr)}}</strong>
                </td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
        </table>
    </div>
    </div>
</div>
</div>
@endsection