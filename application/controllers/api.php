<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
    var $data;
    function debug($obj){
       $fp = fopen('debug.txt', 'a');
       fputs($fp, print_r($obj, true)."\n");
       fclose($fp);
    }

    public function __construct() {
        parent::__construct();
        $valid = !(
        		empty($_SERVER['CONTENT_TYPE']) ||
        		$_SERVER['CONTENT_TYPE'] != 'application/json; charset=UTF-8' ||
        		!(isset($_SERVER['HTTP_API_KEY']) && $_SERVER['HTTP_API_KEY'] == config_item('api_key')));

        if($valid) {
        	$this->data = json_decode(file_get_contents('php://input'), TRUE);
            $valid = !!count($this->data);
        }
        if(!$valid) {
        	echo "Invalid Request";
        	exit;
        }
    }
    
    
   public function user_signup() {
     	$this->debug("Signup Request");
      $request_fields = array('signup_mode');
      $request_form_success = true;
    	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
    		}
	    }
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->user_signup($this->data);
      }  
      echo json_encode($response);
   }  

   
   public function user_login() {
    $this->debug("Login Request");
    	$request_fields = array('user_email', 'user_password');
    	$request_form_success = true;
    	foreach($request_fields as $request_field) {
    		if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
    			$request_form_success = false;
    			break;
    		}
    	}
    	if(!$request_form_success) {
    		$response['status'] = 0;
    		$response['msg'] = config_item('msg_fill_form');
    	} else {
    		$response = $this->api_model->user_login($this->data);
    	}
    	echo json_encode($response);
    }
   
    public function user_retrieve_password() {
    	$request_fields = array('user_email');
    	$request_form_success = true;
    	foreach ($request_fields as $request_field) {
    		if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
    			$request_form_success = false;
    			break;
    		}
    	}
    	if (!$request_form_success) {
    		$response['status'] = 0;
    		$response['msg'] = config_item('msg_fill_form');
    	} else {
    		$response = $this->api_model->user_retrieve_password($this->data);
    	}
    	echo json_encode($response);
    }
    
    public function user_logout() {
    	$request_fields = array('user_id');
    	$request_form_success = true;
    	foreach($request_fields as $request_field) {
    		if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
    			$request_form_success = false;
    			break;
    		}
    	}
    	if(!$request_form_success) {
    		$response['status'] = 0;
    		$response['msg'] = config_item('msg_fill_form');
    	} else {
    		$response = $this->api_model->user_logout($this->data);
    	}
    	echo json_encode($response);
    }

