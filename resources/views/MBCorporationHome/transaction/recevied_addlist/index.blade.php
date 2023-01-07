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
                <div class="card-body overflow-auto">
                    <div class="mb-3">
                        <a href="{{route('recevied_addlist_form')}}" class="btn btn-success">Add New</a>
                    </div>
                    <table class="table table-bordered" id="received_list">
                        <caption>All List Of Received</caption>
                        <thead class="heighlightText" style="background-color: #D6DBDF;">
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Vch. No</th>
                                <th>Payment Mode</th>
                                <th>Account Ledger</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" id="message_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="message_body">
        
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
                { targets: 0,searchable:false, },
                { orderable: false, targets: -1,searchable:false, },
                { orderable: false, targets: -2, searchable:false, },
                { orderable: false, targets: -4 },
            ],
            ajax: "{{route('recevied_addlist')}}",
            columns: [
                { data: 'id' },
                { data: 'date' },
                { data: 'vo_no' },
                { data: 'payment_mode.account_name', name: 'paymentMode.account_name' },
                { data: 'account_mode.account_name', name: 'accountMode.account_name' },
                { data: 'amount' },
                { data: 'description' },
                { data: 'action' },
            ],
            "language": {
                "searchPlaceholder": "Date | Vo. No | Payment Mode | Account Ledger | Amount",
                "paginate": {
                    "previous": '<i class="fa fa-angle-double-left"></i>',
                    "next": '<i class="fa fa-angle-double-right"></i>',
                },
            },
        }).on('init', function(){
            $('#received_list_filter input[type="search"]').css({width:"400px"});
        });


        $(document).on("click", ".view_message", function(){
            let message = $(this).data('message');
            $('#message_modal').modal('show');
            $('#message_body').text(message);
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

