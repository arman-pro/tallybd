@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Add Sale Order")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <form action="{{ url('/SaveAllData/sales_order/store/') }}" method="post">
                @csrf
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">Add Sale Order</h4>
                </div>
                    <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row">
                        <input type="hidden" name="page_name" value="sales_order_addlist" id="page_name">
                        <div class="col-md-12">

                            <table class="table" style="border-color: white;">
                                <tr>
                                    <td style="padding: 5px 5px;width: 250px;">
                                        <div class="row">
                                            <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Date *:
                                            </div>
                                            <div class="col-md-8">
                                                <input type="date" name="date" id="date" class="form-control"
                                                    value="{{ date('Y-m-d') }}" />
                                            </div>
                                    </td>
                                    <td style="padding: 5px 5px;width: 300px;">
                                        <div class="row">
                                            <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Vch.
                                                No *:
                                            </div>
                                            <div class="col-md-8">
                                                @php
                                                    use App\SalesOrderAddList;

                                                    $product_id_list = App\Helpers\Helper::IDGenerator(new SalesOrderAddList, 'product_id_list', 4, 'SO.No');

                                                @endphp
                                                <input type="text" class="form-control" name="product_id_list"
                                                    id="product_id_list" value="{{ $product_id_list }}" readonly
                                                    style="text-align: center;font-size: 12px;">
                                            </div>
                                        </div>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                        <div class="row">
                                            <div class="col-md-3 heighlightText" style="text-align: right;padding-top: 5px;">
                                                Ledger * :</div>
                                            <div class="col-md-9">
                                                <select 
                                                    name="account_ledger_id" id="account_ledger_id" 
                                                    class="select2 form-control" style="width: 200px" required
                                                    data-placeholder="Select a Ledger"
                                                >
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                        

                                </tr>
                                    
                            </table>
                            
                        </div>



                        <div class="col-md-12">
                            <table class="table table-bordered " style="background-color: #F8F9F9;">
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
                                            <select  
                                                onchange="Product()" 
                                                id="item_name" name="item_name" class="select2item form-control" 
                                                style="width: 200px" data-placeholder="Select a Product"
                                            >
                                            </select>
                                        </td>
                                        <td style="width:100px;">
                                            <input type="text" name="qty_product_value" id="qty_product_value"
                                                class="form-control" style="text-align: center;" value="0"
                                                oninput="qty_product()"autocomplete="off">
                                        </td>
                                        <td style="width:150px;" id="sales_price"></td>
                                        <td style="text-align: center; width:150px;">
                                            <input type="text" name="discount_on_product" id="discount_on_product"
                                                oninput="qty_product()" class="form-control" style="text-align: center;"
                                                value="0">
                                        </td>
                                        <td style="text-align: center; width:300px;font-size:16px;" id="hi"><span
                                                id="subtotal_on_qty"></span><span id="subtotal_on_discount"></span>.00
                                        </td>
                                        <td style="text-align: center; width:50px;">
                                            <a class="btn btn-sm btn-info" onclick="addondemoproduct()"><i
                                                    class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table heighlightText" style="border: 1px solid #eee;text-align: center;" id="myTable">
                                <thead>
                                    <tr style="background-color: #D6DBDF;">
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 300px;">Product</td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Quantity
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 150px;">Price</td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 150px;">Discount
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 250px;">Subtotal
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 50px;">#</td>
                                    </tr>

                                </thead>

                                <tbody style="background: #F8F9F9;" >

                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">

                            <table class="table table-responsive table-bordered heighlightText" style="background-color: #F8F9F9;"id="myTable" >
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="text-align: right;"> Item :</td>
                                        <td style="width: 150px;text-align: center;" id="total_item">0</td>
                                        <td style="width: 150px;text-align: center;">Total</td>
                                        <td style="width: 300px;text-align: center;"><span
                                                id="total_sales_price"></span></td>
                                        <td style="width: 65px;"></td>
                                    </tr>

                                    <tr>
                                        <td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">
                                            All SubTotal Amount</td>
                                        <td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;">
                                            <span id="all_subtotal_amount"></span></span></td>
                                        <td style="width: 50px;"></td>
                                    </tr>



                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row" style="background:#F8F9F9;margin:0 5px">

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="cono1" class="control-label col-form-label heighlightText">Shipping Details :</label>
                                <div>
                                    <textarea class="form-control" id="shipping_details" name="shipping_details"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="cono1" class="control-label col-form-label heighlightText">Delivered To :</label>
                                <div>
                                    <textarea class="form-control" id="delivered_to_details" name="delivered_to_details"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" onclick="" class="btn btn-primary" ><b>Save</b></button>
                        <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Save & Print</b></button>
                        <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



     

    function currentData(){
        var subTotal = 0;
        var subQty = 0;
        $('.item').each(function() {
            var $this = $(this),
                sum = Number($this.find('.item-charge').val());
                subQty += Number($(this).find('.item-qty').val());
                discount = 0;
                subTotal += sum;
        });
        $('#all_subtotal_amount').html(subTotal.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));
        $('#total_item').html(subQty.toFixed(2));
    }

    function clearOldData(){
        $('#item_name').val('');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').text('');
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
        var pre_amount = $('#pre_amount').val()??0;

        var Subtotal = (price_as_product * qty_product) - discount_on_product

        var product_id_list = $('#product_id_list').val();
        $('#subtotal_on_qty').html(Subtotal);


    }


