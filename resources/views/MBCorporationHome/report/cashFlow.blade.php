@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Cash Flow Report")

@push('css')
<style>
    @media print {
        .table-bordered >thead > tr > th{
            border: 1px solid black;
        }
    }
</style>
@endpush

@section('admin_content')
@php
$company = App\Companydetail::first();
$leftSide =0;
$rightSide =0;
@endphp

<div class="container-fluid">
	<div class="row">
        <div class="col-sm-12">
            <form action="/cash-flow" method="GET">
                <input type="hidden" name="report" value="1">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Cash Flow Report</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 m-auto">
                            <div class="form-group">
                                <label for="cono1" class="control-label col-form-label" >From</label>
                                <input type="month" class="form-control" name="from_date" value="{{request()->from_date}}" required>
                            </div>    
                            <div class="form-group">
                                <label for="cono1" class="control-label col-form-label" >To</label>
                                <input type="month" class="form-control" name="to_date"  value="{{request()->to_date}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
					<button type="submit" class="btn btn-success btn-lg text-light fw-bold" ><i class="fa fa-search"></i> Search</button>
				</div>
            </div>
            </form>
        </div>

        @if(request()->report)
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header bg-success text-light">
                    <h4 class="card-title">Cash Flow Report</h4>
                </div>
                <div class="card-body">
                    <section id="printArea">
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
                    </section>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-lg btn-success text-light fw-bold"  onclick="printData()"><i class="fa fa-print"></i> Print</button>
                    <a href="{{url()->full()}}&pdf=1" class="btn btn-primary btn-lg fw-bold text-light"><i class="fas fa-file-pdf"></i> PDF</a>
                </div>
            </div>
        </div> 
        @endif
	</div>
</div>

@endsection
@push('js')
    <script>
      function printData()
        {
            var print_ = document.getElementById("printArea");
            var body = $('body').html();
            $("body").html(print_.outerHTML);
            window.print();
            $('body').html(body);

        }
    </script>
@endpush


