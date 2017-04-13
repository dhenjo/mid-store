<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_generate extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  /**
   * TC -> Operation Tour
   */
  
  function total_price(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
	  
	  if($data[0]->id_product_tour_book){
       
         $this->load->model("json/mjson_generate_harga");
         $this->mjson_generate_harga->revert_all_payment($data[0]->id_product_tour_book, $pst['id_users']);
         $this->mjson_generate_harga->recount_payment($data[0]->id_product_tour_book, $pst['id_users']);
		
          $kirim = array(
            'status'  => 2,
            'note'    => "",
            
          );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
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
  function total_price_ppn(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
	  
	  if($data[0]->id_product_tour_book){
       
         $this->load->model("json/mjson_tour");
         $this->mjson_tour->revert_all_payment($data[0]->id_product_tour_book, $pst['id_users']);
         $this->mjson_tour->recount_payment($data[0]->id_product_tour_book, $pst['id_users']);
			$this->mjson_tour->cek_status_book($data[0]->id_product_tour_book, $pst['id_users']);
          $kirim = array(
            'status'  => 2,
            'note'    => "",
            
          );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
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
  
}
