<?php
    $from = null;
    $to = null;
    if(request()->form_date && request()->to_date){
        $from = date('d-m-Y', strtotime(request()->form_date));
        $to = date('d-m-Y', strtotime(request()->to_date));
    }
    $total_amount_dr=0;
    $total_amount_cr=0;
?>
<table>
    <thead>
        <tr>
            <th colspan="7">{{$company->company_name}}</th>
        </tr>
        <tr>
            <th colspan="7">{{$company->company_address}}</th>
        </tr>
        <tr>
            <th colspan="7">Day Book</th>
        </tr>
        <tr>
            <th colspan="7">From : {{$from}} TO : {{ $to }}</th>
        </tr>
        <tr>
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
        <tr>
            <td>{{ date('d-m-y', strtotime($dataRow->date))}}</td>
            <td>
            <?php
                $accountLedgerTransaction= App\AccountLedgerTransaction::where('account_ledger__transaction_id',$dataRow->account_ledger__transaction_id)->first();
                $tranjection_pur = App\PurchasesAddList::with(['account_ledger_transaction'])->where('product_id_list',$dataRow->account_ledger__transaction_id)->first();
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
            ?>
            </td>
            <td>
                {{$dataRow->account_ledger__transaction_id}}
            </td>
            <td>
            <?php
                $output = [];
                if($tranjection_pur){
                    $item = ($tranjection_pur->main_ledger()->account_name ?? "N/A");
                    array_push($output, $item);
                    $itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                    
                    foreach($itemDetails as $itemDetails_row){
                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        $item = $item_row->name." , ".$itemDetails_row->qty . " (".optional($item_row->unit)->name.")@ ".$itemDetails_row->price;
                        array_push($output, $item);
                    }
                    $ac_name = $tranjection_pur->expense_ledger()->accountName->account_name ?? null;
                    if($ac_name) {
                        $item = $ac_name . ' @ '. ($tranjection_pur->expense_ledger()->debit ?? 0);
                        array_push($output, $item);
                    }
                    if($tranjection_pur->delivered_to_details) {
                        $item = $tranjection_pur->delivered_to_details;
                        array_push($output, $item);
                    }
                   
                }elseif($tranjection_pur_return){
                    echo $dataRow->accountName->account_name."<br style='mso-data-placement:same-cell;' />";
                    $itemDetails =App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                    foreach($itemDetails as $itemDetails_row){
                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        $item = $item_row->name." - ".$itemDetails_row->qty." (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price;
                        array_push($output, $item);
                    }
                }elseif($tranjection_sale_return){
                    $item = $dataRow->accountName->account_name;
                    array_push($output, $item);
                    $itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                    foreach($itemDetails as $itemDetails_row){
                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        $item = $item_row->name." - ".$itemDetails_row->qty." (".optional($item_row->unit)->name.") ."." @ ".$itemDetails_row->price;
                        array_push($output, $item);
                    }
                }elseif($tranjection_sale){
                    $item = $tranjection_sale->main_ledger()->accountName->account_name;
                    array_push($output, $item);
                    $itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$dataRow->account_ledger__transaction_id)->get();
                    foreach($itemDetails as $itemDetails_row){
                        $item_row = App\Item::where('id',$itemDetails_row->item_id)->first();
                        $item = $item_row->name." , ".$itemDetails_row->qty." (".optional($item_row->unit)->name.")@ ".$itemDetails_row->price;
                        array_push($output, $item);
                    }
                    if($tranjection_sale->expense_ledger()){
                        $item = ($tranjection_sale->expense_ledger()->accountName->account_name ?? null) . ' @ '.($tranjection_sale->expense_ledger()->credit ?? 0);
                        array_push($output, $item);
                    }
                    if($tranjection_sale->delivered_to_details) {
                        $item = "(".$tranjection_sale->delivered_to_details . ")";
                        array_push($output, $item);
                    }
                    
                }elseif($tranjection_recevie){
                    $item = $tranjection_recevie->paymentMode->account_name;
                    array_push($output, $item);
                    $item = $tranjection_recevie->accountMode->account_name ;
                    array_push($output, $item);
                    if($tranjection_recevie->description) {
                        $item = ' ('.$tranjection_recevie->description.')';
                        array_push($output, $item);
                    }
                }elseif($tranjection_payment){
                    $item = $tranjection_payment->paymentMode->account_name;
                    array_push($output, $item);
                    $item = $tranjection_payment->accountMode->account_name; 
                    array_push($output, $item);
                    if($tranjection_payment->description) {
                        $item = ' ('.$tranjection_payment->description.')';
                        array_push($output, $item);
                    }
                }elseif($tranjection_con){
                    $under_journal = App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                    foreach($under_journal as $under_journal_row){
                        $item = optional($under_journal_row->ledger)->account_name;
                        array_push($output, $item);
                    }
                }elseif($tranjection_jo){
                    $under_journal = App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                    foreach($under_journal as $under_journal_row){
                        $item = optional($under_journal_row->ledger)->account_name;
                        array_push($output, $item);
                        if($under_journal_row->note) {
                            $item = " (".$under_journal_row->note.")";
                            array_push($output, $item);
                        }
                    }
                }
                elseif ($empoyee_jour) {
                    $under_journal = App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                    foreach($under_journal as $under_journal_row){
                        $item = optional($under_journal_row->ledger)->account_name;
                        array_push($output, $item);
                        $item = optional($under_journal_row->employee)->name;
                        array_push($output, $item);
                    }
                }
                elseif($salary_generate){
                    foreach ($salary_generate->details as $key => $data) {
                        $item = optional($data->employee)->name;
                        array_push($output, $item);
                    } ;
                }elseif($salary_payment){
                    $item = optional($salary_payment->employee)->name;
                    array_push($output, $item);
                }elseif($accountLedgerTransaction){
                    $item = "A/C Opening";
                    array_push($output, $item);
                };
                
            ?>
                <?php
                    $output = array_map(function($item){
                        return str_replace('&', 'and', $item);
                    }, $output);
                ?>
                {!! implode("<br style='mso-data-placement:same-cell;' />", $output) !!}
            </td>
            @if ($tranjection_con)
            <?php
                $under_contra = App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
            ?>
            <td>
                <?php
                    $output = [];
                ?>
                @foreach ($under_contra->where('drcr', 'Dr') as $under_contra_row)
                <?php
                    $total_amount_dr += $under_contra_row->amount ?? 0;
                    if($under_contra_row->amount != 0) {
                        $item = new_number_format($under_contra_row->amount);
                        array_push($output, $item);
                    }
                ?>
                @endforeach
                {!! implode("<br style='mso-data-placement:same-cell;' />", $output) !!}
            </td>
            <td>
                <?php
                    $output = [];
                ?>
                @foreach ($under_contra->where('drcr', 'Cr') as $under_contra_row)
                <?php
                    $total_amount_cr += $under_contra_row->amount ?? 0;
                    if($under_contra_row->amount != 0) {
                        $item = new_number_format($under_contra_row->amount);
                        array_push($output, $item);
                    }
                ?>
                @endforeach
                {!! implode("<br style='mso-data-placement:same-cell;' />", $output) !!}
            </td>
            @elseif ($tranjection_jo)
            <?php
                $under_Journal = App\DemoContraJournalAddlist::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                $output = [];
            ?>
            <td>
                @foreach ($under_Journal->where('drcr', 'Dr') as $under_journal_row)
                <?php
                    $total_amount_dr += $under_journal_row->amount ?? 0;
                    if($under_journal_row->amount != 0) {
                        $item = new_number_format($under_journal_row->amount);
                        array_push($output, $item);
                    }
                ?>
                @endforeach
                {!! implode("<br style='mso-data-placement:same-cell;' />", $output) !!}
            </td>
            <td>
                <?php
                    $output = [];
                ?>
                @foreach ($under_Journal->where('drcr', 'Cr') as $under_journal_row)
                <?php
                    $total_amount_cr += $under_journal_row->amount ?? 0;
                     if($under_journal_row->amount != 0) {
                        $item = new_number_format($under_journal_row->amount);
                        array_push($output, $item);
                    }
                ?>
                @endforeach
                {!! implode("<br style='mso-data-placement:same-cell;' />", $output) !!}
            </td>
            @elseif($empoyee_jour)
            <?php
                $under_empjournal = App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
            ?>
            <td>
                @foreach ($under_empjournal->where('drcr', 1) as $empjournal_row)
                <?php
                    $total_amount_dr += $empjournal_row->amount ?? 0;
                    echo ($empjournal_row->amount != 0? new_number_format($empjournal_row->amount):'')."<br style='mso-data-placement:same-cell;' />";
                ?>
                @endforeach
            </td>
            <td>
                @foreach ($under_empjournal->where('drcr', 2) as $empjournal_row)
                <?php
                    $total_amount_cr += $empjournal_row->amount ?? 0;
                    echo ($empjournal_row->amount != 0? new_number_format($empjournal_row->amount):'')."<br style='mso-data-placement:same-cell;' />";
                ?>
                @endforeach
            </td>
            @elseif($tranjection_pur)
            
            <td></td>
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
            <td></td>
            @elseif($salary_payment)
                <?php
                    $total_amount_dr += $dataRow->credit ?? 0;
                ?>
            <td>
                {{ $dataRow->credit != 0 ? new_number_format($dataRow->credit):'' }}
            </td>
            <td></td> 
            @else
                <?php
                    $total_amount_dr += $dataRow->debit ?? 0;
                ?>
                <?php
                    $total_amount_cr += $dataRow->credit ?? 0;
                ?>
            <td>
                {{ $dataRow->debit != 0 ? new_number_format($dataRow->debit) : '' }}
            </td>
            <td>
                {{ $dataRow->credit !=0 ? new_number_format($dataRow->credit) : ' ' }}
            </td>
            @endif
            <td>
                {{ optional($dataRow->createdBy)->name??' ' }}
            </td>
        </tr>

@endforeach
    </tbody>
<tfoot>
    <tr>
        <td colspan="5"> Total</td>
        <td>{{new_number_format($total_amount_dr)}}</td>
        <td>{{new_number_format($total_amount_cr)}}</td>
    </tr>
</tfoot>
</table>