@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Update Sale")

@section('admin_content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">Update Sale</h4>
                </div>
                <div class="card-body">
                    <form action="{{URL::to('/Update/Sales/'.$salesAddList->id)}}" method="POST">
                        <input type="hidden" name="page_name" value="sales_addlist" id="page_name">
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

                        <div class="form-group row">
                            <div class="col-md-4 col-sm-12">
                                <label>Date*</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{$salesAddList->date}}" required />
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label>Vo. No*</label>
                                <input 
                                    type="text" class="form-control" 
                                    name="product_id_list" id="product_id_list" 
                                    value="{{$salesAddList->product_id_list}}"
                                    readonly required
                                />
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label>Godown Name*</label>
                                <select 
                                    class="form-control" id="godown_id"
                                    name="godown_id" readonly
                                    data-placeholder="Godown Name" required
                                >
                                    <option value="{{$salesAddList->godown_id}}">{{$salesAddList->godown->name}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4 col-sm-12">
                                <label>Sale Man Name*</label>
                                <select 
                                    class="form-control" id="SaleMan_name"
                                    name="SaleMen_name" readonly
                                    data-placeholder="Sale Man Name"
                                    required
                                >
                                    <option value="{{$salesAddList->sale_name_id}}">
                                        {{optional($salesAddList->saleMen)->salesman_name??" "}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label>Account Ledger*</label>
                                <select 
                                    class="form-control" name="account_ledger_id"
                                    id="account_ledger_id" onclick="account_details()"
                                    readonly data-placeholder="Select Account Ledger" required
                                >
                                    <option value="{{$salesAddList->account_ledger_id}}" selected>
                                        {{optional($salesAddList->ledger)->account_name??" "}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label>Phone</label>
                                <input type="tel" name="phone" id="phone" placeholder="Phone" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" id="address" placeholder="Address" class="form-control" />
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 col-sm-12">
                                <table class="table table-bordered" id="product_list">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th style="width: 150px;">Quantity</th>
                                            <th style="width: 150px;">Price</th>
                                            <th style="width: 150px;">Discount</th>
                                            <th style="width: 150px;">Subtotal</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>
                                                <select  
                                                    onchange="Product()" id="item_name" 
                                                    name="item_name" class="select2item form-control"
                                                    data-placeholder="Select a Product"
                                                >
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="qty_product_value" id="qty_product_value"
                                                    class="form-control" min="0" step="any"
                                                    oninput="qty_product()" placeholder="Quantity"
                                                />
                                            </td>
                                            <td id="sales_price"></td>
                                            <td>
                                                <input 
                                                    type="number" step="any" name="discount_on_product" 
                                                    id="discount_on_product" oninput="qty_product()" 
                                                    class="form-control" min="0" placeholder="Discount" readonly />
                                            </td>
                                            <td id="hi">
                                                <input type="number" name="subtotal_on_qty" id="subtotal_on_qty" placeholder="Sub Total" step="any" class="form-control">
                                                {{-- <span id="subtotal_on_discount"></span> --}}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" onclick="addondemoproduct()">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="text-align: right;"><b>Total Qty.</b></td>
                                            <td id="total_item">0</td>
                                            <td class="text-end"><b>Total</b></td>
                                            <td>
                                                <span id="total_sales_price"></span>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2" class="text-end">
                                                <select 
                                                    readonly onchange="account_details()" 
                                                    name="expense_ledger_id" id="expense_ledger_id" 
                                                    data-placeholder="Other Expense Ledger" class="form-control"
                                                >
                                                    <option value="0">None</option>
                                                    @if(!$expense_ledgers->isEmpty())
                                                        @foreach($expense_ledgers as $expense_ledger)
                                                            <option value="{{$expense_ledger->id}}" @if($expense_ledger->id == $salesAddList->expense_ledger_id) selected @endif >{{$expense_ledger->account_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input
                                                    type="number" min="0" name="other_expense" id="other_bill" 
                                                    class="form-control" value="{{$salesAddList->other_bill}}"
                                                    placeholder="Other Expense Amount"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td colspan="2" class="text-end">
                                                <b>Grand Total</b>
                                            </td>
                                            <td>
                                                <input 
                                                    type="number" min="0" step="any" 
                                                    id="totalAmount" value="{{$salesAddList->grand_total }}" 
                                                    class="form-control" placeholder="Grand Total"
                                                />
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-md-6 col-sm-12">
                                <label>Shipping Details</label>
                                <textarea class="form-control" id="shipping_details" name="shipping_details">{!!$salesAddList->shipping_details !!}</textarea>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label>Delivered To</label>
                                <textarea class="form-control" id="delivered_to_details" name="delivered_to_details">{!!$salesAddList->delivered_to_details !!}</textarea>
                            </div>                             
                        </div>
                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-primary"><b>Update</b></button>
                            <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Update & Print</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection
@push('js')
    <script>
        
        $(document).ready(function(){
            $('#expense_ledger_id').select2({
                placeholder: "Select Expense Ledger"
            }); 
        });
    
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
        var other_bill =Number($('#other_bill').val());
        totalBill =(subTotal+other_bill); 
        $('#totalAmount').val(totalBill.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));
        $('#total_item').html(subQty.toFixed(2));
    }
    
    // set other expense none then remove other expense bill
    $('#expense_ledger_id').on('change', function(){
        if($(this).val() == 0) {
            $("#other_bill").val(0);
            var subTotal =Number($('#total_sales_price').html());
            var totalBill =(subTotal + 0); 
            $('#totalAmount').val(totalBill.toFixed(2));
        }
    });
    
    $('#other_bill').keyup(function(){
        var other_bill =Number($(this).val());
        var subTotal =Number($('#total_sales_price').html());
        var totalBill =(subTotal+other_bill); 
         $('#totalAmount').val(totalBill.toFixed(2));
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
    function clearOldData(){
        $('#item_name').val('');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').val('');
        $('#totalAmount').val('');
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
                    phone = value.account_ledger_phone;
                    address = value.account_ledger_address;
                    account_id_for_preamound = value.account_ledger_id
                    $('#phone').val(phone);
                    $('#address').val(address);
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

        htmlData += "<tr class='item'>"
        htmlData += "<td><input type='hidden' name='new_item_id[]' value='"+item_id+"'/>" + item_name + "</td>"
        htmlData += "<td><input class='item-qty form-control'  type ='number' step='any' min='0' name='new_qty[]' value='"+qty_product_value+"' /></td>"
        htmlData += "<td><input readonly  type ='number' class='form-control' name='new_price[]' value='"+price_as_product+"' /> </td>"
        htmlData += "<td><input readonly  type ='number' class='form-control' name='new_discount[]' value='"+discount_on_product+"' /></td>"
        htmlData += "<td><input class='item-charge form-control' readonly  type ='text' name='new_subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
        htmlData += "<td><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
        htmlData +="</tr>";
        $('#product_list tbody').append(htmlData)
        currentData();
        clearOldData();
    }

    newProduct()
//----------------------------end store addondemoproduct----------------------------------------

$(document).on('input', '.item-qty', function(){
    var qty = $(this).val();
    var price = $(this).closest('tr').find('input[name="new_price[]"]').val();
    $(this).closest('tr').find('input[name="new_subtotal[]"]').val(qty * price);
    currentData();
});

//----------------------------Start newProduct----------------------------------------
    function newProduct(){
        var product_id_list = $('#product_id_list').val();
        var data_add_for_list = $('#data_add_for_list').val();

        $.ajax({
            type:"GET",
            dataType: "json",
            url:"{{url('/product_new_fild/-')}}"+product_id_list,

            success:function(response){
                    var data ="";
                    var Total_cost ="";
                    var Total_item ="";
                $.each(response, function(key, value){
                    data += "<tr class='item'>"
                    data += "<td><input type='hidden' name='item_id[]' value='"+value.item.id+"'/>" + value.item.name+ "</td>"
                    data += "<td><input class='item-qty form-control' readonly type='number' name='qty[]' value='"+value.qty+"' /></td>"
                    data += "<td><input class='form-control' readonly  type ='number' step='any' name='price[]' value='"+value.price+"' /> </td>"
                    data += "<td><input class='form-control' readonly  type ='number' step='any' name='discount[]' value='"+value.discount+"' /></td>"
                    data += "<td><input  class='item-charge form-control' readonly  type ='number' step='any' name='subtotal[]' value='"+value.subtotal_on_product+"' /> </td>"
                    data += "<td><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
                    data +="</tr>";
                    Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product);
                    Total_item = Number(Total_item)+ Number(value.qty);
                });

                $('#total_item').html(Total_item);
                $('#total_sales_price').html(Total_cost);
                $('#all_subtotal_amount').html(Total_cost);
                $('#all_subtotal_amount').html(Total_cost);
                $('#product_list tbody').append(data);
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

     
    </script>


@endpush
