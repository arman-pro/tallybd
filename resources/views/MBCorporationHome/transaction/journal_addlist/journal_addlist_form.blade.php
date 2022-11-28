@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <form action="{{ urL('contra_journal_addlist/store/') }}" method="post">
                    @csrf
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
                       
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="border: 1px solid #eee;font-size: 12px;">
                                    <tr>

                                        <td style="padding: 5px 5px;min-width: 400px;">
                                            <div class="row">
                                                <div class="col-md-2 heighlightText" style="text-align: right;padding-top: 5px;">Vch. No :
                                                </div>
                                                <div class="col-md-4">
                                                    @php
                                                    use App\Journal;
                                                    $vo_no = App\Helpers\Helper::IDGenerator(new Journal, 'vo_no',4,'Jo');
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
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4 heighlightText" style="text-align: right;padding-top: 5px;">Date :
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" name="date" id="date" class="form-control"
                                                        value="{{ date('Y-m-d') }}" required />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>


                            <div class="col-md-12" style="">
                                <table class="table" style=";font-size: 12px;background: #f8f8f8;border-radius: 5px;" id="myTable">
                                    <thead style="height: 10px;">
                                        <th style="text-align: center;">Account Ledger</th>
                                        <th style="text-align: center;">Dr/Cr</th>
                                        <th style="text-align: center;">Amount</th>
                                        <th style="text-align: center;">Note</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="width: 30%">
                                                <select id="account_name" class="select2" style="width: 100%" >
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
                                                    style="height: 30px;text-align: center;font-size:20px;font-weight:bold;"autocomplete="off">
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
                            <div style="text-align: center; color: #fff; font-weight: 800;">
                                <button type="submit" class="btn btn-success"
                                    style="color:#fff; font-weight: 800;font-size: 18px;">Save</button>
                                <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                            </div>

                            <br>
                            <br>

                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
@endsection

@push('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".select2").select2({
            ajax: {
                url: '{{ url("activeLedger") }}',
                dataType: 'json',
                type: "GET",
                data: function (params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function (data) {
                    console.log(data);
                	var res = data.ledgers.map(function (item) {
                        	return {id: item.id, text: item.account_name};
                        });
                    return {
                        results: res
                    };
                }
            },

    });
    function cleardata(){
        $('.select2').val(null).trigger('change');
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
        htmlData += "<td  style='display:none'><input type='hidden' name='account_id[]' value='"+account_id+"'/> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 300px;text-align: center'>" + account_name + "</td>"
        htmlData += "<td  style='border-right: 1px solid #fff;padding: 5px 5px;width: 100px;'> <input class='drcr_text' style='border:0;text-align:center' readonly  type ='text' name='drcr_text[]' value='"+drcrText+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'><input style='border:0;text-align:center' readonly  type ='number' name='amount[]' value='"+amount+"' /> </td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 150px;'> <input style='border:0;text-align:center' readonly  type ='text' name='note[]' value='"+note+"' /></td>"
        htmlData += "<td style='border-right: 1px solid #fff;padding: 5px 5px;width: 50px;'>"
        htmlData += "<a class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></a>"
        htmlData += "</td>"
        htmlData +="</tr>";
        console.log(htmlData);
        $('#myTable tr:last').after(htmlData)
        cleardata();


    }


  //----------------------------Start newProduct----------------------------------------



    //----------------------------End newProduct----------------------------------------

    function delete_data(delelet) {
        (delelet).closest('tr').remove();

        currentData();
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


        if(debit != credit){
            alert("Your Dr Cr Not Equal!");
            return false;
        }

        this.submit();

    });


</script>
@endpush
