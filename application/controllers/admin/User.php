<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Milon\Barcode\DNS2D;


class User extends MY_ADMIN_Controller {
  var $_enable_cookie_write_methods = ["signing","logout"];

  public function index(){

    $this->load->view('admin/user/index',[]);
  }

  public function logout(){
    unset($_SESSION["user"]);
    return redirect("admin/user/signin");
    
  }

  public function line_connect(){

    require(__DIR__."/../../../vendor/autoload.php");

    $d = new DNS2D();
    $d->setStorPath(__DIR__."/../../cache/");


    $this->load->database();
    $this->load->model("accountModel");
    $token = $this->accountModel->get_line_token($_SESSION["user"]->id);
    $this->load->view('admin/user/line_connect',['user' => $_SESSION["user"],"token" => $token,
      "code64" => $d->getBarcodePNG(site_url("/line/line_connect?token=".$token."&type=report"), "QRCODE") ,
      "auth_url" => site_url("/line/line_connect?token=".$token."&type=report")
    ]);
    
  }

  public function line_bind2_connect(){
      require(__DIR__."/../../../vendor/autoload.php");
  

      $d = new DNS2D();
      $d->setStorPath(__DIR__."/../../cache/");

      
      $this->load->database();
      $this->load->model("accountModel");
      $token = $this->accountModel->get_line_token($_SESSION["user"]->id);
      $this->load->view('admin/user/line_connect',['user' => $_SESSION["user"],"token" => $token,
        "code64" => $d->getBarcodePNG(site_url("/line/line_connect?token=".$token."&type=led"), "QRCODE") ,
        "auth_url" => site_url("/line/line_connect?token=".$token."&type=led")
      ]);
      
    }

  public function signin(){

    $this->load->view('admin/user/signin',["fail" => $this->input->get("fail")]);

  }

  public function pass(){
    $status = $this->input->get("status");
    $this->load->view('admin/user/pass',["status"=>$status]);
  }

  public function passing(){

    $pwd = $this->input->post("pwd");
    $pwd2 = $this->input->post("pwd2");

    if($pwd != $pwd2){
      return redirect(site_url("admin/user/pass?status=2"));
    }

    $this->load->model("accountModel");
    $this->accountModel->set_pwd($_SESSION["user"]->id,$pwd);
    return redirect(site_url("admin/user/pass?status=1"));


  }

  public function signing(){

    $acc = $this->input->post("account");
    $pwd = $this->input->post("pwd");

    $this->load->database();
    $this->load->model("accountModel");

    $user = $this->accountModel->login($acc,$pwd);

    if($user == null){
      return redirect("admin/user/signin?fail=1");
    }

    $_SESSION["user"] = $user;

    return redirect("admin/light/index");

  }
}
