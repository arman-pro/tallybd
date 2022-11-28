@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; "> Company Sales Order</h4>
	</div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
              		<div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">






    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
	<h2 class="card-title" style=" font-weight: 600; padding-bottom: 10px;background-color: #D93478; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">Update Sales Order</h2><br>
    <br>


 @foreach($PurchasesAddList as $PurchasesAddList_row)

 <form action="{{URL::to('/Update/PurchasesOrderAddList/'.$PurchasesAddList_row->product_id_list)}}" method="POST">
	@csrf
    <div class="row">
    	<input type="hidden" name="page_name" value="sales_order_addlist" id="page_name">
    	<div class="col-md-12">
    		<table class="table table-bordered">
    			<tr>
	    			<td>
	    				<div class="form-group row">
				        	<label for="cono1" class="control-label col-form-label" >Date :</label>
				        	<div>
				     			<input type="date" name="date" id="date" class="form-control"  value="{{$PurchasesAddList_row->date}}" />
						    </div>
						</div>
	    			</td>
	    			<td>
	    				<div class="form-group row">
				        	<label for="cono1" class="control-label col-form-label" >Order.No :</label>
				        	<div>

								<input type="text" class="form-control" id="product_id_list" name="product_id_list" value="{{$PurchasesAddList_row->product_id_list}}" style="text-align: center;"readonly>

						    </div>
						</div>
	    			</td>
	    			<td>
	    				<div class="form-group row">
				        	<label for="cono1" class="control-label col-form-label" >Godwn Name :</label>
				        	<div>
				     			<select class="form-control" style="text-align: center;" id="godown_id" name="godown_id">
				     				<option value="{{$PurchasesAddList_row->godown_id}}">{{$PurchasesAddList_row->godown_id}}</option>
				     				@foreach($Godwn as $godwn_row)
				     				<option value="{{$godwn_row->godown_id}}">{{$godwn_row->godown_id}}</option>
				     				@endforeach
				     			</select>
						    </div>
						</div>
	    			</td>
	    			<td>
	    				<div class="form-group row">
				        	<label for="cono1" class="control-label col-form-label" >SaleMan :</label>
				        	<div>
				     		<select class="form-control" style="text-align: center;" id="SaleMan_name" name="SaleMan_name">
	     				        <option value="{{$PurchasesAddList_row->SaleMan_name}}">{{$PurchasesAddList_row->SaleMan_name}}</option>
	     				        @foreach($SaleMan as $SaleMan_row)
				     	        	<option value="{{$SaleMan_row->SaleMan_name}}">{{$SaleMan_row->SaleMan_name}}</option>
				     	        @endforeach
	     		    	</select>
						    </div>
						</div>
	    			</td>
    			</tr>
    		</table>
    	</div>

		<div class="col-md-4">
			<div class="form-group row">
	        	<label for="cono1" class="control-label col-form-label" >Account Ledger</label>
	        	<div>
	     			<select class="form-control" name="" id="account_ladger" onclick="account_details()">
	     				<option value="{{$PurchasesAddList_row->account_ladger}}">{{$PurchasesAddList_row->account_ladger}}</option>
	     			</select>
			    </div>
			</div>
		</div>

		<div class="col-md-4">
		    <div class="form-group row">
				  <label for="cono1" class="control-label col-form-label" >Phone :</label>
				  	<div id="phone">
					  	@php
					  		$account_detais=App\AccountLedger::where("account_name",$PurchasesAddList_row->account_ladger)->get();
					  	@endphp
					  	@foreach($account_detais as $account_detais_row)
	                        <input type="text" class="form-control" value="{{ $account_detais_row->account_ledger_phone}}" readonly>
	                    @endforeach

					</div>
			</div>
		</div>
		<div class="col-md-4">
		    <div class="form-group row">
				<label for="cono1" class="control-label col-form-label" >Address :</label>
				    <div id="address">
				     	@php
					  		$account_detais=App\AccountLedger::where("account_name",$PurchasesAddList_row->account_ladger)->get();
					  	@endphp
					  	@foreach($account_detais as $account_detais_row)
	                        <input type="text" class="form-control" value="{{ $account_detais_row->account_ledger_address}}" readonly>
	                    @endforeach
					</div>
			</div>
		</div>


		<div id="account_pre_amount">

		</div>

		<div class="col-md-12">
			<table class="table table-bordered" style="background-color: #F8F9F9;" >
				 <thead style="background-color: #eee;text-align: center;font-size:18px;">
				 	<th>Product</th>
				 	<th>Quantity</th>
				 	<th>Price</th>
				 	<th>Discount</th>
				 	<th>Subtotal</th>
				 	<th>#</th>
				 </thead>
				<tbody>
				 	<tr>
				 		<td>
				 			<select class="form-control" id="item_name" name="item_name" style="text-align: center;" onclick="Product()">
				     			<option value="">Select</option>
				     				@foreach($Item as $item_row)
							     		<option value="{{$item_row->item_name}}">{{$item_row->item_name}}</option>
							     	@endforeach
				     		</select>
				 		</td>
				 		<td style="width:100px;">
				 			<input type="text" name="qty_product_value" id="qty_product_value" class="form-control" style="text-align: center;" value="0" oninput="qty_product()">
				 		</td>
				 		<td style="width:150px;"  id="sales_price"></td>
				 		<td style="text-align: center; width:150px;">
				 			<input type="text" name="discount_on_product" id="discount_on_product" oninput="qty_product()" class="form-control" style="text-align: center;" value="0">
				 		</td>
				 		<td style="text-align: center; width:300px;font-size:16px;" id="hi"><span id="subtotal_on_qty"></span><span id="subtotal_on_discount"></span>.00</td>
				 		<td style="text-align: center; width:50px;">
				 			<a  class="btn btn-sm btn-info" onclick="addondemoproduct()"><i class="fa fa-plus"></i></button>
				 		</td>
				 	</tr>
				</tbody>
			</table>

			<table class="table table-bordered" style="background-color: #F8F9F9;">
				<tbody id="data_add_for_list">
					@php
							$total_subtotal=0;
					  		$product_addon_list=App\DemoProductAddOnVoucher::where("product_id_list",$PurchasesAddList_row->product_id_list)->get();
					  		foreach($product_addon_list as $product_addon_list_row)
					  		{
					  			$total_subtotal = $total_subtotal + $product_addon_list_row->subtotal_on_product;
					  		}
					@endphp
					@foreach($product_addon_list as $product_addon_list_row)
				 	<tr>
				 		<td>
				 			<select class="form-control" style="text-align: center;"  readonly>
				     			<option value="{{$product_addon_list_row->item_name}}">{{$product_addon_list_row->item_name}}</option>
				     		</select>
				 		</td>
				 		<td style="width:100px;">
				 			<input type="text" class="form-control" style="text-align: center;" value="{{$product_addon_list_row->qty}}" readonly>
				 		</td>
				 		<td style="width:150px;"  id="sales_price">
				 			<input type="text" class="form-control" name=""style="text-align: center;" value="{{$product_addon_list_row->sales_price}}" readonly>
				 		</td>
				 		<td style="text-align: center; width:150px;">
				 			<input type="text" class="form-control" style="text-align: center;" value="{{$product_addon_list_row->discount}}"readonly>
				 		</td>
				 		<td style="text-align: center; width:300px;font-size:16px;"><input type="text" class="form-control" style="text-align: center;"  value="{{$product_addon_list_row->subtotal_on_product}}" readonly></td>
				 		<td style="text-align: center; width:50px;">
				 			<a href="{{URL::to('/delete/DemoProductAddOnVoucher/'.$product_addon_list_row->id_row)}}"  class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
				 		</td>
				 	</tr>
				 	@endforeach
				</tbody>
			</table>


			<table class="table table-responsive table-bordered" style="background-color: #F8F9F9;">
				<tbody>
					@php
						$total_subtotal=0;
						$item_total = 0;
					  	$product_addon_list=App\DemoProductAddOnVoucher::where("product_id_list",$PurchasesAddList_row->product_id_list)->get();
					  	foreach($product_addon_list as $product_addon_list_row)
					  	{
					  		$total_subtotal = $total_subtotal + $product_addon_list_row->subtotal_on_product;
					  		$item_total = $item_total + $product_addon_list_row->qty;
					  	}
					@endphp

				 	<tr>
				 		<td colspan="2" style="text-align: right;"> Item :</td>
				 		<td style="width: 150px;text-align: center;" id="total_item">{{$item_total}}</td>
				 		<td style="width: 150px;text-align: center;" >Total</td>
				 		<td style="width: 300px;text-align: center;" ><input type="" name="" class="form-control" style="text-align: center;" id="total_subtotal" value="{{$total_subtotal}}" readonly></td>
				 		<td style="width: 65px;"></td>
				 	</tr>
				 	<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">Other bill</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;">
					 	    <input type="text" name="other_bill" id="other_bill" oninput="other_bill()" class="form-control" style="text-align: center;" value="{{$PurchasesAddList_row->other_bill}}">
					 	</td>
					 	<td style="width: 50px;"></td>
					</tr>
					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">Discount Amount</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;">
					 	    <input type="text" id="discount_total" name="discount_total" oninput="other_bill()" class="form-control" style="text-align: center;" value="{{$PurchasesAddList_row->discount_total}}">
					 	</td>
					 	<td style="width: 50px;"></td>
					</tr>
					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">All SubTotal Amount</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;"> <span id="all_subtotal_amount">

					 			{{$total_subtotal + $PurchasesAddList_row->other_bill - $PurchasesAddList_row->discount_total}}
					 	</span></span></td>
					 	<td style="width: 50px;"></td>
					</tr>

					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">Pre. Amountt</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;" >
					 	@php
					  		$account_detais=App\AccountLedger::where("account_name",$PurchasesAddList_row->account_ladger)->get();
					  		foreach($account_detais as $account_detais_row)
					  		{
					  			$Debit = 0;
					  			$Credit = 0;
					  			$account_transcation_detais=App\AccountLedgerTransaction::where("account_ledger_id",$account_detais_row->account_ledger_id)->get();
					  			foreach($account_transcation_detais as $account_transcation_detais_row)
					  			{
					  				$Debit = $Debit + $account_transcation_detais_row->Debit;
					  				$Credit = $Credit + $account_transcation_detais_row->Credit;

					  			}
					  			$nowbalance = $Debit - $Credit;
					  		}

					  	@endphp

	                        <input type="text" class="form-control" id="pre_amount" value="{{ $nowbalance}}" style="text-align: center;" readonly>
					 	</td>
					 	<td style="width: 50px;"></td>
					</tr>

					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 18px; font-weight: 800;">Total Amountt</td>
					 	<td style="text-align: center;width: 300px;font-size: 18px; font-weight: 800;">
					 		<span id="total_amount">
					 			{{$total_subtotal + $PurchasesAddList_row->other_bill - $PurchasesAddList_row->discount_total+$nowbalance}}
					 		</span>
					 	</td>
					 	<td style="width: 50px;" id="total_amount"></td>
					</tr>
				</tbody>
			</table>
		</div>
    </div>

    <div class="row" style="background:#F8F9F9;margin:0 5px">

    	<div class="col-md-6">
			<div class="form-group row">
	        	<label for="cono1" class="control-label col-form-label" >Shipping Details :</label>
	        	<div>
	     			<textarea class="form-control" id="shipping_details" name="shipping_details">
	     				{{$PurchasesAddList_row->shipping_details}}
	     			</textarea>
			   	</div>
			</div>
    	</div>

    	<div class="col-md-6">
			<div class="form-group row">
	        	<label for="cono1" class="control-label col-form-label" >Delivered To :</label>
	        	<div>
	     			<textarea class="form-control" id="delivered_to_details" name="delivered_to_details">
	     					{{$PurchasesAddList_row->shipping_details}}
	     			</textarea>
			   	</div>
			</div>
    	</div>

    </div>

                   			<br>
                   			<br>
                   			<br>
                   			<div style="text-align: center; color: #fff; font-weight: 800;">
                   				<button type="submit"  class="btn btn-primary" style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Update</button>

                   				<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                   			</div>

              		</div>
              </div>
            </div>
        <div>

