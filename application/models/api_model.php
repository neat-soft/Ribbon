<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {

    public function __construct() {

    }

    function debug($obj){
       $fp = fopen('debug.txt', 'a');
       fputs($fp, print_r($obj, true)."\n");
       fclose($fp);
    }

    public function user_signup($params){
        
        $result = array();
        $data = array();
        $status = 0;
        $msg = '';
       
        if((int)$params['signup_mode'] == 1) {
           
            $query = $this->db->get_where('rb_users', array('user_email'=>$params['user_email']));
           
            if($query->num_rows() == 0){
                
                $request_fields = array('user_email', 'user_password', 'user_first_name', 'user_last_name', 'user_qb_id','user_language','user_long','user_lat');
                
                foreach($request_fields as $request_field){
                    
                    if(isset($params[$request_field]) && $params[$request_field] != ''){
                        
                        $data[$request_field] = $params[$request_field];
                        
                        if($request_field == 'user_password'){
                            
                            $data[$request_field] = $this->get_user_auth_salt($params[$request_field]);
                        }
                    }
                }

                $current_date = date('Y-m-d H:i:s');
                $data['user_created_at'] = $current_date;
                $data['user_last_updated_date'] = $current_date;
                $this->db->insert('rb_users', $data);
                $insert_id = $this->db->insert_id();
                $data['user_id'] = $insert_id;
                $result['current_user'] = $this->db->get_where('rb_users', array('user_id'=>$data['user_id']))->row_array();
                $result['status'] = 1;
                $result['msg'] = 'success';

            }else{

                $result['status'] = 2;
                $result['msg'] = 'Same email has been existed already!';
            }
        }else if((int)$params['signup_mode'] == 2) {

                $request_fields = array('user_facebook_id', 'user_email', 'user_first_name', 'user_last_name','user_facebook_photo_url', 'user_qb_id','user_language', 'user_lat', 'user_long');
                $query = $this->db->get_where('rb_users', array('user_facebook_id' => $params['user_facebook_id']));

                if ($query->num_rows() > 0) {
	        	
	        	$request_fields_update = array('user_email', 'user_first_name', 'user_last_name', 'user_lat', 'user_long','user_facebook_photo_url', 'user_qb_id','user_language');
	        	
                foreach ($request_fields_update as $request_field) {
	        		
	        		if(isset($params[$request_field])) {
	        			
                        $data[$request_field] = $params[$request_field];
	        		}
	        	}
	        		        	
	        	$update_date = date('Y-m-d h:i:s');
	        	$data['user_last_updated_date'] = $update_date;
	        	
	        	$this->db->update('rb_users', $data, array('user_facebook_id' => $params['user_facebook_id']));
	        	
                $result['current_user'] = $this->db->get_where('rb_users', array('user_facebook_id'=>$params['user_facebook_id']))->row_array();
                $result['status']=1;
                $result['msg']="Success";
	        } else {
	        	
                 $query = $this->db->get_where('rb_users', array('user_email'=>$params['user_email']));
           
                if($query->num_rows() == 0){

                    foreach ($request_fields as $request_field) {
	        		
                        if(isset($params[$request_field])) {
                            $data[$request_field] = $params[$request_field];
                        }
	        	    }
                    $signup_date = date('Y-m-d h:i:s');
                    $data['user_created_at'] = $signup_date;
                    $data['user_last_updated_date'] = $signup_date;
                    
                    $this->db->insert('rb_users', $data);
                    $insert_id = $this->db->insert_id();
                    $data['user_id'] = $insert_id;
                    $result['current_user'] = $this->db->get_where('rb_users', array('user_id'=>$data['user_id']))->row_array();
                    $result['status']=1;
                    $result['msg']="Success";
                }else{
                    $result['status'] = 2;
                    $result['msg'] = 'Email already exist!';
                }
            }
        }
        return $result; 
    }

     public function user_login($params) {
    	$result = array();
    	$status = 0;
    	$msg = '';
    	$query = $this->db->select('user_id')->get_where('rb_users', array('user_email' => $params['user_email'], 'user_password' => $this->get_user_auth_salt($params['user_password'])));
    	if($query->num_rows() > 0) {
            if(isset($params['user_long']) && isset($params['user_lat']))
                $this->db->update('rb_users', array('user_long'=>$params['user_long'], 'user_lat'=>$params['user_lat']), array('user_email'=>$params['user_email']));
            $result['current_user'] = $this->db->get_where('rb_users', array('user_email'=>$params['user_email']))->row_array();
    		
    		$status = 1;
            $msg = 'success';
    	} else {
    		$status = 2;
    		$msg = 'Email and password do not match';
    	}

      // //// Get New gifts count////
      // $query =  $this->db->get_where('rb_gifts', array('receiver'=>$params['user_id'],'status'=>'unread'));
      // if($query->num_rows() > 0) {
      //    $result['new_gifts'] = $query->num_rows(); 
      // } 
    	$result['status'] = $status;
    	$result['msg'] = $msg;
    	return $result;
    }

    public function save_user_qb_id($params) {
    	$result = array();
    	$status = 0;
    	$msg = '';
    	$query = $this->db->get_where('rb_users', array('user_id' => $params['user_id']));
    	if($query->num_rows() > 0) {
            $this->db->update('rb_users', array('user_qb_id'=>$params['user_qb_id']), array('user_id'=>$params['user_id']));
            $result['current_user'] = $this->db->get_where('rb_users', array('user_id'=>$params['user_id']))->row_array();
    		
    		$status = 1;
            $msg = 'success';
    	} 
    	$result['status'] = $status;
    	$result['msg'] = $msg;
    	return $result;
    }

    public function get_all_users($params) {
    	$result = array();
    	$status = 0;
    	$msg = '';
    	$query = $this->db->get_where('rb_users', array('user_id' => $params['user_id']));
    	if($query->num_rows() > 0) {
            $this->db->update('rb_users', array('user_long'=>$params['user_long'], 'user_lat'=>$params['user_lat']), array('user_id'=>$params['user_id']));
            $result['all_users'] = $this->db->get_where('rb_users', array('user_id !='=>$params['user_id']))->result_array();
    		
    		$status = 1;
            $msg = 'success';
    	} 
    	$result['status'] = $status;
    	$result['msg'] = $msg;
    	return $result;
    }

    public function user_logout($params) {
    	$result = array();
    	$status = 0;
    	$msg = '';
    	
    	// if((int)$params['device_type'] == 1) {
    	// 	$this->db->update('wf_users', array('user_apns_id' => ''), array('user_id' => $params['user_id']));
    	// } else if((int)$params['device_type'] == 2) {
    	// 	$this->db->update('wf_users', array('user_gcm_id' => ''), array('user_id' => $params['user_id']));
    	// }
    	
    	$status = 1;
    	
    	$result['status'] = $status;
    	$result['msg'] = $msg;
    	return $result;
    }
    
    public function user_retrieve_password($params) {
    	$result = array();
    	$status = 0;
    	$msg = '';
    	$query = $this->db->get_where('rb_users', array('user_email' => $params['user_email']));
    	if($query->num_rows() > 0) {
    		
    		$current_user = $query->row_array();
    		
    		$digits = config_item('user_new_password_length');
			$new_password = rand(pow(10, $digits-1), pow(10, $digits)-1);
			
			// Send Mail
			$to = $params['user_email'];
		
			$subject = 'Ribbon Social - Password Reset';
			
			$message = '
			Thanks for trying Ribbon ' . (($current_user['user_first_name'] != '') ? $current_user['user_first_name'] : $current_user['user_name']) . '!<br>
			<br>
			You can now login using the credentials below:<br>
			<br>
			<b>Email Address:</b><br>
			' . $current_user['user_email'] . '<br>
			<br>
			<b>Password:</b><br>
			' . $new_password . '<br>
			<br><br>
			Happy barking!<br>
			';

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			// More headers
			$headers .= 'From: <no-reply@Ribbonsocial.com>' . "\r\n";
			//$headers .= 'Cc: cc@example.com' . "\r\n";
			
			if(mail($to, $subject, $message, $headers)) {
				$status = 1;
			} else {
				$status = 3;
				$msg = 'An error occurred while resetting a new password.';
			}
			
			if($status == 1) {
				$this->db->update('rb_users', array('user_password' => $this->get_user_auth_salt(md5($new_password))), array('user_email' => $params['user_email']));
			}
    		
    	} else {
    		$status = 2;
    		$msg = 'Your requested email is not registered to our system.';
    	}
    	$result['status'] = $status;
    	$result['msg'] = $msg;
    	return $result;
    }

    public function edit_user($params){
        $result = array();
        $status = 0;
        $msg = '';
        $query = $this->db->get_where('rb_users', array('user_id'=>$params['user_id']));
        if($query->num_rows() > 0){
            $update_data = array();
            $request_fields = array('user_first_name', 'user_last_name',  'user_language');
            foreach($request_fields as $request_field){
                if(isset($params[$request_field]) && $params[$request_field] != ''){
                    $update_data[$request_field] = $params[$request_field];
                }
            }
            if(isset($params['photo_avatar'])){
                $created_time = time();
                $image_path = config_item('path_media_users');
                $image_name =$created_time  . '_' . $params['user_id'] . '.jpg';
                $image_url = $image_path . $image_name;
                $binary = base64_decode($params['photo_avatar']);
                header('Content-Type: bitmap; charset=utf-8');
                $file = fopen($image_url, 'w');

                if($file) {
                    fwrite($file, $binary);
                } else {
                    $status = 3;
                    $msg = 'File Upload failed';
                }
                fclose($file);
                $photo_url = config_item('base_url') . $image_url;
                $update_data['user_photo_url'] = $photo_url;
            }
            $this->db->update('rb_users', $update_data, array('user_id'=>$params['user_id']));
            $result['current_user'] = $this->db->get_where('rb_users', array('user_id'=>$params['user_id']))->row_array();
            $status = 1;
            $msg = 'success';
        }
        $result['status'] = $status;
        $result['msg'] = $msg;
        return $result;
    }

    public function delete_user($params){
        $result = array();
        $status = 0;
        $msg = '';
        $query = $this->db->get_where('uc_users', array('user_id'=>$params['user_id']));
        if($query->num_rows() > 0){
            $this->db->delete('uc_users', array('user_id'=>$params['user_id']));
            $status = 1;
            $msg = 'success';
        }
        $result['status'] = $status;
        $result['msg'] = $msg;
        return $result;
    }

    

    public function get_user_auth_salt($password) {
        return sha1(config_item('user_auth_salt') . md5($password));
    }

    public function send_gift($params) {
      $result = array();
      $status = 0;
      $msg = '';    
      $request_fields = array('sender', 'receiver', 'gift','type');
      
      foreach($request_fields as $request_field){
        if(isset($params[$request_field]) && $params[$request_field] != ''){
            $data[$request_field] = $params[$request_field];
        }
      }
      if(isset($params['gift'])){
          $created_time = time();
          $image_path = config_item('path_media_gifts');
          $type = ".jpg";
          if ($params['type'] == 1){
            $type = ".pdf";
          }
          $image_name =$created_time  . '_' . $params['sender'] . $type;
          $image_url = $image_path . $image_name;
          $binary = base64_decode($params['gift']);
          header('Content-Type: bitmap; charset=utf-8');
          $file = fopen($image_url, 'w');

          if($file) {
              fwrite($file, $binary);
          } else {
              $status = 3;
              $msg = 'File Upload failed';
              $result['status'] = 3;
              $result['msg'] = 'File upload failed';
              return $result;
          }
          fclose($file);
          $gitf_url = config_item('base_url') . $image_url;
          $data['gift'] = $gitf_url;
      }
      $current_date = date('Y-m-d H:i:s');
      $data['created_at'] = $current_date;
      $sender = $this->db->get_where('rb_users', array('user_id'=>$params['sender']))->row_array();
      $receiver = $this->db->get_where('rb_users', array('user_id'=>$params['receiver']))->row_array();
      $data['sender_name'] = $sender['user_first_name'] . ' ' .  $sender['user_last_name'];
      $data['receiver_name'] = $receiver['user_first_name'] . ' ' . $receiver['user_last_name'];
      $data['type'] = $params['type'];
      $res = $this->db->insert('rb_gifts', $data);
      $result['status'] = 1;
      $result['msg'] = 'Gift Sent';
      return $result;
    }
    public function load_all_gifts($params){
      $msg = 'not found gift';
      $status = 0;
      $query =  $this->db->get_where('rb_gifts', array('sender'=>$params['user_id']));
      if($query->num_rows() > 0) {
          $result['gifts'] = $this->db->get_where('rb_gifts')->result_array();
          $status = 1;
          $msg = 'success';
      } 
      $result['status'] = $status;
      $result['msg'] = $msg;
      return $result;
    }
    public function load_all_sent_gifts($params){
      $status = 0;
      $msg = '';
      $query =  $this->db->get_where('rb_gifts', array('sender'=>$params['user_id']));
      if($query->num_rows() > 0) {
          $result['sent_gifts'] = $this->db->get_where('rb_gifts', array('sender'=>$params['user_id']))->result_array();
          $status = 1;
          $msg = 'success';
      } 
      $result['status'] = $status;
      $result['msg'] = $msg;
      return $result;
    }
     public function load_all_receive_gifts($params){
      $status = 0;
      $msg = '';
      $query =  $this->db->get_where('rb_gifts', array('sender'=>$params['user_id']));
      if($query->num_rows() > 0) {
          $result['sent_gifts'] = $this->db->get_where('rb_gifts', array('sender'=>$params['user_id']))->result_array();
          $status = 1;
          $msg = 'success';
      } 
      $result['status'] = $status;
      $result['msg'] = $msg;
      return $result;
    }
    public function load_new_receive_gift($params){
      $query = $this->db->get_where('rb_gifts', array('receiver'=>$params['user_id'],'status'=>"unread"));
      $result['new_gifts'] = $query->num_rows();
      $result['status'] = 1;
      $result['msg'] = "success";
      return $result;
    }
    public function update_gift_status($params){
      $status = 0;
      $msg = 'Not found gift with your gift_id';
      $query =  $this->db->get_where('rb_gifts', array('id'=>$params['gift_id']));
      if($query->num_rows() > 0) {
          $this->db->update('rb_gifts',  array('status'=>"read"), array('id'=>$params['gift_id']));
          $status = 1;
          $msg = 'success';
      } 
      $result['status'] = $status;
      $result['msg'] = $msg;
      return $result;
    }
}
