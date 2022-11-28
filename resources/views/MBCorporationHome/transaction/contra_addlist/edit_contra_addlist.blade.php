@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; "> Contra List</h4>
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

                    @foreach($contra as $contra_row)

                    <form action=" {{URL::to('/Update/Contra/'.$contra_row->id)}} " method="POST">
                        @csrf
                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;text-align: center;">
                            Update Contra</h4><br>
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
                                                        value="{{$contra_row->vo_no}}" style="text-align: center;"
                                                        readonly>
                                                    <input type="hidden" name="page_name" id="page_name" value="contra">
                                                </div>
                                            </div>
                                        </td>
                                        {{-- @dd($contra_row) --}}
                                        <td
                                            style="border-right: 1px solid #eee;padding: 5px 5px;min-width: 400px;max-width: 500px;">
                                            <div class="row">
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4" style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="date" id="date" class="form-control"
                                                        value="{{ $contra_row->date }}"
                                                        />
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>


                            <div class="col-md-10" style="margin: 0 5%;">
                                <table class="table" style=";font-size: 12px;background: #f8f8f8;border-radius: 5px;" >
                                    <thead style="height: 10px;">
                                        <th style="text-align: center;">Account Ledger</th>
                                        <th style="text-align: center;">Dr/Cr</th>
                                        <th style="text-align: center;">Amount</th>
                                        <th style="text-align: center;">Note</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-control"
                                                    style="height: 30px;text-align: center;min-width:300px;font-size: 12px;"
                                                    id="account_name">
                                                    @php
                                                    $account_ladger_name = App\AccountLedger::where('payment', true)->get(['id', 'account_name']);
                                                    @endphp
                                                    <option>Select</option>
                                                    @foreach($account_ladger_name as $account_ladger_name_row)
                                                    <option value="{{ $account_ladger_name_row->id }}">{{
                                                        $account_ladger_name_row->account_name }}</option>
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
                                                <a onclick="addDemoContraJournal()" class="btn btn-sm btn-info"><i
                                                        class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                                <table class="table" style=";font-size: 12px;background: #f8f8f8;" id="myTable">
                                    <tbody id="data_add_for_list">
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            @endforeach


                            <br>
                            <br>
                            <br>
                            <div style="text-align: center; color: #fff; font-weight: 800;">
                                <button type="submit" class="btn btn-primary" style="color:#fff; font-weight: 800;font-size: 18px;">Update</button>
                                <button type="submit" name="print" value='1' class="btn btn-info" style="color:#fff; font-weight: 800;font-size: 18px;">Update & Print</button>
                                <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                            </div>


                </div>
            </div>
        </div>

        </form>
        @if($mes>1)
        <script type="text/javascript">
            alert("your dr cr not =");
        </script>
        @endif

    @endsection

@push('js')
<script>
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
            let htmlData = '';

            var account_id = $('#account_name').val();
            var account_name = $('#account_name option:selected').text();
            var drcr = $('#drcr').val();
            var drcrText = $('#drcr option:selected').text();
            var amount = $('#amount').val();
            var note = $('#note').val();
            htmlData += "<tr class='item'>"
            htmlData += "<td  style='display:none'><input type='hidden' name='new_account_id[]' value='"+account_id+"'/> </td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;text-align: center'>" + account_name + "</td>"
            htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='drcr_text' style='border:0;text-align:center' readonly  type ='text' name='new_drcr_text[]' value='"+drcrText+"' /></td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='number' name='new_amount[]' value='"+amount+"' /> </td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'> <input style='border:0;text-align:center' readonly  type ='text' name='new_note[]' value='"+note+"' /></td>"
            htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
            htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
            htmlData += "</td>"
            htmlData +="</tr>";

            $('#myTable tbody tr:last').after(htmlData)
            cleardata();
        }


      //----------------------------Start newProduct----------------------------------------
    function newProduct(){
        var vo_no = $('#vo_no').val();
        var data_add_for_list = $('#data_add_for_list').val();
        htmlData='';
        $.ajax({
          type:"GET",
          dataType: "json",
          url:"{{url('/contra_journaladd_new_fild/-')}}"+vo_no,

              success:function(response){

                    var data =""

                    var debit = ""
                    var credit = ""

                    $.each(response, function(key, value){

                        htmlData += "<tr class='item'>"
                        htmlData += "<td  style='display:none'><input type='hidden' name='id[]' value='"+value.id+"'/> </td>"
                        htmlData += "<td  style='display:none'><input type='hidden' name='account_id[]' value='"+value.ledger_id+"'/> </td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;text-align: center'>" + value.ledger.account_name + "</td>"
                        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='drcr_text' style='border:0;text-align:center' readonly  type ='text' name='drcr_text[]' value='"+value.drcr+"' /></td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='number' name='amount[]' value='"+value.amount+"' /> </td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'> <input style='border:0;text-align:center' readonly  type ='text' name='note[]' value='"+value.note+"' /></td>"
                        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
                        htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
                        htmlData += "</td>"
                        htmlData +="</tr>";

                    });


                     $('#data_add_for_list').html(htmlData);
              }
        })
      }

      newProduct();
    //----------------------------End newProduct----------------------------------------
    function delete_data(delelet){

        (delelet).closest('tr').remove();

    }


    $('form').on('submit', function(e){
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


    //    alert(debit,'debit' ,credit, 'credit');
        if(debit != credit){
            alert("Your Dr Cr Not Equal!");
            return false;
        }

        this.submit();

    });
</script>

@endpush
