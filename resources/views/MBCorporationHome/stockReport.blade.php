@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')


		
<h3 class="card-title" style=" font-weight: 800;padding: 20px 0 0 20px ">Stock Information</h3>

        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
          <!-- ============================================================== -->
          <!-- Start Page Content -->
          <!-- ============================================================== -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
              	<div class="card-body">
                    <div style="text-align: center;">
                    	<p style="font-size: 20px; font-weight: 600;">Purbo Dhaka Road, Santahar, Adamdighi, Bogra.</p>							
                    	<p style="font-size: 24px; font-weight: 800;">( Stock Report )</p>							
                    	<p style="font-size: 16px; font-weight: 600;">Sunday 7th of December 2021 08:30:21 PM</p>							
                    </div>

                    <table class="table table-resposive table-bordered">
                    	<thead style="background-color: #566573;text-align: center;">
                    		<th style="color: #fff;"># SL</th>
                    		<th style="color: #fff;">Item Name</th>
                    		<th style="color: #fff;">Unit</th>
                    		<th style="color: #fff;">Purchase</th>
                    		<th style="color: #fff;">Sales</th>
                    		<th style="color: #fff;">Stock</th>
                    		<th style="color: #fff;">Stock Carton</th>
                    		<th style="color: #fff;">Buy Price</th>
                    		<th style="color: #fff;">Buy Stock Price</th>
                    		<th style="color: #fff;">Sales Price</th>
                    		<th style="color: #fff;">Sales Stock Price</th>
                    	</thead>
                    	<tbody>
                    		<tr style="text-align: center;">
                    			<td>1</td>
                    			<td>Rice</td>
                    			<td>2</td>
                    			<td>100</td>
                    			<td>0</td>
                    			<td>100</td>
                    			<td>100.00</td>
                    			<td>1400</td>
                    			<td>140000</td>
                    			<td>1600</td>
                    			<td>160000</td>
                    		</tr>

                    		<tr style="text-align: center;">
                    			<td>2</td>
                    			<td>Dal</td>
                    			<td>2</td>
                    			<td>50</td>
                    			<td>0</td>
                    			<td>50</td>
                    			<td>50.00</td>
                    			<td>1100</td>
                    			<td>110000</td>
                    			<td>1300</td>
                    			<td>130000</td>
                    		</tr>
                    		<tr style="font-size: 20px;font-weight:800;">
                    			<td colspan="8" style="text-align: right;">Total</td>
                    			<td style="text-align: center;">250000</td>
                    			<td></td>
                    			<td style="text-align: center;">290000</td>
                    		</tr>
                    	</tbody>
                    </table>
                </div>
              </div>
          	</div>
      	</div>



@endsection