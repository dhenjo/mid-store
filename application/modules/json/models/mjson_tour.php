<?php
class Mjson_tour extends CI_Model {

    function __construct()
    {
        parent::__construct();
//        $this->load->database();
    }
    function test($t){
      return $t;
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
    
//    function set_price_tour_fit($type, $id_tour_fit_book){
//      $id_tour_fit_schedule = $this->global_models->get_field("tour_fit_book", "id_tour_fit_schedule", array("id_tour_fit_book" => $id_tour_fit_book));
//      $tour_fit_schedule = $this->global_models->get("tour_fit_schedule", array("id_tour_fit_schedule" => $id_tour_fit_schedule));
//      if($type == 1){
//        
//      }
//    }
    
    
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
    
    function revert_all_payment_fit($id_tour_fit_book, $id_users){
      $payment = $this->global_models->get_query("SELECT *"
        . " FROM tour_fit_book_price"
        . " WHERE id_tour_fit_book = '{$id_tour_fit_book}'"
        . " AND status = 1"
        . "");
      foreach($payment AS $py){
        if($py->pos == 1)
          $pos = 2;
        else
          $pos = 1;
        $kirim[] = array(
          "id_tour_fit_book"      => $id_tour_fit_book,
          "id_users"              => $id_users,
          "type"                  => $py->type,
          "title"                 => "Rev ".$py->type,
          "price"                 => $py->price,
          "total"                 => $py->total,
          "qty"                   => $py->qty,
          "tanggal"               => date("Y-m-d H:i:s"),
          "pos"                   => $pos,
          "status"                => 3,
          "create_by_users"       => $users[0]->id_users,
          "create_date"           => date("Y-m-d H:i:s"),
        );
        $this->global_models->update("tour_fit_book_price", array("id_tour_fit_book_price" => $py->id_tour_fit_book_price), array("status" => 3));
      }
      if($kirim)
        $this->global_models->insert_batch("tour_fit_book_price", $kirim);
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
        . " AND (status = 0 OR status = 6 OR status = 5)"
        . " AND tampil IS NULL"
        . " AND pajak IS NULL");
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
    
    function update_sort_book($id_product_tour_book){
        $book = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $id_product_tour_book));
        
        if($book[0]->status == 2 OR $book[0]->status == 3){
          $max_product_tour = $this->global_models->get_field("product_tour_book", "MAX(sort)", array("id_product_tour_information" => $book[0]->id_product_tour_information, "sort <" => 100));
          $max_product_tour += 1;
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $book[0]->id_product_tour_book), array("sort" => $max_product_tour));
          $customer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $book[0]->id_product_tour_book));
          $max_product_customer = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_information" => $book[0]->id_product_tour_information, "sort <" => 100));
          foreach($customer AS $cus){
            $max_product_customer++;
            $cetak_max = $max_product_customer;
            if($cus->less_ticket)
            $cetak_max = 0;
            $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $cus->id_product_tour_customer), array("sort" => 2000));
          }
        }else{
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $book[0]->id_product_tour_book), array("sort" => 2000));
          $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $book[0]->id_product_tour_book), array("sort" => 2000));
        }
        
    }
    
    function cek_status_book($id_product_tour_book, $id_users){
      $book = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $id_product_tour_book));
      if($book[0]->status < 4){
        $payment = $this->global_models->get_query("SELECT SUM(CASE WHEN A.pos = 1 THEN A.nominal ELSE 0 END) AS debit"
          . " ,SUM(CASE WHEN A.pos = 2 THEN A.nominal ELSE 0 END) AS kredit"
          . " ,SUM(CASE WHEN (A.status = 2 OR A.status = 4) THEN nominal ELSE 0 END) AS deposit"
          . " FROM product_tour_book_payment AS A"
          . " WHERE A.tampil IS NULL"
          . " AND A.id_product_tour_book = '{$id_product_tour_book}'");
//        print_r($payment);
        $sisa = $payment[0]->kredit - $payment[0]->debit;
        $status = 1;
        if($payment[0]->deposit > 0){
            
          if($sisa >= 0){
            $status = 3;
          }
          else{
            $status = 2;
          }
        }
        else{
          $status = 1;
        }
        $this->global_models->update("product_tour_book", array("id_product_tour_book" => $id_product_tour_book), array("status" => $status));
        $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $id_product_tour_book, "status <" => "4"), array("status" => $status));
//        print $this->db->last_query();
      }
      return true;
    }
}
?>
