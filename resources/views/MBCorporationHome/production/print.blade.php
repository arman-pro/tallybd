@extends('MBCorporationHome.apps_layout.print_layout')
@section('title', "Production".date('d_m_y'))

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
</style>
@endpush

@section('container')
<div class="invoice-title">
    <div>
        &nbsp;
    </div>
    <div class="font-bold underline uppercase">
        production Sheet
    </div>
    <div>
        &nbsp;
    </div>
</div>
<div class="account-title">
    <table class="head_table">
        <tbody>
            <th class="text-left" style="width: 80px;">Ref/Batch:</th>
                <td style="width: 60%;">{{$workingOrder->refer_no}}</td>
               <th class="text-right">Voucher No:</th>
                <td> {{$productionOrder->vo_no}}</td>
            </tr>
            
                <th class="text-left" style="width: 80px;">&nbsp;</th>
                <td style="width: 60%;">&nbsp;</td>
                <th class="text-right">Date:</th>
                <td> {{date('d-m-Y', strtotime($productionOrder->date))}} </td>
               
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
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Sl</th>
                <th style="width: 30%;border-right: 1px solid #eee;padding: 5px;">Working Order Description</th>
                <th style="width: 10%;border-right: 1px solid #eee;padding: 5px;">Qty</th>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</th>
                <th style="width: 15%;border-right: 1px solid #eee;padding: 5px;">Rate</th>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</th>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;"></th>
                <th style="width: 20%;">Amount</th>
            </tr>

            @php
            $i = 0;
            $total_qty = 0;
            $total_amount = 0;
            $DemoProductAddOnVoucher = App\DemoProductProduction::where('vo_no',$workingOrder->vo_no)->get();
            @endphp
            @foreach($DemoProductAddOnVoucher as $DemoProductAddOnVoucher_row)
            @php
            $i++;
            $total_qty = $total_qty + $DemoProductAddOnVoucher_row->qty ;
            $total_amount = $total_amount + ($DemoProductAddOnVoucher_row->price *
            $DemoProductAddOnVoucher_row->qty) ;

            $item = App\Item::where('id',$DemoProductAddOnVoucher_row->item_id)->first();
            @endphp
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;"> {{$i}}</td>
                <td style="width: 30%;border-right: 1px solid #eee;text-align: left;">
                    {{$item->name." ".$item->unit->name}}</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($DemoProductAddOnVoucher_row->qty,
                    2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 15%;border-right: 1px solid #eee;">{{$DemoProductAddOnVoucher_row->price}}
                </td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($DemoProductAddOnVoucher_row->price *
                    $DemoProductAddOnVoucher_row->qty , 2)}}</td>
            </tr>
            @endforeach
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;height: 150px;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;"></td>
                <td style="width: 10%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;"></td>
            </tr>

            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                <td style="width: 50px;border-right: 1px solid #eee;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;">Total</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($total_qty, 2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($total_amount, 2)}}</td>
            </tr>
            <tr style="border-top: 2px solid #eee;text-align: center;">
                <th colspan="2" style="width: 50px;border-right: 1px solid #eee;">Title</th>
                <th colspan="2" style="width: 10%;border-right: 1px solid #eee;">Price</th>
                <th colspan="2" style="width: 20%;border-right: 1px solid #eee;">Qty</th>
                <th colspan="2" style="width: 20%;border-right: 1px solid #eee;">Total</th>
            </tr>
             @php
                $stotal = 0;
            @endphp
            @foreach($costinfo as $row )
            @php
            $stotal +=$row->total;
            @endphp
            <tr style="border-top: 2px solid #eee;text-align: center;">
                <td colspan="2" style="width: 50px;border-right: 1px solid #eee;">{{$row->title}}</td>
                <td colspan="2" style="width: 10%;border-right: 1px solid #eee;">{{$row->price}}</td>
                <td colspan="2" style="width: 20%;border-right: 1px solid #eee;">{{$row->qty}}</td>
                <td colspan="2" style="width: 20%;border-right: 1px solid #eee;">{{$row->total}}</td>
            </tr>
            @endforeach
                    
                <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                    <td style="width: 50px;border-right: 1px solid #eee;"></td>
                    <td style="width: 30%;border-right: 1px solid #eee;">Total</td>
                    <td style="width: 10%;border-right: 1px solid #eee;"> </td>
                    <td style="width: 5%;border-right: 1px solid #eee;"></td>
                    <td style="width: 15%;border-right: 1px solid #eee;"></td>
                    <td style="width: 5%;border-right: 1px solid #eee;"></td>
                    <td style="width: 5%;border-right: 1px solid #eee;"></td>
                    <td style="width: 20%;">{{ number_format($total_amount+$stotal, 2)}}</td>
                </tr>

                    <tr style="border-top: 1px solid #eee;text-align: center;">
                        <td colspan="7" style="text-align: left;padding-left: 10px;">
                            Amount In Words :
                            <span style="font-size: 16px;font-weight: 800;">@php echo App\Helpers\Helper::NoToWord($total_amount+$stotal); @endphp Taka Only</span>
                        </td>

                    </tr>

       

       


            <tr style="border-top: 1px solid #eee;text-align: center;font-weight: 800;">
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Sl</th>
                <th style="width: 30%;border-right: 1px solid #eee;padding: 5px;">Production  Description</>
                <th style="width: 10%;border-right: 1px solid #eee;padding: 5px;">Qty</>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</>
                <th style="width: 15%;border-right: 1px solid #eee;padding: 5px;">Rate</>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;">Per</>
                <th style="width: 5%;border-right: 1px solid #eee;padding: 5px;"></>
                <th style="width: 20%;">Amount</>
            </tr>

            @php
            $i = 0;
            $total_qty = 0;
            $total_amount = 0;
            $DemoProductAddOnVoucher = App\DemoProductProduction::where('vo_no',$productionOrder->vo_no)->get();
            @endphp
            @foreach($DemoProductAddOnVoucher as $DemoProductAddOnVoucher_row)
            @php
            $i++;
            $total_qty = $total_qty + $DemoProductAddOnVoucher_row->qty ;
            $total_amount = $total_amount + ($DemoProductAddOnVoucher_row->price *
            $DemoProductAddOnVoucher_row->qty) ;

            $item = App\Item::where('id',$DemoProductAddOnVoucher_row->item_id)->first();
            @endphp
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;"> {{$i}}</td>
                <td style="width: 30%;border-right: 1px solid #eee;text-align: left;">
                    {{$item->name." ".$item->unit->name}}</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($DemoProductAddOnVoucher_row->qty,
                    2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 15%;border-right: 1px solid #eee;">{{$DemoProductAddOnVoucher_row->price}}
                </td>
                <td style="width: 5%;border-right: 1px solid #eee;">{{$item->unit->name}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($DemoProductAddOnVoucher_row->price *
                    $DemoProductAddOnVoucher_row->qty , 2)}}</td>
            </tr>
            @endforeach
            <tr style="border-top: 1px solid #eee;text-align: center;">
                <td style="width: 50px;border-right: 1px solid #eee;height: 150px;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;"></td>
                <td style="width: 10%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;"></td>
            </tr>

            <tr style="border-top: 1px solid #eee;text-align: center;font-size: 16px;font-weight: 800;">
                <td style="width: 50px;border-right: 1px solid #eee;"></td>
                <td style="width: 30%;border-right: 1px solid #eee;">Total</td>
                <td style="width: 10%;border-right: 1px solid #eee;">{{ number_format($total_qty, 2)}}</td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 15%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 5%;border-right: 1px solid #eee;"></td>
                <td style="width: 20%;">{{ number_format($total_amount, 2)}}</td>
             </tr>
			</table> 
			 Amount In Words :@php echo App\Helpers\Helper::NoToWord($total_amount+$stotal); @endphp Taka Only
                          <br>                                        
                            <p>Aurhorised Signatory</p>
                        
                   

       
    </div>

</body>
</html>
@endsection