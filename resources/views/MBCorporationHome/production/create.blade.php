@extends('MBCorporationHome.apps_layout.layout')
@section("title", " Working Order To Product")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="card-title">Working Order</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('production/orderList') }}" method="GET">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="working_id">Working Order</label>
                                        <select class="form-control"  name="working_id" style="text-align: center;height: 30px;">
                                            <option value="" hidden>Select Working Order</option>
                                            @foreach ($working_order as $order)
                                                <option value="{{$order->id}}" {{ request()->working_id == $order->id?'Selected': ' '  }}>{{ $order->vo_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="submit">Submit</button>
                                    </div>
                                </div>                                
                            </div>
                        </form>
                        <div class="col-md-12" style="border: 1px solid #D6DBDF;padding-top: 10px;margin-bottom: 10px;">

                            <table class="table" style="border: 1px solid #eee;text-align: center;">

                                <tr style="background-color: #D6DBDF;">
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 300px;">Product</td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Godown</td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Quantity
                                    </td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 150px;">Price</td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 250px;">Subtotal
                                    </td>
                                </tr>
                                @php $ordertotal=0; @endphp
                                @if(request()->working_id)
                                    <tbody style="background: #F8F9F9;" >
                                        

                                        @foreach ($order_list->stock as $order)
                                        <tr>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                                <select class="form-control"
                                                    style="text-align: center;height: 30px;" disabled>
                                                    <option value="">Select</option>
                                                    @foreach ($Item as $item_row)
                                                    <option value="{{ $item_row->id }}"{{ $order->item_id ==  $item_row->id?'Selected': ' ' }}>{{ $item_row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                                <select class="form-control" style="height: 30px;font-size: 12px;" disabled>
                                                    <option value="">Select</option>
                                                    @foreach ($godowns as $godown_row)
                                                    <option value="{{ $godown_row->id }}"{{ $order->godown_id ==  $godown_row->id?'Selected': ' ' }}>{{ $godown_row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                                <input type="text"
                                                    class="form-control" style="text-align: center;height: 30px;" value="{{ $order->out_qty??0 }}"
                                                    disabled/>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"
                                                >{{ number_format( $order->average_price,2) }}</td>

                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-size: 14px;"
                                                ><span >{{ number_format( $order->out_qty*$order->average_price,2) }}</span>
                                            </td>
                                        </tr>
                                        @php
                                            $ordertotal +=$order->out_qty*$order->average_price;
                                        @endphp
                                        @endforeach
                                        
                                        

                                    </tbody>
                                @endif
                            </table>
                            <br>
                            <table class="table" style="border: 1px solid #eee;text-align: center;">
                                <thead >
                                    <tr style="background-color: #D6DBDF;">
                                        <td>Title</td>
                                        <td>Qty</td>
                                        <td>Price</td>
                                        <td>Total</td>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $stotal = 0;
                                    @endphp
                                    @foreach($order_costinfo as $row )
                                    @php
                                    $stotal +=$row->total;
                                    @endphp
                                        <tr class='itemss'>
                                            <td>{{$row->title}}</td>
                                            <td>{{$row->qty}}</td>
                                            <td>{{$row->price}}</td>
                                            <td>{{$row->total}}</td>
                                             
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><strong>Total</strong></td>
                                        <td><strong>{{number_format($ordertotal+ $stotal, 2)}}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>

        <form action="{{ route('production.store')}}" method="post">
            @csrf
            <input type="hidden" name="working_id" value="{{ request()->working_id??' ' }}">
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="card-title">Add Production </h4>
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

                        <div class="col-md-12">
                            <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                <tr>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;">
                                        <div class="row">
                                            <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Date :
                                            </div>
                                            <div class="col-md-8">
                                                <input type="date" name="date" id="date" class="form-control"
                                                    style="font-size: 12px;" />
                                            </div>
                                        </div>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                        <div class="row">
                                            <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Vch. No :
                                            </div>
                                            <div class="col-md-8">
                                                @php
                                                use App\Production;
                                                $adjustmen_vo_id = App\Helpers\Helper::IDGenerator(new Production(),
                                                'vo_no', 4, 'Pro');
                                                @endphp
                                                <input type="text" class="form-control" name="adjustmen_vo_id"
                                                    id="adjustmen_vo_id" value="{{ $adjustmen_vo_id }}"
                                                    style="text-align: center;font-size: 12px;">
                                            </div>
                                        </div>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                        <div class="row">
                                            <div class="col-md-4" style="text-align: right;padding-top: 5px;">
                                                Reference No :</div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" style="font-size: 12px;"
                                                    name="refer_id" id="refer_id"
                                                    placeholder="Reference No"
                                                >
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-12" style="border: 1px solid #D6DBDF;padding-top: 10px;margin-bottom: 10px;">

                            <table class="table" style="border: 1px solid #eee;text-align: center;" id="myTable">

                                <tr style="background-color: #D6DBDF;">
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 300px;">Product</td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Godown</td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Quantity
                                    </td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 150px;">Pur.Price</td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 250px;">Subtotal
                                    </td>
                                    <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 50px;">#</td>
                                </tr>
                                <tbody style="background: #F8F9F9;" id="sss">
                                    <tr></tr>
                                </tbody>
                            </table>
                            <table class="table" style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;">
                                <tr>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                        <select 
                                            onchange="add_Product_search()" id="item_name" 
                                            name="item_name"class="select2item" style="width: 300px" 
                                            data-placeholder="Select a Product"
                                        >
                                        </select>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                        <select class="form-control" style="height: 30px;font-size: 12px;"
                                            name="add_godown_id" id="add_godown_id">
                                            <option value="">Select</option>
                                            @foreach ($godowns as $godwn_row)
                                            <option value="{{ $godwn_row->id }}">{{ $godwn_row->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                        <input type="text" name="qty_product_value" id="qty_product_value"
                                            class="form-control" style="text-align: center;height: 30px;" value=""
                                            oninput="add_qty_product_search()">
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"
                                        id="sales_price"></td>

                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-size: 14px;"
                                        id="hi"
                                    >
                                        <!--<span id="subtotal_on_qty"></span>-->
                                        <input type="number" id="subtotal_on_qty" placeholder="Sub Total" />
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">
                                        <a class="btn btn-sm btn-info" onclick="addondemoproduct()"><i
                                                class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                            </table>
                            <table class="table" style="border: 1px solid #eee;text-align: center;">
                                <tr style="background-color: #F8F9F9;">
                                    <td
                                        style="border-right: 1px solid #eee;padding: 5px 5px;width: 400px;text-align: right; ">
                                        Item</td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;"
                                        id="total_add_item">0</td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total Price
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                            id="total_add_sales_price"></span></td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control" name="note" rows="2" cols="10" placeholder="Note..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success" ><b>Save</b></button>
                        <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Update & Print</b></button>
                        <a href="{{ route('workingOrder.index') }}" class="btn btn-outline-danger"><b>Cancel</b></a>
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
    
    $("#subtotal_on_qty").on("input", function() {
        let subtotal = $("#subtotal_on_qty").val();
        let qty = $('#qty_product_value').val();
        $('#price_as_product').val(Number(subtotal/qty).toFixed(2));
    });

        function cleardata() {
            $('#qty_product_value').val('');
            $('#discount_on_product').val('');
            $('#price_as_product').val('');
            $('#item_name').val('');
            $('#subtotal_on_qty').hide();
            $('#subtotal_on_discount').hide();

        }

        function add_Product_search() {
            var item_name = $('#item_name').val();
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ url('/product_as_price/-') }}" + item_name,

                success: function(response) {
                    var item;
                    var item_price;
                    $.each(response, function(key, value) {
                        item_price = value.sales_price;
                        item =
                            '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" style="text-align: center;height:30px;" value="' +
                            item_price + '">'
                    });
                    $('#sales_price').html(item);
                    $('#subtotal').html(item_price);
                }
            })
        }

        function add_qty_product_search() {
            var price_as_product = $('#price_as_product').val();
            var qty_product = $('#qty_product_value').val();
            var totalAMount = Number(price_as_product) * Number(qty_product);
            $('#subtotal_on_qty').val(totalAMount);
        }

        function qty_product(){
            var price_as_product = $('#price_as_product').val();
            $('#subtotal_on_discount').hide();
            $('#subtotal_on_qty').html();
            $('#total_sales_price').val('');
            $('#all_subtotal_amount').val('');
            $('#total_amount').val('');
            var qty_product = $('#qty_product_value').val();
            // var discount_on_product = $('#discount_on_product').val();
            var pre_amount = $('#pre_amount').val();
            var Subtotal = (price_as_product * qty_product)
            var product_id_list = $('#product_id_list').val();
            // console.log(Subtotal, price_as_product);
            $('#subtotal_on_qty').val(Subtotal);

        }
        //----------------------------start store addondemoproduct----------------------------------------
        function addondemoproduct() {
            let htmlData = '';
            var page_name = 2;
            var date = $('#date').val();
            var item_id = $('#item_name').val();
            var godown_id = $('#add_godown_id').val();
            var item_name = $('#item_name option:selected').text();
            var godown_name = $('#add_godown_id option:selected').text();
            var qty_product_value = $('#qty_product_value').val()||1;
            var price_as_product = $('#price_as_product').val();
            // var subtotal_on_product = (price_as_product * qty_product_value);
            var subtotal_on_product = Number($('#subtotal_on_qty').val());

            htmlData += "<tr class='item'>"
            htmlData += "<td  style='display:none'><input type='hidden' name='item_id[]' value='"+item_id+"'/> </td>"
            htmlData += "<td  style='display:none'><input type='hidden' name='godown_id[]' value='"+godown_id+"'/> </td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + item_name + "</td>"
            htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input  style='border:0;text-align:center' readonly  type ='text'  value='"+godown_name+"' /></td>"
            htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center' readonly  type ='text' name='qty[]' value='"+qty_product_value+"' /></td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='price[]' value='"+price_as_product+"' /> </td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
            htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
            htmlData += "</td>"
            htmlData +="</tr>";
            $('#myTable tbody tr:last').after(htmlData)
            currentData();
            clearOldData();
        }
        function delete_data(delelet) {
            (delelet).closest('tr').remove();
            currentData();
        }
        function clearOldData(){
        $('#item_name').val('');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').val('');
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
            $('#total_add_sales_price').html(subTotal.toFixed(2));
            $('#total_add_item').html(subQty.toFixed(2));
        }

        //----------------------------start store addondemoproduct----------------------------------------
        function maines_addondemoproduct() {
            var adjustmen_vo_id = $('#adjustmen_vo_id').val();
            var item_name = $('#item_name_tow').val();
            var qty_product_value = $('#Tow_qty_product_value').val();
            var page_name = 2;
            var price_as_product = $('#price_as_product_tow').val();
            var godown_id = $('#godown_id_mines').val();
            var subtotal_on_product = price_as_product * qty_product_value
            $.ajax({

                type: "GET",
                dataType: "json",
                url: "{{ url('/production/adjustment') }}",
                data: {
                    vo_no: adjustmen_vo_id,
                    item_name: item_name,
                    page_name: page_name,
                    godown_id: godown_id,
                    sales_price: price_as_product,
                    qty: qty_product_value,
                    subtotal_on_product: subtotal_on_product,
                    "_token": "{{ csrf_token() }}",

                },
                success: function(response) {

                    cleardata();
                    newProduct_add();

                },

            })
        }
        //----------------------------end store addondemoproduct----------------------------------------

        function SaveAllData() {
            var date = $('#date').val();
            var total_add_sales_price = $('#total_add_sales_price').val();
            var adjustmen_vo_id = $('#adjustmen_vo_id').val();
            var refer_id = $('#refer_id').val();

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ route('production.store') }}",
                data: {
                    date: date,
                    adjustmen_vo_id: adjustmen_vo_id,
                    refer_id: refer_id,
                    working_id: '{{ request()->working_id }}',
                    total: total_add_sales_price,
                    "_token": "{{ csrf_token() }}",
                },

                success: function(response) {

                    $(document).ready(function() {
                        setTimeout(function() {
                            window.location.href = "{{ route('production.index') }}";
                        }, 3000);

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
                        title: 'Working Order is Added Successfully',

                    })
                    //----end sweet alert------------------
                },
                error:function(req){
                    const Msg = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 3000,
                        background: 'red',
                    })
                    Msg.fire({
                        type: 'warning',
                        title: req,

                    })
                }
            })
        }

        function cleardata() {
            $('#qty_product_value').val('');
            $('#discount_on_product').val('');
            $('#price_as_product').val('');
            $('#item_name').val('');
            $('#godown_id_mines').val('');
            $('#add_godown_id').val('');

            $('#subtotal_on_qty').val('');

            $('#Two_subtotal_on_qty').hide();

            $('#item_name_tow').val('');
            $('#Tow_qty_product_value').val('');
            $('#price_as_product_tow').val('');


        }
</script>

@endpush
