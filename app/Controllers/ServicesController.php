<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class ServicesController extends BaseController
{
    public function service()
    {
        $serviceModel = new ServiceModel();
        $data['services'] = $serviceModel->orderBy('created_at', 'DESC')->findAll();
        return view('services/service-list', $data);
    }
    public function create_service()
    {
        $serviceModel = new ServiceModel();
        $data = ['name' => $this->request->getPost('name')];
        if (!empty($data)) {
            $serviceModel->insert($data);
            return redirect()->to(base_url('admin/service-list'))->with('sucess', 'Service is added sucessfully!');
        }
    }

    public function delete_service($id=null){
         $serviceModel=new ServiceModel();
         $service=$serviceModel->find($id);
         if(!$service){
         return redirect()->back()->with('error','service not found');
         }
         $serviceModel->delete($service);
         return redirect()->to(base_url('admin/service-list'))->with('sucees','service deleted successfully!');
    }
}
