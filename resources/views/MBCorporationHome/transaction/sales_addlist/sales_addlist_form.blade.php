@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ url('/SaveAllData_sales/store/') }}" method="post">
                    @csrf

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
                        <h2 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">
                            Add Sales</h2>



                        <div class="row">
                            <input type="hidden" name="page_name" value="sales_addlist" id="page_name">
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
                                                      use App\SalesAddList;

                                                        $product_id_list = App\Helpers\Helper::IDGenerator(new SalesAddList,
                                                        'product_id_list', 4, 'Sl');
                                                    @endphp
                                                    <input type="text" class="form-control" name="product_id_list"
                                                        id="product_id_list" value="{{ $product_id_list }}" readonly
                                                        style="text-align: center;font-size: 12px;">
                                                </div>
                                            </div>
                                        </td>

                                        <td style="padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">
                                                    Godown *:</div>
                                                <div class="col-md-8">
                                                    <select class="form-control" required
                                                        style="text-align: center;font-size: 12px;" name="godown_id" id="godown_id">
                                                        <option value="{{ null }} ">Select</option>
                                                        @foreach ($Godwn as $key => $godown_row)
                                                            <option @if($key == 0) selected @endif value="{{ $godown_row->id }}">{{ $godown_row->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>


                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText"  style="text-align: right;padding-top: 5px;">Sale Man *:</div>
                                                <div class="col-md-8">
                                                    <select class="form-control"required
                                                        style="text-align: center;font-size: 12px;" name="SaleMen_name" id="SaleMan_name" >
                                                        <option value=" ">Select</option>
                                                        @foreach ($SaleMen as $key => $saleMan_row)
                                                            <option @if($key == 0) selected @endif value="{{ $saleMan_row->id }}">
                                                                {{ $saleMan_row->salesman_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <table class="table" style="">
                                    <tr>
                                        <td style="padding: 5px 5px;width: 400px;">
                                            <div class="row">
                                                <div class="col-md-3 heighlightText" style="text-align: right;padding-top: 5px;">
                                                    Ledger * :</div>
                                                <div class="col-md-9">
                                                    <select  onchange="account_details()" name="account_ledger_id" id="account_ledger_id" class="select2" style="width: 200px" required>
                                                    </select>

                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 5px 5px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">
                                                    Phone :
                                                </div>
                                                <div class="col-md-8">
                                                    <div id="phone">

                                                    </div>
                                                </div>
                                            </div>
                                        </td>


                                            <td style="padding: 5px 5px;width: 350px;">
                                            <div class="row">
                                                <div class="col-md-3" style="text-align: right;padding-top: 5px;"> Address :</div>
                                                <div class="col-md-9">
                                                    <div id="address">

                                                    </div>
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
                                                {{-- <select class="form-control" id="item_name" name="item_name"
                                                    style="text-align: center;" onclick="Product()">
                                                    <option value="">Select</option>
                                                    @foreach($Item as $item_row)
                                                    <option value="{{$item_row->id}}">{{$item_row->name}}
                                                    </option>
                                                    @endforeach
                                                </select> --}}
                                                <select  onchange="Product()" id="item_name" name="item_name" class="select2item" style="width: 200px" >
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
                                                    value="0" readonly />
                                            </td>
                                            <td style="text-align: center; width:300px;font-size:16px;" id="hi"><input type="text"
                                                    id="subtotal_on_qty"><span id="subtotal_on_discount"></span>.00
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

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label heighlightText"> Others Expense Ledger:</label>
                                    <div>
                                        <select  onchange="account_details()" name="expense_ledger_id" id="expense_ledger_id"  style="width: 200px" >
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label heighlightText">Others Expense Amount :</label>
                                    <div>
                                        <input type="text" name="other_expense" id="other_bill" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label heighlightText">Total Amount :</label>
                                    <div>
                                        <span id="totalAmount"></span>
                                    </div>
                                </div>
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
                        
                        <!--send sms-->
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" name="send_sms" value="yes" class="form-check-input" id="send_sms">
                                <label class="form-check-label" for="send_sms">Send SMS</label>
                            </div>
                        </div>
                        
                        <br>
                        <br>
                        <br>
                        <div style="text-align: center; color: #fff; font-weight: 800;">
                            <button onclick="" class="btn btn-primary"
                                style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                                <button type="submit" class="btn btn-info" name="print" value="1"
                                style="color:#fff; font-weight: 800;font-size: 18px;">Save & Print</button>
                            <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                        </div>

                    </div>
                </form>
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

    function currentData(){
        var subTotal = 0;
        var subQty = 0;
        var totalBill = 0;
        $('.item').each(function() {
            var $this = $(this),
                sum = Number($this.find('.item-charge').val());
                subQty += Number($(this).find('.item-qty').val());
                discount = 0;
                subTotal += sum;
        });
        $('#all_subtotal_amount').html(subTotal.toFixed(2));
        var other_bill =Number($('#other_bill').val());
        totalBill =(subTotal+other_bill); 
        $('#totalAmount').html(totalBill.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));
        $('#total_item').html(subQty.toFixed(2));
    }
    $('#other_bill').keyup(function(){
        var other_bill =Number($(this).val());
        var subTotal =Number($('#total_sales_price').html());
        var totalBill =(subTotal+other_bill); 
         $('#totalAmount').html(totalBill.toFixed(2));
    });
    
    $('#subtotal_on_qty').keyup(function(){
        var subtotal_on_qty =Number($(this).val());
        var qty_product_value = Number($('#qty_product_value').val());
        
        var totalBill =(subtotal_on_qty/qty_product_value); 
        $('#price_as_product').val(totalBill);
    });   

    function clearOldData(){
        $('#item_name').val('');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').val('');
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
        var pre_amount = $('#pre_amount').val()??0;

        var Subtotal = (price_as_product * qty_product) - discount_on_product

        var product_id_list = $('#product_id_list').val();
        $('#subtotal_on_qty').val(Subtotal);


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


            account_details();
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




function SaveAllData(){
    var date = $('#date').val();
    var product_id_list = $('#product_id_list').val();
    var godown_id = $('#godown_id').val();
    var SaleMen_name = $('#SaleMen_name').val();
    var account_ledger_id = $('#account_ledger_id').val();
    var order_no = $('#order_no').val();
    var other_bill = $('#other_bill').val();
    var discount_total = $('#discount_total').val();
    var pre_amount = $('#pre_amount').val();
    var shipping_details = $('#shipping_details').val();
    var delivered_to_details = $('#delivered_to_details').val();

    $.ajax({
        type:"GET",
        dataType:"json",
        url:"{{url('/SaveAllData_sales/store/')}}",
        data: {
            date:date,
            product_id_list:product_id_list,
            godown_id:godown_id,
            SaleMen_name:SaleMen_name,
            account_ledger_id:account_ledger_id,
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
                    {window.location.href = "{{ route('sales_addlist')}}";}, 3000);

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
                              title: 'Sales is Added Successfully',

                            })
                //----end sweet alert------------------
            },
    })
}


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
    $("#expense_ledger_id").select2({
            ajax: {
                url: '{{ url("expenseLedger") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {
                    console.log(data);
                    var res = data.items.map(function (item) {
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
