@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Update Sale")

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush

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
                                            <th class='p-1'>Product</th>
                                            <th class='p-1' style="width: 130px;">Quantity</th>
                                            <th class='p-1' style="width: 150px;">Main Price</th>
                                            <th class='p-1' style="width: 150px;">Discount Price</th>
                                            <th style="width:90px;" class="fw-bold p-1">Type</th>
                                            <th style="width:130px;" class="fw-bold p-1">Discount</th>
                                            <th class='p-1' style="width: 150px;">Subtotal</th>
                                            <th class='p-1'>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class='p-1'>
                                                <select  
                                                    onchange="Product()" id="item_name" 
                                                    name="item_name" class="select2item form-control"
                                                    data-placeholder="Select a Product"
                                                >
                                                </select>
                                                
                                            </td>
                                            <td class='p-1'>
                                                <input type="number" name="qty_product_value" id="qty_product_value"
                                                    class="form-control" min="0" step="any"
                                                    oninput="qty_product()" placeholder="Quantity"
                                                />
                                            </td>
                                            <td class='p-1' id="main_price"></td>
                                            <td class='p-1' id="sales_price"></td>
                                            <td class='p-1'>
                                                <select class="form-control" id="discount_type">
                                                    <option value="bdt" selected>BDT</option>
                                                    <option value="percent">%</option>
                                                </select>
                                            </td>
                                            <td class='p-1'>
                                                <input 
                                                    type="number" class="form-control" id="discount_calculate" value="0" min="0" step="0" 
                                                    placeholder="Discount"
                                                />
                                                <input 
                                                    type="hidden" name="discount_on_product" id="discount_on_product"
                                                    oninput="qty_product()" value="0" 
                                                />
                                            </td>
                                            <td class='p-1' id="hi">
                                                <input type="number" name="subtotal_on_qty" id="subtotal_on_qty" placeholder="Sub Total" step="any" class="form-control">
                                                {{-- <span id="subtotal_on_discount"></span> --}}
                                            </td>
                                            <td class='p-1'>
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
                                            <td colspan="2" style="text-align: right;" class="p-1"><b>Total Qty.</b> <span id="total_item">0</span></td>
                                            <td class="text-end p-1">
                                                <b>Total Discount:</b> <span id="total_amount_discount">0</span>
                                                <input type="hidden" name="total_amount_discount" value="0" />
                                            </td>
                                            <td class="text-end p-1"><b>Total</b></td>
                                            <td class="p-1">
                                                <span id="total_sales_price"></span>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="p-1"></td>
                                            <td colspan="2" class="text-end p-1">
                                                <select 
                                                    readonly onchange="account_details()" 
                                                    name="expense_ledger_id" id="expense_ledger_id" 
                                                    data-placeholder="Other Expense Ledger" class="form-control"
                                                    data-width="100%" data-allow-clear="true"
                                                >
                                                    <option value="0">None</option>
                                                    @if(!$expense_ledgers->isEmpty())
                                                        @foreach($expense_ledgers as $expense_ledger)
                                                            <option value="{{$expense_ledger->id}}" @if($expense_ledger->id == $salesAddList->expense_ledger_id) selected @endif >{{$expense_ledger->account_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input
                                                    type="number" name="other_expense" id="other_bill" 
                                                    class="form-control" value="{{$salesAddList->other_bill}}"
                                                    placeholder="Other Expense Amount"
                                                />
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" class="p-1"></td>
                                            <td colspan="2" class="text-end p-1">
                                                <b>Grand Total</b>
                                            </td>
                                            <td class="p-1">
                                                <input 
                                                    type="number" step="any" 
                                                    id="totalAmount" value="{{$salesAddList->grand_total }}" 
                                                    class="form-control" placeholder="Grand Total"
                                                />
                                            </td>
                                        </tr>
                                        
                                         <tr>
                                            <td colspan="2" class="p-1"></td>
                                            <td colspan="2" class="text-end p-1">
                                                <select
                                                    onchange="account_details()" 
                                                    name="cash_payment_ledger_id" id="received_ledger_id" 
                                                    data-placeholder="Receive Mode" class="form-control select2cashPayment"
                                                    data-width="100%" data-allow-clear="true"
                                                >
                                                    <option value="" hidden>Receive Mode</option>
                                                    @if(!$payments_ledgers->isEmpty())
                                                        @foreach($payments_ledgers as $payment_ledger)
                                                            <option
                                                                value="{{$payment_ledger->id}}" 
                                                                @if($salesAddList->cash_receive && $payment_ledger->id == $salesAddList->cash_receive->payment_mode_ledger_id)
                                                                    selected 
                                                                @endif
                                                            >
                                                                {{$payment_ledger->account_name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input
                                                    type="number" name="cash_payment" id="cash_payment" 
                                                    class="form-control" value="{{$salesAddList->cash_payment}}"
                                                    placeholder="Receive Amount"
                                                />
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" class="p-1"></td>
                                            <td colspan="2" class="text-end p-1">
                                                <b>Total Due</b>
                                            </td>
                                            <td class="p-1">
                                                <input 
                                                    type="number" readonly step="any" 
                                                    id="totalDue" name="totalDue" value="0" 
                                                    class="form-control" placeholder="Total Due"
                                                />
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" class="p-1"></td>
                                            <td colspan="2" class="text-end p-1 fw-bold">
                                                Closing Balance
                                            </td>
                                            <td class="p-1">
                                                <input 
                                                    type="number" readonly step="any" 
                                                    id="closingBalance" name="closingBalance" value="0" 
                                                    class="form-control" placeholder="Closing Balance"
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
                placeholder: "Select Expense Ledger",
                theme: "bootstrap-5",
            }); 
        });
    
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });
        
    $('.select2cashPayment').select2({
        theme: "bootstrap-5",
    });

    function currentData(){
        var subTotal = 0;
        var subQty = 0;
        var total_amount_discount = 0;
        $('.item').each(function() {
            var $this = $(this),
                sum = Number($this.find('.item-charge').val());
                subQty += Number($(this).find('.item-qty').val());
                discount = 0;
                subTotal += sum;
                total_amount_discount += +$(this).find("input[name='discount[]']").val() || 0;
        });
        
        $('#all_subtotal_amount').html(subTotal.toFixed(2));
        var other_bill =Number($('#other_bill').val());
        totalBill =(subTotal+other_bill); 
        $('#totalAmount').val(totalBill.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));
        $('#total_item').html(subQty.toFixed(2));
        $('#total_amount_discount').html(total_amount_discount);
        $('input[name="total_amount_discount"]').val(total_amount_discount);
        
        var cash_payment = $('#cash_payment').val() || 0;
        $('#totalDue').val(+totalBill - +cash_payment);
    }
    
    $(document).on('input', '#cash_payment', currentData);
    
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
        var other_bill = Number($(this).val());
        var subTotal = Number($('#total_sales_price').html());
        var totalBill = (subTotal+other_bill); 
        $('#totalAmount').val(totalBill.toFixed(2));
    });  
    
    $('#subtotal_on_qty').keyup(function(){
        var subtotal_on_qty =Number($(this).val());
        var qty_product_value = Number($('#qty_product_value').val());
        
        var totalBill = (subtotal_on_qty/qty_product_value); 
        $('#price_as_product').val(Number(totalBill).toFixed(2));
        $('#product_main_price').val(Number(totalBill).toFixed(2));
    });   

    
    
    function discount(){
        let qty = $('#qty_product_value').val() || 1;
        let discount = $('#discount_calculate').val() || 0;
        let discount_type = $('#discount_type').val();
        let main_price = $("#product_main_price").val();
        let per_product_discount = 0;
        if(discount_type == 'bdt') {
            discount = discount;
        }else {
            discount = (Number(main_price) * Number(qty) * Number(discount)) / 100;
        }
        $('#discount_on_product').val(discount).trigger('input');
        per_product_discount = Number(discount) / Number(qty);
        $('#price_as_product').val(+main_price - per_product_discount);
    }
    
    $(document).on("input", "#discount_calculate", discount);
    $(document).on("change", "#discount_type", discount);
    
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
        $('#product_main_price').val('');
        $('#discount_calculate').val('');
    }

    function Product(){
        var item_name = $('#item_name').val();
        $.ajax({
            type:"GET",
            dataType: "json",
            url:"{{url('/product_as_price/-')}}"+item_name,

            success:function(response){
                var item;
                var item_price;
                $.each(response, function(key, value){
                    item_price = value.sales_price
                    item = '<input type="number" readonly name="price_as_product" id="price_as_product" class="form-control" value="'+item_price+'">'
                    main_price = '<input type="number" name="product_main_price" id="product_main_price" oninput="qty_product()" class="form-control text-center" value="'+item_price+'">'
                });
                $('#sales_price').html(item);
                $('#main_price').html(main_price);
                $('#subtotal').html(item_price);
            }
        });
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
        var price_as_product = $('#product_main_price').val();
        $('#subtotal_on_discount').hide();
        $('#subtotal_on_qty').show();
        $('#total_sales_price').val('');
        $('#all_subtotal_amount').val('');
        $('#total_amount').val('');
        var qty_product = $('#qty_product_value').val();
        var discount_on_product = $('#discount_on_product').val();
        var pre_amount = $('#pre_amount').val();
        var per_product_discount = +(discount_on_product || 0) / +(qty_product || 0); // could be NaN
        $('#price_as_product').val(+price_as_product - +(per_product_discount||0));
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
        var price_as_product = $('#price_as_product').val();
        var discount_amount = $('#discount_calculate').val();
        var discount_on_product = $('#discount_on_product').val()||0;
        var discount_type = $('#discount_type').val();
        var main_price = $('#product_main_price').val();
        var subtotal_on_product = (main_price * qty_product_value) - discount_on_product;
        
        if(discount_on_product <= 0) {
            discount_type = null;
        }

        htmlData += "<tr class='item p-0'>"
        htmlData += "<td class='p-1'><input type='hidden' name='new_item_id[]' value='"+item_id+"'/>" + item_name + "</td>"
        htmlData += "<td class='p-1'><input class='item-qty form-control'  type ='number' step='any' min='0' name='new_qty[]' value='"+qty_product_value+"' /></td>"
        htmlData += "<td class='p-1'><input readonly  type ='number' class='form-control' name='new_main_price[]' value='"+main_price+"' /> </td>"
        htmlData += "<td class='p-1'><input readonly  type ='number' class='form-control' name='new_price[]' value='"+price_as_product+"' /> </td>"
        htmlData += `<td class='p-1'><select class='form-control discount-type' name='discount_type[]'>`;
        htmlData += `<option value='' hidden>Type</option>`;
        htmlData += `<option ${discount_type === 'bdt' ? 'selected' : null} value='bdt'>BDT</option>`;
        htmlData += `<option ${discount_type === 'percent' ? 'selected' : null} value='percent'>%</option>`;
        htmlData += `</select></td>`;
        htmlData += `<td class='p-1'>`;
        htmlData += "<input type ='number' class='form-control discount-amount' name='discount_amount[]' value='"+discount_amount+"' min='0' />";
        htmlData += "<input type ='hidden' name='discount[]' value='"+discount_on_product+"' min='0' />";
        htmlData += `</td>`;
        htmlData += "<td class='p-1'><input class='item-charge form-control' readonly  type ='number' name='new_subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
        htmlData += "<td class='p-1'><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
        htmlData +="</tr>";
        $('#product_list tbody').append(htmlData)
        currentData();
        clearOldData();
    }

    newProduct()
