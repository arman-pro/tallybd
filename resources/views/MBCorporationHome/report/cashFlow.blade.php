@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
@php
$company = App\Companydetail::first();
$leftSide =0;
$rightSide =0;
@endphp
<style>
    @media print {
        .table-bordered >thead > tr > th{
            border: 1px solid black;
        }
    }
</style>
<div style="background: #fff;margin-bottom: 1250px;">
	<h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Cash Flow</h3>
	<div class="row">
		<form action="/cash-flow" method="GET" style="margin-top: 20px;" class="row">
				<div class="col-md-2"></div>

				<div class="col-md-4">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >From :</label>
			        	<div>
			           		<input type="month" class="form-control" name="from_date" value="{{request()->from_date}}" required>
					    </div>
					</div>
				</div>

				<div class="col-md-4">
				    <div class="form-group row">
			       		<label for="cono1" class="control-label col-form-label" >To :</label>
			        	<div>
			           	<input type="month" class="form-control" name="to_date"  value="{{request()->to_date}}" required>
					    </div>
					</div>
				</div>
				<div class="col-md-12" style="text-align: center;">
					<button type="submit" class="btn btn-success" style="color: #fff;font-size:16px;font-weight: 800;">Search</button>
				</div>

		</form>
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
        <div class="text-center">
            <button class="btn btn-lg btn-success text-white"  onclick="printData()">Print</button>
        </div>


	</div>
</div>

@endsection
@push('js')
    <script>
      function printData()
        {
            var print_ = document.getElementById("printArea");
            window.document.write(print_.outerHTML);
            window.print();

        }
    </script>
@endpush


