@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; "> Journal List</h4>
    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">


                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ action('MBCorporation\EmployeeJournalController@update', $journal->id) }}" method="POST">
                        @csrf
                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">
                            Update Journal</h4><br>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>

                                        <td style="padding: 5px 5px;min-width: 400px;">
                                            <div class="row">
                                                <div class="col-md-2" style="text-align: right;padding-top: 5px;">Vo. No
                                                    :</div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="vo_no" id="vo_no"
                                                        value="{{ $journal->vo_no }}" style="text-align: center;"
                                                        readonly>
                                                    <input type="hidden" name="page_name" id="page_name" value="contra">
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;max-width: 500px;">
                                            <div class="row">
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="date" id="date" class="form-control"
                                                        value="{{ $journal->date }}" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-12" style="margin:0">
                                <table class="table" style="font-size: 12px;background: #f8f8f8;border-radius: 5px;">
                                    <thead style="height: 10px;">
                                        <th style="text-align: center;">Account Ledger</th>
                                        <th style="text-align: center;">Expense Ledger</th>
                                        <th style="text-align: center;">Dr/Cr</th>
                                        <th style="text-align: center;">Amount</th>
                                        <th style="text-align: center;">Note</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control"
                                                    style="height: 30px;text-align: center;min-width:200px;font-size: 12px;"
                                                    id="employee_id">
                                                    @php
                                                    $account_ladger_name = App\Employee::get(['id', 'name']);
                                                    @endphp
                                                    <option value="{{ null }}">Select</option>
                                                    @foreach($account_ladger_name as $account_ladger_name_row)
                                                    <option value="{{ $account_ladger_name_row->id }}">{{
                                                        $account_ladger_name_row->name }}</option>
                                                    @endforeach

                                                </select>
                                            </td>

                                            <td>
                                                <select class="form-control"
                                                    style="height: 30px;text-align: center;min-width:200px;font-size: 12px;"
                                                    id="account_name">
                                                    <option  value="{{ null }}">Select</option>
                                                    @foreach($ledgers as $key=>$account_ladger_name_row)
                                                    <option value="{{ $key }}">{{
                                                        $account_ladger_name_row }}</option>
                                                    @endforeach

                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control"
                                                    style="height: 30px;text-align: center;min-width:100px;" id="drcr">
                                                    <option>Select</option>
                                                    <option value="1">Dr</option>
                                                    <option value="2">Cr</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="" id="amount" class="form-control"
                                                    style="height: 30px;text-align: center;">
                                            </td>
                                            <td>
                                                <input type="text" name="" id="note" class="form-control"
                                                    style="height: 30px;text-align: center;">
                                            </td>
                                            <td>
                                                <a onclick="addDemoContraJournal()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                                <table class="table" style=";font-size: 12px;background: #f8f8f8;">
                                    <tbody id="data_add_for_list">

                                    </tbody>

                                </table>
                            </div>






                            <br>
                            <br>
                            <br>
                            <div style="text-align: center; color: #fff; font-weight: 800;">
                                <button type="submit" class="btn btn-primary"
                                    style="color:#fff; font-weight: 800;font-size: 18px;">Update</button>
                                <a href="{{ route('mb_cor_index') }}" class="btn btn-danger">Cencel</a>
                            </div>
                            <br>
                            <br>
                            <br>
                    </form>
                </div>
            </div>
        </div>

    </form>


@endsection

