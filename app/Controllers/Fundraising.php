<?php

namespace App\Controllers;

use App\Models\ModelFundraising;
use CodeIgniter\RESTful\ResourceController;

class Fundraising extends ResourceController
{
    public function index()
    {
        $modelFnd = new ModelFundraising();
        $data = $modelFnd->findAll();
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
        $modelFnd = new ModelFundraising();
        
        $data = $modelFnd->orLike('id', $cari)->orLike('judul', $cari)->get()->getResult();
 
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
        $modelFnd = new ModelFundraising();
        $id = $this->request->getPost("id");
        $judul = $this->request->getPost("judul");
        $dana = $this->request->getPost("dana");
        $lokasi = $this->request->getPost("lokasi");
        $durasi = $this->request->getPost("durasi");

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'id' => [
                'rules' => 'is_unique[fundraising.id]',
                'label' => 'Id Fundraising',
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
            $modelFnd->insert([
                'id' => $id,
                'judul' => $judul,
                'dana' => $dana,
                'lokasi' => $lokasi,
                'durasi' => $durasi,
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
        $model = new ModelFundraising();

        $data = [
            'judul' => $this->request->getVar("judul"),
            'dana' => $this->request->getVar("dana"),
            'lokasi' => $this->request->getVar("lokasi"),
            'durasi' => $this->request->getVar("durasi"),
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
        $modelFnd = new ModelFundraising();

        $cekData = $modelFnd->find($id);
        if($cekData) {
            $modelFnd->delete($id);
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
