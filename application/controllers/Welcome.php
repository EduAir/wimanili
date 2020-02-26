<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Main_model');

        $this->load->library('upload');
    }


	public function index()
	{
		$this->load->view('lynn');
	}



	public function do_upload(){

		$NewFileName = $this->generateRandomString();

		$file_ext = pathinfo($_FILES["ImageProduct"]["name"], PATHINFO_EXTENSION);//We get the extention of the file

		$config = array(
			'upload_path' => "./assets/photo/",
			'allowed_types' => "gif|jpg|png|jpeg",
			'overwrite' => TRUE,
			'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
			'file_name'  => $NewFileName
		);

		if (isset($_FILES['ImageProduct']['name'])) {

            if (0 < $_FILES['ImageProduct']['error']) {

            	$response = array(
							'statu' => false,
							'message' =>$_FILES['ImageProduct']['error']
						);
            }else {

                if (file_exists(base_url().'/assets/photo/' . $_FILES['ImageProduct']['name'])) {

                	$response = array(
							'statu' => false,
							'message' =>'Le fichier existe déjà:assets/photo/' . $_FILES['ImageProduct']['name']
						);

                } else {

                    $this->load->library('upload');
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('ImageProduct')) {

                    	$response = array(
							'statu' => false,
							'message' =>$this->upload->display_errors()
						);

                    } else {
                        
			            $ProductName = $this->input->post('ProductName');
				        $ProductPrice = $this->input->post('ProductPrice');
				        $ProductPromoPrice = $this->input->post('ProductPromoPrice');
				        $ProductCategory =  $this->input->post('ProductCategory');
				        $ProductPicture = $NewFileName.'.'.$file_ext; //We rename the nex file

			            $response = array(
			            				'statu' => true,
			            				'ProductId' => $this->Main_model->addProduct($ProductName,$ProductPrice,$ProductPromoPrice,$ProductCategory,$ProductPicture)
						);
                    }
                }
            }
        } else {
           $response = array(
							'statu' => false,
							'message' =>'Choisissez un autre fichier'
						);
        }


		header('Content-Type: application/json');//We prepare the json header

		echo json_encode( $response );
	}



	public function get_it()
	{
		  
        header('Content-Type: application/json');//We prepare the json header
		echo json_encode($this->Main_model->get_it($this->input->post('type'),$this->input->post('clause')));
	}




	public function disponibility()
	{
		header('Content-Type: application/json');//We prepare the json header
		echo json_encode( $this->Main_model->SetDisponibility($this->input->post('Disponibility'),$this->input->post('ProductId')));
	}


	public function EditProduct()
	{
		$ProductName = $this->post['ProductName'];
        $ProductPrice = $this->post['ProductPrice'];
        $ProductPromoPrice = $this->post['ProductPrice'];
        $ProductCategory = $this->post['ProductCategory'];
        $ProductId = $this->post['ProductId'];

        echo json_encode( $this->Main_model->UpdateProduct($ProductName,$ProductPrice,$ProductPromoPrice,$ProductCategory,$ProductId));
	}


	public function UpdateProductPicture(){

		$NewFileName = $this->generateRandomString();

		$file_ext = pathinfo($_FILES["ImageProduct"]["name"], PATHINFO_EXTENSION);//We get the extention of the file

		$config = array(
			'upload_path' => "./photo/",
			'allowed_types' => "gif|jpg|png|jpeg",
			'overwrite' => TRUE,
			'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
			'file_name'  => $NewFileName
		);

		$this->load->library('upload', $config);

		header('Content-Type: application/json');//We prepare the json header
		
		if($this->upload->do_upload())
		{
	        $ProductId = $this->post['ProductID'];
	        $ProductPicture = $NewFileName.'.'.$file_ext; //We rename the nex file

	        $this->Main_model->UpdatePictureProduct($ProductPicture,$ProductID);

            $response = array(
            				'statu' => true
			); 
		}
		else
		{
			$response = array(
							'statu' => false,
							'message' =>$this->upload->display_errors()
						);
		}

		echo json_encode( $response );
	}




	function generateRandomString($length = 20) {

	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}
