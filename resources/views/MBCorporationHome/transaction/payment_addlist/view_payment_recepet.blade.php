
@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div style="background: #fff;padding: 2%;" id="printTable">
	 <table class="table" style="border: 1px solid #eee;" >
           <tr>
              <td colspan="7" style="text-align: center;">
                @php
                  $company = App\Companydetail::get();
                @endphp

                @foreach($company as $company_row)

                <h3 style="font-weight: 800;">{{$company_row->company_name}}</h3>
                <p>{{$company_row->company_address}}<br> Tel: {{$company_row->phone}}, Call: {{$company_row->mobile_number}}</p>
                @endforeach
                <h4 style="font-size: 16px;">PAYMENT VOUCHER</h4>
              </td>
              @php
                $row = App\CompanyDetail::where('id','1')->first();
              @endphp
              <td style="width: 80px;">
                <img src="{{asset($row->company_logo)}}" style="height: 80px; width: 80px;float: right;">
              </td>
            </tr>

            @php
            	$payment = App\Payment::where('vo_no', $vo_no)->first();
            	 

            @endphp

            <tr style="border-bottom: 1px solid #eee;">
              <td colspan="6">
                <p>Voucher No.  &nbsp;  &nbsp;  &nbsp;  &nbsp;:  &nbsp;  &nbsp;{{$payment->vo_no}}</p>
                  <p>Payment Mode  &nbsp;&nbsp;:  &nbsp;  &nbsp;
                  {{-- @php
                      $account_ledger = App\AccountLedger::where('account_ledger_id',$payment->payment_mode)->first();
                  @endphp --}}
                  {{ optional($payment->paymentMode)->account_name??' ' }}
                </p>
              </td>
              <td colspan="2" style="text-align: left;">
                <p>Date &nbsp;  &nbsp;: &nbsp;  &nbsp;{{$payment->date}}</p>
              </td>
            </tr>

            <tr style="font-size:14px;font-weight: 800;border-bottom: 1px solid #fff;">
              <td style="padding: 5px 5px;width: 350px;" colspan="3"><span style="border-bottom: 1px solid #000;">Particulars</span></td>
              <td style="padding: 5px 5px; text-align: left;"><span style="border-bottom: 1px solid #000;">Amount</span></td>
              <td style="padding: 5px 5px;text-align: left;"><span style="border-bottom: 1px solid #000;">Narration</span></td>
              <td colspan="3"></td>
            </tr>


              <tr style="border-bottom: 1px solid #fff;">
              <td style="padding: 5px 5px;width: 350px;" colspan="3">
                 {{-- @php
                      $account_ledger = App\AccountLedger::where('account_ledger_id',$payment->account_name)->first();
                  @endphp --}}
                  {{ optional($payment->accountMode)->account_name??' ' }}
              </td>
              <td style="padding: 5px 5px; text-align: left;">{{$payment->amount}}.00</td>
              <td style="padding: 5px 5px;text-align: left;">{{$payment->description}}</td>
              <td colspan="3"></td>
            </tr>

            <tr style="height: 150px;"></tr>

            <tr>
              <td style="padding: 5px 5px;width: 350px;" colspan="3"></td>
              <td colspan="5" style="padding: 5px 5px; text-align: left;border: 1px solid #eee;">{{$payment->amount}}.00</td>
            </tr>

            <tr>
             <td style="padding: 5px 5px;width: 350px;" colspan="5">
                  
                <p style="font-weight: 800;">@php echo App\Helpers\Helper::NoToWord($payment->amount); @endphp Taka Only</p>
               <p>Printed on
                  @php
                    $dt = new DateTime('now', new DateTimezone('Asia/Dhaka'));
                     echo $dt->format('j-m-Y , g:i a');
                  @endphp
                </p>
             </td>
             <td style="font-weight: 800;text-align: left;"> <br><br> Receiver By</td>
             <td style="font-weight: 800;text-align: left;"> <br><br> Verified By</td>
             <td style="font-weight: 800;text-align: left;"> <br><br> Authorised By</td>
            </tr>

       </table>
</div>
<div style="background: #fff; text-align: center;color: #fff;">
    {{-- <button class="btn btn-info print"  >Print</button> --}}
	<a href="{{URL::to('/print_payment_recepet/'.$vo_no)}}" class="btn btn-info" style="color: #fff;">Print</a>
</div>
<script>
    $('.print').click(function(){
        // alert('23423');
        // window.print();
    }
    );
</script>

@endsection

