<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class User extends BaseController
{
    use ResponseTrait;
    protected $model;
    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index()
    {
        $validation = \Config\Services::validation();
        $aturan = [
            'username' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Must enter username',
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Must enter password'
                ]
            ],
        ];

        $validation->setRules($aturan);
        if (!$validation->withRequest($this->request)->run()) {
            return $this->fail($validation->getErrors());
        }
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $this->model->getUsername($username);
        if ($data['password'] != md5($password)) {
            return $this->fail("Password not matched");
        }

        helper('jwt');
        $response = [
            'message' => 'Otentikasi succeed',
            'data' => $data,
            'access_token' => createJWT($username)
        ];

        return $this->respond($response);
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $aturan = [
            'username' => [
                'rules' => 'required|min_length[2]',
                'errors' => [
                    'required' => 'Must enter username',
                    'min_length' => 'Must enter minimal 2 characters'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'Must enter password',
                    'min_length' => 'Must enter minimal 5 characters'
                ]
            ],
            'fullname' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Must enter full name'
                ]
            ]
        ];

        $validation->setRules($aturan);
        if (!$validation->withRequest($this->request)->run()) {
            return $this->fail($validation->getErrors());
        }
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $passEncrypt = md5($password);
        $fullname = $this->request->getVar('fullname');

        $data = [
            'username' => $username,
            'password' => $passEncrypt,
            'fullname' => $fullname
        ];

        $create = $this->model->save($data);
        if (!$create) {
            return $this->fail("Data failed to create");
        }
        helper('jwt');
        $response = [
            'message' => 'Data and Authentication Token created',
            'data' => $data,
            'access_token' => createJWT($username)
        ];

        return $this->respond($response);
    }

    public function showAll()
    {
        $countData = $_GET['data'];
        $countPage = $_GET['page'];

        $begin = ($countPage * $countData) - $countData;
        //pagination, show 5 data per page
        $data = $this->model->limit($countData, $begin)->find();
        return $this->respond($data, 200);
    }
}
