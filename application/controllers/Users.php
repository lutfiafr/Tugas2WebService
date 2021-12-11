<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Users extends REST_Controller{

    function __construct($config = 'rest'){
        parent::__construct($config);
    }

    //menampilkan data
    public function index_get() {

        $id = $this->get('id');
        $products =[];
        if ($id == '') {
            $data = $this->db->get('products')->result();
            foreach ($data as $row => $key) {
                $products[] = ["ProductID" =>$key->ProductID,
                               "ProductName" =>$key->ProductName,
                               "_links"=>[(object)["href"=>"suppliers/{$key->SupplierID}",
                                            "rel"=>"suppliers",
                                            "type"=>"GET"],
                                        (object)["href"=>"categories/{$key->CategoryID",
                                            "rel"=>"categories",
                                            "type"=>"GET"]],
                                "QuantityPerUnit"   =>$key->QuantityPerUnit,
                                "UnitPrice"         =>$key->UnitPrice,
                                "UnitsInStock"      =>$key->UnitsInStock,
                                "UnitsOnOrder"      =>$key->UnitsOnOrder,
                                "ReorderLevel"      =>$key->ReorderLevel,
                                "Discontinued"      =>$key->Discontinued
                                    ];
            endforeach;   
        }else{
            $this->db->where('ProductID', $id);
            $data = $this->db->get('products')->result();
        }
        $result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                    "code"=>200,
                    "message"=>"response successfully",
                    "data"=>$products];

        $this->response($result, 200);
    }
}
?>