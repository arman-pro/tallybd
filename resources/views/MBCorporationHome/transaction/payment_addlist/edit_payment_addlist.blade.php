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
                    @foreach($Payment as $payment_row)
                    <form action="{{url('/update_payment_addlist/'.$payment_row->vo_no)}}" method="POST">
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
                            Update Payment</h4><br>
                        <br>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>
                                        <td
                                            style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;max-width: 500px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Date :
                                                    *</div>
                                                <div class="col-md-8">
                                                    <input type="date" name="date" id="date"
                                                    value="{{ date('Y-m-d', strtotime($payment_row->date)) }}"  class="form-control" />
                                                </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Vo. No
                                                    : *</div>
                                                <div class="col-md-8">

                                                    <input type="text" class="form-control" name="vo_no"
                                                        value="{{$payment_row->vo_no}}" style="text-align: center;"
                                                        readonly>
                                                </div>
                                            </div>
                                        </td>

                                        </td>
                                    </tr>
                                </table>
                            </div>



                            <div class="col-md-2" style="text-align: right;padding-top:5px;">Payment Mode : *</div>
                            <div class="col-md-6">
                                <div class="form-group row">

                                    <div>
                                        @php
                                        $account_proparty = App\AccountLedger::where('payment',true)->get();
                                        @endphp
                                        <select class="form-control" name="payment_mode_ledger_id">

                                            @foreach($account_proparty as $account_proparty_row)
                                            <option value="{{$account_proparty_row->id}}" {{ $payment_row->payment_mode_ledger_id == $account_proparty_row->id?"Selected": ' ' }}>
                                                {{$account_proparty_row->account_name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                            </div>

                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>
                                        <td
                                            style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;max-width: 500px;">
                                            <div class="row">
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">
                                                    Account Ladger : *</div>
                                                    <div class="col-md-8">
                                                        @php
                                                            $account_ladger_name = App\AccountLedger::get();
                                                        @endphp
                                                        <select class="form-control" name="account_name_ledger_id">
                                                            @foreach($account_ladger_name as $account_ladger_name_row)
                                                            <option value="{{$account_ladger_name_row->id}}"
                                                                {{ $payment_row->account_name_ledger_id == $account_ladger_name_row->id?"Selected": ' ' }}>
                                                                {{ $account_ladger_name_row->account_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            </div>
                                        </td>
                                        <td style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;">
                                            <div class="row">
                                                <div class="col-md-3" style="text-align: center;padding-top: 5px;">
                                                    Amount : *</div>
                                                <div class="col-md-9">
                                                    <input type="text" name="amount" value="{{$payment_row->amount}}"
                                                        class="form-control" style="text-align: center;">
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
                                            {!!$payment_row->description!!}
                                  </textarea>
                                    </div>
                                </div>
                            </div>


                        </div>
                </div>

                @endforeach
                <br>
                <br>
                <br>
                <div style="text-align: center; color: #fff; font-weight: 800;">
                    <button type="submit" class="btn btn-primary"
                        style="color:#fff; font-weight: 800;font-size: 18px;">Update </button>
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
    <div>

    </div>
    @endsection
