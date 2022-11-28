@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">
                    @if(session()->has('message'))
                        <div class="alert alert-success">{{session()->get('message')}}</div>
                    @endif
                    <form action="{{route('importpayment')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Import Payment *</label>
                            <input type="file" name="payment_import" id="payment_import" class="form-control" required />
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Upload</button>
                        </div>
                </form>
            </div>
        </div>
    </div>


</div>
@endsection
@push('js')
    <script>
        $(".select2").select2({
            ajax: {
                url: '{{ url("activeLedger") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {
                    console.log(data);
                	var res = data.ledgers.map(function (item) {
                        	return {id: item.id, text: item.account_name};
                        });
                    return {
                        results: res
                    };
                }
            },

        });
        $(".select2Payment").select2({
            ajax: {
                url: '{{ url("paymentLedger") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {
                    console.log(data);
                	var res = data.ledgers.map(function (item) {
                        	return {id: item.id, text: item.account_name};
                        });
                    return {
                        results: res
                    };
                }
            },

        });
        $('#payment_mode_ledger_id').change(function(){
            var ledger_id = $(this).val();
            $.get("{{url('ledgerValue')}}"+'/'+ledger_id, function(data, status){
                 $('#payment_ledger_value').html(data);
            });
        });
        $('#account_name_ledger_id').change(function(){
            var ledger_id = $(this).val();
            $.get("{{url('ledgerValue')}}"+'/'+ledger_id, function(data, status){
                 $('#account_ledger_value').html(data);
            });
        });
    </script>
@endpush
