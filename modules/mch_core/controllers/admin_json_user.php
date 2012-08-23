<?php
class Admin_json_user_Controller extends Template_Controller {

	public $template = 'pdf/index';
	
	public function __construct()
    {
		parent::__construct();
        $this->user_model = new User_Model();
    }
	
    public function check_user_exists($user_name='',$user_id='0')
    {
		$this->db->where(array('user_id <>' => $user_id));
		$this->db->where('user_name', $user_name);
		$data = $this->user_model->get();
		if($data != false) {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('register_validation.txt_username._check_user');
		} else {
			$json['status'] = true;
			$json['msg'] = 'OK.!';
		}
		echo json_encode($json);
	}
    
    public function check_oldpass($user_id='0',$pass='')
    {
		$this->db->where(array('user_id =' => $user_id));
        if($pass!='') $this->db->where('user_pass', md5($pass));
		$data = $this->user_model->get();
		if($data || $pass=='') {
            $json['status'] = true;
			$json['msg'] = 'OK.!';
		} else {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('account_validation.txt_old_pass._check_old_pass');
		}
		echo json_encode($json);
	}
    
    public function check_email_exists($email='',$user_id='0')
    {
        $this->db->where(array('user_id <>' => $user_id));
		$this->db->where('user_email', $email);
		$data = $this->user_model->get();
		if($data != false) {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('register_validation.txt_email._check_email');
		} else {
			$json['status'] = true;
			$json['msg'] = 'OK.!';
		}
		echo json_encode($json);
	}
    
    public function check_user_email_exists($user_name='',$email='',$user_id='0')
    {
		$this->db->where(array('user_id <>' => $user_id));
		if($user_name) $this->db->where('user_name', $user_name);
        if($email) $this->db->where('user_email', $email);
		$data = $this->user_model->get();
		if($data != false) {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('register_validation.txt_username._check_user');
		} else {
			$json['status'] = true;
			$json['msg'] = 'OK.!';
		}
		echo json_encode($json);
	}
}