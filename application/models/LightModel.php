<?php

class LightModel extends CI_Model {
  var $_table = "streetlights";
  var $_table_light_report = "light_report";
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  public function insert($light){
    // ["鹿東", "後寮", "鹿草", "豊稠", "西井", "重寮", "施家", "下潭", "光潭", "碧潭", "竹山", "松竹", "三角", "後堀", "下麻"]
    if($this->check_light_exist((object)($light))){
      return false;
    }

    if($this->check_light_name_exist((object)($light))){
      echo "light ".$light["name"]." not exist and name exist <br />";
    }else{
      echo "light ".$light["name"]." not exist <br />";
    }
    // return true;
    // die(var_dump($light));
    $this->db->insert($this->_table,$light);
  }

  public function check_light_name_exist($light){
    $this->db->select("count(*) as cnt");

    if(is_array($light->lat)){
      die(var_dump($light));
    }
    $this->db->where("name","".$light->name);
    $q = $this->db->get($this->_table);

    return array_first_item($q->result())->cnt != 0;
  }

  public function check_light_exist($light){
    $this->db->select("count(*) as cnt");

    if(is_array($light->lat)){
      die(var_dump($light));
    }
    $this->db->where("lat","".$light->lat);
    $this->db->where("lng","".$light->lng);
    $q = $this->db->get($this->_table);

    return array_first_item($q->result())->cnt != 0;
  }

  public function get_all(){
    return $this->db->get($this->_table)->result();
  }


