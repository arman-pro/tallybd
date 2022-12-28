@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "Salary Generate Report_".date('d_m_y'))

@push('css')
<style>
    .head_table {
        width: 100%;
        border: none;
        border-collapse: collapse;
    }

    .head_table tr, .head_table th, .head_table td {
        border: none;
    }
    .signature-by {
        position: absolute;
        left: 20px;
        bottom: 20px;
    }

    .verifyed-by {
        position: absolute;
        bottom: 20px;
        right: 50%;
    }

    .border {
        border: 1px solid black;
    }
</style>
@endpush

@section('container')
<div class="invoice-title">
    <div>
        &nbsp;
    </div>
    <div class="font-bold underline uppercase">
        SALARY GENERATE
    </div>
    <div>
        &nbsp;
    </div>
</div>
<div class="account-title">
    <table class="head_table">
        <tbody>
            <tr>
                <th class="text-left" style="width: 80px;">Department:</th>
                <td style="width: 60%;">{{$salary->department->name ?? "N/A"}}</td>
                <th class="text-right">Shift:</th>
                <td>{{$salary->shift->name ?? "N/A"}}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="invoice-body">
    <?php
        $total = 0;
        $total_qty = 0;
    ?>
    <table class="print-table">
        <thead>
            <tr>
                <th>SL</th>
                <th style="width:40%">Employee Name</th>
                <th>Designation</th>
                <th>Working Day</th>
                <th>Salary</th>
                <th>Salary Generate Date</th>
            </tr>
        </thead>
        <tbody>
            @if($salary->details->isNotEmpty())
                @foreach($salary->details as $key => $salary_detail)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$salary_detail->employee->name}}</td>
                    <td>{{$salary_detail->employee->designation->name ?? "N/A"}}</td>
                    <td>{{$salary_detail->day}}</td>
                    <td>{{ number_format($salary_detail->salary)}}</td>
                    <td>{{ date('d-m-y', strtotime($salary_detail->salary_date))}}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Grand Total</td>
                <td colspan="2" style="text-align:right;">{{ new_number_format($salary->total_amount ?? 0)}}</td>
                <td colspan='2'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6">
                    Amount In Words :
                    <span style="font-size: 16px;font-weight: 800;">@php echo App\Helpers\Helper::NoToWord($salary->total_amount ?? 0); @endphp Taka Only</span>
                </td>
            </tr>
        </tfoot>
    </table>
   
</div></div></div>
<div class="signature-by">
    Signature
</div>
<div class="verifyed-by">
    Verified By
</div>
<div class="signature-box">
    Authorised Signatory
</div>
</div>

@endsection