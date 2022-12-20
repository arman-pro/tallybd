@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Account Group List')
@section('admin_content')

<div class="container-fluid">
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success">
            <h4 class="card-title">All List Of Account Group</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{route('account_group_create')}}" class="btn btn-success" >Add New</a>
            </div>
            <table class="table table-resposive table-bordered" id="example">
                <thead class="bg-light text-dark">
                    <tr>
                        <th># SL.No</th>
                        <th>Account Group Name</th>
                        <th>Nature</th>
                        <th>Group Under</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($account_group_list as $row)
                    <tr>
                        <td>{{$row->id}}</td>
                        <td>{{$row->account_group_name}}</td>
                        <td>{{$row->account_group_nature}}</td>
                        <td>{{ optional($row->groupUnder)->account_group_name??'-'}}</td>
                        <td>{{ optional($row->createdBy)->name??'-'}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="dropbtn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
                                <div class="dropdown-content">
                                    <a href="{{URL::to('/edit_account_group/'.$row->account_group_id)}}" class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{URL::to('/edit_account_group/'.$row->account_group_id)}}" class="btn btn-sm btn-primary">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" data-id="{{$row->account_group_id}}" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
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
</div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function(){
        $('#example').DataTable({});
    })
    $('a.btn-danger').on('click', function(){

    var here = $(this);
    var url = "{{url('/delete_account_group')}}"+ '/' +$(this).data('id');

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
