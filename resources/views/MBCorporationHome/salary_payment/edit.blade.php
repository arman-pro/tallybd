@extends('MBCorporationHome.apps_layout.layout')
@section('title', "Edit Salary Payment")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('salary_payment.update', $data->id)}}" method="Post">
                @csrf
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">Edit Salary Payment</h4>
                </div>
                <div class="card-body fw-bold">
                    <div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(Session::has('msg'))
                    <p class="alert alert-success">{{ Session::get('msg') }}</p>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-4 col-sm-12">
                            <label for="voNo">Vo No</label>
                            <input type="text" name="vo_no" id="voNo" class="form-control" placeholder="Vo No"
                                value="{{$data->vo_no??''}}"  readonly>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="">Date</label>
                            <input type="date" value="{{ $data->date }}" name="date" id="date" class="form-control" placeholder="date" >
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label style="text-align:left;display:block" for="designation_id">Employee</label>
                            <select 
                                class="form-control" id="employee_id" name="employee_id" required
                            />
                                <option  value="" hidden >Select Employee</option>
                                @foreach ($employees as $row)
                                <option value="{{ $row->id }}"{{ $data->employee_id == $row->id?'Selected': ' '  }}>{{ $row->name??' ' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="text-align:left;display:block" for="summary_ledger">Account Summary</label>
                        <input type="text" class="form-control" value="" id="summary_ledger" disabled />
                    </div>

                    <div class="form-group">
                        <input type="checkbox" class="form-check-input" id="payment"  name='payment'  onclick="return false;"
                        @if ($data->payment_by)
                            checked
                        @endif>
                        <label class="form-check-label" for="payment"><b>Payment</b></label>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12 pay_cls">
                            <label for="payment_by" style="text-align:left;display:block">Payment By</label>
                            <select 
                                class="form-control" id="payment_by" name="payment_by" required 
                            >
                                <option value="" hidden>Select Payment By</option>
                                @foreach ($paymentLedger as $row)
                                <option value="{{ $row->id }}"{{ $data->payment_by == $row->id?'Selected': ' '  }}>{{ $row->account_name??' ' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="name" style="text-align:left;display:block">Amount </label>
                                <input type="text" class="form-control" id="name" name="amount"
                                    placeholder="Enter name"
                                    value="{{ $data->amount }}"
                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                    required
                                />
                        </div>
                    </div>

                    <div class="form-groups">
                        <label for="name" style="text-align:left;display:block">Description </label>
                            <textarea name="description" class="form-control" id="" cols="100%">{{ $data->description??'' }}</textarea>
                    </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type='submit' class="btn btn-success"> <strong>Submit</strong></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
        @endsection
@push('js')
<script>
    $('.btn-danger').click(function(){
        $(this).closest('.employee-row').remove();
    })
    $(document).ready(function(){
        let employee_id=$('#employee_id').val();
        ledgerSummary(employee_id);
        $('#employee_id').change(function(){
        var employee_id = $(this).val();
            ledgerSummary(employee_id)
        });

        function ledgerSummary(employee_id){
            $.ajax({
                type:"get",
                dataType:"json",
                url:"{{route('salary_payment.searchAccountSummary')}}",
                data: {
                    employee_id:employee_id,
                    "_token": "{{ csrf_token() }}",
                },
                success:function(res){
                    let data = res.summary+' '+res.type;
                    $('#summary_ledger').val(data);
                },
                error:function(err){
                    console.log(err);
                }
            });
        }

       
    });


</script>

@endpush
