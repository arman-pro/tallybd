@extends("MBCorporationHome.apps_layout.layout")
@section("title", "Employee Ledger")

@section('admin_content')
<div class='container-fluid'>
    <div class="row">
        <div class="col-md-12" id="main_table">
        	<div class="card">
        	    <div class="card-header bg-success text-light fw-bold">
        	        <h4 class="card-title">Employee Ledger</h4>
        	    </div>
        	    <div class="card-body">
        	        @php
                        $company = App\Companydetail::get();
                    @endphp
        
                    @foreach($company as $company_row)
        
                    <div style="text-align: center;">
                    	<h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
        	            <p>{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
        	                {{$company_row->mobile_number}}</p>
        	            @endforeach
        	            <h4>All Employee Ledger</h4>
                        <p style="text-align: center; padding: 0px;">From : {{request()->from_date." to ".request()->to_date}}</p>
                    </div>
        
                    <table width="100%" class="table table-bordered" style="border: 1px solid #444242;text-align: center;margin-top:30px">
                        <thead>
                            <tr style="font-size:14px;font-weight: 800;">
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px">SL No</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px;">Party Name/Leadger Name</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px;">Mobile Number</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">Debit (TK)</td>
                                <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: Center;">Credit (TK)</td>
                            </tr>
                        </thead>
        
                        <tbody>
                            {!!$table!!}
                        </tbody>
                    </table>
        	    </div>
        	    <div class="card-footer">
        	        <a href="javascript:void(0)" onclick="printData()" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-print"></i> Print</a>
        	    </div>
        	</div>
        </div>
    </div>
</div> 
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
@endsection