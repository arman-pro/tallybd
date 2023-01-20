@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Add Received")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row" style="background-color:LightCyan;">
        <div class="col-md-10 col-sm-12 m-auto">
            <form action="{{url('/store_recived_addlist')}}" method="POST">
            <div class="card">
              
                    <div class="card-header bg-success">
                    <h4 class="text-title">Add Received Voucher</h4>
                </div>
                <div class="card-body fw-bold">
                        @csrf
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
                                <label class="fw-bold">Date*</label>
                                <input type="date" name="date" id="date" class="form-control fw-bold" value="{{ date('Y-m-d') }}" required />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label class="fw-bold">Vch. No*</label>
                                <?php
                                    use App\Receive;
                                    $vo_no = App\Helpers\Helper::IDGenerator(new Receive, 'vo_no', 4, 'Re');
                                ?>
                                <input 
                                    type="text" class="form-control fw-bold" name="vo_no"
                                    value="{{$vo_no}}" readonly
                                />
                            </div>
                        </div>

                        <div class="form-group" style="font-size:17px;font-weight:bold;">
                            <label class="fw-bold">Received Mode*</label>
                            <select   
                                name="payment_mode_ledger_id" id="payment_mode_ledger_id" 
                                class="select2Payment form-control fw-bold" 
                                required data-placeholder="Select Received Mode"
                            />
                            </select>
                           <span id="payment_ledger_value" style="color: green;font-size:20px;"></span>
                           <input type="hidden" id="opening_balance" />
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-sm-12" style="font-size:20px;font-weight:bold;">
                                <label class="fw-bold">Account Ledger*</label>
                                <select  
                                    name="account_name_ledger_id" id="account_name_ledger_id" 
                                    class="select2 form-control fw-bold" required data-placeholder="Select Account Ledger"
                                >
                                </select>
                                <span id="account_ledger_value" style="color: green;font-weight: 600"></span>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label class="fw-bold">Amount*</label>
                                <input 
                                    type="number" name="amount" class="form-control fw-bold"
                                   style="text-align: center;font-size:20px;font-weight:bold;" autocomplete="off" min="0" placeholder="Amount"
                                />
                                <p class="fw-bold p-0">Closing Balance: <span id="closing_balance">0.00</span></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="fw-bold">Description</label>
                            <textarea class="form-control fw-bold" placeholder="Description" name="description"></textarea>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="send_sms" value="yes" class="form-check-input" id="send_sms">
                                <label class="form-check-label" for="send_sms"><b>Send SMS</b></label>
                            </div>
                        </div>
                        
                </div>
                <div class="card-footer text-center fw-bold">
                    <button type="submit" class="btn btn-success"><b>Save</b></button>
                    <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Save & Print</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
                </div>
            </div>
            </form>
        </div>
    </div>


</div>
@endsection

@push('js')
    <script>
    
        function total_closing_balance() {
            let opening_bal = +$('#opening_balance').val();
            let amount = +$('input[name="amount"]').val();
            $('#closing_balance').html(Number(opening_bal-amount).toFixed(2));
        }
        
        $(document).on("change", '#opening_balance', total_closing_balance);
        $(document).on("input", 'input[name="amount"]', total_closing_balance);
    
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
