@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Godown List')
@section('admin_content')

<div class="container-fluid">
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">All List Of Godown</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{route('godown_create_from')}}" class="btn btn-success">Add New</a>
                </div>
                <table class="table table-resposive table-bordered" id="example">
                    <thead class="bg-light text-dark">
                        <th>#Id.No</th>
                        <th>Godwn Name</th>
                        <th>Godwn Id</th>
                        <th>Description</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($godowns  as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->godown_id}}</td>
                            <td>{{$row->description ?? 'N/A'}}</td>
                            <td>
                                <a href="{{ URL::to('/edit_godown/'.$row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                <a href="javascript:void(0)" data-id="{{$row->id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script>
    $('a.btn-danger').on('click', function(){
        var here = $(this);
        var url = "{{url('/delete_godown')}}"+ '/' +$(this).data('id');

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

