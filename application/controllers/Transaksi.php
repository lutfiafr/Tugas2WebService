<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Transaksi extends REST_Controller {

    function __construct($config = 'rest') 
    {
        parent::__construct($config);
    }

    //menampilkan data
    public function index_get()
    {
        $id_transaksi = $this->get('id_transaksi');
        $transaksi=[];
        if ($id_transaksi == '')
        {
            $data = $this->db->get('transaksi')->result();
            foreach ($data as $row=>$key):
                $transaksi[]=[
                            "_links" =>[(object)["href" =>"pembayaran/{$key->id_transaksi}", 
                                                            "rel"   =>"pembayaran",
                                                            "type"  =>"GET"],
                                        (object)["href" =>"barang/{$key->id_barang}", 
                                                            "rel"   =>"barang",
                                                            "type"  =>"GET"],
                                        (object)["href" =>"pembeli/{$key->id_pembeli}", 
                                                            "rel"   =>"pembeli",
                                                            "type"  =>"GET"],
                            "tanggal"  =>$key->tanggal,
                            "keterangan"=>$key->keterangan]];
            endforeach;
        }else{
            $this->db->where('id_transaksi', $id_transaksi);
            $data = $this->db->get('transaksi')->result();
        }
        $result = ["took"       =>$_SERVER["REQUEST_TIME_FLOAT"],
                    "code"      =>200,
                    "message"   =>"response successfully",
                    "data"      => $transaksi];
        $this->response($result, 200);
    }
}
?>