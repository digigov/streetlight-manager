<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Light extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model("lightModel");
	}

	public function index(){
		$this->map();
	}

	public function map()
	{
		$this->load->view('light/map',[
			// "points" => $js_points,
			"last_report_update_time" => $this->lightModel->get_last_report_update_time()
		]);
		session_write_close();
	}

	public function json_pointers(){
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$js_points = [];

		$points = null;
 		
		if ( ! $points = $this->cache->get('points'))
		{
			$points = $this->lightModel->get_all_for_map();		
				// 過 5 分鐘後會存到快取
			$this->cache->save('points', $points, 60 * 60);
		}

		$all_status = $this->lightModel->get_all_special_point_status();
		$map = [];
		foreach($all_status as $s){
			$map[$s->id] = $s->status;
		}

		foreach($points as $p){
			if(!isset($map[$p->id])){
				$p->status = 0;
			}else{
				$p->status = $map[$p->id];
			}
		}

		foreach ($points as $p){
			$js_points[] = [$p->id,$p->name,$p->lat,$p->lng,$p->city,$p->status,$p->reporting_count];
		}
		
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');

		die(json_encode([
			"isSuccess" => true,
			"data"=>$js_points
		]));
		
	}

	public function recent_report(){
		$this->load->view('light/recent_report',[
			"reports" => $this->lightModel->get_recent_report(),
			"last_report_update_time" => $this->lightModel->get_last_report_update_time()
		]);
		session_write_close();
	}

	public function report($point_id){

		$point = $this->lightModel->get($point_id);
		if($point == null){
			return show_404();
		}

		$this->load->view('light/report',
			[
				"point" => $point,
				"points" => [$point]
			] );
		session_write_close();
	}


	public function reporting(){
		$point_id = $this->input->post("point_id");

		$point = $this->lightModel->get($point_id);
		if($point == null){
			return show_404();
		}	

		$fields= ["name","contact","comment","email","led_no"];

		$data = ["light_id" => $point_id,"status" => 0];
		foreach($fields as $key){
			$data[$key] = $this->input->post($key);
		}

		$report_id = $this->lightModel->insert_report($data);

		$record = $this->lightModel->get_city($point_id);

		$this->load->model("accountModel");
		$users = $this->accountModel->get_line_by_city($record->city);

		foreach($users as $u){
			send_message($u->line_access_token,"有新的路燈報修回報，詳細資料：\n".
				"\n報修人：".$data["name"]."\n路燈名稱:".$record->name.
				"\n在 google map 顯示: https://www.google.com.tw/maps?q=".$record->lat.",".$record->lng.
				"\n網址:".site_url("/admin/user")
			);
		}

		redirect("light/reported/".$report_id);
		session_write_close();
	}

	public function reported(){
		$this->load->view('light/reported');
		session_write_close();

	}

}
