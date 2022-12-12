@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Create Salary Receive")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('salary_payment.store_receive')}}" method="Post">
                @csrf
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Create Salary Receive</h4>
                </div>
                <div class="card-body">
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
                             
                    <?php
                        use App\SalaryPayment;
                        $vo_no = App\Helpers\Helper::IDGenerator(new SalaryPayment, 'vo_no', 5, 'SLP');
                    ?>
                    <div class="form-group row">
                        <div class="col-md-4 col-sm-12">
                            <label for="voNo" class="fw-bold">Vch No*</label>
                            <input 
                                type="text" name="vo_no" id="voNo" 
                                class="form-control" placeholder="Vo No"
                                value="{{$vo_no}}"  readonly
                            />
                        </div>
                        <div class="col-md-4 col-sm-12 heighlightText">
                            <label for="date" class="fw-bold">Date *</label>
                            <input type="date" name="date" id="date" class="form-control" placeholder="date" required>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="designation_id" class="fw-bold">Employee *</label>
                            <select
                                class="form-control" id="employee_id" name="employee_id" required
                            >
                                <option  value="" hidden >Select a Employee</option>
                                @foreach ($employees as $row)
                                <option value="{{ $row->id }}"{{ request()->employee_id == $row->id?'Selected': ' '  }}>{{ $row->name??' ' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="summary_ledger" class="fw-bold">Account Summary</label>
                        <input type="text" class="form-control" value="" id="summary_ledger" disabled>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" class="form-check-input" name="receive" id="receive" checked>
                        <label class="form-check-label" for="receive"><b>Receive</b></label>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12 rec_cls">
                            <label for="payment_by" class="fw-bold">Receive By *</label>
                            <select 
                                name="receive_by" class="form-control" 
                                id="select2rec" style="width: 100%" required=""
                                data-placeholder="Select Receive By"
                            >
                            </select>
                        </div>                           

                        <div class="col-md-6 col-sm-12">
                            <label for="name" style="text-align:left;display:block" class="fw-bold">Amount </label>
                            <input 
                                type="number" class="form-control" id="name" name="amount"
                                placeholder="Enter Amount"
                                onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                required autocomplete="off"
                            />
                        </div>                      
                    </div>
                    <div class="form-group">
                        <label for="name" class="fw-bold">Description</label>
                        <textarea name="description" id="" placeholder="Description" cols="100%" class="form-control" ></textarea>
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

    

    $('#employee_id').change(function(){
        var employee_id = $(this).val();
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
    });
  
    $("#select2rec").select2(
        {
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

</script>

@endpush
