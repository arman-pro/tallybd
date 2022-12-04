@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Journal List')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">Journal List</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{route('journa_addlist_form')}}" class="btn btn-success">Add New</a>
                    <button id="search_btn" type="button" class="btn btn-warning">Search</button>
                    @if(request()->has('date'))
                    <a href="{{route('journal_addlist')}}" class="btn btn-outline-danger">Clear</a>
                    @endif
                </div>
                <div id="search_form">
                    <form action="{{route("journal_addlist")}}" method="get">
                        <div class="form-group row">
                            <div class="col-md-4 col-sm-12">
                                <label>Date</label>
                                <input type="date" class="form-control" name="date" />
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label>Vch. No</label>
                                <input type="input" class="form-control" name="vch" placeholder="Vch. No" />
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label>Account Ledger</label>
                                <input type="input" class="form-control" name="ledger" placeholder="Account Ledger" />
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info">Search</button>
                        </div>
                    </form>
                </div>
                <table class="table table-bordered table-sm" id="table">
                    <caption>{{$Journal->count()}} of {{$Journal->total()}} Journal List</caption>
                    <thead class="bg-light text-dark">
                        <th>SL</th>
                        <th>Date</th>
                        <th>Vch.No</th>
                        <th>Account Ledger</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach($Journal as $key =>$journal_row)
                        <?php
                            $under_journal = App\DemoContraJournalAddlist::where('vo_no',$journal_row->vo_no)->with('ledger')->get();
                            $under_journal_count = App\DemoContraJournalAddlist::where('vo_no',$journal_row->vo_no)->with('ledger')->count();
                        ?>
                        <tr data-row="{{$under_journal_count}}">
                            <td rowspan="{{$under_journal_count}}" >{{ $key + 1 }}</td>
                            <td rowspan="{{$under_journal_count}}" >{{ date('d-m-y', strtotime($journal_row->date)) }}</td>
                            <td rowspan="{{$under_journal_count}}" >{{$journal_row->vo_no}}</td>
                            <td>
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
                            <td rowspan="{{$under_journal_count}}" class="text-center" >
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <b>Action</b>
                                    </button>
                                    <div class="dropdown-menu" style="margin: 0px;">         
                                        <a href="{{route('view_journal_recepet', ['vo_no' => $journal_row->vo_no])}}" class="dropdown-item"><i class="far fa-eye"></i> View</a>                              
                                        <a href="{{route("edit_journal_addlist",['id' => $journal_row->id])}}" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>
                                        <a href="javascript:void(0)" data-id="{{$journal_row->id}}" class="dropdown-item delete_btn"><i class="fa fa-trash"></i> Delete</a>
                                        <a target="_blank" href="{{route("print_journal_recepet", ['vo_no' => $journal_row->vo_no])}}" class="dropdown-item"><i class="fas fa-print"></i> Print</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @foreach ($under_journal as $key => $row)
                            @if($key > 0)
                            <tr data-row="-1">
                               
                                <td>
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
                                
                            </tr>
                            @endif
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{$Journal->links()}}
            </div>
        </div>
    </div>
<div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function(){

        // $('#table').DataTable({
        //     "ordering": false,
        //     "createdRow": function(row, data, dataIndex){
        //         let total_row = $(row).data('row');
        //         if(+total_row > 0) {
                    
        //                 $('td:eq(0)', row).attr('rowspan', total_row);
        //                 $('td:eq(1)', row).attr('rowspan', total_row);
        //                 $('td:eq(2)', row).attr('rowspan', total_row);
        //                 $('td:eq(6)', row).attr('rowspan', total_row);
                    
        //         } else {
                    
        //                 $('td:eq(0)', row).css('display', 'none');
        //                 $('td:eq(1)', row).css('display', 'none');
        //                 $('td:eq(2)', row).css('display', 'none');
        //                 $('td:eq(6)', row).css('display', 'none');
                    
        //         }
        //     },
        // });

        $(document).ready(function(){
            $('#search_btn').click(function(){
                $('#search_form').toggle();
            });
        });

        $('#search_form').hide();

        $('a.delete_btn').on('click', function(){
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
    })
    
</script>
@endpush