//----------------------------start store addondemoproduct----------------------------------------

function addondemoproduct(){

        let htmlData = '';
        var product_id_list = $('#product_id_list').text();
        var page_name = $('#page_name').val();
        var date = $('#date').val();
        var item_id = $('#item_name').val();
        var item_name = $('#item_name option:selected').text();
        var qty_product_value = $('#qty_product_value').val()||1;
        var discount_on_product = $('#discount_on_product').val()||0;
        var price_as_product = $('#price_as_product').val();
        var subtotal_on_product = (price_as_product * qty_product_value) - discount_on_product;
        // console.log(item_name, qty_product_value, discount_on_product, price_as_product, subtotal_on_product);

        htmlData += "<tr class='item'>"
        htmlData += "<td  style='display:none'><input type='hidden' name='item_id[]' value='"+item_id+"'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + item_name + "</td>"
        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center' readonly  type ='text' name='qty[]' value='"+qty_product_value+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='price[]' value='"+price_as_product+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'> <input style='border:0;text-align:center' readonly  type ='text' name='discount[]' value='"+discount_on_product+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
        htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
        htmlData += "</td>"
        htmlData +="</tr>";
        $('#myTable tr:last').after(htmlData)
        currentData();
        clearOldData();

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
                data = data + "<td style='text-align:center;'>"+value.item.name+"</td>"
                data = data + "<td style='width:100px;text-align:center;'>"+value.qty+"</td>"
                data = data + "<td style='width:150px;text-align:center;'>"+value.price+"</td>"
                data = data + "<td style='text-align: center; width:150px;'>"+value.discount+"</td>"
                data = data + "<td style='text-align: center; width:300px;font-size:16px;'>"+value.subtotal_on_product+"</td>"
                data = data + "<td style='text-align: center; width:50px;'>"
                data = data +"<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
                data = data+"</td>"

                data = data + "</tr>";
                Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product)
                Total_item = Number(Total_item)+ Number(value.qty)

            });


            $('#total_item').html(Total_item);
            $('#total_sales_price').html(Total_cost);
            $('#all_subtotal_amount').html(Total_cost);

            $('#data_add_for_list').html(data);


            //account_details();
            // Total_cost_x = Number(all_total_product_price_as_pr_amount)+ Number(Total_cost)
            // $('#total_amount').html(Total_cost_x);
        }
    })


}


//----------------------------End newProduct----------------------------------------
//----------------------------start Remove addondemoproduct----------------------------------------
function delete_data(delelet) {
    (delelet).closest('tr').remove();
    currentData();
}
//----------------------------end Remove addondemoproduct----------------------------------------



$("#item_name").select2(
        {
            ajax: {
                url: '{{ url("activeItem") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {

                    var res = data.items.map(function (item) {
                            return {id: item.id, text: item.name};
                        });
                    return {
                        results: res
                    };
                }
            },

    });


$("#account_ledger_id").select2(
{
    ajax: {
        url: '{{ url("activeLedger") }}',
        dataType: 'json',
        type: "GET",
        data: function (params) {
            return {
                name: params.term
            };
        },
        processResults: function (data) {

        	var res = data.ledgers.map(function (item) {
                	return {id: item.id, text: item.account_name};
                });
            return {
                results: res
            };
        }
    },

});
</script>
@endpush
