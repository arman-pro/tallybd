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


                    <h4 class="card-title"
                        style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">
                        Add New Journal</h4><br>
                    <br>
                    <form action="{{ route('employee.journal.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>

                                        <td style="padding: 5px 5px;min-width: 400px;">
                                            <div class="row">
                                                <div class="col-md-2" style="text-align: right;padding-top: 5px;">Vo. No :
                                                </div>
                                                <div class="col-md-4">
                                                    @php
                                                    use App\EmployeeJournal;

                                                    $vo_no = App\Helpers\Helper::IDGenerator(new EmployeeJournal, 'vo_no', 6,
                                                    'EJo');

                                                    @endphp
                                                    <input type="text" class="form-control" name="vo_no" id="vo_no"
                                                        value="{{$vo_no}}" style="text-align: center;" readonly>
                                                    <input type="hidden" name="page_name" id="page_name" value="journal">
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;max-width: 500px;">
                                            <div class="row">

                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="date" id="date" class="form-control"  value='{{ date('Y-m-d') }}'/>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>


                            <div class="col-md-12" style="margin:0">
                                <table class="table" style="font-size: 12px;background: #f8f8f8;border-radius: 5px;" id="myTable">
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
                                                <select  name="employee_id" id="employee_id" class="employee_id" style="width: 200px" >
                                                </select>
                                            </td>
                                            <td>
                                                <select  name="account_name" id="account_name" class="account_name"  style="width: 200px" >
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
                                                <a onclick="addDemoContraJournal()" class="btn btn-sm btn-info"><i
                                                        class="fa fa-plus"></i></a>
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
                                <button type="submit" class="btn btn-success"
                                    style="color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                                <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                    </form>
                </div>
            </div>
            <div>

            </div>


@endsection

@push('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#employee_id").select2(

    {
        ajax: {
            url: '{{ url("employee") }}',
            dataType: 'json',
            type: "GET",
            data: function (params) {
                return {
                    name: params.term
                };
            },
            processResults: function (data) {

                var res = data.items.map(function (item) {
                        return {id: item.id, text: item.name};
                    });
                return {
                    results: res
                };
            }
        },

    });

    $(".account_name").select2(
    {
        ajax: {
            url: '{{ url("expenseLedger") }}',
            dataType: 'json',
            type: "GET",
            data: function (params) {
                return {
                    name: params.term
                };
            },
            processResults: function (data) {
                var res = data.items.map(function (item) {
                        return {id: item.id, text: item.account_name};
                    });
                return {
                    results: res
                };
            }
        },

    });

      function cleardata(){
        $('#account_name').val(null).trigger('change');
        $('#employee_id').val(null).trigger('change');
        $('#drcr').val('Select');
        $('#amount').val('');
        $('#note').val('');


      }

    function addDemoContraJournal(){
        let htmlData = '';
        // var date = $('#date').val();
        // var vo_no = $('#vo_no').val();
        // var page_name = $('#page_name').val();
        var account_id = $('#account_name').val();
        var account_name = $('#account_name option:selected').text();
        var employee_name = $('#employee_id option:selected').text();
        var employee_id = $('#employee_id').val();
        var drcrText = $('#drcr option:selected').text();
        var amount = $('#amount').val();
        var note = $('#note').val();
        if(employee_id && account_id){
            alert("You can't added at a time 2 Ledger!")
            cleardata();
            return false;
        }


        htmlData += "<tr class='item'>"
        htmlData += "<td  style='display:none'><input type='hidden' name='employee_id[]' value='"+employee_id+"'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;text-align: center'>" + employee_name + "</td>"

        htmlData += "<td  style='display:none'><input type='hidden' name='account_id[]' value='"+account_id+"'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;text-align: center'>" + account_name + "</td>"

        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='drcr_text' style='border:0;text-align:center' readonly  type ='text' name='drcr_text[]' value='"+drcrText+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='number' name='amount[]' value='"+amount+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'> <input style='border:0;text-align:center' readonly  type ='text' name='note[]' value='"+note+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
        htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
        htmlData += "</td>"
        htmlData +="</tr>";
        $('#myTable tr:last').after(htmlData)
        cleardata();



      }


    function delete_data(delelet){
        (delelet).closest('tr').remove();

    }


    function save_jornal(){
        // var vo_no = $('#vo_no').val();

        // $.ajax({
        //   type:"GET",
        //   dataType: "json",
        //   url:"{{url('/employee-journal/journal_add_new_field/-')}}"+vo_no,
        //         success:function(response){
        //             var debit = 0
        //             var credit = 0
        //             $.each(response, function(key, value){
        //               if (value.drcr < 2 ) {
        //                 debit = parseInt(debit) + parseInt(value.amount);
        //               }else{
        //                 credit = parseInt(credit) + parseInt(value.amount);
        //               }
        //             });
        //             var total = debit - credit;
        //             // console.log(debit, credit, total);
        //             if ( debit ==   credit ) {
        //                 var date = $('#date').val();
        //                 var page_name = $('#page_name').val();
        //                 var vo_no = $('#vo_no').val();
        //                 $.ajax({
        //                     type:"GET",
        //                     dataType:"json",
        //                     url:"{{url('/employee-journal/contra_journal_addlist/store/')}}",
        //                     data: {
        //                         date:date,
        //                         page_name:page_name,
        //                         vo_no:vo_no,
        //                         "_token": "{{ csrf_token() }}",
        //                     },
        //                     success:function(response){
        //                         window.location.href = "{{ route('employee.journal.index')}}";
        //                     },
        //                 });
        //             }else{
        //               alert("Your Dr Cr Not Equal! Dif ="+total);
        //             }
        //         }
        // })
    }
    $('form').on('submit', function(e){
        alert(23432)
        e.preventDefault();
        var debit = 0; var credit = 0;

        $('.drcr_text').each(function(index) {
            var  drcr_text = $(this).val();
            if (drcr_text == 'Dr') {
                debit+=parseInt($(this).closest('.item').find('input[type="number"]').val())
            }else if(drcr_text == 'Cr'){
                credit+=parseInt($(this).closest('.item').find('input[type="number"]').val())
            }
        })


        if(debit != credit){
            alert("Your Dr Cr Not Equal!");
            return false;
        }

        this.submit();

    });
</script>
@endpush
