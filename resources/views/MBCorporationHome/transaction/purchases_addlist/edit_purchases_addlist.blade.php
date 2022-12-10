@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Update Purchase')
@section('admin_content')
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12 col-sm-12">                
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="card-title">Update Purchases</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ URL::to('/Update/PurchasesAddList/' . $purchasesAddList->id) }}" method="POST">
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
                                <input type="hidden" name="page_name" value="purchases_addlist" id="page_name" />
                                <div class="col-md-4 col-sm-12">
                                    <label>Date*</label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ $purchasesAddList->date }}" required />
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label>Vo. No*</label>
                                    <input 
                                        type="text" class="form-control" 
                                        name="product_id_list" id="product_id_list"
                                        value="{{ $purchasesAddList->product_id_list }}" readonly 
                                    />
                                </div>
                                <div class="col-md-4 col-sm-12">
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
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4 col-sm-12">
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
                                <div class="col-md-4 col-sm-12">
                                    <label>Ledger</label>
                                    <select class="form-control" name="account_ledger_id" id="account_ledger_id" onclick="account_details()" required>
                                        <option value="{{$purchasesAddList->account_ledger_id}}" selected>
                                            {{ $purchasesAddList->ledger->account_name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <label>Phone</label>
                                    <div id="phone" class="form-control"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <div id="address" class="form-control"></div>                                
                            </div>

                            <div class="form-group row overflow-auto">
                                <div class="col-md-12">
                                    <table class="table table-bordered" id="myTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Product</th>
                                                <th style="width:150px">Quantity</th>
                                                <th style="width:200px">Price</th>
                                                <th style="width:150px">Discount</th>
                                                <th style="width:200px">Subtotal</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <select  
                                                        onchange="Product()" 
                                                        id="item_name" name="item_name"
                                                        class="select2item form-control" data-placeholder="Select a Product">
                                                    </select>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="number" step="any" 
                                                        name="qty_product_value" id="qty_product_value"
                                                        class="form-control" min="0"
                                                        oninput="qty_product()" placeholder="Qty."
                                                    >
                                                </td>
                                                <td>
                                                    <div id="sales_price"></div>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="text" name="discount_on_product" 
                                                        id="discount_on_product"
                                                        oninput="qty_product()" class="form-control"
                                                        readonly placeholder="Discount"
                                                    />
                                                </td>
                                                <td id="hi">
                                                    <input type="number" id="subtotal_on_qty" readonly step="any" class="form-control" />
                                                    {{-- <span id="subtotal_on_qty"></span> --}}
                                                    <span id="subtotal_on_discount"></span>
                                                </td>
                                                <td>
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
                                                <td colspan="2" style="text-align: right;"><b>Item</b></td>
                                                <td style="width: 150px;" id="total_item">0</td>
                                                <td style="width: 150px;" class="text-end"><b>Total</b></td>
                                                <td style="width: 300px;">
                                                    <b><span id="total_sales_price"></span></b>
                                                </td>
                                               
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                                <td colspan="2" class="text-end">
                                                    <b>All Total Amount</b>
                                                </td>
                                                <td style="width: 300px;font-weight: 600;">
                                                    <span id="all_subtotal_amount"></span>
                                                </td>
                                               
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                                <td colspan="2" class="text-end">
                                                    <select 
                                                        onchange="account_details()" 
                                                        name="expense_ledger_id" id="expense_ledger_id" 
                                                        data-placeholder="Select Expense Ledger"
                                                        class="form-control"
                                                    >
                                                            <option value="0">None</option>
                                                            @if($purchasesAddList->ledgerexpanse)
                                                            <option value="{{$purchasesAddList->expense_ledger_id}}" selected>
                                                                {{optional($purchasesAddList->ledgerexpanse)->account_name??" "}}
                                                            </option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="number"
                                                        name="other_expense" id="other_bill" 
                                                        class="form-control" value="{{$purchasesAddList->other_bill}}" 
                                                        placeholder="Other Expense Amount"
                                                    />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end">
                                                    <b>Grand Total</b>
                                                </td>
                                                <td>
                                                    <b><span id="totalAmount">{{$purchasesAddList->grand_total }}</span></b>
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
                            <div class="form-group text-center mt-3">
                                <button type="submit" class="btn btn-primary" ><b>Update</b></button>
                                <button type="submit" name="print" value="1" class="btn btn-outline-info"><b>Update & Print</b></button>
                                <a href="{{ route('mb_cor_index') }}" class="btn btn-outline-danger"><b>Cencel</b></a>
                            </div>
                        </form>
                    </div>
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

    });




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
                    item = '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" style="text-align: center;height:30px;" value="' +
                        item_price + '">'
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
        var price_as_product = $('#price_as_product').val();

        $('#subtotal_on_discount').hide();
        // $('#subtotal_on_qty').show();
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
        var subtotal_on_product = (price_as_product * qty_product_value) - discount_on_product;

        htmlData += "<tr class='item'>"
        htmlData += "<td><input type='hidden' id='#new_item_id' name='new_item_id[]' value='"+item_id+"'/>" + item_name + "</td>"
        htmlData += "<td> <input class='item-qty form-control' min='0' type='number' name='new_qty[]' value='"+qty_product_value+"' /></td>"
        htmlData += "<td><input readonly class='form-control' type ='number' name='new_price[]' step='any' value='"+price_as_product+"' /> </td>"
        htmlData += "<td> <input readonly class='form-control' type ='number' name='new_discount[]' step='any' value='"+discount_on_product+"' /></td>"
        htmlData += "<td><input  class='item-charge form-control' readonly  type ='number' step='any' name='new_subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
        htmlData += "<td><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
        htmlData +="</tr>";
        $('#myTable tbody').append(htmlData)
        currentData();
        clearOldData();
    }

    function clearOldData(){
        // $('#item_name').val('');
        $('#item_name').val(null).trigger('change');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').val('');
    }
    newProduct()
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
                    data += "<tr class='item'>"
                    data += "<td><input type='hidden' id='old_item_id' name='item_id[]' value='"+value.item.id+"'/>" + value.item.name+ "</td>"
                    data += "<td><input class='item-qty form-control' readonly  type ='number' name='qty[]' value='"+value.qty+"' /></td>"
                    data += "<td><input class='form-control'  readonly  type ='number' name='price[]' step='any' value='"+value.price+"' /> </td>"
                    data += "<td> <input class='form-control' readonly  type ='number' name='discount[]' step='any' value='"+value.discount+"' /></td>"
                    data += "<td><input  class='item-charge form-control' readonly  type ='number' step='any' name='subtotal[]' value='"+value.subtotal_on_product+"' /> </td>"
                    data += "<td><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
                    data +="</tr>";
                    Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product)
                    Total_item = Number(Total_item)+ Number(value.qty)
                });

                $('#total_item').html(Total_item);
                $('#total_sales_price').html(Total_cost);
                $('#all_subtotal_amount').html(Total_cost);
                $('#myTable tbody').append(data)

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
