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
                    <h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All Salary Manage/Generate
                        <a href="{{ route('salary.create') }}"><span style="display: block;text-align:right"><button class="btn btn-md btn-success"> Add +</button></span></a>
                    </h4>
                <div style="overflow-x:auto;">
                    <table class="table table-resposive table-bordered" id="salary_table">
                        <thead style="background-color: #566573;text-align: center;">
                            <th style="color: #fff;"># SL</th>
                            <th style="color: #fff;">Vch No</th>
                            <th style="color: #fff;"> Salary Date</th>
                            <th style="color: #fff;">Generated Date</th>
                            <th style="color: #fff;"> Salary</th>
                            <th style="color: #fff;"> Payment By </th>
                            <th style="color: #fff;"> Created By </th>
                            <th style="color: #fff;">Action</th>
                        </thead>
                        <tbody>

                        @foreach($salaries  as $key=>$row)
                            <tr style="text-align: center;">
                                <td>{{$key+1}}</td>
                                <td>{{$row->vo_no}}</td>
                                <td>{{date('d-m-y', strtotime($row->salary_date ??' '))}}</td>
                                <td>{{date('d-m-y', strtotime($row->date ??' '))}}</td>
                                <td>{{ number_format($row->total_amount, 2)}}</td>
                                <td>{{optional($row->ledger)->account_name??' '}}</td>
                                <td>{{optional($row->createdBy)->name??' '}}</td>
                                <td>
                                    <a href="{{URL::to('/salary/print/'.$row->id)}}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-print"></i></a>
                                    <a href="{{URL::to('/salary/edit/'.$row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    <a href="#" data-id="{{$row->id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                    <!--<a href="{{URL::to('/salary/delete/'.$row->id)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>-->
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
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
    var url = "{{url('/salary/delete')}}"+ '/' +$(this).data('id');

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
 <script type="text/javascript">
    $(document).ready(function() {
        $('#salary_table').DataTable({
            responsive: true,
             columnDefs: [
                 {orderable: false, target: 5},
                 {orderable: false, target: 6},
                 {orderable: false, target: 7}
            ],
            order: [],
        "lengthMenu": [[10, 5, 15, 25, 50, -1], [10,5,15, 25, 50, "All"]],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'print','pageLength'
            ]
        });
    });
</script>
@endpush

