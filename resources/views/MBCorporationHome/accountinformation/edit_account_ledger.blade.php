@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; "> Company Account</h4>
    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">
                    @foreach($oneaccount_ledger as $por_row)
                    {{-- @dd($por_row->account_ledger_group_name) --}}
                    <form action="{{url('/update_account_ledger/'.$por_row->account_ledger_id)}}" method="POST">
                        @csrf
                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                            Update Account Ledger</h4><br>
                        <br>
                        <div class="row">

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Account Ledger Name :
                                        *</label>
                                    <div>
                                        <input type="text" name="account_name" class="form-control"
                                            value="{{$por_row->account_name}}" />
                                        @error('account_name')
                                        <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Account Group : </label>
                                    <select class="form-control" name="account_group_id">
                                        @foreach($account_group_list as $row)
                                            <option value="{{$row->id}}" {{ $por_row->account_group_id ==   $row->id?"Selected": ' '  }}>{{$row->account_group_name}}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div><!-- col-4 -->

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Phone Number : *</label>
                                    <div>
                                        <input type="text" name="account_ledger_phone" class="form-control" id="cono1"
                                            value="{{$por_row->account_ledger_phone}}" />
                                        @error('account_ledger_phone')
                                        <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">E-mail :</label>
                                    <div>
                                        <input type="text" name="account_ledger_email" class="form-control" id="cono1"
                                            value="{{$por_row->account_ledger_email}}" />
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Opening Balance :</label>
                                    <div>
                                        <input type="hidden" name="account_ledger_opening_balance_old" value="{{$por_row->account_ledger_opening_balance}}" />
                                        <input type="text" name="account_ledger_opening_balance" class="form-control" value="{{$por_row->account_ledger_opening_balance}}" />
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-2">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Debit/Credit : * </label>
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
                            </div><!-- col-4 -->


                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Address :</label>
                                    <div>
                                        <textarea class="form-control" name="account_ledger_address">
					                        	{{$por_row->account_ledger_address}}
					                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Status : * </label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" {{$por_row->status == 1? 'Selected': '' }}>Active</option>
                                        <option value="0" {{$por_row->status == 0? 'Selected': '' }}>Deactive</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="Payment" name="payment"
                                            @if ($por_row->payment)
                                                Checked
                                            @endif
                                        >
                                        <label class="form-check-label" for="Payment">
                                        For Transition Mode
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="receive" id="Receive"
                                        @if ($por_row->receive)
                                        Checked
                                    @endif>
                                        <label class="form-check-label" for="Receive">
                                            For Receive Mode Transition
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>

                        @endforeach
                        <br>
                        <br>
                        <br>
                        <div style="text-align: center; color: #fff; font-weight: 800;">
                            <button type="submit" class="btn btn-primary"
                                style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Update</button>
                            <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div>

        </div>
        @endsection
