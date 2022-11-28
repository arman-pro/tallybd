@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; "> Stock Transfer</h4>
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
                <form action="{{URL::to('/Update/stock_transfer/'.$stockTransfer->id)}}" method="POST">
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
                    {{-- @foreach($Stocktransfer as $stockTransfer) --}}

                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                            Update Stock Transfer</h4>
                        <input type="hidden" name="page_name" value="stock_transfer" id="page_name">
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
                                                        style="font-size: 12px;" value="{{ $stockTransfer->date }}" required />
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Vo. No
                                                    :</div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="product_id_list"
                                                        id="product_id_list"
                                                        value="{{($stockTransfer->product_id_list)}}"
                                                        style="text-align: center;font-size: 12px;" readonly>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">
                                                    Reference No :</div>
                                                <div class="col-md-8">
                                                    <input type="text" name="reference_txt" id="reference_txt"
                                                        class="form-control" style="font-size: 12px;"
                                                        value="{{$stockTransfer->reference_txt}}">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Location (From) :</label>
                                    <div>
                                        <select class="form-control" style="text-align: center;" name="location_form"
                                            id="location_form" required>

                                            @foreach($godown as $godwn_row)
                                            <option value="{{$godwn_row->id}}" {{ $stockTransfer->location_form ==
                                                $godwn_row->id?'Selected':' '}}>
                                                {{$godwn_row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- @dd($stockTransfer); --}}
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Location (To) :</label>
                                    <div>
                                        <select class="form-control" style="text-align: center;" name="location_to"
                                            id="location_to" required >
                                            @foreach($godown as $godwn_row)
                                            <option value="{{$godwn_row->id}}" {{ $stockTransfer->location_to ==
                                                $godwn_row->id?'Selected':
                                                ' '}}>{{$godwn_row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>

                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;text-align: center;" id="myTable">
                                    <tr style="background-color: #D6DBDF;">
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 300px;">Product
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
                                     <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table class="table"
                                    style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;">
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <select class="form-control" id="item_name" name="item_name"
                                                style="text-align: center;height: 30px;" onclick="Product()">
                                                <option value="">Select</option>
                                                @foreach($Item as $item_row)
                                                <option value="{{$item_row->id}}">{{$item_row->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <input type="text" name="qty_product_value" id="qty_product_value"
                                                class="form-control" style="text-align: center;height: 30px;" value=""
                                                oninput="qty_product()">
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
                                            style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;text-align: right; ">
                                            Item</td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;"
                                            id="total_item">0</td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total
                                            Price</td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                                id="total_sales_price"></span></td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                    </tr>
                                </table>
                            </div>

                            <br>
                            <br>
                            <div style="text-align: center; color: #fff; font-weight: 800;">
                                <button type="submit" class="btn btn-primary"
                                    style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Update</button>
                                <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
        <div>

        </div>
        </form>


@endsection
@push('js')
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
    });

function cleardata(){
    $('#qty_product_value').val('0');
    $('#discount_on_product').val('0');
    $('#price_as_product').val('0');
    $('#item_name').val('');
    $('#subtotal_on_qty').hide();
    $('#subtotal_on_discount').hide();

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
                item = '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" style="text-align: center;height:30px;" value="'+item_price+'">'

            })


            $('#sales_price').html(item);
            $('#subtotal').html(item_price);




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
    var pre_amount = $('#pre_amount').val();

    var Subtotal = (price_as_product * qty_product)


    var product_id_list = $('#product_id_list').val();

    $('#subtotal_on_qty').html(Subtotal);


    // $.ajax({
    //     type:"GET",
    //     dataType: "json",
    //     url:"{{url('/product_new_fild/-')}}"+product_id_list,

    //     success:function(response){
    //         var total_product_price = ""
    //         var all_total_product_price =""
    //         var all_total_product_price_as_pr_amount =""
    //         var Total_item =""
    //         $.each(response, function(key, value){
    //             total_product_price = Number(total_product_price) + Number(value.subtotal_on_product)
    //             Total_item = Number(Total_item)+ Number(value.qty)
    //         });
    //         qty_product = Number(Total_item)+ Number(qty_product)
    //         all_total_product_price = Number(total_product_price) + Number(Subtotal)
    //         $('#total_sales_price').html(all_total_product_price);
    //         $('#total_item').html(qty_product);
    //         $('#all_subtotal_amount').html(all_total_product_price);
    //         all_total_product_price_as_pr_amount = Number(all_total_product_price) + Number(pre_amount)
    //         $('#total_amount').html(all_total_product_price_as_pr_amount);
    //     }
    // })

}


