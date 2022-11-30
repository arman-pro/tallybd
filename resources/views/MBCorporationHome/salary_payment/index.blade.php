@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Salary Payment')
@section('admin_content')
<div class="container-fluid">
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card overflow-auto">
            <div class="card-header bg-success">
                <h4 class="card-title">All Salary Payment Manage</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('salary_payment.create') }}" class="btn btn-md btn-success">Add New</a>
                </div>
                @if(session()->has('mes'))
                    <div class="alert alert-info">{{session('mes')}}</div>
                @endif
                <table class="table table-bordered table-responsive" id="salary_payment_list">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>SL</th>
                            <th>Vch No</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Amount</th>
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
        $('#salary_payment_list').DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [
                { orderable: false, targets: -1 },
            ],
            ajax: "{{route('salary_payment.index')}}",
            columns: [
                { data: 'id' },
                { data: 'vo_no' },
                { data: 'date' },
                { data: 'name' },
                { data: 'amount' },
                { data: 'payment_by' },
                { data: 'created_by' },
                { data: 'action' },
            ],
        });

        $(document).on('click', 'a.delete_btn', function(){
            var here = $(this);
            var url = "{{url('/salary-payment/delete')}}"+ '/' +$(this).data('id');

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

