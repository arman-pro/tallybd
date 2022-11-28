<?php

namespace App\Http\Controllers\MBCorporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Companydetail;
use App\FinancialYear;
use Image;

class CompanyDetailcompany_detailsController extends Controller
{
    public function index()
    {
        $years = FinancialYear::all();
        $CompanyDetails = Companydetail::where('id', '1')->get();
        return view('MBCorporationHome.company_details.index', compact('CompanyDetails','years'));
    }

    public function Update_company_details(Request $request, $id)
    {

        if (!empty($request->company_logo)) {

            $company_logo = $request->file('company_logo');
            $name_gen = hexdec(uniqid()) . '.' . $company_logo->getClientOriginalExtension();
            Image::make($company_logo)->resize(270, 270)->save('MBCorSourceFile/upload_img/' . $name_gen);
            $img_url = 'MBCorSourceFile/upload_img/' . $name_gen;

            $company=Companydetail::where('id', $id)->Update([
                'company_name' => $request->company_name,
                'contry_name' => $request->contry_name,
                'mailing_name' => $request->mailing_name,
                'email_id' => $request->email_id,
                'website_name' => $request->website_name,
                'phone' => $request->phone,
                'mobile_number' => $request->mobile_number,
                'booking_date' => $request->booking_date,
                'company_address' => $request->company_address,
                'company_des' => $request->company_des,
                'company_logo' => $img_url,
                'financial_year_id' => $request->financial_year_id
            ]);
        } else {

            $company= Companydetail::where('id', $id)->Update([
                'company_name' => $request->company_name,
                'contry_name' => $request->contry_name,
                'mailing_name' => $request->mailing_name,
                'email_id' => $request->email_id,
                'website_name' => $request->website_name,
                'phone' => $request->phone,
                'mobile_number' => $request->mobile_number,
                'booking_date' => $request->booking_date,
                'company_address' => $request->company_address,
                'company_des' => $request->company_des,
                'company_logo' => $request->old_company_logo,
                'financial_year_id' => $request->financial_year_id

            ]);
        }
        return back();
    }


    public function yearSetting(Request $request)
    {
        $financialYear = FinancialYear::all();
        return view('MBCorporationHome.company_details.yearsetting', compact('financialYear'));
    }

    public function yearSettingstore (Request $request)
    {
        FinancialYear::create($request->all());
        return back();
    }
    public function yearSettingActive ($id)
    {
        FinancialYear::where('status', 1)->update(['status' => 0 ]);
        FinancialYear::find($id)->update(['status' => 1 ]);
        return back();
    }
}
