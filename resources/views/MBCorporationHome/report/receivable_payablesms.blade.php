@extends('MBCorporationHome.apps_layout.layout')
@push('css')
<style type="text/css">
            .topnav {
            overflow: hidden;
            background-color: #eee;
        }

            .topnav a {
                width: 25%;
                float: left;
                color: #000;
                text-align: center;
                padding: 5px 16px;
                text-decoration: none;
                font-size: 17px;
                border-radius: 10%
            }

            .topnav a:hover {
                background-color: #ddd;
                color: black;
            }

            .topnav a.active {
                color: greenyellow;
            }
            table, td, th {
              border: 1px solid #000;
            }
            
            table { 
              border-collapse: collapse;
            }
        </style>

@endpush
@section('admin_content')


<div style="background: #fff;">

    <div class="row">
        <form action="{{url('/all-receivable-payablesms')}}" method="GET">

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">From :</label>
                        <div>
                            <input type="month" class="form-control" name="from_month" value="{{request()->from_date }}"
                                required>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">To :</label>
                        <div>
                            <input type="month" class="form-control" name="to_month" value="{{ request()->to_date }}"
                                required>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="text-align: center;">
                    <br>
                    <button type="submit" class="btn btn-success"
                        style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
                </div>
            </div>
        </form>



        @php
        $company = App\Companydetail::first();
        $leftSide =0;
        $rightSide =0;
        @endphp
        <div class="col-md-12 overflow-auto">
            <div class="p-3 mb-2">
            <div style="text-align:center">
                <h3 style="font-weight: 800;margin:0">{{$company->company_name}}</h3>
                <p style="margin:0">{{$company->company_address}}<br> {{$company->phone}} Call:
                    {{$company->mobile_number}}</p>
                <p style="margin:0"> From : {{ request()->from_date }} TO : {{ request()->to_date }}</p>
                <h4 style="margin:0">All Receivable Balance SMS</h4>
            </div>

            <table cellspacing='0' class="table table-borderless" style="width: 100%;">
                <thead>
                    <tr style=" font-size:14px;">
                        <th style="border:1px solid black;padding:5px 5px;text-align:center;font-weight:800;">SL</th>
                        <th style="border:1px solid black;padding:5px 5px;text-align:center;font-weight:800;">Account Name</th>
                         <th style="border:1px solid black;padding:5px 5px;text-align:center;font-weight:800;">Address</th>
                        <th style="border:1px solid black;padding:5px 5px;text-align:center;font-weight:800;">Mobile Number</th>
                        <th style="border:1px solid black;padding:5px 5px;text-align:center;font-weight:800;">Balance(TK)</th>
                        <th style="border: 1px solid black;padding: 5px 5px;text-align: center;font-weight: 800;">SMS Send</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ledger as $key => $item)
                        <?php
                            $amount = $item->received_payment_amount($endMonth);
                        ?>
                        @if($amount > 0)
                        <?php
                            $leftSide += $amount ?? 0;
                        ?>
                        <tr style="font-size:14px;font-weight: 700;">
                            <td style="padding: 5px 5px;text-align: left;">
                                {{$key}}
                            </td>
                            <td style="padding: 5px 5px;text-align: left;">
                                {{$item->account_name}}
                            </td>
                             <td style="padding: 5px 5px;text-align: left;">
                               {{$item->account_ledger_address ?? "N/A"}}
                            </td>
                            <td style="padding: 5px 5px;text-align: left;">
                                {{$item->account_ledger_phone ?? "N/A"}}
                            </td>
                            <td style="padding: 5px 5px;text-align: right;">
                                {{ new_number_format( $amount??0.00)}} (DR)
                            </td>
                            <td style="padding: 5px 5px;text-align: center;">
                                <a 
                                    data-message="Dear Coustmer, {{date('d/m/y')}} Your Clossing Due Balance: {{$amount ?? 0}} Tk. Pls Pay! Thank You!"
                                    data-phone="{{$item->account_ledger_phone ?? null}}"
                                    href="javascript:void(0)" 
                                    class="btn btn-primary btn-sm sms_send" style="color:#fff; "
                                >SMS SEND</a>
                            </td>
                        </tr>
                        @endif
                        @empty
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

    </div>
</div>
<div class="text-center">
    <!--<button class="btn btn-lg btn-success text-white" onclick="printData()">Print</button>-->
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.14/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).on('click', '.sms_send', function(){
        var phone = $(this).data('phone');
        var message = $(this).data('message');
       
        Swal.fire({
            icon: "question",
            title: 'Are you sure to send sms?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Send it!',
            customClass: {
              confirmButton: 'btn btn-primary btn-lg',
              cancelButton: 'btn btn-danger btn-lg',
              loader: 'custom-loader'
            },
            preConfirm: () => {
              Swal.showLoading()
              return new Promise((resolve) => {
                var request = $.ajax({
                    url: "{{route('sms.send')}}",
                    type: 'POST',
                    dataType: 'json',
                    data: {phone, message},
                    success: function(response) { 
                     
                        if(response.success) {
                            resolve(true);
                        }
                    },
                    beforeSend: function (request) {                    
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    }
                 });
                 
                 request.fail(function(xhr, textStatus, errorThrown){
                     Swal.fire({
                        text: 'Something went worng!',
                        icon: 'error',
                    });
                 });
              });
            }
        }).then(function(result) {
            if (result.isConfirmed) {
            Swal.fire({
                text: 'Message Send Successfull!',
                icon: 'success',
            });
          }
        });
    });
    
    function printData()
    {
        var divToPrint = document.getElementById('printArea');
        var htmlToPrint = '' +
            '<style type="text/css">' +
            'table th, table td {' +
            'border:1px solid #000;' +
            '}' +
            'table{'+
            'border-collapse: collapse;'+
            '}'+
            '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin = window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();

    }
    </script>
@endpush
