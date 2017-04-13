<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flight_tools extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
   
  }
  
  public function index(){
   
    $pst = $this->input->post(NULL, TRUE);
    $detail = array();
    $data_debug = "";
    if($pst){
      $detail = array("pnr_code" => $pst['pnr_code'], "payment_type" => $pst['payment_type']);
      
      $kirim = array(
          'id_user'         =>  $this->session->userdata('id'),
          'tanggal'         =>  date("Y-m-d H:i:s"),
          'code_book'       =>  $pst['pnr_code'],
          'type'            =>  $pst['payment_type'],
          'harga_bayar'     =>  $pst['harga_bayar'],
          "create_by_users" => $this->session->userdata('id'),
          "create_date"     =>  date("Y-m-d H:i:s"),
          "update_by_users" => $this->session->userdata('id')
      );
      $this->global_models->insert("report_log_issued", $kirim);
      
//      $pr =$this->global_models->get("tiket_book", array("book_code" => $pst['pnr_code']));
      $post = array(
       'users'             => USERSSERVER, 
       'password'          => PASSSERVER,
       "book_code"         => $pst['pnr_code'],
       'harga_bayar'       => $pst['harga_bayar'],
       'channel'           => $pst['payment_type'],
     );
     $data = $this->curl_mentah($post, site_url("json/issued"));
     $data_array = json_decode($data);
     
     if($data_array->status == 2){
       $this->session->set_flashdata('success', 'Issued Berhasil');
      }
      else{
        $this->session->set_flashdata('notice', 'Issued Gagal, Coba Lagi');
      }

     redurect("report/flight/btc-book");
    }
    $this->template->build('flight_tools', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => 'report/flight-tools',
            'title'   => 'Flight Tools',
            'detail'  => $detail,
            'data_debug'  => $data_debug,
            'breadcrumb'  => array(
                  "flight_tools"  => "flight_tools"
              ),
          ));
    $this->template
      ->set_layout('default')
      ->build('flight_tools');
    
  }
  
  function refund(){
    $list = $this->global_models->get_query("SELECT A.tanggal"
      . " ,B.name"
      . " ,C.issued_no"
      . " FROM tiket_refund AS A"
      . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
      . " LEFT JOIN tiket_issued AS C ON A.id_tiket_issued = C.id_tiket_issued");
    $this->template->build('refund', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "report/flight-tools/refund",
            'data'        => $list,
            'title'       => lang("report_refund"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc'
          ));
    $this->template
      ->set_layout('datatables')
      ->build('refund');
  }
  
  function do_refund($id_tiket_issued){
    $pst = $this->input->post();
    if($pst){
      $kirim = array(
        "id_users"        => $this->session->userdata("id"),
        "tanggal"         => date("Y-m-d H:i:s"),
        "id_tiket_issued" => $pst['id_tiket_issued'],
        "create_by_users" => $this->session->userdata("id"),
        "create_date"     => date("Y-m-d H:i:s"),
      );
      $id_tiket_refund = $this->global_models->insert("tiket_refund", $kirim);
      if($id_tiket_refund){
        $this->global_models->update("tiket_issued", array("id_tiket_issued" => $pst['id_tiket_issued']), array("status" => 8));
        $id_tiket_book = $this->global_models->get_field("tiket_issued", "id_tiket_book", array("id_tiket_issued" => $pst['id_tiket_issued']));
        $this->global_models->update("tiket_book", array("id_tiket_book" => $id_tiket_book), array("status" => 8));
        $this->session->set_flashdata('success', 'Proses Berhasil');
      }
      else{
        $this->session->set_flashdata('notice', 'Issued Gagal, Coba Lagi');
      }
      redirect("report/flight-tools/refund");
    }
    $tiket_issued = $this->global_models->get_query("SELECT A.*"
      . " ,B.first_name, B.last_name, B.email, B.telphone"
      . " FROM tiket_issued AS A"
      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
      . " WHERE A.id_tiket_issued = '{$id_tiket_issued}'");
//    $this->debug($tiket_issued, true);
    $this->template->build('do-refund', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => 'report/flight-tools/refund',
            'title'       => 'report_do_refund',
            'detail'      => $tiket_issued,
            'breadcrumb'  => array(
                  "report_refund"  => "report/flight-tools/refund"
              ),
          ));
    $this->template
      ->set_layout('default')
      ->build('do-refund');
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */