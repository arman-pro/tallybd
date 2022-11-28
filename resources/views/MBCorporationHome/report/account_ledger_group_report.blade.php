@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
    <div style="background: #fff;margin-bottom: ;">
        {{-- <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 1px solid #444242;">Group Account Ledger
        </h3> --}}
        <div class="row">

            <br>


            <div class="col-md-8">
                
            </div>
            <div class="col-md-4">

                <div class="source_file_list">
                    <a href="#" style="color: #fff;"  onclick="printData()">Print</a>

                </div>
            </div>
            <div class="col-md-12" id="main_table" style="overflow-x:auto;">
                {{-- <table border='1' cellpadding='1' id='Tablbpm1' > --}}
                <table  class="table table-bordered" cellspacing="0" style="text-align: center;border:1px solid black !important">

                    <thead>
                        <tr>
                            <th colspan="8" style="text-align: center;">
                                @php
                                    $company = App\Companydetail::get();
                                    $dr = 0;
                                    $cr = 0;
                                @endphp

                                @foreach ($company as $company_row)
                                    <h3 style="font-weight: 800;">{{ $company_row->company_name }}</h3>
                                    <p>{{ $company_row->company_address }}, Tel: {{ $company_row->phone }}, Call:
                                        {{ $company_row->mobile_number }}</p>
                                @endforeach
                                <h4>Account Ledger</h4>
                                <p style="text-align: left;">Account Group Name : {{ $account_group_list->account_group_name }}
                                </p>
                                <p style="text-align: right; padding-right: 20px;">From : {{ $formDate . ' to ' . $toDate }}</p>

                            </th>
                        </tr>
                        <tr style="font-size:14px;font-weight: 800;">
                            <th width="10%" style="border:1px solid  black;padding: 5px 5px;">Sl No. </th>
                            <th width="30%" style="border:1px solid  black;padding: 5px 5px;">Party Name/ Ledger Name </th>
                            <th width="10%" style="border:1px solid  black;padding: 5px 5px;">Mobile Number </th>
                            <th width="25%" style="border:1px solid  black;text-align: right;padding: 5px 5px;">Debit(Dr)</th>
                            <th width="25%" style="border:1px solid  black;text-align: right;padding: 5px 5px;">Credit(Cr)</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php
                            $number = 0;
                        ?>
                        @foreach ($groupAccount_ledger as $key => $item)
                        @php
                            $i = 0;
                            $x = 0;

                            $account_tran = App\AccountLedgerTransaction::where('ledger_id', $item->id)
                                // ->whereBetween('date', [$formDate, $toDate])
                                //->where('date', '>=', $formDate)
                                ->where('date', '<=', $toDate)
                                ->get()
                                ->unique('account_ledger__transaction_id');
                            $result = $account_tran->sum('debit') - $account_tran->sum('credit');
                            if ($result > 1) {
                                $dr += $result;
                            } else {
                                $cr += $result;
                            }
                            
                            

                        @endphp
                        @if($filter == 'filter' && $result != 0)
                        <tr class="text-right" style="font-size:14px;">
                            <td width="10%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{$number += 1}}</td>
                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{ $item->account_name }}</td>
                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{ $item->account_ledger_phone ?? ' ' }}</td>
                            @if ($result > 0)
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ new_number_format($result) }} </td>
                            @else
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ '-' }}</td>
                            @endif
                            @if ($result < 0)
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                    {{ new_number_format($result * -1) }} </td>
                            @else
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                    {{ '-' }}</td>
                            @endif
                        </tr>
                      
                        @endif
                        
                        @if($filter == 'all')
                         <tr class="text-right" style="font-size:14px;">
                            <td width="10%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{$number += 1}}</td>
                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{ $item->account_name }}</td>
                            <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;padding: 5px 5px;">{{ $item->account_ledger_phone ?? ' ' }}</td>
                            @if ($result > 0)
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ new_number_format($result) }} </td>
                            @else
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">{{ '-' }}</td>
                            @endif
                            @if ($result < 0)
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                    {{ new_number_format($result * -1) }} </td>
                            @else
                                <td width="30%" style="border-bottom:1px solid  black;border-right:1px solid  black;text-align:right;padding: 5px 5px;">
                                    {{ '-' }}</td>
                            @endif
                        </tr>
                         
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right">Grand Total</td>
                        <td width="30%"
                            style="text-align:right;font-size: x-large;padding: 5px 5px;">
                            {{ new_number_format($dr) }} </td>
                        <td d width="30%"
                            style="text-align:right;font-size: x-large;padding: 5px 5px;">
                            {{ new_number_format(-1 * $cr) }} </td>
                    </tr>
                    </tbody>





                </table>
            </div>

        </div>
    </div>

<script lang='javascript'>
    function printData()
        {

        //    var print_ = document.getElementById("main_table");
        // //    window = window.open("");
        //    window.document.write(print_.outerHTML);
        //    window.print();
        //    win.close();
           var divToPrint = document.getElementById('main_table');
    // var htmlToPrint = '' +
    //     '<style type="text/css">' +
    //     'table th, table td {' +
    //     'border:1px solid #000;' +
    //     'padding;0.5em;' +
    //     '}' +
    //     '</style>';
    // htmlToPrint += divToPrint.outerHTML;
    // newWin = window.open("");
    window.document.write(divToPrint.outerHTML);
    window.print();
    window.close();
        }
</script>


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
    @media print {
        .main_table table {
            border: solid #000 !important;
            border-width: 1px 0 0 1px !important;
        }
       .main_table table th, td {
            border: solid #000 !important;
            border-width: 0 1px 1px 0 !important;
        }
    }

</style>
@endsection
