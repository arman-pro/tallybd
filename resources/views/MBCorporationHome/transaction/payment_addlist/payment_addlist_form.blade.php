@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Add Payment Voucher')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row" style="background-color:Seashell;">
        <div class="col-md-10 col-sm-12 m-auto">
            <form id="save_form" action="{{url('/store_payment_addlist')}}" method="POST">
                @csrf
            <div class="card">
                 <div class="card-header bg-warning text-light">
                <h4 class="card-title">Add Payment Voucher</h4>
               </div>
                <div class="card-body fw-bold">                  
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label>Date*</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" required/>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label>Vch. No</label>
                            <?php
                                // use App\Payment;
                                // $vo_no = App\Helpers\Helper::IDGenerator(new Payment, 'vo_no', 4,'Pa');
                            ?>
                            <input 
                                type="text" class="form-control" name="vo_no" placeholder="Vo No" />
                        </div>
                    </div>
                    <div class="form-group" style="font-size:17px;font-weight:bold;">
                        <label>Payment Mode*</label>
                        <select 
                            name="payment_mode_ledger_id" id="payment_mode_ledger_id" 
                            class="select2Payment form-control" required data-placeholder="Select Payment Mode"
                        >
                        </select>
                        <span id="payment_ledger_value" style="color: green;font-size:20px;"></span>
                        <input type="hidden" id="opening_balance" />
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12" style="font-size:20px;font-weight:bold;">
                            <label>Account Ledger*</label>
                            <select  
                                name="account_name_ledger_id" id="account_name_ledger_id" 
                                class="select2 form-control" required data-placeholder="Select Accound Ledger">
                            </select>
                            <span id="account_ledger_value" style="color: green;font-size:15px;"></span>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label>Amount*</label>
                            <input 
                                type="number" min="0" step="1" name="amount" class="form-control"
                               style="text-align: center;font-size:20px;font-weight:bold;" autocomplete="off" placeholder="Amount" required
                            />
                            <p class="fw-bold p-0">Closing Balance: <span id="closing_balance">0.00</span></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="send_sms" value="yes" class="form-check-input" id="send_sms">
                            <label class="form-check-label" for="send_sms"><b>Send SMS</b></label>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <input type="hidden" name="print" value="0" />
                    <button type="button" id="submit_btn" class="btn btn-success" ><b>Save</b></button>
                    <button type="button" id="submit_btn_print" class="btn btn-outline-info" ><b>Save & Print</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
                </div>                
            </div>
            </form>
        </div>
    </div>


</div>
@endsection
@push('js')

    @if(session()->has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{session('success')}}",
        });
    </script>
    @endif
    
    <script>
    
        $(document).ready(function() {
            $('#submit_btn').click(function() {
                $(this).attr('disabled', true);
                $('#save_form').submit();
            });
            
            $('#submit_btn_print').click(function() {
                $("input[name='print']").val(1);
                $(this).attr('disabled', true);
                $('#save_form').submit();
            });
            
        });
        
        function closing_balance() {
            let opening_bal = +$('#opening_balance').val();
            let amount = +$('input[name="amount"]').val();
            $('#closing_balance').html(Number(opening_bal+amount).toFixed(2));
        }
        
        $(document).on("change", '#opening_balance', closing_balance);
        $(document).on("input", 'input[name="amount"]', closing_balance);
        
        
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
                  $('#opening_balance').val(data).trigger('change');
            });
        });
    </script>
@endpush