  public function get_all_for_map(){

    $this->db->select("l.status,l.lat,l.lng,l.id,l.name,t.city,t.name as town_name,l.town_id,
      (select count(*) from light_report where status = 0 and light_id = l.id ) as reporting_count ");
    $this->db->join("town t","l.town_id = t.id");

    $q = $this->db->get($this->_table." l");

    return $q->result();
  }

  public function get_all_special_point_status(){
    $this->db->select("l.status,l.id,
    (select count(*) from light_report where status = 0 and light_id = l.id ) as reporting_count");
    $this->db->where("status <>","0");
    $this->db->or_where("(select count(*) from light_report where status = 0 and light_id = l.id ) > 0 ",null,false);
    $q = $this->db->get($this->_table." l");

    return $q->result();
  }

  public function get($id){

    $this->db->select("l.lat,l.lng,l.height,l.id,l.name,t.city,t.name as town_name,l.town_id");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("l.id",$id);

    return array_first_item($this->db->get($this->_table." l")->result());
  }


  public function get_city($id){
    $this->db->select("l.*,t.city");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("l.id",$id);
    return array_first_item($this->db->get($this->_table." l")->result());
  }

  public function get_repair_light_by_city($city){
    $this->db->select("l.status,l.lat,l.lng,l.height,l.id,l.name,t.city,t.name as town_name,l.town_id,l.updated_at");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where_in("status",["1","2"]);
    $this->db->order_by("status","asc");
    return ($this->db->get($this->_table." l")->result());
  }

  public function get_repair_light_by_ids_city($city,$ids){

    $this->db->select("l.status,l.lat,l.lng,l.height,l.id,l.name,t.city,t.name as town_name,l.town_id,l.updated_at");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where_in("l.id",$ids);
    $this->db->where("status","1");
    return ($this->db->get($this->_table." l")->result());
  }

  public function fix_light_by_ids($city,$ids){

    $this->db->set("status","0");
    $this->db->where_in("id",$ids);
    $q = $this->db->get($this->_table);

    $lights = $q->result();

    $this->db->set("status","0");
    $this->db->where_in("id",$ids);
    $this->db->where_in("status",["1","2"]);
    $this->db->update($this->_table);

    $this->db->set("status",3);
    $this->db->set("updated_at","now() at time zone 'utc'",false);
    $this->db->where("status","1");
    $this->db->where_in("light_id",$ids);
    $this->db->update("light_report");

    foreach($lights as $light){
      $this->db->insert("light_log",["light_id"=>$light->id,"text"=>"修好了"]);
    }

  }

  public function insert_report($data){
    $this->db->insert($this->_table_light_report,$data);
    $id = $this->db->insert_id();
    $this->db->insert("light_log",["light_id"=> $id ,"text"=>"被回報為壞掉"]);
    return ;
  }

  public function get_city_counts($city){
    $this->db->select("l.status,count(l.*)");

    $this->db->join("town t","l.town_id = t.id");
    $this->db->group_by("l.status");
    $this->db->where("t.city",$city);
    return $this->db->get($this->_table." l")->result();

  }


  public function get_city_reports($city){
    $this->db->select("l.name as light_name,l.status as light_status,r.*");
    
    $this->db->join($this->_table." l"," l.id = r.light_id");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where("r.created_at > (current_date - interval '90 days') ");    
    $this->db->order_by("r.created_at desc");
    $q = $this->db->get($this->_table_light_report." r");


    return $q->result();
  }

  public function get_unhandled_city_reports($city){
    $this->db->select("l.name as light_name,l.status as light_status,r.*");
    
    $this->db->join($this->_table." l"," l.id = r.light_id");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("t.city",$city);
    $this->db->where("r.status",0);
    $this->db->order_by("r.created_at desc");
    $q = $this->db->get($this->_table_light_report." r");


    return $q->result();
  }


  public function set_report_status($report_id,$status){
    $this->db->where("id",$report_id);
    $q = array_first_item($this->db->get($this->_table_light_report)->result());

    if($q == null){
      return null;
    }


    if($status == "1" || $status == "2"){

      $this->db->set("status",1);
      $this->db->set("updated_at","now()",false);
      $this->db->where("status",0);
      $this->db->where("light_id",$q->light_id);
      $this->db->update($this->_table_light_report);

      $this->db->set("status",intval($status));
      $this->db->set("updated_at","now()",false);
      $this->db->where("id",$q->light_id);
      $this->db->update($this->_table);
      
      $this->db->insert("light_log",["light_id"=>$q->light_id,"text"=>"已確認報修(".($status=="1" ?"公所廠商":"縣府廠商").")"]);

    }else if($status == "0"){
      $this->db->set("status",2);
      $this->db->set("updated_at","now()",false);
      $this->db->where("status",0);
      $this->db->where("light_id",$q->light_id);
      $this->db->update($this->_table_light_report);

      $this->db->insert("light_log",["light_id"=>$q->light_id,"text"=>"確認為非報修"]);
    }

  }

  public function get_last_report_update_time(){
    $this->db->select("max(updated_at) as max_time");
    $q = $this->db->get($this->_table_light_report);
    return array_first_item($q->result())->max_time;
  }

  public function change_light_loc($id,$lat,$lng){
    $point = $this->get($id);

    if($point == null){
      return false;
    }

    $this->db->insert("light_change_log",Array(
      "light_id" => $id,
      "lat"=>$lat,
      "lng"=>$lng,
      "old_lat"=> $point->lat,
      "old_lng"=> $point->lng,
    ));

    $this->db->set(Array("lat"=>$lat,"lng"=>$lng));

    $this->db->where("id",$id);
    $this->db->update($this->_table);


    return true;

  }


  public function get_all_towns(){
    $this->db->select("name,city");

    $q = $this->db->get("town");
    return $q->result();
  }

  public function get_recent_report(){
    $this->db->select("l.name as light_name,l.status as light_status,r.*,t.city");
    
    $this->db->join($this->_table." l"," l.id = r.light_id");
    $this->db->join("town t","l.town_id = t.id");
    $this->db->where("r.updated_at > (current_date - interval '30' day)",null,false);
    $this->db->or_where("r.status",0);

    $this->db->order_by("r.created_at desc");

    $q = $this->db->get($this->_table_light_report." r");
    return $q->result();

  }

  
}