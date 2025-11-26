<?php

namespace App\Controllers;

use App\Models\BanksModel;
use App\Models\CityModel;
use App\Models\CompanyInfoModel;
use App\Models\CountryModel;
use App\Models\HSNCodeModel;
use App\Models\StateModel;

class CompanyInfoController extends BaseController
{
    public function company_info()
    {
        $companyModel = new \App\Models\CompanyInfoModel();
        $termsModel   = new \App\Models\TermsModel();
        $bankModel = new BanksModel();
        $data['companies']   = $companyModel->findAll();
        $data['latestTerms'] = $termsModel->orderBy('id', 'DESC')->first();
        $data['banks']       = $bankModel->first();
        return view('admin/company-info', $data);
    }

    public function save()
    {
        $companyInfoModel = new CompanyInfoModel();
        $countryModel = new CountryModel();
        $stateModel = new StateModel();
        $cityModel = new CityModel();

        $logoFile = $this->request->getFile('logo');
        $logoName = '';

        $logoFile = $this->request->getFile('logo');

        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $logoName = $logoFile->getRandomName();
            $logoFile->move(FCPATH . 'assets/uploads/company/', $logoName);
            $data['logo'] = $logoName;
        }

        $countryId = $this->request->getPost('country');
        $stateId = $this->request->getPost('state');
        $cityId = $this->request->getPost('city');


        $data = [
            'company_name' => $this->request->getPost('company_name'),
            'address'      => $this->request->getPost('address'),
            'gst_number'   => $this->request->getPost('gst_number'),
        ];
        $data['country'] = $countryModel->where('id', $countryId)->findColumn('name')[0] ?? null;
        $data['state']  = $stateModel->where('id', $stateId)->findColumn('name')[0] ?? null;
        $data['city']   = $cityModel->where('id', $cityId)->findColumn('name')[0] ?? null;

        if ($logoName !== null) {
            $data['logo'] = $logoName;
        }

        $companyInfoModel->insert($data);

        return redirect()->to(base_url('admin/company-manage'))->with('success', 'Company Added Successfully!');
    }
    public function getCompanies()
    {
        $companyModel = new CompanyInfoModel();
        $companies = $companyModel->findAll();
        return $this->response->setJSON($companies);
    }
    public function save_terms()
    {
        $termsModel = new \App\Models\TermsModel();
        $content = $this->request->getPost('terms_content');
        $termsModel->update(1, ['content' => $content]); // stores version

        return redirect()->to('/admin/company-manage')->with('success', 'Terms Updated Successfully!');
    }
    public function save_bank_details()
    {
        $bankModel = new BanksModel();
        $data = [
            'bank_name' => $this->request->getPost('bank_name'),
            'account_holder_name' => $this->request->getPost('account_holder_name'),
            'account_no' => $this->request->getPost('account_no'),
            'ifsc_code' => $this->request->getPost('ifsc_code')
        ];
        $bankModel->update(1, $data);
        return redirect()->to('/admin/company-manage')->with('Success', 'Bank details save Successfully!');
    }

    public function hsn_code()
    {
        $hsnCodeModel = new HSNCodeModel();
        $hsncode = $hsnCodeModel->findAll();
        return $this->response->setJson($hsncode);
    }
    public function save_hsncode()
    {
        $hsnCodeModel = new HSNCodeModel();
         $this->request->getPost('hsn_code');
        
           $hsnCodeModel->insert([
            'code'=>$this->request->getPost('hsn_code'),
            'description'=>$this->request->getPost('description')
        ]);
          return redirect()->to('/admin/company-manage')->with('Success', 'Hsn code is store successfully!');

    }
}
