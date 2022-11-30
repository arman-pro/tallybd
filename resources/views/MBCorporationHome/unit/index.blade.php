@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Unit Information')
@section('admin_content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">All Unit Manage</h4>
                </div>
                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="alert alert-success">{{session()->get('message')}}</div>
                    @endif
                    <table class="table table-resposive table-bordered" id="example">
                        <thead class="bg-light text-dark">
                            <th># SL</th>
                            <th>Unit Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($Unit as $row)
                                <tr>
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->name}}</td>
                                    <td>
                                        <a href="{{route('unit_list').'?update=true&id='.$row->id}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                        <a href="javascript:void(0)" data-id="{{$row->id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="card">
                @if(request()->has('update'))
                <div class="card-header bg-success">
                    <h4 class="card-title">Update Unit</h4>
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
                    
                    <form action="{{route('unit_update', ['id'=> $item->id])}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" value="{{$item->name}}" placeholder="Catagory Name"/>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Save Unit</button>
                            <a href="{{route('unit_list')}}" class="btn btn-outline-danger">Cancel</a>
                        </div>
                    </form>
                </div>
                @else
                <div class="card-header bg-success">
                    <h4 class="card-title">Add Unit</h4>
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
                    <form action="{{url('/store_unit')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" placeholder="Catagory Name"/>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Add Unit</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    <div>
</div>
@endsection

@push('js')
<script>
    $('a.btn-danger').on('click', function(){
    var here = $(this);
    var url = "{{url('/delete_unit')}}"+ '/' +$(this).data('id');
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
