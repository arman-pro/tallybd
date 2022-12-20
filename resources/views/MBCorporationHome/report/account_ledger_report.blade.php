@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Account Ledger Report")
@push('css')
<style type="text/css">
    
    .table {
        border-collapse: collapse !important;
    }
    @media{
        @page{
            size: A4;
            margin: 0.5in;
        }
        .main_table{
            margin: 0%!
        }
        table header
        {
            /* display: table-header-group; */
        }
    }
    
    @media print {
      .table {
          border-collapse: collapse !important;
      }
    }
</style>
@endpush

@section('admin_content')
<script lang='javascript'>
    function printData(){
        var print_ = document.getElementById("main_table");
        var body = $("body").html();
        $("body").html(print_.outerHTML);
        window.print();
        $("body").html(body);
    }
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Account Ledger</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                    <div class="col-12" id="main_table">
                            <div class="row">
                                @php
                                        
                                    $company = App\Companydetail::first();
                                    $openDr = 0;
                                    $openCr = 0;
                                    $openBalance = 0;
                                    $opening = App\AccountLedgerTransaction::where('ledger_id',$ledger_id)->where('date', '<' ,$formDate
                                        )->get()->unique('account_ledger__transaction_id');
        
                                        if($opening){
                                            $openDr = $opening->sum('debit');
                                            $openCr = $opening->sum('credit');
                                            $openBalance = $openDr - $openCr;
                                        }
                                @endphp

                            <h3 style="font-weight: 800;margin:0;text-align:center;">{{$company->company_name}}</h3>
                            <p style="margin:0;text-align:center;">{{$company->company_address}}<br> {{$company->phone}} Call: {{$company->mobile_number}}</p>

                            <h4 style="margin:0;text-align:center;">Account Ledger</h4>
                            <div class="col-md-12">
                                <p style="margin:0;">
                                    <b>Account/Ladger Name :</b> {{ $ledger->account_name }}
                                    <span class="float-end">
                                        @if($openBalance > 1)
                                            <b>Opening Balance:</b> {{ new_number_format($openBalance)}} (Dr)
                                        @elseif($openBalance < -1) 
                                            <b>Opening Balance:</b> {{ new_number_format($openBalance)}} (Cr) 
                                        @else
                                            <b>Opening Balance: </b>
                                        @endif
                                    </span> 
                                </p>
                                <p style="margin:0;">
                                    <b>Address:</b> {{ $ledger->account_ledger_address}}
                                    <span class="float-end">
                                        <b>From :</b> {{date('d-m-Y', strtotime($formDate))}} <b>To :</b> {{date('d-m-Y', strtotime($toDate))}}
                                    </span>
                                </p>
                                <p style="padding: 0px;margin:0">
                                    <b>Mobile:</b> {{ $ledger->account_ledger_phone }}
                                </p>                                
                            </div>
                        </div>
                    <table class="table table-bordered"  cellspacing="0"
                        style="border: 1px solid #444242;text-align: center;border-collapse:collapse;font-size: 12px;">
                        <thead>
        
                            <tr class="thead" style="font-size:14px;font-weight: 800;width:100%">
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px">Date</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 50px;">Type</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;">Vch.No</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 350px; text-align: left;">Account</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">Debit(TK)</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">Credit(TK)</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: center;">Balance(TK)</td>
                            </tr>
                        </thead>
        
        
                        @php
                        $i=0;
                        $x = 0;
                        $dr = 0;
                        $cr = 0;
                        $newBalance = 0;
                        @endphp
                        @foreach($account_tran as $key=>$account_tran_row)
        
                        <tr style="font-size:12px" >
                            <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px;">
                                @php
                                $accountLedgerTransaction=
                                App\AccountLedgerTransaction::where('account_ledger__transaction_id',$account_tran_row->account_ledger__transaction_id)->
                                whereBetween('date', [$formDate, $toDate])->first();
                                $salary_generate= App\Salary::where('vo_no',$account_tran_row->account_ledger__transaction_id)
                                ->first();
                                if($salary_generate){
                                    echo date('d-m-y', strtotime($salary_generate->salary_date));
                                }else{
                                    echo date('d-m-y', strtotime($accountLedgerTransaction->date));
        
                                }
        
                                @endphp
        
                            </td>
                            <td style="border: 1px solid #444242;padding: 5px 5px;width: 50px;">
                                @php
                                $accountLedgerTransaction=
                                App\AccountLedgerTransaction::where('account_ledger__transaction_id',$account_tran_row->account_ledger__transaction_id)->
                                whereBetween('date', [$formDate, $toDate])->first();
                                $tranjection_pur =
                                App\PurchasesAddList::where('product_id_list',$account_tran_row->account_ledger__transaction_id)->whereBetween('date',
                                [$formDate, $toDate])->first();
                                $tranjection_pur_return =
                                App\PurchasesReturnAddList::where('product_id_list',$account_tran_row->account_ledger__transaction_id)->
                                whereBetween('date', [$formDate, $toDate])->first();
                                $tranjection_sale_return =
                                App\SalesReturnAddList::where('product_id_list',$account_tran_row->account_ledger__transaction_id)->whereBetween('date',
                                [$formDate, $toDate])->first();
                                $tranjection_sale =
                                App\SalesAddList::where('product_id_list',$account_tran_row->account_ledger__transaction_id)->whereBetween('date',
                                [$formDate, $toDate])->first();
                                $tranjection_recevie =
                                App\Receive::where('vo_no',$account_tran_row->account_ledger__transaction_id)->whereBetween('date',
                                [$formDate, $toDate])->first();
                                $tranjection_payment =
                                App\Payment::where('vo_no',$account_tran_row->account_ledger__transaction_id)->whereBetween('date',
                                [$formDate, $toDate])->first();
                                $tranjection_con =
                                App\Journal::where('vo_no',$account_tran_row->account_ledger__transaction_id)->
                                whereBetween('date', [$formDate, $toDate])->where('page_name','contra')->first();
                                $tranjection_jo= App\Journal::where('vo_no',$account_tran_row->account_ledger__transaction_id)->
                                whereBetween('date', [$formDate, $toDate])->where('page_name','journal')->first();
                                $salary_generate= App\Salary::where('vo_no',$account_tran_row->account_ledger__transaction_id)
                                ->first();
                                $salary_payment= App\SalaryPayment::where('vo_no',$account_tran_row->account_ledger__transaction_id)
                                ->first();
                                $empoyee_jour= App\EmployeeJournal::where('vo_no',$account_tran_row->account_ledger__transaction_id)
                                        ->first();
                                if($tranjection_pur){
                                echo "Pur";
                                }elseif($tranjection_pur_return){
                                echo "Pur-Re";
                                }elseif($salary_generate){
                                echo "Salary Generate";
        
                                }elseif($salary_payment){
                                echo "Sa/Pay";
                                }elseif($empoyee_jour){
                                echo "EmpJon";
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
                                }elseif($accountLedgerTransaction){
                                echo "A/C Opening";
                                };
                                @endphp
                            </td>
                            <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;">
                                {{$account_tran_row->account_ledger__transaction_id}}
                            </td>
                            <td style="border: 1px solid #444242;padding: 5px 5px;width: 350px; text-align: left;">
                                @php
        
                                
                                if($tranjection_pur){
                                    //echo $tranjection_pur->product_id_list;
                                    $itemDetails =
                                    App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_pur->product_id_list)->get();
                                    foreach($itemDetails as $itemDetails_row)
                                    {
                                    $item = App\Item::where('id',$itemDetails_row->item_id)->first();
                                    
                                    echo $item->name." , ".$itemDetails_row->qty." (".$item->unit->name.")@ ".$itemDetails_row->price."<br/>";
                                    };
                                    echo $tranjection_pur->expense_ledger_id?$tranjection_pur->ledgerexpanse->account_name." @ ".$tranjection_pur->other_bill."<br/>":""; 
                                    echo $tranjection_pur->delivered_to_details ? "(".$tranjection_pur->delivered_to_details.")" : "";
                                    
        
                                }elseif($tranjection_sale){
                                    $itemDetails =
                                    App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_sale->product_id_list)->get();
                                    foreach($itemDetails as $itemDetails_row)
                                    {
                                    $item = App\Item::where('id',$itemDetails_row->item_id)->first();
                                    echo $item->name." , ".$itemDetails_row->qty." (".$item->unit->name.")@ ".$itemDetails_row->price."<br/>";
                                    };
                                    echo $tranjection_sale->expense_ledger_id?$tranjection_sale->ledgerexpense->account_name." @ ".$tranjection_sale->other_bill."<br/>":""; 
                                    echo $tranjection_sale->delivered_to_details ? "(".$tranjection_sale->delivered_to_details . ")" : "";
                                        
                                    
                                }elseif($tranjection_sale_return){
                                $itemDetails =
                                App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_sale_return->product_id_list)->get();
                                foreach($itemDetails as $itemDetails_row)
                                {
                                $item = App\Item::where('id',$itemDetails_row->item_id)->first();
                                echo $item->name." - ".$itemDetails_row->qty."
                                (".$item->unit->name.") ."." @ ".$itemDetails_row->price." TK"."<br>";
                                };
                                }elseif($tranjection_pur_return){
                                    $itemDetails =
                                    App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_pur_return->product_id_list)->get();
                                    foreach($itemDetails as $itemDetails_row){
                                        $item = App\Item::where('id',$itemDetails_row->item_id)->first();
                                        echo $item->name."-".$itemDetails_row->qty."
                                        (".$item->unit->name.") ."." @ ".$itemDetails_row->price." TK"."<br>";
                                    };
        
                                }elseif($tranjection_recevie){
                                    if(optional($tranjection_recevie->accountMode)->account_name == $ledger->account_name){
                                        echo  optional($tranjection_recevie->paymentMode)->account_name;
                                    }else{
                                        echo optional($tranjection_recevie->accountMode)->account_name;
                                    }
                                    if($tranjection_recevie->description){
                                        echo '<br/>('.$tranjection_recevie->description.')';
                                    }
        
                                }elseif($tranjection_payment){
                                    if(optional($tranjection_payment->accountMode)->account_name == $ledger->account_name){
                                        echo optional($tranjection_payment->paymentMode)->account_name??" ";
                                    }else{
                                        echo optional($tranjection_payment->accountMode)->account_name??" ";
                                    }
                                    if($tranjection_payment->description){
                                        echo '<br/>('.$tranjection_payment->description.')';
                                    }
                                }elseif($tranjection_con){
                                    $aLt_con = App\AccountLedgerTransaction::where('account_ledger__transaction_id',$tranjection_con->vo_no)->where('ledger_id', '!=', $ledger_id)->first();
                                    echo $aLt_con->account_name;
                                    //echo $tranjection_con->page_name;
                                    $note = $tranjection_con->demoDetails->where('ledger_id', $ledger_id)->first();
                                    if($note){
                                        echo '<br/>(' .$note->note.')';
                                    }
                                }elseif($tranjection_jo){
                                    //echo $tranjection_jo->page_name;
                                    $aLt_jo = App\AccountLedgerTransaction::where('account_ledger__transaction_id',$tranjection_jo->vo_no)->where('ledger_id', '!=', $ledger_id)->first();
                                    echo $aLt_jo->account_name;
                                    $note = $tranjection_jo->joDemoDetails->where('ledger_id', $ledger_id)->first();
                                    if($note){
                                        echo '<br/>('.$note->note . ')';
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
                                echo $accountLedgerTransaction->accountName->account_name;
                                };
                                @endphp
                            </td>
                            <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">
                                {{new_number_format($account_tran_row->debit)}} </td>
                            <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">
                                {{new_number_format($account_tran_row->credit)}}</td>
                            <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: center;">
                                @php
                                    if($openBalance > 0 && $key == 0){
                                        $dr+=$openBalance;
                                    }elseif($openBalance < 0 && $key == 0){
                                        $cr-=$openBalance;
                                    }
                                    $dr+=$account_tran_row->debit;
                                    $cr+=$account_tran_row->credit;
                                    $newBalance = $dr - $cr;
                                @endphp
                                @if($newBalance >1 )
                                {{ new_number_format($newBalance)." ("."DR)"}}
                                @else
                                {{new_number_format($newBalance*-1)." ("."CR)"}}
                                @endif
        
                            </td>
        
                        </tr>
        
                        @endforeach
                        @php
        
                            if(count($account_tran) == 0){
                                if($openBalance > 0){
                                    $newBalance += $openBalance;
                                }elseif($openBalance < 0 &&  $newBalance==0 ){
                                    $newBalance += $openBalance;
                                }else {
                                    $newBalance -= $openBalance;
        
                                }
                            }
                        @endphp
        
        
                        <tr>
                            <td colspan="4" style="text-align: right"><strong>Total</strong></td>
                            <td style="text-align: right">{{ new_number_format($dr) }}</td>
                            <td style="text-align: right">{{ new_number_format($cr) }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right"><strong>Closing Balance </strong></td>
                            <td style="text-align: right">
                                @if($newBalance >0 )
                                <span style="border-bottom: 3px double black">{{ new_number_format($newBalance)." ("."DR)"}}</span>
                                @else
                                <span style="border-bottom: 3px double black"> {{new_number_format($newBalance*-1)." ("."CR)"}} </span>
                                @endif
        
        
                            </td>
                        </tr>
        
        
                    </table>
                    Printed on	@php
                                $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                                echo $dt->format('j-m-Y , g:i a');
                            @endphp
                </div>
            </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-success btn-lg text-light fw-bold" type="button" onclick="printData()"><i class="fa fa-print"></i> Print</button>
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>
                    <a href="{{url()->full()}}&excel=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-excel"></i> Excel</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
