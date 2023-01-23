@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Add Purchase")

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
                <form action="{{url('/SaveAllData/store/')}}" method="post">
                    @csrf
                <div class="card">
                    <div class="card-header bg-info text-center text-light">
                        <h4 class="card-title">Add Purchases</h4>
                    </div>                    
                    <div class="card-body fw-bold">                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="page_name" value="purchases_addlist" id="page_name">
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-12">
                                <label for="date">Date *</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required/>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="product_id_list">Vch. No</label>
                                <input type="text" class="form-control" name="product_id_list" id="product_id_list" placeholder="Vch. No" />
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="godown_id">Godown *</label>
                                <select class="form-control" required name="godown_id" id="godown_id">
                                    <option value="" hidden>Select Godown</option>
                                    @foreach ($godown as $key => $godown_row)
                                        <option 
                                            @if($key == 0) selected @endif 
                                            value="{{ $godown_row->id }}"
                                        >
                                        {{ $godown_row->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="SaleMan_name">Sale Man*</label>
                                <select class="form-control" required name="SaleMan_name" id="SaleMan_name" >
                                    <option value="" hidden>Select Sale Man</option>
                                    @foreach ($SaleMan as $key => $saleMan_row)
                                        <option @if($key == 0) selected @endif value="{{ $saleMan_row->id }}">
                                            {{ $saleMan_row->salesman_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">                                
                            <div class="col-md-6 col-sm-12" style="font-size:17px;font-weight:bold;">
                                <label for="account_ledger_id">Party Ledger*</label>
                                <select  
                                    onchange="account_details()" 
                                    name="account_ledger_id" 
                                    id="account_ledger_id" class="select2 form-control"
                                    data-placeholder="Select Ledger"
                                    required>
                                </select>
                                <span id="party_ledger" style="color: green;font-size:15px;"></span>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="phone">Phone</label>
                                <div id="phone" class="form-control"></div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label for="address">Address</label>
                                <div id="address" class="form-control"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 col-sm-12">
                                <table class="table table-sm table-bordered" id="myTable">
                                    <thead class="heighlightText" style="background-color: #D6DBDF;">
                                        <tr>
                                            <th class="fw-bolder p-1">Product</th>
                                            <th style="width:130px" class="fw-bolder p-1">Quantity</th>
                                            <th style="width:120px" class="fw-bolder p-1">Main Price</th>
                                            <th style="width:120px" class="fw-bolder p-1">D. Price</th>
                                            <th style="width:70px" class="fw-bolder p-1">Type</th>
                                            <th style="width:100px" class="fw-bolder p-1">Discount</th>
                                            <th style="width:150px" class="fw-bolder p-1">Subtotal</th>
                                            <th class="p-1">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_add_for_list" class="bg-default">
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="p-1">
                                                <select  
                                                    onchange="Product()"
                                                    id="item_name" style="width:100%;"
                                                    name="item_name" class="form-control select2item" 
                                                    placeholder="Select Product"
                                                >
                                                </select>
                                                <p class="m-0 p-0 text-primary"><small id="product_current_stock"></small></p>
                                            </td>
                                            <td class="p-1">
                                                <input 
                                                    type="number" 
                                                    name="qty_product_value" 
                                                    id="qty_product_value"
                                                    class="form-control fw-bold"                                                     
                                                    min="0" placeholder="Qty."                                              
                                                    oninput="qty_product()" 
                                                    autocomplete="off"
                                                />
                                            </td>
                                            <td id="main_price_holder" class="p-1">

                                            </td>
                                            <td id="sales_price" class="p-1">

                                            </td>
                                            <td class="p-1">
                                                <select class="form-control" id="discount_type">
                                                    <option hidden value="">Type</option>
                                                    <option value="bdt" selected>BDT</option>
                                                    <option value="percent">%</option>
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input
                                                    type="number"
                                                    min="0"
                                                    class="form-control"
                                                    name="discount_amount" 
                                                    id="discount_amount"
                                                    placeholder="Discount"
                                                />
                                                <input
                                                    type="hidden"
                                                    value="0"
                                                    name="discount_on_product" 
                                                    id="discount_on_product"
                                                    oninput="qty_product()"
                                                />
                                            </td>
                                            <td id="hi" class="p-1">
                                                <input 
                                                    type="number" 
                                                    id="subtotal_on_qty"
                                                    class="form-control fw-bold"
                                                    placeholder="Sub Total"
                                                />
                                                <span id="subtotal_on_discount"></span>
                                            </td>
                                            <td class="p-1 text-center">
                                                <a
                                                    class="btn btn-sm btn-info" 
                                                    onclick="addondemoproduct()"
                                                >
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>                                   
                            </div>

                            <div class="col-md-12">                           
                                <table class="table table-bodered">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="text-end p-1"><b>Total Qty.</b></td>
                                            <td class="text-end p-1" style="width: 150px;font-weight:800;" id="total_item">0</td>
                                            <td style="width: 150px;" class="text-end p-1"><b>Total</b></td>
                                            <td class="p-1" style="width: 300px;font-weight:800;">
                                                <span id="total_sales_price"></span>
                                            </td>                                                
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end p-1">&nbsp;</td>
                                            <td class="text-end p-1">&nbsp;</td>
                                            <td style="width: 150px;" class="text-end p-1"><b>Total Discount</b></td>
                                            <td style="width: 300px;font-weight:800;" class="p-1">
                                                <span id="total_discount"></span>
                                                <input type="hidden" id="total_discount_input" name="discount_total" value="0" min="0" step="any" />
                                            </td>                                                
                                        </tr>
                                        {{--<tr>
                                            <td colspan="4" class="text-end"><b>All SubTotal Amount</b></td>
                                            <td style="width: 300px;font-weight:800;">
                                                <span id="all_subtotal_amount"></span></span>
                                            </td>
                                        </tr>--}}
                                        <tr>
                                            <td colspan="2" class=" p-1">&nbsp;</td>
                                            <td colspan="2" class="text-end p-1">
                                                <select 
                                                    onchange="account_details()" 
                                                    name="expense_ledger_id" 
                                                    id="expense_ledger_id"
                                                    class="form-control"
                                                    data-placeholder="Select Other Expense"
                                                    data-allow-clear="true" data-width="100%"
                                                >
                                                </select>    
                                            </td>
                                            <td class=" p-1">
                                                 <input type="number" name="other_expense" id="other_bill" placeholder="Other  Amount" class="form-control fw-bold" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end p-1"><b>Grand Total</b></td>
                                            <td style="font-weight: 800;" class=" p-1"><span id="totalAmount"></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class=" p-1">&nbsp;</td>
                                            <td colspan="2" class="text-end p-1">
                                                <select  
                                                    onchange="account_details()"
                                                    name="cash_payment_ledger_id" id="received_ledger_id"
                                                    class="select2Payment fw-bold" data-placeholder="Payment Mode"
                                                    data-allow-clear="true" data-width="100%"
                                                >
                                                </select>   
                                            </td>
                                            <td class=" p-1">
                                                <input 
                                                    type="number" min='0' step="any"
                                                    name="cash_payment" id="cash_payment" 
                                                    class="form-control fw-bold" placeholder="Payment Amount" 
                                                />
                                            </td>
                                        </tr>
                                         <tr>
                                            <td colspan="4" class="text-end p-1"><b>Total Due</b></td>
                                            <td style="font-weight: 800;" class=" p-1"><span id="totalDueAmount"></span></td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 col-sm-12">
                                <label>Shipping Details</label>
                                <textarea class="form-control" id="shipping_details" name="shipping_details"></textarea>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label>Delivered To</label>
                                <textarea class="form-control" id="delivered_to_details" name="delivered_to_details"></textarea>
                            </div>                            
                        </div>
                        
                        <!--send sms-->
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="send_sms" value="yes" class="form-check-input" id="send_sms">
                                <label class="form-check-label fw-bold" for="send_sms">Send SMS</label>
                            </div>
                        </div>                                                
                    </div> 
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary fw-bold">Save</button>
                        <button type="submit" name="print" value="1" class="btn btn-outline-info fw-bold" >Save & Print</button>
                        <a href="{{ route('mb_cor_index') }}" class="btn btn-outline-danger fw-bold">Cancel</a>
                    </div>                   
                </div>
            </form>
            </div>


            <div id="sxan"></div>



@endsection
@push('js')

@if(session()->has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{session('success')}}",
    });
</script>
@endif

    <script>


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
     $('#account_ledger_id').change(function(){
        var ledger_id = $(this).val();
        $.get("{{url('ledgerValue')}}"+'/'+ledger_id, function(data, status){
             $('#party_ledger').html(data);
        });
    });
    
    function discount() {
        var main_price = $('#main_price').val();
        var price = $('#price_as_product').val();
        var discount_amount = $('#discount_amount').val();
        var discount_type = $('#discount_type').val();
        var qty = $('#qty_product_value').val();
        var per_product_discount = 0;
        if(discount_type === 'bdt') {
            $('#discount_on_product').val(+discount_amount).trigger('input');
        }else if(discount_type === 'percent') {
            discount_amount = (+main_price * +qty * +discount_amount) / 100;
            $('#discount_on_product').val(+discount_amount).trigger('input');
        }
        
        per_product_discount = +discount_amount / +qty;
        $('#price_as_product').val(+main_price - +per_product_discount);
    }
    
    $(document).on("input", "#discount_amount", discount);
    $(document).on("change", "#discount_type", discount);

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
            placeholder: "Select a Product",
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
    
    $(".select2Payment").select2({
        ajax: {
            url: '{{ url("paymentLedger") }}',
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
        theme: "bootstrap-5",
    });

    function cleardata(){
        $('#qty_product_value').val('0');
        $('#discount_on_product').val('0');
        $('#price_as_product').val('0');
        $('#item_name').val(null).trigger('change');
        $('#subtotal_on_qty').hide();
        $('#subtotal_on_discount').hide();
        $('#discount_amount').val('');
        $('#main_price').val('');
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
                    main_item = '<input type="number" id="main_price" oninput="qty_product()" class="form-control fw-bold" value="'+item_price+'">';
                    item = '<input readonly type="number" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control fw-bold" value="'+item_price+'">';
                });

                $('#main_price_holder').html(main_item);
                $('#sales_price').html(item);
                $('#subtotal').html(item_price);
                let items = response[0];
                if(items && items.count) {
                    $('#product_current_stock').html(items.count.grand_total + " " + items.unit.name);
                }else {
                    $('#product_current_stock').html(null);
                }
                
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
                    phone = value.account_ledger_phone ?? "N/A";
                    address = value.account_ledger_address ?? "N/A";
                    account_id_for_preamound = value.account_ledger_id
                    $('#phone').html(phone);
                    $('#address').html(address);
                })
            }
        })
    }


    function qty_product(){
        var main_price = $('#main_price').val();
        var price_as_product = $('#price_as_product').val();
     
        $('#subtotal_on_discount').hide();
        $('#subtotal_on_qty').show();
        $('#total_sales_price').val('');
        $('#all_subtotal_amount').val('');
        $('#total_amount').val('');
        
        var qty_product = $('#qty_product_value').val();
        var discount_on_product = $('#discount_on_product').val();
        var pre_amount = $('#pre_amount').val();
        var per_product_discount = +(discount_on_product || 0) / +(qty_product || 0); // could be NaN
        $('#price_as_product').val(+main_price - +per_product_discount);
        var Subtotal = (main_price * qty_product) - discount_on_product
       
        $('#subtotal_on_qty').val(Subtotal);
    }

    var old_item_id= [];
    function addondemoproduct() {

        var item_id = $('#item_name').val();
        if(item_id != null) {
            old_item_id.push(item_id);
            let htmlData = '';
            var product_id_list = $('#product_id_list').text();
            var page_name = $('#page_name').val();
            var date = $('#date').val();

            var item_name = $('#item_name option:selected').text();
            var qty_product_value = $('#qty_product_value').val()||1;
            var discount_on_product = $('#discount_on_product').val()||0;
            var price_as_product = $('#price_as_product').val();
            var discount_amount = $("#discount_amount").val();
            var main_price = $("#main_price").val();
            var subtotal_on_product = (price_as_product * qty_product_value);
            var discount_type = $("#discount_type").val();
            if(discount_on_product <= 0) {
                discount_type = null;
            }
            

            htmlData += "<tr class='item'>";
            htmlData += "<td class='p-1'><input type='hidden' name='item_id[]' value='"+item_id+"'/>" + item_name + "</td>"
            htmlData += "<td class='p-1'><input class='item-qty form-control fw-bold'  type ='number' step='any' name='qty[]' value='"+qty_product_value+"' /></td>"
            htmlData += "<td class='p-1'><input class='form-control fw-bold main_price' placeholder='Main Price'  type ='number' step='any' name='main_price[]' value='"+main_price+"' /></td>"
            htmlData += "<td class='p-1'><input class='form-control fw-bold' readonly  type ='number' step='any' name='price[]' value='"+price_as_product+"' /> </td>";
            htmlData += "<td class='p-1'>";
            htmlData += `<select class="form-control discount_type" name="discount_type[]">`;
            htmlData += `<option hidden value="">Type</option>`;
            htmlData += `<option ${discount_type === 'bdt' ? 'selected' : null} value="bdt">BDT</option>`;
            htmlData += `<option ${discount_type === 'percent' ? 'selected' : null} value="percent">Percent</option>`;
            htmlData += `</select>`;
            htmlData += "</td>";
            htmlData += "<td class='p-1'>";
            htmlData += `<input type="number" step="any" class="form-control discount_amount" placeholder="Discount" name="discount_amount" value="${discount_amount}" min="0" />`;
            htmlData += "<input  type ='hidden' step='any' name='discount[]' value='"+discount_on_product+"' />";
            htmlData += "</td>";
            htmlData += "<td class='p-1'><input  class='form-control item-charge fw-bold' readonly  type ='number' step='any' name='subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
            htmlData += "<td class='p-1 text-center'><button class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></button></td>"
            htmlData += "</tr>";
            $('#myTable tbody').append(htmlData)
            currentData();
            clearOldData();
        }else {
            Swal.fire({
                icon: 'info',
                text: 'Please Select a Product',
            });
        }

    }

    function currentData(){
        var subTotal = 0;
        var subQty = 0;
        var total_discount = 0;
        
        $('.item').each(function() {
            var $this = $(this),
                sum = Number($this.find('.item-charge').val());
            subQty += Number($(this).find('.item-qty').val());
            total_discount = Number($(this).find('input[name="discount[]"]').val());
            subTotal += sum;
        });

        $('#all_subtotal_amount').html(subTotal.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));
        var other_bill =Number($('#other_bill').val());
        
        totalBill =(subTotal+other_bill); 
        $('#totalAmount').html(totalBill.toFixed(2));
        $('#total_item').html(subQty.toFixed(2));
        $('#total_discount').html(total_discount);
        $('#total_discount_input').val(total_discount);
        var total_payable = $('#cash_payment').val() || 0;
        $('#totalDueAmount').html(totalBill - total_payable);
    }
    
    function re_edit(){
        var main_price = $(this).closest('tr').find('input[name="main_price[]"]').val();
        var price = $(this).closest('tr').find('input[name="price[]"]').val();
        var discount_type = $(this).closest('tr').find('select[name="discount_type[]"]').val();
        var discount_amount = $(this).closest('tr').find('input[name="discount_amount"]').val() || 0;
        var discount = $(this).closest('tr').find('input[name="discount[]"]').val() || 0;
        var qty = $(this).closest('tr').find('input[name="qty[]"]').val() || 0;

        if(discount_type == 'bdt') {
            discount = discount_amount;
        }else if(discount_type == 'percent') {
            discount = (+main_price * +qty * +discount_amount) / 100;
        }
        
        if(discount > 0) {
            price = (+main_price - (+discount / +qty));
        }else {
            price = main_price;
        }
        
        $(this).closest('tr').find('input[name="discount[]"]').val(discount);
        $(this).closest('tr').find('input[name="price[]"]').val(price);
        $(this).closest('tr').find('input[name="subtotal[]"]').val(+price * +qty);
        currentData();
    }

    $(document).on('input', '.item-qty', re_edit);
    $(document).on('input', '.main_price', re_edit);
    $(document).on('change', '.discount_type', re_edit);
    $(document).on('input', '.discount_amount', re_edit);
    $(document).on('input', '#cash_payment', currentData);

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
        $('#price_as_product').val(Number(totalBill).toFixed(5));
        $('#main_price').val(Number(totalBill).toFixed(5));
    });   
    
    function clearOldData(){
        $('#item_name').val(null).trigger('change');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').val('');
        $('#discount_amount').val('');
        $('#main_price').val('');
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
            theme: "bootstrap-5",
        });
    

</script>




@endpush
