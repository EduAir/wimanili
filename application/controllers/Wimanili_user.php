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


        $this->load->model('Model_user');

        $this->load->library('upload');
    }
    

    /////////////////////////////////////////////:Lexique///////////////////////////////////////////////////////////
    # En ce qui concerne les roles d'utilisateurs, la nomenclature va de 0 à 5:
    #0:si c'est un simple utilisateur
    #1 si c'est un modérateur
    #2 si c'est administrateur
    #-1 s'il est banni
    #-2 si non authenfitié

    #En ce qui concerne les statu de l'utilisateur, la nomenclature de 0 à 5
    #0 si il veut partager ses publications
    #1 s"il veut les rendre public

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////:

   

    //Inscriptions
    public function signin_post()
    {
        $user_name = $this->post['user_name'];
        $user_password = $this->post['user_password'];
        $user_gender = $this->post['user_gender'];
        $user_policy = $this->post['user_policy'];
        $user_email = $this->post['user_email'];
        $user_phoneBrand = $this->['user_phoneBrand'];

        $returned = $this->Model_user->signin($user_name,$user_password,$user_gender,$user_policy,$user_email,$user_phoneBrand);

        if($returned==true){

            $response = [
                'message' => 'ok';
            ];

        }else{
            $response = [
                'message' => 'fail';
            ];
        }

        $this->set_response($response, REST_Controller::HTTP_CREATED);
    }



    //Update picture profile
    public function UpdatePictureProfile_post()
    {
            
    }


    //Login
    public function login_post()
    {
        $user_name = $this->post['user_name_or_password'];
        $user_password = $this->post['user_password'];

        $returned = $this->Model_user->signin($user_name,$user_password,$user_gender,$user_policy,$user_email,$user_phoneBrand);

        if($returned){

            $response = [
                'response' => $response;
            ];
        }else{
            $response = [
                'response' => 'fail';
            ];
        }
    }

    //Delete picture profile


    /////////////////////////////////////////Update profil///On va creer une fonction pour chaque entré////////////////////////////////////////////////////////////////////
    public function updateusername_post()
    {
       
        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];
        $user_name       = $this->post['user_name'];


        if($user_token){

            $returned = $this->Model_user->UpdateUsername($user_token,$user_id,$user_name);

            if($returned==true){

                $response = [
                    'message' => 'ok';
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }
    }



    public function updateuserpassword_post()
    {
       
        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];
        $user_password   = $this->post['user_password'];


        if($user_token){

            $returned = $this->Model_user->UpdateUserPassword($user_token,$user_id,$user_password);

            if($returned==true){

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


    public function updateuserpolicy_post()
    {
       
        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];
        $user_policy     = $this->post['user_policy'];


        if($user_token){

            $returned = $this->Model_user->UpdateUserPolicy($user_token,$user_id,$user_policy);

            if($returned==true){

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

     public function updateuseremail_post()
    {
       
        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];
        $user_email      = $this->post['user_email'];


        if($user_token){

            $returned = $this->Model_user->UpdateUserEmail($user_token,$user_id,$user_email);

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


     public function updateuserphoneBrand_post()
    {
       
        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];
        $user_phoneBrand = $this->post['user_phoneBrand'];


        if($user_token){

            $returned = $this->Model_user->UpdateUserPhoneBrand($user_token,$user_id,$user_phoneBrand);

            if($returned==true){

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

    //Mettre un utilisateur administrateur
    public function Updateuserrole_post()
    {
       
        $user_token      = $this->post['user_token'];
        $user_id         = $this->post['user_id'];
        $user_role       = $this->post['user_role'];
        $user_my_role    = $this->post['user_my_role'];


        if($user_token && $user_my_role==2){ //Si je suis admin et que j'ai mon token

            $returned = $this->Model_user->UpdateUserRole($user_token,$user_id,$user_role);

            if($returned==true){

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



    /////////////////////////////////////////Update profil///On va creer une fonction pour chaque entré////////////////////////////////////////////////////////////////////




   
    //Bann user
    public function Bannuser_post() //Pour bannir les utilisateur
    {
        //Si je suis connecté en tant qu'admin
        $user_role      = $this->post['user_role'];

        if($user_role==2){//2 pour dire si je suis admin. Voir lexique plus haut de ce fichier

            $returned = $this->Model_user->BannUser($user_id);

            if($returned==true){

                $response = [
                    'message' => 'ok';
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }
    }


    //Pour suivre un utilisateur
    public function followuser_post()
    {
        $user_token      = $this->post['user_token'];
        $user_id  = $this->post['user_id'];
        $user_followed_id  = $this->post['user_followed_id'];


        if($user_token){

            $returned = $this->Model_user->FollowUser($user_token,$user_id,$user_followed_id);

            if($returned==true){

                $response = [
                    'message' => 'ok';
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }
    }


    //Obtenir la list des personnes suivant un utilisateur précis
    public function Followerlist_post()
    {
        $user_token         = $this->post['user_token'];
        $user_id            = $this->post['user_id'];

        if($user_token){

            $returned = $this->Model_user->FollowerList($user_token,$user_id);

            if($returned){

                $response = [
                    'followers' => $response;
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }
    }



    //On vérifie si l'utilisateur connecté suit un autre utilisateur
    public function Isfollower_post()
    {
        $user_token         = $this->post['user_token'];
        $user_id            = $this->post['user_id'];
        $follower_id        = $this->post['follower_id'];

        if($user_token){

            $returned = $this->Model_user->IsFollower($user_token,$user_id,$follower_id);

            if($returned){

                $response = [
                    'message' => true;
                ];

            }else{
                $response = [
                    'message' => false;
                ];
            }

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }
    }



    //Confirm new user
     public function confirmnewuser_get()
    {
        $user_token         = $this->post['user_token'];

        if($user_token){

            $returned = $this->Model_user->confirmnewuser($user_token);

            if($returned){

                $response = [
                    'message' => true;
                ];

            }else{
                $response = [
                    'message' => false;
                ];
            }

            $this->set_response($response, REST_Controller::HTTP_CREATED);
        }
    }






















}
