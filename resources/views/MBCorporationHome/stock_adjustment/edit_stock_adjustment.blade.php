@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; "> Stock Adjustment</h4>
    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">

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
                    $ad_value = App\StockAdjustment::where('adjustmen_vo_id',$adjustmen_no)->get();
                    @endphp
                    @foreach($ad_value as $ad_value_row)
                    <form action="{{URL::to('/Update/stock_adjustment/'.$ad_value_row->adjustmen_vo_id)}}"
                        method="POST">
                        @csrf
                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                            Add Stock Adjustment</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" value="{{$ad_value_row->date}}" name="date"
                                                        id="date" class="form-control" style="font-size: 12px;" />
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Vo. No
                                                    :</div>
                                                <div class="col-md-8">

                                                    <input type="text" class="form-control" name="adjustmen_vo_id"
                                                        id="adjustmen_vo_id" value="{{$ad_value_row->adjustmen_vo_id }}"
                                                        style="text-align: center;font-size: 12px;" readonly>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">
                                                    Reference No :</div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" style="font-size: 12px;"
                                                        name="refer_no" id="refer_id"
                                                        value="{{$ad_value_row->refer_no }}">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endforeach
                            <div class="col-md-12"
                                style="border: 1px solid #D6DBDF;padding-top: 10px;margin-bottom: 10px;">
                                <div class="row">
                                    <div class="col-md-6"
                                        style="font-size: 16px;font-weight: 800;background: #eee;margin: 0 0 10px 10px;padding: 5px 10px;">
                                        Generated
                                    </div>
                                </div>
                                <table class="table" style="border: 1px solid #eee;text-align: center;">

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
                                            <select class="form-control" id="item_name" name="item_name"
                                                style="text-align: center;height: 30px;" onclick="add_Product_search()">
                                                <option value="">Select</option>
                                                @foreach($Item as $item_row)
                                                <option value="{{$item_row->id}}">{{$item_row->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <select class="form-control" style="height: 30px;font-size: 12px;"
                                                name="add_godown_id" id="add_godown_id">
                                                <option value="">Select</option>
                                                @foreach($Godwn as $godwn_row)
                                                <option value="{{$godwn_row->id}}">{{$godwn_row->name}}
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
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total
                                            Price</td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                                id="total_add_sales_price"></span></td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-12" style="border: 1px solid #D6DBDF;padding-top: 10px;">
                                <div class="row">
                                    <div class="col-md-6"
                                        style="font-size: 16px;font-weight: 800;background: green;margin: 0 0 10px 10px;padding: 5px 10px;color: #fff">
                                        Consumed
                                    </div>
                                </div>
                                <table class="table" style="border: 1px solid #eee;text-align: center;">
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
                                    <tbody style="background: #F8F9F9;" id="data_maines_for_list">

                                    </tbody>
                                </table>
                                <table class="table"
                                    style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;">
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <select class="form-control" id="item_name_tow" name="item_name_tow"
                                                style="text-align: center;height: 30px;" onclick="Product_tow_search()">
                                                <option value="">Select</option>
                                                @foreach($Item as $item_row)
                                                <option value="{{$item_row->id}}">{{$item_row->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <select class="form-control" style="height: 30px;font-size: 12px;"
                                                id="godown_id_mines">
                                                <option value="">Select</option>
                                                @foreach($Godwn as $godwn_row)
                                                <option value="{{$godwn_row->id}}">{{$godwn_row->name}}
                                                </option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <input type="text" name="Tow_qty_product_value" id="Tow_qty_product_value"
                                                class="form-control" style="text-align: center;height: 30px;" value=""
                                                oninput="maines_qty_product()">
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"
                                            id="sales_price_tow"></td>

                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-size: 14px;"
                                            id="hi"><span id="Two_subtotal_on_qty"></span>.00
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">
                                            <a class="btn btn-sm btn-info" onclick="maines_addondemoproduct()"><i
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
                                            id="total_item">0</td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total
                                            Price</td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                                id="total_sales_price_two"></span></td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                    </tr>
                                </table>
                            </div>

                            <br>
                            <br>
                            <div style="text-align: center; color: #fff; font-weight: 800;">
                                <button type="submit" class="btn btn-success"
                                    style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                                <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <div>

        </div>
    </div>

</div>
</div>
</form>
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

        function cleardata(){
            $('#qty_product_value').val('');
            $('#discount_on_product').val('');
            $('#price_as_product').val('');
            $('#item_name').val('');
            $('#subtotal_on_qty').hide();
            $('#subtotal_on_discount').hide();

        }
        function add_Product_search(){
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

        function add_qty_product_search(){
            var price_as_product = $('#price_as_product').val();
            var qty_product = $('#qty_product_value').val();
            var Subtotal = price_as_product * qty_product
            var adjustmen_vo_id = $('#adjustmen_vo_id').val();
            $('#subtotal_on_qty').html(Subtotal);
        }

//----------------------------start store addondemoproduct----------------------------------------
        function addondemoproduct(){
            var adjustmen_vo_id = $('#adjustmen_vo_id').val();
            var item_name = $('#item_name').val();
            var page_name = 1;
            var date =  $('#date').val();
            var qty_product_value = $('#qty_product_value').val();
            var price_as_product = $('#price_as_product').val();
            var godown_id = $('#add_godown_id').val();
            var subtotal_on_product = price_as_product * qty_product_value
            $.ajax({

                type:"GET",
                dataType:"json",
                url:"{{url('/add_ondemoproduct_for_adjustment/store/')}}",
                data: {
                    adjustmen_vo_id:adjustmen_vo_id,
                    item_name:item_name,
                    page_name:page_name,
                    godown_id:godown_id,
                    date:date,
                    sales_price:price_as_product,
                    qty:qty_product_value,
                    subtotal_on_product:subtotal_on_product,
                    "_token": "{{ csrf_token() }}",

                },
                success:function(response){

                    cleardata();
                    newProduct_add();

                },

            })
        }
//----------------------------end store addondemoproduct----------------------------------------

//----------------------------Start newProduct----------------------------------------
        function newProduct_add(){
            var adjustmen_vo_id = $('#adjustmen_vo_id').val();
            var data_add_for_list = $('#data_add_for_list').val();
            var data_maines_for_list = $('#data_maines_for_list').val();


            $.ajax({
                type:"GET",
                dataType: "json",
                url:"{{url('/product_new_fild_for_add_inStock/-')}}"+adjustmen_vo_id,

                success:function(response){
                        var data =""
                        var tata =""
                        var Total_cost =""
                        var Total_item =""
                        var Total_cost_tow =""
                        var Total_item_tow =""
                    $.each(response, function(key, value){
                        if (value.page_name < 2) {
                        data = data + "<tr>"
                        data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>"+value.item.name+"</td>"
                        data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'>"+value.godown.name+"</td>"
                        data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.qty+"</td>"
                        data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.price+"</td>"
                        data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'>"+value.subtotal_on_product+"</td>"
                        data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
                        data = data +"<a class='btn btn-sm btn-danger' onclick='delete_add_sear_data("+value.id_row+")'><i class='fa fa-trash'></i></a>"
                        data = data+"</td>"

                        data = data + "</tr>";
                        Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product)
                        Total_item = Number(Total_item)+ Number(value.qty)
                    }else{
                        tata = tata + "<tr>"
                        tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>"+value.item.name+"</td>"
                        tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'>"+value.godown.name+"</td>"
                        tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.qty+"</td>"
                        tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.price+"</td>"
                        tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'>"+value.subtotal_on_product+"</td>"
                        tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
                        tata = tata +"<a class='btn btn-sm btn-danger' onclick='delete_add_sear_data("+value.id_row+")'><i class='fa fa-trash'></i></a>"
                        tata = tata+"</td>"

                        tata = tata + "</tr>";
                        Total_cost_tow = Number(Total_cost_tow)+ Number(value.subtotal_on_product)
                        Total_item_tow = Number(Total_item_tow)+ Number(value.qty)
                    }

                    });

                    $('#total_add_item').html(Total_item);
                    $('#total_add_sales_price').html(Total_cost);

                    $('#total_item').html(Total_item_tow);
                    $('#total_sales_price_two').html(Total_cost_tow);


                    $('#data_add_for_list').html(data);
                    $('#data_maines_for_list').html(tata);


                }
            })


        }

        newProduct_add();
//----------------------------End newProduct----------------------------------------

//----------------------------start Remove addondemoproduct----------------------------------------
    function delete_add_sear_data(id_row){

        $.ajax({
                type:"GET",
                dataType: "json",
                url:"{{url('/adjustment_product_delete_fild_from_add/-')}}"+id_row,

                success:function(response){

                    $.each(response, function(key, value){
                          cleardata();
                        console.log('561456 hello '+ id_row);
                    })



                }
            })
        newProduct_add();

    }
//----------------------------end Remove addondemoproduct----------------------------------------


        function Product_tow_search(){
            var item_name_tow = $('#item_name_tow').val();
            $.ajax({
                type:"GET",
                dataType: "json",
                url:"{{url('/product_as_price/-')}}"+item_name_tow,

                success:function(response){
                    var item
                    var item_price

                    $.each(response, function(key, value){
                        item_price = value.purchases_price
                        item = '<input type="show" name="price_as_product_tow" id="price_as_product_tow" oninput="maines_qty_product()" class="form-control" style="text-align: center;height:30px;" value="'+item_price+'">'
                    })
                    $('#sales_price_tow').html(item);

                }
            })
        }

        function maines_qty_product(){
            var sales_price_tow = $('#price_as_product_tow').val();
            var Tow_qty_product_value = $('#Tow_qty_product_value').val();
            var Subtotal = sales_price_tow * Tow_qty_product_value
            $('#Two_subtotal_on_qty').html(Subtotal);
        }
//----------------------------start store addondemoproduct----------------------------------------
        function maines_addondemoproduct(){
            var adjustmen_vo_id = $('#adjustmen_vo_id').val();
            var item_name = $('#item_name_tow').val();
            var qty_product_value = $('#Tow_qty_product_value').val();
            var page_name = 2;
            var price_as_product = $('#price_as_product_tow').val();
            var godown_id = $('#godown_id_mines').val();
            var subtotal_on_product = price_as_product * qty_product_value
            $.ajax({

                type:"GET",
                dataType:"json",
                url:"{{url('/add_ondemoproduct_for_adjustment/store/')}}",
                data: {
                    adjustmen_vo_id:adjustmen_vo_id,
                    item_name:item_name,
                    page_name:page_name,
                    godown_id:godown_id,
                    sales_price:price_as_product,
                    qty:qty_product_value,
                    subtotal_on_product:subtotal_on_product,
                    "_token": "{{ csrf_token() }}",

                },
                success:function(response){
                    cleardata();
                    newProduct_add();

                },

            })
        }
//----------------------------end store addondemoproduct----------------------------------------

   function cleardata(){
            $('#qty_product_value').val('');
            $('#discount_on_product').val('');
            $('#price_as_product').val('');
            $('#item_name').val('');
            $('#godown_id_mines').val('');
            $('#add_godown_id').val('');

            $('#subtotal_on_qty').hide();

            $('#Two_subtotal_on_qty').hide();

            $('#item_name_tow').val('');
            $('#Tow_qty_product_value').val('');
            $('#price_as_product_tow').val('');


        }

</script>



@endsection
