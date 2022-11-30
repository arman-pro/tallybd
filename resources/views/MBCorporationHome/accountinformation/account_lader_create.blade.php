@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Ledger Create')
@section('admin_content')

<div class="container-fluid">
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">Add Account Ledger</h4>
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
                <form action="{{url('/store_account_ledger')}}" method="POST">
                    @csrf                
                    <input type="hidden" name="date" value="{{App\Companydetail::with('financial_year')->first()->financial_year->financial_year_from }}">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="cono1" >Account Ledger Name*</label>
                                <input type="text" name="account_name" class="form-control" id="cono1" placeholder="Account Name"  required/autocomplete="off"/>
                                @error('account_name')
                                <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Account Group</label>                                
                                <select  name="account_group_id"  class="form-control select2" ></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="cono1" >Phone Number*</label>
                                <input type="text" name="account_ledger_phone" class="form-control" id="cono1" placeholder="Phone Number" required/autocomplete="off" />
                                @error('account_ledger_phone')
                                <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="cono1" >E-mail</label>
                                <input type="text" name="account_ledger_email" class="form-control" id="cono1" placeholder="E-mail" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="cono1" >Opening Balance*</label>
                                <input type="text" name="account_ledger_opening_balance" class="form-control"
                                id="cono1" placeholder="Opening Balance" /autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Debit/Credit*</label>
                                <select class="form-control" name="debit_credit">
                                    <option value="1">Debit</option>
                                    <option value="2">Credit</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label >Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="cono1" >Address</label>
                                <textarea class="form-control" name="account_ledger_address"></textarea>
                            </div>
                        </div>                        
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="Payment" name="payment" />
                            <label class="form-check-label" for="Payment">For Transition Mode Transition</label>
                        </div>
                    </div>
            
                    <div class="form-group clearfix">
                        <button type="submit" class="btn btn-success">Create</button>
                        <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger float-end">Cencel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script>
    $(".select2").select2({
       
        ajax: {
            url: '{{ url("activeGroup") }}',
            dataType: 'json',
            type: "GET",
            data: function (params) {
                return {
                    name: params.term
                };
            },
            processResults: function (data) {
                console.log(data);
                var res = data.groups.map(function (item) {
                        return {id: item.id, text: item.account_group_name};
                    });
                return {
                    results: res
                };
            }
        },
    });
</script>
@endpush
