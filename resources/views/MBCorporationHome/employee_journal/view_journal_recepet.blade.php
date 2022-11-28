
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
                <h4 style="font-size: 16px;">Journal VOUCHER</h4>
              </td>
              @php
                $row = App\Companydetail::where('id','1')->first();
              @endphp
              <td style="width: 80px;">
                <img src="{{asset($row->company_logo)}}" style="height: 80px; width: 80px;float: right;">
              </td>
            </tr>

            @php
            	$payment = App\Journal::where('vo_no', $vo_no)->first();
            @endphp

            <tr style="border-bottom: 1px solid #eee;">
              <td colspan="6">
                <p>Voucher No.  &nbsp;  &nbsp;  &nbsp;  &nbsp;:  &nbsp;  &nbsp;{{$payment->vo_no}}</p>

              </td>
              <td colspan="2" style="text-align: left;">

                <p>Date &nbsp;  &nbsp;: &nbsp;  &nbsp;  {{ date('d-m-Y', strtotime($payment->date)) }}</p>
              </td>
            </tr>

            <tr style="font-size:14px;font-weight: 800;border-bottom: 1px solid #fff;">
              <td style="padding: 5px 5px;width: 350px;" colspan="3"><span style="border-bottom: 1px solid #000;">Particulars</span></td>
              <td style="padding: 5px 5px; text-align: left;"><span style="border-bottom: 1px solid #000;">DEBIT (TK.)</span></td>
              <td style="padding: 5px 5px; text-align: left;"><span style="border-bottom: 1px solid #000;">CRRDIT (TK.)</span></td>
              <td style="padding: 5px 5px;text-align: left;"><span style="border-bottom: 1px solid #000;">Narration</span></td>
              <td colspan="2"></td>
            </tr>
            @php
              $total_dr = 0;
              $contra_drcr = App\DemoContraJournalAddlist::where('vo_no',$vo_no)->with('ledger')->get();
              foreach($contra_drcr as $contra_row){
                if($contra_row->drcr < 2){
                  $total_dr = $total_dr + $contra_row->amount ;
                }
              }
            @endphp
            @foreach($contra_drcr as $contra_drcr_row)
            <tr style="border-bottom: 1px solid #fff;">
              <td style="padding: 5px 5px;width: 350px;" colspan="3">

                  {{$contra_drcr_row->ledger->account_name}}
              </td>
              @if($contra_drcr_row->drcr < 2)
              <td style="padding: 5px 5px; text-align: left;">{{$contra_drcr_row->amount}}.00</td>
              <td style="padding: 5px 5px; text-align: left;"></td>
              @elseif($contra_drcr_row->drcr > 1)
              <td style="padding: 5px 5px; text-align: left;"></td>
              <td style="padding: 5px 5px; text-align: left;">{{$contra_drcr_row->amount}}.00</td>
              @endif
              <td style="padding: 5px 5px;text-align: left;">{{$contra_drcr_row->note}}</td>
              <td colspan="2"></td>
            </tr>
            @endforeach
            <tr style="height: 150px;"></tr>

            <tr>
              <td style="padding: 5px 5px;width: 350px;" colspan="3"></td>
              <td  style="padding: 5px 5px; text-align: left;border: 1px solid #eee;">{{$total_dr}}.00</td>
              <td colspan="4" style="padding: 5px 5px; text-align: left;border: 1px solid #eee;">{{$total_dr}}.00</td>
            </tr>
              @php
                $contra = App\DemoContraJournalAddlist::where('vo_no',$vo_no)->first();
                class numbertowordconvertsconver {
                  function convert_number($number)
                  {
                      if (($number < 0) || ($number > 999999999))
                      {
                          throw new Exception("Number is out of range");
                      }
                      $giga = floor($number / 1000000);
                      // Millions (giga)
                      $number -= $giga * 1000000;
                      $kilo = floor($number / 1000);
                      // Thousands (kilo)
                      $number -= $kilo * 1000;
                      $hecto = floor($number / 100);
                      // Hundreds (hecto)
                      $number -= $hecto * 100;
                      $deca = floor($number / 10);
                      // Tens (deca)
                      $n = $number % 10;
                      // Ones
                      $result = "";
                      if ($giga)
                      {
                          $result .= $this->convert_number($giga) .  "Million";
                      }
                      if ($kilo)
                      {
                          $result .= (empty($result) ? "" : " ") .$this->convert_number($kilo) . " Thousand";
                      }
                      if ($hecto)
                      {
                          $result .= (empty($result) ? "" : " ") .$this->convert_number($hecto) . " Hundred";
                      }
                      $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
                      $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
                      if ($deca || $n) {
                          if (!empty($result))
                          {
                              $result .= " and ";
                          }
                          if ($deca < 2)
                          {
                              $result .= $ones[$deca * 10 + $n];
                          } else {
                              $result .= $tens[$deca];
                              if ($n)
                              {
                                  $result .= "-" . $ones[$n];
                              }
                          }
                      }
                      if (empty($result))
                      {
                          $result = "zero";
                      }
                      return $result;
                  }
                }

                $class_obj = new numbertowordconvertsconver();
                $convert_number = $total_dr;
                $word_num = $class_obj->convert_number($convert_number);
              @endphp
            <tr>
             <td style="padding: 5px 5px;width: 350px;" colspan="5">
                <p style="font-weight: 800;">Taka {{$word_num}} Only.</p>
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
	<a href="{{URL::to('/print_journal_recepet/'.$vo_no)}}" class="btn btn-info" style="color: #fff;">Print</a>
</div>
@endsection

