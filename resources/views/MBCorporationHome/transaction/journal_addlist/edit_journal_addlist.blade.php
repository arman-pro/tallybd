@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Update Contra')
@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            @foreach($contra as $contra_row)
            <form action=" {{URL::to('/Update/journal/'.$contra_row->id)}} " method="POST">
                @csrf
                <input type="hidden" name="page_name" id="page_name" value="journal" />
            <div class="card fw-bold">
                <div class="card-header bg-success">
                    <h4 class="card-title">Update Contra</h4>
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
                            <label>Vo. No</label>
                            <input 
                                type="text" class="form-control" name="vo_no" id="vo_no"
                                value="{{$contra_row->vo_no}}" readonly required                             
                            />
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label>Date</label>
                            <input 
                                type="date" name="date" id="date" class="form-control"
                                value="{{ $contra_row->date }}"
                            />
                        </div>
                    </div>
                        
                    <div class="form-group row">

                        <div class="col-md-12" >
                            <table class="table table-bordered" id="journal_list" >
                                <thead class="bg-light">
                                    <th class="fw-bold">Account Ledger</th>
                                    <th class="fw-bold" style="width:150px;">Dr/Cr</th>
                                    <th class="fw-bold" style="width:150px;">Amount</th>
                                    <th class="fw-bold" style="width:250px;">Note</th>
                                    <th>&nbsp;</th>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <select 
                                                id="account_name" class="select2 form-control" style="width: 100%" 
                                                data-placeholder="Select a Account Ledger"
                                            >
                                            </select>
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
                                                type="number" name="" id="amount" class="form-control" placeholder="Amount"
                                            />
                                        </td>
                                        <td>
                                            <input
                                                type="text" name="" id="note" class="form-control" placeholder="Note"
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
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><b>Update</b></button>
                    <button type="submit" class="btn btn-outline-info" name="print" value="1"><b>Update & Print</b></button>
                    <a href="{{route('mb_cor_index')}}" class="btn btn-outline-danger"><b>Cancel</b></a>
                </div>
            </div>
            </form>
        </div>
    </div>
        @endforeach
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
            htmlData += "<input type='hidden' name='new_account_id[]' value='"+account_id+"'/>"
            htmlData += "<td>" + account_name + "</td>"
            htmlData += "<td> <input class='drcr_text form-control' readonly  type ='text' name='new_drcr_text[]' value='"+drcrText+"' /></td>"
            htmlData += "<td><input class='form-control' type ='number' placeholder='Amount' min='0' name='new_amount[]' value='"+amount+"' /> </td>"
            htmlData += "<td><input class='form-control' type ='text' placeholder='Note' name='new_note[]' value='"+note+"' /></td>"
            htmlData += "<td><button class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></button></td>"
            htmlData +="</tr>";
            $('#journal_list tbody').append(htmlData)
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
                        htmlData += "<input type='hidden' name='id[]' value='"+value.id+"'/>"
                        htmlData += "<input type='hidden' name='account_id[]' value='"+value.ledger_id+"'/>"
                        htmlData += "<td>" + value.ledger.account_name + "</td>"
                        htmlData += "<td><input class='drcr_text form-control' readonly  type ='text' name='drcr_text[]' value='"+value.drcr+"' /></td>"
                        htmlData += "<td><input class='form-control' min='0' placeholder='Amount'  type ='number' name='amount[]' value='"+value.amount+"' /> </td>"
                        htmlData += "<td><input class='form-control' placeholder='Note'  type ='text' name='note[]' value='"+value.note+"' /></td>"
                        htmlData += "<td>"
                        htmlData += "<button type='button' class='btn btn-sm btn-danger' onclick='delete_data(this)'><i class='fa fa-trash'></i></button>"
                        htmlData += "</td>"
                        htmlData +="</tr>";
                    });
                    $('#journal_list tbody').append(htmlData);
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
