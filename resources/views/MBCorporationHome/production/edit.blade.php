@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Edit Working Order")
@section('admin_content')

    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="card-title">View Working Order</h4>
                    </div>
                    <div class="card-body">                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="working_id">Working Order</label>
                                <select class="form-control" id="working_id" name="working_id"
                                    style="text-align: center;height: 30px;" disabled>
                                    @foreach ($working_order as $order)
                                        <option value="{{ $order->id }}"
                                            {{ $production->working_id == $order->id ? 'Selected' : ' ' }}>
                                            {{ $order->vo_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                        </div>
                        {{-- </form> --}}
                        <div class="col-md-12"
                            style="border: 1px solid #D6DBDF;padding-top: 10px;margin-bottom: 10px;">

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

                                <tbody style="background: #F8F9F9;">
                                    @foreach ($production->working->stock as $order)
                                        <tr>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                                <select class="form-control"
                                                    style="text-align: center;height: 30px;" disabled>
                                                    <option value="">Select</option>
                                                    @foreach ($Item as $item_row)
                                                        <option value="{{ $item_row->id }}"
                                                            {{ $order->item_id == $item_row->id ? 'Selected' : ' ' }}>
                                                            {{ $item_row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                                <select class="form-control" style="height: 30px;font-size: 12px;"
                                                    disabled>
                                                    <option value="">Select</option>
                                                    @foreach ($godowns as $godown_row)
                                                        <option value="{{ $godown_row->id }}"
                                                            {{ $order->godown_id == $godown_row->id ? 'Selected' : ' ' }}>
                                                            {{ $godown_row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                                <input type="text" class="form-control"
                                                    style="text-align: center;height: 30px;"
                                                    value="{{ $order->out_qty ?? 0 }}" disabled />
                                            </td>
                                            <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">
                                                {{ number_format($order->average_price, 2) }}</td>

                                            <td
                                                style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-size: 14px;">
                                                <span>{{ number_format($order->out_qty * $order->average_price, 2) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>
                <form action="{{ URL::to('/production/update/' . $production->vo_no) }}" method="POST">
                    @csrf
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="card-title">Edit Working Order</h4>
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
                    
                        @php
                            $ad_value = App\DemoProductProduction::where('vo_no', $vo_no)->get();
                        @endphp

                            

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;">
                                            <div class="row">
                                                <div class="col-md-4"
                                                    style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" value="{{ $production->date }}"
                                                        name="date" id="date" class="form-control"
                                                        style="font-size: 12px;" />
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4"
                                                    style="text-align: right;padding-top: 5px;">Vo. No
                                                    :</div>
                                                <div class="col-md-8">

                                                    <input type="text" class="form-control" name="adjustmen_vo_id"
                                                        id="adjustmen_vo_id" value="{{ $production->vo_no }}"
                                                        style="text-align: center;font-size: 12px;" readonly>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4"
                                                    style="text-align: right;padding-top: 5px;">
                                                    Reference No :</div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control"
                                                        style="font-size: 12px;" name="refer_no" id="refer_id"
                                                        value="{{ $production->refer_no }}"
                                                        placeholder="Reference No"
                                                    >
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                                

                            <div class="col-md-12"
                                style="border: 1px solid #D6DBDF;padding-top: 10px;margin-bottom: 10px;" >

                                <table class="table" style="border: 1px solid #eee;text-align: center;" id="myTable">

                                    <tr style="background-color: #D6DBDF;">
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 300px;">Product
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Godown
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Quantity
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 150px;">Price
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 250px;">Subtotal
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 50px;">#</td>
                                    </tr>
                                    <tbody style="background: #F8F9F9;" id="data_add_for_list">

                                    </tbody>
                                </table>
                                <table class="table"
                                    style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;">
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <select 
                                                onchange="add_Product_search()" id="item_name" name="item_name"
                                                class="select2item" style="width: 200px"
                                                data-placeholder="Select a Product"
                                            >
                                            </select>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <select class="form-control" style="height: 30px;font-size: 12px;"
                                                name="add_godown_id" id="add_godown_id">
                                                <option value="">Select</option>
                                                @foreach ($godowns as $godwn_row)
                                                    <option value="{{ $godwn_row->id }}">{{ $godwn_row->name }}
                                                    </option>
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
                                            id="hi"><span id="subtotal_on_qty"></span>.00
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">
                                            <a class="btn btn-sm btn-info" onclick="addondemoproduct()"><i class="fa fa-plus"></i></a>
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
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total
                                            Price</td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                                id="total_add_sales_price"></span></td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                    </tr>
                                </table>
                            </div> 
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success"><b>Update</b></button>
                        <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Update & Print</b></button>
                        <a href="{{ route('mb_cor_index') }}" class="btn btn-outline-danger"><b>Cancel</b></a>
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
        $(".select2item").select2({
            ajax: {
                url: '{{ url('activeItem') }}',
                dataType: 'json',
                type: "GET",
                data: function(params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function(data) {

                    var res = data.items.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    });
                    return {
                        results: res
                    };
                }
            },

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
                        item_price = value.purchases_price
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
            var Subtotal = price_as_product * qty_product;
            $('#subtotal_on_qty').html(Subtotal);
        }

        function qty_product(){
            var price_as_product = $('#price_as_product').val();
            $('#subtotal_on_discount').hide();
            $('#subtotal_on_qty').show();
            $('#total_sales_price').val('');
            $('#all_subtotal_amount').val('');
            $('#total_amount').val('');
            var qty_product = $('#qty_product_value').val();
            // var discount_on_product = $('#discount_on_product').val();
            var pre_amount = $('#pre_amount').val();
            var Subtotal = (price_as_product * qty_product) ;
            var product_id_list = $('#product_id_list').val();
            $('#subtotal_on_qty').html(Subtotal);

        }

        //----------------------------start store addondemoproduct----------------------------------------
        function addondemoproduct() {

            let htmlData = '';
            var page_name = 1;
            var date = $('#date').val();
            var item_id = $('#item_name').val();
            var godown_id = $('#add_godown_id').val();
            var item_name = $('#item_name option:selected').text();
            var godown_name = $('#add_godown_id option:selected').text();
            var qty_product_value = $('#qty_product_value').val() || 1;
            var price_as_product = $('#price_as_product').val();
            var subtotal_on_product = (price_as_product * qty_product_value);
            htmlData += "<tr class='item'>"
            htmlData += "<td  style='display:none'><input type='hidden' name='new_item_id[]' value='" + item_id +"'/> </td>"
            htmlData += "<td  style='display:none'><input type='hidden' name='new_godown_id[]' value='" + godown_id + "'/> </td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + item_name + "</td>"
            htmlData +="<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input  style='border:0;text-align:center' readonly  type ='text'  value='" +godown_name + "' /></td>"
            htmlData +="<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center' readonly  type ='text' name='new_qty[]' value='" +qty_product_value + "' /></td>"
            htmlData +="<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='new_price[]' value='" +price_as_product + "' /> </td>"
            htmlData +="<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='new_subtotal[]' value='" +subtotal_on_product.toFixed(2) + "' /> </td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
            htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
            htmlData += "</td>"
            htmlData += "</tr>";
            $('#myTable tbody tr:last').after(htmlData)
            currentData();
            clearOldData();

        }

        function clearOldData() {
            $('#item_name').val('');
            $('#qty_product_value').val('');
            $('#discount_on_product').val('');
            $('#price_as_product').val('');
            $('#subtotal_on_qty').text('');
        }

        function delete_data(delelet) {
            (delelet).closest('tr').remove();
            currentData();
        }

        function currentData() {
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
            $('#total_add_sales_price').html(subTotal.toFixed(2));

            $('#total_add_item').html(subQty.toFixed(2));
        }
        //----------------------------end store addondemoproduct----------------------------------------

        //----------------------------Start newProduct----------------------------------------
        function newProduct_add() {

            var vo_no = $('#adjustmen_vo_id').val();
            var data_add_for_list = $('#data_add_for_list').val();
            var data_maines_for_list = $('#data_maines_for_list').val();
            $.ajax({
                type:"GET",
                dataType: "json",
                url:"{{url('/workingorder/findProductRow?vo_no=')}}"+vo_no,

                success:function(response){
                        var htmlData =""
                        var tata =""
                        var Total_cost =""
                        var Total_item =""
                        var Total_cost_tow =""
                        var Total_item_tow =""
                    $.each(response, function(key, value){
                        htmlData += "<tr class='item'>"
                        htmlData += "<td  style='display:none'><input type='hidden' name='item_id[]' value='"+value.item_id+"'/> </td>"
                        htmlData += "<td  style='display:none'><input type='hidden' name='godown_id[]' value='"+value.godown_id+"'/> </td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + value.item.name + "</td>"
                        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input  style='border:0;text-align:center' readonly  type ='text'  value='"+value.godown.name+"' /></td>"
                        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center' readonly  type ='text' name='qty[]' value='"+value.qty+"' /></td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='price[]' value='"+value.price+"' /> </td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='subtotal[]' value='"+(value.subtotal_on_product)+"' /> </td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
                        htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
                        htmlData += "</td>"
                        htmlData +="</tr>";
                        Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product)
                        Total_item = Number(Total_item)+ Number(value.qty)
                    });


                    $('#total_add_item').html(Total_item);
                    $('#total_add_sales_price').html(Total_cost);



                    $('#data_add_for_list').html(htmlData);
                    $('#data_maines_for_list').html(htmlData);


                }
            })

        }

        newProduct_add();
        //----------------------------End newProduct----------------------------------------

        //----------------------------start Remove addondemoproduct----------------------------------------
        function delete_add_sear_data(id_row) {

            $.ajax({
                type: "GET",
                dataType: "json",
                url: "{{ url('/production/delete_field_from_add/-') }}" + id_row,

                success: function(response) {

                    $.each(response, function(key, value) {
                        cleardata();
                        // console.log('561456 hello '+ id_row);
                    })



                }
            })
            newProduct_add();

        }
        //----------------------------end Remove addondemoproduct----------------------------------------







    </script>
@endpush
