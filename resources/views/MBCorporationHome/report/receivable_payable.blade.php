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
        <form action="{{url('/all-receivable-payable')}}" method="GET">

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">From :</label>
                        <div>
                            <input type="month" class="form-control" name="from_date" value="{{request()->from_date }}"
                                required>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group row">
                        <label for="cono1" class="control-label col-form-label">To :</label>
                        <div>
                            <input type="month" class="form-control" name="to_date" value="{{ request()->to_date }}"
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
        <div class="col-md-12" style="" table id="printArea" class="display">
             
            

            <div style="text-align:center">
                <h3 style="font-weight: 800;margin:0">{{$company->company_name}}</h3>
                <p style="margin:0">{{$company->company_address}}<br> {{$company->phone}} Call:
                    {{$company->mobile_number}}</p>
                <p style="margin:0"> From : {{ request()->from_date }} TO : {{ request()->to_date }}</p>
                <h4 style="margin:0">All Receivable & Payable</h4>
            </div>

            <table cellspacing='0' class="table table-borderless" style="width: 100%"">
                <thead>
                    <tr style=" font-size:14px;">
                <th style="border: 1px solid black;padding: 5px 5px;text-align: center;font-weight: 800;width:50%">Receivable (TK)</th>
                <th style="border: 1px solid black;padding: 5px 5px;text-align: center;font-weight: 800;width:50%">Payable (TK)</th>
                </tr>
                </thead>
                <tbody>
                
                <tr style="font-size:14px;">
                        <td style="border: 1px solid black;padding: 5px 0px;">
                            <table style="text-align: center;width: 100%">
                                @forelse ($ledger as $item)
                                <?php
                                    $amount = $item->received_payment_amount($endMonth);
                                ?>
                                @if($amount > 0)
                                @php
                                $leftSide += $amount??0;
                                @endphp
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        {{$item->account_name}}
                                    </td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        {{ new_number_format( $amount??0.00)}} (DR)
                                    </td>
                                </tr>

                                @endif
                                @empty

                                @endforelse
                            </table>
                        </td>


                        <td style="border: 1px solid black;padding: 5px 0px;">
                            <table style="width: 100%">
                                @forelse ($ledger as $payable_item)
                                <?php
                                    $amount = $payable_item->received_payment_amount($endMonth);
                                ?>
                                @if($amount < 0) @php $rightSide +=($amount * (-1)) ?? 0;
                                    @endphp
                                    <tr style="font-size:14px;font-weight: 700;">
                                        <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                            {{$payable_item->account_name}}
                                        </td>
                                        <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                            {{ new_number_format( $amount??0.00)}} (CR)
                                        </td>
                                    </tr>
                                    @endif
                                    @empty

                                    @endforelse

                            </table>
                        </td>
                    </tr>


                   {{-- <tr style="font-size:14px;">
                        <td style="border: 1px solid black;padding: 5px 0px;">
                            <table style="text-align: center;width: 100%">
                                @forelse ($ledger as $item)
                                @if($item->amount > 0)
                                @php
                                $leftSide += $item->amount??0;
                                @endphp
                                <tr style="font-size:14px;font-weight: 700;">
                                    <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                        {{$item->account_name}}
                                    </td>
                                    <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                        {{ new_number_format($item->amount??0.00)}} (DR)
                                    </td>
                                </tr>

                                @endif
                                @empty

                                @endforelse
                            </table>
                        </td>


                        <td style="border: 1px solid black;padding: 5px 0px;">
                            <table style="width: 100%">
                                @forelse ($ledger as $payable_item)
                                @if($payable_item->amount < 0) @php $rightSide +=($payable_item->amount * (-1))??0;
                                    @endphp
                                    <tr style="font-size:14px;font-weight: 700;">
                                        <td style="padding: 5px 5px;width: 70%;text-align: left;">
                                            {{$payable_item->account_name}}
                                        </td>
                                        <td style="padding: 5px 5px;width: 30%;text-align: right;">
                                            {{ new_number_format($payable_item->amount??0.00)}} (CR)
                                        </td>
                                    </tr>
                                    @endif
                                    @empty

                                    @endforelse

                            </table>
                        </td>
                    </tr> --}}
                    <tr>
                        <td style="text-align: right;font-weight: 800;padding:0%">{{new_number_format($leftSide)}} (DR)
                        </td>
                        <td style="text-align: right;font-weight: 800;padding:0%"> {{new_number_format($rightSide)}} (CR)
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

    </div>
</div>
<div class="text-center">
    <button class="btn btn-lg btn-success text-white" onclick="printData()">Print</button>
</div>
@endsection
@push('js')
<script>
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
