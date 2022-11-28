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
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Sales Order</h4>
              		<a href="{{route('sales_order_addlist_form')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a><br><br>

                        
              		<table class="table table-resposive table-bordered" id="example">
                    	<thead style="background-color: #566573;text-align: center;">

                            <th style="color: #fff;"># Vo.No</th>
                    		<th style="color: #fff;">Account Lager</th>
                            <th style="color: #fff;">Item Details</th>
                            <th style="color: #fff;">Total Qty</th>
                            <th style="color: #fff;">Total Price
                            <th style="color: #fff;">Delivered To
                            <th style="color: #fff;">Status</th>
                    		<th style="color: #fff;">Action</th>

                    	</thead>
                    	<tbody>


                        @foreach($PurchasesAddList as $PurchasesAddList_row)
                    		<tr style="text-align: center;">
                                <td>{{$PurchasesAddList_row->product_id_list}}</td>
                                <td>{{ optional($PurchasesAddList_row->ledgers)->account_name??'-'}}</td>
                                </td>
                                <td>
                                    @php
                                        $qty=0;
                                        $total_price = 0;
                                        $subtotal_price= 0;
                                        $item_detais=App\DemoProductAddOnVoucher::where("product_id_list",$PurchasesAddList_row->product_id_list)->get();
                                        foreach ($item_detais as $item_detais_rowss) {
                                          $qty=$qty+$item_detais_rowss->qty;
                                          $subtotal_price=$subtotal_price+$item_detais_rowss->subtotal_on_product;
                                        }
                                        $total_price = ( $subtotal_price + $PurchasesAddList_row->other_bill + $PurchasesAddList_row->pre_amount ) - $PurchasesAddList_row->discount_total;
                                    @endphp
                                    @foreach($item_detais as $item_detais_row)
                                    {{ optional($item_detais_row->item)->name??' '}}<br>
                                    @endforeach
                                </td>
                                <td>
                                   {{$qty}}
                                </td>
                                
                                <td>
                                     {{$total_price}}
                                </td>
                                <td>{{$PurchasesAddList_row->delivered_to_details}}</td>
                                <td>{{$PurchasesAddList_row->order_status}}</td>
                    			<td>
                                    <!-- <a href="{{URL::to('/edit_sales_order/'.$PurchasesAddList_row->product_id_list)}}" class="btn btn-sm btn-success" style="color: #fff;"><i class="far fa-eye"></i></a> -->
                                    <a href="{{URL::to('/view_sales_order/'.$PurchasesAddList_row->id)}}"
                                                class="btn btn-sm btn-success" style="color: #fff;"><i
                                                    class="far fa-eye"></i></a>

                                    <a href="{{URL::to('/edit_sales_order/'.$PurchasesAddList_row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>

                        			<!-- <a href="{{URL::to('/delete_sales_order/'.$PurchasesAddList_row->product_id_list)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a> -->

                                    <a href="#" data-id="{{$PurchasesAddList_row->product_id_list}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    
                                     <a href="{{URL::to('/sales_order_approved/'.$PurchasesAddList_row->id.'/1')}}"
                                                class="btn btn-sm btn-{{$PurchasesAddList_row->md_signature==1?"success":"warning"}}" style="color: #fff;">Approve</a
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
    var url = "{{url('/delete_sales_order')}}"+ '/' +$(this).data('id');

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