public function save_user_qb_id() {
    	$request_fields = array('user_id');
    	$request_form_success = true;
    	foreach($request_fields as $request_field) {
    		if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
    			$request_form_success = false;
    			break;
    		}
    	}
    	if(!$request_form_success) {
    		$response['status'] = 0;
    		$response['msg'] = config_item('msg_fill_form');
    	} else {
    		$response = $this->api_model->save_user_qb_id($this->data);
    	}
    	echo json_encode($response);
    }

    public function get_all_users() {
    	$request_fields = array('user_id');
    	$request_form_success = true;
    	foreach($request_fields as $request_field) {
    		if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
    			$request_form_success = false;
    			break;
    		}
    	}
    	if(!$request_form_success) {
    		$response['status'] = 0;
    		$response['msg'] = config_item('msg_fill_form');
    	} else {
    		$response = $this->api_model->get_all_users($this->data);
    	}
    	echo json_encode($response);
    }
    
   public function edit_user() {
     	$request_fields = array('user_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->edit_user($this->data);
      }  
      echo json_encode($response);
   }     

   public function delete_user() {
     	$request_fields = array('user_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->delete_user($this->data);
      }  
      echo json_encode($response);
   }

   public function create_product() {
     	$request_fields = array('user_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->create_product($this->data);
      }  
      echo json_encode($response);
   }

  ///////////////DOC ////////////////



  //////////////GIFT./////////////////
  public function send_gift() {
    $request_fields = array('user_id');
    $request_form_success = true;
      foreach($request_fields as $request_field) {
        if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
          $request_form_success = false;
          break;
        }
      }
      if(!$request_form_success) {
        $response['status'] = 0;
        $response['msg'] = config_item('msg_fill_form');
      } else {
        $response = $this->api_model->send_gift($this->data);
      }
      echo json_encode($response);
    }  
  public function load_all_gifts(){
    $request_fields = array('user_id');
    $request_form_success = true;
      foreach($request_fields as $request_field) {
        if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
          $request_form_success = false;
          break;
        }
      }
      if(!$request_form_success) {
        $response['status'] = 0;
        $response['msg'] = config_item('msg_fill_form');
      } else {
        $response = $this->api_model->load_all_gifts($this->data);
      }
      echo json_encode($response);
  }
  public function load_all_sent_gifts(){
    $request_fields = array('user_id');
    $request_form_success = true;
      foreach($request_fields as $request_field) {
        if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
          $request_form_success = false;
          break;
        }
      }
      if(!$request_form_success) {
        $response['status'] = 0;
        $response['msg'] = config_item('msg_fill_form');
      } else {
        $response = $this->api_model->load_all_sent_gifts($this->data);
      }
      echo json_encode($response);
  }
  public function load_all_receive_gifts(){
    $request_fields = array('user_id');
    $request_form_success = true;
      foreach($request_fields as $request_field) {
        if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
          $request_form_success = false;
          break;
        }
      }
      if(!$request_form_success) {
        $response['status'] = 0;
        $response['msg'] = config_item('msg_fill_form');
      } else {
        $response = $this->api_model->load_all_receive_gifts($this->data);
      }
      echo json_encode($response);
  }
   public function load_new_receive_gift(){
    $request_fields = array('user_id');
    $request_form_success = true;
      foreach($request_fields as $request_field) {
        if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
          $request_form_success = false;
          break;
        }
      }
      if(!$request_form_success) {
        $response['status'] = 0;
        $response['msg'] = "Unknown user_id";
      } else {
        $response = $this->api_model->load_new_receive_gift($this->data);
      }
      echo json_encode($response);
  }
  
  public function read_gift(){
    $this->debug("read gift");
    $this->debug($this->data);
    $request_fields = array('gift_id');
    $request_form_success = true;
      foreach($request_fields as $request_field) {
        if(!(isset($this->data[$request_field]) && $this->data[$request_field] != '')) {
          $request_form_success = false;
          break;
        }
      }
      if(!$request_form_success) {
        $response['status'] = 0;
        $response['msg'] = "You not sent gift id";
      } else {
        $response = $this->api_model->update_gift_status($this->data);
      }
      echo json_encode($response);
  }


  ////////////////GIFT END/////////////  








   public function edit_product() {
     	$request_fields = array('product_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->edit_product($this->data);
      }  
      echo json_encode($response);
   }

   public function delete_product() {
     	$request_fields = array('product_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->delete_product($this->data);
      }  
      echo json_encode($response);
   }

   public function create_patient() {
     	$request_fields = array('user_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->create_patient($this->data);
      }  
      echo json_encode($response);
   }

   public function edit_patient() {
     	$request_fields = array('patient_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->edit_patient($this->data);
      }  
      echo json_encode($response);
   }

   public function delete_patient() {
     	$request_fields = array('patient_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->delete_patient($this->data);
      }  
      echo json_encode($response);
   }

   public function create_bandagistery() {
     	$request_fields = array('user_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->create_bandagistery($this->data);
      }  
      echo json_encode($response);
   }

   public function edit_bandagistery() {
     	$request_fields = array('bandagistery_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->edit_bandagistery($this->data);
      }  
      echo json_encode($response);
   }

   public function delete_bandagistery() {
     	$request_fields = array('bandagistery_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->delete_bandagistery($this->data);
      }  
      echo json_encode($response);
   }

   public function create_pharmacy() {
     	$request_fields = array('user_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->create_pharmacy($this->data);
      }  
      echo json_encode($response);
   }

   public function edit_pharmacy() {
     	$request_fields = array('pharmacy_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->edit_pharmacy($this->data);
      }  
      echo json_encode($response);
   }

   public function delete_pharmacy() {
     	$request_fields = array('pharmacy_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->delete_pharmacy($this->data);
      }  
      echo json_encode($response);
   }

   public function create_intervention() {
     	$request_fields = array('user_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->create_intervention($this->data);
      }  
      echo json_encode($response);
   }

   public function edit_intervention() {
     	$request_fields = array('intervention_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->edit_intervention($this->data);
      }  
      echo json_encode($response);
   }

   public function delete_intervention() {
     	$request_fields = array('intervention_id');
      $request_form_success = true;
	foreach ($request_fields as $request_field){		
	      if (!isset($this->data[$request_field])) {
                $request_form_success = false;
                break;
		}
	}
      if (!$request_form_success) {
            $response['status'] = 0;
            $response['msg'] = config_item('msg_fill_form');
      } else {  
            $response = $this->api_model->delete_intervention($this->data);
      }  
      echo json_encode($response);
   }

}
