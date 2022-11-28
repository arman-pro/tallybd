@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
               
                <div class="card-body" style="overflow-x:auto;border: 1px solid #69C6E0;border-radius: 5px;">

                    <form action="{{url('/store_payment_addlist')}}" method="POST">
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

                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">
                            Add Payment Voucher</h4><br>
                     
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>
                                        <td
                                            style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;max-width: 500px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" />
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Vch. No
                                                    :</div>
                                                <div class="col-md-8">
                                                    @php
                                                    use App\Payment;

                                                    $vo_no = App\Helpers\Helper::IDGenerator(new Payment, 'vo_no', 4,
                                                    'Pa');

                                                    @endphp
                                                    <input type="text" class="form-control" name="vo_no"
                                                        value="{{$vo_no}}" style="text-align: center;" readonly>
                                                </div>
                                            </div>
                                        </td>

                                        </td>
                                    </tr>
                                </table>
                            </div>



                            <div class="col-md-2 heighlightText" style="text-align: right;padding-top:5px;">Payment Mode :</div>
                            <div class="col-md-4"style="font-size:15px;font-weight:bold;">
                                <div class="form-group row">

                                    {{-- <div>
                                        @php
                                        $account_proparty = App\AccountLedger::where('payment',true)->get();
                                        @endphp
                                        <select class="form-control" name="payment_mode_ledger_id">
                                            <option>Select</option>
                                            @foreach($account_proparty as $account_proparty_row)
                                            <option value="{{$account_proparty_row->id}}">
                                                {{$account_proparty_row->account_name}}</option>
                                            @endforeach


                                        </select>
                                    </div> --}}
                                    <select   name="payment_mode_ledger_id" id="payment_mode_ledger_id" class="select2Payment" style="width: 100%" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <span id="payment_ledger_value" style="color: green;font-size:20px;"></span>
                            </div>
                            <br>
                            <br>
                            <br>
                            

                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>
                                        <td
                                            style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;max-width: 500px;">
                                            <div class="row">
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">
                                                    Account Ladger :</div>
                                                <div class="col-md-8"style="font-size:17px;font-weight:bold;">
                                                    <select  name="account_name_ledger_id" id="account_name_ledger_id" class="select2" style="width: 100%" required>
                                                    </select>
                                                    <span id="account_ledger_value" style="color: green;font-size:15px;"></span>
                                                    {{-- @php
                                                    $account_ladger_name = App\AccountLedger::get();
                                                    @endphp
                                                    <select class="form-control" name="account_name_ledger_id">
                                                        <option>Select</option>
                                                        @foreach($account_ladger_name as $account_ladger_name_row)
                                                        <option value="{{$account_ladger_name_row->id}}">
                                                            {{ $account_ladger_name_row->account_name }}</option>
                                                        @endforeach
                                                    </select> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;">
                                            <div class="row">
                                                <div class="col-md-3 heighlightText" style="text-align: center;padding-top: 5px;">
                                                    Amount :</div>
                                                <div class="col-md-9">
                                                    <input type="text" name="amount" class="form-control"
                                                        style="text-align: center;font-size:20px;font-weight:bold;"autocomplete="off">
                                                </div>
                                            </div>
                                        </td>

                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Description :</label>
                                    <div>
                                        <textarea class="form-control" name="description">

                                  </textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!--send sms-->
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" name="send_sms" value="yes" class="form-check-input" id="send_sms">
                                    <label class="form-check-label" for="send_sms">Send SMS</label>
                                </div>
                            </div>


                        </div>
                </div>


                <br>

                <div style="text-align: center; color: #fff; font-weight: 800;">
                    <button type="submit" class="btn btn-success"
                        style="color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                    <button type="submit" class="btn btn-info" name="print" value="1"
                        style="color:#fff; font-weight: 800;font-size: 18px;">Save & Print</button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                </div>
                <br>
                <br>
                <br>
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
