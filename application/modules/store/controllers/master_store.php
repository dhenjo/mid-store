<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_store extends MX_Controller {
    
  function __construct() {
    $this->menu = $this->cek();
  }
  
  function index(){
    $list = $this->global_models->get("store");
    
    $menutable = '
      <li><a href="'.site_url("store/master-store/add-new").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master/store', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "store/master-store",
            'data'        => $list,
            'title'       => lang("Master Store"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master/store');
  }
  
  public function add_new($id_store = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("store", array("id_store" => $id_store));
      
      $this->template->build("master/add-new", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'store/master-store',
              'title'       => lang("Master Store"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "Master Store"  => "store/master-store"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master/add-new");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "sort"            => $pst['sort'],
            "title"           => $pst['title'],
            "kode"            => $pst['kode'],
            "telp"            => $pst['telp'],
            "fax"             => $pst['fax'],
            "master"          => $pst['master'],
            "alamat"          => $pst['alamat'],
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_store = $this->global_models->update("store", array("id_store" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
          "sort"            => $pst['sort'],
          "title"           => $pst['title'],
          "kode"            => $pst['kode'],
          "telp"            => $pst['telp'],
          "fax"             => $pst['fax'],
          "master"          => $pst['master'],
          "alamat"          => $pst['alamat'],
          "create_by_users" => $this->session->userdata("id"),
          "create_date"     => date("Y-m-d")
        );
        
        $id_store = $this->global_models->insert("store", $kirim);
      }
      if($id_store){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("store/master-store");
    }
  }
 
  public function list_counter($id_store){
    if(!$this->input->post(NULL)){
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script>"
          . "$(function() {"
            . "$( '#users' ).autocomplete({"
              . "source: '".site_url("store/ajax/users")."',"
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
            . "$(document).on('click', '#add-row', function(evt){"
              . "$.post('".site_url("store/ajax/add-row")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
          . "});"
        . "</script>";
      $detail = $this->global_models->get_query("SELECT A.*, B.name, B.email"
        . " FROM store_tc AS A"
        . " LEFT JOIN users_channel AS B ON A.id_users = B.id_users"
        . " WHERE A.id_store = '{$id_store}'");
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
                  . "source: '".site_url("store/ajax/users")."',"
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
      $this->template->build("master/list-counter", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'store/master-store',
              'title'       => lang("List TC"),
              'detail'      => count($detail),
              'hasil'       => $hasil,
              'breadcrumb'  => array(
                    "Master Store"  => "store/master-store"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master/list-counter");
    }
    else{
      $pst = $this->input->post(NULL);
      
      $this->global_models->delete("store_tc", array("id_store" => $id_store));
      foreach ($pst['id_users'] as $value) {
        if($value){
          $kirim[] = array(
            "id_store"            => $id_store,
            "id_users"            => $value,
            "create_by_users"     => $this->session->userdata("id"),
            "create_date"         => date("Y-m-d H:i:s"),
          );
        }
      }
      if($this->global_models->insert_batch("store_tc", $kirim)){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("store/master-store");
    }
  }
 
  public function list_commited($id_store){
    if(!$this->input->post(NULL)){
      $css = "<link href='".base_url()."themes/".DEFAULTTHEMES."/css/jQueryUI/jquery-ui-1.10.3.custom.min.css' rel='stylesheet' type='text/css' />";
      $foot = "<script src='".base_url()."themes/".DEFAULTTHEMES."/js/jquery.ui.autocomplete.min.js' type='text/javascript'></script>"
        . "<script>"
          . "$(function() {"
            . "$( '#users' ).autocomplete({"
              . "source: '".site_url("store/ajax/users")."',"
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
            . "$(document).on('click', '#add-row', function(evt){"
              . "$.post('".site_url("store/ajax/add-row")."',{no: $('#nomor').val()},function(data){"
//                . "$('#wadah').insertBefore(data);"
                . "$(data).insertBefore('#wadah');"
                . "var t = ($('#nomor').val() * 1) + 1;"
                . "$('#nomor').val(t);"
              . "});"
            . "});"
          . "});"
        . "</script>";
      $detail = $this->global_models->get_query("SELECT A.*, B.name, B.email"
        . " FROM store_commited AS A"
        . " LEFT JOIN users_channel AS B ON A.id_users = B.id_users"
        . " WHERE A.id_store = '{$id_store}'");
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
                  . "source: '".site_url("store/ajax/users")."',"
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
      $this->template->build("master/list-commited", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'store/master-store',
              'title'       => lang("List Commited"),
              'detail'      => count($detail),
              'hasil'       => $hasil,
              'breadcrumb'  => array(
                    "Master Store"  => "store/master-store"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master/list-commited");
    }
    else{
      $pst = $this->input->post(NULL);
      
      $this->global_models->delete("store_commited", array("id_store" => $id_store));
      foreach ($pst['id_users'] as $value) {
        if($value){
          $kirim[] = array(
            "id_store"            => $id_store,
            "id_users"            => $value,
            "create_by_users"     => $this->session->userdata("id"),
            "create_date"         => date("Y-m-d H:i:s"),
          );
        }
      }
      if($this->global_models->insert_batch("store_commited", $kirim)){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("store/master-store");
    }
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */