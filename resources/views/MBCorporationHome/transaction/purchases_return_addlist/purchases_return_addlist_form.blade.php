@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

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
                    <h3 class="card-title"
                        style=" font-weight: 600; padding-bottom: 10px;background-color: #69E643; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">
                        Add Purchases Return</h3>


                    <form action="{{ url('/SaveAllData/return/store/') }}" method="post">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="page_name" value="purchases_return_addlist" id="page_name">
                            <div class="col-md-12">
                                <table class="table table-responsive heighlightText" cellspacing="0">
                                    <tr>
                                        <td>
                                            <div class="form-group row">
                                                <label for="cono1" class="control-label col-form-label">Date *:</label>
                                                <div>
                                                    <input type="date" name="date" id="date" n class="form-control"
                                                        value="{{ date('Y-m-d') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group row">
                                                <label for="cono1" class="control-label col-form-label">Vch.No *:</label>
                                                <div>
                                                    @php
                                                    use App\PurchasesReturnAddList;

                                                    $product_id_list = App\Helpers\Helper::IDGenerator(new
                                                    PurchasesReturnAddList, 'product_id_list', 3, 'PuR');

                                                    @endphp
                                                    <input type="text" class="form-control" name="product_id_list"
                                                        id="product_id_list" value="{{$product_id_list}}"
                                                        style="text-align: center;" readonly>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group row">
                                                <label for="cono1" class="control-label col-form-label">Godwn Name *:</label>
                                                <div>
                                                    <select class="form-control" style="text-align: center;" name="godown_id" id="godown_id" required>
                                                        <option value=" ">Select</option>
                                                        @foreach($Godwn as $godwn_row)
                                                        <option value="{{$godwn_row->id}}">{{$godwn_row->name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group row">
                                                <label for="cono1" class="control-label col-form-label">SaleMan *:</label>
                                                <div>
                                                    <select class="form-control" style="text-align: center;"
                                                        id="SaleMan_name" name="SaleMan_name" required>
                                                        <option value=" ">Select</option>
                                                        @foreach($SaleMan as $saleMan_row)
                                                        <option value="{{$saleMan_row->id}}">
                                                            {{$saleMan_row->salesman_name}}</option>
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
                                    <label for="cono1" class="control-label col-form-label heighlightText">Account Ledger</label>
                                        <select  onchange="account_details()" name="account_ledger_id" id="account_ledger_id" class="select2" style="width: 100%" required>
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label heighlightText">Phone :</label>
                                    <div id="phone">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label heighlightText" >Address :</label>
                                    <div id="address">

                                    </div>
                                </div>
                            </div>

                            <div id="account_pre_amount">

                            </div>

                            <div class="col-md-12">
                                <table class="table table-bordered" style="background-color: #F8F9F9;">
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
                                                <select  onchange="Product()" id="item_name" name="item_name"class="select2item" style="width: 200px" >
                                                </select>
                                            </td>
                                            <td style="width:100px;">
                                                <input type="text" name="qty_product_value" id="qty_product_value"
                                                    class="form-control" style="text-align: center;" value="0"
                                                    oninput="qty_product()">
                                            </td>
                                            <td style="width:150px;" id="sales_price"></td>
                                            <td style="text-align: center; width:150px;">
                                                <input type="text" name="discount_on_product" id="discount_on_product"
                                                    oninput="qty_product()" class="form-control" style="text-align: center;"
                                                    value="0">
                                            </td>
                                            <td style="text-align: center; width:300px;font-size:16px;" id="hi"><span
                                                    id="subtotal_on_qty"></span><span id="subtotal_on_discount"></span>
                                            </td>
                                            <td style="text-align: center; width:50px;">
                                                <a class="btn btn-sm btn-info" onclick="addondemoproduct()"><i
                                                        class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-bordered" style="background-color: #F8F9F9;"  id="myTable">
                                    <tbody id="">
                                        <tr></tr>

                                    </tbody>
                                </table>


                                <table class="table table-responsive table-bordered" style="background-color: #F8F9F9;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="text-align: right;"> Item :</td>
                                            <td style="width: 150px;text-align: center;" id="total_item">0</td>
                                            <td style="width: 150px;text-align: center;">Total</td>
                                            <td style="width: 300px;text-align: center;"><span
                                                    id="total_sales_price"></span>.00</td>
                                            <td style="width: 65px;"></td>
                                        </tr>


                                        <tr>
                                            <td colspan="4" style="text-align: right; font-size: 16px; font-weight: 600;">
                                                All SubTotal Amount</td>
                                            <td style="text-align: center;width: 300px;font-size: 16px; font-weight: 600;">
                                                <span id="all_subtotal_amount"></span></span>
                                            </td>
                                            <td style="width: 50px;"></td>
                                        </tr>




                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row" style="background:#F8F9F9;margin:0 5px">

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Shipping Details :</label>
                                    <div>
                                        <textarea class="form-control" id="shipping_details" name="shipping_details"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Delivered To :</label>
                                    <div>
                                        <textarea class="form-control" id="delivered_to_details" name="delivered_to_details"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br>
                        <br>
                        <br>
                        <div style="text-align: center; color: #fff; font-weight: 800;">
                            <button  type="submit"class="btn btn-primary"
                                style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                            <button class="btn btn-info" name="print" value="1"
                                style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save & Print</button>

                            <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
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
    $(".select2").select2(
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
                console.log(data);
                var res = data.ledgers.map(function (item) {
                        return {id: item.id, text: item.account_name};
                    });
                return {
                    results: res
                };
            }
        },

});

    function clearData(){
        $('#item_name').val(null).trigger('change');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').text('');
    }
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
        console.log(subTotal);
        $('#all_subtotal_amount').html(subTotal.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));

        $('#total_item').html(subQty.toFixed(2));
    }


    function Product(){

        var item_name = $('#item_name').val();

        $.ajax({
            type:"GET",
            dataType: "json",
            url:"{{url('/product_as_price/-')}}"+item_name,

            success:function(response){
                var item, item_price;
                $.each(response, function(key, value){
                    item_price = value.purchases_price
                    item = '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" style="text-align: center;" value="'+item_price+'">'

                })

                $('#sales_price').html(item);
                $('#subtotal').html(item_price);


            }
        })


    }

    function account_details(){

        var account_ledger_id = $('#account_ledger_id').val();

        $.ajax({
            type:"GET",
            dataType: "json",
            url:"{{url('/account_details_for_invoice/-')}}"+account_ledger_id,

            success:function(response){
                var phone;
                var address;
                var account_pre_amount;
                var account_id_for_preamound;
                $.each(response, function(key, value){
                    phone = '<input type="text" name="price" class="form-control" style="text-align: center;" value="'+value.account_ledger_phone+'">'
                    address = '<input type="text" name="price" class="form-control" style="text-align: center;" value="'+value.account_ledger_address+'">'
                    account_id_for_preamound = value.account_ledger_id
                    $('#phone').html(phone);
                    $('#address').html(address);
                })
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


    }

    var old_item_id= [];
    function addondemoproduct() {

        var item_id = $('#item_name').val();
        if( $.inArray(item_id, old_item_id) !== -1 ) {
            //----start sweet alert------------------
                const Msg = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 3000,
                    background: '#E6EFC4',
                })

                Msg.fire({
                    type: 'warning',
                    title: 'Already Added This!',

                })
            //----end sweet alert------------------
            return false;
        }else{
            old_item_id.push(item_id);
        }
        let htmlData = '';
        var product_id_list = $('#product_id_list').text();
        var page_name = $('#page_name').val();
        var date = $('#date').val();
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
        clearData();

    }


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
                    data = data + "<td style='width:150px;text-align:center;'>"+value.item.purchases_price+"</td>"
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


                account_details();
                // Total_cost_x = Number(all_total_product_price_as_pr_amount)+ Number(Total_cost)
                // $('#total_amount').html(Total_cost_x);
            }
        })


    }

    newProduct();

function delete_data(delelet){

    (delelet).closest('tr').remove();
    currentData();

}
$(".select2item").select2(
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



</script>

@endpush
