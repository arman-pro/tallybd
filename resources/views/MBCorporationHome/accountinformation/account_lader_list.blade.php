@extends('MBCorporationHome.apps_layout.layout')
@section('List of Account')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">All List Of Account</h4>
            </div>
            <div class="card-body overflow-auto">
                <div class="mb-3 clearfix">
                    <a href="{{route('account_ledger_create')}}" class="btn btn-success">Add New</a>&nbsp;
                </div>

                <table class="table table-resposive table-bordered" id="account-table">
                    <caption>All List Of Account</caption>
                    <thead class="heighlightText" style="background-color: #D6DBDF;">
                        <tr>
                            <th>#SL</th>
                            <th>Account Name</th>
                            <th>Mobile No</th>
                            <th>Group Under</th>
                            <th>Opening Balance</th>
                            <th>Debit/Credit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script>
    

    $(document).ready(function() {
        
        $('#account-table').DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [
                { targets: 0, searchable: false, },
                { orderable: false, targets: -1, searchable: false, },
                { orderable: false, targets: -2, searchable: true, },
                { orderable: false, targets: -3, searchable: false, },
                { orderable: false, targets: -4, searchable: false, },
            ],
            ajax: "{{route('account_ledger_list')}}",
            columns: [
                { data: 'id' },
                { data: 'account_name' },
                { data: 'account_ledger_phone' },
                { data: 'group_under', name: "accountGroupName.account_group_name" },
                { data: 'opening_balance' },
                { data: 'dr_cr' },
                { data: 'action' },
            ],
            "language": {
                "searchPlaceholder": "Account Name | Group Under | Created By",
                "paginate": {
                    "previous": '<i class="fa fa-angle-double-left"></i>',
                    "next": '<i class="fa fa-angle-double-right"></i>',
                },
            },
        }).on('init', function(){
            $('#account-table_filter input[type="search"]').css({width:"400px"});
        });
        

        $(document).on('click', 'a.delete_btn', function(){
            var here = $(this);
            var url = "{{url('/delete_account_ledger')}}"+ '/' +$(this).data('id');
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
