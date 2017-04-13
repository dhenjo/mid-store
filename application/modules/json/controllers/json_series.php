<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_series extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  function tour_information_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,(SELECT CONCAT(B.id_product_tour,'|',B.title,'|',B.kode,'|',B.days,'|',B.night) FROM product_tour AS B WHERE B.id_product_tour = A.id_product_tour) AS tour"
        . " FROM product_tour_information AS A"
        . " WHERE A.kode = '{$pst['code']}'");
      if($data){
        $kirim = array(
          'status'  => 2,
          'book'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_series_schedule_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $status = "";
      if($pst['status']){
        $status = " AND A.status IN {$pst['status']}";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " FROM product_tour_information AS A"
        . " WHERE A.id_product_tour = (SELECT B.id_product_tour FROM product_tour AS B WHERE B.kode = '{$pst['code']}')"
        . " {$status}"
        . " ORDER BY A.start_date DESC LIMIT {$pst['start']}, {$pst['max']}");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_series_get_book_list(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,(SELECT CONCAT(C.email) FROM users_channel AS C WHERE C.id_users = A.id_users) AS users"
        . " FROM product_tour_book AS A"
        . " WHERE A.id_product_tour_information = (SELECT B.id_product_tour_information FROM product_tour_information AS B WHERE B.kode = '{$pst['code']}')"
        . "");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_series_itin_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.*"
        . " FROM product_tour AS A"
        . " WHERE A.kode = '{$pst['code']}'");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_information_clone(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.*"
        . " FROM product_tour_information AS A"
        . " WHERE A.kode = '{$pst['code']}'");
      if($data){
        $this->olah_tour_code_information($kode_info);
        $post = array(
          "id_product_tour"                             => $data[0]->id_product_tour,
          "id_product_tour_master_discount"             => $data[0]->id_product_tour_master_discount,
          "kode"                                        => $kode_info,
          "keberangkatan"                               => $data[0]->keberangkatan,
          "tampil"                                      => $data[0]->tampil,
          "kode_ps"                                     => "",
          "start_date"                                  => $data[0]->start_date,
          "end_date"                                    => $data[0]->end_date,
          "start_time"                                  => $data[0]->start_time,
          "end_time"                                    => $data[0]->end_time,
          "available_seat"                              => $data[0]->available_seat,
          "seat_update"                                 => 0,
          "pax_book"                                    => 0,
          "adult_triple_twin"                           => $data[0]->adult_triple_twin,
          "child_twin_bed"                              => $data[0]->child_twin_bed,
          "child_extra_bed"                             => $data[0]->child_extra_bed,
          "child_no_bed"                                => $data[0]->child_no_bed,
          "sgl_supp"                                    => $data[0]->sgl_supp,
          "airport_tax"                                 => $data[0]->airport_tax,
          "visa"                                        => $data[0]->visa,
          "less_ticket"                                 => $data[0]->less_ticket,
          "less_ticket_adl"                             => $data[0]->less_ticket_adl,
          "less_ticket_chl"                             => $data[0]->less_ticket_chl,
          "dp"                                          => $data[0]->dp,
          "stnb_dp"                                     => $data[0]->stnb_dp,
          "discount_tetap"                              => $data[0]->discount_tetap,
          "stnb_discount_tetap"                         => $data[0]->stnb_discount_tetap,
          "discount_tambahan"                           => $data[0]->discount_tambahan,
          "stnb_discount_tambahan"                      => $data[0]->stnb_discount_tambahan,
          "days"                                        => $data[0]->days,
          "flt"                                         => $data[0]->flt,
          "sts"                                         => $data[0]->sts,
          "in"                                          => $data[0]->in,
          "out"                                         => $data[0]->out,
          "status"                                      => $data[0]->status,
          "note"                                        => $data[0]->note,
          "at_airport"                                  => $data[0]->at_airport,
          "remarks"                                     => $data[0]->remarks,
          "umum"                                        => $data[0]->umum,
          "create_by_users"                             => $pst['id'],
          "create_date"                                 => date("Y-m-d H:i:s"),
        );
        
        if($pst['relasi'])
          $post['id_relasi'] = $data[0]->id_product_tour_information;
        
        $id_product_tour_information = $this->global_models->insert("product_tour_information", $post);
        $kirim = array(
          'status'  => 2,
          'code'    => $kode_info,
          'book'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_information_update(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $post = array(
        "keberangkatan"                               => $data[0]->keberangkatan,
        "kode_ps"                                     => "",
        "start_date"                                  => $data[0]->start_date,
        "end_date"                                    => $data[0]->end_date,
        "start_time"                                  => $data[0]->start_time,
        "end_time"                                    => $data[0]->end_time,
        "available_seat"                              => $data[0]->available_seat,
        "adult_triple_twin"                           => $data[0]->adult_triple_twin,
        "child_twin_bed"                              => $data[0]->child_twin_bed,
        "child_extra_bed"                             => $data[0]->child_extra_bed,
        "child_no_bed"                                => $data[0]->child_no_bed,
        "sgl_supp"                                    => $data[0]->sgl_supp,
        "airport_tax"                                 => $data[0]->airport_tax,
        "visa"                                        => $data[0]->visa,
        "less_ticket_adl"                             => $data[0]->less_ticket_adl,
        "less_ticket_chl"                             => $data[0]->less_ticket_chl,
        "flt"                                         => $data[0]->flt,
        "sts"                                         => $data[0]->sts,
        "in"                                          => $data[0]->in,
        "out"                                         => $data[0]->out,
        "status"                                      => $data[0]->status,
        "at_airport"                                  => $data[0]->at_airport,
        "remarks"                                     => $data[0]->remarks,
        "umum"                                        => $data[0]->umum,
        "update_by_users"                             => $pst['id'],
      );

      $data = $this->global_models->update("product_tour_information", $post);
      if($data){
        $kirim = array(
          'status'  => 2,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function login(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $data = $pst;
      $users = $this->global_models->get("m_users", array("email" => $pst['name']));
      $pass = $this->encrypt->decode($users[0]->pass);
      if($pass == $pst['pass']){
        $data = array(
          'name'            => $users[0]->name,
          'ename'           => substr(md5(date("d")), 0, 5).$this->encrypt->encode($users[0]->name),
          'epass'           => substr(md5(date("d")), -5).$users[0]->pass,
          'email'           => $users[0]->email,
          'id'              => $users[0]->id_users,
          'outlet'          => 0,
//          'privilege'       => $priv->id_user_privilege,
          'id_privilege'    => $users[0]->id_privilege,
//          'dashbord'        => $this->global_models->get_field("m_privilege", "dashbord", array("id_privilege" => $priv->id_privilege)),
//          'id_store'        => $this->global_models->get_field("store_users", "id_store", array("id_users" => $users[0]->id_users)),
//          'id_store_region' => $this->global_models->get_field("store_region_users", "id_store_region", array("id_users" => $users->id_users)),
          'logged_in'       => TRUE
        );
      }
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  private function olah_tour_code_information(&$kode_info){
    $this->load->helper('string');
    $kode_info_data = random_string('alnum', 8);
    $kode_info = strtoupper($kode_info_data);
    $cek = $this->global_models->get_field("product_tour_information", "id_product_tour_information", array("kode" => $kode_info));
    if($cek > 0){
      $this->olah_tour_code_information($kode_info);
    }
    return true;
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */