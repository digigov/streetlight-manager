<?php

class RoadModel extends CI_Model {
  var $_table = "road_reports";
  var $_table_images = "road_images";
  public function __construct()
  {
    parent::__construct();
  }

  public function insert_road($url,$path,$lat=null,$lng=null){
    $this->db->insert($this->_table_images,[
      "url"=>$url,
      "path"=>$path,
      "lat"=>$lat,
      "lng"=>$lng
    ]);

    return $this->db->insert_id();
  }

  public function insert_report($data){
    $this->db->insert($this->_table,$data);
    return $this->db->insert_id();
  }

  public function getImg($id){
    $this->db->where("id",$id);
    $q = $this->db->get($this->_table_images);
    return array_first_item( $q->result());
    
  }

  public function update_img($img_id,$report_id){
    $this->db->where("id",$img_id);
    $this->db->set("report_id",$report_id);
    $this->db->update($this->_table_images);
    
  }
}