@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Category Info')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-8 col-sm-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">All Category Manage</h4>
            </div>
            <div class="card-body">
                @if(session()->has('mes'))
                    <div class="alert alert-success">
                        {{ session()->get('mes') }}
                    </div>
                @endif
                <table class="table table-resposive table-bordered" id="example">
                    <thead class="bg-light text-dark">
                        <th>#SL</th>
                        <th>Category Name</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($category as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->name}}</td>
                                <td>
                                    <a href="{{route('category', ['update'=>true,'id'=>$row->id])}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <a href="#" data-id="{{$row->id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
            @if(request()->has('update') && request()->input('update'))
                <div class="card-header bg-success">
                    <h4 class="card-title">Update Category</h4>
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
                    <form action="{{route('update_category', ['id'=>$item->id])}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" value="{{$item->name}}" placeholder="category Name"/>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Save Category</button>
                            <a href="{{route('category')}}" class="btn btn-outline-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            @else
            <div class="card-header bg-success">
                <h4 class="card-title">Add Category</h4>
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
                <form action="{{url('/store_category')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder="category Name"/>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Add category</button>
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
    var url = "{{url('/delete_category')}}"+ '/' +$(this).data('id');

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
