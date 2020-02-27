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


        $this->load->model('Main_model');

        $this->load->library('upload');
    }
    

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////:

   



    public function indexer_post()
    {
        $message = [
            'message' => 'Vous vous êtes bien connecté'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }


    //Inscriptions
    public function signin()
    {
       $first_name = $this->post['first_name'];
        $last_name = $this->post['last_name'];
        $gender = $this->post['gender'];
        $profile_pic = $this->post['profile_pic_url'];
        $id_messenger = $this->post['messenger_user_id'];
    }

    //Login

    //Update profil

    //Delete user

    //Bann user





















}
