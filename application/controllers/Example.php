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
class Example extends REST_Controller {

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

   


















    public function welcome_post()
    {
        $first_name = $this->post['first_name'];
        $last_name = $this->post['last_name'];
        $gender = $this->post['gender'];
        $profile_pic = $this->post['profile_pic_url'];
        $id_messenger = $this->post['messenger_user_id'];


        // $first_name = 'bb,b,';
        // $last_name = 'ghjgjgj';
        // $gender = 'man';
        // $profile_pic = 'hjgjhgjgjg';
        // $id_messenger = 1368638683;

        $this->Main_model->add_newUser_And_Update_Some_Date($id_messenger,$first_name,$last_name,$profile_pic,$gender);
        
        //We respond to hello message
        $this->set_response($this->Main_model->sayHello($id_messenger),REST_Controller::HTTP_OK); 
    }


    public function douala_post()
    {
        $this->set_response($this->Main_model->keep_last_town($this->post['messenger_user_id'],'douala'), REST_Controller::HTTP_OK); 
    }

    public function yaounde_post()
    {
        $this->set_response($this->Main_model->keep_last_town($this->post['messenger_user_id'],'yaounde'), REST_Controller::HTTP_OK); 
    }



    public function texter_post()
    {

        //On enregistre l'utilisateur au cas où//////////////////////////////////////////////////////////
        $first_name = $this->post['first_name'];
        $last_name = $this->post['last_name'];
        $gender = $this->post['gender'];
        $profile_pic = $this->post['profile_pic_url'];
        $id_messenger = $this->post['messenger_user_id'];

        $this->Main_model->add_newUser_And_Update_Some_Date($id_messenger,$first_name,$last_name,$profile_pic,$gender);
        //On enregistre l'utilisateur//////////////////////////////////////////////////////////



        //On traite son texte//////////////////////////////////////////////////////////

        $text = $this->post["last_user_freeform_input"];

        $id_messenger = $this->post["messenger_user_id"];

        $list_politesse = ['hi','salut','bonjour','bonsoir','hello','coucou','bjr','slt','bsr','cc','cucu','annuler','recommencer','recommencez','ok'];

        $text_min = strtolower($text);

        if (in_array($text_min, $list_politesse)) {//We verify if there is salutation
           
            $this->set_response($this->Main_model->sayHello($id_messenger), REST_Controller::HTTP_OK);//We return hello message
        }else{

            //We get the last town and the last type of search of the user
            $lastData = $this->Main_model->getLastTownAndLastSearchType($id_messenger);

            //We prepare the url for research
            switch($lastData->last_search_type){

                case 'name':
                    $my_snif_url = 'http://www.'.$lastData->last_town.'zoom.com/web/fr/activite/list/saisie/'.$text_min.'?page=1';
                break;

                case 'sector':
                break;

                case 'quater':
                    $my_snif_url = 'http://www.'.$lastData->last_town.'zoom.com/web/fr/recherche/quartier/'.$text_min.'?page=1';
                break;
            }

            $this->Main_model->updateActivity($id_messenger);//I first update activity

            $this->set_response($this->GetPageByUrl($my_snif_url,0,$id_messenger,$lastData), REST_Controller::HTTP_OK); 
        }
    }



    public function searchbyname_post()
    {
        $this->set_response($this->Main_model->SearchBy($this->post['messenger_user_id'],'name'), REST_Controller::HTTP_OK); 
    }

    public function searchbyquater_post()
    {
        $this->set_response($this->Main_model->SearchBy($this->post['messenger_user_id'],'quater'), REST_Controller::HTTP_OK); 
    }

    public function searchbysector_post()
    {
        $this->set_response($this->Main_model->SearchBy($this->post['messenger_user_id'],'sector'), REST_Controller::HTTP_OK); 
    }



