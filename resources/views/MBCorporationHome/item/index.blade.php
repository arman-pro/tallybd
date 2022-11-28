@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
              	<div class="card-body"style="overflow-x:auto;">
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Item</h4>
              		<a href="{{route('item_create_from')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a><br><br>

                    <style type="text/css">
                        .source_file_list{
                            height: 35px;
                            float: right;
                            background-color: #99A3A4;

                            padding:5px;
                        }
                        .source_file_list a{
                           padding: 5px 20px;
                            color: #fff;
                            font-size:18px;

                        }
                        .source_file_list a:hover{
                            background-color:#D6DBDF;
                        }
                    </style>
                    <div class="source_file_list">
                        <a href="{{url('/print_all_item')}}">Print</a>
                        <a href="">PDF</a>
                        <a href="">Excal</a>
                    </div>
                    <br>


                    {{-- @if ($mes )
                    <p style="height:40px;color: green;font-size:18px;text-align: center;padding: 5px;">{{$mes}}</p>

                    @endif --}}
                    @if(session()->has('mes'))
                        <div class="alert alert-success">
                            {{ session()->get('mes') }}
                        </div>
                    @endif
              		<table class="table table-resposive table-bordered" id="example">
                    	<thead style="background-color: #566573;text-align: center;font-size:18px;">

                    		<th style="color: #fff;">Item Code</th>
                    		<th style="color: #fff;">Item Name</th>
                    		<th style="color: #fff;">Catagory</th>
                            <th style="color: #fff;">Unit </th>
                            <th style="color: #fff;">Purchases Price </th>
                            <th style="color: #fff;">Sales Price </th>
                            <th style="color: #fff;">Prevous Stock </th>
                            <th style="color: #fff;">Created By</th>
                    		<th style="color: #fff;">Action</th>

                    	</thead>
                    	<tbody>
                            @foreach($items as $row)
                    		<tr style="text-align: center;">

                    			<td>{{$row->item_code}}</td>
                    			<td>{{$row->name}}</td>
                    			<td>{{ optional($row->category)->name??' ' }}</td>
                    			<td> {{ optional($row->unit)->name??' '}}</td>
                    			<td>{{ number_format($row->purchases_price, 2)}} tk</td>
                    			<td>{{ number_format($row->sales_price, 2) }} tk</td>
                    			<td>{{ number_format($row->previous_stock, 2)}}</td>

                    			<td>{{  optional($row->createdBy)->name??' ' }}</td>
                    			<td>
                    			    <div class="dropdown">
                                <button class="dropbtn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
                                <div class="dropdown-content">
                    				<a href="" class="btn btn-sm btn-success" style="color: #fff;"><i class="fas fa-eye"></i></a>
                                    <a href="{{URL::to('/edit_item/'.$row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
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
    var url = "{{url('/delete_item')}}"+ '/' +$(this).data('id');

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
