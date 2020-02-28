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
class Wimanili_mailer extends REST_Controller {

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

        $this->load->library('email');
    }
    

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////:

   



    public function sendmail_post()
    {
        $message = EMAIL_MESSSAGE_CONFIRMATION;
        $subject = EMAIL_SUBJECT_CONFIRMATION;
        $receiver = $this->post['receiver'];   


        $response = sender($message,$subject,$receiver);

        $message = [
                'message' => $response
            ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }





    //Inscriptions
    public function sender($message,$subject,$receiver)
    {
        // Get full html:
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=' . strtolower(config_item('charset')) . '" />
            <title>' . html_escape($subject) . '</title>
            <style type="text/css">
                body {
                    font-family: Arial, Verdana, Helvetica, sans-serif;
                    font-size: 16px;
                }
            </style>
        </head>
        <body>
        ' . $message . '
        </body>
        </html>';
        // Also, for getting full html you may use the following internal method:
        //$body = $this->email->full_html($subject, $message);

        // // Attaching the logo first.
        // $file_logo = FCPATH.'apple-touch-icon-precomposed.png';  // Change the path accordingly.
        // // The last additional parameter is set to true in order
        // // the image (logo) to appear inline, within the message text:
        // $this->email->attach($file_logo, 'inline', null, '', true);
        // $cid_logo = $this->email->get_attachment_cid($file_logo);
        // $body = str_replace('cid:logo_src', 'cid:'.$cid_logo, $body);
        // // End attaching the logo.

        $result = $this->email
            ->from(MAIL_NO_REPLY)//MAIL_NO_REPLY est une constate globale qui stoke l'adresse mail noreply du projet. Voir /application/config/constant.php
            ->to($receiver)
            ->subject($subject)
            ->message($body)
            ->send();


        return $this->email->print_debugger();

    }






















}
