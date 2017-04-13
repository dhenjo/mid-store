<?php
class Mjson_tour_fit extends CI_Model {

    function __construct()
    {
        parent::__construct();
//        $this->load->database();
    }
    
    function olah_code(&$kode, $table, $num = 10){
      $this->load->helper('string');
      $kode_data = random_string('alnum', $num);
      $kode = strtoupper($kode_data);
      $cek = $this->global_models->get_field($table, "id_{$table}", array("kode" => $kode));
      if($cek > 0){
        $this->olah_tour_code($kode, $table);
      }
    }
    
    function revert_system_price($id_tour_fit_request, $id_users){
      $price = $this->global_models->get_query("SELECT *"
        . " FROM tour_fit_request_price"
        . " WHERE id_tour_fit_request = '{$id_tour_fit_request}'"
        . " AND (type = 1 OR type = 4)"
        . " AND status = 1");
      $this->global_models->update("tour_fit_request_price", array("id_tour_fit_request" => $id_tour_fit_request, "type" => 1, "status" => 1), array("status" => 3));
      $this->global_models->update("tour_fit_request_price", array("id_tour_fit_request" => $id_tour_fit_request, "type" => 4, "status" => 1), array("status" => 3));
      foreach($price AS $pr){
        $pos = 1;
        if($pr->pos == 1){
          $pos = 2;
        }
        $pembalik[] = array(
          "id_tour_fit_request"         => $id_tour_fit_request,
          "id_users"                    => $id_users,
          "type"                        => $pr->type,
          "title"                       => $pr->title,
          "price"                       => $pr->price,
          "qty"                         => $pr->qty,
          "total"                       => $pr->total,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "pos"                         => $pos,
          "status"                      => 3,
          "note"                        => "Revert",
          "create_date"                 => date("Y-m-d H:i:s"),
        );
        $log_note = ""
          . "<ul>"
            . "<li>Code : {$pr->kode}</li>"
            . "<li>Title : {$pr->title}</li>"
            . "<li>Price : ".number_format($pr->price)." * ".number_format($pr->qty)." = ".number_format($pr->total)."</li>"
          . "</ul>"
          . "";
        $this->log_fit_request($id_tour_fit_request,$id_users,$log_note, "Revert Price");
      }
      if($pembalik){
        $this->global_models->insert_batch("tour_fit_request_price", $pembalik);
      }
      return true;
    }
    
    function revert_system_price2($id_tour_fit_request, $id_users){
      $price = $this->global_models->get_query("SELECT *"
        . " FROM tour_fit_request_price_hpp"
        . " WHERE id_tour_fit_request = '{$id_tour_fit_request}'"
        . " AND (type = 1 OR type = 4)"
        . " AND status = 1");
      $this->global_models->update("tour_fit_request_price_hpp", array("id_tour_fit_request" => $id_tour_fit_request, "type" => 1, "status" => 1), array("status" => 3));
      $this->global_models->update("tour_fit_request_price_hpp", array("id_tour_fit_request" => $id_tour_fit_request, "type" => 4, "status" => 1), array("status" => 3));
      foreach($price AS $pr){
        $pos = 1;
        if($pr->pos == 1){
          $pos = 2;
        }
        $pembalik[] = array(
          "id_tour_fit_request"         => $id_tour_fit_request,
          "id_users"                    => $id_users,
          "type"                        => $pr->type,
          "title"                       => $pr->title,
          "price"                       => $pr->price,
          "qty"                         => $pr->qty,
          "total"                       => $pr->total,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "pos"                         => $pos,
          "status"                      => 3,
          "note"                        => "Revert",
          "create_date"                 => date("Y-m-d H:i:s"),
        );
      }
      if($pembalik){
        $this->global_models->insert_batch("tour_fit_request_price_hpp", $pembalik);
      }
      return true;
    }
    
    function log_fit_request($id_tour_fit_request, $id_users, $note, $title){
      $post = array(
        "id_tour_fit_request" => $id_tour_fit_request,
        "id_users"        => $id_users,
        "title"           => $title,
        "tanggal"         => date("Y-m-d H:i:s"),
        "note"            => $note,
        "create_by_users" => $this->session->userdata("id"),
        "create_date"     => date("Y-m-d H:i:s"),
      );
      $this->global_models->insert("log_fit_request", $post);
    }
    
}
?>
