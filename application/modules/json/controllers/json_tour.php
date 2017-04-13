<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_tour extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  function get_payment_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
      if($pst['start']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start']}' AND '{$pst['end']}')";
      }
      else{
        $where .= " AND (A.tanggal BETWEEN '".date("Y-m-")."01' AND '".date("Y-m-")."31')";
      }
      if($pst['code']){
        $where .= " AND A.kode LIKE '%{$pst['code']}%'";
      }
      if($pst['status']){
        $where .= " AND A.status = '{$pst['status']}'";
      }
      $sql = "SELECT A.*, I.id_store"
        . " ,(SELECT C.name FROM users_channel AS C WHERE C.id_users = A.id_users) AS bookers"
        . " ,(SELECT D.name FROM users_channel AS D WHERE D.id_users = A.id_users_confirm) AS confirm"
        . " ,(SELECT G.title FROM store AS G WHERE G.id_store = I.id_store) AS store1"
        . " ,I.kode AS book_code"
        . " FROM product_tour_book_payment AS A"
        . " LEFT JOIN product_tour_book AS I ON A.id_product_tour_book = I.id_product_tour_book"
        . " WHERE A.pos = 2 AND (A.tampil IS NULL OR A.tampil <> 2)"
        . " AND A.status = 2"
        . " {$where}";
      $data = $this->global_models->get_query($sql);
      if($data){
        $kirim = array(
          'status'  => 2,
          'book'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_finance_payment(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book_payment = $this->global_models->get("product_tour_book_payment", array("kode" => $pst['code']));
    if($product_tour_book_payment){
      if($this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $product_tour_book_payment[0]->id_product_tour_book_payment), array("id_users_confirm" => $pst['id_users'], "status" => $pst['status'], "update_by_users" => $users[0]->id_users))){
        $cek_product_tour_book_payment = $this->global_models->get("product_tour_book_payment", array("id_product_tour_book" => $product_tour_book_payment[0]->id_product_tour_book, "status <>" => 3, "pos" => 2));
        if($pst['status'] == 3 AND !$cek_product_tour_book_payment){
          $id_product_tour_information = $this->global_models->get_field("product_tour_book", "id_product_tour_information", array("id_product_tour_book" => $product_tour_book_payment[0]->id_product_tour_book));
          $max = $this->global_models->get_field("product_tour_book", "MAX(sort)", array("id_product_tour_information" => $id_product_tour_information));
          if($max < 100)
            $max = 99;
          
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book_payment[0]->id_product_tour_book), array("sort" => ($max+1), "update_by_users" => $users[0]->id_users));
          
          $max2 = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_information" => $id_product_tour_information));
          if($max2 < 100)
            $max = 99;
          $costumer = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_book" => $product_tour_book_payment[0]->id_product_tour_book));
          foreach ($costumer AS $cos){
            $max++;
            $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $cos->id_product_tour_customer), array("sort" => $mx, "update_by_users" => $users[0]->id_users));
          }
          
          $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $product_tour_book_payment[0]->id_product_tour_book_payment), array("id_users_confirm" => $pst['id_users'], "status" => $pst['status'], "update_by_users" => $users[0]->id_users, "note" => $pst['note']));
          $kirim = array(
            'status'  => 4,
            'note'    => ""
          );
        }
        else{
          $kirim = array(
            'status'  => 2,
            'note'    => ""
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 5,
          'note'    => "Update Gagal"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 3,
        'note'    => 'Data Tidak Ada'
      );
    }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function update_deposit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $this->debug($pst, true);
      $product_tour_book_payment = $this->global_models->get("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id']));
      if($product_tour_book_payment){
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id']), array('no_deposit' => $pst['no_deposit'], "note" => $product_tour_book_payment[0]->note." {$pst['no_deposit']}"));
        $kirim = array(
          'status'  => 2,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function update_contact_person(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $this->debug($pst, true);
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['book_code']));
      if($product_tour_book[0]->id_product_tour_book > 0){
          $data = array("first_name" => "{$pst['first_name']}",
                        "last_name"  => "{$pst['last_name']}",
                        "telphone"   => "{$pst['telp']}",
                        "email"      => "{$pst['email']}",
                        "address"    => "{$pst['eaddress']}");
        $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $data);
        $kirim = array(
          'status'  => 2,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function payment_void(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
        $get = $this->global_models->get("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id_payment']));
        
    if($get[0]->id_product_tour_book_payment){
      if($get[0]->pos == 1){
            $pos = 2;
      }else{
            $pos = 1;
      }
      
      $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id_payment']), array("tampil" => 2));
      
        $kirim_payment = array(
          "id_product_tour_book"        => $get[0]->id_product_tour_book,
          "id_users"                    => $pst['id_users'],
          "id_users_confirm"            => $get[0]->id_users_confirm,  
          "id_tour_pameran"             => $get[0]->id_tour_pameran,
          "id_inventory"                => $get[0]->id_inventory,  
          "id_store"                    => $get[0]->id_store,
          "id_ttu"                      => $get[0]->id_ttu,
          "no_ttu"                      => $get[0]->no_ttu,
          "no"                          => $get[0]->no,
          "no_deposit"                  => $get[0]->no_deposit,  
          "type"                        => $get[0]->type,
          "kode"                        => $get[0]->kode,
          "id_currency"                 => $get[0]->id_currency,
          "nominal"                     => $get[0]->nominal,
          "tanggal"                     => $get[0]->tanggal,
          "pos"                         => $pos,
          "pajak"                       => $get[0]->pajak,  
          "status"                      => $get[0]->status,
          "tampil"                      => 2,  
          "payment"                     => $get[0]->payment,
          "status_payment"              => $get[0]->status_payment, 
          "note"                        => "Note Void: {$pst['note']}",
          "create_by_users"             => $users[0]->id_users,
          "remark"                      => $get[0]->remark,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment);
       
        $kirim_payment2 = array("title"    => "{$get[0]->note}",
            "note"                         => "{$pst['note']}",
            "id_users"                     => "{$pst['id_users']}",
            "id_product_tour_book"         => "{$get[0]->id_product_tour_book}",
            "id_product_tour_book_payment" => "{$pst['id_payment']}",
            "create_by_users"              => $users[0]->id_users,
            "create_date"                  => date("Y-m-d H:i:s"),
            "tanggal"                      => date("Y-m-d H:i:s"));
        $this->global_models->insert("product_tour_book_payment_void", $kirim_payment2);
        
        $this->load->model("json/mjson_tour");
        $this->mjson_tour->cek_status_book($get[0]->id_product_tour_book, $pst['id_users']);
        $this->mjson_tour->update_sort_book($get[0]->id_product_tour_book);
        $this->mjson_tour->recalculation_ppn($get[0]->id_product_tour_book);
        
        $kirim = array(
          'status'      => 2,
          'id_book'     => $get[0]->id_product_tour_book,
          'note'        => "Berhasil"
        );
        
    }else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada'
        );
    }
         
    }else{
        $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    
    print json_encode($kirim);
    die;
  }
  
  function history_payment_void(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
        
     $data = $this->global_models->get_query("SELECT A.title,A.tanggal,A.note,(SELECT K.name FROM users_channel AS K WHERE K.id_users=A.id_users GROUP BY A.id_users) AS name  "
        . " FROM product_tour_book_payment_void AS A"
        . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " WHERE B.kode ='{$pst['code']}' ORDER BY A.id_product_tour_book_payment_void DESC");
       
        
    }
    
    print json_encode($data);
    die;
  }
  
  function payment_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      if($product_tour_book){
        
//        if($pst['id_tour_pameran']){
//          $kirim_pameran = array(
//            "id_tour_pameran"       => $pst['id_tour_pameran'],
//            "id_users"              => $pst['id_users'],
//            "name"                  => $product_tour_book[0]->first_name." ".$product_tour_book[0]->last_name,
//            "no"                    => $pst['no_ttu'],
//            "type"                  => 1,
//            "tanggal"               => $pst['tanggal'],
//            "nominal"               => $pst['nominal'],
//            "note"                  => $pst['note'],
//            "create_by_users"       => $users[0]->id_users,
//            "create_date"           => date("Y-m-d H:i:s"),
//          );
//          $id_tour_transaksi_pameran = $this->global_models->insert("tour_transaksi_pameran", $kirim_pameran);
//        }
          
        $this->load->model("json/mjson_tour");
        $this->olah_payment_code($kode);
        $no_ttu = $this->generate_ttu($pst['id_tour_pameran'], $product_tour_book[0]->id_store, $pst['type']);
        $kirim_payment = array(
          "id_product_tour_book"        => $product_tour_book[0]->id_product_tour_book,
          "id_users"                    => $pst['id_users'],
          "id_tour_pameran"             => $pst['id_tour_pameran'],
          "id_store"                    => $product_tour_book[0]->id_store,
          "no_ttu"                      => $no_ttu['ttu'],
          "no"                          => $no_ttu['no'],
          "type"                        => $pst['type'],
          "kode"                        => $kode,
          "id_currency"                 => 2,
          "nominal"                     => $pst['nominal'],
          "tanggal"                     => $pst['tanggal'],
          "pos"                         => 2,
          "status"                      => 2,
          "payment"                     => $pst['payment'],
          "note"                        => "TTU: {$no_ttu['ttu']}",
          "create_by_users"             => $users[0]->id_users,
          "remark"                      => $pst['remark'],
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment);
        if($product_tour_book[0]->status == 1){
          $max_product_tour = $this->global_models->get_field("product_tour_book", "MAX(sort)", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information, "sort <" => 100));
          $max_product_tour += 1;
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("sort" => $max_product_tour));
          $customer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
          $max_product_customer = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information, "sort <" => 100));
          foreach($customer AS $cus){
            $max_product_customer++;
            $cetak_max = $max_product_customer;
            if($cus->less_ticket)
              $cetak_max = 0;
            $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $cus->id_product_tour_customer), array("sort" => $cetak_max));
          }
          if($cetak_max > 0){
//            $this->global_models->query("UPDATE product_tour_information"
//              . " SET seat_update = (available_seat - ".count($customer).")"
//              . " WHERE id_product_tour_information = '{$product_tour_book[0]->id_product_tour_information}'");
          }
          
        }
        $cek_lunas = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE (nominal * -1) END) AS sisa"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'");
        if($cek_lunas[0]->sisa > 0){
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 2));
          $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 2));
          $kirim = array(
            'status'  => 2,
            'note'    => $cek_lunas[0]->sisa
          );
        }
        else{
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 3));
          $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 3));
          $kirim = array(
            'status'  => 4,
            'note'    => $cek_lunas[0]->sisa
          );
        }
        $this->mjson_tour->cek_status_book($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function refund_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      if($product_tour_book){
        $this->olah_payment_code($kode);
        if($pst['note']){
          $dt_note = ",Note: {$pst['note']}";
        }else{
          $dt_note = "";
        }
        $kirim_payment = array(
          "id_product_tour_book"        => $product_tour_book[0]->id_product_tour_book,
          "id_users"                    => $pst['id_users'],
          "kode"                        => $kode,
          "no_deposit"                  => $pst['no_refund'],
          "id_currency"                 => 2,
          "nominal"                     => $pst['nominal'],
          "tanggal"                     => $pst['tanggal'],
          "pos"                         => 1,
          "status"                      => 8,
          "payment"                     => $pst['payment'],
          "note"                        => "Refund: {$pst['no_refund']}{$dt_note}",
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment);
        
        $kirim = array(
            'status'  => 2,
            'note'    => ""
          );
       
       
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function chat_additional(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
     $id_product_tour_book = $this->global_models->get_field("product_tour_book", "id_product_tour_book", array("kode" => $pst['code']));
      
	  if($id_product_tour_book){
        $post = array(
          "id_product_tour_book"        => $id_product_tour_book,
          "id_users"                    => $pst['id_users'],
          "name"                        => $pst['name'],
          "tanggal"                     => date("Y-m-d H:i:s"),
          "status"                      => $pst['status'],
          "note"                        => $pst['note'],
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s"),
        );
        $id_product_tour_log_request_additional = $this->global_models->insert("product_tour_log_request_additional", $post);
        
		
        if($id_product_tour_log_request_additional){
          $kirim = array(
            'status'  => 2,
            'note'    => $cek_lunas[0]->sisa
          );
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => "Insert Gagal"
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function req_discount(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
     
      $id_product_tour_book = $this->global_models->get_field("product_tour_book", "id_product_tour_book", array("kode" => $pst['code']));
      if($id_product_tour_book){
        $post = array(
          "discount_request"            => $pst['nominal'],
          "status_discount"             => 2,
          "id_currency"                 => 2,
          "id_product_tour_book"        => $id_product_tour_book,
          "id_pengajuan"                => $pst['id_users'],
          "status"                      => 1,
          "note"                        => $pst['note'],
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s"),
        );
        $id_product_tour_discount_tambahan = $this->global_models->insert("product_tour_discount_tambahan", $post);
       
        if($id_product_tour_discount_tambahan){
          $kirim = array(
            'status'  => 2,
            'note'    => ""
          );
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => "Insert Gagal"
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_users_approval(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_users = $this->global_models->get_field("product_tour_book", "id_users", array("kode" => $pst['code']));
      $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $id_users));
      if(!$id_store)
        $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $id_users));
      $daftar = $this->global_models->get("store_commited", array("id_store" => $id_store));
      if($daftar){
        foreach($daftar AS $daf){
          $hasil[] = $daf->id_users;
        }
        $kirim = array(
          'status'  => 2,
          'data'    => $hasil,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_detail_users(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $users_channel = $this->global_models->get("users_channel", array("id_users" => $pst['id_users']));
      if($users_channel){
        $store = $this->global_models->get_query("SELECT B.*"
          . " FROM store_tc AS A"
          . " LEFT JOIN store AS B ON A.id_store = B.id_store"
          . " WHERE A.id_users = '{$pst['id_users']}'");
        if(!$store){
          $store = $this->global_models->get_query("SELECT B.*"
            . " FROM store_commited AS A"
            . " LEFT JOIN store AS B ON A.id_store = B.id_store"
            . " WHERE A.id_users = '{$pst['id_users']}'");
        }
        $kirim = array(
          'status'  => 2,
          'users'   => $users_channel[0],
          'store'   => $store[0]
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_detail_pax_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $customer = $this->global_models->get_query("SELECT A.*"
        . " ,B.kode AS book_code"
        . " FROM product_tour_customer AS A"
        . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " WHERE A.kode = '{$pst['code']}'");
      if($customer){
        $kirim = array(
          'status'  => 2,
          'data'    => $customer[0],
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_detail_fit_pax_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $customer = $this->global_models->get_query("SELECT A.*"
        . " FROM tour_fit_book_pax AS A"
        . " WHERE A.kode = '{$pst['code']}'");
//      $this->debug($pst, true);
      if($customer){
        $kirim = array(
          'status'  => 2,
          'data'    => $customer[0],
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_discount(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_product_tour_book = $this->global_models->get_field("product_tour_book", "id_product_tour_book", array("kode" => $pst['code']));
      $discount = $this->global_models->get_query("SELECT A.*"
        . " ,B.name AS request"
        . " ,C.name AS approval"
        . " FROM product_tour_discount_tambahan AS A"
        . " LEFT JOIN users_channel AS B ON A.id_pengajuan = B.id_users"
        . " LEFT JOIN users_channel AS C ON A.id_user_approval = C.id_users"
        . " WHERE A.id_product_tour_book = '{$id_product_tour_book}'");
      if($discount){
        $kirim = array(
          'status'  => 2,
          'data'    => $discount,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_discount(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($this->global_models->update("product_tour_discount_tambahan", array("id_product_tour_discount_tambahan" => $pst['id']), array("id_user_approval" => $pst['id_users'], "status" => $pst['status'], "update_by_users" => $users[0]->id_users))){
        
        if($pst['status'] == 2){
          $discount = $this->global_models->get("product_tour_discount_tambahan", array("id_product_tour_discount_tambahan" => $pst['id']));
          $kirim_payment = array(
            "id_product_tour_book"              => $discount[0]->id_product_tour_book,
            "id_users"                          => $pst['id_users'],
            "kode"                              => $kode,
            "id_currency"                       => 2,
            "nominal"                           => $discount[0]->discount_request,
            "tanggal"                           => date("Y-m-d H:i:s"),
            "pos"                               => 2,
            "status"                            => 6,
            "note"                              => "[Discount] ".$discount[0]->note,
            "create_by_users"                   => $users[0]->id_users,
            "create_date"                       => date("Y-m-d H:i:s"),
          );
          $this->global_models->insert("product_tour_book_payment", $kirim_payment);
          
          $this->load->model("json/mjson_tour");
          $this->mjson_tour->recalculation_ppn($discount[0]->id_product_tour_book);
		  $this->mjson_tour->cek_status_book($discount[0]->id_product_tour_book, $pst['id_users']);
          
          $kirim = array(
            'status'  => 4,
            'note'    => ""
          );
        }
        else{
          $kirim = array(
            'status'  => 2,
            'note'    => ""
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  //  function cancel_tour_pax(){
//    $pst = $_REQUEST;
//    $this->global_models->get_connect("terminal");
//    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
//    $this->global_models->get_connect("default");
//    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
//      $product_tour_book_customer = $this->global_models->get("product_tour_customer", array("kode" => $pst['pax_code'], "id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
//      if($product_tour_book_customer){
//        if($this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $product_tour_book_customer[0]->id_product_tour_customer), array("status" => 4, "update_by_users" => $pst['id_users']))){
//          $product_tour_information = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $product_tour_book_customer[0]->id_product_tour_information));
//
//          $this->load->model("json/mjson_tour");
//          $this->mjson_tour->revert_all_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
//
//          if($product_tour_book_customer[0]->type == 1){
//            $update = array("adult_triple_twin" => ($product_tour_book[0]->adult_triple_twin - 1), "update_by_users" => $pst['id_users']);
//          }
//          else if($product_tour_book_customer[0]->type == 2){
//            $update = array("child_twin_bed" => ($product_tour_book[0]->child_twin_bed - 1), "update_by_users" => $pst['id_users']);
//          }
//          else if($product_tour_book_customer[0]->type == 3){
//            $update = array("child_extra_bed" => ($product_tour_book[0]->child_extra_bed - 1), "update_by_users" => $pst['id_users']);
//          }
//          else if($product_tour_book_customer[0]->type == 4){
//            $update = array("child_no_bed" => ($product_tour_book[0]->child_no_bed - 1), "update_by_users" => $pst['id_users']);
//          }
//          else if($product_tour_book_customer[0]->type == 5){
//            $update = array("sgl_supp" => ($product_tour_book[0]->sgl_supp - 1), "update_by_users" => $pst['id_users']);
//          }
//          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $update);
//          $this->mjson_tour->recount_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
//          
//          if($product_tour_book_customer[0]->status == 1){
//            $kirim = array(
//              'status'  => 2,
//              'note'    => ""
//            );
//          }
//          else if($product_tour_book_customer[0]->status == 2 OR $product_tour_book_customer[0]->status == 3){
//
//            $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $product_tour_book_customer[0]->id_product_tour_customer), array("status" => 4, "sort" => 1000,"update_by_users" => $pst['id_users']));
//            $type_bed = array(
//              1 => "Adult Triple/ Twin",
//              2 => "Child Twin Bed",
//              3 => "Child Extra Bed",
//              4 => "Child No Bed",
//              5 => "Adult Single",
//            );
//            if($product_tour_book_customer[0]->type == 1){
//              $harga_jual = number_format($product_tour_book[0]->harga_adult_triple_twin);
//            }
//            else if($product_tour_book_customer[0]->type == 2){
//              $harga_jual = number_format($product_tour_book[0]->harga_child_twin_bed);
//            }
//            else if($product_tour_book_customer[0]->type == 3){
//              $harga_jual = number_format($product_tour_book[0]->harga_child_extra_bed);
//            }
//            else if($product_tour_book_customer[0]->type == 4){
//              $harga_jual = number_format($product_tour_book[0]->harga_child_no_bed);
//            }
//            else if($product_tour_book_customer[0]->type == 5){
//              $harga_jual = number_format($product_tour_book[0]->harga_single_adult);
//            }
//            $kirim_log = array(
//              "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
//              "id_users"                  => 1,
//              "name"                      => "System",
//              "tanggal"                   => date("Y-m-d H:i:s"),
//              "status"                    => 1,
//              "note"                      => "Cancel Book. <br />"
//                . "Pax : {$product_tour_book_customer[0]->first_name} {$product_tour_book_customer[0]->last_name} <br />"
//                . "Bed Type : {$type_bed[$product_tour_book_customer[0]->type]} <br />"
//                . "Harga : {$harga_jual} <br />"
//                . "Jika ada biaya tambahan, harap menambahkan biaya tambahan untuk Status Cancel",
//              "create_by_users"           => $pst['id_users'],
//              "create_date"               => date("Y-m-d H:i:s"),
//            );
//            $this->global_models->insert("product_tour_log_request_additional", $kirim_log);
//            $kirim = array(
//              'status'  => 4,
//              'note'    => "Terdapat Deposit"
//            );
//          }
//          else{
//            $kirim = array(
//              'status'  => 5,
//              'note'    => "Status Tidak Bisa Cancel"
//            );
//          }
//        }
//        else{
//          $kirim = array(
//            'status'  => 7,
//            'note'    => "Cancel Gagal"
//          );
//        }
//      }
//      else{
//        $kirim = array(
//          'status'  => 3,
//          'note'    => 'Tidak Ada Data'
//        );
//      }
//    }
//    else{
//      $kirim = array(
//        'status'  => 1,
//        'note'    => 'Tidak Ada Akses'
//      );
//    }
//    print json_encode($kirim);
//    die;
//  }

 function cancel_tour_pax(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      $product_tour_book_customer = $this->global_models->get("product_tour_customer", array("kode" => $pst['pax_code'], "id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
      if($product_tour_book_customer){
        if($this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $product_tour_book_customer[0]->id_product_tour_customer), array("status" => 4, "update_by_users" => $pst['id_users']))){
          $product_tour_information = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $product_tour_book_customer[0]->id_product_tour_information));

         
          if($product_tour_book_customer[0]->status == 1){
             $this->load->model("json/mjson_tour");
          $this->mjson_tour->revert_all_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);

          if($product_tour_book_customer[0]->type == 1){
            $update = array("adult_triple_twin" => ($product_tour_book[0]->adult_triple_twin - 1), "update_by_users" => $pst['id_users']);
          }
          else if($product_tour_book_customer[0]->type == 2){
            $update = array("child_twin_bed" => ($product_tour_book[0]->child_twin_bed - 1), "update_by_users" => $pst['id_users']);
          }
          else if($product_tour_book_customer[0]->type == 3){
            $update = array("child_extra_bed" => ($product_tour_book[0]->child_extra_bed - 1), "update_by_users" => $pst['id_users']);
          }
          else if($product_tour_book_customer[0]->type == 4){
            $update = array("child_no_bed" => ($product_tour_book[0]->child_no_bed - 1), "update_by_users" => $pst['id_users']);
          }
          else if($product_tour_book_customer[0]->type == 5){
            $update = array("sgl_supp" => ($product_tour_book[0]->sgl_supp - 1), "update_by_users" => $pst['id_users']);
          }
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $update);
          $this->mjson_tour->recount_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
          
            $kirim = array(
              'status'  => 2,
              'note'    => ""
            );
          }
          else if($product_tour_book_customer[0]->status == 2 OR $product_tour_book_customer[0]->status == 3){
             $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $product_tour_book_customer[0]->id_product_tour_customer), array("status" => 6, "note" => $pst['note_cancel']));
//            $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $product_tour_book_customer[0]->id_product_tour_customer), array("status" => 4, "sort" => 1000,"update_by_users" => $pst['id_users']));
//            $type_bed = array(
//              1 => "Adult Triple/ Twin",
//              2 => "Child Twin Bed",
//              3 => "Child Extra Bed",
//              4 => "Child No Bed",
//              5 => "Adult Single",
//            );
//            if($product_tour_book_customer[0]->type == 1){
//              $harga_jual = number_format($product_tour_book[0]->harga_adult_triple_twin);
//            }
//            else if($product_tour_book_customer[0]->type == 2){
//              $harga_jual = number_format($product_tour_book[0]->harga_child_twin_bed);
//            }
//            else if($product_tour_book_customer[0]->type == 3){
//              $harga_jual = number_format($product_tour_book[0]->harga_child_extra_bed);
//            }
//            else if($product_tour_book_customer[0]->type == 4){
//              $harga_jual = number_format($product_tour_book[0]->harga_child_no_bed);
//            }
//            else if($product_tour_book_customer[0]->type == 5){
//              $harga_jual = number_format($product_tour_book[0]->harga_single_adult);
//            }
//            $kirim_log = array(
//              "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
//              "id_users"                  => 1,
//              "name"                      => "System",
//              "tanggal"                   => date("Y-m-d H:i:s"),
//              "status"                    => 1,
//              "note"                      => "Cancel Book. <br />"
//                . "Pax : {$product_tour_book_customer[0]->first_name} {$product_tour_book_customer[0]->last_name} <br />"
//                . "Bed Type : {$type_bed[$product_tour_book_customer[0]->type]} <br />"
//                . "Harga : {$harga_jual} <br />"
//                . "Jika ada biaya tambahan, harap menambahkan biaya tambahan untuk Status Cancel",
//              "create_by_users"           => $pst['id_users'],
//              "create_date"               => date("Y-m-d H:i:s"),
//            );
//            $this->global_models->insert("product_tour_log_request_additional", $kirim_log);
            $kirim = array(
              'status'  => 4,
              'note'    => "Terdapat Deposit"
            );
          }
          else{
            $kirim = array(
              'status'  => 5,
              'note'    => "Status Tidak Bisa Cancel"
            );
          }
        }
        else{
          $kirim = array(
            'status'  => 7,
            'note'    => "Cancel Gagal"
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function cancel_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      if($product_tour_book){
        $payment = $this->global_models->get_query("SELECT SUM(nominal) AS nom"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " AND pos = 2 AND tampil IS NULL AND (status = 2 OR status = 4)");
        if($payment[0]->nom > 0){
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 6));
          $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 6,"note" => $pst['note']));
          
//          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 5, "sort" => 1000));
//          $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 5, "sort" => 1000));
          $kirim = array(
            'status'  => 4,
            'note'    => 'Terdapat Pembayaran Sebelumnya',
            'deposit' => $payment[0]->nom,
          );
        }
        else{
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 4));
          $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 4,"note" => $pst['note']));
          $kirim = array(
            'status'  => 2,
            'note'    => 'Cancel Berhasil'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_penjualan_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['start']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start']} 00:00:00' AND '{$pst['end']} 23:56:56')";
      }
      else{
        $where .= " AND (A.tanggal BETWEEN '".date("Y-m-")."01 00:00:00"."' AND '".date("Y-m-t")." 23:56:56')";
      }
      if($pst['region'])
        $where .= " AND E.sub_category = '{$pst['region']}'";
      if($pst['store'])
        $where .= " AND (BS.id_store = '{$pst['store']}')";
      if($pst['status']){
        if($pst['status'] == 9)
          $where .= " AND (A.status = '2' OR A.status = '3')";
        else  
          $where .= " AND A.status = '{$pst['status']}'";
      }
      else{
        $where .= " AND (A.status = '1' OR A.status = '2' OR A.status = '3' OR A.status = '6')";
      }
      if($pst['code'])
        $where = " AND D.kode LIKE '%{$pst['code']}%'";
      
      $product_tour_book = $this->global_models->get_query("SELECT A.*"
        . " ,BS.title AS bstore, BS.id_store AS bid_store"
//        . " ,CS.title AS cstore, CS.id_store AS cid_store"
        . " ,D.kode AS ikode"
        . " ,E.kode AS tkode, E.sub_category, E.title AS tour_name"
        . " ,SUM(CASE WHEN F.pos = 1 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS debit"
        . " ,SUM(CASE WHEN F.pos = 2 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS kredit"
        . " FROM product_tour_book AS A"
//        . " LEFT JOIN store_tc AS B ON A.id_users = B.id_users"
        . " LEFT JOIN store AS BS ON A.id_store = BS.id_store"
//        . " LEFT JOIN store_commited AS C ON A.id_users = C.id_users"
//        . " LEFT JOIN store AS CS ON C.id_store = CS.id_store"
        . " LEFT JOIN product_tour_information AS D ON A.id_product_tour_information = D.id_product_tour_information"
        . " LEFT JOIN product_tour AS E ON A.id_product_tour = E.id_product_tour"
        . " LEFT JOIN product_tour_book_payment AS F ON A.id_product_tour_book = F.id_product_tour_book"
        . " WHERE 1=1"
        . " {$where}"
        . " GROUP BY A.id_product_tour_book"
        . " ORDER BY A.tanggal ASC"
      );
      if($product_tour_book){
        $kirim = array(
          'status'  => 2,
          'data'    => $product_tour_book,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_penjualan_tour_keberangkatan(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['start']){
        $where .= " AND (D.start_date BETWEEN '{$pst['start']}' AND '{$pst['end']}')";
      }
      else{
        $where .= " AND (D.start_date BETWEEN '".date("Y-m-")."01' AND '".date("Y-m-t")."')";
      }
      if($pst['region'])
        $where .= " AND E.sub_category = '{$pst['region']}'";
      if($pst['store'])
        $where .= " AND (BS.id_store = '{$pst['store']}' OR CS.id_store = '{$pst['store']}')";
      if($pst['code'])
        $where .= " AND E.kode LIKE '%{$pst['code']}%'";
      if($pst['status']){
        if($pst['status'] == 9)
          $where .= " AND (A.status = '2' OR A.status = '3')";
        else  
          $where .= " AND A.status = '{$pst['status']}'";
      }
      
      $product_tour_book = $this->global_models->get_query("SELECT A.*"
        . " ,BS.title AS bstore, BS.id_store AS bid_store"
        . " ,CS.title AS cstore, CS.id_store AS cid_store"
        . " ,D.kode AS ikode"
        . " ,D.start_date, D.end_date"
        . " ,E.kode AS tkode, E.sub_category, E.title AS tour_name"
        . " ,SUM(CASE WHEN F.pos = 1 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS debit"
        . " ,SUM(CASE WHEN F.pos = 2 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS kredit"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN store_tc AS B ON A.id_users = B.id_users"
        . " LEFT JOIN store AS BS ON B.id_store = BS.id_store"
        . " LEFT JOIN store_commited AS C ON A.id_users = C.id_users"
        . " LEFT JOIN store AS CS ON C.id_store = CS.id_store"
        . " LEFT JOIN product_tour_information AS D ON A.id_product_tour_information = D.id_product_tour_information"
        . " LEFT JOIN product_tour AS E ON A.id_product_tour = E.id_product_tour"
        . " LEFT JOIN product_tour_book_payment AS F ON A.id_product_tour_book = F.id_product_tour_book"
        . " WHERE A.status < 4"
        . " {$where}"
        . " GROUP BY A.id_product_tour_book"
        . " ORDER BY A.tanggal ASC"
      );
      if($product_tour_book){
        $kirim = array(
          'status'  => 2,
          'data'    => $product_tour_book,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_report_status_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['code'])
        $where = " A.kode LIKE '%{$pst['code']}%'";
      else{
        if($pst['status']){
          if($pst['status'] == 1)
            $where = " (A.status = 1 OR A.status IS NULL)";
          else  
            $where = " A.status = '{$pst['status']}'";
        }
        else{
          $where = " (A.status = '4' OR A.status = 1 OR A.status IS NULL OR A.status = 5)";
        }

        if($pst['start']){
          $where .= " AND (A.start_date BETWEEN '{$pst['start']}' AND '{$pst['end']}')";
        }
        else{
          $where .= " AND (A.start_date BETWEEN '".date("Y-m-")."01' AND '".date("Y-m-t")."')";
        }
        if($pst['region'])
          $where .= " AND B.sub_category = '{$pst['region']}'";
        if($pst['store'])
          $where .= " AND B.id_store = '{$pst['store']}'";
      }
	  
	  if($pst['book_start']){
        if($pst['book_start'] > $pst['book_end']){
          $book_where .= " AND (E.tanggal BETWEEN '{$pst['book_start']} 00:00:00' AND '{$pst['book_start']} 23:59:59')";
        }
        else{
          $book_where .= " AND (E.tanggal BETWEEN '{$pst['book_start']} 00:00:00' AND '{$pst['book_end']} 23:59:59')";
        }
      }
      
      if($pst['book_status2'] == 0){
        $status_where = " ";
      }
      if($pst['book_status2'] == 1){
        $status_where = " AND E.status = '1'";
      }
      else if($pst['book_status2'] == 2){
        $status_where = " AND E.status = '2'";
      }
      else if($pst['book_status2'] == 3){
        $status_where = " AND E.status = '3')";
      }
      else if($pst['book_status2'] == 4){
        $status_where = " AND (E.status = '3' OR E.status = '2')";
      }
      else if($pst['book_status2'] == 5){
        $status_where = " AND (E.status = '1' OR E.status = '2')";
      }
      else if($pst['book_status2'] == 6){
        $status_where = " AND (E.status = '3' OR E.status = '2')";
      }
      else if($pst['book_status2'] == 7){
        $status_where = " AND (E.status = '3' OR E.status = '2' OR E.status = '1')";
      }
      
      if($pst['id_master_sub_agent']){
        $where .= " AND E.id_master_sub_agent = '{$pst['id_master_sub_agent']}'";
      }
      
      if($pst['id_store']){
//        $where .= " AND (ST.id_store = '{$pst['id_store']}')";
        $where .= " AND (E.id_store = '{$pst['id_store']}')";
      }
      
      $product_tour_book = $this->global_models->get_query("SELECT A.id_product_tour_information, A.id_product_tour, A.kode, A.start_date, A.end_date, A.available_seat, A.status, A.kode"
        . " ,B.sub_category AS region,B.title AS tour_name, B.id_store, B.id_store_region"
        . " ,C.title AS store_region"
        . " ,SUM(CASE WHEN D.status = 1 THEN 1 ELSE 0 END) AS book"
        . " ,SUM(CASE WHEN D.status = 2 THEN 1 ELSE 0 END) AS dp"
        . " ,SUM(CASE WHEN D.status = 3 THEN 1 ELSE 0 END) AS lunas"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN store_region AS C ON B.id_store_region = C.id_store_region"
        . " LEFT JOIN product_tour_book AS E ON (A.id_product_tour_information = E.id_product_tour_information AND E.status < 4)"
        . " LEFT JOIN product_tour_customer AS D ON E.id_product_tour_book = D.id_product_tour_book"
        
//        . " LEFT JOIN store_tc AS ST ON E.id_users = ST.id_users"
//        . " LEFT JOIN store_commited SC ON E.id_users = SC.id_users"
        
        . " WHERE {$where} {$book_where} {$status_where}"
        . " GROUP BY A.id_product_tour_information"
        . " ORDER BY A.id_product_tour_information ASC"
      );
      
      $product_tour_book_payment = $this->global_models->get_query("SELECT"
        . " SUM(CASE WHEN F.pos = 1 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS debit"
        . " ,SUM(CASE WHEN F.pos = 2 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS kredit"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN store AS C ON B.id_store = C.id_store"
        . " LEFT JOIN product_tour_book AS E ON (A.id_product_tour_information = E.id_product_tour_information AND E.status < 4)"
        . " LEFT JOIN product_tour_book_payment AS F ON E.id_product_tour_book = F.id_product_tour_book"
        
        . " LEFT JOIN store_tc AS ST ON E.id_users = ST.id_users"
        . " LEFT JOIN store_commited SC ON E.id_users = SC.id_users"
        
        . " WHERE {$where} {$book_where} {$status_where}"
        . " GROUP BY A.id_product_tour_information"
        . " ORDER BY A.id_product_tour_information ASC"
      );
      if($product_tour_book){
        $kirim = array(
          'status'  => 2,
          'data'    => $product_tour_book,
          'payment' => $product_tour_book_payment,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_book_schedule(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get_query("SELECT (A.adult_triple_twin+A.child_twin_bed+A.child_extra_bed+A.child_no_bed+A.sgl_supp) AS pax"
        . " ,A.status, A.id_users"
        . " ,E.title AS store1, E.id_store AS id_store1"
        . " ,G.title AS store2, G.id_store AS id_store2"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN store_tc AS C ON A.id_users = C.id_users"
        . " LEFT JOIN store AS E ON C.id_store = E.id_store"
        . " LEFT JOIN store_commited AS F ON A.id_users = F.id_users"
        . " LEFT JOIN store AS G ON F.id_store = G.id_store"
        . " WHERE B.kode = '{$pst['code']}'"
        . " GROUP BY A.id_product_tour_book"
        . " ORDER BY A.id_product_tour_book ASC"
      );
      if($product_tour_book){
        $kirim = array(
          'status'  => 2,
          'data'    => $product_tour_book,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_penjualan_tc(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['start']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start']} 00:00:00' AND '{$pst['end']} 23:56:56')";
      }
      else{
        $where .= " AND (A.tanggal BETWEEN '".date("Y-m-")."01 00:00:00"."' AND '".date("Y-m-t")." 23:56:56')";
      }
      if($pst['region'])
        $where .= " AND E.sub_category = '{$pst['region']}'";
      
      if($pst['code'])
        $where .= " AND E.kode LIKE '%{$pst['code']}%'";
      if($pst['status']){
        if($pst['status'] == 9)
          $where .= " AND (A.status = '2' OR A.status = '3')";
        else  
          $where .= " AND A.status = '{$pst['status']}'";
      }
      
      $product_tour_book = $this->global_models->get_query("SELECT A.*"
        . " ,D.kode AS ikode"
        . " ,E.kode AS tkode, E.sub_category,E.title AS tour_name"
        . " ,SUM(CASE WHEN F.pos = 1 AND (F.tampil IS NULL OR F.tampil <> 2) THEN F.nominal ELSE 0 END) AS debit"
        . " ,SUM(CASE WHEN F.pos = 2 AND (F.tampil IS NULL OR F.tampil <> 2) THEN F.nominal ELSE 0 END) AS kredit"
        . " ,G.name AS users, G.id_users"
        . " FROM product_tour_book AS A"
//        . " LEFT JOIN store_tc AS B ON A.id_users = B.id_users"
//        . " LEFT JOIN store_commited AS C ON A.id_users = C.id_users"
        . " LEFT JOIN product_tour_information AS D ON A.id_product_tour_information = D.id_product_tour_information"
        . " LEFT JOIN product_tour AS E ON A.id_product_tour = E.id_product_tour"
        . " LEFT JOIN product_tour_book_payment AS F ON A.id_product_tour_book = F.id_product_tour_book"
        . " LEFT JOIN users_channel AS G ON A.id_users = G.id_users"
        . " WHERE (A.id_store = '{$pst['id_store']}')"
        . " {$where}"
        . " GROUP BY A.id_product_tour_book"
        . " ORDER BY A.tanggal ASC"
      );
      if($product_tour_book){
        $kirim = array(
          'status'  => 2,
          'data'    => $product_tour_book,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_sales_lead(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,B.first_name, B.last_name, B.email, B.telphone"
        . " ,C.name AS users"
        . " FROM tour_leads AS A"
        . " LEFT JOIN tour_pax AS B ON A.id_tour_pax = B.id_tour_pax"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
//        . " WHERE 1 = 1"
        . " ORDER BY A.tanggal DESC"
        . " LIMIT {$pst['start']},{$pst['max']}");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'start'   => $pst['start'],
          'max'     => $pst['max'],
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_room_list(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.id_product_tour, A.kode AS code_tour, A.title, A.days, A.night, A.sub_category AS region, A.airlines"
        . " ,B.id_product_tour_information, B.kode AS code_schedule, B.keberangkatan, B.status, B.start_date, B.start_time, B.end_time, B.end_date, B.available_seat AS seats, B.flt, B.in, B.out, B.status"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE B.kode = '{$pst['code']}'");
      if($data){
		if($pst['start']){
          if($pst['start'] > $pst['end']){
            $where .= " AND (B.tanggal BETWEEN '{$pst['start']} 00:00:00' AND '{$pst['start']} 23:59:59')";
          }
          else{
            $where .= " AND (B.tanggal BETWEEN '{$pst['start']} 00:00:00' AND '{$pst['end']} 23:59:59')";
          }
        }
        $pax = $this->global_models->get_query("SELECT A.*, B.kode AS book_code"
          . " FROM product_tour_customer AS A"
          . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
          . " WHERE A.id_product_tour_information = '{$data[0]->id_product_tour_information}'"
          . " AND A.status <> 4 AND A.status <> 5"
          . " {$pst['status']} {$where}"
          . " ORDER BY A.sort ASC");
        $kirim = array(
          'status'  => 2,
          'tour'    => $data[0],
          'pax'     => $pax,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_sales_lead(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['telphone']){
        $kirim_pax = array(
          "first_name"            => $pst['first_name'],
          "last_name"             => $pst['last_name'],
          "email"                 => $pst['email'],
          "tanggal_lahir"         => $pst['tanggal_lahir'],
          "tempat_tanggal_lahir"  => $pst['tempat_lahir'],
          "passport"              => $pst['passport'],
          "place_of_issued"       => $pst['tempat_password'],
          "date_of_issued"        => $pst['tanggal_apply'],
          "date_of_expired"       => $pst['tanggal_akhir'],
          "telphone"              => $pst['telphone'],
          "create_by_users"       => $users[0]->id_users,
          "create_date"           => date("Y-m-d H:i:s"),
        );
        $id_tour_pax = $this->global_models->insert("tour_pax", $kirim_pax);
        
        $kirim = array(
          "id_tour_pax"       => $id_tour_pax,
          "id_users"          => $pst['id_users'],
          "id_store"          => $id_store,
          "tanggal"           => date("Y-m-d H:i:s"),
          "status"            => 1,
          "note"              => $pst['note'],
          "create_by_users"   => $users[0]->id_users,
          "create_date"       => date("Y-m-d H:i:s"),
        );
        $id_tour_leads = $this->global_models->insert("tour_leads", $kirim);
        if($id_tour_leads){
          $kirim = array(
            'status'  => 2,
            'note'    => "Berhasil Disimpan"
          );
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => "Gagal Menyimpan"
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Telp Harus Diisi"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_price_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $book = $this->global_models->get("tour_fit_book", array("kode" => $pst['code']));
      if($book){
        $this->load->model("json/mjson_tour");
        $this->mjson_tour->revert_all_payment_fit($book[0]->id_tour_fit_book, 1);
        
        $note   = json_decode($pst['note']);
        $qty    = json_decode($pst['qty']);
        $price  = json_decode($pst['price']);
        $type   = json_decode($pst['type']);
        $pos    = json_decode($pst['pos']);
        foreach($note AS $key => $nt){
          $post[] = array(
            "id_tour_fit_book"      => $book[0]->id_tour_fit_book,
            "id_users"              => $pst['id_users'],
            "type"                  => $type[$key],
            "title"                 => $nt,
            "price"                 => str_replace("Rp ","", str_replace(",","",$price[$key])),
            "total"                 => (str_replace("Rp ","", str_replace(",","",$price[$key])) * $qty[$key]),
            "qty"                   => $qty[$key],
            "tanggal"               => date("Y-m-d H:i:s"),
            "pos"                   => $pos[$key],
            "status"                => 1,
            "create_by_users"       => $users[0]->id_users,
            "create_date"           => date("Y-m-d H:i:s"),
          );
        }
        if($post){
          $this->global_models->insert_batch("tour_fit_book_price", $post);
          $kirim = array(
            'status'  => 2,
            'note'    => "Berhasil Disimpan"
          );
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => "Gagal Menyimpan"
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Code tidak dikenali"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_master_pameran(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_tour_pameran']){
        $post = array(
          "id_users"            => $pst['id_users'],
          "title"               => $pst['title'],
          "kode"                => $pst['code'],
          "date_start"          => $pst['date_start'],
          "date_end"            => $pst['date_end'],
          "location"            => $pst['location'],
          "status"              => $pst['status'],
          "note"                => $pst['note'],
          "update_by_users"     => $users[0]->id_users,
        );
        $id_tour_pameran = $this->global_models->update("tour_pameran", array("id_tour_pameran" => $pst['id_tour_pameran']), $post);
      }
      else{
        $post = array(
          "id_users"            => $pst['id_users'],
          "title"               => $pst['title'],
          "kode"                => $pst['code'],
          "date_start"          => $pst['date_start'],
          "date_end"            => $pst['date_end'],
          "location"            => $pst['location'],
          "status"              => $pst['status'],
          "note"                => $pst['note'],
          "create_by_users"     => $users[0]->id_users,
          "create_date"         => date("Y-m-d H:i:s")
        );
        $id_tour_pameran = $this->global_models->insert("tour_pameran", $post);
      }
//      $this->debug($post, true);
      if($id_tour_pameran){
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
    function change_pameran(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_book_payment = $this->global_models->get("product_tour_book_payment",array("id_product_tour_book_payment" => "{$pst['id']}"));
      $kode_pameran =$this->global_models->get_field("tour_pameran","kode",array("id_tour_pameran" => "{$tour_book_payment[0]->id_tour_pameran}"));
      $kode_pameran_new =$this->global_models->get_field("tour_pameran","kode",array("id_tour_pameran" => "{$pst['id_pameran']}"));
      if($kode_pameran){
          $cek2 = $kode_pameran."-";
      }else{
          $cek2 = $kode_pameran;
      }
      $no_ttu = str_replace($cek2, "", $tour_book_payment[0]->no_ttu);
//      else{
      if($kode_pameran_new){
          $cek =$kode_pameran_new."-";
      }else{
          $cek = $kode_pameran_new;
      }
      $new_ttu = $cek.$no_ttu;
      $nottu = str_replace("--", "-", $new_ttu);
      
        $post = array(
          "id_tour_pameran"     => $pst['id_pameran'],
          "no_ttu"              => $nottu,
          "update_by_users"     => $pst['id_users'],
          "update_date"         => date("Y-m-d H:i:s")
        );
        $id_inventory = $this->global_models->update("product_tour_book_payment",array("id_product_tour_book_payment" => "{$pst['id']}"), $post);
//      }
      
      if($id_inventory){
        
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
          'id_inventory'    => $id_inventory,
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => "Gagal"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_pameran_users(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->delete("tour_pameran_users", array("id_tour_pameran" => $pst['id_tour_pameran']));
//      $this->debug($pst, true);
      $id_users = json_decode($pst['id_users']);
      foreach ($id_users as $value) {
        if($value){
          $post[] = array(
            "id_tour_pameran"     => $pst['id_tour_pameran'],
            "id_users"            => $value,
            "create_by_users"     => $users[0]->id_users,
            "create_date"         => date("Y-m-d H:i:s"),
          );
        }
      }
//      $this->debug($post, true);
      if($post){
        if($this->global_models->insert_batch("tour_pameran_users", $post)){
          $kirim = array(
            'status'  => 2,
            'note'    => 'Berhasil'
          );
        }
        else {
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 5,
          'note'    => 'Data kosong'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_master_pameran(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_tour_pameran']){
        $where = " AND A.id_tour_pameran = '{$pst['id_tour_pameran']}'";
      }
      else{
        $where = "";
      }
      
      if($pst['master_pameran'] == 1){
          $where .= "";
      }else{
          $where .= " AND A.status=1 AND (date_format(A.date_end, '%Y-%m') >= date_format(now(), '%Y-%m'))";
      }
      
      if($pst['start'] AND $pst['limit']){
        $limit = " LIMIT {$pst['start']}, {$pst['limit']}";
      }
      if($pst['order']){
        $order = " ORDER BY {$pst['order']}";
      }
      
      $query = "SELECT A.*"
        . " ,count(B.id_users) AS jml"
        . " ,(SELECT SUM(C.nominal) FROM tour_transaksi_pameran AS C WHERE C.id_tour_pameran = A.id_tour_pameran) AS nominal"
        . " FROM tour_pameran AS A"
        . " LEFT JOIN tour_pameran_users AS B ON A.id_tour_pameran = B.id_tour_pameran"
        . " WHERE 1 = 1"
//         . " AND (date_format(A.date_end, '%Y-%m') >= date_format(now(), '%Y-%m'))"
        . " {$where}"
        . " GROUP BY A.id_tour_pameran"
        . " {$order}"
        . " {$limit}"
        . "";
      
      $data = $this->global_models->get_query($query);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_transaksi_pameran(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['start'] AND $pst['limit']){
        $limit = " LIMIT {$pst['start']}, {$pst['limit']}";
      }
      if($pst['order']){
        $order = " ORDER BY {$pst['order']}";
      }
      
      $query = "SELECT A.*, B.name AS users"
        . " FROM tour_transaksi_pameran AS A"
        . " LEFT JOIN users_channel AS B ON A.id_users = B.id_users"
        . " WHERE A.id_tour_pameran = '{$pst['id_tour_pameran']}'"
        . " {$where}"
        . " {$order}"
        . " {$limit}"
        . "";
      
      $data = $this->global_models->get_query($query);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_payment_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $query = "SELECT A.tanggal, A.status, A.nominal, A.id_tour_payment, A.id_users, A.id_users_confirm, A.type, A.note"
        . " ,(SELECT B.no_ttu FROM product_tour_book_payment AS B WHERE B.id_product_tour_book_payment = A.id_product_tour_book_payment) AS no_ttu"
        . " FROM tour_payment AS A"
        . " WHERE A.status IN(0,2)"
        . " ORDER BY tanggal DESC LIMIT {$pst['start']}, {$pst['max']}";
      
      $data = $this->global_models->get_query($query);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function product_tour_book_payment_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $query = "SELECT A.*"
        . " ,(SELECT SUM(IF(C.nominal IS NULL, 0, C.nominal)) FROM product_tour_book_payment AS C WHERE C.id_product_tour_book = A.id_product_tour_book AND (C.tampil IS NULL OR C.tampil = 0) AND C.pos = 1 AND C.status <> 3) AS debit"
        . " ,(SELECT SUM(IF(D.nominal IS NULL, 0, D.nominal)) FROM product_tour_book_payment AS D WHERE D.id_product_tour_book = A.id_product_tour_book AND (D.tampil IS NULL OR D.tampil = 0) AND D.pos = 2 AND D.status <> 3) AS kredit"
        . " ,(SELECT B.kode FROM product_tour_book AS B WHERE B.id_product_tour_book = A.id_product_tour_book) AS code"
        . " FROM product_tour_book_payment AS A"
        . " WHERE A.tanggal BETWEEN '{$pst['mulai']}' AND '{$pst['akhir']}'"
        . " AND A.status IN (2,3,4)"
        . " AND A.tampil IS NULL"
        . " AND A.flag_history < 1 "
        . " ORDER BY tanggal DESC LIMIT {$pst['start']}, {$pst['max']}";
      
      $data = $this->global_models->get_query($query);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function ttu_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $this->debug($pst, true);
      if($pst['id_store']){
        $store = " A.id_store = '{$pst['id_store']}'";
      }
      else{
        $store = " A.id_store = 0";
      }
      $query = "SELECT A.*"
        . " ,(SELECT CONCAT(B.no_ttu,'|',B.status,'|',B.id_product_tour_book_payment) FROM product_tour_book_payment AS B WHERE B.id_ttu = A.id_ttu) AS payment"
        . " FROM ttu AS A"
        . " WHERE A.tanggal BETWEEN '{$pst['mulai']}' AND '{$pst['akhir']}'"
        . " AND {$store}"
        . " ORDER BY tanggal DESC LIMIT {$pst['start']}, {$pst['max']}";
      
      $data = $this->global_models->get_query($query);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function ttu_get_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $this->debug($pst, true);
      $query = "SELECT A.*"
        . " , (SELECT CONCAT(C.kode,'|',C.first_name,'|',C.last_name,'|',C.email,'|',C.telp,'|',C.nominal) FROM inventory AS C WHERE C.id_inventory = A.id_inventory) AS inventory"
        . " FROM product_tour_book_payment AS A"
        . " WHERE A.id_product_tour_book_payment = '{$pst['id']}'"
        . "";
      
      $data = $this->global_models->get_query($query);
      $payment = $this->global_models->get_query("SELECT A.*"
        . " FROM product_tour_book_payment AS A"
        . " WHERE A.id_inventory = '{$data[0]->id_inventory}'"
        . " AND A.id_product_tour_book_payment NOT IN ('{$data[0]->id_product_tour_book_payment}')"
        . " AND A.tampil IS NULL");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'payment' => $payment,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function inventory_void(){
      $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
        $get = $this->global_models->get("inventory", array("id_inventory" => $pst['id_inventory']));
        if($pst['id_users'] == $get[0]->id_users){
           $post = array("status" => 4,
                   "update_by_users" => "{$pst['id_users']}",
                   );
        $this->global_models->update("inventory",array("id_inventory" =>"{$pst['id_inventory']}"),$post);
        $post2 = array("id_inventory"   => "{$pst['id_inventory']}",
                  "id_users"            => "{$pst['id_users']}",
                  "tanggal"             => date("Y-m-d H:i:s"),
                  "note"                => $pst['note'],
                  "create_by_users"     => "{$users[0]->id_users}",
                  "create_date"         => date("Y-m-d H:i:s"),   
        );
        $this->global_models->insert("inventory_void",$post2);             
        
         $post = array("tampil" => 2,
                   "update_by_users" => "{$pst['id_users']}",
                   );
        $this->global_models->update("product_tour_book_payment",array("id_inventory" =>"{$pst['id_inventory']}"),$post);
        
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
        }else{
          $kirim = array(
          'status'  => 3,
          'data'    => $get[0]->id_users."|".$pst['id_users'],    
          'note'    => 'Gagal, Tidak ada akses untuk delete inventory ini'
        );  
      }
    }else{
        $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    
    print json_encode($kirim);
    die;
  }
  
  function inventory_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $this->debug($pst, true);
//      if($pst['id_store']){
//        $store = " A.id_store = '{$pst['id_store']}'";
//      }
//      else{
//        $store = " A.id_store = 0";
//      }
      $query = "SELECT A.*"
        . " ,(SELECT CONCAT(id_product_tour_book_payment,'|',nominal) FROM product_tour_book_payment AS C WHERE C.id_inventory = A.id_inventory AND C.status = 0) AS id_product_tour_book_payment"
        . " FROM inventory AS A"
        . " WHERE A.status != 4 AND A.tanggal BETWEEN '{$pst['mulai']}' AND '{$pst['akhir']}'"
//        . " AND {$store}"
        . " ORDER BY tanggal DESC LIMIT {$pst['start']}, {$pst['max']}";
//      $this->debug($query, true);
      $data = $this->global_models->get_query($query);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function inventory_ttu_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $query = "SELECT B.*"
        . " FROM product_tour_book_payment AS B"
        . " WHERE B.id_inventory = '{$pst['id_inventory']}'"
        . " AND B.pos = 2 AND B.status IN (2,4) AND B.tampil IS NULL"
        . " ORDER BY B.tanggal DESC";
//      $this->debug($query, true);
      $data = $this->global_models->get_query($query);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_transaksi_pameran(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $kirim = array(
        "id_tour_pameran"       => $pst['id_tour_pameran'],
        "id_users"              => $pst['id_users'],
        "name"                  => $pst['name'],
        "no"                    => $pst['no'],
        "tanggal"               => $pst['tanggal'],
        "nominal"               => $pst['nominal'],
        "type"                  => $pst['type'],
        "payment"               => $pst['payment'],
        "status"                => 1,
        "note"                  => $pst['note'],
        "create_by_users"       => $users[0]->id_users,
        "create_date"           => date("Y-m-d H:i:s"),
      );
      $id_tour_transaksi_pameran = $this->global_models->insert("tour_transaksi_pameran", $kirim);
      if($id_tour_transaksi_pameran){
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal',
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_master_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['status']){
        $where = " WHERE A.status = '{$pst['status']}'";
      }
      else{
        $where = " WHERE A.status = '1'";
      }
      
      if($pst['start_date']){
        if($pst['start_date'] > $pst['end_date']){
          $where .= " AND (C.start_date BETWEEN '{$pst['start_date']}' AND '{$pst['start_date']}')";
        }
        else{
          $where .= " AND (C.start_date BETWEEN '{$pst['start_date']}' AND '{$pst['end_date']}')";
        }
      }
      
      if($pst['title']){
        $where .= " AND A.title LIKE '%{$pst['title']}%'";
      }
      
      if($pst['destination']){
        $where .= " AND A.destination LIKE '%{$pst['destination']}%'";
      }
      
      if($pst['landmark']){
        $where .= " AND A.landmark LIKE '%{$pst['landmark']}%'";
      }
      
      if($pst['category_product']){
        $where .= " AND A.category_product = '{$pst['category_product']}'";
      }
      
      if($pst['id_store_region']){
        $where .= " AND A.id_store_region = '{$pst['id_store_region']}'";
      }
      
      if($pst['sub_category']){
        $where .= " AND A.sub_category = '{$pst['sub_category']}'";
      }
      
      if($pst['no_pn']){
        $where .= " AND A.no_pn = '{$pst['no_pn']}'";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,B.title AS store_region"
        . " ,COUNT(C.id_product_tour_information) AS schedule"
        . " ,SUM(CASE WHEN C.status = 1 THEN 1 ELSE 0 END) AS available"
        . " ,SUM(CASE WHEN C.status = 3 THEN 1 ELSE 0 END) AS cancel"
        . " ,SUM(CASE WHEN C.status = 4 THEN 1 ELSE 0 END) AS go"
        . " ,SUM(CASE WHEN C.status = 5 THEN 1 ELSE 0 END) AS push"
        . " FROM product_tour AS A"
        . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region"
        . " LEFT JOIN product_tour_information AS C ON (A.id_product_tour = C.id_product_tour AND C.tampil = 1)"
        . " {$where}"
        . " GROUP BY A.id_product_tour"
        . " ORDER BY A.no_pn ASC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
        
//      $this->debug("SELECT A.*"
//        . " ,B.title AS store_region"
//        . " ,COUNT(C.id_product_tour_information) AS schedule"
//        . " FROM product_tour AS A"
//        . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region"
//        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour = C.id_product_tour"
//        . " {$where}"
//        . " GROUP BY A.id_product_tour"
//        . " ORDER BY A.no_pn ASC"
//        . " LIMIT {$pst['start']}, {$pst['max']}", true);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_master_tour_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['status']){
        $where = " WHERE A.status = '{$pst['status']}'";
      }
      else{
        $where = " WHERE A.status = '1'";
      }
      
      if($pst['title']){
        $where .= " AND A.title LIKE '%{$pst['title']}%'";
      }
      
      if($pst['destination']){
        $where .= " AND A.destination LIKE '%{$pst['destination']}%'";
      }
      
      if($pst['store_region']){
        $where .= " AND A.id_store_region = '%{$pst['store_region']}%'";
      }
      
      if($pst['region']){
        $where .= " AND A.region = '{$pst['region']}'";
      }
      
      if($pst['code']){
        $where = " WHERE A.kode LIKE '%{$pst['code']}%'";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,B.title AS store_region"
        . " FROM tour_fit AS A"
        . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region"
        . " {$where}"
        . " GROUP BY A.id_tour_fit"
        . " ORDER BY A.kode ASC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
        
//      $this->debug("SELECT A.*"
//        . " ,B.title AS store_region"
//        . " ,COUNT(C.id_product_tour_information) AS schedule"
//        . " FROM product_tour AS A"
//        . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region"
//        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour = C.id_product_tour"
//        . " {$where}"
//        . " GROUP BY A.id_product_tour"
//        . " ORDER BY A.no_pn ASC"
//        . " LIMIT {$pst['start']}, {$pst['max']}", true);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_master_add_on_tour_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit = $this->global_models->get_field("tour_fit", "id_tour_fit", array("kode" => $pst['code']));
      if($id_tour_fit){
        $data = $this->global_models->get_query("SELECT A.*"
          . " FROM tour_fit_add_on AS A"
          . " WHERE A.id_tour_fit = '{$id_tour_fit}'"
          . " ");
        if($data){
          $kirim = array(
            'status'  => 2,
            'data'    => $data,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => 'Kosong'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak dikenali'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_book_list_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['status']){
        if($pst['status'] == 234){
          $where .= " AND (A.status = 2 OR A.status = 3 OR A.status = 4)";
        }
        else{
          $where .= " AND A.status = {$pst['status']}";
        }
      }
      
      if($pst['date_start']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['date_start']} 00:00:00' AND '{$pst['date_end']} 23:59:59')";
      }
      
      if($pst['code']){
        $where .= " AND A.kode LIKE '%{$pst['code']}%'";
      }
      
      if($pst['fit_code']){
        $where .= " AND B.kode LIKE '%{$pst['fir_code']}%'";
      }
      
      if($pst['name']){
        $where .= " AND A.name LIKE '%{$pst['name']}%'";
      }
      
      if($pst['email']){
        $where .= " AND A.email LIKE '%{$pst['email']}%'";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,B.kode AS fit_code"
        . " FROM tour_fit_book AS A"
        . " LEFT JOIN tour_fit_schedule AS B ON A.id_tour_fit_schedule = B.id_tour_fit_schedule"
        . " WHERE 1=1"
        . " {$where}"
        . " GROUP BY A.id_tour_fit_book"
        . " ORDER BY A.tanggal DESC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
        
//      $this->debug("SELECT A.*"
//        . " ,B.title AS store_region"
//        . " ,COUNT(C.id_product_tour_information) AS schedule"
//        . " FROM product_tour AS A"
//        . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region"
//        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour = C.id_product_tour"
//        . " {$where}"
//        . " GROUP BY A.id_product_tour"
//        . " ORDER BY A.no_pn ASC"
//        . " LIMIT {$pst['start']}, {$pst['max']}", true);
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => ""
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_book_tour_fit_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,B.kode AS fit_schedule, B.start_date, B.end_date, B.days, B.nights, B.hotel, B.stars, B.remarks, B.desc"
        . " FROM tour_fit_book AS A"
        . " LEFT JOIN tour_fit_schedule AS B ON A.id_tour_fit_schedule = B.id_tour_fit_schedule"
        . " WHERE A.kode = '{$pst['code']}'"
        . " ");
      $pax = $this->global_models->get_query("SELECT A.*"
        . " FROM tour_fit_book_pax AS A"
        . " WHERE A.id_tour_fit_book = '{$data[0]->id_tour_fit_book}'"
        . " AND A.status <> 3"
        . " ");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'pax'     => $pax,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Kode Book Tidak Dikenal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_book_tour_fit_price(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_book = $this->global_models->get_field("tour_fit_book", "id_tour_fit_book", array("kode" => $pst['code']));
      if($id_tour_fit_book){
        if($pst['all'] == 2){
          $all = $this->global_models->get_query("SELECT A.*"
            . " FROM tour_fit_book_price AS A"
            . " WHERE A.status = 1 AND A.id_tour_fit_book = '{$id_tour_fit_book}'");
        }
        else{
          $price = $this->global_models->get_query("SELECT A.*"
            . " FROM tour_fit_book_price AS A"
            . " WHERE A.type = 1 AND A.status = 1 AND A.id_tour_fit_book = '{$id_tour_fit_book}'");
          $discount = $this->global_models->get_query("SELECT A.*"
            . " FROM tour_fit_book_price AS A"
            . " WHERE A.type = 5 AND A.status = 1 AND A.id_tour_fit_book = '{$id_tour_fit_book}'");
        }
        
        $kirim = array(
          'status'  => 2,
          'price'   => $price,
          'discount'=> $discount,
          'all'     => $all,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Kode Book Tidak Dikenal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_master_tour_fit_schedule(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit = $this->global_models->get_field("tour_fit", "id_tour_fit", array("kode" => $pst['fit']));
      if($id_tour_fit){
        
        if($pst['status']){
          $where = " AND A.status = '{$pst['status']}'";
        }
        else{
          $where = " AND (A.status = '1' OR A.status = '2')";
        }
        
        if($pst['tanggal']){
          $where .= " AND ('{$pst['tanggal']}' BETWEEN A.start_date AND A.end_date)";
        }
        
        if($pst['kode']){
          $where .= " AND A.kode LIKE '%{$pst['kode']}%'";
        }
        
        if($pst['stars']){
          $where .= " AND A.stars = '{$pst['stars']}'";
        }
        
        if($pst['status']){
          $where .= " AND A.status = '{$pst['status']}'";
        }
        
        if($pst['hotel']){
          $where .= " AND A.hotel LIKE '%{$pst['hotel']}%'";
        }
//        $this->debug($where, true);
        $data = $this->global_models->get_query("SELECT A.*"
          . " FROM tour_fit_schedule AS A"
          . " WHERE A.id_tour_fit = '{$id_tour_fit}'"
          . " {$where}"
          . " ORDER BY A.start_date ASC"
          . " LIMIT {$pst['start']}, {$pst['max']}");
        if($data){
          $kirim = array(
            'status'  => 2,
            'data'    => $data,
            'note'    => 'Berhasil'
          );
        }
        else {
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 5,
          'note'    => 'FIT Tidak Dikenal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_master_tour_fit_schedule_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_fit = $this->global_models->get("tour_fit_schedule", array("kode" => $pst['code']));
      if($tour_fit){
        $kirim = array(
          'status'  => 2,
          'data'    => $tour_fit,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 5,
          'note'    => 'Code Tidak Dikenal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_pameran_users(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT B.*"
        . " FROM tour_pameran_users AS A"
        . " LEFT JOIN users_channel AS B ON A.id_users = B.id_users"
        . " WHERE A.id_tour_pameran = '{$pst['id_tour_pameran']}'"
        . "");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function pameran_users_get_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.*"
        . " FROM tour_pameran_users AS A"
        . " LEFT JOIN tour_pameran AS B ON A.id_tour_pameran = B.id_tour_pameran"
        . " WHERE A.id_users = '{$pst['id_users']}'"
        . " AND ('".date("Y-m-d")."' BETWEEN B.date_start AND B.date_end)"
        . "");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data[0],
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function count_harga_total($id_product_tour_book){
    $product_tour_customer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $id_product_tour_book, "status <>" => 4));
    $product_tour_information = $this->global_models->get("product_tour_information", array("id_product_tour_information" => $product_tour_customer[0]->id_product_tour_information));
    foreach ($product_tour_customer AS $ptc){
      if($ptc->type == 1){
        
      }
      else if($ptc->type == 2){
        
      }
      else if($ptc->type == 3){
        
      }
      else if($ptc->type == 4){
        
      }
      else if($ptc->type == 5){
        
      }
    }
  }
  
  private function olah_payment_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 6);
    $st_upper = strtoupper($kode_random);
    $kode = "AVP".$st_upper;
    $cek = $this->global_models->get_field("product_tour_book_payment", "id_product_tour_book_payment", array("kode" => $kode));
    if($cek > 0){
      $this->olah_tour_code($kode);
    }
  }
  
  private function olah_code(&$kode, $table, $num = 10){
    $this->load->helper('string');
    $kode_data = random_string('alnum', $num);
    $kode = strtoupper($kode_data);
    $cek = $this->global_models->get_field($table, "id_{$table}", array("kode" => $kode));
    if($cek > 0){
      $this->olah_tour_code($kode, $table);
    }
  }
  
  private function revert_all_payment($id_product_tour_book, $id_users){
    $payment = $this->global_models->get("product_tour_book_payment", array("id_product_tour_book" => $id_product_tour_book));
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
  
  function get_data_lead(){
    $lead = $this->global_models->get("product_tour_book");
//    $sudah = array();
    foreach ($lead AS $ld){
      $id_tour_pax = $this->global_models->get_field("tour_pax", $tour_pax, array("telphone" => $ld->telphone));
      if(!$id_tour_pax){
//        $sudah[] = $ld->telphone;
        $id_tour_pax = $this->global_models->insert("tour_pax", array(
          "first_name"          => $ld->first_name,
          "last_name"           => $ld->last_name,
          "email"               => $ld->email,
          "telphone"            => $ld->telphone,
          "create_by_users"     => $this->session->userdata("id"),
          "create_date"         => date("Y-m-d H:i:s")
        ));
        
        $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $ld->id_users));
        if(!$id_store)
          $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $ld->id_users));
        
        $id_tour_leads = $this->global_models->insert("tour_leads", array(
          "id_tour_pax"         => $id_tour_pax,
          "id_users"            => $ld->id_users,
          "id_store"            => $id_store,
          "tanggal"             => $ld->tanggal,
          "status"              => 3,
          "create_by_users"     => $this->session->userdata("id"),
          "create_date"         => date("Y-m-d H:i:s")
        ));
        
      }
    }
    print "id";die;
  }
    
  function generate_ppn(){
    $book = $this->global_models->get("product_tour_book");
    foreach ($book AS $bk){
      $this->load->model("json/mjson_tour");
      $this->mjson_tour->recalculation_ppn($bk->id_product_tour_book);
    }
    print 'das';die;
  }
    
  function generate_visa(){
    $book = $this->global_models->get("product_tour_customer", array("visa" => 1));
    foreach ($book AS $bk){
      $this->load->model("json/mjson_tour");
      $this->mjson_tour->set_additional_visa($bk->id_product_tour_book, $bk->id_product_tour_information, $bk->first_name." ".$bk->last_name, $bk->type);
    }
    print 'das';die;
  }
  
  function recount_payment($code_book){
    $id_product_tour_book = $this->global_models->get_field("product_tour_book", "id_product_tour_book", array("kode" => $code_book));
    $this->load->model("json/mjson_tour");
    $this->mjson_tour->revert_all_payment($id_product_tour_book, 1);
    $this->mjson_tour->recount_payment($id_product_tour_book, 1);
    print 'das';die;
  }
  
  function get_list_tour_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['start']){
        if($pst['start'] > $pst['end']){
          $where .= " AND (A.tanggal BETWEEN '{$pst['start']} 00:00:00' AND '{$pst['start']} 23:59:59')";
        }
        else{
          $where .= " AND (A.tanggal BETWEEN '{$pst['start']} 00:00:00' AND '{$pst['end']} 23:59:59')";
        }
      }
      if($pst['id_store']){
        $where .= " AND (C.id_store = '{$pst['id_store']}' OR E.id_store = '{$pst['id_store']}')";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,D.title AS store"
        . " ,F.title AS store2"
        . " ,SUM(CASE WHEN (G.pos = 1 AND (G.tampil IS NULL OR G.tampil <> 2)) THEN G.nominal ELSE 0 END) AS kredit"
        . " ,SUM(CASE WHEN (G.pos = 2 AND (G.tampil IS NULL OR G.tampil <> 2)) THEN G.nominal ELSE 0 END) AS debit"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN store_tc AS C ON A.id_users = C.id_users"
        . " LEFT JOIN store AS D ON C.id_store = D.id_store"
        . " LEFT JOIN store_commited AS E ON A.id_users = E.id_users"
        . " LEFT JOIN store AS F ON E.id_store = F.id_store"
        . " LEFT JOIN product_tour_book_payment AS G ON A.id_product_tour_book = G.id_product_tour_book"
        . " WHERE B.kode = '{$pst['code']}'"
		. " {$pst['status']} {$where}"
        . " GROUP BY A.id_product_tour_book");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function adjust_harga_all_in(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      if($data){
        $payment = $this->global_models->get_query("SELECT A.*"
          . " FROM product_tour_book_payment AS A"
          . " WHERE A.id_product_tour_book = '{$data[0]->id_product_tour_book}'"
          . " AND A.tampil IS NULL"
          . " AND (A.status <= 1 OR A.status >= 5)");
        foreach ($payment AS $py){
          $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $py->id_product_tour_book_payment), array("tampil" => 2));
          if($py->pos == 1){
            $pos = 2;
          }
          else{
            $pos = 1;
          }
          $kirim_baru = array(
            "id_product_tour_book"      => $py->id_product_tour_book,
            "id_users"                  => $pst['id_users'],
            "id_currency"               => 2,
            "nominal"                   => $py->nominal,
            "tanggal"                   => date("Y-m-d H:i:s"),
            "pos"                       => $pos,
            "status"                    => $py->status,
            "tampil"                    => 2,
            "note"                      => "All in",
            "create_by_users"           => $users[0]->id_users,
            "create_date"               => date("Y-m-d H:i:s"),
          );
          $this->global_models->insert("product_tour_book_payment", $kirim_baru);
        }
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function update_pax_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $book = $this->global_models->get("product_tour_book", array("kode" => $pst['book_code']));
      $data = $this->global_models->get("product_tour_customer", array("kode" => $pst['code']));
      $this->load->model("json/mjson_tour");
      if($book){
        if($data){
          $kirim = array(
            "first_name"            => $pst['first_name'],
            "last_name"             => $pst['last_name'],
            "tanggal_lahir"         => $pst['tanggal_lahir'],
            "tempat_tanggal_lahir"  => $pst['tempat_lahir'],
            "type"                  => $pst['type'],
            "room"                  => $pst['room'],
            "visa"                  => $pst['visa'],
            "passport"              => $pst['passport'],
            "place_of_issued"       => $pst['place_of_issued'],
            "date_of_issued"        => $pst['date_of_issued'],
            "date_of_expired"       => $pst['date_of_expired'],
            "telphone"              => $pst['telp'],
            "note"                  => $pst['note'],
            "update_by_users"       => $pst['id_users'],
          );
          $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $data[0]->id_product_tour_customer), $kirim);
          
        }
        else{
          $kode = $sort = "";
          $this->olah_code($kode, "product_tour_customer");
          
          if($pst['less_ticket']){
            $this->mjson_tour->set_additional_less_ticket($book[0]->id_product_tour_book, $book[0]->id_product_tour_information, $pst['first_name']." ".$pst['last_name'], $pst['type']);
            $sort = 0;
          }
          if($book[0]->status == 2 OR $book[0]->status == 3){
            $sort_max = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_information" => $book[0]->id_product_tour_information, "sort" => "< 100"));
            $sort = 1 + $sort_max;
          }
          else{
            $sort_max = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_information" => $book[0]->id_product_tour_information));
            $sort = 1 + $sort_max;
          }
          
          $kirim = array(
            "status"                      => 1,
            "kode"                        => $kode,
            "id_product_tour_book"        => $book[0]->id_product_tour_book,
            "id_product_tour_information" => $book[0]->id_product_tour_information,
            "less_ticket"                 => $pst['less_ticket'],
            "sort"                        => $sort,
            "create_date"                 => date("Y-m-d H:i:s"),
            "first_name"            => $pst['first_name'],
            "last_name"             => $pst['last_name'],
            "tanggal_lahir"         => $pst['tanggal_lahir'],
            "tempat_tanggal_lahir"  => $pst['tempat_lahir'],
            "type"                  => $pst['type'],
            "room"                  => $pst['room'],
            "visa"                  => $pst['visa'],
            "passport"              => $pst['passport'],
            "place_of_issued"       => $pst['place_of_issued'],
            "date_of_issued"        => $pst['date_of_issued'],
            "date_of_expired"       => $pst['date_of_expired'],
            "telphone"              => $pst['telp'],
            "note"                  => $pst['note'],
            "create_by_users"       => $pst['id_users'],
          );
          $id_product_tour_cusromer = $this->global_models->insert("product_tour_customer", $kirim);
          if($pst['visa']){
            $this->mjson_tour->set_additional_visa($book[0]->id_product_tour_book, $book[0]->id_product_tour_information, $pst['first_name']." ".$pst['last_name'], $pst['type']);
          }
          $status = 6;
        }
        if(($data[0]->type AND $pst['type'] != $data[0]->type) OR $status == 6){
          $customer = $this->global_models->get_query("SELECT SUM(CASE WHEN type = 1 THEN 1 ELSE 0 END) AS att"
            . " ,SUM(CASE WHEN type = 2 THEN 1 ELSE 0 END) AS ctb"
            . " ,SUM(CASE WHEN type = 3 THEN 1 ELSE 0 END) AS ceb"
            . " ,SUM(CASE WHEN type = 4 THEN 1 ELSE 0 END) AS cnb"
            . " ,SUM(CASE WHEN type = 5 THEN 1 ELSE 0 END) AS sgl"
            . " ,MAX(room) AS rm"
            . " FROM product_tour_customer"
            . " WHERE id_product_tour_book = '{$book[0]->id_product_tour_book}'");
            
//          $this->debug($customer, true);
//          book
          $type_fix = $type[$data[0]->type];
          $update_book = array(
            "room"                  => $customer[0]->rm,
            "adult_triple_twin"     => $customer[0]->att,
            "child_twin_bed"        => $customer[0]->ctb,
            "child_extra_bed"       => $customer[0]->ceb,
            "child_no_bed"          => $customer[0]->cnb,
            "sgl_supp"              => $customer[0]->sgl,
            "update_by_users"       => $pst['id_users'],
          );
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $book[0]->id_product_tour_book), $update_book);
//          payment
          
          $this->mjson_tour->revert_all_payment($book[0]->id_product_tour_book, $pst['id_users']);
          $this->mjson_tour->recount_payment($book[0]->id_product_tour_book, $pst['id_users']);
          $this->mjson_tour->cek_status_book($book[0]->id_product_tour_book, $pst['id_users']);
          $kirim = array(
            'status'  => 4,
            'note'    => 'Update dengan perubahan harga'
          );
//          book
        }
        else{
          $kirim = array(
            'status'  => 2,
            'note'    => 'Update'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Data'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_penjualan_pameran(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['region'])
        $where .= " AND E.sub_category = '{$pst['region']}'";
      if($pst['store'])
        $where .= " AND (BS.id_store = '{$pst['store']}' OR CS.id_store = '{$pst['store']}')";
      if($pst['status']){
        if($pst['status'] == 9)
          $where .= " AND (A.status = '2' OR A.status = '3')";
        else  
          $where .= " AND A.status = '{$pst['status']}'";
      }
      if($pst['code'])
        $where = " AND D.kode LIKE '%{$pst['code']}%'";
//      $this->debug($pst, true);
      $product_tour_book = $this->global_models->get_query("SELECT A.*"
        . " ,BS.title AS bstore, BS.id_store AS bid_store"
        . " ,CS.title AS cstore, CS.id_store AS cid_store"
        . " ,D.kode AS ikode"
        . " ,E.kode AS tkode, E.sub_category, E.title AS tour_name"
        . " ,SUM(CASE WHEN F.pos = 1 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS debit"
        . " ,SUM(CASE WHEN F.pos = 2 AND F.tampil IS NULL THEN F.nominal ELSE 0 END) AS kredit"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN store_tc AS B ON A.id_users = B.id_users"
        . " LEFT JOIN store AS BS ON B.id_store = BS.id_store"
        . " LEFT JOIN store_commited AS C ON A.id_users = C.id_users"
        . " LEFT JOIN store AS CS ON C.id_store = CS.id_store"
        . " LEFT JOIN product_tour_information AS D ON A.id_product_tour_information = D.id_product_tour_information"
        . " LEFT JOIN product_tour AS E ON A.id_product_tour = E.id_product_tour"
        . " LEFT JOIN product_tour_book_payment AS F ON A.id_product_tour_book = F.id_product_tour_book"
        . " WHERE A.id_tour_pameran = '{$pst['id_tour_pameran']}'"
        . " {$where}"
        . " GROUP BY A.id_product_tour_book"
        . " ORDER BY A.tanggal ASC"
      );
      
      if($product_tour_book){
        $kirim = array(
          'status'  => 2,
          'data'    => $product_tour_book,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_master_sub_agent(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_master_sub_agent']){
        $where = " AND A.id_master_sub_agent = '{$pst['id_master_sub_agent']}'";
      }
      else{
        $where = "";
      }
      $data = $this->global_models->get_query("SELECT A.*"
        . " FROM master_sub_agent AS A"
        . " WHERE 1 = 1"
        . " {$where}"
        . "");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_master_sub_agent(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_master_sub_agent']){
        $post = array(
          "id_users"            => $pst['id_users'],
          "name"                => $pst['name'],
          "pic"                 => $pst['pic'],
          "email"               => $pst['email'],
          "telp"                => $pst['telp'],
          "alamat"              => $pst['alamat'],
          "status"              => $pst['status'],
          "update_by_users"     => $users[0]->id_users,
        );
        $id_master_sub_agent = $this->global_models->update("master_sub_agent", array("id_master_sub_agent" => $pst['id_master_sub_agent']), $post);
      }
      else{
        $post = array(
          "id_users"            => $pst['id_users'],
          "name"                => $pst['name'],
          "pic"                 => $pst['pic'],
          "email"               => $pst['email'],
          "telp"                => $pst['telp'],
          "alamat"              => $pst['alamat'],
          "status"              => $pst['status'],
          "create_by_users"     => $users[0]->id_users,
          "create_date"         => date("Y-m-d H:i:s")
        );
        $id_master_sub_agent = $this->global_models->insert("master_sub_agent", $post);
      }
//      $this->debug($post, true);
      if($id_master_sub_agent){
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_master_tour_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['code']){
        $fit = $this->global_models->get("tour_fit", array("kode" => $pst['code']));
        if($fit){
          $post = array(
            "id_users"            => $pst['id_users'],
            "id_store_region"     => $pst['id_store_region'],
            "title"               => $pst['title'],
            "summary"             => $pst['summary'],
            "region"              => $pst['region'],
            "destination"         => $pst['destination'],
            "status"              => $pst['status'],
            "update_by_users"     => $users[0]->id_users,
          );
          $this->global_models->update("tour_fit", array("id_tour_fit" => $fit[0]->id_tour_fit), $post);
          $id_tour_fit = $fit[0]->id_tour_fit;
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => 'Code tidak dikenali'
          );
        }
      }
      else{
        $kode = "";
        $this->olah_code($kode, "tour_fit");
        $post = array(
          "id_users"            => $pst['id_users'],
          "id_store_region"     => $pst['id_store_region'],
          "title"               => $pst['title'],
          "summary"             => $pst['summary'],
          "region"              => $pst['region'],
          "destination"         => $pst['destination'],
          "kode"                => $kode,
          "status"              => $pst['status'],
          "create_by_users"     => $users[0]->id_users,
          "create_date"         => date("Y-m-d H:i:s")
        );
        $id_tour_fit = $this->global_models->insert("tour_fit", $post);
      }
//      $this->debug($post, true);
      if($id_tour_fit){
        $kirim = array(
          'status'  => 2,
          'id'      => $id_tour_fit,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_status_book_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $book = $this->global_models->get("tour_fit_book", array("kode" => $pst['code']));
      if($book){
        $post = array(
          "status"          => $pst['status'],
          "id_users"        => $pst['id_users'],
          "update_by_users" => $users[0]->id_users,
        );
        $this->global_models->update("tour_fit_book", array("id_tour_fit_book" => $book[0]->id_tour_fit_book), $post);
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Kode tidak dikenali'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function duplicate_tour_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $fit = $this->global_models->get("tour_fit", array("kode" => $pst['code']));
      if($fit){
        $schedule = $this->global_models->get("tour_fit_schedule", array("id_tour_fit" => $fit[0]->id_tour_fit, "status" => 1));
        $add_on = $this->global_models->get("tour_fit_add_on", array("id_tour_fit" => $fit[0]->id_tour_fit));
        $kode = "";
        $this->olah_code($kode, "tour_fit");
        $post = array(
          "id_store_region"       => $fit[0]->id_store_region,
          "id_users"              => $pst['id_users'],
          "title"                 => $fit[0]->title,
          "summary"               => $fit[0]->summary,
          "region"                => $fit[0]->region,
          "destination"           => $fit[0]->destination,
          "kode"                  => $kode,
          "status"                => 2,
          "note"                  => $fit[0]->note,
          "create_by_users"       => $users[0]->id_users,
          "create_date"           => date("Y-m-d H:i:s"),
        );
        $id_tour_fit = $this->global_models->insert("tour_fit", $post);
        if($id_tour_fit){
          foreach($add_on AS $ao){
            $post_ao[] = array(
              "id_tour_fit"         => $id_tour_fit,
              "title"               => $ao->title,
              "adult"               => $ao->adult,
              "child"               => $ao->child,
              "create_by_users"     => $users[0]->id_users,
              "create_date"         => date("Y-m-d H:i:s")
            );
          }
          if($post_ao){
            $this->global_models->insert_batch("tour_fit_add_on", $post_ao);
          }
          foreach($schedule AS $sch){
            $kode = "";
            $this->olah_code($kode, "tour_fit_schedule", 6);
            $post_schedule[] = array(
              "id_tour_fit"         => $id_tour_fit,
              "id_users"            => $pst['id_users'],
              "kode"                => $kode,
              "start_date"          => $sch->start_date,
              "end_date"            => $sch->end_date,
              "days"                => $sch->days,
              "nights"              => $sch->nights,
              "hotel"               => $sch->hotel,
              "desc"                => $sch->desc,
              "stars"               => $sch->stars,
              "bfast"               => $sch->bfast,
              "bfast_price"         => $sch->bfast_price,
              "twn"                 => $sch->twn,
              "sgl"                 => $sch->sgl,
              "x_bed"               => $sch->x_bed,
              "remarks"             => $sch->remarks,
              "status"              => 1,
              "note"                => $sch->note,
              "create_by_users"     => $users[0]->id_users,
              "create_date"         => date("Y-m-d H:i:s"),
            );
          }
//          $this->debug($post_schedule, true);
          if($post_schedule){
            $this->global_models->insert_batch("tour_fit_schedule", $post_schedule);
            $kirim = array(
              'status'  => 2,
              'note'    => 'Berhasil'
            );
          }
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => 'Gagal'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code Tidak Diketahui'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function duplicate_tour_fit_schedule(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $fit = $this->global_models->get("tour_fit_schedule", array("kode" => $pst['code']));
      if($fit){
        $kode = "";
        $this->olah_code($kode, "tour_fit_schedule", 6);
        $post = array(
          "id_tour_fit"       => $fit[0]->id_tour_fit,
          "id_users"          => $pst['id_users'],
          "kode"              => $kode,
          "start_date"        => $fit[0]->start_date,
          "end_date"          => $fit[0]->end_date,
          "days"              => $fit[0]->days,
          "nights"            => $fit[0]->nights,
          "hotel"             => $fit[0]->hotel,
          "desc"              => $fit[0]->desc,
          "stars"             => $fit[0]->stars,
          "bfast"             => $fit[0]->bfast,
          "bfast_price"       => $fit[0]->bfast_price,
          "twn"               => $fit[0]->twn,
          "sgl"               => $fit[0]->sgl,
          "x_bed"             => $fit[0]->x_bed,
          "remarks"           => $fit[0]->remarks,
          "status"            => $fit[0]->status,
          "create_by_users"   => $users[0]->id_users,
          "create_date"       => date("Y-m-d H:i:s")
        );
        $id_tour_fit_schedule = $this->global_models->insert("tour_fit_schedule", $post);
        if($id_tour_fit_schedule){
          $kirim = array(
            'status'  => 2,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => 'Gagal'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code Tidak Diketahui'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_payment_book_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $book = $this->global_models->get("tour_fit_book", array("kode" => $pst['code']));
      if($book){
        $post = array(
          "id_tour_fit_book"        => $book[0]->id_tour_fit_book,
          "id_users"                => $pst['id_users'],
          "nomor"                   => $pst['nomor'],
          "nomor_ttu"               => $pst['nomor_ttu'],
          "type"                    => 6,
          "title"                   => "ND {$pst['nomor']}, TTU {$pst['nomor_ttu']}",
          "price"                   => $pst['price'],
          "qty"                     => 1,
          "total"                   => $pst['price'],
          "tanggal"                 => date("Y-m-d H:i:s"),
          "pos"                     => 2,
          "status"                  => 1,
          "create_by_users"         => $users[0]->id_users,
          "create_date"             => date("Y-m-d H:i:s"),
        );
        $id_tour_fit_book_price = $this->global_models->insert("tour_fit_book_price", $post);
        if($id_tour_fit_book_price){
          $kirim = array(
            'status'  => 2,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal Menyimpan'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Kode tidak dikenali'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_book_tour_fit_schedule(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $kode = "";
      $this->olah_code($kode, "tour_fit_book", 6);
      $id_tour_fit_schedule = $this->global_models->get_field("tour_fit_schedule", "id_tour_fit_schedule", array("kode" => $pst['fit_schedule']));
      if($id_tour_fit_schedule){
        $post = array(
          "kode"                  => $kode,
          "id_tour_fit_schedule"  => $id_tour_fit_schedule,
          "id_users"              => $pst['id_users'],
          "id_store"              => $pst['id_store'],
          "tanggal"               => date("Y-m-d H:i:s"),
          "departure"             => $pst['departure'],
          "name"                  => $pst['name'],
          "email"                 => $pst['email'],
          "telp"                  => $pst['telp'],
          "address"               => $pst['address'],
          "status"                => 1,
          "create_by_users"       => $users[0]->id_users,
          "create_date"           => date("Y-m-d H:i:s")
        );
        $id_tour_fit_book = $this->global_models->insert("tour_fit_book", $post);
  //      $this->debug($post, true);
        if($id_tour_fit_book){
          $kirim = array(
            'status'  => 2,
            'code'    => $kode,
            'note'    => 'Berhasil'
          );
        }
        else {
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 5,
          'note'    => 'Kode Tour Tidak Dikenal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_master_tour_fit_schedule(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst["code"]){
        $fit_schedule = $this->global_models->get("tour_fit_schedule", array("kode" => $pst['code']));
        if($fit_schedule){
          $post = array(
            "id_users"          => $pst['id_users'],
            "start_date"        => $pst['start_date'],
            "end_date"          => $pst['end_date'],
            "days"              => $pst['days'],
            "nights"            => $pst['nights'],
            "hotel"             => $pst['hotel'],
            "desc"              => $pst['desc'],
            "stars"             => $pst['stars'],
            "bfast"             => $pst['bfast'],
            "bfast_price"       => $pst['bfast_price'],
            "twn"               => $pst['twn'],
            "sgl"               => $pst['sgl'],
            "x_bed"             => $pst['x_bed'],
            "remarks"           => $pst['remarks'],
            "status"            => $pst['status'],
            "update_by_users"   => $users[0]->id_users,
          );
          $this->global_models->update("tour_fit_schedule", array("id_tour_fit_schedule" => $fit_schedule[0]->id_tour_fit_schedule), $post);
          $id_tour_fit_schedule = $fit_schedule[0]->id_tour_fit_schedule;
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => 'Gagal'
          );
        }
      }
      else{
        $kode = "";
        $this->olah_code($kode, "tour_fit_schedule", 6);
        $id_tour_fit = $this->global_models->get_field("tour_fit", "id_tour_fit", array("kode" => $pst['fit']));
        if($id_tour_fit){
          $post = array(
            "id_tour_fit"       => $id_tour_fit,
            "id_users"          => $pst['id_users'],
            "kode"              => $kode,
            "start_date"        => $pst['start_date'],
            "end_date"          => $pst['end_date'],
            "days"              => $pst['days'],
            "nights"            => $pst['nights'],
            "hotel"             => $pst['hotel'],
            "desc"              => $pst['desc'],
            "stars"             => $pst['stars'],
            "bfast"             => $pst['bfast'],
            "bfast_price"       => $pst['bfast_price'],
            "twn"               => $pst['twn'],
            "sgl"               => $pst['sgl'],
            "x_bed"             => $pst['x_bed'],
            "remarks"           => $pst['remarks'],
            "status"            => $pst['status'],
            "create_by_users"   => $users[0]->id_users,
            "create_date"       => date("Y-m-d H:i:s")
          );
          $id_tour_fit_schedule = $this->global_models->insert("tour_fit_schedule", $post);
    //      $this->debug($post, true);
        }
        else {
          $kirim = array(
            'status'  => 5,
            'note'    => 'Code FIT Tidak Dikenali'
          );
        }
      }
      if($id_tour_fit_schedule){
        $kirim = array(
          'status'  => 2,
          'id'      => $id_tour_fit_schedule,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_book_tour_fit_pax(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $kode = "";
      $this->olah_code($kode, "tour_fit_book_pax", 10);
      $id_tour_fit_book = $this->global_models->get_field("tour_fit_book", "id_tour_fit_book", array("kode" => $pst['book']));
      if($id_tour_fit_book){
        if($pst["code"]){
          $post = array(
            "id_tour_fit_book"  => $id_tour_fit_book,
            "id_users"          => $pst['id_users'],
            "title"             => $pst['title'],
            "type"              => $pst['type'],
            "pax_type"          => $pst['pax_type'],
            "first_name"        => $pst['first_name'],
            "last_name"         => $pst['last_name'],
            "telp"              => $pst['telp'],
            "email"             => $pst['email'],
            "tempat_lahir"      => $pst['tempat_lahir'],
            "tanggal_lahir"     => $pst['tanggal_lahir'],
            "passport"          => $pst['passport'],
            "tempat_passport"   => $pst['tempat_passport'],
            "tanggal_passport"  => $pst['tanggal_passport'],
            "expired_passport"  => $pst['expired_passport'],
            "note"              => $pst['note'],
            "update_by_users"   => $users[0]->id_users,
          );
          $id_tour_fit_book_pax = $this->global_models->update("tour_fit_book_pax", array("kode" => $pst['code']), $post);
        }
        else{
          $post = array(
            "id_tour_fit_book"  => $id_tour_fit_book,
            "id_users"          => $pst['id_users'],
            "kode"              => $kode,
            "title"             => $pst['title'],
            "type"              => $pst['type'],
            "pax_type"          => $pst['pax_type'],
            "first_name"        => $pst['first_name'],
            "last_name"         => $pst['last_name'],
            "telp"              => $pst['telp'],
            "email"             => $pst['email'],
            "tempat_lahir"      => $pst['tempat_lahir'],
            "tanggal_lahir"     => $pst['tanggal_lahir'],
            "passport"          => $pst['passport'],
            "tempat_passport"   => $pst['tempat_passport'],
            "tanggal_passport"  => $pst['tanggal_passport'],
            "expired_passport"  => $pst['expired_passport'],
            "note"              => $pst['note'],
            "status"            => 1,
            "create_by_users"   => $users[0]->id_users,
            "create_date"       => date("Y-m-d H:i:s")
          );
          $id_tour_fit_book_pax = $this->global_models->insert("tour_fit_book_pax", $post);
        }
        
//        $this->load->model("json/mjson_tour");
//        $id_tour_fit_book_price = $this->set_price_tour_fit($pst['type'], $id_tour_fit_book);
  //      $this->debug($post, true);
        if($id_tour_fit_book_pax){
          $kirim = array(
            'status'  => 2,
            'code'    => $kode,
            'note'    => 'Berhasil'
          );
        }
        else {
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 5,
          'note'    => 'Code Book Tidak Dikenali'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_master_tour_fit_add_on(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->delete("tour_fit_add_on", array("id_tour_fit" => $pst['id']));
      $title = json_decode($pst['title']);
      $adult = json_decode($pst['adult']);
      $child = json_decode($pst['child']);
      foreach ($title AS $key => $tit){
        if($tit){
          $post[] = array(
            "id_tour_fit"         => $pst['id'],
            "title"               => $tit,
            "adult"               => trim(str_replace(",", "",str_replace("Rp ","",$adult[$key]))),
            "child"               => trim(str_replace(",", "",str_replace("Rp ","",$child[$key]))),
            "create_by_users"     => $users[0]->id_users,
            "create_date"         => date("Y-m-d H:i:s")
          );
        }
      }
//      $this->debug($post, true);
      if($post){
        $this->global_models->insert_batch("tour_fit_add_on", $post);
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_all_store_region(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get("store_region");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function get_product_search(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      if($pst['start_date']){
        $start = $pst['start_date'];
        if($pst['end_date'] AND $pst['end_date'] > $pst['start_date']){
          $end = $pst['end_date'];
        }
        else{
          $end = $pst['start_date'];
        }
      }
      else{
        $start = date("Y-m-d");
        $end = date("Y-m-t");
      }
      
      $where .= " AND (B.start_date BETWEEN '{$start}' AND '{$end}')";
      
      if($pst['title']){
        $where .= " AND LOWER(A.title) LIKE '%".strtolower($pst['title'])."%'";
      }
      
      if($pst['destination']){
        $where .= " AND LOWER(A.destination) LIKE '%".strtolower($pst['destination'])."%'";
      }
      
      if($pst['landmark']){
        $where .= " AND LOWER(A.landmark) LIKE '%".strtolower($pst['landmark'])."%'";
      }
      
      if($pst['category_product']){
        $where .= " AND A.category_product = '{$pst['category_product']}'";
      }
      
      if($pst['id_store_region']){
        $where .= " AND A.id_store_region = '{$pst['id_store_region']}'";
      }
      
      if($pst['sub_category']){
        $where .= " AND A.sub_category = '{$pst['sub_category']}'";
      }
      
      if($pst['status']){
        if($pst['status'] == 9){
          $where .= " AND (B.status = '5' OR B.status = '1')";
        }
        else{
          $where .= " AND B.status = '{$pst['status']}'";
        }
      }
      else{
        $where .= " AND B.status = '5'";
      }
      
      if($pst['code']){
        $where = " AND B.kode = '{$pst['code']}'";
      }
      if($pst['no_pn']){
        $where = " AND A.no_pn = '{$pst['no_pn']}'";
      }
      
//      $this->debug($pst, true);
      $data = $this->global_models->get_query("SELECT A.id_product_tour, A.id_store_region, A.title, A.sub_category, A.destination, A.division, A.landmark, A.kode, A.no_pn, A.push_selling, A.days, A.status, A.category_product"
        . " ,B.start_date, B.end_date, B.status AS status_info, B.available_seat, B.adult_triple_twin, B.airport_tax, B.flt, B.kode AS kode_info,B.at_airport_date"
        . " ,SUM(CASE WHEN C.status = 1 THEN 1 ELSE 0 END) AS book"
        . " ,SUM(CASE WHEN (C.status = 2 OR C.status = 3) THEN 1 ELSE 0 END) AS conf"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_customer AS C ON B.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.status = 1 AND B.tampil = 1"
        . " {$where}"
        . " GROUP BY B.id_product_tour_information"
        . " ORDER BY B.start_date ASC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function get_product_fit_search(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $where = " WHERE A.status = 1 AND B.status = 1";
      if($pst['date_start']){
        $where .= " AND (A.start_date BETWEEN '{$pst['date_start']}' AND '{$pst['date_start']}')";
      }
      
      if($pst['title']){
        $where .= " AND A.title LIKE '%{$pst['title']}%'";
      }
      
      if($pst['hotel']){
        $where .= " AND A.hotel LIKE '%{$pst['hotel']}%'";
      }
      
      if($pst['id_store_region']){
        $where .= " AND B.id_store_region = '{$pst['id_store_region']}'";
      }
      
      if($pst['kode']){
        $where .= " AND A.kode LIKE '%{$pst['kode']}%'";
      }
      
      if($pst['region']){
        $where .= " AND A.region = '{$pst['region']}'";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,B.title"
        . " FROM tour_fit_schedule AS A"
        . " LEFT JOIN tour_fit AS B ON A.id_tour_fit = B.id_tour_fit"
        . " {$where}"
        . " ORDER BY A.start_date ASC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function get_book_tour_fit(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,C.title AS fit"
        . " ,B.hotel, B.stars"
        . " FROM tour_fit_book AS A"
        . " LEFT JOIN tour_fit_schedule AS B ON A.id_tour_fit_schedule = B.id_tour_fit_schedule"
        . " LEFT JOIN tour_fit AS C ON B.id_tour_fit = C.id_tour_fit"
        . " {$where}"
        . " ORDER BY A.tanggal ASC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function get_tour_information_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->get_query("SELECT A.flt, A.sts, A.in, A.out, A.keberangkatan"
        . " ,B.destination, B.landmark"
        . " ,C.title AS store_region"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN store_region AS C ON B.id_store_region = C.id_store_region"
        . " WHERE A.kode = '{$pst['code']}'");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function get_tour_information_harga(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->get_query("SELECT A.kode, A.adult_triple_twin, A.child_twin_bed, A.child_extra_bed, A.child_no_bed, A.sgl_supp, A.airport_tax"
        . " FROM product_tour_information AS A"
        . " WHERE A.kode = '{$pst['code']}'");
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Data Tidak Ada"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function master_tour_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      if($pst['id_store_region']){
        $id_store_region = " AND C.id_store_region = '{$pst['id_store_region']}'";
        }else{
        $id_store_region = "";
        }
        
         if(date("Y") == $pst['year']){
            $dt_date = "(A.start_date BETWEEN '".date("Y-m-d")."' AND '{$pst['year']}-12-31')";
        }else{
            $dt_date = "(A.start_date BETWEEN '{$pst['year']}-1-31' AND '{$pst['year']}-12-31')";
        }
        
      $data = $this->global_models->get_query("SELECT"
        . " A.id_product_tour_information,A.id_product_tour,A.keberangkatan,A.kode_ps,A.start_date,A.end_date,A.start_time,A.end_time"
        . " ,A.available_seat,A.adult_triple_twin,A.child_twin_bed,A.child_extra_bed,A.child_no_bed,A.sgl_supp,A.airport_tax"
        . " ,A.flt,A.sts,A.in,A.out,A.status,A.seat_update,A.kode,A.status,A.pax_book"
        . " ,C.title,C.days,C.sub_category,C.category_product,C.no_pn,C.push_selling,C.kode AS kode2,A.at_airport,A.at_airport_date"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS C ON (A.id_product_tour = C.id_product_tour)"
        . " WHERE A.tampil = 1 $id_store_region AND C.sub_category = '{$pst['sub_category']}'"
        . " AND {$dt_date}"
        . " ORDER BY C.title ASC,C.days ASC,C.push_selling ASC,A.start_date ASC"
//        . " ORDER BY C.no_pn ASC, A.start_date ASC, C.days ASC, C.title ASC, A.adult_triple_twin ASC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
        
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_series_open(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['status']){
        $status = " AND A.status IN ({$pst['status']})";
      }
      else{
        $status = " AND A.status IN (1,5)";
      }
      $data = $this->global_models->get_query("SELECT"
        . " C.title"
        . " ,A.start_date, A.seat_update, A.flt, A.kode, A.id_product_tour_information"
        . " ,(SELECT COUNT(B.id_product_tour_book) FROM product_tour_customer AS B WHERE B.id_product_tour_information = A.id_product_tour_information AND B.status IN (2,3,6,7)) AS tpax"
        . " ,(SELECT CONCAT(D.first_name, ' ', D.last_name) FROM product_tour_leader AS D WHERE D.id_product_tour_information = A.id_product_tour_information) AS leader"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS C ON (A.id_product_tour = C.id_product_tour)"
        . " WHERE C.status = 1 {$status}"
        . " AND (A.start_date BETWEEN '{$pst['awal']}' AND '{$pst['akhir']}')"
        . " ORDER BY A.seat_update DESC, A.start_date ASC"
        . " LIMIT {$pst['start']}, {$pst['max']}");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_series_info_book_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.id_store"
            . " ,(SELECT CONCAT(COUNT(B.id_product_tour_customer),'|',(C.adult_triple_twin * C.harga_adult_triple_twin),'|',(C.child_twin_bed * C.harga_child_twin_bed),'|',(C.child_extra_bed * C.harga_child_extra_bed),'|',(C.child_no_bed * C.harga_child_no_bed),'|',(C.sgl_supp * (C.harga_adult_triple_twin + harga_single_adult)))"
            . " FROM product_tour_customer AS B "
            . " LEFT JOIN product_tour_book AS C ON B.id_product_tour_book = C.id_product_tour_book WHERE B.id_product_tour_information = '{$pst['id_product_tour_information']}' AND C.id_store = A.id_store AND B.status IN (2,3,6,7)) AS tpax"
            . " FROM store AS A"
            . " ORDER BY A.sort ASC");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_series_ttu_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_tour_pameran'])
        $where .= " AND A.id_tour_pameran = '{$pst['id_tour_pameran']}'";
      if($pst['id_store'])
        $where .= " AND B.id_store = '{$pst['id_store']}'";
        
      if($pst['max']){
        $limit = "LIMIT {$pst['start']}, {$pst['max']}";
      }
      
      $data = $this->global_models->get_query("SELECT A.tanggal, A.no_ttu, A.no_deposit, A.nominal AS total, A.id_users, A.remark"
        . " ,D.nominal, D.id_users_confirm, D.type, D.id_tour_payment"
        . " ,CONCAT(B.kode,'|', '-','|', B.first_name,'|', B.last_name) AS book"
        . " ,(SELECT C.title FROM store AS C WHERE C.id_store = B.id_store) AS store"
        . " FROM product_tour_book_payment AS A"
        . " LEFT JOIN product_tour_book AS B ON B.id_product_tour_book = A.id_product_tour_book"
        . " LEFT JOIN tour_payment AS D ON (D.id_product_tour_book_payment = A.id_product_tour_book_payment AND (D.status IN (0,2) OR D.status IS NULL))"
        . " WHERE (A.tanggal BETWEEN '{$pst['awal']} 00:00:00' AND '{$pst['akhir']} 23:59:59')"
        . " AND A.status IN (2,4)"
        . " {$where}"
        . " ORDER BY A.tanggal ASC, D.type DESC {$limit}");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_series_cashier_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_tour_pameran'])
        $where .= " AND B.id_tour_pameran = '{$pst['id_tour_pameran']}'";
      if($pst['id_store'])
        $where .= " AND B.id_store = '{$pst['id_store']}'";
        
      if($pst['max']){
        $limit = "LIMIT {$pst['start']}, {$pst['max']}";
      }
      
       if(!empty($pst['awal']) AND !empty($pst['akhir'])){
          $where .= " AND (A.tanggal BETWEEN '{$pst['awal']} 00:00:00' AND '{$pst['akhir']} 23:59:59')";
      }
      if($pst['type']){
        $where .= " AND A.type = '{$pst['type']}'";
      }
      
      if($pst['jenis']){
        $where .= " AND B.type = '{$pst['jenis']}'";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,B.id_inventory, B.id_users, B.id_product_tour_book, B.no_ttu, B.id_tour_pameran,B.id_product_tour_book, B.id_store, B.type AS jenis"
        . " ,(SELECT C.kode FROM product_tour_book AS C WHERE C.id_product_tour_book = B.id_product_tour_book) AS tour"
        . " ,(SELECT D.kode FROM inventory AS D WHERE D.id_inventory = B.id_inventory) AS inventory"
        . " ,(SELECT concat(F.first_name,' ',F.last_name) FROM inventory AS F WHERE F.id_inventory = B.id_inventory) AS pemesan"
        . " ,(SELECT concat(G.first_name,' ',G.last_name) FROM product_tour_book AS G WHERE G.id_product_tour_book = B.id_product_tour_book) AS book_pemesan"      
        . " ,(SELECT E.title FROM store AS E WHERE E.id_store = B.id_store) AS store"
        . " FROM tour_payment AS A"
        . " LEFT JOIN product_tour_book_payment AS B ON A.id_product_tour_book_payment = B.id_product_tour_book_payment"
        . " WHERE 1=1 "
        . " AND A.status IN (2)"
        . " {$where}"
        . " ORDER BY A.tanggal ASC, A.type DESC {$limit}");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_pameran_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.*"
        . " FROM tour_pameran AS A"
        . " WHERE A.status=1 "
        . " ORDER BY A.date_start DESC");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_payment_get_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get_query("SELECT A.nominal, A.id_product_tour_book, A.id_product_tour_book_payment, A.no_ttu, A.remark, A.id_inventory,A.id_tour_pameran, A.type"
        . " ,(SELECT CONCAT("
          . "B.kode,'|',"
          . "B.first_name,'|',"
          . "B.last_name,'|',"
          . "B.email,'|',"
          . "B.telphone,'|',"
          . "C.title,'|',"
          . "D.keberangkatan,'|',"
          . "D.start_date"
        . ") FROM product_tour_book AS B "
          . "LEFT JOIN product_tour AS C ON C.id_product_tour = B.id_product_tour "
          . "LEFT JOIN product_tour_information AS D ON D.id_product_tour_information = B.id_product_tour_information "
          . " WHERE B.id_product_tour_book = A.id_product_tour_book) AS book"
        . " FROM product_tour_book_payment AS A"
        . " WHERE A.id_product_tour_book_payment = '{$pst['id']}'"
        . "");
      $data_all = $this->global_models->get_query("SELECT A.*"
            . " FROM product_tour_book_payment AS A"
            . " WHERE A.id_product_tour_book = '{$data[0]->id_product_tour_book}'"
            . " AND (A.tampil IS NULL OR A.tampil <> 2) AND A.id_product_tour_book_payment <> '{$data[0]->id_product_tour_book_payment}'"
            . "");
      if(!$data_all){
        $data_all = $this->global_models->get_query("SELECT A.*"
            . " FROM product_tour_book_payment AS A"
            . " WHERE A.id_inventory = '{$data[0]->id_inventory}'"
            . " AND (A.tampil IS NULL OR A.tampil <> 2) AND A.id_product_tour_book_payment <> '{$data[0]->id_product_tour_book_payment}'"
            . "");
      }
      $data_pax = $this->global_models->get_query(""
        . "SELECT A.first_name, A.last_name, A.tanggal_lahir, A.type, A.room"
        . " FROM product_tour_customer AS A"
        . " WHERE A.id_product_tour_book = '{$data[0]->id_product_tour_book}'"
        . "");
      $data_payment = $this->global_models->get_query(""
        . "SELECT A.id_tour_payment, A.id_users, id_users_confirm, A.tanggal, A.type, A.nominal, A.status, A.remarks, A.mdr, A.number"
        . " FROM tour_payment AS A"
        . " WHERE A.id_product_tour_book_payment = '{$pst['id']}' AND A.status IN(0,2)"
        . "");
      $data_ttu = $this->global_models->get_query(""
        . "SELECT A.*"
        . " FROM inventory AS A"
        . " WHERE A.id_inventory = '{$data[0]->id_inventory}'"
        . "");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => array(
            "payment"       => $data,
            "all"           => $data_all,
            "pax"           => $data_pax,
            "payment_list"  => $data_payment,
            "ttu"           => $data_ttu,
          ),
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function inventory_get_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $inventory = $this->global_models->get_query("SELECT A.*"
        . " FROM inventory AS A"
        . " WHERE A.id_inventory = '{$pst['id_inventory']}'"
        . " ");
        
      $ttu_data = $this->global_models->get_query("SELECT A.*"
        . " FROM product_tour_book_payment AS A"
        . " WHERE A.id_inventory = '{$pst['id_inventory']}' AND pos=2"
        . " ");
        
      foreach($ttu_data AS $tu){
        $payment = $this->global_models->get_query("SELECT A.*"
        . " FROM tour_payment AS A"
        . " WHERE A.id_product_tour_book_payment = '{$tu->id_product_tour_book_payment}' AND A.status = 2"
        . " ");
        $ttu[] = array(
//          "ttu"       => $tu,
          "payment"   => $payment,
        );
      }
      
      if($inventory){
        $kirim = array(
          'status'  => 2,
          'data'    => array(
            "inventory"     => $inventory,
            "ttu"           => $ttu,
          ),
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_series_cancel(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->update("product_tour_information", array("kode" => $pst['code']), array("status" => 3));
      $code = $this->global_models->get_query("SELECT A.id_product_tour, A.start_date, A.end_date"
        . " ,(SELECT B.kode FROM product_tour AS B WHERE B.id_product_tour = A.id_product_tour) AS code"
        . " ,(SELECT B.title FROM product_tour AS B WHERE B.id_product_tour = A.id_product_tour) AS title"
        . " FROM product_tour_information AS A"
        . " WHERE A.kode = '{$pst['code']}'"
        . "");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
          'code'    => $code[0]->code,
          'detail'  => $code[0]
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
    
  function tour_series_close(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $data = $this->global_models->update("product_tour_information", array("kode" => $pst['code']), array("status" => 4));
      $code = $this->global_models->get_query("SELECT A.id_product_tour"
        . " ,(SELECT B.kode FROM product_tour AS B WHERE B.id_product_tour = A.id_product_tour) AS code"
        . " FROM product_tour_information AS A"
        . " WHERE A.kode = '{$pst['code']}'"
        . "");
        
      if($data){
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
          'code'    => $code[0]->code
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_payment_set(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_detail']){
        
      }
      else{
        $type       = json_decode($pst['type']);
        $nominal    = json_decode($pst['nominal']);
        $mdr        = json_decode($pst['mdr']);
        $number     = json_decode($pst['number']);
        $tanggal    = json_decode($pst['tanggal']);
        $remark     = json_decode($pst['remark']);
        $id_users_confirm = ($pst['status'] == 2 ? $pst['id_confirm'] : NULL);
        
        foreach($nominal AS $key => $nom){
          if($nom){
            $post[] = array(
              "id_product_tour_book_payment"    => $pst['id'],
              "id_users"                        => $pst['id_create'],
              "id_users_confirm"                => $id_users_confirm,
              "type"                            => $type[$key],
              "mdr"                             => $mdr[$key],
              "number"                          => $number[$key],
              "tanggal"                         => $tanggal[$key],
              "remarks"                         => $remark[$key],
              "nominal"                         => str_replace(",", "", $nom),
//              "tanggal"                         => $pst['tanggal'],
              "status"                          => $pst['status'],
              "note"                            => $pst['note'],
              "create_by_users"                 => $users[0]->id_users,
              "create_date"                     => date("Y-m-d H:i:s"),
            );
          }
        }
        if($post)
          $data = $this->global_models->insert_batch("tour_payment", $post);
      }
      
      if($data){
        $total_ttu = $this->global_models->get_query("SELECT A.nominal,A.id_inventory"
          . " FROM product_tour_book_payment AS A"
          . " WHERE A.id_product_tour_book_payment = '{$pst['id']}'");
        
        $total_kredit = $this->global_models->get_query("SELECT A.nominal,A.id_inventory"
          . " FROM product_tour_book_payment AS A"
          . " WHERE A.id_inventory = '{$total_ttu[0]->id_inventory}' AND A.status=0 AND A.pos=1");  
          
        $total_pay = $this->global_models->get_query("SELECT SUM(A.nominal) AS nominal"
          . " FROM tour_payment AS A"
          . " WHERE A.id_product_tour_book_payment = '{$pst['id']}' AND A.status = 2");
//         $pay = $this->db->last_query();
        if($total_pay[0]->nominal >= $total_ttu[0]->nominal){
          $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id']), array("status" => 4));
        }
        
        if($total_pay[0]->nominal >= $total_kredit[0]->nominal){
            $this->global_models->update("inventory", array("id_inventory" => "{$total_ttu[0]->id_inventory}"), array("status" =>3));
        }else{
            if($total_pay[0]->nominal > 0){
              $this->global_models->update("inventory", array("id_inventory" => "{$total_ttu[0]->id_inventory}"), array("status" =>2));  
            }else{
              $this->global_models->update("inventory", array("id_inventory" => "{$total_ttu[0]->id_inventory}"), array("status" =>1));  
            }
        }
        
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => "Gagal"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_payment_void(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_tour_payment']){
          $note = trim($pst['note']);
        if($note){
        $payment = array(
          "status"            => 3,
          "update_by_users"   => $users[0]->id_users
        );
        $this->global_models->update("tour_payment", array("id_tour_payment" => $pst['id_tour_payment']), $payment);
        
        $kirim = array(
          "id_tour_payment"     => $pst['id_tour_payment'],
          "id_users"            => $pst['id_users'],
          "tanggal"             => date("Y-m-d H:i:s"),
          "note"                => $pst['note'],
          "update_by_users"     => $users[0]->id_users,
          "create_date"         => date("Y-m-d H:i:s"),
        );
        
        $this->global_models->insert("tour_payment_void", $kirim);
        
        $id_product_tour_book_payment = $this->global_models->get_field("tour_payment", "id_product_tour_book_payment", array("id_tour_payment" => $pst['id_tour_payment']));
        
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $id_product_tour_book_payment), array("status" => 2));
        
        $total_ttu = $this->global_models->get_query("SELECT A.nominal,A.id_inventory"
          . " FROM product_tour_book_payment AS A"
          . " WHERE A.id_product_tour_book_payment = '{$id_product_tour_book_payment}'");
        
        $total_kredit = $this->global_models->get_query("SELECT A.nominal,A.id_inventory"
          . " FROM product_tour_book_payment AS A"
          . " WHERE A.id_inventory = '{$total_ttu[0]->id_inventory}' AND A.status=0 AND A.pos=1");  
          
        $total_pay = $this->global_models->get_query("SELECT SUM(A.nominal) AS nominal"
          . " FROM tour_payment AS A"
          . " WHERE A.id_product_tour_book_payment = '{$id_product_tour_book_payment}' AND A.status = 2");
         
        if($total_pay[0]->nominal >= $total_ttu[0]->nominal){
          $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id']), array("status" => 4));
        }else{
          $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id']), array("status" => 2));  
        }
        
        if($total_pay[0]->nominal >= $total_kredit[0]->nominal){
            $this->global_models->update("inventory", array("id_inventory" => "{$total_ttu[0]->id_inventory}"), array("status" =>3));
        }else{
            if($total_pay[0]->nominal > 0){
              $this->global_models->update("inventory", array("id_inventory" => "{$total_ttu[0]->id_inventory}"), array("status" =>2));  
            }else{
              $this->global_models->update("inventory", array("id_inventory" => "{$total_ttu[0]->id_inventory}"), array("status" =>1));  
            }
        }
        
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
//          'data'    => $this->db->last_query(),
//          'data1'   => $d_pay  
        );
        }else{
           $kirim = array(
          'status'  => 5,
          'note'    => 'Gagal, Note harus di isi',
        ); 
        }
        
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => "Gagal"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function ttu_void(){
      $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
        $get = $this->global_models->get("product_tour_book_payment", array("id_product_tour_book_payment" => $pst['id']));
        if($pst['id_users'] == $get[0]->id_users){
                  
         $post = array("tampil" => 2,
                   "update_by_users" => "{$pst['id_users']}",
                   );
        $this->global_models->update("product_tour_book_payment",array("id_product_tour_book_payment" =>"{$pst['id']}"),$post);
        
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
        }else{
          $kirim = array(
          'status'  => 3,
          'data'    => $get[0]->id_users."|".$pst['id_users'],    
          'note'    => 'Gagal, Tidak ada akses untuk delete inventory ini'
        );  
      }
    }else{
        $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    
    print json_encode($kirim);
    die;
  }
  
  function ttu_set(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $pst['type'] = ($pst['type'] ? $pst['type'] : $this->global_models->get_field("inventory", "type", array("id_inventory" => $pst['id_inventory'])));
//      if($pst['id_detail']){
//        
//      }
//      else{
//        $post = array(
//          "id_users"            => $pst['id_users'],
//          "id_tour_pameran"     => $pst['id_tour_pameran'],
//          "id_store"            => $pst['id_store'],
//          
//          "type"                => $pst['type'],
//          "nominal"             => $pst['nominal'],
//          "tanggal"             => $pst['tanggal'],
//          "note"                => $pst['note'],
//          "remark"              => $pst['remark'],
//          "create_by_users"     => $users[0]->id_users,
//          "create_date"         => date("Y-m-d H:i:s")
//        );
//        $id_ttu = $this->global_models->insert("ttu", $post);
//      }
      
//      if($id_ttu){
        $no_ttu = $this->generate_ttu($pst['id_tour_pameran'], $pst['id_store'], $pst['type']);
        if($pst['id_users']){
	        $post2[] = array(
          "id_users"            => $pst['id_users'],
          "id_tour_pameran"     => $pst['id_tour_pameran'],
          "id_inventory"        => $pst['id_inventory'],
          "id_store"            => $pst['id_store'],
          "type"                => $pst['type'],
          "no_ttu"              => $no_ttu['ttu'],
          "no"                  => $no_ttu['no'],
          "kode"                => $pst['code'],
          "nominal"             => $pst['nominal'],
          "tanggal"             => date("Y-m-d H:i:s"),
          "pos"                 => 2,
          "pajak"               => 2,
          "status"              => 2,
          "note"                => "Nominal Payment dari Inventory TTU: ".$no_ttu['ttu'],
          "remark"              => $pst['remark'],
          "create_by_users"     => $users[0]->id_users,
          "create_date"         => date("Y-m-d H:i:s")
        );
        if(!$pst['khusus']){
          $post2[] = array(
            "id_users"            => $pst['id_users'],
            "id_tour_pameran"     => $pst['id_tour_pameran'],
            "id_inventory"        => $pst['id_inventory'],
            "id_store"            => $pst['id_store'],
            "type"                => $pst['type'],
            "no_ttu"              => $no_ttu['ttu'],
            "no"                  => $no_ttu['no'],
            "kode"                => $pst['code'],
            "nominal"             => $pst['harga'],
            "tanggal"             => date("Y-m-d H:i:s"),
            "pos"                 => 1,
            "pajak"               => 2,
            "status"              => 0,
            "note"                => "Nominal Payment dari Inventory ".$pst['harga'],
            "remark"              => $pst['remark'],
            "create_by_users"     => $users[0]->id_users,
            "create_date"         => date("Y-m-d H:i:s")
          );
        }
        $id_product_tour_book_payment = $this->global_models->insert_batch("product_tour_book_payment", $post2);
        
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
        );
        
        }else{
	      $kirim = array(
          'status'  => 5,
          'note'    => 'gagal,Session users Habis',
          );
        }
        
//      }
//      else {
//        $kirim = array(
//          'status'  => 3,
//          'note'    => "Gagal"
//        );
//      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function inventory_set(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_detail']){
        
      }
      else{
	   if($pst['id_users']){
        $post = array(
          "id_users"            => $pst['id_users'],
          "first_name"          => $pst['first_name'],
          "last_name"           => $pst['last_name'],
          "title"               => $pst['title'],
          "kode"                => $pst['code'],
          "email"               => $pst['email'],
          "telp"                => $pst['telp'],
          "alamat"              => $pst['alamat'],
          "type"                => $pst['type'],
          "nominal"             => $pst['nominal'],
          "tanggal"             => $pst['tanggal'],
          "note"                => $pst['note'],
          "status"              => $pst['status'],
          "create_by_users"     => $users[0]->id_users,
          "create_date"         => date("Y-m-d H:i:s")
        );
        $id_inventory = $this->global_models->insert("inventory", $post);
        }else{
           $kirim = array(
          'status'  => 5,
          'note'    => 'Gagal,Session Users Habis, harap Login Ulang',
        );
       }  
      }
      
      if($id_inventory){
        
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
          'id_inventory'    => $id_inventory,
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => "Gagal"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_payment_status(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $post = array(
        "id_users_confirm"        => $pst['id_users'],
        "status"                  => $pst['status'],
        "update_by_users"         => $users[0]->id_users,
      );
      $data = $this->global_models->update("tour_payment", array("id_tour_payment" => $pst['id']), $post);
      
      $total_ttu = $this->global_models->get_query("SELECT A.id_product_tour_book_payment"
        . " (SELECT B.nominal FROM product_tour_book_payment AS B WHERE B.id_product_tour_book_payment = A.id_product_tour_book_payment) AS nominal"
        . " FROM tour_payment AS A"
        . " WHERE A.id_tour_payment = '{$pst['id']}' AND A.status = 2");
      $total_pay = $this->global_models->get_query("SELECT SUM(A.nominal) AS nominal"
        . " FROM tour_payment AS A"
        . " WHERE A.id_product_tour_book_payment = '{$total_ttu[0]->id_product_tour_book_payment}' AND A.status = 2");
        
      if($total_pay[0]->nominal >= $total_ttu[0]->nominal){
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $total_ttu[0]->id_product_tour_book_payment), array("status" => 4));
      }
      
      if($data){
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => "Gagal"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function tour_payment_cancel(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $post = array(
        "id_users_confirm"        => $pst['id_users'],
        "status"                  => 1,
        "note"                    => $pst['note'],
        "update_by_users"         => $users[0]->id_users,
      );
      $data = $this->global_models->update("tour_payment", array("id_tour_payment" => $pst['id']), $post);
      $payment = $this->global_models->get_query(""
        . " SELECT A.*"
        . " ,(SELECT SUM(C.nominal) FROM tour_payment AS C WHERE C.id_product_tour_book_payment = A.id_product_tour_book_payment AND (C.status IN (0, 2) OR C.status IS NULL)) AS total"
        . " FROM product_tour_book_payment AS A"
        . " WHERE A.id_product_tour_book_payment = (SELECT B.id_product_tour_book_payment FROM tour_payment AS B WHERE B.id_tour_payment = '{$pst['id']}')"
        . "");
//      $this->debug($payment, true);
//      ubah status book_payment
      if($payment[0]->total <= 0){
        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $payment[0]->id_product_tour_book_payment), array("status" => 3));
        $ttu = $this->global_models->get_query("SELECT SUM(A.nominal) AS nominal"
          . " FROM product_tour_book_payment AS A"
          . " WHERE A.id_product_tour_book = '{$payment[0]->id_product_tour_book}' AND A.status IN (2,4)"
          . "");
      }
      
      
//      ubah status sort costumer
      if($ttu[0]->nominal <= 0){
        $id_product_tour_information = $this->global_models->get_field("product_tour_book", "id_product_tour_information", array("id_product_tour_book" => $payment[0]->id_product_tour_book));
        $max = $this->global_models->get_field("product_tour_book", "MAX(sort)", array("id_product_tour_information" => $id_product_tour_information));
        if($max < 100)
          $max = 99;
        
        $this->global_models->update("product_tour_book", array("id_product_tour_book" => $payment[0]->id_product_tour_book), array("sort" => ($max+1), "update_by_users" => $users[0]->id_users, "status" => 1));

        $max2 = $this->global_models->get_field("product_tour_customer", "MAX(sort)", array("id_product_tour_information" => $id_product_tour_information));
        if($max2 < 100)
          $max = 99;
        $costumer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $payment[0]->id_product_tour_book));
//        $this->debug($costumer, true);
        foreach ($costumer AS $cos){
          $max++;
          $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $cos->id_product_tour_customer), array("sort" => $max, "update_by_users" => $users[0]->id_users, "status" => 1));
        }

//        $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $product_tour_book_payment[0]->id_product_tour_book_payment), array("id_users_confirm" => $pst['id_users'], "status" => $pst['status'], "update_by_users" => $users[0]->id_users, "note" => $pst['note']));
      }
      
      if($data){
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => "Gagal"
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function generate_ttu($id_tour_pameran = NULL, $id_store, $type){
    $code = ($id_tour_pameran ? $this->global_models->get_field("tour_pameran", "kode", array("id_tour_pameran" => $id_tour_pameran))."-" : "");
    $store = $this->global_models->get_field("store", "kode", array("id_store" => $id_store))."-";
    $pc = array(
      1 => "01-",
      2 => "02-",
      3 => "03-",
    );
    $yy = date("y-");
    $not = $this->global_models->get_query("SELECT MAX(no) AS h"
      . " FROM product_tour_book_payment"
      . " WHERE tanggal BETWEEN '".date("Y")."-01-01' AND '".date("Y")."-12-31'"
      . "");
    $no = ($not[0]->h ? ($not[0]->h + 1) : 1);
    $digit = str_pad($no, 6, '0', STR_PAD_LEFT);
    return array(
      "ttu" => $code.$store.$pc[$type].$yy.$digit,
      "no"  => ($no + 1)
      );
  }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */