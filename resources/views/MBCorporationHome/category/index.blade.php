@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; ">category Information</h4>
	</div>
</div>


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-9">
              <div class="card">
              	<div class="card-body">
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All category Manage</h4>
                      @if(session()->has('mes'))
                        <div class="alert alert-success">
                            {{ session()->get('mes') }}
                        </div>
                    @endif
              		<table class="table table-resposive table-bordered" id="example">
                    	<thead style="background-color: #566573;text-align: center;">
                    		<th style="color: #fff;"># SL</th>
                    		<th style="color: #fff;">category Name</th>

                    		<th style="color: #fff;">Action</th>
                    	</thead>
                    	<tbody>

                        @foreach($category as $row)
                    		<tr style="text-align: center;">
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

            <div class="col-md-3">
              <div class="card">
                    @if(request()->has('update') && request()->input('update'))
                        <div class="card-body">
                  			<div style="text-align: center;">
    
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
                  					<h4 class="card-title" 
                  					    style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee; background-color: #DC7633; padding: 5px; color: #fff;">
                  					    Update category</h4><br>
    	              				<input class="form-control" type="text" name="name" value="{{$item->name}}" placeholder="category Name">
    	              				<br>
    	              				<br>
    	              				<button type="submit" class="btn btn-success" style="color: #fff;">Save Category</button>
    	              				<a href="{{route('category')}}" class="btn btn-outline-danger">Cancel</a>
                  				</form>
                  			</div>
                  		</div>
                    @else
              		<div class="card-body">
              			<div style="text-align: center;">

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
              					<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee; background-color: #DC7633; padding: 5px; color: #fff;">Add category</h4><br>

	              				<input class="form-control" type="text" name="name" placeholder="category Name">
	              				<br>
	              				<br>
	              				<button class="btn btn-success" style="color: #fff;">Add category</button>
              				</form>

              			</div>
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
