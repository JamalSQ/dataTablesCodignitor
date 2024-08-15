<?php

namespace App\Models;

use CodeIgniter\Model;
class ProductModel extends Model{

    public function getallProducts(){
        
        $query=$this->db->query("select * from products");
        return $query->getResult();
    }

    public function getsingleProducts($id){
        $query=$this->db->query("select * from products where id=$id");
        return $query->getResult();
    }

    public function insertProducts($data){
    
       $builder = $this->db->table('products');
       $result=$builder->insert($data);
       return $result;
    }

    public function updateProducts($data,$id){

        $result = $this->db->table('products')
            ->where('id', $id)
            ->update([
                'p_name' => $data['p_name'],
                'p_price' => $data['p_price']
            ]);
       return $result;
    }

    public function deleteProducts($id){

            $builder=$this->db->table("products");
            $builder->where('id', $id);
            $builder->delete();

            $affected_rows = $this->db->affectedRows();
            if($affected_rows){
                return true;
            }else{
                return false;
            }
     
    }

  

    public function userlogin($data){

        $email=$data['email'];
        $password=$data['password'];

        // $builder=$this->db->query("SELECT * FROM login WHERE email=$email && passward=$password")->getRow();

       
            $builder = $this->db->table('login');
            $builder->where('email', $email);
            $builder->where('passward', $password);
            $query = $builder->get();

            if($query->resultID->num_rows == 1){

                $row = $query->getRow();
                $name=$row->name;
                $id=$row->id;
                $result=['name'=>$name,'id'=>$id];
                return $result;

            }else{
                return $result=null;
            }
    }
}