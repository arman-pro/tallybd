
@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')


<div style="background: #fff;padding: 2%;" id="printTable">
	 <table  style="border: 1px solid #eee;width: 100%">
	 		<tr>
	 			<td colspan="6" style="padding: 5px;">Invoice NO. : <span style="font-weight: 700;">{{$purchasesAddList->product_id_list }}</span></td>
	 			<td colspan="2" style="padding: 5px 20px;text-align: right;">Date : <span style="font-weight: 700;">{{$purchases->date??" "}}</span></td>
	 		</tr>
           	<tr>
              <td colspan="7" style="text-align: center;padding-top: 10px;">
                @php
                  $company = App\Companydetail::get();
                @endphp

                @foreach($company as $company_row)

                <h3 style="font-weight: 800;margin: 0;">{{$company_row->company_name}}</h3>
                <p style="margin: 0;">{{$company_row->company_address}}</p>
                <p style="margin: 0;"> Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>
                @endforeach
                <span style="font-size: 18px;font-weight: 800;border-bottom: 4px solid #566573;">INVOICE</span>
              </td>
              @php
                $row = App\Companydetail::where('id','1')->first();
              @endphp
              <td style="width: 80px;">
                <img src="{{asset($row->company_logo)}}" style="height: 80px; width: 80px;float: right;">
              </td>
            </tr>
            <tr>

	 			<td colspan="6" style="padding: 20px;font-size: 16px;">
	 				<span style="font-weight: 800;">Account : </span>
	 				<span style="font-weight: 700;padding-left:2px;">{{$purchasesAddList->ledger->account_name}}</span><br>
	 			</td>
	 			<td colspan="2" style="padding: 20px;text-align: right;"> <span style="font-weight: 700;"></span></td>
	 		</tr>


            <tr style="border-top: 1px solid #eee;text-align: center;font-weight: 800;">
            	<td style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Sl</td>
            	<td style="width: 30%;border-right: 1px solid #eee;padding: 5px;">Description</td>
            	<td style="width: 10%;border-right: 1px solid #eee;padding: 5px;">Qty</td>
            	<td style="width: 5%;border-right: 1px solid #eee;padding: 5px;">per</td>
            	<td style="width: 15%;border-right: 1px solid #eee;padding: 5px;">Rate</td>
            	<td style="width: 5%;border-right: 1px solid #eee;padding: 5px;">per</td>
            	<td style="width: 5%;border-right: 1px solid #eee;padding: 5px;"></td>
            	<td style="width: 20%;">Amount</td>
            </tr>

            @php
            	$i = 0;
            	$total_qty = 0;
            	$total_amount = 0;

			@endphp
	 		@foreach($purchasesAddList->demoProducts as $rowData)
	 		@php
	 			$i++;
	 			$total_qty = $total_qty + $rowData->qty ;
	 			$total_amount = $total_amount + ($rowData->price * $rowData->qty) ;

	 		@endphp
            <tr style="border-top: 1px solid #eee;text-align: center;">
            	<td style="width: 50px;border-right: 1px solid #eee;"> {{$i}}</td>
            	<td style="width: 30%;border-right: 1px solid #eee;text-align: left;">{{$rowData->item->name." ".$rowData->item->unit->name}}</td>
            	<td style="width: 10%;border-right: 1px solid #eee;">{{$rowData->qty}}</td>
            	<td style="width: 5%;border-right: 1px solid #eee;">{{$rowData->item->unit->name}}</td>
            	<td style="width: 15%;border-right: 1px solid #eee;">{{$rowData->price}}</td>
            	<td style="width: 5%;border-right: 1px solid #eee;">{{$rowData->item->unit->name}}</td>
            	<td style="width: 5%;border-right: 1px solid #eee;"></td>
            	<td style="width: 20%;">{{$rowData->price * $rowData->qty}}.00</td>
            </tr>
            @endforeach
            <tr style="border-top: 1px solid #eee;text-align: center;">
            	<td style="width: 50px;border-right: 1px solid #eee;height: 150px;"></td>
            	<td style="width: 30%;border-right: 1px solid #eee;"></td>
            	<td style="width: 10%;border-right: 1px solid #eee;"></td>
            	<td style="width: 5%;border-right: 1px solid #eee;"></td>
            	<td style="width: 15%;border-right: 1px solid #eee;"></td>
            	<td style="width: 5%;border-right: 1px solid #eee;"></td>
            	<td style="width: 5%;border-right: 1px solid #eee;"></td>
            	<td style="width: 20%;"></td>
            </tr>

            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
            	<td style="width: 50px;border-right: 1px solid #eee;"></td>
            	<td style="width: 30%;border-right: 1px solid #eee;">Total</td>
            	<td style="width: 10%;border-right: 1px solid #eee;">{{$total_qty}}</td>
            	<td style="width: 5%;border-right: 1px solid #eee;"></td>
            	<td style="width: 15%;border-right: 1px solid #eee;"></td>
            	<td style="width: 5%;border-right: 1px solid #eee;"></td>
            	<td style="width: 5%;border-right: 1px solid #eee;"></td>
            	<td style="width: 20%;">{{ number_format($total_amount, 2)}}</td>
            </tr>
            
            @php
                $accountexpens = App\AccountLedger::where('id',$purchasesAddList->expense_ledger_id)->first();
                $gtotal = $purchasesAddList->other_bill+$total_amount;
            @endphp
            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                <td style="width: 50px;border-right: 1px solid #eee;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;"> {{$purchasesAddList->expense_ledger_id?$accountexpens->account_name:''}}</td>
                <td style="width: 10%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{$purchasesAddList->other_bill}}</td>
            </tr>
            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                <td style="width: 50px;border-right: 1px solid #eee;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;">Grand Total</td>
                <td style="width: 10%;border-right: 1px solid #eee;"> </td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{number_format($gtotal,2)}}</td>
            </tr>
			 

            <tr style="border-top: 1px solid #eee;text-align: center;">
            	<td colspan="5" style="text-align: left;padding-left: 10px;">
            		Amount In Words :<br>
            		<span style="font-size: 16px;font-weight: 800;"> @php echo App\Helpers\Helper::NoToWord($gtotal); @endphp Taka Only</span>
            	</td>
            	<td colspan="3">
            		<br>
            		<br>
            		<br>
            		<p style="font-size: 16px;font-weight: 800;">for {{$row->company_name}}</p>
            		<br>
            		<p>Aurhorised Signatory</p>
            		<br>
            	</td>
            </tr>



       </table>
</div>
<div style="background: #fff; text-align: center;color: #fff;">
		<a href="{{URL::to('/print_pruchases_invoice/'.$purchasesAddList->product_id_list)}}" class="btn btn-info" style="color: #fff;">Print</a>
</div>
@endsection

