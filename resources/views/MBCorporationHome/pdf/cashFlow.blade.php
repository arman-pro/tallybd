@extends('MBCorporationHome.apps_layout.pdf_layout')
@section("title", "Cash Flow Report")

@push('css')
<style media="screen">
    body,html {
        /* width: 8.3in;
        height: 11.7in; */
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
    <?php
        $company = App\Companydetail::first(); 
    ?>
   
    <div class="p-0 content_area" >
        <div style="text-align:center">
            <h3 style="font-weight: 800;margin:0">{{$company->company_name}}</h3>
            <p style="margin:0">{{$company->company_address}}, Tel: {{$company->phone}}, Call: {{$company->mobile_number}}</p>
            <p style="margin:0">From : {{ request()->from_date }} - To : {{request()->to_date}}</p>
            <h4 style="margin:0">Cash Flow</h4>
        </div>

        <table cellspacing='0' class="table table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th style="font-weight: 800;border:1px solid black;padding:5px"> Party</th>
                    <th style="font-weight: 800;border:1px solid black;padding:5px">DR</th>
                    <th style="font-weight: 800;border:1px solid black;padding:5px">CR </th>
                    <th style="font-weight: 800;border:1px solid black;padding:5px">Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $debit_sum = $credit_sum = 0;
                @endphp
                @forelse ($account_ledger_list as $item)
                @php
                    $debit_sum += $item->debit_sum??0;
                    $credit_sum += $item->credit_sum??0;
                @endphp
                    <tr>
                        <td style="font-weight: 600;border:1px solid black">{{ $item->account_name }}</td>
                        {{-- @if ($item->debit_sum > 0 ) --}}
                        <td style="font-weight: 600;border:1px solid black;text-align:right">{{ $item->debit_sum??0.00 }}</td>
                        {{-- @else --}}
                        <td style="font-weight: 600;border:1px solid black;text-align:right">{{ $item->credit_sum??0.00 }}</td>
                        {{-- @endif --}}
                        {{-- @if ($item->credit_sum  ) --}}

                        <td style="font-weight: 600;border:1px solid black;text-align:right">{{ $debit_sum - $credit_sum??0.00 }}</td>
                        {{-- @endif --}}
                    </tr>
                @empty

                @endforelse

            </tbody>
            <tr>

                <td colspan="2" style="border:1px solid black;font-weight: 800;text-align: right">{{ number_format($debit_sum, 2) }}</td>
                <td style="border:1px solid black;font-weight: 800;text-align: right">{{ number_format($credit_sum, 2) }}</td>
                <td style="border:1px solid black;font-weight: 800;text-align: right"></td>

            </tr>
        </table>
    </div>
</div>

@endsection