@extends('MBCorporationHome.apps_layout.layout')
@section('List of Account')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">All List Of Account</h4>
            </div>
            <div class="card-body overflow-auto">
                <div class="mb-3 clearfix">
                    <a href="{{route('account_ledger_create')}}" class="btn btn-success">Add New</a>&nbsp;
                    <button type="button" id="search_btn" class="btn btn-info">Search</button>
                    @if(request()->search) 
                        <a href="{{route('account_ledger_list')}}" class="btn btn-danger">Clear</a>
                    @endif
                </div>

                <form action="{{route("account_ledger_list")}}" method="get" class="mb-3" id="search_form">
                    <input type="hidden" name="search" value="1">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="name" placeholder="Search Account Name..." class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Account Group</label>
                                <input type="text" name="group" placeholder="Search Account Group..." class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" id="" class="form-control">
                                    <option value="" hidden>Select Type</option>
                                    <option value="1">Debit</option>
                                    <option value="2">Credit</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </form>

                <table class="table table-resposive table-bordered">
                    <caption>{{$ledger_list->count()}} of {{$ledger_list->total()}} All List Of Account</caption>
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>#Id No</th>
                            <th>Account Name</th>
                            <th>Group Under</th>
                            <th>Opening Balance</th>
                            <th>Debit/Credit</th>
                            <th>Created By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ledger_list as $row)
                        <tr>
                            <td>{{$row->id}}</td>
                            <td>{{$row->account_name}}</td>
                            <td>{{ optional($row->accountGroupName)->account_group_name??'-' }}</td>
                            <td>{{ number_format($row->account_ledger_opening_balance, 2)}}</td>
                            <td>
                                @if($row->debit_credit < 2)
                                    Debit
                                @elseif($row->debit_credit > 1)
                                    Credit
                                @endif
                            </td>
                            <td> {{ optional($row->createdBy)->name??' ' }} </td>
                            <td>
                                <?php
                                    $i=0;
                                    $accoun_trans = App\AccountLedgerTransaction::where('account_ledger_id',$row->account_ledger_id)->get();
                                    foreach( $accoun_trans as $accoun_trans_row){
                                        $i++;
                                    };
                                ?>
                                <div class="dropdown">
                                    <button class="dropbtn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
                                    <div class="dropdown-content" style="z-index: 100;">
                                        <a href="{{URL::to('/edit_account_ledger/'.$row->account_ledger_id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                        @if($i < 2)
                                        <a href="javascript:void(0)" data-id="{{$row->id}}" class="btn btn-sm btn-danger delete_btn"><i class="fa fa-trash"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{$ledger_list->links()}}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('js')
<script>
    

    $(document).ready(function() {
        $('#search_form').hide();

        $('#search_btn').on("click", function(){
            $('#search_form').toggle('slow');
        });

        $('a.delete_btn').on('click', function(){
            var here = $(this);
            var url = "{{url('/delete_account_ledger')}}"+ '/' +$(this).data('id');
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
    });

</script>
@endpush
