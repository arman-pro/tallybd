@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form action="{{url('/SaveAllData/store/')}}" method="post">
                        <div class="card-body" style="overflow-x:auto;border: 2px solid #69C6E0;border-radius: 5px;">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <h3 style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">
                                Add Purchases</h3>

                            <div class="row">
                                <input type="hidden" name="page_name" value="purchases_addlist" id="page_name">
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
                                                            use App\PurchasesAddList;
                                                            $product_id_list = App\Helpers\Helper::IDGenerator(new PurchasesAddList(), 'product_id_list', 4, 'Pr');
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
                                                            @foreach ($godown as $key => $godown_row)
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
                                                            style="text-align: center;font-size: 12px;" name="SaleMan_name" id="SaleMan_name" >
                                                            <option value=" ">Select</option>
                                                            @foreach ($SaleMan as $key => $saleMan_row)
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
                                                    <div class="col-md-3 heighlightText" style="text-align: right;padding-top: 5px;">
                                                        Address :
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div id="address">

                                                        </div>
                                                    </div>
                                                </div>
                                            </td> 

                                            
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table" style="border: 1px solid #eee;text-align: center;"
                                        id="myTable">
                                        <tr class="heighlightText" style="background-color: #D6DBDF;">
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
                                        <tbody style="background: #F8F9F9;" id="data_add_for_list">

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <table class="table"
                                        style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;">
                                        <tr>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">

                                                <select  onchange="Product()" id="item_name" name="item_name"class="select2item" style="width: 200px" >
                                                </select>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                                <input type="text" name="qty_product_value" id="qty_product_value"
                                                    class="form-control" style="text-align: center;height: 30px;" value=""
                                                    oninput="qty_product()"autocomplete="off"/>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"
                                                id="sales_price"></td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">
                                                <input type="text" name="discount_on_product" id="discount_on_product"
                                                    oninput="qty_product()" class="form-control"
                                                    style="text-align: center;height: 30px;" value="" readonly />
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-size: 14px;"
                                                id="hi"><input type="text" id="subtotal_on_qty"><span
                                                    id="subtotal_on_discount"></span>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">
                                                <a class="btn btn-sm btn-info" onclick="addondemoproduct()"><i
                                                        class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table" style="background-color: #F8F9F9;">
                                        <tbody>
                                            <tr class="heighlightText">
                                                <td colspan="2" style="text-align: right;"> Item :</td>
                                                <td style="width: 150px;text-align: center;" id="total_item">0</td>
                                                <td style="width: 150px;text-align: center;">Total</td>
                                                <td style="width: 300px;text-align: center;"><span
                                                        id="total_sales_price"></span></td>
                                                <td style="width: 65px;"></td>
                                            </tr>

                                            <tr class="heighlightText">
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
                                <button type="submit" class="btn btn-primary"
                                    style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                                <button type="submit" name="print" value="1" class="btn btn-info"
                                    style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save & Print</button>
                                <a href="{{ route('mb_cor_index') }}" class="btn btn-danger">Cencel</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>


            <div id="sxan"></div>



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

                	var res = data.ledgers.map(function (item) {
                        	return {id: item.id, text: item.account_name};
                        });
                    return {
                        results: res
                    };
                }
            },

    });
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

    function cleardata(){
        $('#qty_product_value').val('0');
        $('#discount_on_product').val('0');
        $('#price_as_product').val('0');
        $('#item_name').val(null).trigger('change');
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

                    item_price = value.purchases_price
                    item = '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" style="text-align: center;height:30px;" value="'+item_price+'">'

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
        $('#subtotal_on_qty').val(Subtotal);


    }

    var old_item_id= [];
    function addondemoproduct() {
        var item_id = $('#item_name').val();
        // if( $.inArray(item_id, old_item_id) !== -1 ) {
            //----start sweet alert------------------
                // const Msg = Swal.mixin({
                //     toast: true,
                //     position: 'top-end',
                //     icon: 'warning',
                //     showConfirmButton: false,
                //     timer: 3000,
                //     background: '#E6EFC4',
                // })

                // Msg.fire({
                //     type: 'warning',
                //     title: 'Already Added This!',

                // })
            //----end sweet alert------------------
        //     return false;
        // }else{
        //     old_item_id.push(item_id);
        // }
        old_item_id.push(item_id);
        let htmlData = '';
        var product_id_list = $('#product_id_list').text();
        var page_name = $('#page_name').val();
        var date = $('#date').val();

        var item_name = $('#item_name option:selected').text();
        var qty_product_value = $('#qty_product_value').val()||1;
        var discount_on_product = $('#discount_on_product').val()||0;
        var price_as_product = $('#price_as_product').val();
        var subtotal_on_product = (price_as_product * qty_product_value) - discount_on_product;

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
        var other_bill =Number($('#other_bill').val());
        totalBill =(subTotal+other_bill); 
        $('#totalAmount').html(totalBill.toFixed(2));
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
        $('#item_name').val(null).trigger('change');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').val('');
    }





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
                    data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>"+value.item.name+"</td>"
                    data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'>"+value.qty+"</td>"
                    data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.price+"</td>"
                    data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.discount+"</td>"
                    data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'>"+value.subtotal_on_product+"</td>"
                    data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
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

            }
        })


    }


    function delete_data(delelet) {
        (delelet).closest('tr').remove();

        currentData();


    }

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
