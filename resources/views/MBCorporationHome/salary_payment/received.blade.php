@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                @if(Session::has('mes'))
                    <p class="alert alert-info text-center">{{ Session::get('mes') }}</p>
                @endif
                <div class="card-body">
                    <h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All Salary Received Manage
                    <a href="{{ route('salary_payment.create_receive') }}"><span style="display: block;text-align:right"><button class="btn btn-md btn-success"> Add +</button></span></a>
                    </h4>

                    <table class="table table-resposive table-bordered" id="example">
                        <thead style="background-color: #566573;text-align: center;">
                            <th style="color: #fff;"># SL</th>
                            <th style="color: #fff;"> Date</th>
                            <th style="color: #fff;">Name </th>
                            <th style="color: #fff;"> Amount</th>
                            <th style="color: #fff;"> Received By </th>
                            <th style="color: #fff;"> Created By </th>
                            <th style="color: #fff;">Action</th>
                        </thead>
                        <tbody>

                        @foreach($salaries  as $key=>$row)
                            <tr style="text-align: center;">
                                <td>{{$row->vo_no}}</td>
                                <td>{{date('d-m-y', strtotime($row->date??' '))}}</td>
                                <td>{{optional($row->employee)->name??' '}}</td>
                                <td>{{ number_format($row->amount, 2)}}</td>
                                <td>{{ optional($row->receive)->account_name??' '}}</td>
                                <td>{{optional($row->createdBy)->name??' '}}</td>
                                <td>
                                    <a target="_blank" href="{{URL::to('/salary-payment/print_salary_receive_recepet/'.$row->vo_no)}}" class="btn btn-sm btn-info" style="color: #fff;"><i class="fas fa-print"></i></a>
                                    <a href="{{URL::to('/salary-payment/edit_receive/'.$row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <!--<a href="{{URL::to('/salary-payment/delete_receive/'.$row->id)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>-->
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
    var url = "{{url('/salary-payment/delete_receive')}}"+ '/' +$(this).data('id');

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