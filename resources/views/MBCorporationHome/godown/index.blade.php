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
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Godwn</h4>
              		<a href="{{route('godown_create_from')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a><br><br>

<p style="height:40px;color: green;font-size:18px;text-align: center;padding: 5px;">{{$mes}}</p>
              		<table class="table table-resposive table-bordered" id="example">
                    	<thead style="background-color: #566573;text-align: center;">

                            <th style="color: #fff;"># Id.No</th>
                    		<th style="color: #fff;">Godwn Name</th>
                    		<th style="color: #fff;">Godwn Id</th>
                            <th style="color: #fff;">Description</th>
                    		<th style="color: #fff;">Action</th>

                    	</thead>
                    	<tbody>

                            @foreach($godowns  as $row)

                    		<tr style="text-align: center;">

                                <td>{{$row->id}}</td>
                    			<td>{{$row->name}}</td>
                    			<td>{{$row->godown_id}}</td>
                    			<td>{{$row->description??' '}}</td>
                    			<td>
                                    <a href="{{ URL::to('/edit_godown/'.$row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                        			<a href="#" data-id="{{$row->id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

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

