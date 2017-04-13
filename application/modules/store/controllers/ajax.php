<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller {
    
  function __construct() {      
    $this->menu = $this->cek();
  }
  function add_row(){
    $nomor = $this->input->post("no") + 1;
    print "<div class='input-group margin' id='users-box{$nomor}'>"
      . "<input type='text' class='form-control' id='users{$nomor}' name='users[]'>"
      . "<input type='text' class='form-control' id='id_users{$nomor}' name='id_users[]' style='display: none'>"
      . "<span class='input-group-btn'>"
        . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='users-box{$nomor}'>"
          . "<i class='fa fa-fw fa-times'></i>"
        . "</a>"
      . "</span> "
    . "</div>"
    . "<script>"
        . "$(function() {"
          . "$( '#users{$nomor}' ).autocomplete({"
            . "source: '".site_url("store/ajax/users")."',"
            . "minLength: 1,"
            . "search  : function(){ $(this).addClass('working');},"
            . "open    : function(){ $(this).removeClass('working');},"
            . "select: function( event, ui ) {"
              . "$('#id_users{$nomor}').val(ui.item.id);"
            . "}"
          . "});"
        . "});"
    . "</script>";
    die;
  }
  
  function users(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM users_channel
      WHERE 
      (LOWER(name) LIKE '%{$q}%' OR LOWER(email) LIKE '%{$q}%')
      AND type = 4
      LIMIT 0,20
      ");
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_users,
            "label" => $tms->name." <".$tms->email.">",
            "value" => $tms->name." <".$tms->email.">",
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
    echo json_encode($result);
    die;
  }
  function add_row_mid_user(){
    $nomor = $this->input->post("no") + 1;
    print "<div class='input-group margin' id='users-box{$nomor}'>"
      . "<input type='text' class='form-control' id='users{$nomor}' name='users[]'>"
      . "<input type='text' class='form-control' id='id_users{$nomor}' name='id_users[]' style='display: none'>"
      . "<span class='input-group-btn'>"
        . "<a href='javascript:void(0)' class='btn btn-danger btn-flat delete' isi='users-box{$nomor}'>"
          . "<i class='fa fa-fw fa-times'></i>"
        . "</a>"
      . "</span> "
    . "</div>"
    . "<script>"
        . "$(function() {"
          . "$( '#users{$nomor}' ).autocomplete({"
            . "source: '".site_url("store/ajax/mid_users")."',"
            . "minLength: 1,"
            . "search  : function(){ $(this).addClass('working');},"
            . "open    : function(){ $(this).removeClass('working');},"
            . "select: function( event, ui ) {"
              . "$('#id_users{$nomor}').val(ui.item.id);"
            . "}"
          . "});"
        . "});"
    . "</script>";
    die;
  }
  function mid_users(){
    if (empty($_GET['term'])) exit ;
    $q = strtolower($_GET["term"]);
    if (get_magic_quotes_gpc()) $q = stripslashes($q);
    $items = $this->global_models->get_query("
      SELECT *
      FROM m_users
      WHERE 
      (LOWER(name) LIKE '%{$q}%' OR LOWER(email) LIKE '%{$q}%')
      AND type = 1
      LIMIT 0,20
      ");
      $aa = $this->db->last_query();
    if(count($items) > 0){
      foreach($items as $tms){
        $result[] = array(
            "id"    => $tms->id_users,
            "label" => $tms->name." <".$tms->email.">",
            "value" => $tms->name." <".$tms->email.">",
        );
      }
    }
    else{
      $result[] = array(
          "id"    => 0,
          "label" => "No Found",
          "value" => "No Found",
      );
    }
   
    echo json_encode($result);
    die;
  }
  
  function region(){

     $html = "
                   <div class='input-group margin' id='users-box2'>";
                $html .= $this->form_eksternal->form_dropdown('region[]', array("0" =>"Pilih", "1" =>"eropa","2" =>"middle east africa","3" =>"america","4" => "australia", "5" => "asia", "6" =>"china","7" =>"new zeland" ),"", 'class="form-control input-sm"');
                 $html .= "<span class='input-group-btn'>
                     <a href='javascript:void(0)' class='btn btn-danger btn-flat delete2' isi2='users-box2' >
                            <i class='fa fa-fw fa-times'></i>
                          </a>
                      </span>
                  </div>";
     
    print $html;
    die;
  }
  
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */