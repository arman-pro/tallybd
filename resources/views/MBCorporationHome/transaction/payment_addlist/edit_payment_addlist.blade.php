@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Update Payment')
@section('admin_content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-10 col-sm-12 m-auto">
            @foreach($Payment as $payment_row)
            <form action="{{url('/update_payment_addlist/'.$payment_row->vo_no)}}" method="POST">
                @csrf
            <div class="card fw-bold">
                <div class="card-header bg-success">
                    <h4 class="card-ttile">Update Payment</h4>
                </div>
                <div class="card-body">
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
                                <input 
                                    type="date" name="date" id="date" required
                                    value="{{ date('Y-m-d', strtotime($payment_row->date)) }}"  class="form-control" 
                                />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label>Vo. No*</label>
                                <input 
                                    type="text" class="form-control" name="vo_no"
                                    value="{{$payment_row->vo_no}}" readonly required 
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Payment Mode*</label>
                            <?php
                                $account_proparty = App\AccountLedger::where('payment',true)->get();
                            ?>
                            <select class="form-control" name="payment_mode_ledger_id" data-placeholder="Select Payment Mode">
                                @foreach($account_proparty as $account_proparty_row)
                                <option value="{{$account_proparty_row->id}}" {{ $payment_row->payment_mode_ledger_id == $account_proparty_row->id?"Selected": ' ' }}>
                                    {{$account_proparty_row->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 col-sm-12">
                                <label>Account Ledger*</label>
                                <?php
                                    $account_ladger_name = App\AccountLedger::get();
                                ?>
                                <select class="form-control" name="account_name_ledger_id" data-placeholder="Select Account Ledger">
                                    @foreach($account_ladger_name as $account_ladger_name_row)
                                    <option value="{{$account_ladger_name_row->id}}"
                                        {{ $payment_row->account_name_ledger_id == $account_ladger_name_row->id?"Selected": ' ' }}>
                                        {{ $account_ladger_name_row->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label>Amount*</label>
                                <input 
                                    type="number" name="amount" value="{{$payment_row->amount}}"
                                    class="form-control" required
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description">{!!$payment_row->description!!}</textarea>
                        </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><b>Update</b></button>
                    <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Save & Print</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
                </div>
            </div>
            </form>
            @endforeach
        </div>
    </div>
    <div>
    </div>
    @endsection
