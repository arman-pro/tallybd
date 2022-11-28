@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
	<h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Stock Summery Report By Catagory</h3>
	<div class="row">
		<style type="text/css">
			.topnav {
		    overflow: hidden;
		    background-color: #eee;
		  }

		  .topnav a {
		    width: 33.33%;
		    float: left;
		    color: #000;
		    text-align: center;
		    padding: 5px 16px;
		    text-decoration: none;
		    font-size: 17px;
		  }

		  .topnav a:hover {
		    background-color: #ddd;
		    color: black;
		  }

		  .topnav a.active {
		    background-color: #99A3A4;
		    color: #fff;
		  }
		</style>
		<div class="col-md-12">
			<div class="topnav">
                <a  href="{{route('all_stock_summery_report')}}">Stock Summery</a>
                <a   href="{{route('stock_summery_report_catagory_search_from')}}">Catagory Wise Stock Summery</a>
                <a  class="active" href="{{route('stock_summery_report_godown_search_from')}}">Godown Wise Stock Summery</a>
                <a   href="{{url('stock_summery_report_item_search_from')}}">Item Wise Stock Summery</a>
			</div>
		</div>


		<br>
		<br>
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
                        <a href="">PDF</a>
                        <a href="">Excal</a>
                    </div>
		</div>
		<div class="col-md-12" style="margin: 2%;" id="main_table">

			<br>
			<table class="table" style="border: 1px solid #eee;text-align: center;">
    			<tr>
    				<td colspan="8" style="text-align: center; border:0px !important;">
    					@php
		    				$company = App\Companydetail::get();
			    		@endphp

			    		@foreach($company as $company_row)

    					<h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
    					<p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>
    					@endforeach

    					<h4>{{$catagory_name}}<br>dd Stock Summery Report</h4>
    				</td>
    			</tr>

    			<tr style="font-size:14px;font-weight: 800;">
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">SL.NO</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px; text-align: left;">Product Name</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Sales Price</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Purchases Price</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Qty</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"> Total Sales Price</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total Purchases Price</td>
    			</tr>

    			@php
    				$i = 0;
    				$all_total_pur_price = 0;
	    			$all_total_sales_price = 0;

	    			$all_total_qty = 0;
    				$item = App\Item::where('catagory_name',$catagory_name)->get();
    			@endphp
    			    			@foreach($item as $item_row)
    			<tr style="font-size:14px;">
    				@php
    					$price = App\StockDetail::where('item_name',$item_row->item_name)->first();
    					$i++;
	    				$sale_qty = 0;
	    				$sales = App\DemoProductAddOnVoucher::where('page_name','sales_addlist')->where('item_name',$item_row->item_name)->get();
	    				foreach($sales as $sales_row){
	    					$sale_qty = $sale_qty + $sales_row->qty ;
	    				}

	    				$sales_return_qty = 0;
	    				$sales_return = App\DemoProductAddOnVoucher::where('page_name','sales_return_addlist')->where('item_name',$item_row->item_name)->get();
	    				foreach($sales_return as $sales_return_row){
	    					$sales_return_qty = $sales_return_qty + $sales_return_row->qty ;
	    				}
	    			@endphp
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;">{{$i}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;text-align: left;">{{$item_row->item_name}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$price->sale_price}}.00</td>
	    			@php
	    				$pur_qty = 0;
	    				$purchases = App\DemoProductAddOnVoucher::where('page_name','purchases_addlist')->where('item_name',$item_row->item_name)->get();
	    				foreach($purchases as $purchases_row){
	    					$pur_qty = $pur_qty + $purchases_row->qty ;
	    				}


	    				$pur_return_qty = 0;
	    				$purchases_return = App\DemoProductAddOnVoucher::where('page_name','purchases_return_addlist')->where('item_name',$item_row->item_name)->get();
	    				foreach($purchases_return as $purchases_return_row){
	    					$pur_return_qty = $pur_return_qty + $purchases_return_row->qty ;
	    				}
	    			@endphp
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$price->purchases_price}}.00</td>

	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
	    			@php
	    				$ad_mains_qty = 0;
	    				$ad_mains = App\Demostockadjusment::where('item_name',$item_row->item_name)->where('page_name','1')->get();
	    				foreach($ad_mains as $ad_mains_row){
	    					$ad_mains_qty = $ad_mains_qty + $ad_mains_row->qty ;
	    				}

	    				$ad_plus_qty = 0;
	    				$ad_plus = App\Demostockadjusment::where('item_name',$item_row->item_name)->where('page_name','2')->get();
	    				foreach($ad_plus as $ad_plus_row){
	    					$ad_plus_qty = $ad_plus_qty + $ad_plus_row->qty ;
	    				}
	    			@endphp
	    			@php
	    				$total_pur_price = 0;
	    				$total_sales_price = 0;

	    				$total_stock_qty = ($item_row->previous_stock + $pur_qty + $sales_return_qty + $ad_plus_qty) - $sale_qty - $pur_return_qty - $ad_mains_qty;

	    				$total_pur_price =  $price->purchases_price * $total_stock_qty;
	    				$total_sales_price = $price->sale_price * $total_stock_qty;

	    				$all_total_qty = $all_total_qty + $total_stock_qty;
	    				$all_total_pur_price = $all_total_pur_price + $total_pur_price;
	    				$all_total_sales_price = $all_total_sales_price + $total_sales_price;
	    			@endphp

	    			{{$total_stock_qty." ".$item_row->unit_name}}
	    			</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$total_sales_price}}.00</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$total_pur_price}}.00</td>
    			</tr>
    			@endforeach


    			<tr style="font-weight: 800;font-size: 16px;">
	    			<td colspan="4" style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">All Stock Total</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">{{$all_total_qty}}</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$all_total_pur_price}} .00</td>
	    			<td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">{{$all_total_sales_price}}.00</td>
    			</tr>

    		</table>
		</div>

	</div>
</div>

@endsection
