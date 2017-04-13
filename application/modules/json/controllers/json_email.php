<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_email extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
function request_additional(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
     // $id_product_tour_book = $this->global_models->get_field("product_tour_book", "id_product_tour_book", array("kode" => $pst['code']));
      
	   $sql = "SELECT A.id_product_tour_book,A.id_users"
        . " ,B.id_store,B.title,sub_category"
        . " ,C.start_date,C.end_date"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
       . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.kode ='{$pst['code']}'";
      $data = $this->global_models->get_query($sql);
	  
	  if($data[0]->id_product_tour_book){
        $dt_region = array(1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia", 6 => "China", 7 => "New Zealand" );
            $url = base_url()."inventory/tour-book/book-information/".$pst['code'];
            $nama_store = $this->global_models->get_field("store", "title", array("id_store" => $data[0]->id_store));
            $id_product_tour_email = $this->global_models->get_field("product_tour_email_region", "id_product_tour_email", array("region" => $data[0]->sub_category));
            
            $id_user = $this->global_models->get("product_tour_email_blast", array("id_product_tour_email" => $id_product_tour_email));
            $no = 1;
           foreach ($id_user as $val) {
            $email =  $this->global_models->get_field("m_users", "email", array("id_users" => $val->id_users));
              if($no > 1){
                $email_user .= ",".$email;
              }else{
                $email_user .= $email;
              }
              $no++;
              
            }
            
            $tgl = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
          $data_store = array(
            "nama_store"                => $nama_store,
            "email_user"                => $email_user,
            "nama_tour"                 => $data[0]->title,
            "region"                    => $dt_region[$data[0]->sub_category],
            "tanggal_keberangkatan"     => $tgl,
            "url"                       => $url,
           
           
          );
          
          $kirim = array(
            'status'  => 2,
            'store'   => $data_store,
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
  
  function request_discount(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
          $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
          $nama_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store)); 
          $data_user = $this->global_models->get("store_commited", array("id_store" => $id_store));
           foreach ($data_user as $val) {
             $email = $this->global_models->get_field("users_channel", "email", array("id_users" => $val->id_users));
             $dname_user = $this->global_models->get_field("users_channel", "name", array("id_users" => $val->id_users));
             $name_user .= $dname_user.",";
             $email_user .= $email.",";
            }
            
             $store = array(
              'name_user' => $name_user,
              'email_user'  => $email_user,
              'data_user'   => $data_user,
               'name_store' => $nama_store
          );
            
            
          $kirim = array(
            'status'  => 2,
            'store'   => $store,
            'note'    => ""
          );
       
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */