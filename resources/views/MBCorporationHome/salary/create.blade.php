@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Salary Generate")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">Salary Generate</h4>
                </div>
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
                    <form action="{{url('salary/search-employee')}}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="text-align:left;display:block" for="designation_id">Designation
                                    </label>
                                    <select class="form-control" id="designation_id" name="designation_id" >
                                        <option value="" hidden>Select Designation</option>
                                        @foreach ($designations as $row)
                                        <option value="{{ $row->id }}"{{ request()->designation_id == $row->id?'Selected': ' '  }}>{{ $row->name??' ' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="text-align:left;display:block" for="department_id">Deparment *
                                    </label>
                                    <select class="form-control" id="department_id" name="department_id" required>
                                        <option value="" hidden>Select Department</option>
                                        @foreach ($departments as $row)
                                        <option value="{{ $row->id }}"{{ request()->department_id == $row->id?'Selected': ' '  }}>{{ $row->name??' ' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label style="text-align:left;display:block" for="shift_id">Shift *
                                    </label>
                                    <select class="form-control" id="shift_id" name="shift_id" >
                                        <option value="" hidden>Select Shift</option>
                                        @foreach ($shifts as $row)
                                        <option value="{{ $row->id }}" {{ request()->shift_id == $row->id?'Selected': ' '  }}>{{ $row->name??' ' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label style="text-align:left;display:block" for="">&nbsp;
                                    </label>
                                    <button type="submit" class="btn btn-success" style="color: #fff;"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form   action="{{url('salary/store')}}" method="POST">
                        @csrf
                        @php
                        use App\Salary;
                        $vo_no = App\Helpers\Helper::IDGenerator(new Salary, 'vo_no', 4,'Sa');
                        @endphp
                        <input type="hidden"  name="department_id" value="{{ request()->department_id??null }}">
                        <input type="hidden"  name="designation_id" value="{{ request()->department_id??null }}">
                        <input type="hidden"  name="shift_id" value="{{ request()->shift_id??null }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="text" style="text-align:left;display:block">Vch No </label>
                                    <input type="text" class="form-control" id="vo_no" name="vo_no"required
                                        placeholder="Enter vo_no" value="{{ $vo_no }}" readonly/>
                                </div>
                            </div>
                            <div class="col-md-3 heighlightText">
                                <div class="form-group">
                                    <label for="salary_date" style="text-align:left;display:block"> Date *
                                    </label>

                                    <input type="date" class="form-control" id="salary_date" name="salary_date"
                                        placeholder="Enter salary "required >
                                </div>
                            </div>
                            <div class="col-md-4 heighlightText">
                                <div class="form-group">
                                    <label style="text-align:left;display:block" for="payment_by">Processing By *
                                    </label>
                                    <select class="form-control" id="payment_by" name="payment_by" required>
                                        <option value="" hidden>Select Processing</option>
                                        @foreach ($ledgers as $id=>$data)
                                        <option value="{{ $id }}">{{ $data??' ' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="text" style="text-align:left;display:block" for="">Employee </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="text" style="text-align:left;display:block">Salary </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="text" style="text-align:left;display:block">Workign Day </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="text" style="text-align:left;display:block">Processing </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="text" style="text-align:left;display:block">Action </label>
                                </div>
                            </div>
                            @forelse ($employees as $employee)
                            <div class="employee-row row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="employee_salary" name="employee_salary[]"
                                        value="{{$employee->name}}"
                                            placeholder="Enter employee name" readonly />
                                    </div>
                                </div>
                                <input type="hidden" name="employee_id[]" id="employee_id" value="{{ $employee->id }}">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="number" class="form-control" id="default_salary"
                                            placeholder="Enter default salary" value="{{ $employee->salary }}" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="number" class="form-control" id="day" name="day[]"required
                                            placeholder="Enter days" value="30" min="1" max="31" onkeypress="return (event.charCode !=8 && event.charCode ==0 ||
                                            (event.charCode >= 48 && event.charCode <= 57))" />
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="number" class="form-control" id="salary" name="salary[]"required
                                            placeholder="Enter salary" value="0"  onkeypress="return (event.charCode !=8 && event.charCode ==0 ||
                                            (event.charCode >= 48 && event.charCode <= 57))" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-window-close" aria-hidden="true"></i> </button>
                                    </div>
                                </div>
                            </div>
                            @empty

                            @endforelse
                            <button type="submit" class="btn btn-success" style="color: #fff;">Add Salary</button>
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
    // $('#employee_id').change(function(){
    //     var employee_id = $(this).val();
    //     $.ajax({
    //         type:"get",
    //         dataType:"json",
    //         url:"{{url('salary/search')}}",
    //         data: {
    //             employee_id:employee_id,
    //             "_token": "{{ csrf_token() }}",
    //         },

    //         success:function(res){
    //             if(res.salary > 0){
    //                 $('#salary').val(res.salary);
    //             }else{
    //                 $('#salary').val(0);
    //             }
    //         }
    //     });
    // });
</script>

@endpush
