@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div style="text-align: left;">

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
                            <h4 class="card-title"
                            style=" font-weight: 800;text-align:center; padding-bottom: 10px; border-bottom: 2px solid #eee; background-color: #DC7633; padding: 5px; color: #fff;">
                             Salary Receive</h4><br>

                             <form action="{{route('salary_payment.store_receive')}}" method="Post">
                                @csrf
                                @php
                                    use App\SalaryPayment;
                                    $vo_no = App\Helpers\Helper::IDGenerator(new SalaryPayment, 'vo_no', 5, 'SLP');
                                @endphp

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                          <label for="voNo">Vch No</label>
                                          <input type="text" name="vo_no" id="voNo" class="form-control" placeholder="vo no..."
                                            value="{{$vo_no}}"  readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2 heighlightText">
                                        <div class="form-group">
                                          <label for="date">Date *</label>
                                          <input type="date" name="date" id="date" class="form-control" placeholder="date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label style="text-align:left;display:block" for="designation_id">Employee *
                                            </label>
                                            <select class="form-control" id="employee_id" name="employee_id" required>
                                                <option  value="{{ null}}" selected >- select -</option>
                                                @foreach ($employees as $row)
                                                <option value="{{ $row->id }}"{{ request()->employee_id == $row->id?'Selected': ' '  }}>{{ $row->name??' ' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label style="text-align:left;display:block" for="summary_ledger">Account Summary
                                            </label>
                                            <input type="text" value="" id="summary_ledger" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                      

                                       <div class="form-group">
                                            <input type="checkbox" class="form-check-input" name="receive" id="receive" checked>
                                            <label class="form-check-label" for="receive">Recevie</label>
                                        </div>
                                    </div>
                                   

                                    <div class="col-md-4 rec_cls">
                                        <div class="form-groups">
                                            <label for="payment_by" style="text-align:left;display:block">Receive By *</label>

                                            <select  name="receive_by"  id="select2rec" style="width: 200px" required="">
                                            </select>

                                        </div>
                                    </div>


                                     

                                    <div class="col-md-4">
                                        <div class="form-groups">
                                            <label for="name" style="text-align:left;display:block">Amount </label>
                                            <input type="text" class="form-control" id="name" name="amount"
                                                placeholder="Enter name"
                                                onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-groups">
                                            <label for="name" style="text-align:left;display:block">Description </label>
                                                <textarea name="description" id="" cols="100%" rows="5" ></textarea>
                                        </div>
                                    </div>

                                    <button type='submit' class="btn btn-sm btn-success"> <strong>Submit</strong></button>
                                </div>
                            </form>


                    </div>
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
