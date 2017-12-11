<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Road extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model("roadModel");
		
	}

	public function index(){
	}


	public function upload(){
		@mkdir(__DIR__."/../uploads");
		$target_dir = __DIR__."/../uploads/";

		$imageFileType = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);

		$target_filename = uniqid().".".$imageFileType;
		$target_file = $target_dir . $target_filename;
		$uploadOk = 1;
		$check = getimagesize($_FILES["file"]["tmp_name"]);
		if($check === false) {
			die("檔案錯誤");
		}

		if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
			die("檔案上傳失敗");
		}

		$ret = read_image_gps_location($target_file);
		if($ret == null){
			$ret = ["lat"=>null,"lng"=>null];
		}

		$rimg_id = $this->roadModel->insert_road(
			$target_filename,
			basename($_FILES["file"]["name"]),
			$ret["lat"],
			$ret["lng"]
		);

		redirect(site_url("road/report?img=".$rimg_id));

	}

	public function report(){

		$img_id =  $this->input->get("img");

		$img = $this->roadModel->getImg($img_id);
		if($img == null){
			return show_404();
		}

		$this->load->view('road/report',[
			"img" => $img,
		] );
		session_write_close();
	}

	public function reporting(){
		$point_id = $this->input->post("img_id");

		$point = $this->roadModel->getImg($point_id);
		if($point == null){
			return show_404();
		}	

		$fields= ["name","location","contact","comment","email"];

		$data = ["status" => 0];
		foreach($fields as $key){
			$data[$key] = $this->input->post($key);
		}

		$data["lat"] = $point->lat;
		$data["lng"] = $point->lng;

		$report_id = $this->roadModel->insert_report($data);
		$this->roadModel->update_img($point->id,$report_id);

		redirect("road/reported/".$report_id);
		session_write_close();
	}

	public function reported(){
		$this->load->view('road/reported');
		session_write_close();

	}
		

}
