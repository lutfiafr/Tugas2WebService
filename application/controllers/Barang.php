<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require "vendor\autoload.php";

use Restserver\Libraries\REST_Controller;
use \Firebase\JWT\JWT;

class Barang extends REST_Controller {

    function __construct($config = 'rest') 
    {
        parent::__construct($config);
    }
    //Menampilkan data
	public function index_get(){
		$authHeader = $this->input->get_request_header('Authorization');
		$arr = explode(" ", $authHeader);
		$jwt = isset($arr[1])? $arr[1]: "";
		$secretkey = base64_encode("gampang");

		if($jwt){
			try {
				$decode = JWT::decode($jwt, $secretkey, array('HS256'));
				$id_barang = $this->get('id_barang');
				if ($id_barang == '') {
					$data = $this->db->get('barang')->result();
				}else{
					$this->db->where("id_barang", $id_barang);
					$data = $this->db->get('barang')->result();
				}
				$result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
						"code"=>200,
						"message"=>"Response successfully",
						"data"=>$data];	
				$this->response($result, 200);
			}catch (Exception $e){
					$result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
						"code"=>401,
						"message"=>"Access denied",
						"data"=>null];	
					$this->response($result, 401);
			}
		}else{
			$result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
				"code"=>402,
				"message"=>"Access denied",
				"data"=>null];	
			$this->response($result, 402);
		}
	}

    // untuk menambahkan data baru
    public function index_post() 
    {
        $data = array(
                'nama_barang'       => $this->post('nama_barang'),
                'harga'             => $this->post('harga'),
                'stok'              => $this->post('stok'),
                'id_supplier'       => $this->post('id_supplier'));
        $insert = $this->db->insert('barang', $data);
        if ($insert) 
        {
            $result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                "code"=>201,
                "message"=>"Data has successfully added",
                "data"=>$data];
            $this->response($result, 201);
        }else 
        {
            $result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                "code"=>502,
                "message"=>"Failed adding data",
                "data"=>null];
        $this->response($result, 502);
        }
    }

    //untuk mengubah data
    public function index_put() {
        $id_barang = $this->put('id_barang');
        $data = array(
                'id_barang'       => $this->put('id_barang'),
                'nama_barang'       => $this->put('nama_barang'),
                'harga'             => $this->put('harga'),
                'stok'              => $this->put('stok'),
                'id_supplier'       => $this->put('id_supplier'));
        $this->db->where('id_barang', $id_barang);
        $update = $this->db->update('barang', $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    
//untuk Menghapus Data
    public function index_delete() 
    {
        $id_barang = $this->delete('id_barang');
        $this->db->where('id_barang', $id_barang);
        $delete = $this->db->delete('barang');
        if ($delete) 
        {
            $this->response(array('status' => 'success'), 201);
        }else
        {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
?> 
