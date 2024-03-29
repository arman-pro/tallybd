@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Sales Order List')
@section('admin_content')

 

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">All List Of Sales Order</h4>
                </div>
                <div class="card-body overflow-auto">
                    <div class="mb-3">
                        <a href="{{route('sales_order_addlist_form')}}" class="btn btn-success">Add New</a>
                    </div>
                    <table class="table table-bordered" id="sale_order_list">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th>SL</th>
                                <th>Vo. No</th>
                                <th>Account Ledger</th>
                                <th>Item Details</th>
                                <th>Qty.</th>
                                <th>Total Price</th>
                                <th>Delivered To</th>
                                <th>Status</th>
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
    
        $('#sale_order_list').DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [
                { orderable: false, targets: -1 },
                { orderable: false, targets: -6 },
                { orderable: false, targets: -7 },
            ],
            ajax: "{{route('sales_order_addlist')}}",
            columns: [
                { data: 'id' },
                { data: 'product_id_list' },
                { data: 'ledger_name' },
                { data: 'item_details' },
                { data: 'qty' },
                { data: 'total_price' },
                { data: 'delivered_to_details' },
                { data: 'md_signature' },
                { data: 'action' },
            ],
        });

        $(document).on('click', 'a.delete_btn', function(){
            var here = $(this);
            var url = "{{url('/delete_sales_order')}}"+ '/' +$(this).data('id');

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