@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Edit Received')
@section('admin_content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-10 m-auto col-sm-12">
            <form action="{{url('/update_recived_addlist/'.$receive->id)}}" method="POST">
                @csrf
            <div class="card">
                <div class="card-header bg-warning text-light">
                    <h4 class="card-title">Update Received Voucher</h4>
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
                            <label class="fw-bold">Date*</label>
                            <input 
                                type="date" name="date" id="date"
                                value="{{ date('Y-m-d', strtotime($receive->date)) }}" class="form-control fw-bold" 
                            />
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="fw-bold">Vo. No*</label>
                            <input 
                                type="text" class="form-control fw-bold" name="vo_no"
                                value="{{$receive->vo_no}}" readonly
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="fw-bold">Payment Mode*</label>
                        <?php
                            $account_proparty = App\AccountLedger::where('payment',true)->get();
                        ?>
                        <select 
                            class="form-control fw-bold" name="payment_mode_ledger_id"
                            data-placeholder="Select Payment Mode" required
                        >
                            @foreach($account_proparty as $account_proparty_row)
                            <option value="{{$account_proparty_row->id}}" {{ $receive->payment_mode_ledger_id == $account_proparty_row->id?"Selected": ' ' }}>
                                {{$account_proparty_row->account_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label class="fw-bold">Account Ledger*</label>
                            <?php
                                $account_ladger_name = App\AccountLedger::get();
                            ?>
                            <select 
                                class="form-control fw-bold" name="account_name_ledger_id"
                                data-placeholder="Select Account Ledger" required
                            >
                                @foreach($account_ladger_name as $account_ladger_name_row)
                                <option value="{{$account_ladger_name_row->id}}"
                                    {{ $receive->account_name_ledger_id == $account_ladger_name_row->id? "Selected": ' ' }}>
                                    {{ $account_ladger_name_row->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="fw-bold">Amount*</label>
                            <input 
                                type="number" min='0' name="amount" value="{{$receive->amount}}"
                                class="form-control fw-bold" placeholder="Amount"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="fw-bold">Description</label>
                        <textarea class="form-control fw-bold" placeholder="Description" name="description">{!!$receive->description!!}</textarea>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary"><b>Update</b></button>
                    <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Update & Print</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
                </div>               
            </div>
            </form>
        </div>
    </div>
    <div>

    </div>
    @endsection
