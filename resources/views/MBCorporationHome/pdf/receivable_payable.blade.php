@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Receive Payment Report")

@push('css')
<style media="screen">
    body,html {
        /* width: 8.3in;*/
        /*height: 11.7in; */
        margin: 10px;
        padding: 0;
    }
    .content_area {
        /* width: 8.3in;
        height: 11.7in;
        margin: auto;
        border: 1px solid black;
        display: block; */
    }
    
    /*@page {*/
    /*    page: a4;*/
    /*    margin: 0.2in;*/
    /*}*/

    .pdf-table {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
    }

    .pdf-table tr, .pdf-table  th, .pdf-table  td, .pdf-table thead {
        border: 1px solid black;
        padding: 5px 3px;
    }

    .text-center {
        text-align: center;
    }

    .float-end {
        float: right;
    }

    .float-start {
        float: left;
    }

    .page-break {
        page-break-after: always;
    }
</style>
@endpush

@section('pdf_content')
<div class="container-fluid">
    <div class="p-0 content_area" >
        @php
        $company = App\Companydetail::first();
        $leftSide =0;
        $rightSide =0;
        @endphp
        <div id="printArea" class="display">           

            <div style="text-align:center">
                <h3 style="font-weight: 800;margin:0">{{$company->company_name}}</h3>
                <p style="margin:0">{{$company->company_address}}<br> {{$company->phone}} Call:
                    {{$company->mobile_number}}</p>
                <p style="margin:0"> From : {{ request()->from_date }} TO : {{ request()->to_date }}</p>
                <h4 style="margin:0">All Receivable & Payable</h4>
            </div>

            <h4 style='text-align:center;margin:0;padding:0;'><u>Receive List</u></h4>
            <table style="text-align: center;" class="pdf-table">
                @forelse ($ledger as $item)
                <?php
                    $amount = $item->received_payment_amount($endMonth);
                ?>
                @if($amount > 0)
                @php
                $leftSide += $amount??0;
                @endphp
                <tr style="font-size:14px;font-weight: 700;">
                    <td style="text-align: left;">
                        {{$item->account_name}}
                    </td>
                    <td style="text-align: right;">
                        {{ new_number_format( $amount??0.00)}} (DR)
                    </td>
                </tr>

                @endif
                @empty

                @endforelse
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td>{{new_number_format($leftSide)}} (DR)</td>
                    </tr>
                </tfoot>
            </table>
             <h4 style='text-align:center;margin:0;padding:0;'><u>Payment List</u></h4>
            <table class="pdf-table">
                @forelse ($ledger as $payable_item)
                <?php
                    $amount = $payable_item->received_payment_amount($endMonth);
                ?>
                @if($amount < 0) @php $rightSide +=($amount * (-1)) ?? 0;
                    @endphp
                    <tr style="font-size:14px;font-weight: 700;">
                        <td style="text-align: left;">
                            {{$payable_item->account_name}}
                        </td>
                        <td style="text-align: right;">
                            {{ new_number_format( $amount??0.00)}} (CR)
                        </td>
                    </tr>
                    @endif
                    @empty

                    @endforelse
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td>{{new_number_format($rightSide)}} (CR)</td>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>
</div>

@endsection