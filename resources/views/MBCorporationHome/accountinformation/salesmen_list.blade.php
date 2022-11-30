@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Sale Man List")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">All List Of Sale Man</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{route('selasman_create')}}" class="btn btn-success">Add New</a>
                    </div>
                    
                    @if(Session::has('message'))
                        <p class="alert alert-info">{{ Session::get('message') }}</p>
                    @endif

                    <table class="table table-resposive table-bordered" id="example">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th>#Sale Man ID</th>
                                <th>Sale Man Name</th>
                                <th>Phone Number</th>
                                <th>E-mail</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($SaleMan_list as $row)
                            <tr>
                                <td>{{$row->salesman_id}}</td>
                                <td>{{$row->salesman_name}}</td>
                                <td>{{$row->phone}}</td>
                                <td>{{$row->email}}</td>
                                <td>{{$row->address}}</td>
                                <td>
                                    <a href="{{URL::to('/edit_SaleMan/'.$row->salesman_id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <a href="javascript:void(0)" data-id="{{$row->salesman_id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
        var url = "{{url('/delete_SaleMan')}}"+ '/' +$(this).data('id');
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
