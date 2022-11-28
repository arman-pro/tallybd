@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"
                        style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Sales
                        Return</h4>
                    <a href="{{ route('salesreturn_addlist_form') }}" class="btn btn-success"
                        style="color:#fff; float: right;">+Add New</a><br><br>


                    <p style="height:40px;color: #fff;font-size:18px;text-align: center;padding: 5px;"></p>
                    <table class="table table-resposive table-bordered" id="example">
                        <thead style="background-color: #566573;text-align: center;">
                            <tr>
                                <th style="color: #fff;">Date</th>
                                <th style="color: #fff;"># Vch.No</th>
                                <th style="color: #fff;">Account Lager</th>
                                <th style="color: #fff;">Item Details</th>
                                <th style="color: #fff;">Total Qty</th>
                                <th style="color: #fff;">Price</th>
                                <th style="color: #fff;">Total Price</th>
                                <th style="color: #fff;">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($PurchasesAddList as $purchasesAddList_row)
                            <tr style="text-align: center;">
                                <td>{{ date('d-m-Y', strtotime($purchasesAddList_row->date)) }} </td>
                                <td>{{ $purchasesAddList_row->product_id_list }}</td>
                                <td>
                                    {{ optional($purchasesAddList_row->ledger)->account_name ?? '-' }}</td>
                                <td>
                                    @php
                                    $qty = 0;
                                    $total_price = 0;
                                    $subtotal_price = 0;
                                    $item_detais = App\DemoProductAddOnVoucher::where('product_id_list',
                                    $purchasesAddList_row->product_id_list)
                                    ->with('item')
                                    ->get();

                                    foreach ($item_detais as $item_detais_rowss) {
                                        $qty = $qty + $item_detais_rowss->qty;
                                        $subtotal_price = $subtotal_price + $item_detais_rowss->subtotal_on_product;
                                    }
                                    $total_price = $subtotal_price + $purchasesAddList_row->other_bill -
                                    $purchasesAddList_row->discount_total;

                                    @endphp
                                    @foreach ($item_detais as $item_detais_row)
                                    {{ optional($item_detais_row->item)->name ?? ' ' }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item_detais as $item_detais_row)
                                    {{ new_number_format($item_detais_row->qty ?? 0) }} <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item_detais as $row)
                                    {{ new_number_format($row->price ?? 0) }} TK <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item_detais as $row)
                                    {{ new_number_format($row->price * $row->qty) }} Tk <br>
                                    @endforeach
                                </td>
                                <td>

                                    <div class="dropdown">
                                        <button class="dropbtn"><i class="fa fa-ellipsis-v"
                                                aria-hidden="true"></i></button>
                                        <div class="dropdown-content">
                                            <a href="{{ URL::to('/edit_sales_return/' . $purchasesAddList_row->id) }}"
                                                class="btn btn-sm btn-success" style="color: #fff;"><i
                                                    class="far fa-eye"></i></a>
                                            <a href="{{ URL::to('/edit_sales_return/' . $purchasesAddList_row->id) }}"
                                                class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                            <a href="{{ URL::to('/send_sales_return_sms/' . $purchasesAddList_row->id) }}"
                                                onclick="alert('Do You want to send sms?')"
                                                class="btn btn-sm btn-warning"><i class="far fa-envelope"></i></a>

                                            <!--<a href="{{ URL::to('/delete_sales_return/' . $purchasesAddList_row->product_id_list) }}"-->
                                            <!--    onclick="alert('Do You want to delete?')"-->
                                            <!--    class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>-->
                                            <a href="#" data-id="{{$purchasesAddList_row->product_id_list}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                            <a target="_blank"
                                                href="{{ URL::to('/print_sales_return_invoice/' . $purchasesAddList_row->product_id_list) }}"
                                                class="btn btn-sm btn-info" style="color: #fff;"><i
                                                    class="fas fa-print"></i></a>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>

        </div>
 @endsection
@push('js')
<script>
    $('a.btn-danger').on('click', function(){
    var here = $(this);
    //console.log(here);
    var url = "{{url('/delete_sales_return')}}"+ '/' +$(this).data('id');

    $.confirm({
            icon: 'fa fa-spinner fa-spin',
            title: 'Delete this?',
            theme: 'material',
            type: 'orange',
            closeIcon: true,
            animation: 'scale',
            content: 'This dialog will automatically trigger \'cancel\' in 6 seconds if you don\'t respond.',
            autoClose: 'cancelAction|8000',
            buttons: {
                deleteUser: {
                    text: 'delete data',
                    action: function () {
                        $.get(url, function(data){
                            if(data.status == true){
                                here.closest('tr').remove();
                            }
                            $.alert(data.mes);

                        });
                    }
                },
                cancelAction: function () {
                    $.alert('This action is canceled.');
                }
            }
        });
    });
</script>
@endpush