<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ExcerciseModel;
use App\Models\StatModel;

class Stat extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

    use ResponseTrait;

    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id_excercise = null)
    {
        $model = new StatModel();
        $data = $model->where('excercise_id', $id_excercise)->first();
        if($data == null){
            return $this->failNotFound('No stat found');
        }
        return $this->respond($data);
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
        //
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
    public function update($id_excercise = null)
    {
        $model = new StatModel();
        $stat = $model->where('excercise_id', $id_excercise)->first();
        $data = [
            'excercise_id' => $id_excercise,
            'set' => $this->request->getVar('set'),
            'reps' => $this->request->getVar('reps'),
            'duration' => $this->request->getVar('duration'),
            'weight' => $this->request->getVar('weight'),
        ];

        $rules = [
            'excercise_id' => 'required',
        ];

        if(!$this->validate($rules)){
            return $this->fail($this->validator->getErrors());
        }

        $model->update($stat['stat_id'], $data);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Stat updated'
            ]
        ];
        return $this->respondCreated($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
