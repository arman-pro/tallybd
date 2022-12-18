@extends('MBCorporationHome.apps_layout.layout')
@section("title", "Employee Ledger")

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" >
            <div class="card">
                <div class="card-header bg-success text-light fw-bold">
                    <h4 class="card-title">Employee Ledger</h4>
                </div>
                <div class="card-body" id="main_table">
                    <table class="table table-bordered"
                    style="border: 1px solid #444242;text-align: center;">
    
                    <td colspan="7" style="text-align: center;">
                            @php
                                $company = App\Companydetail::get();
                                $openDr = 0;
                                $openCr = 0;
                                $openBalance = 0;
                                $opening = App\AccountLedgerTransaction::where('ledger_id',$employee->id)->where('date', '<=' ,request()->from_date
                                    )->get()->unique('account_ledger__transaction_id');
                                    
                                $salaryDetails = App\SalaryDetails::where('employee_id', request()->ledger_id)->where('salary_date', '<' ,request()->from_date)->get();
                                $salaryPayment = App\SalaryPayment::where('employee_id', request()->ledger_id)->where('date', '<' ,request()->from_date)->get();
    
                                if($opening){
                                    $openDr += $opening->sum('debit');
                                    $openCr += $opening->sum('credit');
    
                                }
                                if($salaryDetails){
                                    $openCr += $salaryDetails->sum('salary');
                                }
                                if($salaryPayment){
                                    foreach ($salaryPayment as $key => $salary) {
                                        if($salary->payment_by){
                                            $openDr += $salary->amount;
                                        }else{
                                            $openCr += $salary->amount;
                                        }
                                    }
                                }
                                $openBalance = $openDr - $openCr;
    
                            @endphp
    
                            @foreach($company as $company_row)
    
                            <h3 style="margin:0;padding:0;text-align:center;padding:0;">{{$company_row->company_name}}</h3>
                            <p style="margin:0;padding:0;text-align:center;padding:0;">{{$company_row->company_address}}<br>{{$company_row->phone}}Call:
                                {{$company_row->mobile_number}}</p>
                            @endforeach
                            <h4 style="margin:0;padding:0;text-align:center;padding:0;">Employee Ledger</h4>
    
                            <p style="text-align: left;margin:0;padding:0;">
                                Account : {{ $employee->name }}
                                <span style="float:right;">From : {{request()->from_date." to ".request()->to_date}}</span>
                            </p>
                            <p style="text-align: left;margin:0;padding:0;">
                                Address: {{ $employee->present_address }} 
                                <span style="float:right;">
                                    @if($openBalance > 0)
                                    Opening Bal. = Tk. {{ number_format($openBalance, 2)}} (Dr)
                                    
                                    @elseif($openBalance < -1) Opening Bal.=Tk. {{ number_format($openBalance*-1, 2)}} (Cr) @else
                                        Opening Bal.=Tk. 0 
                                    @endif
                                </span>
                            </p>
                            <p style="text-align: left;margin:0;padding:0;">Mobile No: {{ $employee->mobile }} </p>
                    </td>
                    </tr>
    
                    <tr style="font-size:14px;font-weight: 800;">
                        <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px">Date</td>
                        <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px;">Type</td>
                        <td style="border: 1px solid #444242;padding: 5px 5px;width: 100px;">Vch No.</td>
                        <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">Debit (Tk)</td>
                        <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: right;">Credit (TK)</td>
                        <td style="border: 1px solid #444242;padding: 5px 5px;width: 150px;text-align: center;">Balance (TK)</td>
    
                    </tr>
                    @php
                    
                    
                    
                    $dr = 0;
                    $cr = 0;
                    $newBalance = 0;
                    $salaryDetails = App\SalaryDetails::where('employee_id', request()->ledger_id)->whereBetween('salary_date',[request()->from_date,request()->to_date])->get();
                  
                    $salaryDetails = $salaryDetails->map(function($value,$i){
                        return [
                            'date'=>date('y-m-d', strtotime($value->salary_date)),
                            'type'=>'Sal/Generate',
                            'dr'=>0,
                            'cr'=> $value->salary,
                            'vo_no' => $value->salary_->vo_no,
                        ];
                    });
    
                    $salaryPayment = App\SalaryPayment::where('employee_id', request()->ledger_id)->whereBetween('date',[request()->from_date,request()->to_date])->get();
    
                    $salaryPayment = $salaryPayment->map(function($value,$i){
                        if ($value->payment_by) {
                            return [
                                'date'=>date('y-m-d', strtotime($value->date)),
                                'type'=>'Payment',
                                'dr'=>$value->amount,
                                'cr'=> 0,
                                'vo_no' => $value->vo_no,
                            ];
                        }else{
                            return [
                                'date'=>date('y-m-d', strtotime($value->date)),
                                'type'=>'Receive',
                                'dr'=> 0,
                                'cr'=>$value->amount,
                                 'vo_no' => $value->vo_no,
                            ];
                        }
    
                    });
                     
                    $account_tran=[];
                    foreach ($salaryDetails as $key => $Details) {
                        array_push($account_tran, $Details);
                    }
                    foreach ($salaryPayment as $key => $Payment) {
                        array_push($account_tran, $Payment);
                    }
                     
                    $account_tran = collect($account_tran)->sortBy('date')->all();
                        $i = 0;
                    @endphp
                    @foreach($account_tran as $key => $account_tran_row)
                    @php
                      
                        if($openBalance > 0 && $i == 0){
                            $dr += $openBalance;
                            
                        }elseif($openBalance < 0 && $i == 0){
                            $cr -= $openBalance;
                        }
                       $i++;
                        $dr += $account_tran_row['dr'] ?? 0;
                        $cr += $account_tran_row['cr'] ?? 0;
                        
                       
                        $newBalance = $dr - $cr;
                    
                    @endphp
                    <tr style="front-size:14px">
                        <td>{{ date('d-m-y', strtotime($account_tran_row['date'])) }}</td>
                        <td>{{  $account_tran_row['type'] }}</td>
                        <td>{{$account_tran_row['vo_no'] ?? 'N/A'}}</td>
                        <td  style="text-align: right">{{  number_format($account_tran_row['dr'], 2)}}</td>
                        <td  style="text-align: right">{{  number_format($account_tran_row['cr'], 2) }}</td>
                        <td  style="text-align: right">
                            @if($newBalance > 1)
                            {{ number_format($newBalance, 2)." ("."DR)"}}
                            @else
                            {{number_format($newBalance * -1, 2)." ("."CR)"}}
                            @endif
                        </td>
                    </tr>
    
                    @endforeach
                    @php
                    // dd($account_tran);
                    if(count($account_tran) == 0){
                        if($openBalance > 0){
                            $newBalance += $openBalance;
                        }else{
                            $newBalance -= $openBalance;
                        }
                    }
    
                    @endphp
    
    
                    <tr>
                        <td colspan="3" style="text-align: right"><strong>Total</strong></td>
                        <td style="text-align: right">{{ number_format($dr, 2) }}</td>
                        <td style="text-align: right">{{ number_format($cr, 2) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right"><strong>Closing Balance </strong></td>
                        <td style="text-align: right">
                            @if($newBalance >1 )
                            <span style="border-bottom: 3px double black">{{ number_format($newBalance, 2)." ("."DR)"}}</span>
                            @else
                            <span style="border-bottom: 3px double black"> {{number_format($newBalance*-1, 2)." ("."CR)"}} </span>
                            @endif
                        </td>
                    </tr>
                </table>
                </div>
                <div class="card-footer text-center">
                    <a href="javascript:void(0)" onclick="printData()" class="btn btn-success btn-lg text-light fw-bold"><i class="fa fa-print"></i> Print</a>
                </div>
            </div>
            
        </div>
    </div>
</div>
<script lang='javascript'>
    function printData(){
        var print_ = document.getElementById("main_table");
        var body = $("body").html();
        $('body').html(print_.outerHTML);
        window.print();
        $('body').html(body);
        // win = window.open("");
        // win.document.write(print_.outerHTML);
        // win.print();
        // win.close();
    }
</script>

@endsection
