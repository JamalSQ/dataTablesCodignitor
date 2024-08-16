<?php

namespace App\Models;

use CodeIgniter\Model;
class productModel extends Model{
    protected $table="products";
    protected $primaryKey="id";
    protected $allowedFields=[
        'p_name',
        'p_price'
    ];
}