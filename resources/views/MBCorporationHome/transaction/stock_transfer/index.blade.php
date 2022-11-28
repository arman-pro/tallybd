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
                        style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Stock
                        Transfer</h4>
                    <a href="{{route('stock_transfer_addlist_form')}}" class="btn btn-success"
                        style="color:#fff; float: right;">+Add New</a><br><br>

                    <table class="table table-resposive table-bordered" id="example">
                        <thead style="background-color: #566573;text-align: center;">

                            <th style="color: #fff;"># Vch.No</th>
                            <th style="color: #fff;">Date</th>
                            <th style="color: #fff;">Location (From)</th>
                            <th style="color: #fff;">Location (To)</th>
                            <th style="color: #fff;">Item Details</th>
                            <th style="color: #fff;">Action</th>

                        </thead>
                        <tbody>
                            @foreach($stockTransfer as $Stocktransfer_row)
                            <tr style="text-align: center;">
                                <td>{{$Stocktransfer_row->product_id_list}}</td>
                                <td>{{$Stocktransfer_row->date}}</td>
                                <td>{{ optional($Stocktransfer_row->locationForm)->name??" "}}</td>
                                <td>{{ optional($Stocktransfer_row->locationTo)->name??" "}}</td>
                                @php

                                $demop = App\DemoProductAddOnVoucher::where('product_id_list',$Stocktransfer_row->product_id_list)->get();

                                @endphp
                                <td>
                                    @foreach($demop as $demop_row)
                                    {{ optional($demop_row->item)->name??' '}} {{"-"}} {{ $demop_row->qty}} {{"P"}}<br>
                                    @endforeach
                                </td>
                                <td>

                                    <div class="dropdown">
                                        <button class="dropbtn"><i class="fa fa-ellipsis-v"
                                                aria-hidden="true"></i></button>
                                        <div class="dropdown-content">

                                            <a href="{{URL::to('/edit_stocktransfer/'.$Stocktransfer_row->id)}}"
                                                class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                            {{-- <a href="{{URL::to('/delete_stocktransfer/'.$Stocktransfer_row->product_id_list)}}"
                                                onclick="alert('Do You want to delete?')"
                                                class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a> --}}
                        			        <a href="#" data-id="{{$Stocktransfer_row->product_id_list}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

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
            var url = "{{url('/delete_stocktransfer')}}"+ '/' +$(this).data('id');

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
