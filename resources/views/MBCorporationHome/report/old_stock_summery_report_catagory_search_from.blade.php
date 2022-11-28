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
			  <a class="active"  href="{{route('stock_summery_report_catagory_search_from')}}">Catagory Wise Stock Summery</a>
			  <a   href="{{route('stock_summery_report_godown_search_from')}}">Godwn Wise Stock Summery</a>
			</div>
		</div>
		<form action="{{url('stock_summery_report_category_search_from')}}" method="GET">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >Catagory Name :</label>
			        	<div>
			           		<select class="form-control" name="catagory_id" required>
			           			<option>Select</option>
			           			@php
			           				$Catagory = App\Category::get();
			           			@endphp
			           			@foreach($Catagory as $row)
			           				<option value="{{$row->id}}"{{  request()->catagory_id==    $row->id?'Selected': ' '  }}>{{$row->name}}</option>
			           			@endforeach
			           		</select>
					    </div>
					</div>
				</div>


				<div class="col-md-12" style="text-align: center;">
					<br>
					<button type="submit" class="btn btn-success" style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
				</div>
			</div>
		</form>
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
        @if($category_name)
		<div class="col-md-12" style="margin: 2%;" id="main_table">

			<br>
			<table class="table" style="border: 1px solid #eee;text-align: center;">
    			<tr>
    				<td colspan="8" style="text-align: center;">
    					@php
		    				$company = App\Companydetail::get();
			    		@endphp

			    		@foreach($company as $company_row)

    					<h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
    					<p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>
    					@endforeach
    					<h4> Stock Summery Report</h4>
    				</td>
    			</tr>

    			<tr style="font-size:14px;font-weight: 800;">
	    			<td style="padding: 5px 5px;width: 150px; text-align: left;">Product Name</td>
	    			<td style="padding: 5px 5px;width: 100px;"> Stock Qty</td>
	    			<td style="padding: 5px 5px;width: 100px;">Purchase Qty </td>
	    			<td style="padding: 5px 5px;width: 100px;">Purchase Return Qty</td>
	    			<td style="padding: 5px 5px;width: 100px;"> Sell Qty </td>
	    			<td style="padding: 5px 5px;width: 100px;">Sell Return Qty</td>
	    			<td style="padding: 5px 5px;width: 100px;">Generated Qty</td>
	    			<td style="padding: 5px 5px;width: 100px;">Consumed Qty</td>
	    			<td style="padding: 5px 5px;width: 100px;">Grand Total </td>
    			</tr>

    			@php
    				$i = 0;
    				$all_total_pur_price = 0;
	    			$all_total_sales_price = 0;
	    			$all_total_qty = 0;

    			@endphp
    			@foreach($category_name->items as $item_row)
                <tr style="font-size:14px;">
	    			<td style="padding: 5px 5px;width: 100px;">{{ ($item_row->name )}} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->stock_qty??0  }} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->purchase_qty??0  }} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->purchase_return_qty??0  }} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->sell_qty??0  }} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->sell_return_qty??0  }} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->generated_qty??0  }} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->consumed_qty??0  }} </td>
	    			<td style="padding: 5px 5px;width: 100px;">{{ optional($item_row->count)->grand_total??0  }} </td>
                </tr>
    			@endforeach



    		</table>
		</div>
        @endif
	</div>
</div>

@endsection
