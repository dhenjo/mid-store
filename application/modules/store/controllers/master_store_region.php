<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_store_region extends MX_Controller {
    
  function __construct() {
    $this->menu = $this->cek();
  }
  
  function delete($id_store_region){
    $this->global_models->delete("store_region", array("id_store_region" => $id_store_region));
     $this->session->set_flashdata('success', 'Data terhapus');
      redirect("store/master-store-region");
  }
  
  function index(){
    $list = $this->global_models->get("store_region");
    
    $menutable = '
      <li><a href="'.site_url("store/master-store-region/add-new").'"><i class="icon-plus"></i> Add New</a></li>
      ';
    $this->template->build('master/store-region/store-region', 
      array(
            'url'         => base_url()."themes/".DEFAULTTHEMES."/",
            'menu'        => "store/master-store-region",
            'data'        => $list,
            'title'       => lang("Master Store Region"),
            'menutable'   => $menutable,
          ));
    $this->template
      ->set_layout('datatables')
      ->build('master/store-region/store-region');
  }
  
  public function add_new($id_store_region = 0){
    if(!$this->input->post(NULL)){
      $detail = $this->global_models->get("store_region", array("id_store_region" => $id_store_region));
      
      $this->template->build("master/store-region/add-new", 
        array(
              'url'         => base_url()."themes/".DEFAULTTHEMES."/",
              'menu'        => 'store/master-store-region',
              'title'       => lang("Master Store Region"),
              'detail'      => $detail,
              'breadcrumb'  => array(
                    "Master Store Region"  => "store/master-store-region"
                ),
              'css'         => $css,
              'foot'        => $foot
            ));
      $this->template
        ->set_layout('form')
        ->build("master/store-region/add-new");
    }
    else{
      $pst = $this->input->post(NULL);
      
      if($pst['id_detail']){
        $kirim = array(
            "title"           => $pst['title'],
            "telp"            => $pst['telp'],
            "fax"             => $pst['fax'],
            "master"          => $pst['master'],
            "alamat"          => $pst['alamat'],
            "update_by_users" => $this->session->userdata("id"),
        );
        $id_store_region = $this->global_models->update("store_region", array("id_store_region" => $pst['id_detail']),$kirim);
      }
      else{
        $kirim = array(
            "title"           => $pst['title'],
            "telp"            => $pst['telp'],
            "fax"             => $pst['fax'],
            "master"          => $pst['master'],
            "alamat"          => $pst['alamat'],
            "create_by_users" => $this->session->userdata("id"),
            "create_date"     => date("Y-m-d")
        );
        
        $id_store_region = $this->global_models->insert("store_region", $kirim);
      }
      if($id_store_region){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("store/master-store-region");
    }
  }
 
  public function list_operation($id_store_region){
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
        . " FROM store_region_operation AS A"
        . " LEFT JOIN users_channel AS B ON A.id_users = B.id_users"
        . " WHERE A.id_store_region = '{$id_store_region}'");
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
      
      $this->global_models->delete("store_region_operation", array("id_store_region" => $id_store_region));
      foreach ($pst['id_users'] as $value) {
        if($value){
          $kirim[] = array(
            "id_store_region"     => $id_store_region,
            "id_users"            => $value,
            "create_by_users"     => $this->session->userdata("id"),
            "create_date"         => date("Y-m-d H:i:s"),
          );
        }
      }
      if($this->global_models->insert_batch("store_region_operation", $kirim)){
        $this->session->set_flashdata('success', 'Data tersimpan');
      }
      else{
        $this->session->set_flashdata('notice', 'Data tidak tersimpan');
      }
      redirect("store/master-store-region");
    }
  }
 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */