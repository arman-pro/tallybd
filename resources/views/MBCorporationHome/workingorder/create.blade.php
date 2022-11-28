@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('workingOrder.store') }}" method="post">
                    @csrf
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

                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                            Add Working Order</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" name="date" id="date" class="form-control"
                                                        style="font-size: 12px;" value="{{ date('Y-m-d') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Vch. No :
                                                </div>
                                                <div class="col-md-8">
                                                    @php
                                                    use App\WorkingOrder;
                                                    $adjustmen_vo_id = App\Helpers\Helper::IDGenerator(new WorkingOrder,
                                                    'vo_no', 4, 'Wo');
                                                    @endphp
                                                    <input type="text" class="form-control" name="adjustmen_vo_id"
                                                        id="adjustmen_vo_id" value="{{$adjustmen_vo_id}}"
                                                        style="text-align: center;font-size: 12px;" readonly />
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

                                <table class="table" style="border: 1px solid #eee;text-align: center;" id="myTable">

                                    <tr style="background-color: #D6DBDF;">
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 300px;">Product</td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Godown</td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 100px;">Quantity
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 150px;">Pur.Price
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 250px;">Subtotal
                                        </td>
                                        <td style="border-right: 1px solid #fff;padding: 5px 5px;width: 50px;">#</td>
                                    </tr>
                                    <tbody style="background: #F8F9F9;" id="data_add_for_list">
                                        <tr></tr>
                                    </tbody>
                                </table>
                                <table class="table"
                                    style="border: 1px solid #eee;font-size: 12px;text-align: center;background: #eee;">
                                    <tr>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 300px;">
                                            <select onchange="add_Product_search()" id="item_name" name="item_name"
                                                class="select2item" style="width: 200px">
                                            </select>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                                            <select class="form-control" style="height: 30px;font-size: 12px;"
                                                name="add_godown_id" id="add_godown_id">
                                                <option value="">Select</option>
                                                @foreach($Godwn as $godwn_row)
                                                <option value="{{$godwn_row->id}}">{{$godwn_row->name}}</option>
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
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;">Total Price
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;"><span
                                                id="total_add_sales_price"></span></td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                    </tr>
                                </table>

                                <!-- Extra cost -->
                                <table class="table" style="border: 1px solid #eee;text-align: center;">
                                    <thead >
                                        <tr style="background-color: #D6DBDF;">
                                            <td>Title</td>
                                            <td>Qty</td>
                                            <td>Price</td>
                                            <td>Total</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                </table>
                                <table class="table"  id="extracost">
                                    <tr>
                                        <td><input type="text" id="e_title"></td>
                                        <td><input type="text" id="e_qty"></td>
                                        <td><input type="text" id="e_price"></td>
                                        <td><input type="text" id="e_total" readonly=""></td>
                                        <td><a class="btn btn-sm btn-success" onclick="addNewItems()"><i
                                                class="fa fa-plus"></i></a></td>
                                    </tr>
                                </table>
                                <table class="table" style="border: 1px solid #eee;text-align: center;">
                                    <tr style="background-color: #F8F9F9;">
                                        
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 150px;"><strong>Total Amount</strong>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 250px;font-weight: 700"><span
                                                id="totalBalance"></span></td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 50px;"></td>
                                    </tr>
                                </table>

                            </div>



                            <div style="text-align: center; color: #fff; font-weight: 800;">
                                <button type="submit" class="btn btn-success"
                                    style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                                <button type="submit" class="btn btn-info" name="print" value="1"
                                    style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Save & Print</button>
                                <a href="{{route('workingOrder.index')}}" class="btn btn-danger">Cencel</a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>
</div>



@endsection
@push('js')
<script>
    function addNewItems(){
        let htmlDatax = '';
        var e_title = $('#e_title').val();
        var e_qty = $('#e_qty').val();
        var e_price = $('#e_price').val();
        var subtotal  = (e_qty * e_price);

        htmlDatax += "<tr class='itemss'>"
        htmlDatax += "<td><input type='text' name='e_title[]' readonly value='"+e_title+"'/> </td>" 
        htmlDatax += "<td><input type='text' class='eqty' readonly name='e_qty[]' value='"+e_qty+"'/> </td>" 
        htmlDatax += "<td><input type='text' class='eprice' readonly name='e_price[]' value='"+e_price+"'/> </td>" 
        htmlDatax += "<td><input type='text' name='e_total[]' readonly value='"+subtotal.toFixed(2)+"'/> </td>" 
        htmlDatax += "<td><a class='btn btn-sm btn-danger' onclick='delete_extra(this)'><i class='fa fa-trash'></i></a>"
        htmlDatax += "</td>"
        htmlDatax +="</tr>";
        $('#extracost tr:last').after(htmlDatax)
        calculatedata();
    }
    $('#e_price').keyup(function(){
        var e_price =Number($(this).val());
        var e_qty =Number($('#e_qty').val());
        var totalBill =(e_qty*e_price); 
         $('#e_total').val(totalBill.toFixed(2));
    });
    
    $('#e_qty').keyup(function(){
        var e_qty =Number($(this).val());
        var e_price =Number($('#e_price').val());
        var totalBill =(e_qty*e_price); 
         $('#e_total').val(totalBill.toFixed(2));
    });
    
    
    function delete_extra(delelet) {
        (delelet).closest('tr').remove();
        calculatedata();
    }

    function calculatedata(){
        var subTotal = 0;
        $('.itemss').each(function() {
            var $this = $(this),
                eqty = Number($this.find('.eqty').val());
            eprice = Number($(this).find('.eprice').val());
            subTotal += Number(eqty*eprice);
        });

        var all_subtotal_amount = Number($('#total_add_sales_price').text());
        var total = Number(subTotal+all_subtotal_amount);
        $('#totalBalance').html(total.toFixed(2));
    }


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
                var item;
                var item_price;
                $.each(response, function(key, value){
                    item_price = value.purchases_price
                    item = '<input type="show" name="price_as_product" id="price_as_product" oninput="qty_product()" class="form-control" style="text-align: center;height:30px;" value="'+item_price+'">'
                });
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
        let htmlData = '';
        var page_name = 1;
        var date = $('#date').val();
        var item_id = $('#item_name').val();
        var godown_id = $('#add_godown_id').val();
        var item_name = $('#item_name option:selected').text();
        var godown_name = $('#add_godown_id option:selected').text();
        var qty_product_value = $('#qty_product_value').val()||1;
        var price_as_product = $('#price_as_product').val();
        var subtotal_on_product = (price_as_product * qty_product_value);

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
        $('#myTable tr:last').after(htmlData)
        currentData();
        clearOldData();

    }


    function clearOldData(){
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
        $('#total_add_sales_price').html(subTotal.toFixed(2));

        $('#total_add_item').html(subQty.toFixed(2));
    }

//----------------------------end store addondemoproduct----------------------------------------

//----------------------------Start newProduct----------------------------------------
    // function newProduct_add(){

        // var vo_no = $('#adjustmen_vo_id').val();
        // var data_add_for_list = $('#data_add_for_list').val();
        // var data_maines_for_list = $('#data_maines_for_list').val();
        // $.ajax({
        //     type:"GET",
        //     dataType: "json",
        //     url:"{{url('/workingorder/findProductRow?vo_no=')}}"+vo_no,

        //     success:function(response){
        //             var data =""
        //             var tata =""
        //             var Total_cost =""
        //             var Total_item =""
        //             var Total_cost_tow =""
        //             var Total_item_tow =""
        //         $.each(response, function(key, value){
        //             if (value.page_name < 2) {
        //             data = data + "<tr>"
        //             data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>"+value.item.name+"</td>"
        //             data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'>"+value.godown.name+"</td>"
        //             data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.qty+"</td>"
        //             data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.price+"</td>"
        //             data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'>"+value.subtotal_on_product+"</td>"
        //             data = data + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
        //             data = data +"<a class='btn btn-sm btn-danger' onclick='delete_add_sear_data("+value.id_row+")'><i class='fa fa-trash'></i></a>"
        //             data = data+"</td>"
        //             data = data + "</tr>";
        //             Total_cost = Number(Total_cost) + Number(value.subtotal_on_product)
        //             Total_item = Number(Total_item) + Number(value.qty)
        //         }else{
        //             tata = tata + "<tr>"
        //             tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;'>"+value.item.name+"</td>"
        //             tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'>"+value.godown.name+"</td>"
        //             tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.qty+"</td>"
        //             tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'>"+value.price+"</td>"
        //             tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 250px;'>"+value.subtotal_on_product+"</td>"
        //             tata = tata + "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
        //             tata = tata +"<a class='btn btn-sm btn-danger' onclick='delete_add_sear_data("+value.id_row+")'><i class='fa fa-trash'></i></a>"
        //             tata = tata+"</td>"
        //             tata = tata + "</tr>";
        //             Total_cost_tow = Number(Total_cost_tow)+ Number(value.subtotal_on_product)
        //             Total_item_tow = Number(Total_item_tow)+ Number(value.qty)
        //         }

        //         });


        //         $('#total_add_item').html(Total_item);
        //         $('#total_add_sales_price').html(Total_cost);

        //         $('#total_item').html(Total_item_tow);
        //         $('#total_sales_price_two').html(Total_cost_tow);


        //         $('#data_add_for_list').html(data);
        //         $('#data_maines_for_list').html(tata);


        //     }
        // })


    // }

    // newProduct_add();
//----------------------------End newProduct----------------------------------------

//----------------------------start Remove addondemoproduct----------------------------------------
// function delete_add_sear_data(id_row){

//     $.ajax({
//             type:"GET",
//             dataType: "json",
//             url:"{{url('/workingorder/delete_field_from_add/-')}}"+id_row,

//             success:function(response){

//                 $.each(response, function(key, value){
//                       cleardata();
//                     // console.log('561456 hello '+ id_row);
//                 })



//             }
//         })
//     newProduct_add();

// }
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
            url:"{{url('/workingorder/adjustment')}}",
            data: {
                vo_no:adjustmen_vo_id,
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

function SaveAllData(){
        var date = $('#date').val();
        var total_add_sales_price = $('#total_add_sales_price').val();
        var adjustmen_vo_id = $('#adjustmen_vo_id').val();
        var refer_id = $('#refer_id').val();

        $.ajax({
            type:"GET",
            dataType:"json",
            url:"{{route('workingOrder.store')}}",
            data: {
                date:date,
                adjustmen_vo_id:adjustmen_vo_id,
                refer_id:refer_id,
                total:total_add_sales_price,
                "_token": "{{ csrf_token() }}",
            },

                success:function(response){

                    // console.log("Hello data save");

                    $(document).ready(function () {
                        setTimeout(function ()
                        {window.location.href = "{{ route('workingOrder.index')}}";}, 3000);

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
        })
}

// function cleardata(){
//         $('#qty_product_value').val('');
//         $('#discount_on_product').val('');
//         $('#price_as_product').val('');
//         $('#item_name').val('');
//         $('#godown_id_mines').val('');
//         $('#add_godown_id').val('');

//         $('#subtotal_on_qty').hide();

//         $('#Two_subtotal_on_qty').hide();

//         $('#item_name_tow').val('');
//         $('#Tow_qty_product_value').val('');
//         $('#price_as_product_tow').val('');


//     }



    
</script>


@endpush
