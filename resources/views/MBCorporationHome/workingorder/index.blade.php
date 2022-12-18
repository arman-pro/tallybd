@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Working List")
@section('admin_content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="card overflow-auto">
                <div class="card-header bg-success">
                    <h4 class="card-title">All Working List</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{route('workingOrder.create')}}" class="btn btn-success" >Add New</a>
                    </div>
                    <table class="table table-bordered" id="working_table">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Vch. No</th>                               
                                <th>Items</th>
                                <th>Production Vch. No.</th>
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
    $('#working_table').DataTable({
        processing: true,
        serverSide: true,
        columnDefs: [
            { orderable: false, targets: 0, searchable: false, },
            { orderable: false, targets: -1, searchable: false, },
            { orderable: false, targets: -2 },
            { orderable: false, targets: -3, searchable: false,},
        ],
        ajax: "{{route('workingOrder.index')}}",
        columns: [
            { data: 'id' },
            { data: 'date' },
            { data: 'vo_no' },
            { data: 'items' },
            { data: 'production_vo_no', name:'production.vo_no', },
            { data: 'action' },
        ],
        "language": {
                "search": '<i class="fa fa-search"></i>',
                "searchPlaceholder": "Date | Vo. No | Production Vo. No",
                "paginate": {
                "previous": '<i class="fa fa-angle-double-left"></i>',
                    "next": '<i class="fa fa-angle-double-right"></i>'
            }
        }
    }).on('init', function(){
        $('#working_table_filter input[type="search"]').css({width:"400px"});
    });

    $(document).on('click', 'a.delete_btn', function(){
        var here = $(this);
        var url = "{{url('/workingorder/delete')}}"+ '/' +$(this).data('id');
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
