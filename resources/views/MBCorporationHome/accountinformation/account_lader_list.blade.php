@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
              	<div class="card-body" style="overflow-x:auto;">
              		<h4 class="card-title" style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">All List of Account </h4>
              		<a href="{{route('account_ledger_create')}}" class="btn btn-success" style="color:#fff; float: right;">+Add New</a>
                    <br>
                    <br>


              		<table class="table table-resposive table-bordered" id="example">
                    	<thead style="background-color: #566573;text-align: center;">
                    		<th style="color: #fff;"># Id No</th>
                    		<th style="color: #fff;">Account Name</th>
                            <th style="color: #fff;">Group Under</th>
                            <th style="color: #fff;">Opening Balance </th>
                    		<th style="color: #fff;">Debit/Credit </th>
                    		<th style="color: #fff;">Created By</th>
                    		<th style="color: #fff;">Action</th>

                    	</thead>
                    	<tbody>
                            @foreach($ledger_list as $row)
                    		<tr style="text-align: center;">
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
                                    @php
                                        $i=0;
                                        $accoun_trans = App\AccountLedgerTransaction::where('account_ledger_id',$row->account_ledger_id)->get();
                                        foreach( $accoun_trans as $accoun_trans_row){
                                            $i++;
                                        };
                                    @endphp
                                    <div class="dropdown">
                                <button class="dropbtn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
                                <div class="dropdown-content">
                    				<a href="{{URL::to('/edit_account_ledger/'.$row->account_ledger_id)}}" class="btn btn-sm btn-primary"><i class="far fa-edit"></i></a>
                                    @if($i < 2)
                        			<a href="#" data-id="{{$row->id}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                                    {{-- <a href="{{URL::to('/delete_account_ledger/'.$row->id)}}"  class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a> --}}
                                    @endif
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
@push('js')
<script>
    $('a.btn-danger').on('click', function(){
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
</script>
@endpush
