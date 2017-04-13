<?php
class Mjson_generate_harga extends CI_Model {

    function __construct()
    {
        parent::__construct();
//        $this->load->database();
    }
    function test($t){
      return $t;
    }
    
    function olah_code(&$kode, $table){
      $this->load->helper('string');
      $kode_data = random_string('alnum', 10);
      $kode = strtoupper($kode_data);
      $cek = $this->global_models->get_field($table, "id_{$table}", array("kode" => $kode));
      if($cek > 0){
        $this->olah_tour_code($kode, $table);
      }
    }
    
    
    function set_additional_visa($id_product_tour_book, $id_product_tour_information, $name, $type){
      $this->olah_code($kode, "product_tour_additional");
      $stat = array(
        1 => "Adult Triple Twin",
        2 => "Child Twin Bed",
        3 => "Child Extra Bed",
        4 => "Child No Bed",
        5 => "Adult Single",
      );
      $harga_visa = $this->global_models->get_field("product_tour_information", "visa", array("id_product_tour_information" => $id_product_tour_information));
      $kirim = array(
        "id_product_tour_book"          => $id_product_tour_book,
        "kode"                          => $kode,
        "id_user_pengaju"               => 1,
        "name"                          => "Visa {$name} {$stat[$type]}",
        "nominal"                       => $harga_visa,
        "pos"                           => 1,
        "status"                        => 1,
        "id_currency"                   => 2,
        "create_by_users"               => $this->session->userdata("id"),
        "create_date"                   => date("Y-m-d H:i:s"),
      );
      $this->global_models->insert("product_tour_additional", $kirim);
      return true;
    }
    
    function set_additional_less_ticket($id_product_tour_book, $id_product_tour_information, $name, $type){
      $this->olah_code($kode, "product_tour_additional");
      $stat = array(
        1 => "Adult Triple Twin",
        2 => "Child Twin Bed",
        3 => "Child Extra Bed",
        4 => "Child No Bed",
        5 => "Adult Single",
      );
      if($type == 1 OR $type == 5)
        $ty = "less_ticket_adl";
      else
        $ty = "less_ticket_chl";
      $harga_less_ticket = $this->global_models->get_field("product_tour_information", $ty, array("id_product_tour_information" => $id_product_tour_information));
      $kirim = array(
        "id_product_tour_book"          => $id_product_tour_book,
        "kode"                          => $kode,
        "id_user_pengaju"               => 1,
        "name"                          => "Less Ticket {$name} {$stat[$type]}",
        "nominal"                       => $harga_less_ticket,
        "pos"                           => 2,
        "status"                        => 1,
        "id_currency"                   => 2,
        "create_by_users"               => $this->session->userdata("id"),
        "create_date"                   => date("Y-m-d H:i:s"),
      );
      $this->global_models->insert("product_tour_additional", $kirim);
      return true;
    }
    
