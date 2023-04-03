@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Stock Adjustment')
@section('admin_content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <form action="{{ url('SaveAllData_adjusment/store/')}}" method="post">
                @csrf
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">Add Stock Adjustment</h4>
                </div>
                <div class="card-body" >
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                <tr>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;">
                                        <div class="row">
                                            <div class="col-md-4" style="text-align: right;padding-top: 5px;">Date :
                                            </div>
                                            <div class="col-md-8">
                                                <input type="date" name="date" id="date" class="form-control"
                                                    style="font-size: 12px;" />
                                            </div>
                                        </div>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                        <div class="row">
                                            <div class="col-md-4" style="text-align: right;padding-top: 5px;">Vch. No :
                                            </div>
                                            <div class="col-md-8">
                                                @php
                                                use App\StockAdjustment;
                                                $adjustmen_vo_id = App\Helpers\Helper::IDGenerator(new StockAdjustment,
                                                'adjustmen_vo_id', 3, 'SAd');
                                                @endphp
                                                <input type="text" class="form-control" name="adjustmen_vo_id"
                                                    id="adjustmen_vo_id" value="{{$adjustmen_vo_id}}"
                                                    style="text-align: center;font-size: 12px;">
                                            </div>
                                        </div>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                        <div class="row">
                                            <div class="col-md-4" style="text-align: right;padding-top: 5px;">Reference
                                                No :</div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" style="font-size: 12px;"
                                                    name="refer_id" id="refer_id">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-12" style="border: 1px solid #D6DBDF;padding-top: 10px;margin-bottom: 10px;">
                            <div class="row">
                                <div class="col-md-4"
                                    style="font-size: 16px;font-weight: 800;background:Green;margin: 0 0 10px 10px;padding: 5px 10px;color: #fff">
                                    Generated (create)(+)
                                </div>
                            </div>
                            <table class="table" style="border: 1px solid #eee;text-align: center;">

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
                                <tbody style="background: #F8F9F9;" id="data_add_for_list">

                                </tbody>
                            </table>
                            <table class="table"
                                style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;" id="myTable">
                                <thead>
                                <tr>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                        <select class="form-control" id="item_name"
                                            style="text-align: center;height: 30px;" onchange="add_Product_search(this)">
                                            <option value="" hidden>Select a Product</option>
                                            @foreach($Item as $item_row)
                                            <option value="{{$item_row->id}}">{{$item_row->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                        <select class="form-control" style="height: 30px;font-size: 12px;"
                                            id="add_godown_id">
                                            <option value="" hidden>Select a Godown</option>
                                            @foreach($Godwn as $godwn_row)
                                            <option value="{{$godwn_row->id}}">{{$godwn_row->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                        <input type="text" name="qty_product_value" id="qty_product_value"
                                            class="form-control qty_product_value_one" style="text-align: center;height: 30px;" value=""
                                            oninput="add_qty_product_search(this)">
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">
                                            <input type="text" name="price_as_product" id="price_as_product"
                                            class="form-control price_as_product_one" style="text-align: center;height: 30px;" >
                                        </td>

                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-size: 14px;"
                                        id="hi"><span id="subtotal_on_qty"></span>
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">
                                        <a class="btn btn-sm btn-info" onclick="addondemoproduct(this)"><i
                                                class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
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
                        <div class="col-md-12" style="border: 1px solid #D6DBDF;padding-top: 10px;">
                            <div class="row">
                                <div class="col-md-3"
                                    style="font-size: 16px;font-weight: 800;background: Red;margin: 0 0 10px 10px;padding: 5px 10px;color: #fff">
                                    Consumed (wasted)(-)
                                </div>
                            </div>
                            <table class="table" style="border: 1px solid #eee;text-align: center;">
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
                                <tbody style="background: #F8F9F9;" id="data_maines_for_list">

                                </tbody>
                            </table>
                            <table class="table" style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;" id="myTable_2">
                                <thead>
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <select class="form-control" id="item_name"
                                                style="text-align: center;height: 30px;" onchange="add_Product_search(this)">
                                                <option value="" hidden>Select a Product</option>
                                                @foreach($Item as $item_row)
                                                <option value="{{$item_row->id}}">{{$item_row->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <select class="form-control" style="height: 30px;font-size: 12px;"
                                                id="add_godown_id">
                                                <option value="" hidden>Select a Godown</option>
                                                @foreach($Godwn as $godwn_row)
                                                <option value="{{$godwn_row->id}}">{{$godwn_row->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <input type="text" name="qty_product_value" id="qty_product_value"
                                                class="form-control" style="text-align: center;height: 30px;" value=""
                                                oninput="maines_add_qty_product_search(this)">
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">
                                                <input type="text" name="price_as_product" id="price_as_product"
                                                class="form-control" style="text-align: center;height: 30px;">
                                            </td>
    
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-size: 14px;"
                                            id="hi"><span id="subtotal_on_qty"></span>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;">
                                            <a class="btn btn-sm btn-info" onclick="maines_addondemoproduct(this)"><i
                                                    class="fa fa-plus"></i></a>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>

                            {{-- maines_addondemoproduct(this) --}}
                            <table class="table" style="border: 1px solid #eee;text-align: center;">
                                <tr style="background-color: #F8F9F9;">
                                    <td
                                        style="border-right: 1px solid #eee;padding: 5px 5px;width: 400px;text-align: right; ">
                                        Item</td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;"
                                        id="total_item">0</td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total Price
                                    </td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                            id="total_sales_price_two"></span></td>
                                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                </tr>
                            </table>
                        </div>

                        
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-success" ><b>Save</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
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

    function cleardata(){
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#item_name').val('');
        $('#subtotal_on_qty').hide();
        $('#subtotal_on_discount').hide();

    }
    
    $('#myTable #price_as_product').on("input", function() {
        let price = $(this).val();
        let qty = $("#myTable #qty_product_value").val();
        $("#myTable #subtotal_on_qty").text(qty * price);
    });
    
    $('#myTable_2 #price_as_product').on("input", function() {
        let price = $(this).val();
        let qty = $("#myTable_2 #qty_product_value").val();
        $("#myTable_2 #subtotal_on_qty").text(qty * price);
    });
    
    function add_Product_search(item){
        var item_name = $(item).val();
        var itemhtml;
        var item_price=0;
        if(item_name) {
            $.ajax({
                type:"GET",
                dataType: "json",
                url:"{{url('/get-product-price')}}/"+item_name,
                success:function(response){
                    item_price += parseInt(response.purchases_price);
                    $(item).closest('tr').find('#qty_product_value').val(1)
                    $(item).closest('tr').find('#price_as_product').val(item_price)
                    $(item).closest('tr').find('#subtotal_on_qty').html(item_price);
                }
            });
        }
        
    }

    function add_qty_product_search(row){
        var price_as_product =  $(row).closest('tr').find('#price_as_product').val();
        var qty_product =  $(row).val();
        var subtotal = parseInt(price_as_product) * parseInt(qty_product);
        $(row).closest('tr').find('#subtotal_on_qty').html(subtotal.toFixed(2));
    }

    function addondemoproduct(row){
        let htmlData = '';
        var item_id = $(row).closest('tr').find('td #item_name').val();
        var item_name = $(row).closest('tr').find('td #item_name option:selected').text();
        var godown_name = $(row).closest('tr').find('td #add_godown_id option:selected').text();
        var godown_id = $(row).closest('tr').find('td #add_godown_id').val();
        var qty_product_value =  $(row).closest('tr').find('td #qty_product_value').val();
        var price_as_product =  $(row).closest('tr').find('td #price_as_product').val();
        var subtotal_on_product = price_as_product * qty_product_value
        var uniqueNumber = Math.floor(Math.random() * 125632);
        var itemId = 'minusItem'+uniqueNumber;
        
        htmlData += "<tr class='item' id='"+itemId+"'>"
        htmlData += "<td  style='display:none'><input type='hidden' name='item_id[]' value='"+item_id+"'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + item_name + "</td>"
        htmlData += "<td  style='display:none'><input type='hidden' name='godown_id[]' value='"+godown_id+"'/> <input type='hidden' name='page_name[]' value='1'/></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + godown_name + "</td>"
        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center;background: lightgray;' readonly  type ='text' name='qty[]' value='"+qty_product_value+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='price[]' value='"+price_as_product+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
        htmlData += "<a class='btn btn-sm btn-danger productDelete' data-id='"+uniqueNumber+"'><i class='fa fa-trash'></i></a>"
        htmlData += "</td>"
        htmlData += "</tr>";
        $('#myTable tbody').append(htmlData);
    
        // currentData();
        clearOldData(row);
    }
    
    $(document).on("click", '.productDelete', function(){
        let id = $(this).data('id');
        $('#myTable tbody #minusItem'+id).remove();
    })

    function clearOldData(row){
        $(row).closest('tr').find('td #item_name').val('').trigger('change');
        $(row).closest('tr').find('td #add_godown_id').val('').trigger('change');
        $(row).closest('tr').find('td #price_as_product').val('');
        $(row).closest('tr').find('td #qty_product_value').val('');
        $(row).closest('tr').find('td #subtotal_on_qty').text('');
    }

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
    function delete_add_sear_data(id_row){

        $.ajax({
                type:"GET",
                dataType: "json",
                url:"{{url('/adjustment_product_delete_fild_from_add/-')}}"+id_row,

                success:function(response){

                    $.each(response, function(key, value){
                        cleardata();
                        
                    })



                }
            })
        newProduct_add();

    }


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

    function maines_add_qty_product_search(row){
        var price_as_product =  $(row).closest('tr').find('#price_as_product').val();
        var qty_product =  $(row).val();
        var subtotal = parseInt(price_as_product) * parseInt(qty_product);
        $(row).closest('tr').find('#subtotal_on_qty').html(subtotal.toFixed(2));
    }

    
    function maines_qty_product(){
        var sales_price_tow = $('#price_as_product_tow').val();
        var Tow_qty_product_value = $('#Tow_qty_product_value').val();
        var Subtotal = sales_price_tow * Tow_qty_product_value
        $('#Two_subtotal_on_qty').html(Subtotal);
    }


    function maines_addondemoproduct(row){
        let htmlData = '';
        var item_id = $(row).closest('tr').find('td #item_name').val();
        var item_name = $(row).closest('tr').find('td #item_name option:selected').text();
        var godown_name = $(row).closest('tr').find('td #add_godown_id option:selected').text();
        var godown_id = $(row).closest('tr').find('td #add_godown_id').val();
        var price_as_product =  $(row).closest('tr').find('td #price_as_product').val();
        var qty_product_value =  $(row).closest('tr').find('td #qty_product_value').val();
        var subtotal_on_product = price_as_product * qty_product_value
        var uniqueNumber = Math.floor(Math.random() * 125632);
        var itemId = 'minusItem'+uniqueNumber;
        htmlData += "<tr class='item' id='"+itemId+"'>"
        htmlData += "<td  style='display:none'><input type='hidden' name='item_id[]' value='"+item_id+"'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + item_name + "</td>"
        htmlData += "<td  style='display:none'><input type='hidden' name='godown_id[]' value='"+godown_id+"'/><input type='hidden' name='page_name[]' value='2'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + godown_name + "</td>"
        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center;background: lightgray;' readonly  type ='text' name='qty[]' value='"+qty_product_value+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='price[]' value='"+price_as_product+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
        htmlData += "<a class='btn btn-sm btn-danger minusProductDelete' data-id='"+uniqueNumber+"'><i class='fa fa-trash'></i></a>"
        htmlData += "</td>"
        htmlData +="</tr>";
        $('#myTable_2 tbody').append(htmlData);
    
        clearOldData(row);
    }
    
    $(document).on('click', '.minusProductDelete', function(){
        let id = $(this).data("id");
        $('#myTable_2 tbody #minusItem'+id).remove();
    });


    function SaveAllData(){
        var date = $('#date').val();
        var adjustmen_vo_id = $('#adjustmen_vo_id').val();
        var refer_id = $('#refer_id').val();

        $.ajax({
            type:"GET",
            dataType:"json",
            url:"{{url('/SaveAllData_adjusment/store/')}}",
            data: {
                date:date,
                adjustmen_vo_id:adjustmen_vo_id,
                refer_id:refer_id,
                "_token": "{{ csrf_token() }}",
            },

                success:function(response){

                    $(document).ready(function () {
                        setTimeout(function ()
                        {window.location.href = "{{ route('stock_adjustment_addlist')}}";}, 3000);

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
                                  title: 'Stock Adjustment is Added Successfully',

                                })
                    //----end sweet alert------------------
                },
        })
    }

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

@endpush
