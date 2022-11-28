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

                    <form action="{{url('/store_account_ledger')}}" method="POST">
                        @csrf
                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                            Add Account Ledger</h4><br>
                 
                        <input type="hidden" name="date" value="{{App\Companydetail::with('financial_year')->first()->financial_year->financial_year_from }}">
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
                                        <input type="text" name="account_name" class="form-control" id="cono1"
                                            placeholder="Account Name"  required/autocomplete="off">
                                        @error('account_name')
                                        <strong class="text-danger">{{$message}}</strong>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Account Group : </label>
                                  
                                    <select  name="account_group_id"  class="select2" style="width: 200px" >
                                    </select>

                                </div>
                            </div><!-- col-4 -->

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Phone Number : *</label>
                                    <div>
                                        <input type="text" name="account_ledger_phone" class="form-control" id="cono1"
                                            placeholder="Phone Number" required/autocomplete="off">
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
                                            placeholder="E-mail" />
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Opening Balance : *</label>
                                    <div>
                                        <input type="text" name="account_ledger_opening_balance" class="form-control"
                                            id="cono1" placeholder="Opening Balance" /autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Debit/Credit : * </label>
                                    <select class="form-control" name="debit_credit">
                                        <option value="1">Debit</option>
                                        <option value="2">Credit</option>
                                    </select>

                                </div>
                            </div><!-- col-4 -->

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="cono1" class="control-label col-form-label">Address :</label>
                                    <div>
                                        <textarea class="form-control" name="account_ledger_address"></textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-2">
                                <div class="form-group mg-b-10-force">
                                    <label class="form-control-label">Status : * </label>
                                    <select class="form-control" name="status" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Deactive</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="Payment" name="payment">
                                        <label class="form-check-label" for="Payment">
                                            For Transition Mode Transition
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div style="text-align: center; color: #fff; font-weight: 800;">
                            <button type="submit" class="btn btn-success"
                                style="width: 150px;color:#fff; font-weight: 800;font-size: 18px;">Create</button>
                            <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
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