    function revert_all_payment($id_product_tour_book, $id_users){
      $payment = $this->global_models->get_query("SELECT *"
        . " FROM product_tour_book_payment"
        . " WHERE id_product_tour_book = '{$id_product_tour_book}'"
        . " AND (status = 0 OR status = 7)"
        . " AND tampil IS NULL");
      foreach($payment AS $py){
        if($py->pos == 1)
          $pos = 2;
        else
          $pos = 1;
        $kirim[] = array(
          "id_product_tour_book"        => $id_product_tour_book,
          "id_currency"                 => 2,
          "id_users"                    => $id_users,
          "nominal"                     => $py->nominal,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "pos"                         => $pos,
          "status"                      => $py->status,
          "tampil"                      => 2,
          "payment"                     => $py->payment,
          "status_payment"              => $py->status_payment,
          "note"                        => "Rev {$py->note}",
          "create_by_users"             => $this->session->userdata("id"),
          "create_date"                 => date("Y-m-d H:i:s"),
        );
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $py->id_product_tour_book_payment), array("tampil" => 2));
      }
      if($kirim)
        $this->global_models->insert_batch("product_tour_book_payment", $kirim);
      return true;
    }
    
    function recalculation_ppn($id_product_tour_book){
      $payment = $this->global_models->get_query("SELECT *"
        . " FROM product_tour_book_payment"
        . " WHERE id_product_tour_book = '{$id_product_tour_book}'"
        . " AND status = 7"
        . " AND tampil IS NULL");
      foreach($payment AS $py){
        if($py->pos == 1)
          $pos = 2;
        else
          $pos = 1;
        $kirim[] = array(
          "id_product_tour_book"        => $id_product_tour_book,
          "id_currency"                 => 2,
          "id_users"                    => $payment[0]->id_users,
          "nominal"                     => $py->nominal,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "pos"                         => $pos,
          "status"                      => $py->status,
          "tampil"                      => 2,
          "payment"                     => $py->payment,
          "status_payment"              => $py->status_payment,
          "note"                        => "Rev {$py->note}",
          "create_by_users"             => $this->session->userdata("id"),
          "create_date"                 => date("Y-m-d H:i:s"),
        );
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $py->id_product_tour_book_payment), array("tampil" => 2));
      }
      if($kirim)
        $this->global_models->insert_batch("product_tour_book_payment", $kirim);
      
      $tanggungan = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
        . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
        . " FROM product_tour_book_payment"
        . " WHERE id_product_tour_book = '{$id_product_tour_book}'"
        . " AND (status = 0 OR status = 6)"
        . " AND tampil IS NULL");
      $sisa = $tanggungan[0]->debit - $tanggungan[0]->kredit;
      $ppn = 1/100 * $sisa;
      
      $this->global_models->insert("product_tour_book_payment", array(
        "id_product_tour_book"        => $id_product_tour_book,
        "id_currency"                 => 2,
        "nominal"                     => $ppn,
        "tanggal"                     => date("Y-m-d H:i:s"),
        "pos"                         => 1,
        "status"                      => 7,
        "note"                        => "PPN 1% ".  number_format($sisa),
        "create_by_users"             => $this->session->userdata("id"),
        "create_date"                 => date("Y-m-d H:i:s"),
      ));
      
      return true;
    }
    
    function recount_payment($id_product_tour_book, $id_users){
      $book = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $id_product_tour_book));
      $pax = 0;
      if($book[0]->adult_triple_twin > 0){
        $nominal_att = $book[0]->adult_triple_twin * $book[0]->harga_adult_triple_twin;
        $kirim[] = array(
          "id_product_tour_book"      => $id_product_tour_book,
          "id_users"                  => $id_users,
          "id_currency"               => 2,
          "nominal"                   => $nominal_att,
          "tanggal"                   => date("Y-m-d H:i:s"),
          "pos"                       => 1,
          "status"                    => 0,
          "note"                      => "Adult Triple Twin {$book[0]->adult_triple_twin} x ".number_format($book[0]->harga_adult_triple_twin),
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s"),
        );
        $pax += $book[0]->adult_triple_twin;
        $total += $nominal_att;
      }

      if($book[0]->child_twin_bed > 0){
        $nominal_ctb = $book[0]->child_twin_bed * $book[0]->harga_child_twin_bed;
        $kirim[] = array(
          "id_product_tour_book"      => $id_product_tour_book,
          "id_users"                  => $id_users,
          "id_currency"               => 2,
          "nominal"                   => $nominal_ctb,
          "tanggal"                   => date("Y-m-d H:i:s"),
          "pos"                       => 1,
          "status"                    => 0,
          "note"                      => "Child Twin Bed {$book[0]->child_twin_bed} x ".number_format($book[0]->harga_child_twin_bed),
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s"),
        );
        $pax += $book[0]->child_twin_bed;
        $total += $nominal_ctb;
      }

      if($book[0]->child_extra_bed > 0){
        $nominal_ceb = $book[0]->child_extra_bed * $book[0]->harga_child_extra_bed;
        $kirim[] = array(
          "id_product_tour_book"      => $id_product_tour_book,
          "id_users"                  => $id_users,
          "id_currency"               => 2,
          "nominal"                   => $nominal_ceb,
          "tanggal"                   => date("Y-m-d H:i:s"),
          "pos"                       => 1,
          "status"                    => 0,
          "note"                      => "Child Extra Bed {$book[0]->child_extra_bed} x ".number_format($book[0]->harga_child_extra_bed),
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s"),
        );
        $pax += $book[0]->child_extra_bed;
        $total += $nominal_ceb;
      }

      if($book[0]->child_no_bed > 0){
        $nominal_cnb = $book[0]->child_no_bed * $book[0]->harga_child_no_bed;
        $kirim[] = array(
          "id_product_tour_book"      => $id_product_tour_book,
          "id_users"                  => $id_users,
          "id_currency"               => 2,
          "nominal"                   => $nominal_cnb,
          "tanggal"                   => date("Y-m-d H:i:s"),
          "pos"                       => 1,
          "status"                    => 0,
          "note"                      => "Child No Bed {$book[0]->child_no_bed} x ".number_format($book[0]->harga_child_no_bed),
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s"),
        );
        $pax += $book[0]->child_no_bed;
        $total += $nominal_cnb;
      }

      if($book[0]->sgl_supp > 0){
        $nominal_ss = $book[0]->sgl_supp * $book[0]->harga_single_adult;
        $kirim[] = array(
          "id_product_tour_book"      => $id_product_tour_book,
          "id_users"                  => $id_users,
          "id_currency"               => 2,
          "nominal"                   => $nominal_ss,
          "tanggal"                   => date("Y-m-d H:i:s"),
          "pos"                       => 1,
          "status"                    => 0,
          "note"                      => "Single {$book[0]->sgl_supp} x ".number_format($book[0]->harga_single_adult),
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s"),
        );
        $pax += $book[0]->sgl_supp;
        $total += $nominal_ss;
      }

      if($pax > 0){
        $nominal_pax = $pax * $book[0]->harga_airport_tax;
        $kirim[] = array(
          "id_product_tour_book"      => $id_product_tour_book,
          "id_users"                  => $id_users,
          "id_currency"               => 2,
          "nominal"                   => $nominal_pax,
          "tanggal"                   => date("Y-m-d H:i:s"),
          "pos"                       => 1,
          "status"                    => 0,
          "note"                      => "Airport Tax & Flight Insurance {$pax} x ".number_format($book[0]->harga_airport_tax),
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s"),
        );
        $total += $nominal_pax;
//        $ppn = 1/100 * $total;
//        $kirim[] = array(
//          "id_product_tour_book"      => $id_product_tour_book,
//          "id_users"                  => $id_users,
//          "id_currency"               => 2,
//          "nominal"                   => $ppn,
//          "tanggal"                   => date("Y-m-d H:i:s"),
//          "pos"                       => 1,
//          "status"                    => 7,
//          "note"                      => "PPN 1% x ".number_format($total),
//          "create_by_users"           => $this->session->userdata("id"),
//          "create_date"               => date("Y-m-d H:i:s"),
//        );
      }
      
      if($kirim)
        $this->global_models->insert_batch("product_tour_book_payment", $kirim);
      
          $this->recalculation_ppn($id_product_tour_book);

      return true;
    }
}
?>
