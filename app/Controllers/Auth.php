<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (!$this->validate([
            'username'     => 'required|min_length[3]|max_length[30]',
            'password'     => 'required|min_length[6]',
        ])) {
            return $this->response->setJSON([
                'success' => false, 
                'data' => null, 
                "message" => \Config\Services::validation()->getErrors()
            ]);
        }

        $db = new UserModel();
        $user  = $db->where('username', $this->request->getVar('username'))->first();
        if ($user) {
            if (password_verify($this->request->getVar('password'), $user['password'])) {
                $jwt = new JWTCI4();
                $token = $jwt->token($user);

                $ses_data = [
                    'user_id'       => $user['user_id'],
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'role'     => $user['role'],
                    'token'    => $token,
                ];

                $this->session->set($ses_data);
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $ses_data,
                    "message" => "Login success",
                ])->setStatusCode(200);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'data' => null,
                "message" => "Username or password is incorrect",
            ])->setStatusCode(401);
        }
    }

    public function logout(){
        session()->destroy();
        return redirect()->to('/');
    }
}