</div>


<div id="sxan"></div>

@endforeach

</form>


<script type="text/javascript">
	 $.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
	});

    	function cleardata(){
    		$('#qty_product_value').val('0');
    		$('#discount_on_product').val('0');
    		$('#price_as_product').val('0');
    		$('#item_name').val('');
    		$('#subtotal_on_qty').hide();
    		$('#subtotal_on_discount').hide();

    	}

	function Product(){

    		var item_name = $('#item_name').val();

    		$.ajax({
    			type:"GET",
    			dataType: "json",
    			url:"{{url('/product_as_price/-')}}"+item_name,

    			success:function(response){
    				var item
    				var item_price

    				$.each(response, function(key, value){

    						item_price = value.sales_price
    					item = '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" style="text-align: center;" value="'+item_price+'">'

    				})


    				$('#sales_price').html(item);
    				$('#subtotal').html(item_price);




    			}
    		})


    	}

    	function qty_product(){
    		var price_as_product = $('#price_as_product').val();

    		$('#subtotal_on_discount').hide();
    		$('#subtotal_on_qty').show();
    		$('#total_sales_price').val('');
    		$('#all_subtotal_amount').val('');
    		$('#total_amount').val('');
    		var qty_product = $('#qty_product_value').val();
    		var discount_on_product = $('#discount_on_product').val();
    		var pre_amount = $('#pre_amount').val();

    		var Subtotal = (price_as_product * qty_product) - discount_on_product


    		var product_id_list = $('#product_id_list').val();

    			$('#subtotal_on_qty').html(Subtotal);


    		$.ajax({
    			type:"GET",
    			dataType: "json",
    			url:"{{url('/product_new_fild/-')}}"+product_id_list,

    			success:function(response){
    				var total_product_price = ""
    				var all_total_product_price =""
    				var all_total_product_price_as_pr_amount =""
    				var Total_item =""
    				$.each(response, function(key, value){
	    				total_product_price = Number(total_product_price) + Number(value.subtotal_on_product)
	    				Total_item = Number(Total_item)+ Number(value.qty)
    				});
    				qty_product = Number(Total_item)+ Number(qty_product)
    				all_total_product_price = Number(total_product_price) + Number(Subtotal)
    				$('#total_sales_price').html(all_total_product_price);
    				$('#total_item').html(qty_product);
    				$('#all_subtotal_amount').html(all_total_product_price);
    				all_total_product_price_as_pr_amount = Number(all_total_product_price) + Number(pre_amount)
    				$('#total_amount').html(all_total_product_price_as_pr_amount);
    			}
    		})

    	}

    	//----------------------------start store addondemoproduct----------------------------------------

    	function addondemoproduct(){

    		var product_id_list = $('#product_id_list').val();
    		var page_name = $('#page_name').val();
    		var item_name = $('#item_name').val();
    		var qty_product_value = $('#qty_product_value').val();
    		var discount_on_product = $('#discount_on_product').val();
    		var price_as_product = $('#price_as_product').val();
    		var subtotal_on_product = (price_as_product * qty_product_value) - discount_on_product;
    		$.ajax({

    			type:"POST",
    			dataType:"json",
    			url:"{{url('/addondemoproduct/store/')}}",
    			data: {
    				product_id_list:product_id_list,
    				page_name:page_name,
    				item_name:item_name,
    				sales_price:price_as_product,
    				discount:discount_on_product,
    				qty:qty_product_value,
    				subtotal_on_product:subtotal_on_product,
    				"_token": "{{ csrf_token() }}",

    			},
    			success:function(response){
    				other_bill()
    				location.reload();

    			},

    		})
    	}
//----------------------------end store addondemoproduct----------------------------------------

function other_bill(){
	    		var other_bill = $('#other_bill').val();
	    		var total_subtotal = $('#total_subtotal').val();
	    		var discount_total = $('#discount_total').val();
	    		var pre_amount = $('#pre_amount').val();

	    		var all_subtotal_amount =(Number(total_subtotal)+ Number(other_bill)) - Number(discount_total);

	    		var Total_cost =(Number(total_subtotal)+ Number(other_bill) + Number(pre_amount)) - Number(discount_total);

	    				$('#all_subtotal_amount').html(all_subtotal_amount);

	    				$('#total_amount').html(Total_cost);



}


</script>


@endsection
