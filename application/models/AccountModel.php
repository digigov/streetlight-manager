<?php

class AccountModel extends CI_Model {
  var $_table = "account";
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  public function login($acc,$pwd){
    $this->db->where("acc",$acc);
    // $this->db->where("pwd",$pwd);
    $u = $this->db->get($this->_table);

    $res = $u->result();
    foreach($res as $user){
      if(password_verify($pwd,$user->pwd)){
        return $user;
      }
    }
    return null;

  }

  public function get_line_token($uid){

    $this->db->where("id",$uid);
    $q= $this->db->get($this->_table);
    $user = array_first_item($q->result());
    if($user->line_verify_token != null){
      return $user->line_verify_token;
    }

    $hash = uniqid();
    $token = password_hash($hash,PASSWORD_BCRYPT);
    
    $this->db->where("id",$uid);
    $this->db->set("line_verify_token",$token);
    $this->db->update($this->_table);

    
    return $token;

  }

  public function get_line_by_city($city){
    $this->db->where("line_access_token is not null",null,false);
    $this->db->where("city",$city);
    $q = $this->db->get($this->_table);

    return $q->result();
    
  }

  public function set_user_line_token($type,$code,$access_token){

    $this->db->where("line_verify_token",$code);

    if($type=="report"){
      $this->db->set("line_access_token",$access_token);
    }else{
      $this->db->set("line_led_access_token",$access_token);
    }
    
    $this->db->update($this->_table);
    
  }

  public function set_pwd($uid,$pwd){

    $this->db->where("id",$uid);
    $this->db->set("pwd", password_hash($pwd,PASSWORD_BCRYPT));
    $this->db->update($this->_table);    
  }
  
}