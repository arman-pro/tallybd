@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Received List')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">All List Of Received</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{route('recevied_addlist_form')}}" class="btn btn-success">Add New</a>
                    </div>
                    <table class="table table-bordered" id="received_list">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Vch. No</th>
                                <th>Payment Mode</th>
                                <th>Account Ledger</th>
                                <th>Amount</th>
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
    $(document).ready(function(){
      
        $('#received_list').DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [
                { orderable: false, targets: -1 },
                { orderable: false, targets: -3 },
                { orderable: false, targets: -4 },
            ],
            ajax: "{{route('recevied_addlist')}}",
            columns: [
                { data: 'id' },
                { data: 'date' },
                { data: 'vo_no' },
                { data: 'payment_mode.account_name' },
                { data: 'account_mode.account_name' },
                { data: 'amount' },
                { data: 'action' },
            ],
        });

        $(document).on('click', 'a.delete_btn', function(){
            var here = $(this);
            var url = "{{url('/delete_recevie_addlist')}}"+ '/' +$(this).data('id');

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

