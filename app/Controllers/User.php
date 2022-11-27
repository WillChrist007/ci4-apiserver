<?php

namespace App\Controllers;

use App\Models\Modelmahasiswa;
use App\Models\ModelUser;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    public function index()
    {
        $modelUser = new ModelUser();
        $data = $modelUser->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];

        return $this->respond($response, 200);
    }

    public function show($cari = null)
    {
        $modelUser = new ModelUser();
        
        $data = $modelUser->orLike('id', $cari)->orLike('username', $cari)->get()->getResult();
 
        if(count($data) > 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
 
            return $this->respond($response, 200);
        }else if(count($data) == 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
 
            return $this->respond($response, 200);
        }else {
            return $this->failNotFound('maaf data ' . $cari . ' tidak ditemukan');
        }
    }
    
    public function create()
    {
        $modelUser = new ModelUser();
        $id = $this->request->getPost("id");
        $username = $this->request->getPost("username");
        $password = $this->request->getPost("password");
        $email = $this->request->getPost("email");
        $tanggalLahir = $this->request->getPost("tanggalLahir");
        $telepon = $this->request->getPost("telepon");

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'id' => [
                'rules' => 'is_unique[user.id]',
                'label' => 'Id User',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        if(!$valid){
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("id"),
            ];

            return $this->respond($response, 404);
        }else {
            $modelUser->insert([
                'id' => $id,
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'tanggalLahir' => $tanggalLahir,
                'telepon' => $telepon,
            ]);

            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];

            return $this->respond($response, 201);
        }
    }
    
    public function update($id = null)
    {
        $model = new ModelUser();

        $data = [
            'username' => $this->request->getVar("username"),
            'password' => $this->request->getVar("password"),
            'email' => $this->request->getVar("email"),
            'tanggalLahir' => $this->request->getVar("tanggalLahir"),
            'telepon' => $this->request->getVar("telepon"),
        ];
        $data = $this->request->getRawInput();
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan id $id berhasil dibaharukan"
        ];

        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $modelUser = new ModelUser();

        $cekData = $modelUser->find($id);
        if($cekData) {
            $modelUser->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];

            return $this->respondDeleted($response);
        }else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
