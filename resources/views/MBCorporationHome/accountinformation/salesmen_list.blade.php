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
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of SaleMan </h4>
              		<a href="{{route('selasman_create')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a>
                    <br>
                    <br>
                    @if(Session::has('message'))
                    <p class="alert alert-info">{{ Session::get('message') }}</p>
                    @endif
                    <br>

              		<table class="table table-resposive table-bordered" id="example">
                    	<thead style="background-color: #566573;text-align: center;">
                    		<th style="color: #fff;"># SaleMan ID</th>
                    		<th style="color: #fff;">SaleMan Name</th>
                    		<th style="color: #fff;">Phone Number</th>
                    		<th style="color: #fff;">E-mail</th>
                    		<th style="color: #fff;">Address</th>
                    		<th style="color: #fff;">Action</th>

                    	</thead>
                    	<tbody>
                            @foreach($SaleMan_list as $row)
                    		<tr style="text-align: center;">
                    			<td>{{$row->salesman_id}}</td>
                    			<td>{{$row->salesman_name}}</td>
                    			<td>{{$row->phone}}</td>
                    			<td>{{$row->email}}</td>
                    			<td>{{$row->address}}</td>
                    			<td>
                                    <a href="{{URL::to('/edit_SaleMan/'.$row->salesman_id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <a href="#" data-id="{{$row->salesman_id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
        // console.log();
        var here = $(this);
        console.log(here);
    var url = "{{url('/delete_SaleMan')}}"+ '/' +$(this).data('id');
    // console.log(url);
    //
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
