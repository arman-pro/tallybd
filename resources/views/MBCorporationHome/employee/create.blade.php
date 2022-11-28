@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; ">Employees Information</h4>
    </div>
</div>


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
                        <h4 class="card-title"
                            style=" font-weight: 800;text-align:center; padding-bottom: 10px; border-bottom: 2px solid #eee; background-color: #DC7633; padding: 5px; color: #fff;">
                            Add Employees</h4><br>

                        <form action="{{url('/employee/store')}}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{App\Companydetail::with('financial_year')->first()->financial_year->financial_year_from }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-groups">
                                        <label for="name" style="text-align:left;display:block">Name *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-groups">
                                        <label for="Mobile" style="text-align:left;display:block">Mobile *</label>
                                        <input type="number" class="form-control" id="mobile" name="mobile"
                                            placeholder="Enter Mobile number" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Gmail" style="text-align:left;display:block">Gmail</label>
                                        <input type="email" class="form-control" id="Gmail" name="email"
                                            placeholder="Enter Gmail ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text" style="text-align:left;display:block">NID</label>
                                        <input type="text" class="form-control" id="name" name="nid"
                                            placeholder="Enter NID ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text" style="text-align:left;display:block">Present Address</label>
                                        <input type="text" class="form-control" id="name" name="present_address"
                                            placeholder="Enter present address ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text" style="text-align:left;display:block">Permanent
                                            Address</label>
                                        <input type="text" class="form-control" id="name" name="permanent_address"
                                            placeholder="Enter Permanent address ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text" style="text-align:left;display:block">Salary </label>
                                        <input type="text" class="form-control" id="salary" name="salary"
                                            placeholder="Enter salary"
                                            onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date" style="text-align:left;display:block">Joining Date
                                        </label>
                                        <input type="date" class="form-control" id="joining_date" name="joining_date"
                                            placeholder="Enter salary ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="text-align:left;display:block" for="reference_id">Reference By
                                        </label>
                                        <select class="form-control" id="reference_id" name="reference_id">
                                            <option value="{{ null}}">- select -</option>
                                            @foreach ($employees as $row)
                                            <option value="{{ $row->id }}">{{ $row->name??' ' }}</option>
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

                                            <option value="{{ $row->id }}">{{ $row->name??' ' }}</option>
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
                                            <option value="{{ $row->id }}">{{ $row->name??' ' }}</option>
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
                                            <option value="{{ $row->id }}">{{ $row->name??' ' }}</option>
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
                                            <option value="1" selected>Active</option>
                                            <option value="0">Deactive</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label style="text-align:left;display:block" for="advance_amount">Advance Amount
                                        </label>
                                        <input type="text" class="form-control" id="advance_amount" name="advance_amount"
                                            placeholder="Enter advance amount"
                                            onkeypress="return (event.charCode !=8 && event.charCode ==0 ||
                                             (event.charCode >= 48 && event.charCode <= 57))" />
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success" style="color: #fff;">Add Employees</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div>

        </div>
        @endsection
        
@push('js')
<script>
    $(document).ready(function(){
        $('#reference_id').select2();
    })
</script>
@endpush
