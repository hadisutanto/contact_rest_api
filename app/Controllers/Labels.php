<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\LabelModel;

class Labels extends ResourceController
{
    use ResponseTrait;
    //get all product
    public function index(){
        $model = new LabelModel();
        $data = $model->findAll();
        return $this->respond($data,200);
    }

    public function show($id = null)
    {
        $model = new LabelModel();
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
        $model = new LabelModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
        ];

        $model->insert($data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => [
                'success' => 'Data Saved'
            ]
        ];

        return $this->respondCreated($response,200);
    }

    public function update($id = null)
    {
        $model = new LabelModel();
        $json = $this->request->getJSON();
        if($json){
            $data = [
                'name' => $json->name,
                'slug' => $json->slug,
                
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'name' => $input['name'],
                'slug' => $input['slug'],
            ];
        }
        //insert to database
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $model = new LabelModel();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
            $response = [
                'status' => 200,
                'error'=> null,
                'messages' => [
                    'success' => 'data deleted'
                ]
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id ',$id);
        }
    }

}

?>
