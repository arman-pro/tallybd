@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
	<h3 style="height:50px;text-align: center; padding-top: 10px;">Day Book</h3>
	<div class="row">
		<script lang='javascript'>
		    function printData()
				{
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
                        .source_file_list{
                            height: 35px;
                            float: right;
                            background-color: #99A3A4;

                            padding:5px;
                        }
                        .source_file_list a{
                        	text-decoration: none;
                           padding: 5px 20px;
                            color: #fff;
                            font-size:18px;

                        }
                        .source_file_list a:hover{
                            background-color:#D6DBDF;
                            color: #fff;
                        }
                    </style>
                    <div class="source_file_list">
                        <a style="color: #fff;" type="sumit" onclick="printData()">Print</a>
                        <a href="#">PDF</a>
                        <a href="">Excal</a>
                    </div>
		</div>
		<div class="col-md-12" style="text-align: center;" id="main_table">
			<br>
			<table class="table" style="border: 1px solid #eee;text-align: center;margin-left: 20px;margin-right: 50px;">
    			<tr>
    				<td colspan="7" style="text-align: center;">
    					@php
		    				$company = App\Companydetail::get();

			    		@endphp

			    		@foreach($company as $company_row)

    					<h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
    					<p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>
    					@endforeach
    					<h4>Day Book</h4>
    					<p>From: {{$formdate}} to {{$todate}}</p>
    				</td>
    			</tr>

    			<tr style="font-size:14px;font-weight: 800;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Date</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Type</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Vo.No</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px; text-align: left;">Account</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">Debit</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">Credit</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">Short Narration</td>
    			</tr>

    			@php
    				$sale = App\SalesAddList::whereBetween('date',[$formdate,$todate])->get();

    			@endphp
    			@foreach($sale as $sale_row)
    			<tr style="font-size:14px;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$sale_row->date}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Sales</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$sale_row->product_id_list}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px; text-align: left;">
	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$sale_row->account_ladger)->first();
		    				$itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$sale_row->product_id_list)->get();
		    			@endphp
		    			{{$account_ledger->account_name}}<br>
		    			@foreach($itemDetails as $itemDetails_row)
		    				@php
		    					$item = App\Item::where('item_name',$itemDetails_row->item_name)->get();

		    				@endphp

		    				@foreach($item as $item_row)
		    				{{$itemDetails_row->item_name.$item_row->how_many_unit." - ".$itemDetails_row->qty." (".$item_row->unit_name.") ."." @ ".$itemDetails_row->sales_price." TK"}}<br>
		    				@endforeach
		    			@endforeach
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				@php
	    					$subtotal = 0;
		    				$itemDetails_to = App\DemoProductAddOnVoucher::where('product_id_list',$sale_row->product_id_list)->get();
		    				foreach($itemDetails_to as $itemDetails_to_row)
		    				{
		    					$subtotal = $subtotal + $itemDetails_to_row->subtotal_on_product;
		    				};
		    					$allsubtotal = ($subtotal + $sale_row->other_bill) - $sale_row->discount_total;
		    			@endphp
		    			<br>
		    			{{$allsubtotal}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">

	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">
	    				{{$sale_row->shopping_details}}<br>
	    				{{$sale_row->delivered_to_details}}
	    			</td>
    			</tr>

    			@endforeach

    			{{-- @php
    				$sale = App\SalesReturnAddList::whereBetween('date',[$formdate,$todate])->get();
    			@endphp
    			@foreach($sale as $sale_row)
    			<tr style="font-size:14px;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$sale_row->date}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Sales-Re</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$sale_row->product_id_list}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px; text-align: left;">
	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$sale_row->account_ladger)->first();
		    				$itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$sale_row->product_id_list)->get();
		    			@endphp
		    			{{$account_ledger->account_name}}<br>
		    			@foreach($itemDetails as $itemDetails_row)
		    				@php
		    					$item = App\Item::where('item_name',$itemDetails_row->item_name)->get();
		    				@endphp

		    				@foreach($item as $item_row)
		    				{{$itemDetails_row->item_name.$item_row->how_many_unit." - ".$itemDetails_row->qty." (".$item_row->unit_name.") ."." @ ".$itemDetails_row->sales_price." TK"}}<br>
		    				@endforeach
		    			@endforeach
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				@php
	    					$subtotal = 0;
		    				$itemDetails_to = App\DemoProductAddOnVoucher::where('product_id_list',$sale_row->product_id_list)->get();
		    				foreach($itemDetails_to as $itemDetails_to_row)
		    				{
		    					$subtotal = $subtotal + $itemDetails_to_row->subtotal_on_product;
		    				};
		    					$allsubtotal = ($subtotal + $sale_row->other_bill) - $sale_row->discount_total;
		    			@endphp
		    			<br>

	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				{{$allsubtotal}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">
	    				{{$sale_row->shopping_details}}<br>
	    				{{$sale_row->delivered_to_details}}
	    			</td>
    			</tr> --}}

    			@endforeach

    			@php
    				$purchases = App\PurchasesAddList::whereBetween('date',[$formDate,$toDate])->get();
    			@endphp
    			@foreach($purchases as $purchases_row)
    			<tr style="font-size:14px;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$purchases_row->date}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Purchases</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$purchases_row->product_id_list}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px; text-align: left;">
	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$purchases_row->account_ladger)->first();
		    				$itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$purchases_row->product_id_list)->get();
		    			@endphp
		    			{{$account_ledger->account_name}}<br>
		    			@foreach($itemDetails as $itemDetails_row)
		    				@php
		    					$item = App\Item::where('item_name',$itemDetails_row->item_name)->get();
		    				@endphp

		    				@foreach($item as $item_row)
		    				{{$itemDetails_row->item_name.$item_row->how_many_unit." - ".$itemDetails_row->qty." (".$item_row->unit_name.") ."." @ ".$itemDetails_row->sales_price." TK"}}<br>
		    				@endforeach
		    			@endforeach
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				@php
	    					$subtotal = 0;
		    				$itemDetails_to = App\DemoProductAddOnVoucher::where('product_id_list',$purchases_row->product_id_list)->get();
		    				foreach($itemDetails_to as $itemDetails_to_row)
		    				{
		    					$subtotal = $subtotal + $itemDetails_to_row->subtotal_on_product;
		    				};
		    					$allsubtotal = ($subtotal + $purchases_row->other_bill) - $purchases_row->discount_total;
		    			@endphp
		    			<br>

	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				{{$allsubtotal}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">
	    				{{$purchases_row->shopping_details}}<br>
	    				{{$purchases_row->delivered_to_details}}
	    			</td>
    			</tr>
    			@endforeach

    			@php
    				$purchases = App\PurchasesReturnAddList::whereBetween('date',[$formdate,$todate])->get();
    			@endphp
    			@foreach($purchases as $purchases_row)
    			<tr style="font-size:14px;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$purchases_row->date}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Purchases</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$purchases_row->product_id_list}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px; text-align: left;">
	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$purchases_row->account_ladger)->first();
		    				$itemDetails = App\DemoProductAddOnVoucher::where('product_id_list',$purchases_row->product_id_list)->get();
		    			@endphp
		    			{{$account_ledger->account_name}}<br>
		    			@foreach($itemDetails as $itemDetails_row)
		    				@php
		    					$item = App\Item::where('item_name',$itemDetails_row->item_name)->get();
		    				@endphp

		    				@foreach($item as $item_row)
		    				{{$itemDetails_row->item_name.$item_row->how_many_unit." - ".$itemDetails_row->qty." (".$item_row->unit_name.") ."." @ ".$itemDetails_row->sales_price." TK"}}<br>
		    				@endforeach
		    			@endforeach
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				@php
	    					$subtotal = 0;
		    				$itemDetails_to = App\DemoProductAddOnVoucher::where('product_id_list',$purchases_row->product_id_list)->get();
		    				foreach($itemDetails_to as $itemDetails_to_row)
		    				{
		    					$subtotal = $subtotal + $itemDetails_to_row->subtotal_on_product;
		    				};
		    					$allsubtotal = ($subtotal + $purchases_row->other_bill) - $purchases_row->discount_total;
		    			@endphp
		    			<br>
		    			{{$allsubtotal}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">

	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">
	    				{{$purchases_row->shopping_details}}<br>
	    				{{$purchases_row->delivered_to_details}}
	    			</td>
    			</tr>
    			@endforeach

    			@php
    				$recevied = App\Receive::whereBetween('date',[$formdate,$todate])->get();
    			@endphp
    			@foreach($recevied as $recevied_row)
    			<tr style="font-size:14px;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$recevied_row->date}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Received
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$recevied_row->vo_no}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px; text-align: left;">
	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$recevied_row->account_name)->first();
	    				@endphp
	    				{{$account_ledger->account_name}}<br>

	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$recevied_row->payment_mode)->first();
	    				@endphp
	    				{{$account_ledger->account_name}}<br>

	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				{{$recevied_row->amount}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">
	    				{{$recevied_row->description}}
	    			</td>
    			</tr>
    			@endforeach

    			@php
    				$payment = App\Payment::whereBetween('date',[$formdate,$todate])->get();
    			@endphp
    			@foreach($payment as $payment_row)
    			<tr style="font-size:14px;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$payment_row->date}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Payment
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$payment_row->vo_no}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px; text-align: left;">
	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$payment_row->account_name)->first();
	    				@endphp
	    				{{$account_ledger->account_name}}<br>

	    				@php
	    					$account_ledger = App\AccountLedger::where('account_ledger_id',$payment_row->payment_mode)->first();
	    				@endphp
	    				{{$account_ledger->account_name}}<br>

	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">
	    				{{$payment_row->amount}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">

	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">
	    				{{$payment_row->description}}
	    			</td>
    			</tr>
    			@endforeach

    			@php
    				$Contra = App\Journal::whereBetween('date',[$formdate,$todate])->get();
    			@endphp
    			@foreach($Contra as $Contra_row)
    			<tr style="font-size:14px;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$Contra_row->date}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$Contra_row->page_name}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$Contra_row->vo_no}}</td>
		    		<td colspan="4" style="border-right: 1px solid #eee;padding: 0 0;">
		    			<table class="table" style="text-align: center;">
		    				@php
                               $under_journal = App\DemoContraJournalAddlist::where('vo_no',$Contra_row->vo_no)->get();
                            @endphp
                            @foreach($under_journal as $under_journal_row)
							<tr style="font-size:14px;">
								<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;text-align: left;">
									@php
				    					$account_ledger = App\AccountLedger::where('account_ledger_id',$under_journal_row->account_name)->first();
				    				@endphp
				    				{{$account_ledger->account_name}}
								</td>
								@if($under_journal_row->drcr < 2)
                                  <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">{{$under_journal_row->amount}}</td>
                                  @else
                                  <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"></td>
                                  @endif
                                  @if($under_journal_row->drcr > 1)
                                  <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;text-align: right;">{{$under_journal_row->amount}}</td>
                                  @else
                                  <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"></td>
                                  @endif
								<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px; text-align: left;">{{$under_journal_row->note}}</td>
							</tr>
							@endforeach
						</table>
	    			</td>

    			</tr>
    			@endforeach

    		</table>
		</div>

	</div>
</div>

@endsection
