<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_inventory extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  function cek_tour_inventory(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
       $invt = $this->global_models->get("inventory",array("id_inventory" =>"{$pst['id_inventory']}","id_users" => "{$pst['id_users']}"));
       
    if($invt[0]->id_inventory){
       $payment = $this->global_models->get("product_tour_book_payment",array("id_inventory" => "{$invt[0]->id_inventory}","pos" => 2));

    if($payment){
     $kirim = array(
       'status'     => 2,
       'payment'    => $payment,
       'note'       => ""
     );
   }
     }else{
           $kirim = array(
          'status'  => 3,
          'note'    => "Tidak ada Akses untuk book tour dari inventory"
        ); 
        }
    }else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function book_tour_inventory_set(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
       $product_tour_book = $this->global_models->get("product_tour_book",array("kode" => "{$pst['code']}"));
//       $id_product_tour_book = $this->global_models->get_field("product_tour_book_payment","id_product_tour_book_payment",array("kode" => "{$pst['code']}")); 
       $post = array(
        "id_product_tour_book"    => $product_tour_book[0]->id_product_tour_book,
        "update_by_users"         => $users[0]->id_users,
        "id_currency"             => 2, 
      );
      $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id_payment']), $post);
      
          $max_product_tour = $this->global_models->get_field("product_tour_book", "MAX(sort)", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information, "sort <" => 100));
          $max_product_tour += 1;
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("sort" => $max_product_tour));
          $customer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
          $max_product_customer = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information, "sort <" => 100));
          foreach($customer AS $cus){
            $max_product_customer++;
            $cetak_max = $max_product_customer;
            if($cus->less_ticket)
              $cetak_max = 0;
            $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $cus->id_product_tour_customer), array("sort" => $cetak_max));
          }
          
      $post = array(
        "flag_book"               => 1,
        "kode"                    => $pst['code'],
        "update_by_users"         => $users[0]->id_users,
      );
      $this->global_models->update("inventory", array("id_inventory" => $pst['id_inventory']), $post);
      $this->load->model("json/mjson_tour");
      $this->mjson_tour->cek_status_book($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
      
    }
  }
  
  
}