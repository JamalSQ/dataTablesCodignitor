<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\productModel;
use CodeIgniter\HTTP\ResponseTrait;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Exception;

class Home extends BaseController
{
    use ResponseTrait;
    public $model;
    public function __construct()
    {

        $this->model = new productModel();
    }

    public function index(){
   
        $data['products']=$this->model->findAll();
        return view('products', $data);
}
    
    

    public function viewallproducts(){
        $data['products']=$this->model->findAll();
        return $data;
    }

    public function updatefile(){
        $filepath = FCPATH . 'js/products.json';
            // Fetch data from the database
            $data['products'] = $this->model->findAll();

            // Write data to the file in JSON format
            if ($file = fopen($filepath, 'w')) {
                fwrite($file, json_encode($data['products'], JSON_PRETTY_PRINT));
                fclose($file);
                echo json_encode(["status"=>"success","message"=>"From database"]); 
            } else {
                // Handle error if the file cannot be opened
                echo json_encode(["status"=>"success","message"=>"Error while opening file"]); 
            }
    }
    public function getAllProducts()
    { 
        $data['products'] = $this->model->findAll();      
        echo json_encode(['data'=>$data['products']]); 
      
    }

    public function insertData()
    {
        $pname=$this->request->getPost('pname');
        $pprice=$this->request->getPost('pprice');
       $data=[
        'p_name'=>$pname,
        'p_price'=>$pprice
       ];
       $this->model->save($data);
       echo json_encode(["status"=>"success","message"=>"Data added successfully"]);        
    }

    public function deletesingleProduct(){

        $id=$this->request->getPost('id');
        $this->model->delete(['id' => $id]);
        echo json_encode(["status"=>"success","message"=>"Deleted successfully"]); 

    }

    public function getsingleproduct(){
        $id=$this->request->getPost('id');
        $data['products']=$this->model->where('id',$id)->first();
        echo json_encode(['data'=>$data['products']]); 
    }

    public function updateProducts(){
        $id=$this->request->getPost('id');
        $pname=$this->request->getPost('pname');
        $pprice=$this->request->getPost('pprice');
       $data=[
        'p_name'=>$pname,
        'p_price'=>$pprice
       ];

    //    $this->model->update($id, $data);
       $this->model->where('id', $id)->set($data)->update();
    //    echo json_encode(["status"=>"success","message"=>$data]);  
       echo json_encode(["status"=>"success","message"=>"Data updated successfully"]);  
    }
}
