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


        $this->load->model('poster');

    }
    

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////:

   
    public function AddPost_post()
    {
        $parcours_id = $this->post['parcours_id'];
        $user_id = $this->post['user_id'];
        $user_token = $this->post['user_token'];
        $text = $this->post['text'];
        $longitude = $this->post['longitude'];
        $latitude = $this->post['latitude'];
        $pays = $this->post['pays'];
        $ville = $this->post['ville'];
        $share_id = $this->post['share_id'];
        $status = $this->post['status'];

        if($user_token){

            $returned = $this->poster->Addpost($user_token,$user_id,$parcours_id,$text,$longitude,$latitude,$pays,$ville,$share_id,$status);

            if($returned){

                $response = [
                    'postData' => $returned;//On obtient mon dernier post_id et les photos ajoutées
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }



    public function signal_post()
    {
        $post_id = $this->post['post_id'];
        $user_id = $this->post['user_id'];
        $user_token = $this->post['user_token'];

        if($user_token){

            $returned = $this->poster->signal($user_token,$user_id,$post_id);

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



    public function DeletePost_post() //This will delete all post and all media joined to this post
    {
        $post_id = $this->post['post_id'];
        $user_id = $this->post['user_id'];
        $user_role = $this->post['user_role'];
        $user_token = $this->post['user_token'];

        if($user_token && $user_role==2){

            $returned = $this->poster->deletePost($user_token,$user_id,$post_id);

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



    public function GetPost_post() 
    {
        $post_id = $this->post['post_id'];
        $user_id = $this->post['user_id'];
        $user_token = $this->post['user_token'];

        if($user_token){

            $returned = $this->poster->GetPost($user_token,$user_id,$post_id);

            if($returned){

                $response = [
                    'postData' => $returned;
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }


    public function GetAllPostOfUser_post() 
    {
        $user_id = $this->post['user_id'];
        $user_token = $this->post['user_token'];

        if($user_token){

            $returned = $this->poster->GetAllPostOfUser($user_token,$user_id);

            if($returned){

                $response = [
                    'postData' => $returned;//Posts list. Last 10 posts
                ];

            }else{
                $response = [
                    'message' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }



    public function AddComment_post() 
    {
        $post_id = $this->post['post_id'];
        $user_id = $this->post['user_id'];
        $user_token = $this->post['user_token'];
        $text = $this->post['text'];

        if($user_token){

            $returned = $this->poster->AddComment($user_token,$user_id,$post_id,$text);

            if($returned){

                $response = [
                    'comment_id' => $returned;//CommentID
                ];

            }else{
                $response = [
                    'status' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }



    public function DeleteComment_post() 
    {
        $comment_id = $this->post['comment_id'];
        $my_user_id = $this->post['my_user_id'];//JE prend mon userID
        $user_id = $this->post['user_id'];//Je le compara avec l'User id de celui qui a commenté
        $poster_id = $this->post['poster_id']; //Id de celui qui a fait le post général
        $user_role = $this->post['user_role'];
        $text = $this->post['text'];

        if($my_user_id==$user_id || $user_role==2 || $my_user_id==$poster_id ){ //On supprime le commentaire quand:on est celui qui a fait le commentaire,quand on est celui qui a fait le post commenté,quand on est admin

            $returned = $this->poster->DeleteComment($comment_id);

            if($returned){

                $response = [
                    'message' => 'ok';
                ];

            }else{
                $response = [
                    'status' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }




    public function SignalComment_post() 
    {
        $comment_id = $this->post['comment_id'];
        $my_user_id = $this->post['my_user_id'];//JE prend mon userID
        $user_id = $this->post['user_id'];//Je le compara avec l'User id de celui qui a commenté
        $poster_id = $this->post['poster_id']; //Id de celui qui a fait le post général
        $user_token = $this->post['user_token'];
        $text = $this->post['text'];

        if($my_user_id!=$user_id){ //On supprime signal le post si celui qui est diffrent de celui qui est connecté

            $returned = $this->poster->SignalComment($comment_id);

            if($returned){

                $response = [
                    'message' => 'ok';
                ];

            }else{
                $response = [
                    'status' => 'fail';
                ];
            }

            $this->set_response($response,REST_Controller::HTTP_CREATED);
        }
    }






















}
