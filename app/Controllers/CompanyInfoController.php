<?php
namespace App\Controllers;

use App\Models\CompanyInfoModel;

class CompanyInfoController extends BaseController{
    public function company_info(){
        $companyInfoModel=new CompanyInfoModel();
        $data['companies']=$companyInfoModel->findAll();
        return view('admin/company-info',$data);
    }
}