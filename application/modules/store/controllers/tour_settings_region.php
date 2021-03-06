<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tour_settings_region extends MX_Controller {
    
  function __construct() {
    $this->menu = $this->cek();
  }
  
  function index(){
//    $list = $this->global_models->get("tour_settings_region");
    $list = $this->global_models->get_query("SELECT A.id_tour_settings_region,A.id_store,A.title,A.region,B.name,B.email,A.id_users"
        . " FROM tour_settings_region AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users");
    $dropdown = $this->global_models->get_dropdown("store", "id_store", "title", FALSE);
    $dt_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
    $menutable = '
      <li><a href="'.site_url("store/tour-settings-region/add-new").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('email/list', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "store/tour-settings-region",
            'data'        => $list,
            'dropdown'    => $dropdown,
            'region'      => $dt_region,
            'title'       => lang("Tour Settings Region"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('email/list');
  }
  
  public function add_new($id_tour_settings_region = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get_query("SELECT A.id_tour_settings_region,A.id_store,A.title,A.region,B.name,B.email,A.id_users"
        . " FROM tour_settings_region AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " WHERE A.id_tour_settings_region = '{$id_tour_settings_region}'");
        
//      $detail = $this->global_models->get("tour_settings_region", array("id_tour_settings_region" => $id_tour_settings_region));
      $dropdown = $this->global_models->get_dropdown("store", "id_store", "title", FALSE);     
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script>"
          . "$(function() {"
            . "$( '#users' ).autocomplete({"
              . "source: '".site_url("store/ajax/mid_users")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_users').val(ui.item.id);"
              . "}"
            . "});"
            . "$(document).on('click', '.delete', function(evt){"
              . "var didelete = $(this).attr('isi');"
              . "$('#'+didelete).remove();"
            . "});"
              . "$(document).on('click', '.delete2', function(evt){"
              . "var didelete = $(this).attr('isi2');"
              . "$('#'+didelete).remove();"
            . "});"
            . "$(document).on('click', '#add-row', function(evt){"
              . "$.post('".site_url("store/ajax/add-row-mid-user")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
              . "$(document).on('click', '#add-row2', function(evt){"
              . "$.post('".site_url("store/ajax/region")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah2');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
          . "});"
        . "</script>";
      
      $this->template->build("email/add-new", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'store/blast-email',
              'title'       => lang("Blast Email"),
              'detail'      => $detail,
              'dropdown'    => $dropdown,
              'breadcrumb'  => array(
                    "Blast Email"  => "store/blast-email"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("email/add-new");
    }
    else{
      $pst = $this->input->post(NULL);
     
      if($pst['id_detail']){
        $kirim = array(
            "title"           => $pst['title'],
            "id_store"        => $pst['id_store'],
            "region"          => $pst['region'],
            "id_users"          => $pst['id_users'],
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_store = $this->global_models->update("tour_settings_region", array("id_tour_settings_region" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
             "title"           => $pst['title'],
            "id_store"        => $pst['id_store'],
            "region"          => $pst['region'],
            "id_users"          => $pst['id_users'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d H:i:s")
        );
        
        $id_store = $this->global_models->insert("tour_settings_region", $kirim);
      }
      if($id_store){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("store/tour-settings-region");
    }
  }
 
  public function setting($id_product_tour_blast_email){
    if(!$this->input->post(NULL)){
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script>"
          . "$(function() {"
            . "$( '#users' ).autocomplete({"
              . "source: '".site_url("store/ajax/mid_users")."',"
              . "minLength: 1,"
              . "search  : function(){ $(this).addClass('working');},"
              . "open    : function(){ $(this).removeClass('working');},"
              . "select: function( event, ui ) {"
                . "$('#id_users').val(ui.item.id);"
              . "}"
            . "});"
            . "$(document).on('click', '.delete', function(evt){"
              . "var didelete = $(this).attr('isi');"
              . "$('#'+didelete).remove();"
            . "});"
              . "$(document).on('click', '.delete2', function(evt){"
              . "var didelete = $(this).attr('isi2');"
              . "$('#'+didelete).remove();"
            . "});"
            . "$(document).on('click', '#add-row', function(evt){"
              . "$.post('".site_url("store/ajax/add-row-mid-user")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
              . "$(document).on('click', '#add-row2', function(evt){"
              . "$.post('".site_url("store/ajax/region")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah2');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
          . "});"
        . "</script>";
      $detail = $this->global_models->get_query("SELECT B.name,B.email,A.id_users"
        . " FROM product_tour_blast_email_user AS A"
        . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
        . " WHERE A.id_product_tour_blast_email = '{$id_product_tour_blast_email}'");
      foreach ($detail AS $key => $det){
        $hasil .= "<div class='input-group margin' id='users-box{$key}'>"
            . "<input type='text' class='form-control' value='{$det->name} <{$det->email}>' id='users{$key}' name='users[]'>"
            . "<input type='text' class='form-control' value='{$det->id_users}' id='id_users{$key}' name='id_users[]' style='display: none'>"
            . "<span class='input-group-btn'>"
              . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='users-box{$key}'>"
                . "<i class='fa fa-fw fa-times'></i>"
              . "</a>"
            . "</span> "
          . "</div>";
             
        $foot .= "<script>"
              . "$(function() {"
                . "$( '#users{$key}' ).autocomplete({"
                  . "source: '".site_url("store/ajax/mid-users")."',"
                  . "minLength: 1,"
                  . "search  : function(){ $(this).addClass('working');},"
                  . "open    : function(){ $(this).removeClass('working');},"
                  . "select: function( event, ui ) {"
                    . "$('#id_users{$key}').val(ui.item.id);"
                  . "}"
                . "});"
              . "});"
          . "</script>";
      }
      
       $detail2 = $this->global_models->get_query("SELECT A.region"
        . " FROM product_tour_blast_email_region AS A"
        . " WHERE A.id_product_tour_blast_email = '{$id_product_tour_blast_email}'");
       foreach ($detail2 AS $key => $det2){
       
            $hasil2 .= "<div class='input-group margin' id='users-box2{$key}'>";
            $hasil2 .= $this->form_eksternal->form_dropdown('region[]', array("1" =>"eropa","2" =>"middle east africa","3" =>"america","4" => "australia", "5" => "asia", "6" =>"china","7" =>"new zeland" ),array($det2->region), 'class="form-control input-sm"');
            $hasil2 .= "<span class='input-group-btn'>"
              . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete2' isi2='users-box2{$key}'>"
                . "<i class='fa fa-fw fa-times'></i>"
              . "</a>"
            . "</span> "
          . "</div>";
       
      }
      $this->template->build("email/setting", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'store/blast-email',
              'title'       => lang("Setting Blast Email"),
              'detail'      => count($detail),
              'hasil'       => $hasil,
              'hasil2'       => $hasil2,
              'breadcrumb'  => array(
                    "Blast Email"  => "store/blast-email"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("email/setting");
    }
    else{
      $pst = $this->input->post(NULL);
//      print_r($pst); die;
      $this->global_models->delete("product_tour_blast_email_user", array("id_product_tour_blast_email" => $id_product_tour_blast_email));
      foreach ($pst['id_users'] as $value) {
        if($value){
          $kirim = array(
            "id_product_tour_blast_email"       => $id_product_tour_blast_email,
            "id_users"                          => $value,
            "create_by_users"                   => $this->session->userdata("id"),
            "create_date"                       => date("Y-m-d H:i:s"),
          );
          $dt_user = $this->global_models->insert("product_tour_blast_email_user", $kirim);
        }
      }
      
       $this->global_models->delete("product_tour_blast_email_region", array("id_product_tour_blast_email" => $id_product_tour_blast_email));
      foreach ($pst['region'] as $value2) {
        if($value2){
          $kirim = array(
            "id_product_tour_blast_email"            => $id_product_tour_blast_email,
            "region"                                => $value2,
            "create_by_users"                        => $this->session->userdata("id"),
            "create_date"                               => date("Y-m-d H:i:s"),
          );
         $dt_region = $this->global_models->insert("product_tour_blast_email_region", $kirim);
        }
      }
      if($dt_user !="" OR $dt_region !=""){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("store/blast-email");
    }
  }
 
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */