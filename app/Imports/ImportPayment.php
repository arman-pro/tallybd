<?php

namespace App\Imports;

use App\Payment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportPayment implements ToModel, WithValidation,WithHeadingRow,SkipsEmptyRows 
{
     use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Payment([
            'date' => $row[0],
            'ledger' => $row[1],
            'payment_mode' => $row[2],
            'amount' => $row[3],
            'note' => $row[4],
        ]);
    }
    
    public function rules(): array
    {
        return [
            'date' => 'required',
            'ledger' => 'required',
            'amount' => 'required|numeric',
        ];
    }
}
