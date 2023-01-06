@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'All Items')
@section('admin_content')


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card overflow-auto">
            <div class="card-header bg-success">
                <h4 class="card-title">All List Of Item</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                   
                    <a href="{{route('item_create_from')}}" class="btn btn-success">Add New</a>
                </div>
                @if(session()->has('mes'))
                    <div class="alert alert-success">
                        {{ session()->get('mes') }}
                    </div>
                @endif
                <table class="table table-bordered table-sm" id="item_list">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>SL</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Purchase Price</th>
                            <th>Sales Price</th>
                            <th>Previous Stock</th>
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
        $('#item_list').DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [
                { orderable: false, targets: -1, searchable: false },
                { orderable: false, targets: -3, searchable: false },
                { orderable: false, targets: -4, searchable: false },
                { orderable: false, targets: -5, searchable: false },
                { targets: 0, searchable: false },
            ],
            ajax: "{{route('item_list')}}",
            columns: [
                { data: 'id' },
                { data: 'item_code' },
                { data: 'name' },
                { data: 'category' },
                { data: 'unit', name: "unit.name" },
                { data: 'purchases_price' },
                { data: 'sale_price' },
                { data: 'previouse_stock' },
                { data: 'created_by', name: "createdBy.name" },
                { data: 'action' },
            ],
            "language": {
                "searchPlaceholder": "Item Code | Item Name | Category | Created By",
                "paginate": {
                    "previous": '<i class="fa fa-angle-double-left"></i>',
                    "next": '<i class="fa fa-angle-double-right"></i>',
                },
            },
        }).on('init', function(){
            $('#item_list_filter input[type="search"]').css({width:"400px"}).att;
        });

        $(document).on('click', 'a.delete_btn', function(){
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
    });
    
</script>
@endpush
