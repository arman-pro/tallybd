@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Sale')

@push('css')
<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush

@section('admin_content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <form action="{{ url('/SaveAllData_sales/store/') }}" method="post">
                @csrf
            <div class="card fw-bold">
                <div class="card-header bg-primary text-center text-light">
                    <h4 class="card-title">Add Sale</h4>
                </div>
                <div class="card-body">
                    {{-- hidden field --}}
                    <input type="hidden" name="page_name" value="sales_addlist" id="page_name" />
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
                        <div class="col-md-3 col-sm-12">
                            <label>Date*</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required/>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Vch. No*</label>
                            <?php
                                use App\SalesAddList;
                                $product_id_list = App\Helpers\Helper::IDGenerator(new SalesAddList,
                                'product_id_list', 4, 'Sl');
                            ?>
                            <input 
                                type="text" class="form-control" name="product_id_list"
                                id="product_id_list" value="{{ $product_id_list }}" readonly required
                            />
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Godown</label>
                            <select class="form-control" required name="godown_id" id="godown_id">
                                <option value="" hidden>Select Godown</option>
                                @foreach ($Godwn as $key => $godown_row)
                                    <option @if($key == 0) selected @endif value="{{ $godown_row->id }}">{{ $godown_row->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Sale Man*</label>
                            <select class="form-control" required name="SaleMen_name" id="SaleMan_name" >
                                <option value="" hidden>Select Sale Man</option>
                                @foreach ($SaleMen as $key => $saleMan_row)
                                    <option @if($key == 0) selected @endif value="{{ $saleMan_row->id }}">
                                        {{ $saleMan_row->salesman_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                        
                        <div class="col-md-6 col-sm-12" style="font-size:20px;font-weight:bold;">
                            <label>Party Ledger*</label>
                            <select 
                                onchange="account_details()" 
                                name="account_ledger_id" id="account_ledger_id" 
                                class="select2 form-control"
                                required data-placeholder="Select Ledger"
                            />
                            </select>
                           <!--{{-- <p class="p-0 m-0 text-danger"><small id="party_ledger"></small></p>--}}-->
                              <span id="party_ledger" style="color: green;font-size:15px;"></span>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" />
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Address</label>
                            <input type="text" id="address" name="address" placeholder="Address" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 col-sm-12">
                            <table class="table table-bordered" id="product_table">
                                <thead class="heighlightText" style="background-color: #D6DBDF;">
                                    <th class="fw-bold p-2">Product</th>
                                    <th style="width:100px;" class="fw-bold p-2">Quantity</th>
                                    <th style="width:120px;" class="fw-bold p-2">Main Price</th>
                                    <th style="width:100px;" class="fw-bold p-2">Dis: Price</th>
                                    <th style="width:50px;" class="fw-bold p-2">Type</th>
                                    <th style="width:100px;" class="fw-bold p-2">Discount</th>
                                    <th style="width:200px;" class="fw-bold p-2">Subtotal</th>
                                    <th class="p-2">#</th>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="p-2">                                            
                                            <select 
                                                onchange="Product()" id="item_name" style="width:100%;"
                                                name="item_name" class="select2item form-control fw-bold" 
                                                data-placeholder="Select a Product"
                                            >
                                            </select>
                                            <p class="m-0 p-0 text-primary"><small id="product_current_stock"></small></p>
                                        </td>
                                        <td class="p-2" style="width:100px;">
                                            <input 
                                                type="number" name="qty_product_value" id="qty_product_value"
                                                class="form-control fw-bold" min="0" placeholder="Quantity" step="any"
                                                oninput="qty_product()" autocomplete="off" />
                                        </td>
                                        <td class="p-2" id="main_price"></td>
                                        <td class="p-2" id="sales_price"></td>
                                        <td class="p-2">
                                            <select class="form-control" id="discount_type">
                                                <option value="bdt" selected>BDT</option>
                                                <option value="percent">%</option>
                                            </select>
                                        </td>
                                        <td class="p-2">
                                            <input 
                                                type="number" class="form-control" id="discount_calculate" value="0" min="0" step="0" 
                                                placeholder="Discount"
                                            />
                                            <input 
                                                type="hidden" name="discount_on_product" id="discount_on_product"
                                                oninput="qty_product()" value="0" />
                                        </td>
                                        <td class="p-2" id="hi">
                                            <input type="number" class="form-control fw-bold" min="0" step="any" placeholder="Sub Total" id="subtotal_on_qty" />
                                            {{-- <span id="subtotal_on_discount"></span>.00 --}}
                                        </td>
                                        <td class="p-2">
                                            <button type="button" class="btn btn-sm btn-info" onclick="addondemoproduct()">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-responsive">
                                <tbody>
                                    <tr>
                                        <td colspan="3" style="text-align: right;" class="p-2"><b>Total Qty.</b> <span id="total_item">0</span></td>
                                        <td class="p-2">
                                            <b>Total Discount:</b> <span style="float:right;" id="total_amount_discount">0</span>
                                            <input type="hidden" name="total_amount_discount" value="0" />
                                        </td>
                                        <td class="text-end p-2"><b>Total</b></td>
                                        <td colspan="2" class="p-2">
                                            <span id="total_sales_price"></span>
                                        </td>
                                    </tr>
                                
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2" class="text-end p-2 fw-bold">
                                            <select  
                                                onchange="account_details()"
                                                name="expense_ledger_id" id="expense_ledger_id"
                                                class="form-control" data-placeholder="Others Expense Ledger"
                                                data-width="100%" data-allow-clear="true"
                                            >
                                            </select>
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <input type="text" name="other_expense" id="other_bill" class="form-control fw-bold" placeholder="Other Amount" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end p-2">
                                            <b>Grand Total</b>
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <input type="number" step="any" name="totalAmount" id="totalAmount" class="form-control" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2" class="text-end p-2">
                                            <select  
                                                onchange="account_details()"
                                                name="cash_payment_ledger_id" id="received_ledger_id"
                                                class="select2Payment fw-bold" data-placeholder="Receive Mode"
                                                data-allow-clear="true" data-width="100%"
                                            >
                                            </select>
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <input type="number" min='0' step="any" name="cash_payment" id="cash_payment" class="form-control fw-bold" placeholder="Receive Amount" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end p-2">
                                            <b>Total Due</b>
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <input readonly type="number" placeholder="Total Due" min="0" step="any" name="totalDue" id="totalDue" class="form-control" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label>Shipping Details</label>
                            <textarea class="form-control" id="shipping_details" name="shipping_details" placeholder="Shipping Details"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label>Delivered To</label>
                            <textarea class="form-control" id="delivered_to_details" placeholder="Delivered To" name="delivered_to_details"></textarea>
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
                    <button type="submit" class="btn btn-primary"><b>Save</b></button>
                    <button type="submit" class="btn btn-outline-info" name="print" value="1" ><b>Save & Print</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger">Cancel</a>
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
    
    $('#account_ledger_id').change(function(){
        var ledger_id = $(this).val();
        $.get("{{url('ledgerValue')}}"+'/'+ledger_id, function(data, status){
             $('#party_ledger').html(data);
        });
    });
    
    function discount() {
        let discount = $('#discount_calculate').val();
        let discount_type = $("#discount_type").val();
        let price = $('#price_as_product').val();
        let qty = $('#qty_product_value').val()||1;
        let main_price = $('#product_main_price').val();
        var per_product_discount = 0;
        if(discount_type === 'bdt') {
           $('#discount_on_product').val(Number(discount)).trigger('input');
        }else if(discount_type == 'percent') {
           discount = (Number(main_price) * Number(qty) * Number(discount)) / 100;
           $('#discount_on_product').val(discount).trigger('input');
        }
        per_product_discount = Number(discount) / Number(qty);
        $('#price_as_product').val(Number(+main_price - per_product_discount).toFixed(2));
    }
    
    $(document).on('input', '#discount_calculate', discount);
    $(document).on('change', '#discount_type', discount);
    
    
    $(".select2").select2({
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
    
    $(document).on("change", "#received_ledger_id", function(){
       var cash_payment = $('#cash_payment').val();
       var cash_payment_id = $("#received_ledger_id").val();
       if(cash_payment_id != '') {
           $('#cash_payment').prop('required', true);
       }else {
           $('#cash_payment').prop('required', false);
       }
    });

    function currentData(){
        var subTotal = 0;
        var subQty = 0;
        var totalBill = 0;
        var total_amount_discount = 0;
        $('.item').each(function() {
            var $this = $(this),
                sum = Number($this.find('.item-charge').val());
                subQty += Number($(this).find('.item-qty').val());
                subTotal += sum;
                total_amount_discount += Number($(this).find("input[name='discount[]']").val());
        });
        $('#all_subtotal_amount').html(subTotal.toFixed(2));
        var other_bill =Number($('#other_bill').val());
        totalBill =(subTotal+other_bill); 
        $('#totalAmount').val(totalBill.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));
        $('#total_item').html(subQty.toFixed(2));
        total_amount_discount = +Number(total_amount_discount).toFixed(2);
        $('#total_amount_discount').html(total_amount_discount);
        $('input[name="total_amount_discount"]').val(total_amount_discount);
        var cash_payment = $('#cash_payment').val() || 0;
        $('#totalDue').val(+totalBill - +cash_payment);
    }
    
    $(document).on('input', '#cash_payment', currentData);
    
    $('#other_bill').keyup(function(){
        var other_bill =Number($(this).val());
        var subTotal =Number($('#total_sales_price').html());
        var totalBill =(subTotal+other_bill); 
         $('#totalAmount').val(totalBill.toFixed(2));
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
        $('#discount_calculate').val('');
        $('#product_main_price').val('');
    }


    function Product() {
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
                    item = '<input type="show" readonly name="price_as_product" id="price_as_product" class="form-control fw-bold" value="'+item_price+'">'
                    main_price_item = '<input type="number" class="form-control fw-bold" id="product_main_price" value="'+item_price+'" oninput="qty_product()" />';
                })

                $('#main_price').html(main_price_item);
                $('#sales_price').html(item);
                $('#subtotal').html(item_price);
                $('#product_current_stock').html(response[0].count.grand_total + " " + response[0].unit.name);

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
        var price_as_product = $('#product_main_price').val();
        $('#subtotal_on_discount').hide();
        $('#subtotal_on_qty').show();
        $('#total_sales_price').val('');
        $('#all_subtotal_amount').val('');
        $('#total_amount').val('');
        var qty_product = $('#qty_product_value').val();
        var discount_on_product = $('#discount_on_product').val();
        var per_product_discount = +(discount_on_product || 0) / +(qty_product || 0); // could be NaN
        $('#price_as_product').val(+price_as_product - +(per_product_discount||0));
        var Subtotal = (price_as_product * qty_product) - discount_on_product
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
    var discount_on_product = $('#discount_on_product').val();
    var price_as_product = $('#price_as_product').val();
    var discount_type = $('#discount_type').val();
    var discount_value = $('#discount_calculate').val() || 0;
    var main_price = $('#product_main_price').val();
    var subtotal_on_product = (main_price * qty_product_value) - discount_on_product;
    if(discount_value<=0) {
        discount_type = null;
    }

    htmlData += "<tr class='item'>";
    htmlData += "<td class='p-2'><input type='hidden' name='item_id[]' value='"+item_id+"'/>" + item_name + "</td>";
    htmlData += "<td class='p-2'><input class='item-qty form-control'  type ='number' step='any' name='qty[]' value='"+qty_product_value+"' /></td>";
    htmlData += "<td class='p-2'><input readonly type ='number' class='form-control' name='main_price[]' value='"+main_price+"' min='0' /> </td>";
    htmlData += "<td class='p-2'><input readonly type ='number' class='form-control' name='price[]' value='"+price_as_product+"' /> </td>";
    htmlData += `<td class='p-2'><select class='form-control discount-type' name='discount_type[]'>`;
    htmlData += '<option value="" hidden>Type</option>';
    htmlData += `<option value='bdt' ${discount_type == 'bdt' ? 'selected' : null}>BDT</option><option ${discount_type == 'percent' ? 'selected' : null} value='percent'>%</option>`;
    htmlData += `</select></td>`;
    htmlData += "<td class='p-2'>";
    htmlData += "<input type='number' name='discount_amount[]' class='form-control discount-amount' value='"+discount_value+"' min='0' step='0' placeholder='Discount'/>";
    htmlData += "<input type ='hidden' name='discount[]' value='"+discount_on_product+"' />";
    htmlData += "</td>";
    htmlData += "<td class='p-2'><input class='item-charge form-control' readonly type ='number' step='any' name='subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>";
    htmlData += "<td class='p-2'><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
    htmlData +="</tr>";
    $('#product_table tbody').append(htmlData)
    currentData();
    clearOldData();
}

function product_re_edit(){
    var qty = $(this).closest('tr').find('input[name="qty[]"]').val();
    var main_price = $(this).closest('tr').find('input[name="main_price[]"]').val();
    var discount_type = $(this).closest('tr').find('select[name="discount_type[]"]').val();
    var discount_amount = $(this).closest('tr').find('input[name="discount_amount[]"]').val();
    var qty_price = Number(qty) * Number(main_price);
    var total_discount = 0;
    var per_product_discount = 0;
    if(discount_type === 'percent') {
        total_discount = (qty_price * Number(discount_amount) ) / 100
    }else if(discount_type === 'bdt') {
        total_discount = Number(discount_amount);
    }
    per_product_discount = +total_discount / +qty;
    
    $(this).closest('tr').find('input[name="discount[]"]').val(total_discount);
    $(this).closest('tr').find('input[name="subtotal[]"]').val(qty_price - total_discount);
    $(this).closest('tr').find('input[name="price[]"]').val(Number(main_price-per_product_discount).toFixed(2));
    currentData();
}

$(document).on('input', '.item-qty', product_re_edit);
$(document).on('input', '.discount-amount', product_re_edit);
$(document).on('change', '.discount-type', product_re_edit);

//----------------------------end store addondemoproduct----------------------------------------

//----------------------------start Remove addondemoproduct----------------------------------------
function delete_data(delelet) {
    (delelet).closest('tr').remove();
    currentData();
}
//----------------------------end Remove addondemoproduct----------------------------------------


$("#item_name").select2({
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
