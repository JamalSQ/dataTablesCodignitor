<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\ProductModel;
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

        // if($_SERVER['REQUEST_METHOD'] !== 'GET') {
        //      $this->responsed("200","request method is not valid");
        // }

        $this->model = new ProductModel();
    }

    public function index(): string
    {
        return view('welcome_message');
    }

    public function responsed($status, $msg)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'Success'=>[
            'status' => $status, 'msg' => $msg
            ]
        ]);
        die();
    }
    public function ThrowError($status, $msg)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'Error'=>[
            'status' => $status, 'msg' => $msg
            ]
        ]);
        // return $this->response->setJSON([
        //     'Error' => [
        //         'status' => $status,
        //         'access_token' => $msg
        //     ]
        // ]);
        die();
    }

    public function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function generateToken($id, $name)
    {
        // $key="hello";

        $payload = [
            'iss' => 'admin',
            'iat' => time(),
            'exp' => time() + (24*60*60),
            'data' => [
                'name' => $name,
                'id' => $id
            ]
        ];

        $token = JWT::encode($payload, SECRET_KEY, 'HS256');
        return $token;
    }

    function authenticateUser()
    {
        try {
            $token=$this->getBearerToken();
            // Decode the JWT
            $decoded = JWT::decode($token, new Key(SECRET_KEY, 'HS256'));

            // Convert the decoded data to a PHP object
            $decodedArray = (array) $decoded;
        } catch (ExpiredException $e) {

            $this->ThrowError(500, "token has been expired");
        } catch (SignatureInvalidException $e) {

            $this->ThrowError(500, "Invalid signature");
        } catch (BeforeValidException $e) {

            $this->ThrowError(500, "token not yet valid");
        } catch (Exception $e) {
            $this->ThrowError(500, "Failed to decode token: " . $e->getMessage());
        }
    }
    function validateRequest(){
        if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            $this->responsed("201", 'Request content type is not valid');
        }
    }

    /**
     * get access token from header
     * */
    public function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        $msg = "Access Token Not found";
        $this->ThrowError(500, $msg);

    }

    public function getAllProducts()
    { 
        $this->authenticateUser();
        $data = $this->model->getallProducts();
        $this->responsed(200, $data);
    }
    
    public function getsingleproduct()
    {
        $this->validateRequest();
        $this->authenticateUser();
        $jsondata = $this->request->getJSON();
        $id = $jsondata->id;
        $data = $this->model->getsingleProducts($id);

        $this->responsed(200, $data);
    }

    public function updateProducts()
    {
        $this->validateRequest();
        $this->authenticateUser();
        $jsondata = $this->request->getJSON();
        $id = $jsondata->id;
        $data = [
            'p_name' => $jsondata->p_name,
            'p_price' => $jsondata->p_price
        ];

        $responce = $this->model->updateProducts($data, $id);
        if ($responce){
            $this->responsed(200, "data updated successfully");
        }
        $this->ThrowError(500, "un able to update data");
    }

    public function deletesingleProduct()
    {
        $this->validateRequest();
        $this->authenticateUser();
        $jsondata = $this->request->getJSON();
        $id = $jsondata->id;
        $data = $this->model->deleteProducts($id);
        if ($data) {
            $msg = "product deleted successfully";
            $this->responsed(200, $msg);
        } else {
            $msg = "No product found";
            $this->ThrowError(500, $msg);
        }

    }

    public function insertProducts()
    {
        $this->validateRequest();
        $this->authenticateUser();
        $jsondata = $this->request->getJSON();
        $data = [
            'p_name' => $jsondata->p_name,
            'p_price' => $jsondata->p_price
        ];

        $responce = $this->model->insertProducts($data);
        if ($responce) {
            $msg = "data inserted successfully";
            $this->responsed(200, $msg);
        } else {
            $msg = "Unable to insert data";
            $this->ThrowError(500, $msg);
        }
    }
 
    public function login()
    {
        $jsondata = $this->request->getJSON();
        $data = [
            'email' => $jsondata->email,
            'password' => $jsondata->password
        ];

        $responce = $this->model->userlogin($data);

        if (is_array($responce)) {
            $id = $responce['id'];
            $name = $responce['name'];
            $token = $this->generateToken($id, $name);
        return $this->response->setJSON([
            'Success' => [
                'status' => 200,
                'access_token' => $token
            ]
        ]);
        } else {
            $this->ThrowError(500, "wrong email or password | User not found");              
        }
    }
}
