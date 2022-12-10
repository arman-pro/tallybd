@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Stock Transfer')
@section('admin_content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ url('/SaveAllData/stock_transfer/store/') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-header bg-success">
                        <h4 class="card-title">Add Stock Transfer</h4>
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
                                                        style="font-size: 12px;" value="{{ date('Y-m-d') }}" required/>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Vch. No :
                                                </div>
                                                <div class="col-md-8">
                                                    @php
                                                    use App\StockTransfer;

                                                    $product_id_list = App\Helpers\Helper::IDGenerator(new StockTransfer, 'product_id_list', 4, 'STr');

                                                    @endphp
                                                    <input type="text" class="form-control" name="product_id_list"
                                                        id="product_id_list" value="{{$product_id_list}}"
                                                        style="text-align: center;font-size: 12px;" required>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Reference
                                                    No :</div>
                                                <div class="col-md-8">
                                                    <input type="text" name="reference_txt" id="reference_txt"
                                                        class="form-control" style="font-size: 12px;">
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
                                            <option value="{{ null }}">Select</option>
                                            @foreach($godown as $godown_row)
                                            <option value="{{$godown_row->id}}">{{$godown_row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Location (To) :</label>
                                    <div>
                                        <select class="form-control" style="text-align: center;" name="location_to"
                                            id="location_to" required>
                                            <option value="{{ null }}">Select</option>
                                            @foreach($godown as $godown_row)
                                            <option value="{{$godown_row->id}}">{{$godown_row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>

                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;text-align: center;" id="myTable">
                                    <tr style="background-color: #D6DBDF;">
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 300px;">Product</td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Quantity
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 150px;">Price</td>
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
                                            
                                            <select  
                                                onchange="Product()" id="item_name" name="item_name"
                                                class="select2item form-control" style="width: 200px"
                                                data-placeholder="Select a Product"
                                            >
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
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total Price
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                                id="total_sales_price"></span></td>
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
        $('#qty_product_value').val('0');
        $('#discount_on_product').val('0');
        $('#price_as_product').val('0');
        $('#item_name').val('');
        $('#subtotal_on_qty').hide();
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

    function Product(){

        var item_name = $('#item_name').val();

        $.ajax({
            type:"GET",
            dataType: "json",
            url:"{{url('/product_as_price/-')}}"+item_name,

            success:function(response){
                var item;
                var item_price;

                $.each(response, function(key, value){
                    item_price = value.purchases_price;
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


    }


    //----------------------------start store addondemoproduct----------------------------------------

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
        htmlData += "<td  style='display:none'><input type='hidden' name='item_id[]' value='"+item_id+"'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>" + item_name + "</td>"
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

    //

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


    function delete_data(id_row){

        (id_row).closest('tr').remove();

        currentData();


    }
    //----------------------------end Remove addondemoproduct----------------------------------------



</script>

@endpush
