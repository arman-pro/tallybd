@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">All Receive & Payment
    </h3>
    <div class="row">

        <form action="{{url('/all_recevie_payment/by/date')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">From :</label>
                        <div>
                            <input type="Date" class="form-control" name="form_date">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">To :</label>
                        <div>
                            <input type="Date" class="form-control" name="to_date">
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="text-align: center;">
                    <br>
                    <button type="submit" class="btn btn-success"
                        style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
                </div>
            </div>
        {{-- </form>

        <br>
        <br>
        <script lang='javascript'>
            function printData()
				{
				   var print_ = document.getElementById("main_table");
				   win = window.open("");
				   win.document.write(print_.outerHTML);
				   win.print();
				   win.close();
				}
        </script>
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <style type="text/css">
                .source_file_list {
                    height: 35px;
                    float: right;
                    background-color: #99A3A4;

                    padding: 5px;
                }

                .source_file_list a {
                    text-decoration: none;
                    padding: 5px 20px;
                    color: #fff;
                    font-size: 18px;

                }

                .source_file_list a:hover {
                    background-color: #D6DBDF;
                    color: #fff;
                }
            </style>
            <div class="source_file_list">
                <a style="color: #fff;" type="sumit" onclick="printData()">Print</a>
                <a href="">PDF</a>
                <a href="">Excal</a>
            </div>
        </div>
        <div class="col-md-12" style="padding: 2%;" id="main_table">

            <br>
            <table class="table" style="border: 1px solid #eee;text-align: center;width: 100%;">
                <tr style="border: 1px solid #eee;">
                    <td colspan="2" style="text-align: center;">
                        @php
                        $company = App\Companydetail::get();
                        @endphp

                        @foreach($company as $company_row)

                        <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                        <p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
                            {{$company_row->mobile_number}}</p>
                        @endforeach
                        <h4>All Receive & Payment</h4>
                    </td>
                </tr>
                <tr style="font-size:16px;font-weight: 800;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">Receive</td>
                    <td style="padding: 5px 5px;width: 100px;">Payment</td>
                </tr>
                </tr>

                <tr style="font-size:14px;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;width: 100px;">
                        <table style="text-align: center;width: 100%">
                            <tr style="font-size:14px;font-weight: 700;">
                                <td style="padding: 5px 5px;width: 30%;text-align: left;">Date</td>
                                <td style="padding: 5px 5px;width: 50%;text-align: left;">Account Ledger</td>
                                <td style="padding: 5px 5px;width: 30%;text-align: right;">Amount</td>
                            </tr>
                            @php
                            $total_rec = 0;
                            $Receive = App\Receive::get();
                            // dd($Receive);
                            @endphp
                            @foreach($Receive as $row)
                            <tr style="font-size:14px;">
                                <td style="padding: 5px 5px;width: 20%;text-align: left;">{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                <td style="padding: 5px 5px;width: 50%;text-align: left;">
                                    @php
                                    $total_rec = $total_rec + $row->amount;
                                    $account_name = App\AccountLedger::where('id',$row->account_name_ledger_id)->first();
                                    @endphp
                                    {{$account_name->account_name}}
                                </td>
                                <td style="padding: 5px 5px;width: 30%;text-align: right;">{{$row->amount}}.00
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td style="padding: 5px 5px;width: 100px;">
                        <table style="width: 100%">
                            <tr style="font-size:14px;font-weight: 700;">
                                <td style="padding: 5px 5px;width: 30%;text-align: left;">Date</td>
                                <td style="padding: 5px 5px;width: 50%;text-align: left;">Account Ledger</td>
                                <td style="padding: 5px 5px;width: 30%;text-align: right;">Amount</td>
                            </tr>
                            @php
                            $total_pay = 0;
                            $Receive = App\Payment::get();
                            @endphp
                            @foreach($Receive as $row)
                            <tr style="font-size:14px;">
                                <td style="padding: 5px 5px;width: 20%;text-align: left;">{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                
                                <td style="padding: 5px 5px;width: 50%;text-align: left;">
                                    @php
                                    $total_pay = $total_pay + $row->amount;
                                    $account_name =
                                    App\AccountLedger::where('id',$row->account_name_ledger_id)->first();
                                    @endphp
                                    {{$account_name->account_name}}
                                </td>
                                <td style="padding: 5px 5px;width: 30%;text-align: right;">{{$row->amount}}.00
                                </td>
                            </tr>
                            @endforeach
                        </table> 
                    </td>
                </tr>
                <tr style="font-size:16px;font-weight: 800;">
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;text-align: right;">Total :
                        {{$total_rec}}.00</td>
                    <td style="border-right: 1px solid #eee;padding: 5px 5px;text-align: right;">Total :
                        {{$total_pay}}.00</td>
                </tr>

            </table>--}}
        </div>

    </div>
</div>

@endsection