    public function feedback_post()
    {
        
        $feedback = $this->post["feedback"];

        $id_messenger = $this->post["messenger_user_id"];

        $this->Main_model->updateActivity($id_messenger);//I first upadte this activity

        // $this->set_response($this->Main_model->sendTextMessage("Désolé il n'ont pas de site web 😅 ".$feedback), REST_Controller::HTTP_OK);

  
        switch($feedback){

            case 'No_WebSite':
                $this->set_response($this->Main_model->sendTextMessage("Désolé il n'ont pas de site web 😅"), REST_Controller::HTTP_OK); 
            break;

            default:

                $action = explode('||', $feedback);

                $action = $action[0];

                switch ($action) {
                    
                    case 'GetDetail__':
                          $feedback = str_replace(' ', '%20', $this->post["feedback"]);

                        $thisTown = strpos(strtolower($feedback), 'douala');

                        if($thisTown!=false){

                            $the_result = $this->getDetail(str_replace('GetDetail__||','',$feedback),'Douala'); 
                        }else{
                            $the_result = $this->getDetail(str_replace('GetDetail__||','',$feedback),'Yaounde');
                        }

                        $this->set_response($the_result, REST_Controller::HTTP_OK); 
                    break;

                    case 'GetMore__':

                        $feedback = $this->post["feedback"];

                        $final_data = str_replace('GetMore__||','',$feedback);

                        $final_data = explode("__",$final_data);

                        //We get the last town and the last type of search of the user
                        $lastData = $this->Main_model->getLastTownAndLastSearchType($id_messenger);

                        $the_result = $this->snifsnif_url($id_messenger,$final_data[0],$final_data[1],$lastData);

                        $this->set_response($the_result, REST_Controller::HTTP_OK);
                    break; 

                    case 'Get_CONTACT__':

                        $feedback = $this->post["feedback"];

                        $final_data = str_replace('Get_CONTACT__||','',$feedback);

                        $nombreContact = explode('___', $final_data);
                        

                        switch (count($nombreContact) ) {
                            case 0:
                               $message_result =  [
                                    "messages"=> [
                                        ["text"=> "Contact non trouvé"]
                                    ]
                                ];
                             break;

                             case 1:
                               $message_result =  [
                                    "messages"=> [
                                        ["text"=> $nombreContact[0]]
                                    ]
                                ];
                             break;

                            case 2:

                                $message_result =  [
                                        "messages"=> [
                                            ["text"=>  $nombreContact[0]],
                                            ["text"=>  $nombreContact[1]]
                                        ]
                                    ];
                             break;
                            
                            default:
                               $message_result =  [
                                    "messages"=> [
                                        ["text"=> "Contact non trouvé"]
                                    ]
                                ];
                                break;
                        }

                        $this->set_response($message_result, REST_Controller::HTTP_OK); 
                
                    break;
                 } 
              break;
            }

    }


    function GetPageByUrl($url,$rest,$id_messenger,$lastData)
    {

        $htmler = $this->get_with_curl_or_404($url);

        if($htmler != 'LeNdem') {
        
            $html = new Simple_html_dom();

            $html->load_file($url);

            $AllListEnterprise = $html->find('div.div_list_nomentreprise');
            $AllDetailEnterprise = $html->find('div.div_list_detailentreprise');
            $Alldiv_categorielist_detail_btn = $html->find('div.div_categorielist_detail_btn a');

            $nombre_list = count($AllListEnterprise);

           

            if($nombre_list>0){

                if($rest==0){
                  $rest = $nombre_list-$rest;
                  $starter = 0;
                }else{
                  $starter=$nombre_list-$rest;
                }
               
                //pagination
                //$number_list_to_take;//Keep the number of enterperise to take on the page

                if($rest>=4){
                    $number_list_to_take=4;
                    $rest=$rest-4;
                }else{
                    $number_list_to_take=$rest;
                    $rest=0;
                }

                $json_data = array();

                if($number_list_to_take>1){

                    for ($i = 0; $i < $number_list_to_take; $i++) { 

                        $this_div_list_nomentreprise = $AllListEnterprise[$starter+$i];
                        $this_div_list_detailentreprise = $AllDetailEnterprise[$starter+$i];
                        $this_div_categorielist_detail_btn = $Alldiv_categorielist_detail_btn[$starter+$i];

                        //We construct all the elements of the list
                        $thisData = [
                                "title"=>$this_div_list_nomentreprise->plaintext,
                                "subtitle"=>html_entity_decode($this_div_list_detailentreprise->plaintext),
                                "image_url"=> base_url()."/assets/search_4883.png",          
                                "buttons"=> [
                                  [
                                    "title"=> "Detail",
                                    "type"=> "show_block",
                                    "block_names"=> [
                                                    "Feedback"
                                                ],
                                    "set_attributes"=> [
                                        "feedback"=> "GetDetail__||http://www.".$lastData->last_town."zoom.com".$this_div_categorielist_detail_btn->href
                                    ]
                                 ]
                                ]
                              ];

                        array_push($json_data,$thisData);
                      
                        if($number_list_to_take==$i+1){
                            
                            if(count($html->find('span.next a'))==0){

                                $NextUrl = 'none';
                            }else{
                                $NextUrl = $html->find('span.next a')[0]->href;
                            }
                            return $this->sendListEnterprise($json_data,$NextUrl,$url,$rest);
                        }
                    }
                }else{//single result

                    $this_div_list_nomentreprise = $AllListEnterprise[$starter];
                    $this_div_list_detailentreprise = $AllDetailEnterprise[$starter];
                    $this_div_categorielist_detail_btn = $Alldiv_categorielist_detail_btn[0];

                    return $this->single_result($id_messenger,$lastData, $this_div_list_nomentreprise->plaintext,$this_div_list_detailentreprise->plaintext,$this_div_categorielist_detail_btn->attr['href']);
                }
            }else{
                return $this->sendTextMessageErrorSearch($id_messenger,$lastData);
            }
        }else{
            return $this->sendTextMessageErrorSearch($id_messenger,$lastData);
        }
    }



