<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Light extends MY_ADMIN_Controller {
  var $_enable_cookie_write_methods = [];

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("lightModel");
  }

  public function index(){

    $unCity = $_SESSION["user"]->city;
    $city_counts = $this->lightModel->get_city_counts($unCity);
    $reports =  $this->lightModel->get_unhandled_city_reports($unCity);

    $this->load->view('admin/light/index',
      [
        "city_counts" => $city_counts,
        "city" => $unCity,
        "reports" => $reports
      ] );
  }

  public function point_list(){
    $this->load->view('admin/light/point_list',
      [
        "points" => $this->lightModel->get_all_for_map( ),
        "towns" => $this->lightModel->get_all_towns()
      ] );
    session_write_close();    
  }

  public function reports(){

    $unCity = $_SESSION["user"]->city;
    $reports =  $this->lightModel->get_city_reports($unCity);

    $this->load->view('admin/light/reports',
      [
        "city" => $unCity,
        "reports" => $reports
      ] );
  }

  public function repair(){
    $unCity = $_SESSION["user"]->city;
    $lights = $this->lightModel->get_repair_light_by_city($unCity);

    $this->load->view('admin/light/repair',
      [
        "city" => $unCity,
        "lights" => $lights
      ] );
  }


  public function set_report_status($report,$status){
    $this->lightModel->set_report_status($report,$status);


    if($status == 2){
      $report = $this->lightModel->get_report_status($report);
      $light = $this->lightModel->get_city($report->light_id);


      $this->load->model("accountModel");
      $users = $this->accountModel->get_line_by_city($record->city);

      foreach($users as $u){
        send_message($u->line_led_access_token,"路燈報修回報，路燈編號:".$record->name.
          "\n在 google map 顯示: https://www.google.com.tw/maps?q=".$record->lat.",".$record->lng
        );
      }

    }

    redirect("admin/light/index");
  }

  public function change_loc($id){
    $lat = $this->input->post("lat");
    $lng = $this->input->post("lng");

    $ret = $this->lightModel->change_light_loc($id,$lat,$lng);

    die(json_encode(["success"=>$ret]));

  }

  public function action_submit(){
    $unCity = $_SESSION["user"]->city;

    $ids = $this->input->post("ids"); //array
    $inputs = [];

    if(empty($ids)){
      die("wrong input ");
    }

    foreach($ids as $id){
      $inputs[] = $id;
    }
    
    $lights = $this->lightModel->get_repair_light_by_ids_city($unCity,$inputs);

    if($this->input->post("action") == "1"){
      header('Content-type:application/force-download'); //告訴瀏覽器 為下載 
      header('Content-Transfer-Encoding: Binary'); //編碼方式
      header('Content-Disposition:attachment;filename=維修座標點- '.date("Y.m.d").".gdb"); //檔名 
      
      $this->load->view('xml/gpx_template',
        [
          "city" => $unCity,
          "lights" => $lights
        ] );
        
    }else if($this->input->post("action") == "3"){

      header('Content-type:application/force-download'); //告訴瀏覽器 為下載 
      header('Content-Transfer-Encoding: Binary'); //編碼方式
      header('Content-Disposition:attachment;filename=維修單- '.date("Y.m.d").".xlsx"); //檔名 
      return $this->_export_xlsx($unCity,$lights);

    }else if($this->input->post("action") == "2"){

      $lights = $this->lightModel->fix_light_by_ids($unCity,$inputs);
      redirect("admin/light/repair");
    }else{
      die("未知的選項");
    }
    

  }

  public function _export_xlsx($city,$lights){
    require(__DIR__."/../../../vendor/autoload.php");
    $objPHPExcel = PHPExcel_IOFactory::load(__DIR__."/../../../public/excel/repair_reports.xlsx");


    $objPHPExcel->getActiveSheet()->setCellValue('G1', 
        (date("Y")-1911)."年".(date("m"))."月".date("d")."日交大發"
      );

    foreach($lights as $ind => $light){
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($ind+3), 
        $light->town_name."/".$light->name
      );
      // $objPHPExcel->getActiveSheet()->setCellValue('B'.($ind+2), 
        // $light->comment
      // );
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    // $objPHPExcel = new PHPExcel();

  }

}