//----------------------------end store addondemoproduct----------------------------------------
function product_re_edit(){
    var qty = $(this).closest('tr').find('.item-qty').val();
    var main_price = $(this).closest('tr').find('input[name="new_main_price[]"]').val();
    var discount_type = $(this).closest('tr').find('select[name="discount_type[]"]').val();
    var discount_amount = $(this).closest('tr').find('input[name="discount_amount[]"]').val();
    var qty_price = Number(qty) * Number(main_price);
    var total_discount = 0;
    var per_product_discount = 0;
    console.log(discount_amount);
    if(discount_type === 'percent') {
        total_discount = (qty_price * Number(discount_amount) ) / 100
    }else if(discount_type === 'bdt') {
        total_discount = Number(discount_amount);
    }
    per_product_discount = +total_discount / +qty;
    
    $(this).closest('tr').find('input[name="discount[]"]').val(Number(+total_discount).toFixed(2));
    $(this).closest('tr').find('input[name="new_subtotal[]"]').val(Number(+qty_price - +total_discount).toFixed(2));
    $(this).closest('tr').find('input[name="new_price[]"]').val(Number(+main_price - +per_product_discount).toFixed(2));
    currentData();
}

$(document).on('input', '.item-qty', product_re_edit);
$(document).on('input', '.discount-amount', product_re_edit);
$(document).on('input', '.new_main_price', product_re_edit);
$(document).on('change', '.discount-type', product_re_edit);

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
                   
                    let discount_value = value.discount_amount;
                    data += "<tr class='item p-0'>";
                    data += "<td class='p-1'><input type='hidden' name='new_item_id[]' value='"+value.item.id+"'/>" + value.item.name+ "</td>";
                    data += "<td class='p-1'><input class='item-qty form-control' type='number' name='new_qty[]' value='"+value.qty+"' /></td>";
                    data += "<td class='p-1'><input class='form-control new_main_price'  type='number' step='any' name='new_main_price[]' value='"+value.main_price+"' /> </td>";
                    data += "<td class='p-1'><input class='form-control' readonly  type='number' step='any' name='new_price[]' value='"+value.price+"' /> </td>";
                    data += `<td class='p-1'><select class='form-control discount-type' name='discount_type[]'>`;
                    data += `<option value="" hidden>Type</option>`;
                    data += `<option value='bdt' ${value.discount_type == 'bdt' ? 'selected' : null}>BDT</option><option ${value.discount_type == 'percent' ? 'selected' : null} value='percent'>%</option>`;
                    data += `</select></td>`;
                    data += "<td class='p-1'>";
                    data += "<input type='number' name='discount_amount[]' class='form-control discount-amount' value='"+discount_value+"' min='0' step='0' placeholder='Discount'/>";
                    data += "<input type ='hidden' name='discount[]' value='"+value.discount+"' />";
                    data += "</td>";
                    data += "<td class='p-1'><input  class='item-charge form-control' readonly  type ='number' step='any' name='new_subtotal[]' value='"+value.subtotal_on_product+"' /> </td>";
                    data += "<td class='p-1'><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
                    data +="</tr>";
                    Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product);
                    Total_item = Number(Total_item)+ Number(value.qty);
                });

                $('#total_item').html(Total_item);
                $('#total_sales_price').html(Total_cost);
                $('#all_subtotal_amount').html(Total_cost);
                $('#all_subtotal_amount').html(Total_cost);
                $('#product_list tbody').append(data);
                currentData();
            }
        });
        
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
