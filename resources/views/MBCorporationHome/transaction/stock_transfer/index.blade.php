@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Stock Transfer")
@section('admin_content')


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card overflow-auto">
            <div class="card-header bg-success">
                <h4 class="card-title">All List Of Stock Transfer</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{route('stock_transfer_addlist_form')}}" class="btn btn-success" >Add New</a>
                </div>

                <table class="table table-resposive table-bordered">
                    <caption>{{$stockTransfer->count()}} of {{$stockTransfer->total()}} Stock List</caption>
                    <thead class="bg-light text-dark">
                        <tr>
                            <th># Vch.No</th>
                            <th>Date</th>
                            <th>Location (From)</th>
                            <th>Location (To)</th>
                            <th>Item Details</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockTransfer as $Stocktransfer_row)
                        <tr>
                            <td>{{$Stocktransfer_row->product_id_list}}</td>
                            <td>{{$Stocktransfer_row->date}}</td>
                            <td>{{ optional($Stocktransfer_row->locationForm)->name??" "}}</td>
                            <td>{{ optional($Stocktransfer_row->locationTo)->name??" "}}</td>
                            <?php
                                $demop = App\DemoProductAddOnVoucher::where('product_id_list',$Stocktransfer_row->product_id_list)->get();
                            ?>
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
                                        <a href="javascrip:void(0)" data-id="{{$Stocktransfer_row->product_id_list}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{$stockTransfer->links()}}
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
