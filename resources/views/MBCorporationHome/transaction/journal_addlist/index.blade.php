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
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Journal</h4>
              		<a href="{{route('journa_addlist_form')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a><br><br>


              		<table class="table table-resposive table-bordered" id="table">
                    	<thead style="background-color: #566573;text-align: center;">
                    	    <th style="color: #fff;">SL</th>
                          <th style="color: #fff;">Date</th>
                          <th style="color: #fff;">Vch.No</th>
                    	    <th style="color: #fff;">Account Lager</th>
                          <th style="color: #fff;">Debit</th>
                          <th style="color: #fff;">Credit</th>
                    		  <th style="color: #fff;">Action</th>
                    	</thead>
                    	<tbody>
                        @foreach($Journal as $key =>$journal_row)
                        @php
                          $under_journal = App\DemoContraJournalAddlist::where('vo_no',$journal_row->vo_no)->with('ledger')->get();
                          $under_journal_count = App\DemoContraJournalAddlist::where('vo_no',$journal_row->vo_no)->with('ledger')->count();
                        @endphp
                        <tr data-row="{{$under_journal_count}}" style="text-align: center;">
                            <td  >{{ $key + 1 }}</td>
                            <td  >{{ date('d-m-y', strtotime($journal_row->date)) }}</td>
                            <td  >{{$journal_row->vo_no}}</td>
                            <td style="text-align: center;">
                                {{ optional($under_journal[0]->ledger)->account_name ??' '}}
                            </td>
                            @if($under_journal[0]->drcr == 'Dr')
                                    <td style="text-align: right;">{{new_number_format($under_journal[0]->amount ?? 0)}} </td>
                            @else
                                    <td style="text-align: right;">-</td>
                            @endif
                            @if($under_journal[0]->drcr =='Cr')
                                    <td style="text-align: right;">{{new_number_format($under_journal[0]->amount ?? 0)}}</td>
                            @else
                                    <td style="text-align: right;">-</td>
                            @endif
                            <td >
                                <div class="dropdown">
                                    <button class="dropbtn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
                                    <div class="dropdown-content">
                                        <a href="{{URL::to('/view_journal_recepet/'.$journal_row->vo_no)}}" class="btn btn-sm btn-success" style="color: #fff;"><i class="far fa-eye"></i></a>
                                        <a href="{{URL::to('/edit_journal_addlist/'.$journal_row->id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                        {{-- <a href="{{URL::to('/delete_journal_addlist/'.$journal_row->id)}}" onclick="alert('Do You want to delete?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a> --}}
                        			    <a href="#" data-id="{{$journal_row->id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                        <a target="_blank" href="{{URL::to('/print_journal_recepet/'.$journal_row->vo_no)}}" class="btn btn-sm btn-info" style="color: #fff;"><i class="fas fa-print"></i></a>

                                    </div>
                                </div>
                             </td>
                        </tr>
                        @foreach ($under_journal as $key => $row)
                            @if($key > 0)
                            <tr data-row="-1">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: center;">
                                    {{ optional($row->ledger)->account_name ??' '}}
                                </td>
                                @if($row->drcr == 'Dr')
                                        <td style="text-align: right;">{{new_number_format($row->amount ?? 0)}} </td>
                                @else
                                        <td style="text-align: right;">-</td>
                                @endif
                                @if($row->drcr =='Cr')
                                        <td style="text-align: right;">{{new_number_format($row->amount ?? 0)}}</td>
                                @else
                                        <td style="text-align: right;">-</td>
                                @endif
                                <td></td>
                            </tr>
                            @endif
                        @endforeach
                        @endforeach
                    	</tbody>
                  </table>
                  
                  {{-- 
                  <table class="table" style="font-size: 12px;">
                                
                                    @foreach ($under_journal as $row)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{ optional($row->ledger)->account_name ??' '}}
                                            </td>
                                            @if($row->drcr == 'Dr')
                                                    <td style="text-align: right;">{{$row->amount}} </td>
                                            @else
                                                    <td style="text-align: right;">-</td>
                                            @endif
                                            @if($row->drcr =='Cr')
                                                    <td style="text-align: right;">{{$row->amount}}</td>
                                            @else
                                                    <td style="text-align: right;">-</td>
                                            @endif
                                        </tr>
                                    @endforeach

                              </table>
                              --}}
                </div>
              </div>
            </div>
        <div>

</div>
@endsection

@push('js')
<script>
    $(document).ready(function(){
        $('#table').DataTable({
            "ordering": false,
            "createdRow": function(row, data, dataIndex){
                console.log(dataIndex);
                let total_row = $(row).data('row');
                if(+total_row > 0) {
                    
                        $('td:eq(0)', row).attr('rowspan', total_row);
                        $('td:eq(1)', row).attr('rowspan', total_row);
                        $('td:eq(2)', row).attr('rowspan', total_row);
                        $('td:eq(6)', row).attr('rowspan', total_row);
                    
                } else {
                    
                        $('td:eq(0)', row).css('display', 'none');
                        $('td:eq(1)', row).css('display', 'none');
                        $('td:eq(2)', row).css('display', 'none');
                        $('td:eq(6)', row).css('display', 'none');
                    
                }
            },
            "lengthMenu": [[10, 5, 15, 25, 50, -1], [10,5,15, 25, 50, "All"]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print','pageLength'
                ],
        });
    })
    $('a.btn-danger').on('click', function(){
    var here = $(this);
    var url = "{{url('/delete_journal_addlist')}}"+ '/' +$(this).data('id');

    $.confirm({
            icon: 'fa fa-spinner fa-spin',
            title: 'Delete this?',
            theme: 'material',
            type: 'orange',
            closeIcon: true,
            animation: 'scale',
            content: 'This dialog will automatically trigger \'cancel\' in 6 seconds if you don\'t respond.',
            autoClose: 'cancelAction|8000',
            buttons: {
                deleteUser: {
                    text: 'delete data',
                    action: function () {
                        $.get(url, function(data){
                            if(data.status == true){
                                here.closest('tr').remove();
                            }
                            $.alert(data.mes);

                        });
                    }
                },
                cancelAction: function () {
                    $.alert('This action is canceled.');
                }
            }
        });
    });
</script>
@endpush
