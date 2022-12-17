<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class Login extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    
    public function index()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[20]',
            'password' => 'required|min_length[6]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        } else {
            $model = new \App\Models\UserModel();
            $user = $model->where('username', $this->request->getVar('username'))->first();
            if ($user) {
                $pass = $this->request->getVar('password');
                $verify_pass = password_verify($pass, $user['password']);
                if ($verify_pass) {
                    $ses_data = [
                        'user_id' => $user['user_id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'logged_in' => TRUE
                    ];

                    $key = getenv('TOKEN_SECRET');
                    $payload = [
                        'iat' => time(),
                        'exp' => time() + 60 * 60 * 24 * 7,
                        'data' => $ses_data
                    ];
                    $token = JWT::encode($payload, $key, 'HS256');

                    session()->set($ses_data);
                    return $this->respond($token);
                } else {
                    $response = [
                        'status'   => 401,
                        'error'    => null,
                        'messages' => [
                            'error' => 'Wrong password'
                        ]
                    ];
                    return $this->respond($response);
                }
            } else {
                $response = [
                    'status'   => 401,
                    'error'    => null,
                    'messages' => [
                        'error' => 'User not found'
                    ]
                ];
                return $this->respond($response);
            }
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
