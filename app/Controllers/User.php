<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

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
        
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        if(session()->get('role') == 'admin'){
            if($id == null){
                $model = new UserModel();
                $user = $model->findAll();
                return $this->respond($user);
            } else{
                $model = new UserModel();
                $user = $model->where('user_id', $id)->first();
                if($user == null){
                    return $this->failNotFound('No user found');
                }
                return $this->respond($user);
            }
        } else {
            return $this->failUnauthorized('You are not authorized');
        }
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
        if(session()->get('role') == 'admin'){
            $model = new UserModel();
            $user = $model->where('user_id', $id)->first();
            if($user){
                return $this->respond($user);
            } else {
                return $this->failNotFound('No user found');
            }
        } else {
            return $this->failUnauthorized('You are not authorized');
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        // update user
        if(session()->get('role') == 'admin'){
            $model = new UserModel();
            $user = $model->where('user_id', $id)->first();
            if($user){
                $data = [
                    'username' => $this->request->getVar('username'),
                    'fullname' => $this->request->getVar('fullname'),
                    'email' => $this->request->getVar('email'),
                    'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                    'pass_confirm' => password_hash($this->request->getVar('pass_confirm'), PASSWORD_DEFAULT),
                ];

                $rules = [
                    'fullname' => 'required|min_length[3]|max_length[50]',
                    'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
                    'email' => 'required|valid_email',
                    'password' => 'required|min_length[6]|max_length[255]',
                    'pass_confirm' => 'matches[password]'
                ];

                if(!$this->validate($rules)){
                    return $this->fail($this->validator->getErrors());
                }

                $model->update($id, $data);
                return $this->respondUpdated($data);
            } else {
                return $this->failNotFound('No user found');
            }
        } else {
            return $this->failUnauthorized('You are not authorized');
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
        if(session()->get('role') == 'admin'){
            $model = new UserModel();
            $user = $model->where('user_id', $id)->first();
            if($user){
                $model->delete($id);
                return $this->respondDeleted($user);
            } else {
                return $this->failNotFound('No user found');
            }
        } else {
            return $this->failUnauthorized('You are not authorized');
        }
    }
}
