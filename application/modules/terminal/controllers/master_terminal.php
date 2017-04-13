<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_terminal extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  
  function nation(){
    $this->global_models->get_connect("terminal");
    $list = $this->global_models->get("master_hotel_nation");
    $this->global_models->get_connect("default");
    
    $menutable = '
      <li><a href="'.site_url("terminal/master-terminal/add-new-nation").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master-terminal/nation', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "terminal/master-terminal/nation",
            'data'        => $list,
            'title'       => lang("antavaya_hotel_nation"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master-terminal/nation');
  }
  
  public function add_new_nation($id_master_hotel_nation = 0){
    if(!$this->input->post(NULL)){
      $this->global_models->get_connect("terminal");
      $detail = $this->global_models->get("master_hotel_nation", array("id_master_hotel_nation" => $id_master_hotel_nation));
      $this->global_models->get_connect("default");
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script> "
        . "<script type='text/javascript'> "
        . "$(function() { "
          . "CKEDITOR.replace('editor2'); "
        . "});"
        . "</script>";
      
      $this->template->build("master-terminal/add-new-nation", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'terminal/master-terminal/nation',
              'title'       => lang("antavaya_add_hotel_nation"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "antavaya_hotel_nation"  => "terminal/master-terminal/nation"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master-terminal/add-new-nation");
    }
    else{
      $pst = $this->input->post(NULL);
      $this->global_models->get_connect("terminal");
      if($pst['id_detail']){
        $kirim = array(
            "title"           => $pst['title'],
            "kode"            => $pst['kode'],
            "note"            => $pst['note'],
            "update_by_users" => $this->session->userdata("id"),
        );
        
        $id_master_hotel_nation = $this->global_models->update("master_hotel_nation", array("id_master_hotel_nation" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"           => $pst['title'],
            "kode"            => $pst['kode'],
            "note"            => $pst['note'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        $id_master_hotel_nation = $this->global_models->insert("master_hotel_nation", $kirim);
      }
      $this->global_models->get_connect("default");
      if($id_master_hotel_nation){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("terminal/master-terminal/nation");
    }
  }
 
  function city(){
    $this->global_models->get_connect("terminal");
    $list = $this->global_models->get_query("SELECT A.*, B.title AS nation"
      . " FROM master_hotel_city AS A"
      . " LEFT JOIN master_hotel_nation AS B ON A.id_master_hotel_nation = B.id_master_hotel_nation");
    $this->global_models->get_connect("default");
    
    $menutable = '
      <li><a href="'.site_url("terminal/master-terminal/add-new-city").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master-terminal/city', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "terminal/master-terminal/city",
            'data'        => $list,
            'title'       => lang("antavaya_hotel_city"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master-terminal/city');
  }
  
  public function add_new_city($id_master_hotel_city = 0){
    if(!$this->input->post(NULL)){
      $this->global_models->get_connect("terminal");
      $detail = $this->global_models->get("master_hotel_city", array("id_master_hotel_nation" => $id_master_hotel_city));
      $this->global_models->get_connect("default");
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>"
        . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script type='text/javascript'> "
        . "$(function() { "
          . "CKEDITOR.replace('editor2');"
          . "$( '#master_hotel_nation' ).autocomplete({"
            . "source: '".site_url("ajax/hotel-nation")."',"
            . "minLength: 1,"
            . "search  : function(){ $(this).addClass('working');},"
            . "open    : function(){ $(this).removeClass('working');},"
            . "select: function( event, ui ) {"
              . "$('#id_master_hotel_nation').val(ui.item.id);"
            . "}"
          . "});"
        . "});"
        . "</script>";
      
      $this->template->build("master-terminal/add-new-city", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'terminal/master-terminal/city',
              'title'       => lang("antavaya_add_hotel_city"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "antavaya_hotel_nation"  => "terminal/master-terminal/city"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master-terminal/add-new-city");
    }
    else{
      $pst = $this->input->post(NULL);
      $this->global_models->get_connect("terminal");
      if($pst['id_detail']){
        $kirim = array(
            "title"                   => $pst['title'],
            "kode"                    => $pst['kode'],
            "id_master_hotel_nation"  => $pst['id_master_hotel_nation'],
            "note"                    => $pst['note'],
            "update_by_users"         => $this->session->userdata("id"),
        );
        
        $id_master_hotel_city = $this->global_models->update("master_hotel_city", array("id_master_hotel_city" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"                   => $pst['title'],
            "kode"                    => $pst['kode'],
            "id_master_hotel_nation"  => $pst['id_master_hotel_nation'],
            "note"                    => $pst['note'],
            "create_by_users"         => $this->session->userdata("id"),
            "create_date"             => date("Y-m-d")
        );
        $id_master_hotel_city = $this->global_models->insert("master_hotel_city", $kirim);
      }
      $this->global_models->get_connect("default");
      if($id_master_hotel_city){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("terminal/master-terminal/city");
    }
  }
 
  function destination(){
    $this->global_models->get_connect("terminal");
    $list = $this->global_models->get_query("SELECT A.*"
      . " FROM master_hotel_destination AS A"
      . "");
    $this->global_models->get_connect("default");
    
    $menutable = '
      <li><a href="'.site_url("terminal/master-terminal/add-new-destination").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master-terminal/destination', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "terminal/master-terminal/destination",
            'data'        => $list,
            'title'       => lang("antavaya_hotel_destination"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master-terminal/destination');
  }
  
  public function add_new_destination($id_master_hotel_destination = 0){
    if(!$this->input->post(NULL)){
      $this->global_models->get_connect("terminal");
      $detail = $this->global_models->get("master_hotel_city", array("id_master_hotel_nation" => $id_master_hotel_city));
      $this->global_models->get_connect("default");
      
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/plugins/ckeditor/ckeditor.js' type='text/javascript'></script>"
        . "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script type='text/javascript'> "
        . "$(function() { "
          . "CKEDITOR.replace('editor2');"
          . "$( '#master_hotel_nation' ).autocomplete({"
            . "source: '".site_url("ajax/hotel-nation")."',"
            . "minLength: 1,"
            . "search  : function(){ $(this).addClass('working');},"
            . "open    : function(){ $(this).removeClass('working');},"
            . "select: function( event, ui ) {"
              . "$('#id_master_hotel_nation').val(ui.item.id);"
            . "}"
          . "});"
        . "});"
        . "</script>";
      
      $this->template->build("master-terminal/add-new-city", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'terminal/master-terminal/city',
              'title'       => lang("antavaya_add_hotel_city"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "antavaya_hotel_nation"  => "terminal/master-terminal/city"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master-terminal/add-new-city");
    }
    else{
      $pst = $this->input->post(NULL);
      $this->global_models->get_connect("terminal");
      if($pst['id_detail']){
        $kirim = array(
            "title"                   => $pst['title'],
            "kode"                    => $pst['kode'],
            "id_master_hotel_nation"  => $pst['id_master_hotel_nation'],
            "note"                    => $pst['note'],
            "update_by_users"         => $this->session->userdata("id"),
        );
        
        $id_master_hotel_city = $this->global_models->update("master_hotel_city", array("id_master_hotel_city" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"                   => $pst['title'],
            "kode"                    => $pst['kode'],
            "id_master_hotel_nation"  => $pst['id_master_hotel_nation'],
            "note"                    => $pst['note'],
            "create_by_users"         => $this->session->userdata("id"),
            "create_date"             => date("Y-m-d")
        );
        $id_master_hotel_city = $this->global_models->insert("master_hotel_city", $kirim);
      }
      $this->global_models->get_connect("default");
      if($id_master_hotel_city){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("terminal/master-terminal/city");
    }
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */