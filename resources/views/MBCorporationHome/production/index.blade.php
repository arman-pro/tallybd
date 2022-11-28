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
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Production</h4>
              		<a href="{{route('production.create')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a><br><br>


              		<table class="table table-resposive table-bordered" style="text-align: center;" id="example">
                    	<thead style="background-color: #566573;text-align: center;">
                    	    <th style="color: #fff;">#SL</th>
                            <th style="color: #fff;">#Vch.No</th>
                            <th style="color: #fff;">Date</th>
                            <th style="color: #fff;">Items</th>
                            <th style="color: #fff;">Working Vo No.</th>
                            <th style="color: #fff;">Action</th>
                    	</thead>
                    	<tbody>
                        @php
                           $ad_list = App\Production::with('working')->get();
                        //    dd($ad_list);

                        @endphp
                        @foreach($ad_list as $key => $ad_list_row)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                          <td>{{$ad_list_row->vo_no}}</td>
                          <td>{{ date('d-m-y', strtotime($ad_list_row->date))}}</td>
                          @php
                            $ad_list_maines = App\DemoProductProduction::where('vo_no',$ad_list_row->vo_no)->where('page_name','2')->get();
                          @endphp
                          <td>@foreach($ad_list_maines as  $ad_list_maines_row)
                                {{$ad_list_maines_row->item->name.", ".$ad_list_maines_row->qty." @ ".$ad_list_maines_row->subtotal_on_product." "}} <br>
                              @endforeach
                          </td>
                          <td>
                              {{ optional($ad_list_row->working)->vo_no??' No Working Id' }}
                          </td>

                          <td>
                              {{-- <a href="{{URL::to('/view_stock_adjustment/'.$ad_list_row->adjustmen_vo_id)}}" class="btn btn-sm btn-success" style="color: #fff;"><i class="far fa-eye"></i></a> --}}
                              <a href="{{URL::to('/production/edit/'.$ad_list_row->vo_no)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                              <!--<a href="{{URL::to('/production/delete/'.$ad_list_row->vo_no)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>-->
                              <a href="#" data-id="{{$ad_list_row->vo_no}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                              <a target="_blank" href="{{URL::to('/production/print/'.$ad_list_row->id)}}" class="btn btn-sm btn-info" style="color: #fff;"><i class="fas fa-print"></i></a>
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
            var url = "{{url('/production/delete')}}"+ '/' +$(this).data('id');
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
                                    console.log(data);
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

