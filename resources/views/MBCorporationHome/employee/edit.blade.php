@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Employee Information')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">Update Employee</h4>
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
                <form action="{{url('/employee/update', $employee->id)}}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{App\Companydetail::with('financial_year')->first()->financial_year->financial_year_from }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-groups">
                                <label for="name" style="text-align:left;display:block">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name" value="{{ $employee->name??'' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-groups">
                                <label for="Mobile" style="text-align:left;display:block">Mobile</label>
                                <input type="number" class="form-control" id="mobile" name="mobile"
                                    placeholder="Enter Mobile number" value="{{ $employee->mobile??'' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" style="text-align:left;display:block">Gmail</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $employee->email??'' }}"
                                    placeholder="Enter Gmail ">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="text" style="text-align:left;display:block">NID</label>
                                <input type="text" class="form-control" id="name" name="nid"
                                value="{{ $employee->nid??'' }}"
                                    placeholder="Enter NID ">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="text" style="text-align:left;display:block">Present Address</label>
                                <input type="text" class="form-control" id="name" name="present_address" value="{{ $employee->present_address??'' }}"
                                    placeholder="Enter present address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="text" style="text-align:left;display:block">Permanent
                                    Address</label>
                                <input type="text" class="form-control" id="name" name="permanent_address"
                                value="{{ $employee->permanent_address??'' }}"
                                    placeholder="Enter Permanent address ">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="text" style="text-align:left;display:block">Salary </label>
                                <input type="number" class="form-control" id="salary" name="salary"
                                value="{{ $employee->salary??'' }}"
                                    placeholder="Enter salary" </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date" style="text-align:left;display:block">Joining Date
                                </label>
                                <input type="date" class="form-control" id="joining_date" name="joining_date"
                                value="{{ $employee->joining_date??' ' }}"
                                placeholder="Enter salary">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-align:left;display:block" for="reference_id">Reference By
                                </label>
                                <select class="form-control" id="reference_id" name="reference_id">
                                    <option value="{{ null}}">- select -</option>
                                    @foreach ($employees as $row)
                                    <option value="{{ $row->id }}" {{ $row->id==$employee->reference_id ? 'Selected': ' ' }}>{{ $row->name??' ' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-align:left;display:block" for="department_id">Department
                                </label>
                                <select class="form-control" id="department_id" name="department_id">
                                    <option value="{{ null}}">- select -</option>
                                    @foreach ($departments as $row)
                                    <option value="{{ $row->id }}" {{ $row->id==$employee->department_id ? 'Selected': ' ' }}>{{ $row->name??' ' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-align:left;display:block" for="designation_id">Designation
                                </label>
                                <select class="form-control" id="designation_id" name="designation_id">
                                    <option value="{{ null}}">- select -</option>
                                    @foreach ($designations as $row)
                                    <option value="{{ $row->id }}" {{ $row->id==$employee->designation_id ? 'Selected': ' ' }}>{{ $row->name??' ' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-align:left;display:block" for="shift_id">Shift
                                </label>
                                <select class="form-control" id="shift_id" name="shift_id">
                                    <option value="{{ null}}">- select -</option>
                                    @foreach ($shifts as $row)
                                    <option value="{{ $row->id }}" {{ $row->id==$employee->shift_id ? 'Selected': ' ' }}>{{ $row->name??' ' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-align:left;display:block" for="status">Status
                                </label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="{{ null}}">- select  -</option>
                                    <option value="1" {{ $employee->status == true?'Selected': ' '  }}>Active</option>
                                    <option value="0" {{ $employee->status == false?'Selected': ' '  }}>Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="text-align:left;display:block" for="advance_amount">Advance Amount
                                </label>
                                <input type="text" class="form-control" id="advance_amount" name="advance_amount"
                                    placeholder="Enter advance amount" value="{{$employee->advance_amount??0 }}"
                                    onkeypress="return (event.charCode !=8 && event.charCode ==0 ||
                                        (event.charCode >= 48 && event.charCode <= 57))" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
