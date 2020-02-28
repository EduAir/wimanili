<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model {



	function signup($user_name,$user_password,$user_gender,$user_policy,$user_email,$user_phoneBrand)
	{
		$data = array(
			'user_name'=> $user_name,
			'user_password' =>  hash('sha512', $user_password),
			'user_gender' => $user_gender,
			'user_policy' => $user_policy,
			'user_email' => $user_email,
			'user_phoneBrand'=> $user_phoneBrand,
			'user_role' => 0,//He is not confirmed
			'user_status' => 0,//He is simple user
			'user_createdAt' => time(),
			'user_UpdatedAt' => time(),
			'user_token'	=> hash('sha512', $user_gender);
		);

		$this->db->insert('wimanili_user', $data);

		return $this->db->insert_id();
	}



	function login($user_name_or_email,$user_password)
	{
		$this->db->select('user_gender,user_name,user_policy,user_createdAt,user_token,user_phoneBrand,user_picture,user_role,user_id');
	    $this->db->from('wimanili_user');
	    $this->db->where('user_password', hash('sha512',$user_password));

		if(strpos($user_name_or_email, '@')===false){ //si c'est une authentification par user_name

			$user_name = $user_name_or_email;
			
	   		$this->db->where('user_name', $user_name);
		}else{ //Si c'est une authetification par mail

			$user_email = $user_name_or_email;
			$this->db->where('user_email', $user_email);
		}

		$query = $this->db->get();

		$row = $query->row();

		if (isset($row))
		{
		    return $row;
		}else{
			return false;
		}
	}


	function FollowUser($user_id,$user_followed_id)
	{
		$data = array(
			'user_id'=> $user_id,
			'user_followed_id' =>  $user_followed_id,
			'createdAt' => time()
		);

		$this->db->insert('wimanili_follower', $data);

		return $this->db->insert_id();
	}


	function FollowerList($user_id)
	{
		//We take all followers_id of this user
		$this->db->select('user_id');
	    $this->db->from('wimanili_follower');
	    $this->db->where('user_followed_id',$user_id);

		$query = $this->db->get();

		//and now we take all the list name 
		$the_list = [];

		foreach ($query->result_array() as $row)
		{
	        $this->db->select('user_gender,user_createdAt,user_name,user_picture,user_role,user_id');
		    $this->db->from('wimanili_user');
		    $this->db->where('user_id',$row['user_id']);
		    $this_row = $query->row();

		    array_push($the_list, $this_row)
		}

		return $the_list;
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