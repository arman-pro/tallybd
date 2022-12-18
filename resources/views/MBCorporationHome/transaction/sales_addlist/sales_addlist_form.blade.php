@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Sale')

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
                                    <th class="fw-bold">Product</th>
                                    <th style="width:150px;" class="fw-bold">Quantity</th>
                                    <th style="width:200px;" class="fw-bold">Price</th>
                                    <th style="width:150px;" class="fw-bold">Discount</th>
                                    <th style="width:200px;" class="fw-bold">Subtotal</th>
                                    <th>#</th>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>                                            
                                            <select 
                                                onchange="Product()" id="item_name" style="width:100%;"
                                                name="item_name" class="select2item form-control fw-bold" 
                                                data-placeholder="Select a Product"
                                            >
                                            </select>
                                        </td>
                                        <td style="width:100px;">
                                            <input 
                                                type="number" name="qty_product_value" id="qty_product_value"
                                                class="form-control fw-bold" min="0" placeholder="Quantity" step="any"
                                                oninput="qty_product()" autocomplete="off" />
                                        </td>
                                        <td id="sales_price"></td>
                                        <td>
                                            <input 
                                                type="number" name="discount_on_product" id="discount_on_product"
                                                oninput="qty_product()" class="form-control fw-bold" min="0" step="1"
                                                value="0" readonly placeholder="Discount" />
                                        </td>
                                        <td id="hi">
                                            <input type="number" class="form-control fw-bold" min="0" step="any" placeholder="Sub Total" id="subtotal_on_qty" />
                                            {{-- <span id="subtotal_on_discount"></span>.00 --}}
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
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-responsive">
                                <tbody>
                                    <tr>
                                        <td colspan="3" style="text-align: right;"><b>Total Qty.</b></td>
                                        <td id="total_item">0</td>
                                        <td class="text-end"><b>Total</b></td>
                                        <td>
                                            <span id="total_sales_price"></span>
                                        </td>
                                        <td></td>
                                    </tr>
                                
                                    <tr>
                                        <td colspan="5" class="text-end">
                                            <select  
                                                onchange="account_details()"
                                                name="expense_ledger_id" id="expense_ledger_id"
                                                class="form-control fw-bold" data-placeholder="Others Expense Ledger"
                                            >
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="other_expense" id="other_bill" class="form-control fw-bold" placeholder="Other Amount" />
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">
                                            <b>Grand Total</b>
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="totalAmount" id="totalAmount" class="form-control" />
                                        </td>
                                        <td></td>
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
        $('#totalAmount').val(totalBill.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));
        $('#total_item').html(subQty.toFixed(2));
    }
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
                    item = '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control fw-bold" value="'+item_price+'">'
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

    htmlData += "<tr class='item'>"
    htmlData += "<td><input type='hidden' name='item_id[]' value='"+item_id+"'/>" + item_name + "</td>"
    htmlData += "<td><input class='item-qty form-control'  type ='number' step='any' name='qty[]' value='"+qty_product_value+"' /></td>"
    htmlData += "<td><input readonly type ='number' class='form-control' name='price[]' value='"+price_as_product+"' /> </td>"
    htmlData += "<td><input readonly type ='number' class='form-control' name='discount[]' value='"+discount_on_product+"' /></td>"
    htmlData += "<td><input class='item-charge form-control' readonly type ='number' step='any' name='subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
    htmlData += "<td><a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a></td>";
    htmlData +="</tr>";
    $('#product_table tbody').append(htmlData)
    currentData();
    clearOldData();
}

$(document).on('input', '.item-qty', function(){
    var qty = $(this).val();
    var price = $(this).closest('tr').find('input[name="price[]"]').val();
    $(this).closest('tr').find('input[name="subtotal[]"]').val(qty*price);
    currentData();
});
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
