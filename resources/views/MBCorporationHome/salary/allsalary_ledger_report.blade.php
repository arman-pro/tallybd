@extends("MBCorporationHome.apps_layout.layout")
@section("title", "Employee Ledger")

@section('admin_content')
<div class='container-fluid'>
    <div class="row">
        <div class="col-md-12">
        	<div class="card">
        	    <div class="card-header bg-success text-light fw-bold">
        	        <h4 class="card-title">Employee Ledger</h4>
        	    </div>
        	    <div class="card-body" id="main_table">
        	        @php
                        $company = App\Companydetail::get();
                    @endphp
        
                    @foreach($company as $company_row)
        
                    <div style="text-align: center;">
                    	<h3 style="font-weight: 800;margin:0;">{{$company_row->company_name}}</h3>
        	            <p style="margin:0;">{{$company_row->company_address}}, Tel: {{$company_row->phone}}, Call:
        	                {{$company_row->mobile_number}}</p>
        	            @endforeach
        	            <h4 style="margin:0;">All Employee Ledger</h4>
                        <p style="text-align: center; padding: 0px;margin:0;">From : {{request()->from_date." to ".request()->to_date}}</p>
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
        	    <div class="card-footer text-center">
        	        <a href="javascript:void(0)" onclick="printData()" class="btn btn-lg fw-bold btn-primary text-light"><i class="fa fa-print"></i> Print</a>
        	    </div>
        	</div>
        </div>
    </div>
</div> 
<script lang='javascript'>

     function printData() {
        var divToPrint = document.getElementById('main_table');
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
@endsection