@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'All Purchase List')

@section('admin_content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">All List of Purchases</h4>
                </div>
                <div class="card-body overflow-auto">
                    <div class="mb-3">
                        <a href="{{route('purchases_addlist_from')}}" class="btn btn-success">Add New</a>
                    </div>
                    <table class="table table-bordered" id="purchase_list">
                        <caption>All List Of Purchases</caption>
                         <thead class="heighlightText" style="background-color: #D6DBDF;">
                        <!--<thead class="bg-light text-dark">-->
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Vch. No</th>
                                <th>Account Ledger</th>
                                <th>Account Item Details</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total Price</th>
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
        $('#purchase_list').DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [
                { targets: 0, searchable: false, },
                { orderable: false, targets: -1, searchable: false, },
                { orderable: false, targets: -2, searchable: false, },
                { orderable: false, targets: -3, searchable: false, },
                { orderable: false, targets: -4, searchable: false, },
                { orderable: false, targets: -5, searchable: false, },
            ],
            ajax: "{{route('purchases_addlist_list')}}",
            columns: [
                { data: 'id' },
                { data: 'date' },
                { data: 'product_id_list' },
                { data: 'ledger_name', name: 'ledger.account_name' },
                { data: 'item_details' },
                { data: 'qty' },
                { data: 'price' },
                { data: 'total_price' },
                { data: 'action' },
            ],
            "language": {
                "searchPlaceholder": "Date | Vo. No | Account Ledger",
                "paginate": {
                    "previous": '<i class="fa fa-angle-double-left"></i>',
                    "next": '<i class="fa fa-angle-double-right"></i>',
                }
            },
        }).on('init', function(){
            $('#purchase_list_filter input[type="search"]').css({width:"400px"});
        });

        $(document).on('click', 'a.delete_btn', function(){
            var here = $(this);
            var url = "{{url('/delete_purchases')}}"+ '/' +$(this).data('id');

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
