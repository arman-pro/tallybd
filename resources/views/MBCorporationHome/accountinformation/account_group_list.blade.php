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
                        style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of
                        Account Group</h4>
                    <a href="{{route('account_group_create')}}" class="btn btn-success"
                        style="color:#fff; float: right;">+Add New</a>
                    <br>
                    <br>
                    <br>

                    <table class="table table-resposive table-bordered" id="example">
                        <thead style="background-color: #566573;text-align: center;">
                            <th style="color: #fff;"># S.No</th>
                            <th style="color: #fff;">Account Group Name</th>
                            <th style="color: #fff;">Nature</th>
                            <th style="color: #fff;">Group Under</th>
                            <th style="color: #fff;">Created By</th>
                            <th style="color: #fff;">Action</th>
                        </thead>
                        <tbody>
                            {{-- @dd($account_group_list); --}}
                            @foreach($account_group_list as $row)

                            <tr style="text-align: center;">
                                <td>{{$row->id}}</td>
                                <td>{{$row->account_group_name}}</td>
                                <td>{{$row->account_group_nature}}</td>
                                <td>{{ optional($row->groupUnder)->account_group_name??'-'}}</td>
                                <td>{{ optional($row->createdBy)->name??'-'}}</td>
                                

                                <td>
                                    <div class="dropdown">
                                <button class="dropbtn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
                                <div class="dropdown-content">
                                    <a href="{{URL::to('/edit_account_group/'.$row->account_group_id)}}"
                                        class="btn btn-sm btn-success" style="color: #fff;"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{URL::to('/edit_account_group/'.$row->account_group_id)}}"
                                        class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <a href="#" data-id="{{$row->account_group_id}}" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"></i></a>

                                    {{-- <a href="{{URL::to('/delete_account_group/'.$row->account_group_id)}}"
                                        onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"></i></a> --}}
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
