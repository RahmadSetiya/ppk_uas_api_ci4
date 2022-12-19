<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ExcerciseModel;
use App\Models\StatModel;

class Excercise extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

    use ResponseTrait;

    public function index()
    {
        $model = new ExcerciseModel();
        $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();
        if($data == null){
            return $this->failNotFound('No excercises found');
        }
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $model = new ExcerciseModel();
        if($id == null){
            $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();
            if($data == null){
                return $this->failNotFound('No excercises found');
            }
            return $this->respond($data);
        }

        $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->where('excercises.excercise_id', $id)->first();
        if($data == null){
            return $this->failNotFound('No excercise found');
        }
        return $this->respond($data);
    }

    public function show_by_user($user_id = null)
    {
        $model = new ExcerciseModel();
        if($user_id == null){
            $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();
            if($data == null){
                return $this->failNotFound('No excercises found');
            }
            return $this->respond($data);
        }

        $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->where('excercises.user_id', $user_id)->findAll();
        if($data == null){
            return $this->failNotFound('No excercise found');
        }
        return $this->respond($data);
    }

    public function show_by_type($type = null)
    {
        $model = new ExcerciseModel();
        if($type == null){
            $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();
            if($data == null){
                return $this->failNotFound('No excercises found');
            }
            return $this->respond($data);
        }

        $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->where('excercises.type', $type)->findAll();
        if($data == null){
            return $this->failNotFound('No excercise found');
        }
        return $this->respond($data);
    }

    public function show_by_done($user_id = null)
    {
        $model = new ExcerciseModel();
        if($user_id == null){
            $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();
            if($data == null){
                return $this->failNotFound('No excercises found');
            }
            return $this->respond($data);
        }

        $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->where('excercises.user_id', $user_id)->where('excercises.is_done', true)->findAll();
        if($data == null){
            return $this->failNotFound('No excercise found');
        }
        return $this->respond($data);
    }

    public function show_by_not_done($user_id = null)
    {
        $model = new ExcerciseModel();
        if($user_id == null){
            $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();
            if($data == null){
                return $this->failNotFound('No excercises found');
            }
            return $this->respond($data);
        }

        $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->where('excercises.user_id', $user_id)->where('excercises.is_done', false)->findAll();
        if($data == null){
            return $this->failNotFound('No excercise found');
        }
        return $this->respond($data);
    }

    public function show_by_date($date = null)
    {
        $date_fixit = explode('_', $date);
        $date = $date_fixit[0] . '-' . $date_fixit[1] . '-' . $date_fixit[2];
        $model = new ExcerciseModel();
        if($date == null){
            $data = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();
            if($data == null){
                return $this->failNotFound('No excercises found');
            }
            return $this->respond($data);
        }

        $data_all = $model->join('stats', 'stats.excercise_id = excercises.excercise_id')->findAll();

        $data_this_date = [];
        foreach($data_all as $data){
            if(explode(' ', $data['datetime'])[0] == $date){
                array_push($data_this_date, $data);
            }
        }

        if($data_this_date == null){
            return $this->failNotFound('No excercise found');
        }
        return $this->respond($data_this_date);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        // create new excercise
        $model = new ExcerciseModel();

        $data = [
            'user_id' => session()->get('user_id'),
            'name' => $this->request->getVar('name'),
            'type' => $this->request->getVar('type'),
            'datetime' => date('Y-m-d H:i:s', strtotime($this->request->getVar('datetime'))),
            'is_done' => false,
        ];

        $rules = [
            'name' => 'required',
            'type' => 'required',
            'datetime' => 'required',
        ];

        if(!$this->validate($rules)){
            return $this->fail($this->validator->getErrors());
        }

        $model->insert($data);
        $stat = new StatModel();
        $stat->insert([
            'excercise_id' => $model->getInsertID(),
        ]);

        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Excercise created'
            ]
        ];
        return $this->respondCreated($response);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        // update excercise
        $model = new ExcerciseModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'type' => $this->request->getVar('type'),
            'datetime' => date('Y-m-d H:i:s' ,strtotime($this->request->getVar('datetime'))),
            'is_done' => false,
        ];

        $rules = [
            'name' => 'required',
            'type' => 'required',
            'datetime' => 'required',
        ];

        if(!$this->validate($rules)){
            return $this->fail($this->validator->getErrors());
        }

        $model->update($id, $data);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Excercise updated'
            ]
        ];
        return $this->respondCreated($response);
    }

    public function update_is_done($id)
    {
        // update excercise
        $model = new ExcerciseModel();
        $data = [
            'is_done' => true,
        ];

        $excercise = $model->find($id);

        if($excercise == null){
            return $this->failNotFound('No excercise found');
        }

        if($excercise['user_id'] == session()->get('user_id') || session()->get('role') == 'admin'){
            $model->update($id, $data);
            $response = [
                'status' => 201,
                'error' => null,
                'messages' => [
                    'success' => 'Excercise is done updated'
                ]
            ];
            return $this->respondCreated($response);
        } else{
            return $this->failUnauthorized('You are not authorized to do this action');
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        // delete excercise
        $model = new ExcerciseModel();
        $data = $model->find($id);
        
        if($data == null){
            return $this->failNotFound('No excercise found');
        }

        if($data['user_id'] != session()->get('user_id') && session()->get('role') != 'admin'){
            return $this->failUnauthorized('You are not authorized to do this action');
        }


        $model->delete($id);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Excercise deleted'
            ]
        ];
        return $this->respondDeleted($response);
    }
}