function addondemoproduct(){
    htmlData ='';
    var date = $('#date').val();
    var product_id_list = $('#product_id_list').val();
    var page_name = $('#page_name').val();
    var item_id = $('#item_name').val();
    var item_name = $('#item_name option:selected').text();
    var qty_product_value = $('#qty_product_value').val();
    var price_as_product = $('#price_as_product').val();
    var subtotal_on_product = price_as_product * qty_product_value;
    htmlData += "<tr class='item'>"
    htmlData += "<td  style='display:none'><input type='hidden' name='new_item_id[]' value='"+item_id+"'/> </td>"
    htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + item_name + "</td>"
    htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center' readonly  type ='text' name='new_qty[]' value='"+qty_product_value+"' /></td>"
    htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='new_price[]' value='"+price_as_product+"' /> </td>"
    htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='new_subtotal[]' value='"+subtotal_on_product.toFixed(2)+"' /> </td>"
    htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
    htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
    htmlData += "</td>"
    htmlData +="</tr>";
    $('#myTable tbody tr:last').after(htmlData)
    currentData();
    clearOldData();
}

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
                    data += "<tr class='item'>"
                    data += "<td  style='display:none'><input type='hidden' name='item_id[]' value='"+value.item.id+"'/> </td>"
                    data += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + value.item.name+ "</td>"
                    data += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='item-qty' style='border:0;text-align:center' readonly  type ='text' name='qty[]' value='"+value.qty+"' /></td>"
                    data += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='text' name='price[]' value='"+value.price+"' /> </td>"
                    data += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'><input  class='item-charge' style='border:0;text-align:center' readonly  type ='text' name='subtotal[]' value='"+value.subtotal_on_product+"' /> </td>"
                    data += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
                    data += "<a class='btn btn-sm btn-danger' href='{{url('/stocktransfer_product_delete_fild_from_add/-')}}"+value.id_row+"' ><i class='fa fa-trash'></i></a>"
                    data += "</td>"
                    data +="</tr>";
                    Total_cost = Number(Total_cost)+ Number(value.subtotal_on_product)
                    Total_item = Number(Total_item)+ Number(value.qty)

                });

                $('#total_item').html(Total_item);
                $('#total_sales_price').html(Total_cost);
                $('#all_subtotal_amount').html(Total_cost);
                $('#myTable tbody tr:last').after(data)


        }
    })


}

newProduct();

function delete_data(id_row){

    (id_row).closest('tr').remove();

    currentData();


}

function currentData(){
        var subTotal = 0;
        var subQty = 0;
        $('.item').each(function() {
            var $this = $(this),
            sum = Number($this.find('.item-charge').val());
            console.log($(this).find('.item-qty').val());
            subQty += Number($(this).find('.item-qty').val());
            discount = 0;
            subTotal += sum;
        });
        console.log(subTotal);
        $('#all_subtotal_amount').html(subTotal.toFixed(2));
        $('#total_sales_price').html(subTotal.toFixed(2));

        $('#total_item').html(subQty.toFixed(2));
    }
    function clearOldData(){
        $('#item_name').val('');
        $('#qty_product_value').val('');
        $('#discount_on_product').val('');
        $('#price_as_product').val('');
        $('#subtotal_on_qty').text('');
    }


</script>
@endpush
