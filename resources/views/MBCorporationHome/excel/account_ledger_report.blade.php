<?php
    $openDr = 0;
    $openCr = 0;
    $openBalance = 0;
    $opening = App\AccountLedgerTransaction::where('ledger_id',$ledger_id)
        ->where('date', '<' ,$formDate)
        ->get()->unique('account_ledger__transaction_id');

    if($opening){
        $openDr = $opening->sum('debit');
        $openCr = $opening->sum('credit');
        $openBalance = $openDr - $openCr;
    }
?>
 
<table>
    <thead>
        <tr>
            <th colspan="3">Account/Ladger Name :{{ $ledger->account_name }}</th>
            <th colspan="4">
                @if($openBalance > 1)
                    Opening Balance: {{ new_number_format($openBalance)}} (Dr)
                @elseif($openBalance < -1) 
                    Opening Balance: {{ new_number_format($openBalance)}} (Cr) 
                @else
                    Opening Balance: 
                @endif
            </th>
        </tr>
        <tr>
            <th colspan="3">Address: {{ $ledger->account_ledger_address}}</th>
            <th colspan="4">From : {{date('d-m-Y', strtotime($formDate))}} To : {{date('d-m-Y', strtotime($toDate))}}</th>
        </tr>
        <tr>
            <td>Date</td>
            <td>Type</td>
            <td>Vch.No</td>
            <td>Account</td>
            <td>Debit(TK)</td>
            <td>Credit(TK)</td>
            <td>Balance(TK)</td>
        </tr>
    </thead>
    <?php
        $i=0;
        $x = 0;
        $dr = 0;
        $cr = 0;
        $newBalance = 0;
    ?>
    @foreach($account_tran as $key=>$account_tran_row)
    <tr>
        <td>
        <?php
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
        ?>
        </td>
        <td>
        <?php
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
        ?>
        </td>
        <td>
            {{$account_tran_row->account_ledger__transaction_id}}
        </td>
        <td>
        <?php
            
            if($tranjection_pur){
                //echo $tranjection_pur->product_id_list;
                $itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_pur->product_id_list)->get();
                foreach($itemDetails as $itemDetails_row)
                {
                $item = App\Item::where('id',$itemDetails_row->item_id)->first();
                echo $item->name." , ".$itemDetails_row->qty." (".$item->unit->name.")@ ".$itemDetails_row->price. "<br style='mso-data-placement:same-cell;' />";
                };
                echo $tranjection_pur->expense_ledger_id?$tranjection_pur->ledgerexpanse->account_name." @ ".$tranjection_pur->other_bill."<br style='mso-data-placement:same-cell;' />":""; 
                echo $tranjection_pur->delivered_to_details ? "(".$tranjection_pur->delivered_to_details.")" : "";

            }elseif($tranjection_sale){
                $itemDetails =
                App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_sale->product_id_list)->get();
                foreach($itemDetails as $itemDetails_row)
                {
                $item = App\Item::where('id',$itemDetails_row->item_id)->first();
                echo $item->name." , ".$itemDetails_row->qty." (".$item->unit->name.")@ ".$itemDetails_row->price."<br style='mso-data-placement:same-cell;' />";
                };
                echo $tranjection_sale->expense_ledger_id?$tranjection_sale->ledgerexpense->account_name." @ ".$tranjection_sale->other_bill."<br style='mso-data-placement:same-cell;' />":""; 
                echo $tranjection_sale->delivered_to_details ? "(".$tranjection_sale->delivered_to_details . ")" : "";
                    
                
            }elseif($tranjection_sale_return){
            $itemDetails =
            App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_sale_return->product_id_list)->get();
            foreach($itemDetails as $itemDetails_row)
            {
            $item = App\Item::where('id',$itemDetails_row->item_id)->first();
            echo $item->name." - ".$itemDetails_row->qty."
            (".$item->unit->name.") ."." @ ".$itemDetails_row->price." TK"."<br style='mso-data-placement:same-cell;' />";
            };
            }elseif($tranjection_pur_return){
                $itemDetails =
                App\DemoProductAddOnVoucher::where('product_id_list',$tranjection_pur_return->product_id_list)->get();
                foreach($itemDetails as $itemDetails_row){
                    $item = App\Item::where('id',$itemDetails_row->item_id)->first();
                    echo $item->name."-".$itemDetails_row->qty."
                    (".$item->unit->name.") ."." @ ".$itemDetails_row->price." TK"."<br style='mso-data-placement:same-cell;' />";
                };

            }elseif($tranjection_recevie){
                if(optional($tranjection_recevie->accountMode)->account_name == $ledger->account_name){
                    echo  optional($tranjection_recevie->paymentMode)->account_name;
                }else{
                    echo optional($tranjection_recevie->accountMode)->account_name;
                }
                if($tranjection_recevie->description){
                    echo "<br style='mso-data-placement:same-cell;' />(".$tranjection_recevie->description.")";
                }

            }elseif($tranjection_payment){
                if(optional($tranjection_payment->accountMode)->account_name == $ledger->account_name){
                    echo optional($tranjection_payment->paymentMode)->account_name??" ";
                }else{
                    echo optional($tranjection_payment->accountMode)->account_name??" ";
                }
                if($tranjection_payment->description){
                    echo "<br style='mso-data-placement:same-cell;' />(".$tranjection_payment->description.")";
                }
            }elseif($tranjection_con){
                $aLt_con = App\AccountLedgerTransaction::where('account_ledger__transaction_id',$tranjection_con->vo_no)->where('ledger_id', '!=', $ledger_id)->first();
                echo $aLt_con->account_name;
                //echo $tranjection_con->page_name;
                $note = $tranjection_con->demoDetails->where('ledger_id', $ledger_id)->first();
                if($note){
                    echo "<br style='mso-data-placement:same-cell;' />(" .$note->note.")";
                }
            }elseif($tranjection_jo){
                //echo $tranjection_jo->page_name;
                $aLt_jo = App\AccountLedgerTransaction::where('account_ledger__transaction_id',$tranjection_jo->vo_no)->where('ledger_id', '!=', $ledger_id)->first();
                echo $aLt_jo->account_name;
                $note = $tranjection_jo->joDemoDetails->where('ledger_id', $ledger_id)->first();
                if($note){
                    echo "<br style='mso-data-placement:same-cell;' />(".$note->note . ")";
                }
            }
            
            elseif ($empoyee_jour) {
                    $under_journal =
                    App\EmployeeJournalDetails::where('vo_no',$dataRow->account_ledger__transaction_id)->get();
                    foreach($under_journal as $under_journal_row){
                        echo optional($under_journal_row->ledger)->account_name."<br style='mso-data-placement:same-cell;' />";
                        echo optional($under_journal_row->employee)->name;
                    }
            }
            elseif($salary_generate){
                foreach ($salary_generate->details as $key => $data) {
                    echo optional($data->employee)->name."<br style='mso-data-placement:same-cell;' />";
                } ;
            }
            elseif($salary_payment){
                echo optional($salary_payment->employee)->name."<br style='mso-data-placement:same-cell;' />";
            }
            elseif($accountLedgerTransaction){
                echo $accountLedgerTransaction->accountName->account_name;
            };
        ?>
        </td>
        <td>
            {{new_number_format($account_tran_row->debit)}} </td>
        <td>
            {{new_number_format($account_tran_row->credit)}}</td>
        <td>
        <?php
            if($openBalance > 0 && $key == 0){
                $dr+=$openBalance;
            }elseif($openBalance < 0 && $key == 0){
                $cr-=$openBalance;
            }
            $dr+=$account_tran_row->debit;
            $cr+=$account_tran_row->credit;
            $newBalance = $dr - $cr;
        ?>
        @if($newBalance >1 )
            {{ new_number_format($newBalance)." ("."DR)"}}
        @else
            {{new_number_format($newBalance*-1)." ("."CR)"}}
        @endif
        </td>
    </tr>
    @endforeach
    <?php
        if(count($account_tran) == 0){
            if($openBalance > 0){
                $newBalance += $openBalance;
            }elseif($openBalance < 0 &&  $newBalance==0 ){
                $newBalance += $openBalance;
            }else {
                $newBalance -= $openBalance;

            }
        }
    ?>
    <tr>
        <td colspan="4"><strong>Total</strong></td>
        <td>{{ new_number_format($dr) }}</td>
        <td>{{ new_number_format($cr) }}</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">Closing Balance</td>
        <td>
            @if($newBalance >0 )
            <span>{{ new_number_format($newBalance)." ("."DR)"}}</span>
            @else
            <span>{{new_number_format($newBalance*-1)." ("."CR)"}}</span>
            @endif
        </td>
    </tr>
</table>