@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Salary Manage")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md--12 col-sm-12">
        <div class="card overflow-auto">
            <div class="card-header bg-success">
                <h4 class="card-title">All Salary Manage/Generate</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('salary.create') }}" class="btn btn-md btn-success">Add New</a>
                </div>
                @if(Session::has('mes'))
                    <p class="alert alert-info text-center">{{ Session::get('mes') }}</p>
                @endif
                <table class="table table-bordered table-sm" id="salary_list">
                    <caption>All Salary Manage/Generate</caption>
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>SL</th>
                            <th>Vch. No</th>
                            <th>Salary Date</th>                            
                            <th>Generated Date</th>
                            <th>Salary</th>
                            <th>Payment By</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#salary_list').DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [
                { targets: 0, searchable: false, },
                { orderable: false, targets: -1, searchable: false, },
            ],
            ajax: "{{route('salary.index')}}",
            columns: [
                { data: 'id' },
                { data: 'vo_no' },
                { data: 'salary_date', name: 'salary_date' },
                { data: 'generated_date', name: 'date' },
                { data: 'salary', name: "total_amount" },
                { data: 'payment_by', name: 'ledger.account_name', },
                { data: 'created_by', name: 'createdBy.name' },
                { data: 'action' },
            ],
            "language": {
                "searchPlaceholder": "Vo. No|Salary Date|Generated Date|Salary|Payment By|Created By",
                "paginate": {
                    "previous": '<i class="fa fa-angle-double-left"></i>',
                    "next": '<i class="fa fa-angle-double-right"></i>',
                },
            },
        }).on('init', function(){
            $('#salary_list_filter input[type="search"]').css({width:"450px"});
        });

        $(document).on('click', 'a.delete_btn', function(){
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
    });

</script>

@endpush

