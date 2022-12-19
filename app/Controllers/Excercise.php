<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Excercise as ExcerciseModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        //
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if($header){
            try {
                $token = explode(' ', $header)[1];
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                $model = new ExcerciseModel();
                $excercise = $model->where('user_id', $decoded->data->user_id)->findAll();
                if($excercise){
                    return $this->respond($excercise);
                } else {
                    return $this->failNotFound('No excercise found');
                }
            } catch (\Exception $e) {
                return $this->failServerError('Something went wrong');
            }
        } else {
            return $this->failUnauthorized('No required token');
        }
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
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
    public function update($id = null)
    {
        //
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
