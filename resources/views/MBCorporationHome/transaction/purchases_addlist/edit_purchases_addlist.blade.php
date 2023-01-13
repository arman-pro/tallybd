@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Update Purchase')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush

@section('admin_content')
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12 col-sm-12">  
                <form action="{{ URL::to('/Update/PurchasesAddList/' . $purchasesAddList->id) }}" method="POST">
                    @csrf              
                <div class="card fw-bold">
                    <div class="card-header bg-success">
                        <h4 class="card-title text-light">Update Purchases</h4>
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
                        <div class="form-group row">
                            <input type="hidden" name="page_name" value="purchases_addlist" id="page_name" />
                            <div class="col-md-3 col-sm-12">
                                <label>Date*</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ $purchasesAddList->date }}" required />
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label>Vo. No*</label>
                                <input 
                                    type="text" class="form-control" 
                                    name="product_id_list" id="product_id_list"
                                    value="{{ $purchasesAddList->product_id_list }}" readonly 
                                />
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label>Godown *</label>
                                <select class="form-control" id="godown_id" name="godown_id" readonly>
                                    <option value="" hidden>Select Godown</option>
                                    @foreach ($godown as $godwn_row)
                                    <option value="{{ $godwn_row->id }}" {{ $purchasesAddList->
                                        godown_id == $godwn_row->id ? 'Selected' : ' ' }}>
                                        {{ $godwn_row->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label>Sale Man*</label>
                                <select class="form-control" id="SaleMan_name" name="SaleMan_name" aria-readonly="true" required>
                                    <option value="" hidden>Select Sale Man</option>
                                    @foreach ($saleMan as $SaleMan_row)
                                    <option value="{{ $SaleMan_row->id }}" {{ $SaleMan_row->id ==
                                        $purchasesAddList->sale_name_id ? 'Selected' : ' ' }}>
                                        {{ $SaleMan_row->salesman_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">                            
                            <div class="col-md-6 col-sm-12">
                                <label>Ledger</label>
                                <select class="form-control" name="account_ledger_id" id="account_ledger_id" onclick="account_details()" required>
                                    <option value="{{$purchasesAddList->account_ledger_id}}" selected>
                                        {{ $purchasesAddList->ledger->account_name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label>Phone</label>
                                <div id="phone" class="form-control"></div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <label>Address</label>
                                <div id="address" class="form-control"></div>                                
                            </div>
                        </div>

                        <div class="form-group row overflow-auto">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="myTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="fw-bold p-1">Product</th>
                                            <th style="width:130px" class="fw-bold p-1">Quantity</th>
                                            <th style="width:150px" class="fw-bold p-1">Main Price</th>
                                            <th style="width:150px" class="fw-bold p-1">D. Price</th>
                                            <th style="width:120px" class="fw-bold p-1">Type</th>
                                            <th style="width:130px" class="fw-bold p-1">Discount</th>
                                            <th style="width:150px" class="fw-bold p-1">Subtotal</th>
                                            <th class="p-1">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="p-1">
                                                <select  
                                                    onchange="Product()" 
                                                    id="item_name" name="item_name" style="width:100%;"
                                                    class="select2item form-control fw-bold" data-placeholder="Select a Product">
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input 
                                                    type="number" step="any" 
                                                    name="qty_product_value" id="qty_product_value"
                                                    class="form-control fw-bold" min="0"
                                                    oninput="qty_product()" placeholder="Qty."
                                                >
                                            </td>
                                            <td id="main_price_holder" class="p-1 fw-bold"></td>
                                            <td class="p-1 fw-bold" id="sales_price"></td>
                                            <td class="p-1">
                                                <select class="form-control" id="discount_type">
                                                    <option hidden value="">Type</option>
                                                    <option value="bdt" selected>BDT</option>
                                                    <option value="percent">Percent</option>
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
                                                    type="hidden" name="discount_on_product" 
                                                    id="discount_on_product"
                                                    oninput="qty_product()"
                                                />
                                            </td>
                                            <td id="hi" class="p-1">
                                                <input type="number" id="subtotal_on_qty" readonly step="any" class="form-control fw-bold" />
                                                {{-- <span id="subtotal_on_qty"></span> --}}
                                                <span id="subtotal_on_discount"></span>
                                            </td>
                                            <td class="p-1">
                                                <a class="btn btn-sm btn-info" onclick="addondemoproduct()">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>                                    
                            
                                <table class="table tale-bordered">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"class="p-1 text-end"><b>Item</b></td>
                                            <td style="width: 150px;" id="total_item" class="p-1">0</td>
                                            <td style="width: 150px;" class="text-end p-1"><b>Total</b></td>
                                            <td style="width: 300px;" class="p-1">
                                                <b><span id="total_sales_price"></span></b>
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
                                        <tr>
                                            <td colspan="2" class="p-1">&nbsp;</td>
                                            <td colspan="2" class="text-end fw-bold p-1">
                                                All Total Amount
                                            </td>
                                            <td style="width: 300px;" class="p-1 fw-bold" id="all_subtotal_amount">
                                              
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="p-1">&nbsp;</td>
                                            <td colspan="2" class="text-end p-1">
                                                <select 
                                                    onchange="account_details()" 
                                                    name="expense_ledger_id" id="expense_ledger_id" 
                                                    data-placeholder="Select Expense Ledger"
                                                    class="form-control fw-bold" data-allow-clear="true"
                                                >
                                                        <option value="0">None</option>
                                                        @if($purchasesAddList->ledgerexpanse)
                                                        <option value="{{$purchasesAddList->expense_ledger_id}}" selected>
                                                            {{optional($purchasesAddList->ledgerexpanse)->account_name??" "}}
                                                        </option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input 
                                                    type="number"
                                                    name="other_expense" id="other_bill" 
                                                    class="form-control fw-bold" value="{{$purchasesAddList->other_bill}}" 
                                                    placeholder="Other Expense Amount" 
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end p-1">
                                                <b>Grand Total</b>
                                            </td>
                                            <td class="p-1">
                                                <b><span id="totalAmount">{{$purchasesAddList->grand_total }}</span></b>
                                            </td>
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
                                                    <option value="" hidden>Select Payment Mode</option>
                                                    @if($payment_ledgers->isNotEmpty()) 
                                                        @foreach($payment_ledgers as $payment_ledger)
                                                            <option
                                                                value="{{$payment_ledger->id}}"
                                                                @if($purchasesAddList->payment_voucher && $purchasesAddList->payment_voucher->payment_mode_ledger_id == $payment_ledger->id 
                                                                ) {{"selected"}} @endif
                                                            >{{$payment_ledger->account_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>   
                                            </td>
                                            <td class=" p-1">
                                                <input 
                                                    type="number" min='0' step="any"
                                                    name="cash_payment" id="cash_payment" 
                                                    value="{{$purchasesAddList->cash_payment ?? 0}}"
                                                    class="form-control fw-bold" placeholder="Payment Amount" 
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end p-1">
                                                <b>Total Due</b>
                                            </td>
                                            <td class="p-1">
                                                <b><span id="totalDueAmount">{{$purchasesAddList->grand_total - ($purchasesAddList->cash_payment ?? 0) }}</span></b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="from-group row">
                            <div class="col-md-6 col-sm-12">
                                <label>Shipping Details</label>
                                <textarea class="form-control" id="shipping_details" name="shipping_details">{!! $purchasesAddList->shipping_details !!}</textarea>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label>Delivered To</label>
                                <textarea 
                                    class="form-control" id="delivered_to_details" 
                                    name="delivered_to_details">{!! $purchasesAddList->delivered_to_details !!}</textarea>
                            </div>
                        </div>                           
                        
                    </div>
                    <div class="card-footer fw-bold text-center mt-3">
                        <button type="submit" class="btn btn-primary" ><b>Update</b></button>
                        <button type="submit" name="print" value="1" class="btn btn-outline-info"><b>Update & Print</b></button>
                        <a href="{{ route('mb_cor_index') }}" class="btn btn-outline-danger"><b>Cancel</b></a>
                    </div>
                    </form>
                </div>
            </div>
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
    
    // other expesnse ledger
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
                var data_ = [];
                data_.push(data.items);
                data.items = [...data.items, {id: 0, account_name: "None"}];
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
    
    $(".select2Payment").select2({
        
        theme: "bootstrap-5",
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
        $('#price_as_product').val(Number(+main_price - +per_product_discount).toFixed(2));
    }
    
    $(document).on("input", "#discount_amount", discount);
    $(document).on("change", "#discount_type", discount);



    function Product() {

        var item_name = $('#item_name').val();

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('/product_as_price/-') }}" + item_name,

            success: function(response) {
                var item
                var item_price

                $.each(response, function(key, value) {
                    item_price = value.purchases_price;
                    main_price = '<input type="number" min="0" id="main_price" oninput="qty_product()" class="form-control" value="' + item_price + '">';
                    item = '<input type="number" readonly name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" value="' + item_price + '">';
                })

                $('#main_price_holder').html(main_price);
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
                    phone = value.account_ledger_phone ?? "N/A";
                    address = value.account_ledger_address ?? "N/A";
                    account_id_for_preamound = value.account_ledger_id
                    $('#phone').html(phone);
                    $('#address').html(address);
                })
            }
        });
    }

    function qty_product() {
        var main_price = $('#main_price').val();
        var price_as_product = $('#price_as_product').val();

        $('#subtotal_on_discount').hide();
        // $('#subtotal_on_qty').show();
        $('#total_sales_price').val('');
        $('#all_subtotal_amount').val('');
        $('#total_amount').val('');
        
        var qty_product = $('#qty_product_value').val();
        var discount_on_product = $('#discount_on_product').val();
        var pre_amount = $('#pre_amount').val();
        var per_product_discount = +(discount_on_product || 0) / +(qty_product || 1); // could be NaN
        $('#price_as_product').val(Number(+main_price - +per_product_discount).toFixed(2));
        var Subtotal = Number((main_price * qty_product) - discount_on_product).toFixed(2);

        $('#subtotal_on_qty').val(Subtotal);
    }
    
    $(document).on('input', '#main_price', qty_product);

    var old_item_id= [];

    //----------------------------start store addondemoproduct----------------------------------------

    function addondemoproduct() {
        var item_id = $('#item_name').val();
        $("#myTable #old_item_id").each(function(index) {
            old_item_id.push($(this).val());
        });

        old_item_id.push(item_id);
        let htmlData = '';
        var product_id_list = $('#product_id_list').text();
        var page_name = $('#page_name').val();
        var date = $('#date').val();

        var item_name = $('#item_name option:selected').text();
        var qty_product_value = $('#qty_product_value').val()||1;
        var discount_on_product = $('#discount_on_product').val()||0;
        var price_as_product = $('#price_as_product').val();
        var main_price = $('#main_price').val();
        var discount_amount = $("#discount_amount").val();
        var subtotal_on_product = Number(price_as_product * qty_product_value).toFixed(2);
        var discount_type = $("#discount_type").val();
        if(discount_type <= 0) {
            discount_type = null;
        }

        htmlData += "<tr class='item'>"
        htmlData += "<td class='p-1'><input type='hidden' id='#new_item_id' name='new_item_id[]' value='"+item_id+"'/>" + item_name + "</td>"
        htmlData += "<td class='p-1'><input class='item-qty form-control fw-bold' min='0' type='number' name='new_qty[]' value='"+qty_product_value+"' /></td>"
        htmlData += "<td class='p-1'><input class='form-control fw-bold main_price' placeholder='Main Price'  type ='number' step='any' name='main_price[]' value='"+main_price+"' /></td>"
        htmlData += "<td class='p-1'><input readonly class='form-control fw-bold' type ='number' name='new_price[]' step='any' value='"+price_as_product+"' /></td>"
        htmlData += "<td class='p-1'>";
        htmlData += `<select class="form-control discount_type" name="discount_type[]">`;
        htmlData += `<option hidden value="">Type</option>`;
        htmlData += `<option ${discount_type === 'bdt' ? 'selected' : null} value="bdt">BDT</option>`;
        htmlData += `<option ${discount_type === 'percent' ? 'selected' : null} value="percent">Percent</option>`;
        htmlData += `</select>`;
        htmlData += "</td>";
        htmlData += "<td class='p-1'>";
        htmlData += `<input type="number" step="any" class="form-control discount_amount" name="discount_amount" value="${discount_amount}" min="0" placeholder="Discount" />`;
        htmlData += "<input type ='hidden' name='new_discount[]' step='any' value='"+discount_on_product+"' />";
        htmlData += "</td>";
        htmlData += "<td class='p-1'><input  class='item-charge form-control fw-bold' readonly  type ='number' step='any' name='new_subtotal[]' value='"+Number(subtotal_on_product).toFixed(2)+"' /> </td>"
        htmlData += "<td class='p-1'><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
        htmlData +="</tr>";
        $('#myTable tbody').append(htmlData)
        currentData();
        clearOldData();
    }

    function clearOldData(){
        // $('#item_name').val('').;
        $('#item_name').val(null).trigger('change');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').val('');
        $('#main_price').val('');
        $('#discount_amount').val('');
    }
    newProduct()
    
    
    function re_edit(){
        var main_price = $(this).closest('tr').find('input[name="main_price[]"]').val();
        var price = $(this).closest('tr').find('input[name="new_price[]"]').val();
        var discount_type = $(this).closest('tr').find('select[name="discount_type[]"]').val();
        var discount_amount = $(this).closest('tr').find('input[name="discount_amount"]').val() || 0;
        var discount = $(this).closest('tr').find('input[name="new_discount[]"]').val() || 0;
        var qty = $(this).closest('tr').find('input[name="new_qty[]"]').val() || 0;

        if(discount_type == 'bdt') {
            discount = discount_amount;
        }else if(discount_type == 'percent') {
            discount = (+main_price * +qty * +discount_amount) / 100;
        }
        
        if(discount > 0) {
            price = Number(+main_price - (+discount / +qty)).toFixed(2);
        }else {
            price = main_price;
        }
        
        $(this).closest('tr').find('input[name="new_discount[]"]').val(discount);
        $(this).closest('tr').find('input[name="new_price[]"]').val(price);
        $(this).closest('tr').find('input[name="new_subtotal[]"]').val(Number(+price * +qty).toFixed(2));
        currentData();
    }

    $(document).on('input', '.item-qty', re_edit);
    $(document).on('input', '.main_price', re_edit);
    $(document).on('change', '.discount_type', re_edit);
    $(document).on('input', '.discount_amount', re_edit);
    $(document).on('input', '#other_bill', currentData);
    $(document).on('input', '#cash_payment', currentData);
    
    
    //----------------------------end store addondemoproduct----------------------------------------

    //----------------------------Start newProduct----------------------------------------
    function newProduct() {
        var product_id_list = $('#product_id_list').val();
        var data_add_for_list = $('#data_add_for_list').val();

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('/product_new_fild/-') }}" + product_id_list,
            
            success: function(response) {
                var data = ""
                var Total_cost = ""
                var Total_item = ""
                $.each(response, function(key, value){
                    let type = value.discount_type;
                    let discount_amount = value.discount_amount;
                    let main_price = value.main_price_get;
                    data += "<tr class='item'>"
                    data += "<td class='p-1'><input type='hidden' id='new_item_id' name='new_item_id[]' value='"+value.item.id+"'/>" + value.item.name+ "</td>"
                    data += "<td class='p-1'><input class='item-qty form-control fw-bold'  type ='number' name='new_qty[]' value='"+value.qty+"' /></td>"
                    data += "<td class='p-1'><input class='form-control fw-bold main_price'  type ='number' name='main_price[]' step='any' value='"+main_price+"' /> </td>"
                    data += "<td class='p-1'><input class='form-control fw-bold'  readonly  type ='number' name='new_price[]' step='any' value='"+value.price+"' /> </td>"
                    data += `<td class="p-1">`;
                    data += `<select name="discount_type[]" class="form-control">`;
                    data += `<option value="" hidden>Type</option>`;
                    data += `<option ${type==='bdt' ? 'selected' : null} value="bdt">BDT</option>`;
                    data += `<option ${type==='percent' ? 'selected' : null} value="percent">Percent</option>`;
                    data += `</select>`;
                    data += `</td>`;
                    data += "<td class='p-1'>";
                    data += `<input type="number" step="any" class="form-control discount_amount" name="discount_amount" value="${discount_amount}" />`;
                    data += "<input  type ='hidden' name='new_discount[]' step='any' value='"+value.discount+"' />";
                    data += "</td>";
                    data += "<td class='p-1'><input  class='item-charge form-control fw-bold' readonly  type ='number' step='any' name='new_subtotal[]' value='"+value.subtotal_on_product+"' /> </td>"
                    data += "<td class='p-1'><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
                    data +="</tr>";
                    Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product)
                    Total_item = Number(Total_item)+ Number(value.qty)
                });

                $('#total_item').html(Total_item);
                $('#total_sales_price').html(Total_cost);
                $('#all_subtotal_amount').html(Total_cost);
                $('#myTable tbody').append(data);
                currentData();
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


    function currentData(){
        var subTotal = 0;
        var subQty = 0;
        var total_discount = 0;
        $('.item').each(function() {
            var $this = $(this),
                sum = Number($this.find('.item-charge').val());
                subQty += Number($(this).find('.item-qty').val());
                discount = 0;
                total_discount += Number($(this).find('input[name="new_discount[]"]').val());
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
    
    
    // if other expense is none then other expense will be less from total amount
    $('#expense_ledger_id').on('change', function(){
       if($(this).val() == 0)  {
            var subTotal =Number($('#total_sales_price').html());
            var totalBill =(subTotal); 
            $('#totalAmount').html(totalBill.toFixed(2));
            $('#other_bill').val(0);
       }
    });


    $('#other_bill').keyup(function(){
        var other_bill =Number($(this).val());
        var subTotal =Number($('#total_sales_price').html());
        var totalBill =(subTotal+other_bill); 
        $('#totalAmount').html(totalBill.toFixed(2));
    });   

    function cleardata(){
        $('#qty_product_value').val('0');
        $('#discount_on_product').val('0');
        $('#price_as_product').val('0');
        $('#item_name').val(null).trigger('change');
        $('#subtotal_on_qty').val('');
        $('#subtotal_on_discount').hide();

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
