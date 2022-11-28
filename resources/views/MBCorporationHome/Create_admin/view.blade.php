@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<br>
<br>
<br>


<div class="modal fade" id="myModal" role="dialog" >
  <div class="modal-dialog modal-lg" >
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Data</h4>
      </div>
      <div class="modal-body" >
        <p>
                

        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>



<div class="main-content" style="overflow: hidden;">
            <!--page title start-->
            <div class="page-title">
                <h4 class="mb-0">View Admin
                    <small></small>
                </h4>
                <ol class="breadcrumb mb-0 pl-0 pt-1 pb-0">
                    <li class="breadcrumb-item"><a href="#" class="default-color">Home</a></li>
                    <li class="breadcrumb-item active">View Admin</li>
                </ol>
            </div>
            <!--page title end-->


            <div class="container-fluid" style="overflow-x: scroll;">

                <!-- state start-->
                <div class="row">
                    <div class=" col-sm-12">
                        <div class="mb-4">
                   
                            <div class="card-body">
                                <table id="example" class="display nowrap" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                        @if(Auth()->user()->id == '1')
                                    	@if(isset($data))
                                    	@foreach($data as $showdata)

                                        <tr id="tr-{{$showdata->id}}">
                                        <td>{{$showdata->name}}</td>
                                        <td>{{$showdata->email}}</td>
                                        <td>{{$showdata->phone}}</td>
                                        <td>{{$showdata->address}}</td>
                                        <td><img src="{{asset('/AdminImage')}}/{{$showdata->image}}" style="width: 50px;height: 50px"></td>
                                        <td>
                                        	@if($showdata->status == '1')
                                        	<span style="color: green;font-family: monospace;">Active</span>
                                        	@else
                                        	 	<span style="color: orange;font-family: monospace;">Inactive</span>
                                        	@endif

                                        </td>
                                        <td>
                                        	@if($showdata->status == '1')
                                        	<a href="#" class="btn btn-danger" onclick="inactivestatus('{{$showdata->id}}')">Inactive</a>
                                        	@else
                                        	<a href="#" class="btn btn-success"  onclick="activestatus('{{$showdata->id}}')">Active</a>
                                        	@endif

                                        	<a href="{{url('/editadminModal')}}/{{$showdata->id}}"
                                class="btn btn-primary btn-mini" >Update</a>
                                        	<a href="#" class="btn btn-danger" onclick="deleteaccount('{{$showdata->id}}')">Delete</a>
                                        </td>
                                       
                                    	</tr>
                                    	
                                    	@endforeach
                                    	@endif

                                        @else

                                        @if(isset($data))
                                        @foreach($data as $showdata)
                                        @if($showdata->id != '1')
                                        <tr id="tr-{{$showdata->id}}">
                                        <td>{{$showdata->name}}</td>
                                        <td>{{$showdata->email}}</td>
                                        <td>{{$showdata->phone}}</td>
                                        <td>{{$showdata->address}}</td>
                                        <td><img src="{{asset('public/AdminImage')}}/{{$showdata->image}}" style="width: 50px;height: 50px"></td>
                                        <td>
                                            @if($showdata->status == '1')
                                            <span style="color: green;font-family: monospace;">Active</span>
                                            @else
                                                <span style="color: orange;font-family: monospace;">Inactive</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if($showdata->status == '1')
                                            <a href="#" class="btn btn-danger" onclick="inactivestatus('{{$showdata->id}}')">Inactive</a>
                                            @else
                                            <a href="#" class="btn btn-success"  onclick="activestatus('{{$showdata->id}}')">Active</a>
                                            @endif

                                            <a href="{{url('/editadminModal')}}/{{$showdata->id}}"
                                class="btn btn-primary btn-mini" >Update</a>
                                            <a href="#" class="btn btn-danger" onclick="deleteaccount('{{$showdata->id}}')">Delete</a>
                                        </td>
                                       
                                        </tr>
                                        
                                        @endif
                                        @endforeach
                                        @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- state end-->

            </div>
        </div>


<script type="text/javascript">
	function loadModel(id)
{
  $(".modal-body").load("{{URL::to('editadminModal')}}"+'/'+id);
}

function inactivestatus(id)
{

      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: '{{ url("inactive-status-admin") }}',
        type: 'POST',
        data: {id:id},
        success: function(data){
		location.reload(); 
        }
      });
      

}

function activestatus(id)
{

      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: '{{ url("active-status-admin") }}',
        type: 'POST',
        data: {id:id},
        success: function(data){
		location.reload(); 
        }
      });
      

}
function deleteaccount(id)
{

if(confirm('Are you sure?'))
{
      $.ajax({
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        url: '{{ url("delete-account-admin") }}',
        type: 'POST',
        data: {id:id},
        success: function(data){
		$("#tr-"+id).hide(); 
        }
      }); 
}
   
      

}
</script>
@endsection