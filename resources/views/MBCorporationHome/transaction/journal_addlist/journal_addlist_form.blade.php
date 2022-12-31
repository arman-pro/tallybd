@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Add New Journal")
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12 fw-bold">
            <form action="{{ urL('contra_journal_addlist/store/') }}" method="post">
                @csrf
                <input type="hidden" name="page_name" id="page_name" value="journal"/>
                <input type="hidden" name="print" value="0" />
            <div class="card">
                <div class="card-header bg-success">
                    <h4 class="card-title">Add New Journal</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-6 col-sm-12">
                            <label>Vch. No</label>
                            <?php
                                use App\Journal;
                                $vo_no = App\Helpers\Helper::IDGenerator(new Journal, 'vo_no',4,'Jo');
                            ?>
                            <input 
                                type="text" class="form-control" name="vo_no" id="vo_no"
                                value="{{$vo_no}}" readonly
                            />                            
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label>Date</label>
                            <input 
                                type="date" name="date" id="date" class="form-control"
                                value="{{ date('Y-m-d') }}" required 
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <table class="table table-bordered" id="myTable">
                            <thead class="heighlightText" style="background-color: #D6DBDF;">
                                <th class="fw-bold">Account Ledger</th>
                                <th style="width:150px;" class="fw-bold">Dr/Cr</th>
                                <th style="width:150px;" class="fw-bold">Amount</th>
                                <th style="width:250px;" class="fw-bold">Note</th>
                                <th>&nbsp;</th>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <select 
                                            id="account_name" class="select2" style="width: 100%" data-placeholder="Select a Account Ledger" >
                                        </select>
                                       <span id="account_ledger_value" style="color: green;font-size:15px;"></span>
                                    </td>
                                    <td>
                                        <select 
                                            class="form-control" id="drcr">
                                            <option value="" hidden>Select Dr/Cr</option>
                                            <option value="1">Dr</option>
                                            <option value="2">Cr</option>
                                        </select>
                                        
                                    </td>
                                    <td>
                                        <input 
                                            type="number" name="" id="amount" class="form-control"
                                            autocomplete="off" placeholder="Amount" min='0'
                                        />
                                    </td>
                                    <td>
                                        <input 
                                            type="text" name="" id="note" class="form-control"
                                            placeholder="Note"
                                        />
                                    </td>
                                    <td>
                                        <button type="button" onclick="addDemoContraJournal()" class="btn btn-sm btn-info">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
                <div class='card-footer text-center'>
                    <button type="submit" class="btn btn-success" ><b>Save</b></button>
                    <button type="submit" class="btn btn-outline-primary print" ><b>Save & Print</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script type="text/javascript">

    $(document).ready(function(){
        $(document).on('click', '.print',function(){
            $('input[name="print"]').val(1);
        });
    });
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

        $('#account_name').change(function(){
            var ledger_id = $(this).val();
            $.get("{{url('ledgerValue')}}"+'/'+ledger_id, function(data, status){
                 $('#account_ledger_value').html(data);
            });
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
        if(account_id) {
            htmlData += "<tr class='item'>"
            htmlData += "<td  style='display:none'><input type='hidden' name='account_id[]' value='"+account_id+"'/> </td>"
            htmlData += "<td>" + account_name + "</td>"
            htmlData += "<td><input class='drcr_text form-control' readonly  type ='text' name='drcr_text[]' value='"+drcrText+"' /></td>"
            htmlData += "<td><input class='form-control'  type ='number' name='amount[]' value='"+amount+"' /></td>"
            htmlData += "<td><input class='form-control'  type ='text' name='note[]' value='"+note+"' /></td>"
            htmlData += "<td><button type='button' class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></button></td>";
            htmlData +="</tr>";
            $('#myTable tbody').append(htmlData)
            cleardata();
        }else {
            Swal.fire({
                icon: 'question',
                text: 'Please select a ledger',
            });
        }
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
