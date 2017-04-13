<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Btc extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  public function payment(){
    $list = $this->global_models->get("tiket_discount");
    $menutable = '
      <li><a href="'.site_url("discount/btc/add-new").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('btc/discount-payment', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "discount/btc/payment",
            'data'        => $list,
            'title'       => lang("antavaya_discount_payment"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc'
          ));
    $this->template
      ->set_layout('datatables')
      ->build('btc/discount-payment');
  }
  
  public function maskapai(){
    $list = $this->global_models->get("tiket_discount_maskapai");
    $menutable = '
      <li><a href="'.site_url("discount/btc/add-new-maskapai").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('btc/discount-maskapai', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "discount/btc/maskapai",
            'data'        => $list,
            'title'       => lang("antavaya_discount_maskapai"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc'
          ));
    $this->template
      ->set_layout('datatables')
      ->build('btc/discount-maskapai');
  }
  
  public function add_new($id_tiket_discount = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("tiket_discount", array("id_tiket_discount" => $id_tiket_discount));
      
      $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />";
    
      $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
        . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>"
              ."<script type='text/javascript'>"
        . "$(function() { "
          . "CKEDITOR.replace('editor2');"
          . "$( '#start_date' ).datetimepicker({ "
            . "dateFormat: 'yy-mm-dd', "
          . "}); "
          . "$( '#end_date' ).datetimepicker({ "
            . "dateFormat: 'yy-mm-dd', "
          . "}); "
        . "}); "
        . "</script> ";
      
      $this->template->build("btc/add-new", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'discount/btc/payment',
              'title'       => lang("antavaya_add_discount_payment"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "antavaya_discount_payment"  => "discount/btc/payment"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("btc/add-new");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "type"            => $pst['type'],
            "channel"         => $pst['channel'],
            "title"           => $pst['title'],
            "nilai"           => $pst['nilai'],
            "mulai"           => $pst['periodestart'],
            "akhir"           => $pst['periodeend'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "update_by_users" => $this->session->userdata("id"),
        );
        
        $id_tiket_discount = $this->global_models->update("tiket_discount", array("id_tiket_discount" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "type"            => $pst['type'],
            "channel"         => $pst['channel'],
            "title"           => $pst['title'],
            "nilai"           => $pst['nilai'],
            "mulai"           => $pst['periodestart'],
            "akhir"           => $pst['periodeend'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        
        $id_tiket_discount = $this->global_models->insert("tiket_discount", $kirim);
      }
      if($id_tiket_discount){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("discount/btc/payment");
    }
  }
 
  public function add_new_maskapai($id_tiket_discount_maskapai = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("tiket_discount_maskapai", array("id_tiket_discount_maskapai" => $id_tiket_discount_maskapai));
      
      $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />";
    
      $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
        . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>"
              ."<script type='text/javascript'>"
        . "$(function() { "
          . "CKEDITOR.replace('editor2');"
          . "$( '#start_date' ).datetimepicker({ "
            . "dateFormat: 'yy-mm-dd', "
          . "}); "
          . "$( '#end_date' ).datetimepicker({ "
            . "dateFormat: 'yy-mm-dd', "
          . "}); "
        . "}); "
        . "</script> ";
      
      $this->template->build("btc/add-new-maskapai", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'discount/btc/maskapai',
              'title'       => lang("antavaya_add_discount_maskapai"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "antavaya_discount_maskapai"  => "discount/btc/maskapai"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("btc/add-new-maskapai");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "type"            => $pst['type'],
            "maskapai"        => $pst['maskapai'],
            "title"           => $pst['title'],
            "nilai"           => $pst['nilai'],
            "mulai"           => $pst['periodestart'],
            "akhir"           => $pst['periodeend'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "update_by_users" => $this->session->userdata("id"),
        );
        
        $id_tiket_discount_maskapai = $this->global_models->update("tiket_discount_maskapai", array("id_tiket_discount_maskapai" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "type"            => $pst['type'],
            "maskapai"        => $pst['maskapai'],
            "title"           => $pst['title'],
            "nilai"           => $pst['nilai'],
            "mulai"           => $pst['periodestart'],
            "akhir"           => $pst['periodeend'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        
        $id_tiket_discount_maskapai = $this->global_models->insert("tiket_discount_maskapai", $kirim);
      }
      if($id_tiket_discount_maskapai){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("discount/btc/maskapai");
    }
  }
  
  function destination(){
    $list = $this->global_models->get("tiket_discount_destination");
    $menutable = '
      <li><a href="'.site_url("discount/btc/add-new-destination").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('btc/discount-destination', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "discount/btc/destination",
            'data'        => $list,
            'title'       => lang("antavaya_discount_destination"),
            'menutable'   => $menutable,
            'tableboxy'   => 'tableboxydesc'
          ));
    $this->template
      ->set_layout('datatables')
      ->build('btc/discount-destination');
  }
  
  public function add_new_destination($id_tiket_discount_destination = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("tiket_discount_destination", array("id_tiket_discount_destination" => $id_tiket_discount_destination));
      
      $css = ""
      . "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jquery-ui-timepicker-addon.min.css' rel='stylesheet' type='text/css' />";
    
      $foot .= "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery-ui-timepicker-addon.js' type='text/javascript'></script>"
        . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>"
              ."<script type='text/javascript'>"
        . "$(function() { "
          . "CKEDITOR.replace('editor2');"
          . "$( '#start_date' ).datetimepicker({ "
            . "dateFormat: 'yy-mm-dd', "
          . "}); "
          . "$( '#end_date' ).datetimepicker({ "
            . "dateFormat: 'yy-mm-dd', "
          . "}); "
        . "}); "
        . "</script> ";
      
      $this->template->build("btc/add-new-destination", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'discount/btc/destination',
              'title'       => lang("antavaya_add_discount_destination"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "antavaya_discount_destination"  => "discount/btc/destination"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("btc/add-new-destination");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "type"            => $pst['type'],
            "maskapai"        => $pst['maskapai'],
            "destinationcode" => $pst['destinationcode'],
            "title"           => $pst['title'],
            "nilai"           => $pst['nilai'],
            "mulai"           => $pst['periodestart'],
            "akhir"           => $pst['periodeend'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "update_by_users" => $this->session->userdata("id"),
        );
        
        $id_tiket_discount_destination = $this->global_models->update("tiket_discount_destination", array("id_tiket_discount_destination" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "type"            => $pst['type'],
            "maskapai"        => $pst['maskapai'],
            "destinationcode" => $pst['destinationcode'],
            "title"           => $pst['title'],
            "nilai"           => $pst['nilai'],
            "mulai"           => $pst['periodestart'],
            "akhir"           => $pst['periodeend'],
            "note"            => $pst['note'],
            "status"          => $pst['status'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        
        $id_tiket_discount_destination = $this->global_models->insert("tiket_discount_destination", $kirim);
      }
      if($id_tiket_discount_destination){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("discount/btc/destination");
    }
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */