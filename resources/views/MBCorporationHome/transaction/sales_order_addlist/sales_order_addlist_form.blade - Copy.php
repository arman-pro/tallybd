@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; "> Company Sales Order</h4>
	</div>
</div>
<form action="{{ url('/SaveAllData_sales/store/') }}" method="post">
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
	<h2 class="card-title" style=" font-weight: 600; padding-bottom: 10px;background-color: #69E643; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">Add Sales Order</h2><br>
    <br>



    <div class="row">
    	<input type="hidden" name="page_name" value="sales_order_addlist" id="page_name">
    	<div class="col-md-12">
    		<table class="table table-bordered">
    			<tr>
	    			<td>
	    				<div class="form-group row">
				        	<label for="cono1" class="control-label col-form-label" >Date :</label>
				        	<div>
				     			<input type="date" name="date" id="date" class="form-control" />
						    </div>
						</div>
	    			</td>
	    			<td>
	    				<div class="form-group row">
				        	<label for="cono1" class="control-label col-form-label" >Order.No :</label>
				        	<div>
				        		@php
	    							use App\SalesOrderAddList;

	    							$product_id_list = App\Helpers\Helper::IDGenerator(new SalesOrderAddList, 'product_id_list', 4, 'SO.No');

								@endphp
								<input type="text" class="form-control" name="product_id_list" id="product_id_list" value="{{$product_id_list}}" style="text-align: center;">

						    </div>
						</div>
	    			</td>
	    			<td>
	    				<div class="form-group row">
				        	<label for="cono1" class="control-label col-form-label" >Godwn Name :</label>
				        	<div>
				     			<select class="form-control" style="text-align: center;" id="godown_id">
				     				<option value=" ">Select</option>
				     				@foreach($Godwn as $godwn_row)
				     				<option value="{{$godwn_row->godown_id}}">{{$godwn_row->name}}</option>
				     				@endforeach
				     			</select>
						    </div>
						</div>
	    			</td>
	    			<td>
	    				<div class="row">
                            <div class="col-md-3 heighlightText" style="text-align: right;padding-top: 5px;">
                                Ledger * :</div>
                            <div class="col-md-9">
                                <select  onchange="account_details()" name="account_ledger_id" id="account_ledger_id" class="select2" style="width: 200px" required>
                                </select>

                            </div>
                        </div>
	    			</td>
    			</tr>
    		</table>
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
				     					<option value="{{$item_row->id}}">{{$item_row->name}}</option>
							     		
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

				</tbody>
			</table>


			<table class="table table-responsive table-bordered" style="background-color: #F8F9F9;">
				<tbody>
				 	<tr>
				 		<td colspan="2" style="text-align: right;"> Item :</td>
				 		<td style="width: 150px;text-align: center;" id="total_item">0</td>
				 		<td style="width: 150px;text-align: center;" >Total</td>
				 		<td style="width: 300px;text-align: center;" ><span id="total_sales_price"></span>.00</td>
				 		<td style="width: 65px;"></td>
				 	</tr>
				 	<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">Other bill</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;">
					 	    <input type="text" id="other_bill" oninput="other_bill()" class="form-control" style="text-align: center;" value="0">
					 	</td>
					 	<td style="width: 50px;"></td>
					</tr>
					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">Discount Amount</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;">
					 	    <input type="text" id="discount_total" oninput="discount_total()" class="form-control" style="text-align: center;" value="0">
					 	</td>
					 	<td style="width: 50px;"></td>
					</tr>
					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">All SubTotal Amount</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;"> <span id="all_subtotal_amount"></span></span></td>
					 	<td style="width: 50px;"></td>
					</tr>

					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">Pre. Amountt</td>
					 	<td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;" id="pre_amount_position">

					 	</td>
					 	<td style="width: 50px;"></td>
					</tr>

					<tr>
					 	<td colspan="4" style="text-align: right; font-size: 18px; font-weight: 800;">Total Amountt</td>
					 	<td style="text-align: center;width: 300px;font-size: 18px; font-weight: 800;"><span id="total_amount"></span></td>
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
	     			<textarea class="form-control" id="shipping_details">

	     			</textarea>
			   	</div>
			</div>
    	</div>

    	<div class="col-md-6">
			<div class="form-group row">
	        	<label for="cono1" class="control-label col-form-label" >Delivered To :</label>
	        	<div>
	     			<textarea class="form-control" id="delivered_to_details">

	     			</textarea>
			   	</div>
			</div>
    	</div>

    </div>
                   			<br>
                   			<br>
                   			<br>
                   			<div style="text-align: center; color: #fff; font-weight: 800;">
                   				<button onclick="SaveAllData()"  class="btn btn-primary" style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                   				<button class="btn btn-info" style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save & Print</button>
                   				<button class="btn btn-success" style="color:#fff; font-weight: 800;font-size: 18px;">Save & Print & SMS</button>
                   				<a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                   			</div>

              		</div>
              </div>
            </div>
        <div>

</div>
</form>

<div id="sxan"></div>


<script>

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
    				newProduct();
    				cleardata();
    				account_details();
    			},

    		})
    	}
//----------------------------end store addondemoproduct----------------------------------------

//----------------------------Start newProduct----------------------------------------
		function newProduct(){
    		var product_id_list = $('#product_id_list').val();
    		var data_add_for_list = $('#data_add_for_list').val();

    		$.ajax({
    			type:"GET",
    			dataType: "json",
    			url:"{{url('/product_new_fild/-')}}"+product_id_list,

    			success:function(response){
    					var data =""
    					var Total_cost =""
    					var Total_item =""
    				$.each(response, function(key, value){
	    				data = data + "<tr>"
    					data = data + "<td style='text-align:center;'>"+value.item_name+"</td>"
    					data = data + "<td style='width:100px;text-align:center;'>"+value.qty+"</td>"
    					data = data + "<td style='width:150px;text-align:center;'>"+value.sales_price+"</td>"
    					data = data + "<td style='text-align: center; width:150px;'>"+value.discount+"</td>"
    					data = data + "<td style='text-align: center; width:300px;font-size:16px;'>"+value.subtotal_on_product+"</td>"
    					data = data + "<td style='text-align: center; width:50px;'>"
    					data = data +"<a class='btn btn-sm btn-danger' onclick='delete_data("+value.id_row+")'><i class='fa fa-trash'></i></a>"
    					data = data+"</td>"

	    				data = data + "</tr>";
	    				Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product)
	    				Total_item = Number(Total_item)+ Number(value.qty)

    				});


    				$('#total_item').html(Total_item);
    				$('#total_sales_price').html(Total_cost);
    				$('#all_subtotal_amount').html(Total_cost);

    				$('#data_add_for_list').html(data);


    				account_details();
    				Total_cost_x = Number(all_total_product_price_as_pr_amount)+ Number(Total_cost)
    				$('#total_amount').html(Total_cost_x);
    			}
    		})


    	}

    	newProduct();
//----------------------------End newProduct----------------------------------------
//----------------------------start Remove addondemoproduct----------------------------------------
	function delete_data(id_row){

		$.ajax({
    			type:"GET",
    			dataType: "json",
    			url:"{{url('/product_delete_fild/-')}}"+id_row,

    			success:function(response){

    				$.each(response, function(key, value){
    					  cleardata();
    					console.log('561456 hello '+ id_row);
    				})



    			}
    		})
		newProduct();

	}
//----------------------------end Remove addondemoproduct----------------------------------------

	function other_bill(){
	    		var other_bill = $('#other_bill').val();
	    		var discount_total = $('#discount_total').val();
	    		var product_id_list = $('#product_id_list').val();
	    		var pre_amount = $('#pre_amount').val();

	    		var all_subtotal_amount =""
	    		$('#all_subtotal_amount').val('');
	    		$('#total_amount').val('');
	    		$.ajax({
	    			type:"GET",
	    			dataType: "json",
	    			url:"{{url('/product_new_fild/-')}}"+product_id_list,

	    			success:function(response){
	    					var Total_cost =""
	    				$.each(response, function(key, value){

		    				Total_cost =  Number(Total_cost)+ Number(value.subtotal_on_product)
	    				});

	    				all_subtotal_amount =( Number(Total_cost) + Number(other_bill) )- Number(discount_total)
	    				$('#all_subtotal_amount').html(all_subtotal_amount);
	    				total_amount =  Number(all_subtotal_amount)+ Number(pre_amount)
	    				$('#total_amount').html(total_amount);

	    			}
	    		})



	}

	function discount_total(){
	    		var other_bill = $('#other_bill').val();
	    		var discount_total = $('#discount_total').val();
	    		var product_id_list = $('#product_id_list').val();
	    		var pre_amount = $('#pre_amount').val();

	    		var all_subtotal_amount =""
	    		$('#all_subtotal_amount').val('');
	    		$('#total_amount').val('');
	    		$.ajax({
	    			type:"GET",
	    			dataType: "json",
	    			url:"{{url('/product_new_fild/-')}}"+product_id_list,

	    			success:function(response){
	    					var Total_cost =""
	    				$.each(response, function(key, value){

		    				Total_cost =  Number(Total_cost)+ Number(value.subtotal_on_product)
	    				});

	    				all_subtotal_amount =( Number(Total_cost) + Number(other_bill) )- Number(discount_total)
	    				$('#all_subtotal_amount').html(all_subtotal_amount);
	    				total_amount =  Number(all_subtotal_amount)+ Number(pre_amount)
	    				$('#total_amount').html(total_amount);

	    			}
	    		})

	}



	function SaveAllData(){
			var date = $('#date').val();
    		var product_id_list = $('#product_id_list').val();
    		var godown_id = $('#godown_id').val();
    		var SaleMan_name = $('#SaleMan_name').val();
    		var account_ladger = $('#account_ladger').val();
    		var order_no = $('#order_no').val();
    		var other_bill = $('#other_bill').val();
    		var discount_total = $('#discount_total').val();
    		var pre_amount = $('#pre_amount').val();
    		var shipping_details = $('#shipping_details').val();
    		var delivered_to_details = $('#delivered_to_details').val();

    		$.ajax({

    			type:"POST",
    			dataType:"json",
    			url:"{{url('/SaveAllData/sales_order/store/')}}",
    			data: {
    				date:date,
    				product_id_list:product_id_list,
    				godown_id:godown_id,
    				SaleMan_name:SaleMan_name,
    				account_ladger:account_ladger,
    				order_no:order_no,
    				other_bill:other_bill,
    				discount_total:discount_total,
    				pre_amount:pre_amount,
    				shipping_details:shipping_details,
    				delivered_to_details:delivered_to_details,
    				"_token": "{{ csrf_token() }}",
    			},

	    			success:function(response){

	    				console.log("Hello data save");

	    				$(document).ready(function () {
		    				setTimeout(function ()
		    				{window.location.href = "{{ route('sales_order_addlist')}}";}, 3000);

		    				});

	    				//----start sweet alert------------------
	    				const Msg = Swal.mixin({
	    							  toast: true,
									  position: 'top-end',
									  icon: 'success',
									  showConfirmButton: false,
									  timer: 3000,
                                      background: '#E6EFC4',
									})

	    							Msg.fire({

									  type: 'success',
									  title: 'Sales Order is Added Successfully',

									})
    					//----end sweet alert------------------
	    			},
    		})
	}

	function clearAlldataAndNewPage(){
			$('#date').val('');
    		$('#product_id_list').val('');
  			$('#godown_id').val('');
    		$('#SaleMan_name').val('');
    		$('#account_ladger').val('');
			$('#order_no').val('');
  			$('#other_bill').val('');
    		$('#discount_total').val('');
  			$('#pre_amount').val('');
    		$('#shipping_details').val('');
    		$('#delivered_to_details').val('');
	}


</script>



@endsection
