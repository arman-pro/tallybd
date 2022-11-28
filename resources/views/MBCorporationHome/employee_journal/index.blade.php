@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
	<div class="card-body">
		<h4 class="card-title" style=" font-weight: 800; ">Journal List</h4>
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
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Journal</h4>
              		<a href="{{route('employee.journal.create')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a><br><br>

                    <br>
                    <br>
                    <br>
              		<table class="table table-resposive table-bordered" id="example">
                    	<thead style="background-color: #566573;text-align: center;">
                          <th style="color: #fff;">Date</th>
                          <th style="color: #fff;">V.No</th>
                    		  <th style="color: #fff;">Account Lager</th>
                          <th style="color: #fff;">Debit</th>
                          <th style="color: #fff;">Credit</th>
                    		  <th style="color: #fff;">Action</th>
                    	</thead>
                    	<tbody>
                        @foreach($Journal as $journal_row)
                        <tr style="text-align: center;">
                            <td >{{ date('d-m-Y', strtotime($journal_row->date)) }}</td>
                            <td>{{$journal_row->vo_no}}</td>
                            <td colspan="3">
                              <table class="table" style="font-size: 12px;">
                                @php
                                  $under_journal = App\EmployeeJournalDetails::where('vo_no',$journal_row->vo_no)->with('ledger')->get();

                                @endphp
                                    @foreach ($under_journal as $row)
                                        <tr>
                                            @if($row->ledger)
                                            <td style="text-align: center;">
                                                {{ optional($row->ledger)->account_name ??' '}}
                                            </td>
                                            @else
                                            <td style="text-align: center;">
                                                {{ optional($row->employee)->name ??' '}}
                                            </td>
                                            @endif
                                            @if($row->drcr < 2)
                                                    <td style="text-align: right;">{{$row->amount}} </td>
                                            @else
                                                    <td style="text-align: right;">-</td>
                                            @endif
                                            @if($row->drcr > 1)
                                                    <td style="text-align: right;">{{$row->amount}}</td>
                                            @else
                                                    <td style="text-align: right;">-</td>
                                            @endif
                                        </tr>

                                    @endforeach

                              </table>
                            </td>
                            <td>
                              {{-- <a href="{{URL::to('/view_journal_recepet/'.$journal_row->vo_no)}}" class="btn btn-sm btn-success" style="color: #fff;"><i class="far fa-eye"></i></a> --}}
                              <a href="{{action('MBCorporation\EmployeeJournalController@edit',$journal_row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                              <a href="{{URL::to('/delete_journal_addlist/'.$journal_row->vo_no)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                              <a target="_blank" href="{{URL::to('/print_journal_recepet/'.$journal_row->vo_no)}}" class="btn btn-sm btn-info" style="color: #fff;"><i class="fas fa-print"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    	</tbody>
                  </table>
                </div>
              </div>
            </div>
        <div>

</div>
@endsection

