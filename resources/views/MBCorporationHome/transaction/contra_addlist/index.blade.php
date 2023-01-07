@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Contra List')
@section('admin_content')

<div class="container-fluid">
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">
                    All List Of Contra
                </h4>
            </div>
            <div class="card-body overflow-auto">
                <div class="mb-3">
                    <a href="{{route('contra_addlist_form')}}" class="btn btn-success">Add New</a>
                    <button id="search_btn" type="button" class="btn btn-warning">Search</button>
                    @if(request()->has('date'))
                    <a href="{{route('contra_addlist')}}" class="btn btn-outline-danger">Clear</a>
                    @endif
                </div>
                <div id="search_form">
                    <form action="{{route("contra_addlist")}}" method="get">
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

                @if(Session::has('message'))
                <p class="alert alert-info">{{ Session::get('message') }}</p>
                @endif
                <table class="table table-sm table-bordered">
                    <caption>{{$Journal->count()}} of {{$Journal->total()}} Contra List</caption>
                   <thead class="heighlightText" style="background-color: #D6DBDF;">
                        <th>Date</th>
                        <th>Vch. No</th>
                        <th>Account Ledger</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($Journal as $journal_row)
                        <tr style="text-align: center;">
                            <td>{{ date('d-m-Y', strtotime($journal_row->date)) }}</td>
                            <td>{{$journal_row->vo_no}}</td>
                            <td colspan="3">
                                <table class="table" style="font-size: 12px;">
                                    @php
                                    $under_journal =
                                    App\DemoContraJournalAddlist::where('vo_no',$journal_row->vo_no)->with('ledger')->get();
                                    @endphp

                                    @foreach ($under_journal as $row)
                                    <tr>
                                        <td style="text-align: center;">
                                            {{ optional($row->ledger)->account_name ??' '}}
                                        </td>
                                        @if($row->drcr == 'Dr') <td style="text-align: right;">{{new_number_format($row->amount)}}
                                        </td>
                                        @else
                                        <td style="text-align: right;">-</td>
                                        @endif
                                        @if($row->drcr == 'Cr')
                                        <td style="text-align: right;">{{new_number_format($row->amount)}}</td>
                                        @else
                                        <td style="text-align: right;">-</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <b>Action</b>
                                    </button>
                                    <div class="dropdown-menu" style="margin: 0px;">         
                                        <a href="{{route('view_contra_recepet', ['vo_no' => $journal_row->id])}}" class="dropdown-item"><i class="far fa-eye"></i> View</a>                              
                                        <a href="{{route("edit_contra_addlist",['id' => $journal_row->id])}}" class="dropdown-item"><i class="far fa-edit"></i> Edit</a>
                                        <a href="javascript:void(0)" data-id="{{$journal_row->id}}" class="dropdown-item delete_btn text-danger"><i class="fa fa-trash"></i> Delete</a>
                                        <a target="_blank" href="{{route("print_contra_recepet", ['vo_no' => $journal_row->id])}}" class="dropdown-item"><i class="fas fa-print"></i> Print</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
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
        $('#search_btn').click(function(){
            $('#search_form').toggle();
        });
    });

    $('#search_form').hide();

    $('a.delete_btn').on('click', function(){
        var here = $(this);
        var url = "{{url('/delete_contra_addlist')}}"+ '/' +$(this).data('id');

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

