<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function index() {
        // if ($this->session->userdata('admin_login')) {
        //     redirect(site_url('admin'), 'refresh');
        // }elseif ($this->session->userdata('user_login')) {
        //     redirect(site_url('user'), 'refresh');
        // }else {
        //     redirect(site_url('home/login'), 'refresh');
        // }
    }
    
    public function log_update()
    {
        date_default_timezone_set('Asia/Colombo');
        
        $today = strtotime(date("Y/m/d"));
        
        // Checking Live Class Date
        
        $this->db->order_by('updateDate', 'desc');
        $this->db->limit(1);
        $query = $this->db->get_where('live_class', array('date' => $today));
        
        if ($query->num_rows() > 0) {
            
            $row = $query->row();
            $startTime =  $row->time;
            $endTime = $row->end_time;
            $nowTime = strtotime(date('h:i A',time()));
            
            if($startTime < $nowTime && $nowTime < $endTime){
           
            }else{
                
               $param = 'login_method';
               $this->db->where('key' ,$param);
               $this->db->update('settings' , array('value' => 0));
               //$this->db->update('users' , array('log_active' => 0));
            }
            
        }else{
            
           $param = 'login_method';
           $this->db->where('key' ,$param);
           $this->db->update('settings' , array('value' => 0));
        }
    }

}