@push('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


      function cleardata(){
        $('#account_name').val('Select');
        $('#drcr').val('Select');
        $('#amount').val('');
        $('#note').val('');


      }

    function addDemoContraJournal(){
        var date = $('#date').val();
        var vo_no = $('#vo_no').val();
        var page_name = $('#page_name').val();
        var account_name = $('#account_name').val();
        var employee_id = $('#employee_id').val();
        var drcr = $('#drcr').val();
        var amount = $('#amount').val();
        var note = $('#note').val();

        $.ajax({

            type:"GET",
            dataType:"json",
            url:"{{url('/employee-journal/employee_journal_list/store/')}}",
            data: {
                vo_no:vo_no,
                date:date,
                page_name:page_name,
                account_name:account_name,
                employee_id:employee_id,
                drcr:drcr,
                amount:amount,
                note:note,
                "_token": "{{ csrf_token() }}",

            },
            success:function(response){
                cleardata();
                newProduct();
            },

        })

      }


      //----------------------------Start newProduct----------------------------------------
    function newProduct(){
        var vo_no = $('#vo_no').val();
        var data_add_for_list = $('#data_add_for_list').val();
        console.log(vo_no, 'vo_no');
        $.ajax({
          type:"GET",
          dataType: "json",
          url:"{{url('/employee-journal/journal_add_new_field/-')}}"+vo_no,

                success:function(response){
                    var data =""
                    var debit = ""
                    var credit = ""

                    $.each(response, function(key, value){
                    data = data + "<tr>";

                    if (value.employee_id) {

                    data = data + "<td><input type='text' class='form-control' value='"+value.employee.name+"' style='text-align: center;' readonly></td>"
                    }else if(value.ledger_id) {
                        data += "<td><input type='text' class='form-control' value='"+value.ledger.account_name+"' style='text-align: center;' readonly></td>"
                    }
                    data+="<input type='hidden' name='journal_details_id[]' value='"+ value.id +"'>"
                    data+="<input type='hidden' name='ledger_id[]' value='"+value.ledger_id+"'> "
                    data+="<input type='hidden' name='employee_id[]' value='"+value.employee_id+"'> "


                    if (value.drcr<2) {
                    data = data + "<td ><input type='text' name='drcr[]' value='Dr'  class='form-control' style='text-align: center;' readonly></td>"
                    }else{
                    data = data + "<td ><input type='text' name='drcr[]' value='Cr' class='form-control'  style='text-align: center;' readonly></td>"
                    }
                    if (value.drcr<2) {
                    data = data + "<td ><input type='number' name='amount[]' class='form-control dramount' value='"+value.amount+"' style='text-align: center;' readonly></td>"
                    }else{
                    data = data + "<td ><input type='number' name='amount[]' class='form-control crmount' value='"+value.amount+"' style='text-align: center;' readonly></td>"
                    }

                    data = data + "<td><input type='text' class='form-control' name='note[]' value='"+value.note+"' style='text-align: center;' readonly></td>"

                    data = data + "<td style='text-align: center; width:50px;'>"
                    data = data +"<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
                    data = data+"</td>"

                    data = data + "</tr>";

                    if (value.drcr<2) {
                        debit = debit + value.amount
                    }else{
                        credit = credit + value.amount
                    }


                    });


                     $('#data_add_for_list').html(data);
                }
        })
      }

      newProduct();
    //----------------------------End newProduct----------------------------------------
    function delete_data(delelet){
        (delelet).closest('tr').remove();
        // console.log((delelet).closest('tr'));;
        // $.ajax({
        //     type:"GET",
        //     dataType: "json",
        //     url:"{{url('/employee-journal/employee_democontrajournal_delete_fild/-')}}"+id_row,

        //         success:function(response){
        //         $.each(response, function(key, value){
        //             cleardata();
        //         })

        //         }
        //     })

        // newProduct();
    }

    $('form').on('submit', function(e){
        e.preventDefault();

        var debit = 0; var credit = 0;
        $('.dramount').map(function(i ,e ) {
             debit+=parseInt(e.value);
        }).get();
        $('.crmount').map(function(i ,e ) {
            credit+=parseInt(e.value);
        }).get();
        if(debit != credit){
            alert("Your Dr Cr Not Equal! Dif");
            return false;
        }
        this.submit();

    });

    // function save_jornal(){
    //     var vo_no = $('#vo_no').val();

    //     $.ajax({
    //       type:"GET",
    //       dataType: "json",
    //       url:"{{url('/employee-journal/journal_add_new_field/-')}}"+vo_no,
    //             success:function(response){
    //                 var debit = 0
    //                 var credit = 0
    //                 $.each(response, function(key, value){
    //                   if (value.drcr < 2 ) {
    //                     debit = parseInt(debit) + parseInt(value.amount);
    //                   }else{
    //                     credit = parseInt(credit) + parseInt(value.amount);
    //                   }
    //                 });
    //                 // var total = debit - credit;
    //                 // console.log(debit, credit, total);
    //                 if ( debit ==   credit ) {
    //                     var date = $('#date').val();
    //                     var page_name = $('#page_name').val();
    //                     var vo_no = $('#vo_no').val();
    //                     $.ajax({
    //                         type:"GET",
    //                         dataType:"json",
    //                         url:"{{url('/employee-journal/contra_journal_addlist/store/')}}",
    //                         data: {
    //                             date:date,
    //                             page_name:page_name,
    //                             vo_no:vo_no,
    //                             "_token": "{{ csrf_token() }}",
    //                         },
    //                         success:function(response){
    //                             window.location.href = "{{ route('employee.journal.index')}}";
    //                         },
    //                     });
    //                 }else{
    //                   alert("Your Dr Cr Not Equal! Dif ="+total);
    //                 }
    //             }
    //     })
    // }
</script>
@endpush
