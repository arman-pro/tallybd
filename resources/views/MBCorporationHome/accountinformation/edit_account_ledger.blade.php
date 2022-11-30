@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Update Account Ledger')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success">
            <h4 class="card-title">Update Account Ledger</h4>
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

            @foreach($oneaccount_ledger as $por_row)
            <form action="{{url('/update_account_ledger/'.$por_row->account_ledger_id)}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="cono1">Account Ledger Name *</label>
                            <input type="text" name="account_name" class="form-control" value="{{$por_row->account_name}}" />
                            @error('account_name')
                            <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label >Account Group*</label>
                            <select class="form-control" name="account_group_id">
                                @foreach($account_group_list as $row)
                                    <option value="{{$row->id}}" {{ $por_row->account_group_id ==   $row->id?"Selected": ' '  }}>{{$row->account_group_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="cono1">Phone Number*</label>
                            <input type="text" name="account_ledger_phone" class="form-control" id="cono1" value="{{$por_row->account_ledger_phone}}" />
                            @error('account_ledger_phone')
                            <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="cono1" >E-mail</label>
                            <input type="text" name="account_ledger_email" class="form-control" id="cono1" value="{{$por_row->account_ledger_email}}" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="cono1" >Opening Balance</label>
                            <input type="hidden" name="account_ledger_opening_balance_old" value="{{$por_row->account_ledger_opening_balance}}" />
                            <input type="text" name="account_ledger_opening_balance" class="form-control" value="{{$por_row->account_ledger_opening_balance}}" />
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Debit/Credit*</label>
                            <select class="form-control" name="debit_credit">
                                @if($por_row->debit_credit < 2) <option value="{{$por_row->debit_credit}}">Debit
                                    </option>
                                    @elseif($por_row->debit_credit > 1)
                                    <option value="{{$por_row->debit_credit}}">Credit</option>
                                    @endif
                                    <option>Select</option>
                                    <option value="1">Debit</option>
                                    <option value="2">Credit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="cono1" >Address</label>
                            <textarea class="form-control" name="account_ledger_address">{{$por_row->account_ledger_address}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label >Status*</label>
                            <select class="form-control" name="status" required>
                                <option value="1" {{$por_row->status == 1? 'Selected': '' }}>Active</option>
                                <option value="0" {{$por_row->status == 0? 'Selected': '' }}>Deactive</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="Payment" name="payment"
                                    @if($por_row->payment) checked @endif
                                />
                                <label class="form-check-label" for="Payment">For Transition Mode </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-danger float-end">Cencel</a>
                </div>
            </form>
            @endforeach
        </div>
    </div>
</div>
</div>

</div>
@endsection
