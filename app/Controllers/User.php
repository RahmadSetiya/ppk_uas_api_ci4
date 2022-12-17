<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class User extends ResourceController
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
                $model = new UserModel();
                $user = $model->where('user_id', $decoded->data->user_id)->first();
                if($user){
                    return $this->respond($user);
                } else {
                    return $this->failNotFound('No user found');
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
        // update user
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if($header){
            try {
                $token = explode(' ', $header)[1];
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                $model = new UserModel();
                $user = $model->where('user_id', $decoded->data->user_id)->first();
                if($user){
                    $data = [
                        'fullname' => $this->request->getVar('fullname'),
                        'username' => $this->request->getVar('username'),
                        'email' => $this->request->getVar('email'),
                        'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    ];

                    $rules = [
                        'fullname' => 'required|min_length[3]|max_length[50]',
                        'username' => 'required|min_length[3]|max_length[20]',
                        'email' => 'required|valid_email',
                        'password' => 'required|min_length[6]|max_length[255]',
                        'pass_confirm' => 'matches[password]'
                    ];
                    if(!$this->validate($rules)){
                        return $this->fail($this->validator->getErrors());
                    }

                    $model->update($user['user_id'], $data);
                    $user['fullname'] = $data['fullname'];
                    $user['username'] = $data['username'];
                    $user['email'] = $data['email'];
                    $user['password'] = $data['password'];

                    return $this->respond($user);
                } else {
                    return $this->failNotFound('No user found');
                }
            } catch (\Exception $e) {
                return $this->failServerError('Something went wrong');
            }
        } else {
            return $this->failUnauthorized('No required token');
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        // delete user
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if($header){
            try {
                $token = explode(' ', $header)[1];
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                $model = new UserModel();
                $user = $model->where('user_id', $decoded->data->user_id)->first();
                if($user['role'] == 'admin'){
                    $model->delete($id);
                    //success response
                    return $this->respondDeleted(['status' => 200, 'message' => 'User deleted successfully']);
                } else {
                    return $this->failUnauthorized('You are not authorized');
                }
            } catch (\Exception $e) {
                return $this->failServerError('Something went wrong');
            }
        } else {
            return $this->failUnauthorized('No required token');
        }
    }
}
