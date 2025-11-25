<?php

namespace App\Controllers;

use App\Models\CityModel;
use App\Models\CompanyInfoModel;
use App\Models\CountryModel;
use App\Models\StateModel;

class CompanyInfoController extends BaseController
{
    public function company_info()
    {
        $companyInfoModel = new CompanyInfoModel();
        $data['companies'] = $companyInfoModel->findAll();
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
}
