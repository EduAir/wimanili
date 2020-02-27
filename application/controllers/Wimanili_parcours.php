<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';



/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Wimanili_user extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->post = $_REQUEST;


        $this->load->model('parcours');

    }
    

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////:

   


    public function StartParcours_post()
    {

        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];

        if($user_token){

            $returned = $this->parcours->StartParcours($user_token,$user_id);

            if($returned){

                $response = [
                    'parcours_id' => $returned; //It return parcours_id
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }


    public function EndParcours_post()
    {

        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];
        $parcours_id     = $this->post['parcours_id'];

        if($user_token){

            $returned = $this->parcours->EndParcours($user_token,$user_id,$parcours_id);

            if($returned){

                $response = [
                    'message' => 'ok';
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }



    public function GetAllParcours_post() //On obtient la liste des parcours d'un utilisateurs
    {

        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];

        if($user_token){

            $returned = $this->parcours->GetAllParcours($user_token,$user_id);

            if($returned){

                $response = [
                    'parcours' => $returned;
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }




    public function GetMyLastParcours_post() //On obtient le dernier parcours de l'utilisateur
    {

        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];

        if($user_token){

            $returned = $this->parcours->GetMyLastParcours($user_token,$user_id);

            if($returned){

                $response = [
                    'parcours' => $returned;//On obtient mon dernier parcours_id
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }





















}
