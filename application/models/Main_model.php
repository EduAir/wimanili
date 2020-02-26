<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model {



	function addProduct($ProductName,$ProductPrice,$ProductPromoPrice,$ProductCategory,$ProductPicture)
	{
		$data = array(
			'ProductName' => $ProductName,
			'ProductPrice' => $ProductPrice,
			'ProductPromoPrice'=>$ProductPromoPrice,
			'ProductCategory'=>$ProductCategory,
			'ProductPicture'=>$ProductPicture,
			'disponible'=>'oui',
			'date_mise_en_ligne'=>time(), 
		);


		$this->db->insert('produits', $data);

		return $this->db->insert_id();
	}



	function SetDisponibility($disponibility,$productId)
	{
		$array = array(
		        'disponible' => $disponibility
			);
		$this->db->set($array);
		$this->db->where('ProductId',$ProductId);
		$this->db->update('produits');

		return array('statu' => true );
	}



	function UpdateProduct($ProductName,$ProductPrice,$ProductPromoPrice,$ProductCategory,$ProductId)
	{
		$data = array(
			'ProductName' => $ProductName,
			'ProductPrice' => $ProductPrice,
			'ProductPromoPrice'=>$ProductPromoPrice,
			'ProductCategory'=>$ProductCategory 
		);
		$this->db->set($array);
		$this->db->where('ProductId',$ProductId);
		$this->db->update('produits');

		return array('statu' => true );
	}


	function UpdatePictureProduct($ProductPicture,$ProductID)
	{
		$array = array(
		        'ProductPicture' => $ProductPicture
			);
		$this->db->set($array);
		$this->db->where('ProductId',$ProductId);
		$this->db->update('produits');

		return array('statu' => true );
	}



	function get_it($type,$clause)
	{

		switch ($type) {
			case 'lister':
				$this->db->select('*')->from('produits')->where('ProductCategory',$clause);
			break;
		}

		$this->db->order_by('ProductId', "desc");
		$query = $this->db->get();

		return $query->result_array();
	}






	function add_newUser_And_Update_Some_Date($id_messenger,$fisrt_name,$last_name,$profil_pic,$gender)
	{

		$query = $this->db->query("SELECT id FROM users where id_messenger = '".$id_messenger."'");
		$thisResult = $query->num_rows();

		if($thisResult>0){

			$this->updateActivity($id_messenger);
		}else{
 
			$data = array(
							'id_messenger' => $id_messenger,
							'first_name' => $fisrt_name,
							'last_name' => $last_name,
							'profil_pic'=> $profil_pic,
							'gender'=> $gender,
							'first_visit'=> time(),
							'last_visit'=> time()
					);

			$this->db->insert('users', $data);

		}
	}


	function keep_last_town($id_messenger,$last_town)
	{
		$array = array(
		        'last_town' => $last_town
			);
		$this->db->set($array);
		$this->db->where('id_messenger',$id_messenger);
		$this->db->update('users');

		$this->updateActivity($id_messenger);

		return $this->sendOptionSearchEnterprise($id_messenger);
	}



	function updateActivity($id_messenger)
	{
		
		$this->db->query("UPDATE users SET nombre_requete = nombre_requete+1, last_visit = '".time()."' WHERE id_messenger = '".$id_messenger."'");
	}



	function sayHello($id_messenger)
	{
  	
		$messageData = [
		  "messages"=> [
		    [
		      "text"=>  "Salut {{first name}}  Je m'appelle Patriote. Je suis le robot assistant de My Cameroun. La plate-forme de communication des entreprises et professionnels au Cameroun😉. Choisissez dans quelle ville vous voulez faire la recherche " ,
		      "quick_replies"=> [
		            [
		              "title"=>"Douala",
		              "block_names"=> [
		                "Douala"
		              ]
		            ],
		            [
		              "title"=>"Yaounde",
		              "block_names"=> [
		                "Yaounde"
		              ]
		            ]
		      ]
		    ]
		  ]
		];

  		return $messageData;
  	}


  	function getLastTownAndLastSearchType($id_messenger)
	{

		
		$this->db->select('last_town,last_search_type');
    	$this->db->from('users');
   		$this->db->where('id_messenger', $id_messenger);

		$query = $this->db->get();

		$row = $query->row();

		if (isset($row))
		{
		    return $row;
		}
  	}






	function sendTextMessage($message) {
	  
	  $message_result =  [
	    "messages"=> [
	       ["text"=> $message]
	    ]
	  ];

	  return $message_result;
	}




	function sendOptionSearchEnterprise($recipientId) {

	  $messageData = 
	  [
	    "messages"=> [
	       [
	        "text"=>  "OK. Vous recherchez suivant quel critère",
	        "quick_replies"=> [
	              [
	                "title"=>"NOM",
	                "block_names"=> [
	                  "NOM"
	                ]
	              ],
	               [
	                "title"=>"Quartier",
	                "block_names"=> [
	                  "Quartier"
	                ]
	              ],
	               [
	                "title"=>"Secteur d'activité",
	                "block_names"=> [
	                  "Secteur d'activité"
	                ]
	              ],
	              [
	                "title"=>"Recommencez",
	                "block_names"=> [
	                  "Welcome Message"
	                ]
	              ]
	        ]
	       ]
	    ]
	  ]; 

	  return $messageData;
	}	



	function SearchBy($id_messenger,$type)
	{

		//We record the last search type
		$array = array(
		        'last_search_type' => $type
			);
		$this->db->set($array);
		$this->db->where('id_messenger',$id_messenger);
		$this->db->update('users');


		$this->updateActivity($id_messenger);

		switch ($type) {
			case 'name':
				return $this->sendTextMessage("😉 Ecrivez le nom de l'entreprise ou Ecrivez \"Annuler\" pour recommencer");
				break;

			case 'quater':
				return $this->sendTextMessage("😉 Entrez la zone (quartier) des entreprises que vous recherchez ou Entrez \"Annuler\" pour recommencer");
				break;

			case 'sector':
				return $this->sendTextMessage("La recherche par secteur d'activité sera disponible très prochainement. Entrez \"ok\" pour recommencer");
				break;
			
			default:
				return $this->sendTextMessage("Un problèmes est survenu. Nous réparons ce soucis. Entrez \"ok\" pour recommencer");
				break;
		}
	}




}