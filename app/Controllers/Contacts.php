<?php

namespace App\Controllers;

use App\Models\ContactLabelModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\URI;
use App\Models\ContactsModel;
use App\Models\ContactsLabelModel;


class Contacts extends ResourceController
{
    use ResponseTrait;
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

    //get all product
    public function index(){
        $q = $_GET['q'];
        $label = $_GET['label'];
        if(!empty($q) && !empty($label))
        {
            $model = new ContactsModel();
            $data = $model->search_data($q,$label);
            if(!empty($data))
            {
                return $this->respond($data, 200);
            }
            else
            {
                return $this->failNotFound('No Data Found');
            }
        }
        else{
            $model = new ContactsModel();
            $data = $model->get_all_data();
            return $this->respond($data, 200);
        }
        
    }

    public function show($id = null)
    {
        $model = new ContactsModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No Data Found with id ',$id);
        }
    }

    //insert contact
    public function create()
    {
        //$data = $this->request->getPost();
        $data = [
            'name' => $this->request->getPost('name'),
            'email'  => $this->request->getPost('email'),
            'phone'  => $this->request->getPost('phone'),
            'notes'  => $this->request->getPost('notes'),
        ];
        $validate = $this->validation->run($data, 'register');
        $errors = $this->validation->getErrors();

        if($errors){
            return $this->fail($errors);
        }

        $model = new ContactsModel();
        $model->created = date("Y-m-d H:i:s");


        if($model->insert($data))
        {
            $last_insert_id = $model->getInsertID();
            $modelContactLabel = new ContactLabelModel();
            $data_contact_label = ['contact_id' => $last_insert_id,'label_id' => $this->request->getPost('label_id')];
            $modelContactLabel->insert($data_contact_label);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Contact created successfully'
                ]
            ];
            return $this->respondCreated($response);
        }
    }

    public function update($id = null)
    {
        $model = new ContactsModel();
        $json = $this->request->getJSON();
        if($json){
            $data = [
                'name' => $json->name,
                'email' => $json->email,
                'phone' => $json->phone,
                'notes' => $json->notes,
                
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'notes' => $input['notes']
            ];
        }
        //insert to database
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => [
                'success' => 'Contact Updated Successfully'
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $model = new ContactsModel();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
            $response = [
                'status' => 200,
                'error'=> null,
                'messages' => [
                    'success' => 'contact data deleted'
                ]
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id ',$id);
        }
    }

}

?>