    ///Verify if the oage exist
    function get_with_curl_or_404($url){
        
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        curl_close($handle);

        if($httpCode == 404 || !$response || $httpCode == 500 || $httpCode == 0) { // arbitrary choice to return 404 when anything went wront
            return 'LeNdem';
        } else {
            return $response;
        }
    }





    function sendListEnterprise($json_data,$next_page,$url,$rest) {
  
        if($next_page!='none' || $rest!=0){

            if($rest!=0){
                $messageData = [
                
                    "messages" => [
                        [
                            "attachment" => [
                                "type"=> "template",
                                "payload"=> [
                                    "template_type"=> "list",
                                    "top_element_style"=> "compact",
                                    "elements"=>$json_data,
                                    "buttons"=>[
                                        [
                                            "title"=> "Plus",
                                             "type"=> "show_block",
                                             "block_names"=> [
                                                "Feedback"
                                            ],
                                            "set_attributes"=> [
                                                "feedback"=> "GetMore__||".$url."__".$rest
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]; 
            }else{
                $messageData = [
                    "messages"=>[
                        [
                            "attachment"=> [
                                "type"=> "template",
                                "payload"=> [
                                    "template_type"=> "list",
                                    "top_element_style"=> "compact",
                                    "elements"=>$json_data,
                                    "buttons"=>[
                                        [
                                            "title"=> "Plus",
                                            "type"=> "show_block",
                                            "block_names"=> [
                                                "Feedback"
                                            ],
                                            "set_attributes"=> [
                                                "feedback"=> "GetMore__||".$next_page."__0"  
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }else{

            $messageData = [
                "messages"=> [
                    [
                        "attachment"=> [
                            "type"=> "template",
                            "payload"=> [
                                "template_type"=> "list",
                                "top_element_style"=> "compact",
                                "elements"=>$json_data
                            ]
                        ]
                    ]
                ]
            ];
        }

        return $messageData;
    }



    function single_result($id_messenger,$lastData,$nom_entreprise,$detail,$url_detail) {

        $last_town =$lastData->last_town;

        $messageData = [
            "messages"=> [
                [
                    "attachment"=> [
                        "type"=> "template",
                        "payload"=> [
                            "template_type"=> "generic",
                            "elements"=> [
                                [
                                    "title"=> $nom_entreprise,
                                    "subtitle"=>html_entity_decode($detail),
                                    "image_url"=> base_url(). "/assets/search_4883.png?time=1524606955198",
                                    "buttons"=> [
                                        [
                                            "title"=> "Detail",
                                            "type"=> "show_block",
                                            "block_names"=> [
                                                "Feedback"
                                            ],
                                            "set_attributes"=> [
                                                    "feedback"=> "GetDetail__||http://www.".$last_town."zoom.com".$url_detail  
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
      
      
      return $messageData;
    }


    function sendTextMessageErrorSearch($id_messenger,$lastData) {

        $type_search = $lastData->last_search_type;
          
        switch($type_search){

            case 'name':
               $messageText = "Aie! Il y a un soucis. Aucune entreprise de ce nom à ".ucfirst( $lastData->last_town)." n'a été retrouvée pour l'instant 🤕. Tapez 'Recommencer' pour changer vos critères de recherche";
            break;

            case 'sector':
              $messageText = "Aie! Il y a un soucis. Aucun secteur d'activité de ce nom n'est repertorié à ".ucfirst( $lastData->last_town)." pour l'instant 🤕. Tapez 'Recommencer' pour changer vos critères de recherche";
            break;

            case 'quater':
              $messageText = "Aie! Il y a un soucis. Aucun quartier de ce nom à ".ucfirst( $lastData->last_town)." n'a été repertorié pour l'instant 🤕. Tapez 'Recommencer' pour changer vos critères de recherche";
            break;

            default:
              $messageText = "Aie! Il y a un soucis. Votre requête ne peut pas être traitée. Nous revenons vers vous une fois le service rétabli 🤕";
            break;
        }
        
        return $this->Main_model->sendTextMessage($messageText);
    }




    function getDetail($url,$ville) { 

        if($this->web_exists($url)){ //Est ce que l'url existe?

            $html = new Simple_html_dom();

            $html->load_file($url);

            $data = $this->get_this_enterprise_data($html,$ville);

            $data_enterprise = $data;

            $website = $data_enterprise['website'];
            
            if($this->isValidURL($website)){
  
                $this_button = [
                   "type"=>"web_url",
                    "url"=>$website,
                    "title"=>"Site Web"
                ];
            }else{
              
                $this_button = [
                    "type"=> "show_block",
                    "block_names"=> [
                                "Feedback"
                        ],
                    "set_attributes"=> [
                            "feedback"=> "No_WebSite",  
                    ],
                    "title"=>"Pas de site Web"
                ];
            }



            $messageData = [
                "messages"=> [
                    [
                        "attachment"=> [
                            "type"=> "template",
                            "payload"=> [
                                "template_type"=> "generic",
                                "image_aspect_ratio"=> "square",
                                "elements"=> [
                                    [
                                        "title"=> $data_enterprise['nom_entreprise'],
                                        "subtitle"=>'Quartier: '.$data_enterprise['quartier'].' '.$data_enterprise['repere'],
                                        "image_url"=> "https://images.pexels.com/photos/255379/pexels-photo-255379.jpeg?auto=compress&cs=tinysrgb&h=350",
                                        "buttons"=> [
                                            $this_button,
                                            [
                                                "type"=> "show_block",
                                                "title"=> "contact",
                                                "block_names"=> [
                                                    "Feedback"
                                                ],
                                                "set_attributes"=> [
                                                    "feedback"=> "Get_CONTACT__||".$data_enterprise['email'].'___'.$data_enterprise['phone1']  
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            return $messageData;

        }else{
            $messageText = "(404) Aie! Il y a un soucis. Votre requête ne peut pas être traitée. Nous revenons vers vous une fois le service rétabli 🤕";
            
            return $this->Main_model->sendTextMessage($messageText);
        }

       ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // if($data_enterprise['flyer']!=''){
            
        //     sendImageMessageEnterprise(recipientId,data_enterprise.flyer)
        // }

        // if($data_enterprise['video_source']!=''){
        //       sendImageMessageEnterprise(recipientId,data_enterprise.video_source)
        // }
    }



    function sendImageMessageEnterprise($url,$type) {
        
        $messageData = [
            "messages"=> [
                "attachment"=> [
                    "type"=> $type,
                    "payload"=> [
                         "url"=> $url
                    ]
                ]
            ]
        ];

      return $messageData;
    }


    function snifsnif_url($id_messenger,$url,$rest,$lastData) {

        return $this->GetPageByUrl($url,$rest,$id_messenger,$lastData);
    }



    function get_this_enterprise_data($html,$ville) { 

        $site_web = 'http://www.'.strtolower($ville).'zoom.com';
 
        //{nom_entreprise,activity_sector,phone1,phone2,Fax,email,BP,Lat;Long,pays,ville,quartier,addresse_quartier,repère}
        $nom_entreprise = $html->find('.div_titre_show_activite',1)->plaintext; 

        // $activity_sector = $html->find('#content_container  div.content  div.div_list_entreprise_zones_container  div  div.div_list_entreprise_zone1-1__activiteshow  div:nth-child(3)  a').text().split(',');

        $thePhone = $html->find('.div_list_entreprise_zones_container a[href^="tel:"]');

        $NumberPhoneTel = count($thePhone);

        switch ($NumberPhoneTel) {
            case 0:
                $phone1='Aucune information sur le numéro de téléphone';
                $phone2='Aucune information sur le numéro de téléphone';
                $fax='Aucune information sur le numéro de Fax';
                break;
            case 1:
                $phone1=str_replace('tel:','', $html->find('.div_list_entreprise_zones_container a[href^="tel:"]',0)->href);
                $phone2='Aucune information sur le numéro de téléphone secondaire';
                 $fax='Aucune information sur le numéro de Fax';
            break;

            case 2:
                $phone1=str_replace('tel:','', $html->find('.div_list_entreprise_zones_container a[href^="tel:"]',0)->href);
                $phone2=str_replace('tel:','', $html->find('.div_list_entreprise_zones_container a[href^="tel:"]',1)->href);
                 $fax='Aucune information sur le numéro de Fax';
            break;

            case 3:
                $phone1=str_replace('tel:','', $html->find('.div_list_entreprise_zones_container a[href^="tel:"]',0)->href);
                $phone2=str_replace('tel:','', $html->find('.div_list_entreprise_zones_container a[href^="tel:"]',1)->href);
                $fax=str_replace('tel:','', $html->find('.div_list_entreprise_zones_container a[href^="tel:"]',2)->href);
            break;
            
            default:
                $phone1='Problème rencontré';
                $phone2='Problème rencontré';
                $fax='Aucune information sur le numéro de Fax';
            break;
        }

        if(count($html->find('.div_list_entreprise_zones_container a[href^="mailto:"]'))>0){

            $email = str_replace('mailto:', '', $html->find('.div_list_entreprise_zones_container a[href^="mailto:"]', 0)->href) ;
        }else{
            $email = 'Aucune adresse e-mail';
        }


       // $long_text = $html->find('.div_list_entreprise_zone1_container_activiteshow')->plaintext;

        // var BP = '';
        // if(long_text!=null && long_text.indexOf('Boite postale')!=-1){
        //   BP = long_text.split('Boite postale:');
        //   BP = BP[1];
        //   BP = BP.split('</div>');
        //   BP = BP[0].replace(/"/g, '').replace(':','');
        // }

        if(count($html->find('.div_list_entreprise_zone1_container_activiteshow a[target^="_blanc"]'))>0){
            $website = $html->find('.div_list_entreprise_zone1_container_activiteshow a[target^="_blanc"]',0)->href;
        }else{
            $website='Aucun site Web';
        }
       
        $situation = $html->find('.div_intro_cellule01_text',0)->plaintext;

        $pays = 'Cameroun';

        // var ville = this_town;
        $quartier = explode('à:', $situation);
        $quartier = $quartier[1];

        
        if($quartier){

            $quartier = explode(',', $quartier);
          
            if(count($quartier)>1){
                $quartier = $quartier[0];
                $rue = $quartier[1];
            }else{
                $quartier = $quartier[0];
            }
        }else{
          $quartier = 'Nous ne trouvons pas le quartier';
        }
        
        
        if(count($html->find('.div_directeurgeneral'))!=0){

            $directeur_general =  $html->find('.div_directeurgeneral',0)->plaintext;
        }else{
            $directeur_general='__';
        }

        $flyer = 'Aucun flyer';

        $video = 'Aucune vidéo';

       // var position = $('#content_container > div.content > script:nth-child(12)').html();
        // if(position!=null){

        //   position = position.split('center: new google.maps.LatLng(');

        //   position = position[1];
        //   position = position.split("),");
        //   position = position[0];
        //   position = position.split(',')
        //   position = {longitude:position[0]*1,latitude:position[1]*1};
        // }else{
        //   position = '';
        // }
       
        if(count($html->find('.div_repere'))==1){

            $repere = str_replace('\n', '', $html->find('.div_repere',0)->plaintext);
            $repere = str_replace('\t', '', $repere);
        }else{
            $repere = "Repère non trouvé";
        }
       

        if(count($html->find('.div_showActivite_logo img'))==1){

            $logo =  $html->find('.div_showActivite_logo img',0)->src;
            $logo = $site_web.$logo;

        }else{
            $logo = 'Logo absent';
        }

        
        $all_data = [
            "nom_entreprise"=>$nom_entreprise,
            "logo"=>$logo,
            // "activity_sector"=>$activity_sector,
            "phone1"=>$phone1,
            "phone2"=>$phone2,
            "fax"=>$fax,
            "email"=>$email,
            // BP:BP,
            "website"=>$website,
            "pays"=>$pays,
            "ville"=>$ville,
            "quartier"=>$quartier,
            "rue"=>$rue,
            "directeur_general"=>$directeur_general,
            "flyer"=>$flyer,
            "video_source"=>$video,
            
            // position:position,
            "repere"=>$repere
        ];

        return $all_data;
    }



    function isValidURL($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
            
            return true;
        } else {
            return false;
        }
    }


    function web_exists($url) {
        $headers = @get_headers($url, 1);
        if ($headers[0]=='') return false;
        return !((preg_match('/404/', $headers[0]))==1); 
    }




}
