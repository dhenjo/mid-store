<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_midlle_system extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   * @param string $order Order query berdasarkan
   * @param string $sort Sort query berdasarkan
   * @param string $start Start query limit
   * @param string $limit Limit query
   */
  function hajj_umrah(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $order = "ORDER BY A.harga_rata ASC";
      $limit = "";
      if($pst['order'])
        $order = "ORDER BY A.{$pst['order']} {$pst['sort']}";
      if($pst['limit'])
        $limit = "LIMIT {$pst['start']}, {$pst['limit']}";
      $items = $this->global_models->get_query("SELECT A.*"
        . " FROM master_hajj_umrah AS A"
        . " WHERE status = 1"
        . " {$where}"
        . " {$order}"
        . " {$limit}");
      $kirim = array(
        'status'  => 2,
        'items'   => $items
      );
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   * @param string $nicename Nicename detail Hajj Umrah
   */
  function hajj_umrah_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $items = $this->global_models->get_query("SELECT A.*"
        . " FROM master_hajj_umrah AS A"
        . " WHERE nicename = '{$pst['nicename']}'"
        . "");
      $kirim = array(
        'status'  => 2,
        'items'   => $items[0]
      );
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   */
  function book_hajj(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $kirim = array(
        "id_master_hajj_umrah"      => $pst['id_master_hajj_umrah'],
        "name"                      => $pst['name'],
        "email"                     => $pst['email'],
        "telp"                      => $pst['telp'],
        "note"                      => $pst['note'],
        "status"                    => $pst['status'],
        "create_by_users"           => $this->session->userdata("id"),
        "create_date"               => date("Y-m-d H:i:s")
      );
      
      $id_master_hajj_umrah_book = $this->global_models->insert("master_hajj_umrah_book", $kirim);
      $haji = $this->global_models->get("master_hajj_umrah", array("id_master_hajj_umrah" => $pst['id_master_hajj_umrah']));

      $this->load->library('email');
      $this->email->initialize($this->global_models->email_conf());

      $this->email->from($pst['email'], $pst['name']);
//      $this->email->to('nugroho@antavaya.com'); 
      $this->email->to('umroh@antavaya.com'); 
      $this->email->cc('nugroho.budi@antavaya.com');

      $this->email->subject("Inquiry Product Hajj & Umrah {$haji[0]->title} ".date("Y-m-d H:i:s"));
      $this->email->message(""
        . "Dear Hajj & Umrah Admin <br />"
        . "Mohon informasi lebih detail untuk product Hajj & Umrah "
        . "<a href='".URLHAJJUMRAH."package/detail/{$haji[0]->nicename}'>"
          . "{$haji[0]->title}</a><br />"
        . "Kepada <br />"
        . "Nama : {$pst['name']}<br />"
        . "Email : {$pst['email']}<br />"
        . "Telp : {$pst['telp']}<br />"
        . "Desc <br />"
        . "{$pst['note']} <br />"
        . "Terima Kasih"
        . "");  
  //die;
      $this->email->send();
      $kirim = array(
        'status'    => 2,
        'items'     => $haji[0]
      );
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   */
  function get_product_tour_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour = $this->global_models->get("product_tour", array("kode" => $pst['code']));
      if($tour){
        $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
        $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia", 5 => "Asia", 6 => "China", 7 => "New Zealand");
        
        
          $where = " AND id_product_tour = '".$tour[0]->id_product_tour."'";
          $where .= " AND start_date >= '".date("Y-m-d")."'";
          
        if($pst['code_schedule']){
          $where .= " AND kode = '{$pst['code_schedule']}'";
        }
       
        $info = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_information"
          . " WHERE 1 = 1 AND tampil=1 "
          . " {$where}"
          . " ORDER BY start_date ASC");
        foreach($info AS $fo){
          $book = $this->global_models->get_query("SELECT count(A.kode) AS aid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$fo->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)");
         $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, "");     
            
        if($fo->at_airport_date != "0000-00-00" AND $fo->at_airport_date != ""){
            $date_start = $fo->at_airport_date;
        }else{
            $date_start = $fo->start_date;
        }
        
          $information[] = array(
            "code"                  => $fo->kode,
            "start_date"            => $date_start,
            "end_date"              => $fo->end_date,
            "start_time"            => $fo->start_time,
            "end_time"              => $fo->end_time,
            "days"                  => $fo->days,
            "flt"                   => $fo->flt,
            "in"                    => $fo->in,
            "out"                   => $fo->out,
            "currency"              => $dropdown[$fo->id_currency],
            "discount_tetap"        => $fo->discount_tetap,
            "stnb_discount_tetap"   => $fo->stnb_discount_tetap,
            "seat"                  => $fo->available_seat,
            "available_seat"        => ($fo->available_seat - ($book[0]->aid)),
            "price"                 => array("adult_triple_twin" => $fo->adult_triple_twin,"child_twin_bed" => $fo->child_twin_bed,"child_extra_bed" => $fo->child_extra_bed,"child_no_bed" => $fo->child_no_bed,"sgl_supp" => $fo->sgl_supp, "airport_tax" => $fo->airport_tax, "visa" => $fo->visa),
          //  "committed_book"    => $fo->dp
          );
        }
        
        if($tour[0]->file_thumb){
          $file_thumb2 = base_url()."files/antavaya/product_tour/".$tour[0]->file_thumb;
        }else{
          $file_thumb2 = "";
        }
        
        if($tour[0]->file){
          $file2 = base_url()."files/antavaya/product_tour/".$tour[0]->file;
        }else{
          $file2 = "";
        }
        
        if($tour[0]->file_itin){
          $file3 = base_url()."files/antavaya/product_tour/".$tour[0]->file_itin;
        }else{
          $file3 = "";
        }
        
        $kirim = array(
          "status"        => 2,
          "tour"          => array(
            "code"              => $tour[0]->kode,
            "title"             => $tour[0]->title,
            "night"             => $tour[0]->night,
            "days"              => $tour[0]->days,
            "airlines"              => $tour[0]->airlines,
           // "sub_title"         => $tour[0]->sub_title,
            "destination"         => $tour[0]->destination,
            "landmark"         => $tour[0]->landmark,
            "summary"           => $tour[0]->summary,
            "file_thumb"        => $file_thumb2,
            "file"              => $file2,
            "file_itin"         => $file3,
            "category"          => array("id" => $tour[0]->category, "name" => $category[$tour[0]->category]),
            "sub_category"      => array("id" => $tour[0]->sub_category, "name" => $sub_category[$tour[0]->sub_category]),
            "text"              => $tour[0]->note,
            "information"       => $information,
          ),
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
  
  function get_tour_detail_information(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_infomation = $this->global_models->get("product_tour_information", array("kode" => $pst['code']));
      if($tour_infomation){
       
         $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, "");     
           
          $info_disc = $this->global_models->get_query("SELECT C.batas_discount,C.discount,stnb_discount AS status_discount"
              . " FROM product_tour_information AS A"
              . " LEFT JOIN product_tour_master_discount AS B ON A.id_product_tour_master_discount = B.id_product_tour_master_discount"
              . " LEFT JOIN product_tour_setting_discount AS C ON B.id_product_tour_master_discount = C.id_product_tour_master_discount"
              . " WHERE A.id_product_tour_information = '{$tour_infomation[0]->id_product_tour_information}' AND B.status='1'"); 
        $kirim = array(
          "status"        => 2,
          "tour"          => array(
            "code"                  => $tour_infomation[0]->kode,
            "kode_ps"               => $tour_infomation[0]->kode_ps,
            "start_date"            => $tour_infomation[0]->start_date,
            "end_date"              => $tour_infomation[0]->end_date,
            "start_time"            => $tour_infomation[0]->start_time,
            "end_time"              => $tour_infomation[0]->end_time,
            "days"                  => $tour_infomation[0]->days,
            "flt"                   => $tour_infomation[0]->flt,
            "in"                    => $tour_infomation[0]->in,
            "out"                   => $tour_infomation[0]->out,
            "currency"              => $dropdown[$tour_infomation[0]->id_currency],
//            "discount_tetap"        => $tour_infomation[0]->discount_tetap,
//            "stnb_discount_tetap"   => $tour_infomation[0]->stnb_discount_tetap,
            "seat"                  => $tour_infomation[0]->available_seat,
            "available_seat"        => ($tour_infomation[0]->available_seat - ($book[0]->aid)),
            "price"                 => array("adult_triple_twin" => $tour_infomation[0]->adult_triple_twin,"child_twin_bed" => $tour_infomation[0]->child_twin_bed,"child_extra_bed" => $tour_infomation[0]->child_extra_bed,"child_no_bed" => $tour_infomation[0]->child_no_bed,"sgl_supp" => $tour_infomation[0]->sgl_supp, "airport_tax" => $tour_infomation[0]->airport_tax),
            "info_disc"             => $info_disc
          ),
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   */
  function get_tour_book(){
    $pst = $_REQUEST;
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia", 5 => "Asia", 6 => "China", 7 => "New Zealand");
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get_query("SELECT A.*,A.stnb_discount,A.adult_triple_twin AS total_person_adult_ttwin,A.child_twin_bed AS total_person_child_twin,A.child_extra_bed AS total_person_child_extra,A.child_no_bed AS total_person_child_no_bed,A.sgl_supp AS total_person_sgl_supp, A.DP,A.status_additional_request"
        . " ,A.harga_adult_triple_twin,A.harga_child_twin_bed,A.harga_child_extra_bed,A.harga_child_no_bed,A.harga_single_adult,A.harga_airport_tax, B.title, B.category, B.sub_category, B.kode AS tour_code,B.airlines"
        . " , C.kode AS tour_information_code, C.id_product_tour_information, C.start_date,C.at_airport_date, C.end_date, C.available_seat, C.adult_triple_twin, C.child_twin_bed, C.child_extra_bed, C.child_no_bed,C.sgl_supp,C.airport_tax,C.id_currency,C.visa,C.status AS information_status"
        . " ,E.name, E.email, A.email AS email_user"
        . " ,G.id_store AS id_store1, G.title AS store1"
        . " ,I.id_store AS id_store2, I.title AS store2"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_additional AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " LEFT JOIN users_channel AS E ON A.id_users = E.id_users"
        
        . " LEFT JOIN store_tc AS F ON A.id_users = F.id_users"
        . " LEFT JOIN store AS G ON F.id_store = G.id_store"
        
        . " LEFT JOIN store_commited AS H ON A.id_users = H.id_users"
        . " LEFT JOIN store AS I ON H.id_store = I.id_store"
        
        . " WHERE A.kode = '{$pst['code']}'");
      if($product_tour_book[0]->id_product_tour_book > 0){
        $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
        $additional = $this->global_models->get("product_tour_additional", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
        $note_type = array(
          1 => "Adult Triple Twin",
          2 => "Child Twin Bed",
          3 => "Child Extra Bed",
          4 => "Child No Bed",
          5 => "SGL SUPP"
        );
         $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}' AND status < '3' ");
//       $tes2 = $this->db->last_query();
//         $data_additional = $this->global_models->get("product_tour_optional_additional", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information));
//        foreach($data_additional AS $dat){
//            $addtinal2 = $this->global_models->get("product_tour_master_additional", array("id_product_tour_master_additional" => $dat->id_product_tour_master_additional));
//            $dt_additional[] = array(
//              "id_product_tour_master_additional"         => $dat->id_product_tour_optional_additional,
//              "name"                                      => $addtinal2[0]->name,
//              "nominal"                                   => $dat->nominal,
//            );
//            
//          }
          
        foreach($passenger AS $psng){
            if($psng->status == 1){
            $status_cust = "Book";
          }elseif($psng->status == 2){
            $status_cust = "Deposit";
          }elseif($psng->status == 3){
            $status_cust = "Lunas";
          }elseif($psng->status == 4){
            $status_cust = "Cancel";
          }elseif($psng->status == 6){
            $status_cust = "[Cancel] Waiting Approval";
          }elseif($psng->status == 5){
            $status_cust = "Cancel Deposit";
          }elseif($psng->status == 7){
            $status_cust = "[Change Tour] Waiting Approval";
          }elseif($psng->status == 8){
            $status_cust = "[Cancel] Change Tour";
          }elseif($psng->status == 9){
            $status_cust = "Reject Change Tour";
          }
          $passenger_tour[] = array(
            "first_name"            => $psng->first_name,
            "last_name"             => $psng->last_name,
            "tanggal_lahir"         => $psng->tanggal_lahir,
            "tempat_tanggal_lahir"  => $psng->tempat_tanggal_lahir,
            "type"                  => array("code" => $psng->type, "desc" => $note_type[$psng->type]),
            "room"                  => $psng->room,
            "visa"                  => $psng->visa,
            "less_ticket"           => $psng->less_ticket,
            "no_passport"           => $psng->passport,
            "place_of_issued"       => $psng->place_of_issued,
            "date_of_issued"        => $psng->date_of_issued,
            "date_of_expired"       => $psng->date_of_expired,
            "telphone"              => $psng->telphone,
            "status"                => $status_cust,
            "cstatus"               => $psng->status,
            "customer_code"         => $psng->kode,
			"note"					=> $psng->note,
          );
        }
        
        foreach($additional AS $add){
          
          $dtst_addtional = array(1 => "Pengajuan",2 => "Disetujui", 3 => "Ditolak");
          $additional_tour[] = array(
            "name_additional"             => $add->name,
            "nominal_additional"          => $add->nominal,
            "pos"                         => $add->pos,
            "id_currency"                 => $add->id_currency,
            "status"                      => $dtst_addtional[$add->status],
            "code_status"                 => $add->status,
            "kode"                        => $add->kode,
            "user_pengaju"                => $this->global_models->get_field("m_users", "name", array("id_users" => $add->id_user_pengaju)),
            "user_approval"               => $this->global_models->get_field("users_channel", "name", array("id_users" => $add->id_user_approval)),
          );
        }
        $last_log_request_discount = $this->global_models->get_query("SELECT status"
          . " FROM log_request_discount"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " ORDER BY id_log_request_discount DESC LIMIT 0,1");
          
          $discount_tambahan = $this->global_models->get_query("SELECT discount_request,status,status_discount"
          . " FROM product_tour_discount_tambahan"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}' AND status= 1");
          
          $discount_tambahan2 = $this->global_models->get_query("SELECT B.name AS name_request,id_user_approval,A.id_product_tour_discount_tambahan,A.discount_request,A.status_discount,A.status"
          . " FROM product_tour_discount_tambahan AS A"
          . " LEFT JOIN users_channel AS B ON A.create_by_users = B.id_users"
          . " WHERE A.id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"); 
        
          $usr_approval = $this->global_models->get("users_channel", array("id_users" => $discount_tambahan2[0]->id_user_approval));
          
          
          
          if($discount_tambahan2[0]->status == 1){
            $status_disc_tambahan = "Waiting Appraval";
          }  elseif($discount_tambahan2[0]->status == 2) {
            $status_disc_tambahan = "Approve";
          }elseif($discount_tambahan2[0]->status == 3){
            $status_disc_tambahan = "Reject";
          }
          
          $discount_tambahan4 = array("name_request"         => $discount_tambahan2[0]->name_request,
                                     "discount_request"     => $discount_tambahan2[0]->discount_request,
                                     "status_discount"      => $discount_tambahan2[0]->status_discount,
                                     "status"               => $status_disc_tambahan,
                                     "user_approval"        => $usr_approval[0]->name,
                                     "id_product_tour_discount_tambahan"  => $discount_tambahan2[0]->id_product_tour_discount_tambahan
                                    );
          
          if($product_tour_book[0]->stnb_discount == 1){
            $stnb_discount = "Persen";
          }elseif($product_tour_book[0]->stnb_discount == 2){
            $stnb_discount = "Nominal";
          }
          
          $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1 ,"status" =>2));     
          $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
          $dropdown1 = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1)); 
       $data_dropdown = array(1 => "USD",2 => "IDR");
         $totl_committed = $this->global_models->get_query("SELECT count(A.kode) AS cid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$product_tour_book[0]->id_product_tour_information}' AND (A.status='2' OR A.status='3')"); 
              
              $info_disc = $this->global_models->get_query("SELECT C.batas_discount,C.discount,stnb_discount AS status_discount"
              . " FROM product_tour_information AS A"
              . " LEFT JOIN product_tour_master_discount AS B ON A.id_product_tour_master_discount = B.id_product_tour_master_discount"
              . " LEFT JOIN product_tour_setting_discount AS C ON B.id_product_tour_master_discount = C.id_product_tour_master_discount"
              . " WHERE A.id_product_tour_information = '{$product_tour_book[0]->id_product_tour_information}' AND B.status='1'"); 
         
//       $own = $this->global_models->get("users_channel", array("id_users" => $product_tour_book[0]->id_users));
            
       $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['dt_users']));
      if(!$id_store){
        $status_user = 1;
      }
      else{
        $status_user = 2;
      }
      
      $id_store_book = $product_tour_book[0]->id_store1;
      $store_book = $product_tour_book[0]->store1;
      if(!$id_store_book){
        $id_store_book = $product_tour_book[0]->id_store2;
        $store_book = $product_tour_book[0]->store2;
      }
      
      $book = array(
          "code"                    => $product_tour_book[0]->kode,
          "own_user"                => $product_tour_book[0]->email,
          "agent"                   => $product_tour_book[0]->name,
          "id_store"                => $id_store_book,
          "store"                   => $store_book,
          "first_name"              => $product_tour_book[0]->first_name,
          "last_name"               => $product_tour_book[0]->last_name,
          "telphone"                => $product_tour_book[0]->telphone,
          "email"                   => $product_tour_book[0]->email_user,
          "address"                 => $product_tour_book[0]->address,  
          "tanggal"                 => $product_tour_book[0]->tanggal,
          "status"                  => $product_tour_book[0]->status,
          "status_user"             => $status_user,
          "room"                    => $product_tour_book[0]->room,
          "discount"                => $product_tour_book[0]->discount,
          "status_discount"         => $stnb_discount,
          "total_customer"          => $totl_committed[0]->cid,
          "note_additional"         => $product_tour_book[0]->additional_request,
          "status_additional_request"            => $product_tour_book[0]->status_additional_request,
          "dp"                                          => $product_tour_book[0]->DP,
          "status_log_discount"                         => $last_log_request_discount[0]->status,
          "jumlah_person_adult_triple_twin"             => $product_tour_book[0]->total_person_adult_ttwin,
          "jumlah_person_child_twin"                    => $product_tour_book[0]->total_person_child_twin,
          "jumlah_person_child_extra"                   => $product_tour_book[0]->total_person_child_extra,
          "jumlah_person_child_no_bed"                  => $product_tour_book[0]->total_person_child_no_bed,
          "jumlah_person_sgl_supp"                      => $product_tour_book[0]->total_person_sgl_supp,
          "passenger"                                   => $passenger_tour,
          "additional"                                  => $additional_tour,
          "discount_tambahan"                           => $discount_tambahan[0]->discount_request,
          "status_discount_tambahan"                    => $status_disc_tambahan,
          "st_discount_tambahan"                         => $discount_tambahan[0]->status_discount,
          "history_discount_req"                        => $discount_tambahan4,
         // "additional_req"                              => $dt_additional,
          "currency"                                    => $dropdown1,
            "total_visa"                                => $total_visa[0]->totl_visa,
            "info_disc"                                 => $info_disc,
          "remark"                                 => $product_tour_book[0]->remark
        );
		
		if(!$product_tour_book[0]->information_status)
			$product_tour_book[0]->information_status = 1;
        
        if($product_tour_book[0]->at_airport_date != "0000-00-00" AND $product_tour_book[0]->at_airport_date != ""){
            $date_start = $product_tour_book[0]->at_airport_date;
        }else{
            $date_start = $product_tour_book[0]->start_date;
        }     
        
        $information = array(
          "code"              => $product_tour_book[0]->tour_information_code,
          "start_date"        => $date_start,
          "end_date"          => $product_tour_book[0]->end_date,
          "seat"              => $product_tour_book[0]->available_seat,
          "keberangkatan"     => $product_tour_book[0]->keberangkatan,
		  "information_status" => $product_tour_book[0]->information_status,
          "price"             => array(
              "adult_triple_twin"     => $product_tour_book[0]->harga_adult_triple_twin,
              "child_twin_bed"        => $product_tour_book[0]->harga_child_twin_bed,
              "child_extra_bed"       => $product_tour_book[0]->harga_child_extra_bed,
              "child_no_bed"          => $product_tour_book[0]->harga_child_no_bed,
              "single_adult"          => $product_tour_book[0]->harga_single_adult,
              "tax_and_insurance"     => $product_tour_book[0]->harga_airport_tax,
              "visa"                  => $product_tour_book[0]->visa,
              "currency"              => $data_dropdown[$product_tour_book[0]->id_currency])
        );
        
        $tour = array(
          "code"              => $product_tour_book[0]->tour_code,
          "title"             => $product_tour_book[0]->title,
          "airlines"          => $product_tour_book[0]->airlines,
           "category"          => array("id" => $product_tour_book[0]->category, "name" => $category[$product_tour_book[0]->category]),
          "sub_category"      => array("id" => $product_tour_book[0]->sub_category, "name" => $sub_category[$product_tour_book[0]->sub_category]),
          "information"       => $information
        );
        
        $tour_payment = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " AND tampil IS NULL"
          . " ORDER BY create_date ASC");
        foreach($tour_payment AS $tp){
          $payment[] = array(
            "id"                      => $tp->id_product_tour_book_payment,
            "agent"                   => $tp->id_users,
            "checker"                 => $tp->id_users_confirm,
            "nominal"                 => $tp->nominal,
            "tanggal"                 => $tp->tanggal,
            "pos"                     => $tp->pos,
            "status"                  => $tp->status,
            "status_payment"          => $tp->payment,
            "no_deposit"              => $tp->no_deposit,
            "no_ttu"                  => $tp->no_ttu,
            "currency"                => $tp->id_currency,
            "note"                    => $tp->note
          );
        }
        
        $log_request_discount_additional = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_log_request_additional"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " ORDER BY id_product_tour_log_request_additional ASC");
         $data_log_additional = "";
          
          if($log_request_discount_additional){
            foreach($log_request_discount_additional AS $lrd){
         
          $data_log_additional[] = array(
            "name"            => $lrd->name,
            "tanggal"         => $lrd->tanggal,
            "text"            => $lrd->note
          );
        }
          }
        
        if($pst["committed"]){
          $status_payment = $this->global_models->get_query("SELECT *"
            . " FROM product_tour_book_payment"
            . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
            . " ORDER BY id_product_tour_book_payment DESC limit 0,1");
           
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 2));
       $data_committed =  $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $status_payment[0]->id_product_tour_book_payment), array("status" => 2));
         
        }
        if($data_committed){
           $dt_committed = "Sukses";
         }else{
           $dt_committed = "Gagal";
         }
        
        $kirim = array(
          'status'  => 2,
          'tour'    => $tour,
          'book'    => $book,
          'payment' => $payment,
          'currency_rate' => $dropdown_rate[1],
          'log_request_additional' => $data_log_additional,
//          'tes' => $tes2
         // 'data_committed' => $tes_dta,
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Book Tidak Ada'
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   */
  function payment_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
     
      if($book[0]->status == 1){
           $totl_committed = $this->global_models->get_query("SELECT count(A.kode) AS cid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$book[0]->id_product_tour_information}' AND (A.status='2' OR A.status='3')"); 
              
              $info_disc = $this->global_models->get_query("SELECT C.batas_discount,C.discount,stnb_discount AS status_discount"
              . " FROM product_tour_information AS A"
              . " LEFT JOIN product_tour_master_discount AS B ON A.id_product_tour_master_discount = B.id_product_tour_master_discount"
              . " LEFT JOIN product_tour_setting_discount AS C ON B.id_product_tour_master_discount = C.id_product_tour_master_discount"
              . " WHERE A.id_product_tour_information = '{$book[0]->id_product_tour_information}' AND B.status='1'"); 
        
             $jml_cust = $totl_committed[0]->cid + 1;
            
            $no_cs = 1;
         foreach ( $info_disc as $dval) {
             $aks = 1;
                                     $batas = ($dt_dis + 1);
                                     $bts +=  $dval->batas_discount;
                                    if($no_cs == 1){
                                        $aks = $aks;
                                        if($dval->batas_discount >= $jml_cust){
                                             $simpan_dt = array(
                                                'status'    => 2,
                                                'stnb_discount'      => $dval->status_discount,
                                                 'discount'      => $dval->discount,
                                              );
                                       $this->global_models->update("product_tour_book", array("id_product_tour_book" => $book[0]->id_product_tour_book), $simpan_dt); 
                                         
                                          break;
                                        }
                                       
                                    }else{
                                        if($batas >= $jml_cust){
                                            if($jml_cust <= $bts){
                                                $aks++;
                                                $simpan_dt = array(
                                                'status'    => 2,
                                                'stnb_discount'      => $dval->status_discount,
                                                 'discount'      => $dval->discount,
                                              );
                                         $this->global_models->update("product_tour_book", array("id_product_tour_book" => $book[0]->id_product_tour_book), $simpan_dt); 
                                          
                                          break;
                                            }

                                        }
//                                        $dt = "Selanjutnya";
//                                        $batas_all = $batas." - ".$bts;


                                    }
                                  //  print "Untuk ".$batas_all." Orang Deposit {$dt} Akan mendapatkan discount ".$dval->discount." ".$dt_status_disc2[$dval->status_discount]."<br>";
                                    $dt_dis = $bts;
                                    $no_cs++;
                                    }
             
      }
     $status_payment = $this->global_models->get_query("SELECT *"
            . " FROM product_tour_book_payment"
            . " WHERE id_product_tour_book = '{$book[0]->id_product_tour_book}'"
            . " ORDER BY id_product_tour_book_payment DESC limit 0,1");
            $nominal = str_replace(",", "", $pst['nominal']);
       $this->olah_payment_code($kode);
       if($book){
        $kirim = array(
          "id_product_tour_book"        => $book[0]->id_product_tour_book,
          "id_users"                    => $pst['id_users'],
          "nominal"                     => $nominal,
          "tanggal"                     => $pst['tanggal'],
          "kode"                        => $kode,
          "no_deposit"                  => $pst['no_deposit'],
          "no_ttu"                      => $pst['no_ttu'],
          "pos"                         => 2,
          "payment"                     => $pst['payment'],
          "id_currency"                 => $pst['currency'],
          "status"                      => 1,
          "note"                        => "TTU: {$pst['no_ttu']}, Deposit: {$pst['no_deposit']}",
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim);
        if($id_product_tour_book_payment){
          $status_data = $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $id_product_tour_book_payment), array("status" => 2));
           $data_payment2 = $this->global_models->get("product_tour_book_payment", array("id_product_tour_book_payment" => $id_product_tour_book_payment));
//          $balance = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
//            . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
//            . " FROM product_tour_book_payment"
//            . " WHERE id_product_tour_book = '{$book[0]->id_product_tour_book}'");
//          
//          $dp = $this->global_models->get_field("product_tour_book", "DP", array("id_product_tour_book" => $book[0]->id_product_tour_book));
//          $nominal_pertama = $this->global_models->get_field("product_tour_book_payment", "nominal", array("pos" => 1, "status" => 1, "id_product_tour_book" => $book[0]->id_product_tour_book));
//          
           $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
           
       $data_tour_book2 = $this->global_models->get_query("SELECT A.*,A.stnb_discount,A.adult_triple_twin AS total_person_adult_ttwin,A.child_twin_bed AS total_person_child_twin,A.child_extra_bed AS total_person_child_extra,A.child_no_bed AS total_person_child_no_bed,A.sgl_supp AS total_person_sgl_supp, A.DP,A.status_additional_request"
        . " , B.title, B.category, B.sub_category, B.kode AS tour_code"
        . " , C.kode AS tour_information_code, C.id_product_tour_information, C.start_date, C.end_date, C.available_seat, C.adult_triple_twin, C.child_twin_bed, C.child_extra_bed, C.child_no_bed,C.sgl_supp,C.airport_tax,C.id_currency,C.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_additional AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'");
    
//        $total_nom = $this->global_models->get_query("SELECT nominal, id_currency"
//              . " FROM product_tour_book_payment"
//              . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'"
//              . " AND status = 2"); 
//              
//              foreach($total_nom AS $pr_nom){
//               
//                  if($pr_nom->id_currency == 1){
//                      $tot_nom = $pr_nom->nominal;
//                    }elseif($pr_nom->id_currency == 2){
//                      $tot_nom = $pr_nom->nominal/$dropdown_rate[1];
//                    }
//                  $total_price_awal += $tot_nom;
//                 
//                }
        
    
             if($data_payment2[0]->nominal > 0){
                  $sort_cust2 = $this->global_models->get_query("SELECT max(A.sort) AS cust_sort"
              . " FROM product_tour_customer AS A"
              . " WHERE id_product_tour_information = '{$book[0]->id_product_tour_information}' AND A.status='2' ");
        
               $pro_tour_book = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book, "status" => 1));
               $no_sort = 0;
               $sort_cust4 = $sort_cust2[0]->cust_sort;
               foreach ($pro_tour_book as $ky2 => $val2) {
                 if($val2->status == 1){
                    $no_sort  = $no_sort + 1;
                  
              if($sort_cust4 > 0){
                $sort_book_3  = $sort_cust4 + $no_sort;
              }else{
                $sort_book_3  = $no_sort;
              }
              
                    $kirim_st = array("status" => 2,
                                      "sort"    =>$sort_book_3);
                    $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $val2->id_product_tour_customer,"status" => 1), $kirim_st);
            
                 }
               }
               
               $sort_bk4 = $this->global_models->get_query("SELECT max(sort) AS book_sort"
                . " FROM product_tour_book"
                . " WHERE id_product_tour_information = '{$book[0]->id_product_tour_information}' AND status='2' ");
                
                if($sort_bk4[0]->book_sort > 0){
                  $sort_book4  = $sort_bk4[0]->book_sort + 1;
                }else{
                  $sort_book4  = "1";
                }
                        $kirim_st3 = array("status" => 2,
                                            "sort"    => $sort_book4);
                    $this->global_models->update("product_tour_book", array("id_product_tour_book" => $book[0]->id_product_tour_book,"status" => 1), $kirim_st3);
            
             }   
             
        $additional = $this->global_models->get("product_tour_additional", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book));
            
            $tour_payment = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}' AND status IN (0,2,4)"
          . " ORDER BY tanggal ASC");
//           $total_person =($data_tour_book2[0]->total_person_adult_ttwin + $data_tour_book2[0]->total_person_child_twin + $data_tour_book2[0]->total_person_child_extra + $data_tour_book2[0]->total_person_child_no_bed + $data_tour_book2[0]->total_person_sgl_supp);
//          
//           $data_tax = $total_person * $data_tour_book2[0]->airport_tax;
//           if($data_tour_book2[0]->id_currency == 1){
//               $data_tax = $data_tax * $dropdown_rate[1];
//           }elseif($data_tour_book2[0]->id_currency == 2){
//               $data_tax = $data_tax;
//           }
           $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}' AND status < '3' ");
         
        if($total_visa[0]->totl_visa > 0){
         if($data_tour_book2[0]->id_currency == 1){
               $total_visa = ($total_visa[0]->totl_visa * $data_tour_book2[0]->visa) * $dropdown_rate[1];
           }elseif($data_tour_book2[0]->id_currency == 2){
               $total_visa = $total_visa[0]->totl_visa * $data_tour_book2[0]->visa;
           }
        }else{
          $total_visa =0;
        }
        
       
       
           foreach($tour_payment AS $dp){
               
                  if($dp->id_currency == 1){
                    //  $tot_debit0_idr = $dp->nominal * $dropdown_rate[1];
                    }elseif($dp->id_currency == 2){
                      $tot_debit0_idr = $dp->nominal;
                    }
                  if($dp->pos == 1){
                  //  $debit = number_format($tot_debit0_idr, 0, ",", ".");
                   // $kredit = "";
                    $total_debit += $tot_debit0_idr;
                  }elseif($dp->pos == 2 AND $dp->status != 2){
                    $balance_kredit_potongan_tambahan_idr += $nom1_idr;
                  }elseif($dp->pos == 2 AND $dp->status == 2){
                   // $kredit = number_format($tot_debit0_idr, 0, ",", ".");
                  //  $debit = "";
                    $balance_kredit_pembayaran_idr += $tot_debit0_idr;
                  }
                 
                }

//            foreach($additional AS $add){
//                    $nom_ad1 = "";
//                    $nom_ad2 = "";
//                    if($add->id_currency == 1){
//                        $nom_add0 = $add->nominal * $dropdown_rate[1];
//                      }elseif($add->id_currency == 2){
//                        $nom_add0 = $add->nominal;
//                      }
//                    if($add->pos == 1){
//                      $nom_ad1 = $nom_add0;
//                      $total_kredit2 += $nom_add0;
//                    }else{
//                      $nom_ad2 = $nom_add0;
//                      $total_debit2 += $nom_add0;
//                    }
//        }
//        if($data_tour_book2[0]->stnb_discount == 1){
//                  $status_price = $book['discount'];
//                  $tot_disc_price1 =  (($total_debit * $data_tour_book2[0]->discount)/100);
//                }elseif($data_tour_book2[0]->stnb_discount == 2) {
//                 $tot_disc_price1 = $data_tour_book2[0]->discount;
//                }
                
//         $discount_tambahan = $this->global_models->get_query("SELECT id_product_tour_discount_tambahan,discount_request,status_discount,status"
//          . " FROM product_tour_discount_tambahan"
//          . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}' AND status='1'"); 
//        
//           if($discount_tambahan[0]->discount_request){
//                       if($discount_tambahan[0]->status_discount == 1){
//                   
//                    $tot_disc_tambahan =  (($total_debit * $discount_tambahan[0]->discount_request)/100);
//                  }elseif($discount_tambahan[0]->status_discount == 2) {
//                   $tot_disc_tambahan = $discount_tambahan[0]->discount_request;
//                  }
//           }
         
            
//            if($data_payment2[0]->status_discount == 1){
//                  $status_price = $book['discount'];
//                  $tot_disc_price1 =  (($total_debit * $book['discount'])/100);
//                }elseif($data_payment2[0]->status_discount == 1) {
//                 $tot_disc_price1 = number_format($book['status_discount'],0,",",".");
//                }
//                $ppn = (1 * (($total_debit + $total_debit2 + $total_visa + $data_tax)-$tot_disc_price1)/100);
            $total_all =    (($total_debit)- ($balance_kredit_potongan_tambahan_idr + $balance_kredit_pembayaran_idr) );

            
      if($total_all <= 0){
         
           $this->db->query("UPDATE product_tour_customer SET status = 3 WHERE id_product_tour_book ={$data_payment2[0]->id_product_tour_book} AND (status = 1 OR status = 2)");
           $this->db->query("UPDATE product_tour_book SET status = 3 WHERE id_product_tour_book ={$data_payment2[0]->id_product_tour_book} AND (status = 1 OR status = 2)");
      }
          
          
            $kirim = array(
              'status'    => 2,
                'book_status'                   => $book[0]->id_product_tour_book,
              'desc'                            => "Lunas",
              'id_product_tour_book_payment'   =>$id_product_tour_book_payment,
              'total_all'     => $total_all,
              'total_debit'   => $total_debit,
              'balance_kredit_potongan_tambahan_idr' => $balance_kredit_potongan_tambahan_idr,
              'balance_kredit_pembayaran_idr'         => $balance_kredit_pembayaran_idr
             // 'query'       =>$ssss
            );
           
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => 'Data Gagal Disimpan'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Book Tidak Ada'
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   */
  
  function report_payment(){
    $pst = $_REQUEST;
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia", 5 => "Asia", 6 => "China", 7 => "New Zealand");
    
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $stt = array("Draft" => 1, "Confirm" => 2, "Not Paid" => 3);   
      $where = "";
       if($pst['code']){
          $where .= " AND LOWER(A.kode) LIKE '%".strtolower($pst['code'])."%' OR LOWER(A.kode) LIKE '%".strtolower($pst['code'])."%'"; 
      }
      
      if($pst['start_date'] || $pst['$end_date']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start_date']} 00:00:00' AND '{$pst['end_date']} 23:59:59')";
      }
     
      if($pst['status']){
        $where .= " AND LOWER(B.status) LIKE '%".strtolower($pst['status'])."%' OR LOWER(B.status) LIKE '%".strtolower($pst['status'])."%'"; 
      }
      
      if($pst['payment_type']){
        $where .= " AND LOWER(B.payment) LIKE '%".strtolower($pst['payment_type'])."%' OR LOWER(B.payment) LIKE '%".strtolower($pst['payment_type'])."%'"; 
      }
      
      $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
      if(!$id_store){
        $filter_users = "AND A.id_users IN ({$pst['id_users']})";
      }
      else{
        $filter_users = "AND A.id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}')";
      }
      
      $book = $this->global_models->get_query("SELECT A.kode,A.first_name,A.last_name,B.id_product_tour_book, B.id_currency, B.nominal,B.status, B.payment,B.tanggal"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_book_payment AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " WHERE pos = 2"
        . " {$filter_users}"
        . "  $where"
        . " ORDER BY B.tanggal DESC");
      
      if($book){
        $additional_tour = "";
        foreach($book AS $ky => $bk){
         $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
         $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
          
        $name = $bk->first_name." ".$bk->last_name;
        $currency = $dropdown[$bk->id_currency];
        $channel = array(
                  1 => "Cash",
                  2 => "BCA",
                  3 => "Mega",
                  4 => "Mandiri",
                  5 => "CC");
        $status = array(1 => "Draft", 2 => "Confirm", 3 => "Not Paid");
        
          $book_detail[] = array(
            "name"            => $name,
            "book_code"       => $bk->kode,
            "currency"        => $currency,
            "tanggal"         => $bk->tanggal,
            "status"            => $status[$bk->status],
            "nominal"            => $bk->nominal,
            "payment_type"        => $channel[$bk->payment],
            'currency_rate' => $dropdown_rate[1],
          //  "passenger" => $passenger,
          );
        }
       
        $kirim = array(
          'status'  => 2,
          'book'    => $book_detail,
          
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
  
  function cek_payment(){
    $pst = $_REQUEST;
   
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
     

      $lIMIT = "";
      if($pst['start'] > 0){
          $start_limit = $pst['start'];
        }else{
          $start_limit = 0;
        }
        
      if($pst['limit'] OR $pst['start']){
         $lIMIT .= " LIMIT {$start_limit}, {$pst['limit']}";
      }
      
      $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
      if(!$id_store){
        $filter_users = "AND 1 = 2";
      }
      else{
        $filter_users = "AND (A.id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}') OR A.id_users IN ({$pst['id_users']}))";
      }
      
      $data_keseluruhan = $this->global_models->get_query("SELECT COUNT(A.id_product_tour_book_payment) AS total"
        . " FROM product_tour_book_payment AS A"
        . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " LEFT JOIN users_channel AS D ON A.update_by_users = D.id_users"
        . " WHERE 1=1 AND A.pos=2"
        . " {$filter_users}"
        . " ORDER BY A.create_date ASC");
      
         $list = $this->global_models->get_query("SELECT A.payment,A.kode,A.id_product_tour_book_payment,A.id_currency,A.nominal,A.tanggal,A.status,B.kode AS book_code,B.first_name,B.last_name,C.name AS name_tc,D.name AS name_konfirm"
        . " FROM product_tour_book_payment AS A"
        . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " LEFT JOIN users_channel AS D ON A.update_by_users = D.id_users"
        . " WHERE 1=1 AND A.pos=2"
        . " {$filter_users}"
        . " ORDER BY A.create_date ASC"
        . $lIMIT);

       if($pst['payment_code']){
         $data_payment2 = $this->global_models->get("product_tour_book_payment", array("kode" => $pst['payment_code']));
       
         if($data_payment2[0]->id_product_tour_book_payment > 0){
          
        $status_data = $this->global_models->update("product_tour_book_payment", array("id_product_tour_book_payment" => $data_payment2[0]->id_product_tour_book_payment), array("status" => 2,"update_by_users" =>$pst['id_users']));
       $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
           
       $data_tour_book2 = $this->global_models->get_query("SELECT A.*,A.stnb_discount,A.adult_triple_twin AS total_person_adult_ttwin,A.child_twin_bed AS total_person_child_twin,A.child_extra_bed AS total_person_child_extra,A.child_no_bed AS total_person_child_no_bed,A.sgl_supp AS total_person_sgl_supp, A.DP,A.status_additional_request"
        . " , B.title, B.category, B.sub_category, B.kode AS tour_code"
        . " , C.kode AS tour_information_code, C.id_product_tour_information, C.start_date, C.end_date, C.available_seat, C.adult_triple_twin, C.child_twin_bed, C.child_extra_bed, C.child_no_bed,C.sgl_supp,C.airport_tax,C.id_currency,C.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_additional AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'");
    
        $total_nom = $this->global_models->get_query("SELECT nominal, id_currency"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'"
              . " AND status = 2"); 
              
              foreach($total_nom AS $pr_nom){
               
                  if($pr_nom->id_currency == 1){
                      $tot_nom = $pr_nom->nominal;
                    }elseif($pr_nom->id_currency == 2){
                      $tot_nom = $pr_nom->nominal/$dropdown_rate[1];
                    }
                  $total_price_awal += $tot_nom;
                 
                }
        
    
             if($data_payment2[0]->nominal > 0){
               $kirim_st = array("status" => 2);
              $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book,"status" => 1), $kirim_st);
             }   
             
        $additional = $this->global_models->get("product_tour_additional", array("id_product_tour_book" => $data_payment2[0]->id_product_tour_book));
            
            $tour_payment = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}' AND (status = 2 OR status = 0)"
          . " ORDER BY tanggal ASC");
           $total_person =($data_tour_book2[0]->total_person_adult_ttwin + $data_tour_book2[0]->total_person_child_twin + $data_tour_book2[0]->total_person_child_extra + $data_tour_book2[0]->total_person_child_no_bed + $data_tour_book2[0]->total_person_sgl_supp);
          
           $data_tax = $total_person * $data_tour_book2[0]->airport_tax;
           if($data_tour_book2[0]->id_currency == 1){
               $data_tax = $data_tax * $dropdown_rate[1];
           }elseif($data_tour_book2[0]->id_currency == 2){
               $data_tax = $data_tax;
           }
           $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}' AND status < '3' ");
         
        if($total_visa[0]->totl_visa > 0){
         if($data_tour_book2[0]->id_currency == 1){
               $total_visa = ($total_visa[0]->totl_visa * $data_tour_book2[0]->visa) * $dropdown_rate[1];
           }elseif($data_tour_book2[0]->id_currency == 2){
               $total_visa = $total_visa[0]->totl_visa * $data_tour_book2[0]->visa;
           }
        }else{
          $total_visa =0;
        }
       
           foreach($tour_payment AS $dp){
               
                  if($dp->id_currency == 1){
                      $tot_debit0_idr = $dp->nominal * $dropdown_rate[1];
                    }elseif($dp->id_currency == 2){
                      $tot_debit0_idr = $dp->nominal;
                    }
                  if($dp->pos == 1){
                    $debit = number_format($tot_debit0_idr, 0, ",", ".");
                    $kredit = "";
                    $total_debit += $tot_debit0_idr;
                  }
                  else{
                    $kredit = number_format($tot_debit0_idr, 0, ",", ".");
                    $debit = "";
                    $total_kredit += $tot_debit0_idr;
                  }
                 
                }

            foreach($additional AS $add){
                    $nom_ad1 = "";
                    $nom_ad2 = "";
                    if($add->id_currency == 1){
                        $nom_add0 = $add->nominal * $dropdown_rate[1];
                      }elseif($add->id_currency == 2){
                        $nom_add0 = $add->nominal;
                      }
                    if($add->pos == 1){
                      $nom_ad1 = $nom_add0;
                      $total_kredit2 += $nom_add0;
                    }else{
                      $nom_ad2 = $nom_add0;
                      $total_debit2 += $nom_add0;
                    }
        }
        if($data_tour_book2[0]->stnb_discount == 1){
                  $status_price = $book['discount'];
                  $tot_disc_price1 =  (($total_debit * $data_tour_book2[0]->discount)/100);
                }elseif($data_tour_book2[0]->stnb_discount == 2) {
                 $tot_disc_price1 = $data_tour_book2[0]->discount;
                }
                
         $discount_tambahan = $this->global_models->get_query("SELECT id_product_tour_discount_tambahan,discount_request,status_discount,status"
          . " FROM product_tour_discount_tambahan"
          . " WHERE id_product_tour_book = '{$data_payment2[0]->id_product_tour_book}'"); 
        
           if($discount_tambahan[0]->discount_request){
                       if($discount_tambahan[0]->status_discount == 1){
                   
                    $tot_disc_tambahan =  (($total_debit * $discount_tambahan[0]->discount_request)/100);
                  }elseif($discount_tambahan[0]->status_discount == 2) {
                   $tot_disc_tambahan = $discount_tambahan[0]->discount_request;
                  }
           }
         
            
            if($data_payment2[0]->status_discount == 1){
                  $status_price = $book['discount'];
                  $tot_disc_price1 =  (($total_debit * $book['discount'])/100);
                }elseif($data_payment2[0]->status_discount == 1) {
                 $tot_disc_price1 = number_format($book['status_discount'],0,",",".");
                }
                $ppn = (1 * (($total_debit + $total_debit2 + $total_visa + $data_tax)-$tot_disc_price1)/100);
            $total_all =    (($total_debit + $total_debit2 + $ppn + $total_visa + $data_tax)- ($total_kredit2 + $total_kredit + $tot_disc_price1 + $tot_disc_tambahan) );

      if($total_all <= 0){
         
           $this->db->query("UPDATE product_tour_customer SET status = 3 WHERE id_product_tour_book ={$data_payment2[0]->id_product_tour_book} AND (status = 1 OR status = 2)");
          
      }
           $data_confirm = "Berhasil";
         }else{
           $data_confirm = "Gagal";
         }
       }
      
      if($list){
       
        foreach($list AS $ky => $ls){
          $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", FALSE, array("status" => 1));     
          
        
        $currency = $dropdown[$ls->id_currency];
        $channel = array(
                  1 => "Cash",
                  2 => "BCA",
                  3 => "Mega",
                  4 => "Mandiri",
                  5 => "CC");
        $status = array(1 => "Draft", 2 => "Confirm", 3 => "Not Paid");
        
          $book_detail[] = array(
            "first_name"            => $ls->first_name,
             "last_name"            => $ls->last_name,
            "kode_payment"    => $ls->kode,
            "book_code"       => $ls->book_code,
            "currency"        => $currency,
            "tanggal"         => $ls->tanggal,
            "status"          => $status[$ls->status],
            "nominal"         => $ls->nominal,
            "payment_type"    => $channel[$ls->payment],
            "id_user"         => $ls->id_users,
            "name_konfirm"     => $ls->name_konfirm,
            "name_tc"           => $ls->name_tc
          //  'currency_rate'   => $dropdown_rate[1],
          //  "passenger" => $passenger,
          );
        }
       
        $kirim = array(
          'status'  => 2,
          'payment'     => $book_detail,
          'confirm'     => $data_confirm,
          'total'       => $data_keseluruhan[0]->total
          
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   */
  function get_product_tour_information_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $tour = $this->global_models->get_query("SELECT A.*, B.kode AS kode_info, B.start_date, B.end_date, B.available_seat"
        . " ,B.adult_triple_twin, B.child_twin_bed, B.child_extra_bed, B.child_no_bed,B.sgl_supp,B.airport_tax,B.dp,B.stnb_dp,B.discount_tetap,B.id_product_tour_information,B.visa,B.less_ticket_adl, B.less_ticket_chl,B.seat_update, B.pax_book,"
        . "id_currency,stnb_discount_tetap,stnb_dp"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE B.kode = '{$pst['code']}'");
        
        $data_additional = $this->global_models->get("product_tour_optional_additional", array("id_product_tour_information" => $tour[0]->id_product_tour_information));
        foreach($data_additional AS $fo){
            $addtinal = $this->global_models->get("product_tour_master_additional", array("id_product_tour_master_additional" => $fo->id_product_tour_master_additional));
            $dt_additional[] = array(
              "id_product_tour_master_additional"         => $fo->id_product_tour_optional_additional,
              "name"                                      => $addtinal[0]->name,
              "nominal"                                   => $fo->nominal,
            );
          }
          
      if($tour){
        $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
        $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
        $currency = $this->global_models->get_field("master_currency", "code", array("id_master_currency" => $tour[0]->id_currency));
          if($tour[0]->stnb_discount_tetap == 1){
            $status_discount_tetap = "Persen";
          }elseif($tour[0]->stnb_discount_tetap == 2){
            $status_discount_tetap = "Nominal";
          }
          
          if($tour[0]->stnb_dp == 1){
            $stnb_dp = "Persen";
          }elseif($tour[0]->stnb_dp == 2){
            $stnb_dp = "Nominal";
          }
          
      /*    if($tour[0]->stnb_discount_tambahan == 1){
            $status_discount_tambahan = "Persen";
          }elseif($tour[0]->stnb_discount_tambahan == 2) {
            $status_discount_tambahan = "Nominal";
          } */
          
        $kirim = array(
          "status"            => 2,
          "tour"              => array(
          "code"              => $tour[0]->kode,
          "title"             => $tour[0]->title,
          "sub_title"         => $tour[0]->sub_title,
          "summary"           => $tour[0]->summary,
          "file_thumb"        => base_url()."files/antavaya/product_tour/".$tour[0]->file_thumb,
          "file"              => base_url()."files/antavaya/product_tour/".$tour[0]->file,
          "category"          => array("id" => $tour[0]->category, "name" => $category[$tour[0]->category]),
          "sub_category"      => array("id" => $tour[0]->sub_category, "name" => $sub_category[$tour[0]->sub_category]),
          "text"              => $tour[0]->note,
          "additional"        =>   $dt_additional,
          ),
          "information"   => array(
            "code"                        => $tour[0]->kode_info,
            "start_date"                  => $tour[0]->start_date,
            "end_date"                    => $tour[0]->end_date,
            "seat"                        => $tour[0]->available_seat,
            "dp"                          => $tour[0]->seat_update,
            "book"                        => $tour[0]->pax_book,
            "visa"                        => $tour[0]->visa,
            "less_ticket_adl"             => $tour[0]->less_ticket_adl,
            "less_ticket_chl"             => $tour[0]->less_ticket_chl,
            "status_dp"                    => $stnb_dp,
            "discount_tetap"                  => $tour[0]->discount_tetap,
            "status_discount_tetap"           => $status_discount_tetap,
          //  "discount_tambahan"               => $tour[0]->discount_tambahan,
          //  "status_discount_tambahan"        => $status_discount_tambahan,
            "price"                       => array(
            "currency"                    => $currency,
            "adult_triple_twin"           => $tour[0]->adult_triple_twin,
            "child_twin_bed"              => $tour[0]->child_twin_bed,
            "child_extra_bed"             => $tour[0]->child_extra_bed,
            "child_no_bed"                => $tour[0]->child_no_bed,
            "sgl_supp"                    => $tour[0]->sgl_supp,
            "tax_and_insurance"           => $tour[0]->airport_tax,
            ),
           // "committed_book"    => $tour[0]->dp
          ),
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
  
  function set_users(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $kirim = array(
        "name"            => $pst['name'],
        "pass"            => $pst['pass'],
        "email"           => $pst['email'],
        "id_privilege"    => $pst['id_privilege'],
        "type"            => $users[0]->type,
        "status"          => $pst['id_status_user'],
        "create_by_users" => $pst['create_by_users'],
        "create_date"     => $pst['create_date'],
      );
      $id_users = $this->global_models->insert("users_channel", $kirim);
      if($id_users){
        $kirim = array(
          'status'  => 2,
          'id_users'=> $id_users
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal Membuat Users'
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
  
  function update_users(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $kirim = array(
        "name"            => $pst['name'],
        "email"           => $pst['email'],
        "id_privilege"    => $pst['id_privilege'],
        "type"            => $users[0]->type,
        "status"          => $pst['id_status_user'],
        "update_by_users" => $pst['update_by_users'],
      );
      if($pst['pass'])
        $kirim["pass"] = $pst['pass'];
      $id_users = $this->global_models->update("users_channel", array("id_users" => $pst['id_users']), $kirim);
      if($id_users){
        $kirim = array(
          'status'  => 2,
          'id_users'=> $id_users
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Gagal Membuat Users'
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
  
  private function olah_code(&$kode, $table){
    $this->load->helper('string');
    $kode_data = random_string('alnum', 10);
    $kode = strtoupper($kode_data);
    $cek = $this->global_models->get_field($table, "id_{$table}", array("kode" => $kode));
    if($cek > 0){
      $this->olah_tour_code($kode, $table);
    }
  }
  
  private function olah_book_code(&$kode){
    $this->load->helper('string');
    $kode_random = random_string('alnum', 4);
    $st_upper = strtoupper($kode_random);
    $kode = "AV".$st_upper;
    $cek = $this->global_models->get_field("product_tour_book", "id_product_tour_book", array("kode" => $kode));
    if($cek > 0){
      $this->olah_tour_code($kode);
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
  
  /**
   * 2015-04-10
   * hendri
   * product tour
   */
  function get_seat_tour(){
      
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->get_connect("default");
      
    
      if($pst['search_data']){
        $seat_tour = $this->global_models->get_query("
        select sum(adult) as adult, 
        sum(child) as child, 
        sum(infant) as infant       
        from product_tour_book 
        where id_product_tour_information = {$pst['id_product_tour_information']} AND status > 1
        ");
         
        
      }
      
      $this->global_models->get_connect("default");
      
      if($seat_tour){
        
        $kirim[] = array(
              "status"    => "2",          
              "jml_adult"               => $seat_tour[0]->adult,
              "jml_child"               => $seat_tour[0]->child,
              "jml_inf"                 => $seat_tour[0]->infant,
          );
      
      }
      else{
        $kirim[] = array(
            "id"    => 0,
            "tour" => "No Found",
            "value" => "No Found",
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    //$kirim1  =$this->db->last_query();
    print json_encode($kirim);
    die;
  }
  
  function get_product_tour(){
      
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
      $sub_category = array(1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia",6 => "China", 7 => "New Zealand");
      $where = "1 = 1";
      $lIMIT = "";

      if($pst['title']){
          $where .= " AND LOWER(A.title) LIKE '%".strtolower($pst['title'])."%'"; 
      }
      
      if($pst['destination']){
          $where .= " AND LOWER(A.destination) LIKE '%".strtolower($pst['destination'])."%'"; 
      }
      
      if($pst['landmark']){
          $where .= " AND LOWER(A.landmark) LIKE '%".strtolower($pst['landmark'])."%'"; 
      }

      if($pst['kategori1']){
          $where .= " AND A.category  ='{$pst['kategori1']}' "; 
      }

      if($pst['kategori2']){
          $where .= " AND A.sub_category  = '{$pst['kategori2']}' "; 
      }

      if($pst['start_date']){
        if(!$pst['end_date']){
          $end_date = $pst['start_date'];
        }
        else{
          $end_date = $pst['end_date'];
        }
        //$where .= " AND B.start_date >= '".date("Y-m-d")."' AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
       $where .= " AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
	   $where_information = " AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
      }
      else{
        $where_information = " AND B.start_date >= '".date("Y-m-d")."'";
        $where .= " AND B.start_date >= '".date("Y-m-d")."'";
        
      }
	  
	  if($pst['id_store']){
          $where .= " AND A.id_store  = '{$pst['id_store']}' "; 
      }
	  if($pst['keberangkatan']){
          $where .= " AND B.keberangkatan  LIKE '%{$pst['keberangkatan']}%' "; 
      }
	  
	 if($pst['status']){
        if($pst['status'] == 9){
          $where .= " AND (B.status  = '5' OR B.status  = '1') ";
        }else{
         
           $where .= " AND B.status  = '{$pst['status']}' "; 
        }
          
      }
	  
	  if($pst['code']){
          $where .= " AND B.kode LIKE '%{$pst['code']}%' "; 
      }

      
       $data_limit = $this->global_models->get_query("SELECT COUNT(B.id_product_tour_information) AS total"
       . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.status=1 AND B.tampil=1 AND {$where} "
        . " ORDER BY push_selling ASC"
          );
        
        if($pst['start'] > 0){
          $start_limit = $pst['start'];
        }else{
          $start_limit = 0;
        }
        
      if($pst['limit'] OR $pst['start']){
         $lIMIT .= "LIMIT {$start_limit}, {$pst['limit']}";
      }
         
        
      $items = $this->global_models->get_query("SELECT A.kode,A.title,A.days,A.destination,A.landmark,"
        . " A.category,A.sub_category,A.id_product_tour,B.id_product_tour_information,"
        . " B.kode AS kode_information,B.start_date,B.end_date,B.status,"
        . " B.available_seat,B.adult_triple_twin,B.child_twin_bed,B.child_extra_bed,B.child_no_bed,B.sgl_supp,B.airport_tax"
		. " ,B.flt, B.sts, B.in, B.out, B.keberangkatan"
         . " ,C.title AS store, D.title AS store_region"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
		. " LEFT JOIN store AS C ON A.id_store = C.id_store"
		. " LEFT JOIN store_region AS D ON A.id_store_region = D.id_store_region"
        . " WHERE A.status=1 AND B.tampil=1 AND {$where} "
        
      //  . " GROUP BY A.id_product_tour"
        . " {$pst['sort']}"
         . " {$lIMIT}" );
      // $db_data = $this->db->last_query();
//     $this->debug($items, true);
      if($items){
        foreach($items AS $it){
//          $info = $this->global_models->get_query("SELECT B.*"
//            . " FROM product_tour_information AS B"
//            . " WHERE B.id_product_tour = '{$it->id_product_tour}'"
//            . " {$where_information}");
         
//       foreach($info AS $fo){
            $book = $this->global_models->get_query("SELECT count(A.kode) AS aid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)");
              
            $totl_book = $this->global_models->get_query("SELECT count(A.kode) AS bid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND A.status = 1 ");  
              
              $totl_committed = $this->global_models->get_query("SELECT count(A.kode) AS cid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)"); 
            
            $available_seat = $this->global_models->get_query("SELECT count(id_product_tour_customer) AS jml"
              . " FROM product_tour_customer"
              . " WHERE id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND sort > 0"
              . " AND (status = 2 OR status = 3)");
              
            $information = array(
              "code"                    => $it->kode_information,
			  "flight"                  => $it->flt,
              "sts"                     => $it->sts,
              "in"                      => $it->in,
              "out"                     => $it->out,
              "keberangkatan"           => $it->keberangkatan,
              "start_date"              => $it->start_date,
              "end_date"                => $it->end_date,
              "seat"                    => $it->available_seat,
              "total_seat_book"         => $totl_book[0]->bid,
              "total_seat_committed"    => $totl_committed[0]->cid,
              "available_seat"          => ($it->available_seat-$available_seat[0]->jml),
              "price"                 => array("adult_triple_twin" => $it->adult_triple_twin,"child_twin_bed" => $it->child_twin_bed,"child_extra_bed" => $it->child_extra_bed,"child_no_bed" => $it->child_no_bed,"sgl_supp" => $it->sgl_supp, "airport_tax" => $it->airport_tax),
            //  "committed_book"    => $fo->dp
            );
//         }
//         if($it->file_thumb){
//            $file_thumb2 = base_url()."files/antavaya/product_tour/".$it->file_thumb;
//          }else{
//            $file_thumb2 = "";
//          }
//          
//          if($it->file){
//            $file2 = base_url()."files/antavaya/product_tour/".$it->file;
//          }else{
//            $file2 = "";
//          } 
          
          $tour[] = array(
            "code"              => $it->kode,
            "title"             => $it->title,
            "days"              => $it->days,
		//	"store"             => $it->store,
		"store"             => $it->store_region,
			"status"            => $it->status,
          //  "sub_title"         => $it->sub_title,
          //  "summary"           => $it->summary,
            "destination"       => $it->destination,
            "landmark"          => $it->landmark,
         //   "file_thumb"        => $file_thumb2,
          //  "file"              => $file2,
            "category"          => array("id" => $it->category, "name" => $category[$it->category]),
            "sub_category"      => array("id" => $it->sub_category, "name" => $sub_category[$it->sub_category]),
         //   "text"              => $it->note,
            "information"       => $information
          );
        }
        $kirim = array(
            "status"        => 2,
            "tour"          => $tour,
            "total"          => $data_limit[0]->total,
       //     "query"         => $db_data,
        );
      }
      else{
        $kirim = array(
            "status"    => 3,
            "note" => "No Data",
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
//    $this->debug($kirim, true);
    print json_encode($kirim);
    die;
  }
  
  function get_change_book_tour(){
      
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
      $sub_category = array(1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia");
      $where = "1 = 1";
      $limit = "";
    $product_tour = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      if(!$pst['title'] AND !$pst['kategori1'] AND !$pst['kategori2'] AND !$pst['start_date'] AND !$pst['end_date']){
        
         $where .= " AND A.id_product_tour = '".$product_tour[0]->id_product_tour."'"; 
      }
      
      if($pst['title']){
          $where .= " AND LOWER(A.title) LIKE '%".strtolower($pst['title'])."%'"; 
      }

      if($pst['kategori1']){
          $where .= " AND A.category  ='{$pst['kategori1']}' "; 
      }

      if($pst['kategori2']){
          $where .= " AND A.sub_category  = '{$pst['kategori2']}' "; 
      }

      if($pst['start_date']){
        if(!$pst['end_date']){
          $end_date = NOW();
        }
        else{
          $end_date = $pst['end_date'];
        }
        $where .= " AND B.start_date >= '".date("Y-m-d")."' AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
        $where_information = " AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
      }
      else{
        $where_information = " AND B.start_date >= '".date("Y-m-d")."'";
       $where .= " AND B.start_date >= '".date("Y-m-d")."'";
      }

      if($pst['limit'] AND $pst['start']){
         $lIMIT .= "LIMIT {$pst['start']}, {$pst['limit']}";
      }
      
      
        $items = $this->global_models->get_query("SELECT A.*,B.kode AS kode_info,B.start_date AS start_date_info,B.end_date AS end_date_info,B.available_seat,B.id_product_tour_information"
        . " ,B.adult_triple_twin,B.child_twin_bed,B.child_extra_bed,B.child_no_bed,B.sgl_supp,B.airport_tax"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.status=1 AND B.tampil=1 AND {$where} "
        . " ORDER BY B.start_date ASC");
      // $data1 = $this->db->last_query();
//      $this->debug($items, true);
      if($product_tour[0]->id_product_tour > 0){
        foreach($items AS $it){
       
//            $book = $this->global_models->get_query("SELECT SUM(adult_triple_twin) AS a, SUM(child_twin_bed) AS c, SUM(child_extra_bed) AS d,SUM(child_no_bed) AS e,SUM(sgl_supp) AS f"
//              . " FROM product_tour_book"
//              . " WHERE id_product_tour_information = '{$it->id_product_tour_information}'"
//              . " AND (status = 2 OR status = 3)");
//              
            //  $data2 = $this->db->last_query();
             $book = $this->global_models->get_query("SELECT count(A.kode) AS aid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)");
            $search_information[] = array(
              "code"              => $it->kode_info,
              "start_date"        => $it->start_date_info,
              "end_date"          => $it->end_date_info,
              "seat"              => $it->available_seat,
              "available_seat"    => ($it->available_seat - ($book[0]->aid)),
              "price"             => array("adult_triple_twin" => $it->adult_triple_twin,"child_twin_bed" => $it->child_twin_bed,"child_extra_bed" => $it->child_extra_bed,"child_no_bed" => $it->child_no_bed,"sgl_supp" => $it->sgl_supp, "airport_tax" => $it->airport_tax),
          
            );
        
          $search_tour[] = array(
            "code"              => $it->kode,
            "title"             => $it->title,
          //  "sub_title"         => $it->sub_title,
           // "summary"           => $it->summary,
            "category"          => array("id" => $it->category, "name" => $category[$it->category]),
            "sub_category"      => array("id" => $it->sub_category, "name" => $sub_category[$it->sub_category]),
          //  "text"              => $it->note
          );
        }
       $product_tour_book = $this->global_models->get_query("SELECT A.*,A.adult_triple_twin AS total_person_adult_ttwin,A.child_twin_bed AS total_person_child_twin,A.child_extra_bed AS total_person_child_extra,A.child_no_bed AS total_person_child_no_bed,A.sgl_supp AS total_person_sgl_supp,A.stnb_discount"
        . " , B.title, B.sub_title, B.summary, B.file_thumb, B.category, B.sub_category, B.file, B.kode AS tour_code, B.note AS text"
        . " , C.kode AS tour_information_code, C.start_date, C.end_date, C.available_seat, C.adult_triple_twin, C.child_twin_bed, C.child_extra_bed, C.child_no_bed,C.sgl_supp,C.airport_tax,C.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_additional AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.kode = '{$pst['code']}'");
        if($product_tour_book[0]->id_product_tour_book > 0){
        $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
        $additional = $this->global_models->get("product_tour_additional", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
        $note_type = array(
          1 => "Adult Triple Twin",
          2 => "Child Twin Bed",
          3 => "Child Extra Bed",
          4 => "Child No Bed",
          5 => "SGL SUPP"
        );
        $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}' AND status < '3' ");
        
        foreach($passenger AS $psng){
          if($psng->status == 1){
            $status_cust = "Book";
          }elseif($psng->status == 2){
            $status_cust = "Committed Book";
          }elseif($psng->status == 3){
            $status_cust = "Lunas";
          }elseif($psng->status == 4){
            $status_cust = "Cancel";
          }elseif($psng->status == 5){
            $status_cust = "[Cancel] Waiting Approval";
          }
          
          $passenger_tour[] = array(
            "first_name"            => $psng->first_name,
            "last_name"             => $psng->last_name,
            "tanggal_lahir"         => $psng->tanggal_lahir,
            "type"                  => array("code" => $psng->type, "desc" => $note_type[$psng->type]),
            "room"                  => $psng->room,
            "no_passport"           => $psng->passport,
            "status"                => $status_cust,
            "customer_code"         => $psng->kode,
          );
        }
        
        foreach($additional AS $add){
          $additional_tour[] = array(
            "name_additional"            => $add->name,
            "nominal_additional"         => $add->nominal,
            "id_currency"                => $add->id_currency,
            "pos"                        => $add->pos
          );
        }
         if($product_tour_book[0]->stnb_discount == 1){
            $stnb_discount = "Persen";
          }elseif($product_tour_book[0]->stnb_discount == 2){
            $stnb_discount = "Nominal";
          }
         $own = $this->global_models->get("users_channel", array("id_users" => $product_tour_book[0]->id_users));
         
        $book = array(
          "code"            => $product_tour_book[0]->kode,
          "own_user"        => $own[0]->email,
          "first_name"      => $product_tour_book[0]->first_name,
          "last_name"       => $product_tour_book[0]->last_name,
          "telphone"        => $product_tour_book[0]->telphone,
          "email"           => $product_tour_book[0]->email,
          "tanggal"         => $product_tour_book[0]->tanggal,
          "status"          => $product_tour_book[0]->status,
          "agent"           => $product_tour_book[0]->id_users,
          "room"            => $product_tour_book[0]->room,
          "discount"        => $product_tour_book[0]->discount,
          "status_discount"         => $stnb_discount,
          "jumlah_person_adult_triple_twin"             => $product_tour_book[0]->total_person_adult_ttwin,
          "jumlah_person_child_twin"                    => $product_tour_book[0]->total_person_child_twin,
          "jumlah_person_child_extra"                   => $product_tour_book[0]->total_person_child_extra,
          "jumlah_person_child_no_bed"                  => $product_tour_book[0]->total_person_child_no_bed,
          "jumlah_person_sgl_supp"                      => $product_tour_book[0]->total_person_sgl_supp,
          "passenger"                                   => $passenger_tour,
          "additional"                                  => $additional_tour,
          "total_visa"                                  => $total_visa
        );
        
        $tour_payment = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " ORDER BY tanggal ASC");
        foreach($tour_payment AS $tp){
          $payment[] = array(
            "agent"           => $tp->id_users,
            "nominal"         => $tp->nominal,
            "tanggal"         => $tp->tanggal,
            "pos"             => $tp->pos,
            "status"          => $tp->status
          );
        }
         $information = array(
          "code"              => $product_tour_book[0]->tour_information_code,
          "start_date"        => $product_tour_book[0]->start_date,
          "end_date"          => $product_tour_book[0]->end_date,
          "seat"              => $product_tour_book[0]->available_seat,
          "price"             => array(
            "adult_triple_twin"   => $product_tour_book[0]->adult_triple_twin,
            "child_twin_bed"   => $product_tour_book[0]->child_twin_bed,
            "child_extra_bed"  => $product_tour_book[0]->child_extra_bed,
             "child_no_bed"    => $product_tour_book[0]->child_no_bed,
            "sgl_supp"    => $product_tour_book[0]->sgl_supp,
            "tax_and_insurance"   => $product_tour_book[0]->airport_tax,
            "visa"              => $product_tour_book[0]->visa)
        );
        $tour = array(
          "code"              => $product_tour_book[0]->tour_code,
          "title"             => $product_tour_book[0]->title,
        //  "sub_title"         => $product_tour_book[0]->sub_title,
         // "summary"           => $product_tour_book[0]->summary,
           "category"          => array("id" => $product_tour_book[0]->category, "name" => $category[$product_tour_book[0]->category]),
          "sub_category"      => array("id" => $product_tour_book[0]->sub_category, "name" => $sub_category[$product_tour_book[0]->sub_category]),
       //   "text"              => $product_tour_book[0]->text,
          "information"       => $information
        );
        $total_harga = ($product_tour_book[0]->total_person_adult_ttwin * $product_tour_book[0]->adult_triple_twin) + ($product_tour_book[0]->total_person_child_twin * $product_tour_book[0]->child_twin_bed) + ($product_tour_book[0]->total_person_child_extra * $product_tour_book[0]->child_extra_bed) + ($product_tour_book[0]->total_person_child_no_bed * $product_tour_book[0]->child_no_bed) + ($product_tour_book[0]->total_person_sgl_supp * $product_tour_book[0]->sgl_supp);
      }
      $data_update = "";
      if($pst["code_change_book"]){
        $name_user = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
      
        $this->load->model("json/mjson_tour");
   $data_id = $this->global_models->get("product_tour_information", array("kode" => $pst["code_change_book"]));
  if($data_id[0]->id_product_tour_information){

    $dt_prodct_book = $this->global_models->get("product_tour_book", array("kode" => $pst["code"]));

    if($dt_prodct_book[0]->id_product_tour_book > 0){

      if($dt_prodct_book[0]->status == 1){
    $this->mjson_tour->revert_all_payment($dt_prodct_book[0]->id_product_tour_book, $pst['id_users']);

    $update = array(
    "status"              => 8,
    "sort"                => 2000, 
    "update_by_users"     => $pst['id_users'],
    "update_date"         => date("Y-m-d H:i:s")
    );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book),$update);

      if($data_update > 0){
                
       $this->olah_book_code($kode);
       $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $dt_prodct_book[0]->id_users));
       if(!$id_store)
         $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $dt_prodct_book[0]->id_users));
       
        $kirim_book = array(
          "id_product_tour"             => $data_id[0]->id_product_tour,
          "id_product_tour_information" => $data_id[0]->id_product_tour_information,
          "id_users"                    => $dt_prodct_book[0]->id_users,
          "id_store"                    => $id_store,
          "kode"                        => $kode,
          "id_tour_pameran"             => $dt_prodct_book[0]->id_tour_pameran,
		  "id_master_sub_agent"         => $dt_prodct_book[0]->id_master_sub_agent,
          "first_name"                  => $dt_prodct_book[0]->first_name,
          "last_name"                   => $dt_prodct_book[0]->last_name,
          "telphone"                    => $dt_prodct_book[0]->telphone,
          "email"                       => $dt_prodct_book[0]->email,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "status"                      => 1,
          "adult_triple_twin"           => $dt_prodct_book[0]->adult_triple_twin,
          "child_twin_bed"              => $dt_prodct_book[0]->child_twin_bed,
          "child_extra_bed"             => $dt_prodct_book[0]->child_extra_bed,
          "child_no_bed"                => $dt_prodct_book[0]->child_no_bed,
          "sgl_supp"                    => $dt_prodct_book[0]->sgl_supp,
          "harga_adult_triple_twin"     => $data_id[0]->adult_triple_twin,
          "harga_child_twin_bed"        => $data_id[0]->child_twin_bed,
          "harga_child_extra_bed"       => $data_id[0]->child_extra_bed,
          "harga_child_no_bed"          => $data_id[0]->child_no_bed,
          "harga_single_adult"          => ($data_id[0]->adult_triple_twin + $data_id[0]->sgl_supp),
          "harga_airport_tax"           => $data_id[0]->airport_tax,
          "room"                        => $dt_prodct_book[0]->room,
          "discount"                    => $dt_prodct_book[0]->discount,
          "address"                     => $dt_prodct_book[0]->address,
          "stnb_discount"               => $dt_prodct_book[0]->stnb_discount,
          "DP"                          => $dt_prodct_book[0]->DP,
          "sort"                        => $dt_prodct_book[0]->sort,
          "additional_request"          => $dt_prodct_book[0]->additional_request,
          "status_additional_request"   => $dt_prodct_book[0]->status_additional_request,
          "create_by_users"             => $pst['id_users'],
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_new = $this->global_models->insert("product_tour_book", $kirim_book);
        
       
        $update2 = array(
            "id_product_tour_book_awal"              => $id_product_tour_book_new,
            );
        $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book),$update2);
             
        $update_cust = array(
            "status"              => 8,
            "sort"                => 2000,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
     $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book),$update_cust);
              
     
        $dt_customer =$this->global_models->get("product_tour_customer", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book));
          
        foreach ($dt_customer as $value) {
          
          $this->olah_code($kode2, "product_tour_customer");
          
          $kirim_product_tour_customer[] = array(
            "id_product_tour_book"          => $id_product_tour_book_new,
            "id_product_tour_information"   => $data_id[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $value->first_name,
            "last_name"                     => $value->last_name,
            "tempat_tanggal_lahir"          => $value->tempat_lahir,
            "tanggal_lahir"                 => $value->lahir,
            "room"                          => $value->room,
            "passport"                      => $value->passport,
            "status"                        => 1,    
            "type"                          => $value->type,
            "sort"                          => $value->sort,
            "visa"                          => $value->visa,    
            "less_ticket"                   => $value->less_ticket,
            "telphone"                      => $value->telphone,
            "place_of_issued"               => $value->place_of_issued,
            "date_of_issued"                => $value->date_of_issued,
            "date_of_expired"               => $value->date_of_expired,       
            "create_by_users"               => $pst['id_users'],
            "create_date"                   => date("Y-m-d H:i:s")
          );
          
          if($value->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book_new, $data_id[0]->id_product_tour_information, $value->first_name." ".$value->last_name, $value->type);
              }
          if($value->less_ticket){
            $this->mjson_tour->set_additional_less_ticket($id_product_tour_book_new, $data_id[0]->id_product_tour_information, $value->first_name." ".$value->last_name, $value->type);
          }
          
        }
        $this->global_models->insert_batch("product_tour_customer", $kirim_product_tour_customer);
        
        $this->mjson_tour->recount_payment($id_product_tour_book_new, $pst['id_users']);
         
       $kode_tour = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $dt_prodct_book[0]->id_product_tour_information));
       
       $kode_tour_new = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $data_id[0]->id_product_tour_information));
      
       
         $kirim_log_add = array(
          "id_product_tour_book"      => $id_product_tour_book_new,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}] Untuk Kode Book [{$pst["code"]}] Menjadi Kode Book [{$kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
       
       $kirim_log_add = array(
          "id_product_tour_book"      => $dt_prodct_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Cancel karena Pindah Tour oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}], Dari Kode Book [{$pst["code"]}] Menjadi Kode Book [{$kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       if($data_update > 0){
        $status_update = "2";
      }
          }

    }else{
      $update = array(
    "status"              => 7,
    "sort"                => 500,      
    "update_by_users"     => $pst['id_users'],
    "update_date"         => date("Y-m-d H:i:s")
    );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book),$update);

      if($data_update > 0){
                
       $this->olah_book_code($kode);
       $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $dt_prodct_book[0]->id_users));
       if(!$id_store)
         $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $dt_prodct_book[0]->id_users));
       
        $kirim_book = array(
          "id_product_tour"             => $data_id[0]->id_product_tour,
          "id_product_tour_information" => $data_id[0]->id_product_tour_information,
          "id_users"                    => $dt_prodct_book[0]->id_users,
          "id_store"                    => $id_store,
          "kode"                        => $kode,
          "id_tour_pameran"             => $dt_prodct_book[0]->id_tour_pameran,
          "first_name"                  => $dt_prodct_book[0]->first_name,
          "last_name"                   => $dt_prodct_book[0]->last_name,
          "telphone"                    => $dt_prodct_book[0]->telphone,
          "email"                       => $dt_prodct_book[0]->email,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "status"                      => 7,
          "adult_triple_twin"           => $dt_prodct_book[0]->adult_triple_twin,
          "child_twin_bed"              => $dt_prodct_book[0]->child_twin_bed,
          "child_extra_bed"             => $dt_prodct_book[0]->child_extra_bed,
          "child_no_bed"                => $dt_prodct_book[0]->child_no_bed,
          "sgl_supp"                    => $dt_prodct_book[0]->sgl_supp,
          "harga_adult_triple_twin"     => $data_id[0]->adult_triple_twin,
          "harga_child_twin_bed"        => $data_id[0]->child_twin_bed,
          "harga_child_extra_bed"       => $data_id[0]->child_extra_bed,
          "harga_child_no_bed"          => $data_id[0]->child_no_bed,
          "harga_single_adult"          => ($data_id[0]->adult_triple_twin + $data_id[0]->sgl_supp),
          "harga_airport_tax"           => $data_id[0]->airport_tax,
          "room"                        => $dt_prodct_book[0]->room,
          "discount"                    => $dt_prodct_book[0]->discount,
          "address"                     => $dt_prodct_book[0]->address,
          "stnb_discount"               => $dt_prodct_book[0]->stnb_discount,
          "DP"                          => $dt_prodct_book[0]->DP,
          "sort"                        => 1000,
          "additional_request"          => $dt_prodct_book[0]->additional_request,
          "status_additional_request"   => $dt_prodct_book[0]->status_additional_request,
          "create_by_users"             => $pst['id_users'],
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_new = $this->global_models->insert("product_tour_book", $kirim_book);
        
       
        $update2 = array(
            "id_product_tour_book_awal"              => $id_product_tour_book_new,
            );
        $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book),$update2);
             
        $update_cust = array(
            "status"              => 7,
            "sort"                => 500,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
     $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book),$update_cust);
              
        $dt_customer =$this->global_models->get("product_tour_customer", array("id_product_tour_book" =>$dt_prodct_book[0]->id_product_tour_book));
          
        foreach ($dt_customer as $value) {
          
          $this->olah_code($kode2, "product_tour_customer");
          
          $kirim_product_tour_customer[] = array(
            "id_product_tour_book"          => $id_product_tour_book_new,
            "id_product_tour_information"   => $data_id[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $value->first_name,
            "last_name"                     => $value->last_name,
            "tempat_tanggal_lahir"          => $value->tempat_lahir,
            "tanggal_lahir"                 => $value->lahir,
            "room"                          => $value->room,
            "passport"                      => $value->passport,
            "status"                        => 7,    
            "type"                          => $value->type,
            "sort"                          => $value->sort,
            "visa"                          => $value->visa,    
            "less_ticket"                   => $value->less_ticket,
            "telphone"                      => $value->telphone,
            "place_of_issued"               => $value->place_of_issued,
            "date_of_issued"                => $value->date_of_issued,
            "date_of_expired"               => $value->date_of_expired,       
            "create_by_users"               => $pst['id_users'],
            "create_date"                   => date("Y-m-d H:i:s")
          );
          
          if($value->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book_new, $data_id[0]->id_product_tour_information, $value->first_name." ".$value->last_name, $value->type);
              }
          if($value->less_ticket){
            $this->mjson_tour->set_additional_less_ticket($id_product_tour_book_new, $data_id[0]->id_product_tour_information, $value->first_name." ".$value->last_name, $value->type);
          }
          
        }
        $this->global_models->insert_batch("product_tour_customer", $kirim_product_tour_customer);
        
        $this->mjson_tour->recount_payment($id_product_tour_book_new, $pst['id_users']);
         
       $kode_tour = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $dt_prodct_book[0]->id_product_tour_information));
       
       $kode_tour_new = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $data_id[0]->id_product_tour_information));
      
       
         $kirim_log_add = array(
          "id_product_tour_book"      => $id_product_tour_book_new,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}] Untuk Kode Book Sebelumnya [{$pst["code"]}] Menjadi Kode Book [{$kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       $kirim_log_add = array(
          "id_product_tour_book"      => $dt_prodct_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Cancel Change Tour Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}] Untuk Kode Book Sebelumnya [{$pst["code"]}] Menjadi Kode Book [{$kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       if($data_update > 0){
        $status_update = "4";
      }
          }
    }

}

  }
      }
//      if($data_update > 0){
//        $status_update = "Data Berhasil Terupdate";
//      }else{
//        $status_update = "";
//      } 
        $kirim = array(
            'status'                  => 2,
            'data_search_tour'        => $search_tour,
            'tour'                    => $tour,
            'book'                    => $book,
            'payment'                 => $payment,
            'tour_info'               => $search_information,
            'status_update'           => $status_update,
            'kode_book'               => $kode
          //  'last'                     => array($dss,$dss2,$dss3)
        );
      }
      else{
        $kirim = array(
            "status"    => 3,
            "note" => "No Data",
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
//    $this->debug($kirim, true);
    print json_encode($kirim);
    die;
  }
  
   function get_change_person_book_tour(){
      
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
      $sub_category = array(1 => "Eropa", 2 => "Africa", 3 => "America", 4 => "Australia", 5 => "Asia");
      $where = "1 = 1";
      $limit = "";
      $product_tour_customer = $this->global_models->get("product_tour_customer", array("kode" => $pst['code']));
      $product_tour = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $product_tour_customer[0]->id_product_tour_book));
      if(!$pst['title'] AND !$pst['kategori1'] AND !$pst['kategori2'] AND !$pst['start_date'] AND !$pst['end_date']){
        
        
         $where .= " AND A.id_product_tour = '".$product_tour[0]->id_product_tour."'"; 
      }
      
      if($pst['title']){
          $where .= " AND LOWER(A.title) LIKE '%".strtolower($pst['title'])."%'"; 
      }

      if($pst['kategori1']){
          $where .= " AND A.category  ='{$pst['kategori1']}' "; 
      }

      if($pst['kategori2']){
          $where .= " AND A.sub_category  = '{$pst['kategori2']}' "; 
      }

      if($pst['start_date']){
        if(!$pst['end_date']){
          $end_date = NOW();
        }
        else{
          $end_date = $pst['end_date'];
        }
        $where .= " AND B.start_date >= '".date("Y-m-d")."' AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
        $where_information = " AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$end_date}')";
      }
      else{
        $where_information = " AND B.start_date >= '".date("Y-m-d")."'";
       $where .= " AND B.start_date >= '".date("Y-m-d")."'";
      }

      if($pst['limit'] AND $pst['start']){
         $lIMIT .= "LIMIT {$pst['start']}, {$pst['limit']}";
      }
      
      
        $items = $this->global_models->get_query("SELECT A.*,B.kode AS kode_info,B.start_date AS start_date_info,B.end_date AS end_date_info,B.available_seat,B.id_product_tour_information"
        . " ,B.adult_triple_twin,B.child_twin_bed,B.child_extra_bed,B.child_no_bed,B.sgl_supp,B.airport_tax"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.status=1 AND B.tampil=1 AND {$where} "
        . " ORDER BY B.start_date ASC");
      // $data1 = $this->db->last_query();
//      $this->debug($items, true);
      if($product_tour[0]->id_product_tour > 0){
        foreach($items AS $it){
       
//            $book = $this->global_models->get_query("SELECT SUM(adult_triple_twin) AS a, SUM(child_twin_bed) AS c, SUM(child_extra_bed) AS d,SUM(child_no_bed) AS e,SUM(sgl_supp) AS f"
//              . " FROM product_tour_book"
//              . " WHERE id_product_tour_information = '{$it->id_product_tour_information}'"
//              . " AND (status = 2 OR status = 3)");
//              
            //  $data2 = $this->db->last_query();
             $book = $this->global_models->get_query("SELECT count(A.kode) AS aid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)");
            $search_information[] = array(
              "code"              => $it->kode_info,
              "start_date"        => $it->start_date_info,
              "end_date"          => $it->end_date_info,
              "seat"              => $it->available_seat,
              "available_seat"    => ($it->available_seat - ($book[0]->aid)),
              "price"             => array("adult_triple_twin" => $it->adult_triple_twin,"child_twin_bed" => $it->child_twin_bed,"child_extra_bed" => $it->child_extra_bed,"child_no_bed" => $it->child_no_bed,"sgl_supp" => $it->sgl_supp, "airport_tax" => $it->airport_tax),
          
            );
        
          $search_tour[] = array(
            "code"              => $it->kode,
            "title"             => $it->title,
          //  "sub_title"         => $it->sub_title,
           // "summary"           => $it->summary,
            "category"          => array("id" => $it->category, "name" => $category[$it->category]),
            "sub_category"      => array("id" => $it->sub_category, "name" => $sub_category[$it->sub_category]),
          //  "text"              => $it->note
          );
        }
       $product_tour_book = $this->global_models->get_query("SELECT A.*,A.adult_triple_twin AS total_person_adult_ttwin,A.child_twin_bed AS total_person_child_twin,A.child_extra_bed AS total_person_child_extra,A.child_no_bed AS total_person_child_no_bed,A.sgl_supp AS total_person_sgl_supp,A.stnb_discount"
        . " , B.title, B.sub_title, B.summary, B.file_thumb, B.category, B.sub_category, B.file, B.kode AS tour_code, B.note AS text"
        . " , C.kode AS tour_information_code, C.start_date, C.end_date, C.available_seat, C.adult_triple_twin, C.child_twin_bed, C.child_extra_bed, C.child_no_bed,C.sgl_supp,C.airport_tax,C.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " WHERE A.id_product_tour_book = '{$product_tour[0]->id_product_tour_book}'");
        if($product_tour_book[0]->id_product_tour_book > 0){
        $passenger = $this->global_models->get("product_tour_customer", array("kode" => $pst['code']));
      
        $note_type = array(
          1 => "Adult Triple Twin",
          2 => "Child Twin Bed",
          3 => "Child Extra Bed",
          4 => "Child No Bed",
          5 => "SGL SUPP"
        );
        $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}' AND status < '3' ");
        
//        foreach($passenger AS $psng){
          if($passenger[0]->status == 1){
            $status_cust = "Book";
          }elseif($passenger[0]->status == 2){
            $status_cust = "Deposit ";
          }elseif($passenger[0]->status == 3){
            $status_cust = "Lunas";
          }elseif($passenger[0]->status == 4){
            $status_cust = "Cancel";
          }elseif($passenger[0]->status == 5){
            $status_cust = "[Cancel] Waiting Approval";
          }
          
       
         if($product_tour_book[0]->stnb_discount == 1){
            $stnb_discount = "Persen";
          }elseif($product_tour_book[0]->stnb_discount == 2){
            $stnb_discount = "Nominal";
          }
          
        $book = array(
          "code"            => $product_tour_book[0]->kode,
          "first_name"      => $passenger[0]->first_name,
          "last_name"       => $passenger[0]->last_name,
          "telphone"        => $passenger[0]->telphone,
          "tanggal_lahir"   => $passenger[0]->tanggal_lahir,
          "code_customer"   => $passenger[0]->kode,
          "status"          => $status_cust,
          "discount"        => $product_tour_book[0]->discount,
          "status_discount"         => $stnb_discount,
          "jumlah_person_adult_triple_twin"             => $product_tour_book[0]->total_person_adult_ttwin,
          "jumlah_person_child_twin"                    => $product_tour_book[0]->total_person_child_twin,
          "jumlah_person_child_extra"                   => $product_tour_book[0]->total_person_child_extra,
          "jumlah_person_child_no_bed"                  => $product_tour_book[0]->total_person_child_no_bed,
          "jumlah_person_sgl_supp"                      => $product_tour_book[0]->total_person_sgl_supp,
         // "passenger"                                    => $passenger,
          "total_visa"                                  => $total_visa
        );
        
        $tour_payment = $this->global_models->get_query("SELECT *"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " ORDER BY tanggal ASC");
        foreach($tour_payment AS $tp){
          $payment[] = array(
            "agent"           => $tp->id_users,
            "nominal"         => $tp->nominal,
            "tanggal"         => $tp->tanggal,
            "pos"             => $tp->pos,
            "status"          => $tp->status
          );
        }
         $information = array(
          "code"              => $product_tour_book[0]->tour_information_code,
          "start_date"        => $product_tour_book[0]->start_date,
          "end_date"          => $product_tour_book[0]->end_date,
          "seat"              => $product_tour_book[0]->available_seat,
          "price"             => array(
            "adult_triple_twin"   => $product_tour_book[0]->adult_triple_twin,
            "child_twin_bed"   => $product_tour_book[0]->child_twin_bed,
            "child_extra_bed"  => $product_tour_book[0]->child_extra_bed,
             "child_no_bed"    => $product_tour_book[0]->child_no_bed,
            "sgl_supp"    => $product_tour_book[0]->sgl_supp,
            "tax_and_insurance"   => $product_tour_book[0]->airport_tax,
            "visa"              => $product_tour_book[0]->visa)
        );
        $tour = array(
          "code"              => $product_tour_book[0]->tour_code,
          "title"             => $product_tour_book[0]->title,
        //  "sub_title"         => $product_tour_book[0]->sub_title,
         // "summary"           => $product_tour_book[0]->summary,
           "category"          => array("id" => $product_tour_book[0]->category, "name" => $category[$product_tour_book[0]->category]),
          "sub_category"      => array("id" => $product_tour_book[0]->sub_category, "name" => $sub_category[$product_tour_book[0]->sub_category]),
       //   "text"              => $product_tour_book[0]->text,
          "information"       => $information
        );
        $total_harga = ($product_tour_book[0]->total_person_adult_ttwin * $product_tour_book[0]->adult_triple_twin) + ($product_tour_book[0]->total_person_child_twin * $product_tour_book[0]->child_twin_bed) + ($product_tour_book[0]->total_person_child_extra * $product_tour_book[0]->child_extra_bed) + ($product_tour_book[0]->total_person_child_no_bed * $product_tour_book[0]->child_no_bed) + ($product_tour_book[0]->total_person_sgl_supp * $product_tour_book[0]->sgl_supp);
      }
      $data_update = "";
      
      
        $kirim = array(
            'status'                  => 2,
            'data_search_tour'        => $search_tour,
            'tour'                    => $tour,
            'book'                    => $book,
            'payment'                 => $payment,
            'tour_info'               => $search_information,
            'status_update'           => $status_update,
          //  'last'                     => array($dss,$dss2,$dss3)
        );
      }
      else{
        $kirim = array(
            "status"    => 3,
            "note" => "No Data",
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
//    $this->debug($kirim, true);
    print json_encode($kirim);
    die;
  }
  
  function insert_change_person_book_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      if($pst["code_change"]){
        $this->load->model("json/mjson_tour");
        $data_id = $this->global_models->get("product_tour_information", array("kode" => $pst["code_change"]));
    if($data_id[0]->id_product_tour_information){
    $dt_product_customer = $this->global_models->get("product_tour_customer", array("kode" => $pst["code"]));
    $dt_product_book = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $dt_product_customer[0]->id_product_tour_book));
  
    if($dt_product_customer[0]->id_product_tour_book > 0){

      if($dt_product_customer[0]->status == 1){
        
       $this->olah_book_code($kode);
       $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $dt_product_book[0]->id_users));
       if(!$id_store)
         $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $dt_product_book[0]->id_users));
       
        $kirim_book = array(
          "id_product_tour"             => $data_id[0]->id_product_tour,
          "id_product_tour_information" => $data_id[0]->id_product_tour_information,
          "id_users"                    => $dt_product_book[0]->id_users,
          "id_store"                    => $id_store,
          "kode"                        => $kode,
          "id_tour_pameran"             => $dt_product_book[0]->id_tour_pameran,
		  "id_master_sub_agent"         => $dt_product_book[0]->id_master_sub_agent,
          "first_name"                  => $dt_product_customer[0]->first_name,
          "last_name"                   => $dt_product_customer[0]->last_name,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "status"                      => 1,
          "adult_triple_twin"           => 0,
          "child_twin_bed"              => 0,
          "child_extra_bed"             => 0,
          "child_no_bed"                => 0,
          "sgl_supp"                    => 1,
          "harga_adult_triple_twin"     => $data_id[0]->adult_triple_twin,
          "harga_child_twin_bed"        => $data_id[0]->child_twin_bed,
          "harga_child_extra_bed"       => $data_id[0]->child_extra_bed,
          "harga_child_no_bed"          => $data_id[0]->child_no_bed,
          "harga_single_adult"          => ($data_id[0]->adult_triple_twin + $data_id[0]->sgl_supp),
          "harga_airport_tax"           => $data_id[0]->airport_tax,
          "room"                        => 1,
          "address"                     => $dt_product_book[0]->address,
          "stnb_discount"               => $dt_product_book[0]->stnb_discount,
          "DP"                          => $dt_product_book[0]->DP,
          "sort"                        => $dt_product_book[0]->sort,
          "additional_request"          => $dt_product_book[0]->additional_request,
          "status_additional_request"   => $dt_product_book[0]->status_additional_request,
          "create_by_users"             => $pst['id_users'],
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_new = $this->global_models->insert("product_tour_book", $kirim_book);
        
      //  $this->mjson_tour->recount_payment($id_product_tour_book_new, $pst['id_users']);
       
              
        if($dt_product_customer[0]->type == 1){
          $no_type_1 = 1;
        }elseif($dt_product_customer[0]->type == 2){
          $no_type_2 = 1;
        }elseif($dt_product_customer[0]->type == 3){
          $no_type_3 = 1;
        }elseif($dt_product_customer[0]->type == 4){
          $no_type_4 = 1;
        }elseif($dt_product_customer[0]->type == 5){
          $no_type_5 = 1;
        }
        $update2 = array(
            "id_product_tour_book_awal"   => $id_product_tour_book_new,
            "adult_triple_twin"           => ($dt_product_book[0]->adult_triple_twin - $no_type_1),
            "child_twin_bed"              => ($dt_product_book[0]->child_twin_bed - $no_type_2),
            "child_extra_bed"             => ($dt_product_book[0]->child_extra_bed - $no_type_3),
            "child_no_bed"                => ($dt_product_book[0]->child_no_bed - $no_type_4),
            "sgl_supp"                    => ($dt_product_book[0]->sgl_supp - $no_type_5),
            );
        
        $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$dt_product_book[0]->id_product_tour_book),$update2);
             
        $update_cust = array(
            "status"              => 8,
            "sort"                => 2000,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
     $this->global_models->update("product_tour_customer", array("id_product_tour_customer" =>$dt_product_customer[0]->id_product_tour_customer),$update_cust);
              
          $this->olah_code($kode2, "product_tour_customer");
          
          $kirim_product_tour_customer = array(
            "id_product_tour_book"          => $id_product_tour_book_new,
            "id_product_tour_information"   => $data_id[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $dt_product_customer[0]->first_name,
            "last_name"                     => $dt_product_customer[0]->last_name,
            "tempat_tanggal_lahir"          => $dt_product_customer[0]->tempat_lahir,
            "tanggal_lahir"                 => $dt_product_customer[0]->lahir,
            "room"                          => $dt_product_customer[0]->room,
            "passport"                      => $dt_product_customer[0]->passport,
            "status"                        => 1,    
            "type"                          => 5,
            "sort"                          => $dt_product_customer[0]->sort,
            "visa"                          => $dt_product_customer[0]->visa,    
            "less_ticket"                   => $dt_product_customer[0]->less_ticket,
            "telphone"                      => $dt_product_customer[0]->telphone,
            "place_of_issued"               => $dt_product_customer[0]->place_of_issued,
            "date_of_issued"                => $dt_product_customer[0]->date_of_issued,
            "date_of_expired"               => $dt_product_customer[0]->date_of_expired,       
            "create_by_users"               => $pst['id_users'],
            "create_date"                   => date("Y-m-d H:i:s")
          );
          
          $this->global_models->insert("product_tour_customer", $kirim_product_tour_customer);
          
          if($dt_product_customer[0]->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book_new, $dt_product_book[0]->id_product_tour_information, $dt_product_customer[0]->first_name." ".$dt_product_customer[0]->last_name, 5);
              }
          if($dt_product_customer[0]->less_ticket){
            $this->mjson_tour->set_additional_less_ticket($id_product_tour_book_new, $data_id[0]->id_product_tour_information, $dt_product_customer[0]->first_name." ".$dt_product_customer[0]->last_name, 5);
          }
       
          $this->mjson_tour->recount_payment($id_product_tour_book_new, $pst['id_users']);
          
        $this->mjson_tour->revert_all_payment($dt_product_book[0]->id_product_tour_book, $pst['id_users']);
        $this->mjson_tour->recount_payment($dt_product_book[0]->id_product_tour_book, $pst['id_users']);
              
       $kode_tour = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $data_id[0]->id_product_tour_information));
       
       $kode_tour_new = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $data_id[0]->id_product_tour_information));
      
       $harga_single_adult = number_format($data_id[0]->adult_triple_twin + $data_id[0]->sgl_supp);
         $kirim_log_add = array(
          "id_product_tour_book"      => $id_product_tour_book_new,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour per-pax Dari Kode Tour [{$kode_tour}] ke [{$kode_tour_new}] Untuk [{$dt_product_customer[0]->first_name} {$dt_product_customer[0]->last_name}][Single Adult][{$harga_single_adult}] Book Code baru adalah [{$kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
       
       $kirim_log_add = array(
          "id_product_tour_book"      => $dt_product_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Cancel Change Tour per-pax Dari Kode Tour [{$kode_tour}] Ke [{$kode_tour_new}] Untuk [{$dt_product_customer[0]->first_name} {$dt_product_customer[0]->last_name}][Single Adult][{$harga_single_adult}] Book Code baru adalah [{$kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       if($data_update > 0){
        $status_update = "2";
      }
//          }

    }else{
      
//      $update = array(
//    "status"              => 7,
//    "update_by_users"     => $pst['id_users'],
//    "update_date"         => date("Y-m-d H:i:s")
//    );
//      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$dt_product_book[0]->id_product_tour_book),$update);

                
       $this->olah_book_code($kode);
       $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
       if(!$id_store)
         $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
       
        $kirim_book = array(
          "id_product_tour"             => $data_id[0]->id_product_tour,
          "id_product_tour_information" => $data_id[0]->id_product_tour_information,
          "id_users"                    => $pst['id_users'],
          "id_store"                    => $id_store,
          "kode"                        => $kode,
          "id_tour_pameran"             => $dt_product_book[0]->id_tour_pameran,
          "first_name"                  => $dt_product_customer[0]->first_name,
          "last_name"                   => $dt_product_customer[0]->last_name,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "status"                      => 7,
          "adult_triple_twin"           => 0,
          "child_twin_bed"              => 0,
          "child_extra_bed"             => 0,
          "child_no_bed"                => 0,
          "sgl_supp"                    => 1,
          "harga_adult_triple_twin"     => $data_id[0]->adult_triple_twin,
          "harga_child_twin_bed"        => $data_id[0]->child_twin_bed,
          "harga_child_extra_bed"       => $data_id[0]->child_extra_bed,
          "harga_child_no_bed"          => $data_id[0]->child_no_bed,
          "harga_single_adult"          => ($data_id[0]->adult_triple_twin + $data_id[0]->sgl_supp),
          "harga_airport_tax"           => $data_id[0]->airport_tax,
          "room"                        => 1,
          "address"                     => $dt_product_book[0]->address,
          "stnb_discount"               => $dt_product_book[0]->stnb_discount,
          "DP"                          => $dt_product_book[0]->DP,
          "sort"                        => $dt_product_book[0]->sort,
          "additional_request"          => $dt_product_book[0]->additional_request,
          "status_additional_request"   => $dt_product_book[0]->status_additional_request,
          "create_by_users"             => $pst['id_users'],
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_new = $this->global_models->insert("product_tour_book", $kirim_book);
        
       
        $update2 = array(
            "id_product_tour_book_awal"              => $id_product_tour_book_new,
            );
        $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$dt_product_book[0]->id_product_tour_book),$update2);
             
          $update_cust = array(
            "status"              => 7,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
      $this->global_models->update("product_tour_customer", array("id_product_tour_customer" =>$dt_product_customer[0]->id_product_tour_customer),$update_cust);
              
          $this->olah_code($kode2, "product_tour_customer");
          
          $kirim_product_tour_customer = array(
            "id_product_tour_book"          => $id_product_tour_book_new,
            "id_product_tour_information"   => $data_id[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $dt_product_customer[0]->first_name,
            "last_name"                     => $dt_product_customer[0]->last_name,
            "tempat_tanggal_lahir"          => $dt_product_customer[0]->tempat_lahir,
            "tanggal_lahir"                 => $dt_product_customer[0]->lahir,
            "room"                          => $dt_product_customer[0]->room,
            "passport"                      => $dt_product_customer[0]->passport,
            "status"                        => 7,    
            "type"                          => 5,
            "sort"                          => $dt_product_customer[0]->sort,
            "visa"                          => $dt_product_customer[0]->visa,    
            "less_ticket"                   => $dt_product_customer[0]->less_ticket,
            "telphone"                      => $dt_product_customer[0]->telphone,
            "place_of_issued"               => $dt_product_customer[0]->place_of_issued,
            "date_of_issued"                => $dt_product_customer[0]->date_of_issued,
            "date_of_expired"               => $dt_product_customer[0]->date_of_expired,       
            "create_by_users"               => $pst['id_users'],
            "create_date"                   => date("Y-m-d H:i:s")
          );
          
          $this->global_models->insert("product_tour_customer", $kirim_product_tour_customer);
          
          if($dt_product_customer[0]->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book_new, $dt_product_book[0]->id_product_tour_information, $dt_product_customer[0]->first_name." ".$dt_product_customer[0]->last_name, 5);
              }
          if($dt_product_customer[0]->less_ticket){
            $this->mjson_tour->set_additional_less_ticket($id_product_tour_book_new, $data_id[0]->id_product_tour_information, $dt_product_customer[0]->first_name." ".$dt_product_customer[0]->last_name, 5);
          }
       
          $this->mjson_tour->recount_payment($id_product_tour_book_new, $pst['id_users']);
          
        $this->mjson_tour->revert_all_payment($dt_product_book[0]->id_product_tour_book, $pst['id_users']);
        $this->mjson_tour->recount_payment($dt_product_book[0]->id_product_tour_book, $pst['id_users']);
              
       $kode_tour = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $data_id[0]->id_product_tour_information));
       
       $kode_tour_new = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $data_id[0]->id_product_tour_information));
      
       $harga_single_adult = number_format($data_id[0]->adult_triple_twin + $data_id[0]->sgl_supp);
         $kirim_log_add = array(
          "id_product_tour_book"      => $id_product_tour_book_new,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour per-pax Dari Kode Tour [{$kode_tour}] ke [{$kode_tour_new}] Untuk [{$dt_product_customer[0]->first_name} {$dt_product_customer[0]->last_name}][Single Adult][{$harga_single_adult}] Book Code baru adalah [{$kode}] Butuh Approval ",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
       
       $kirim_log_add = array(
          "id_product_tour_book"      => $dt_product_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour per-pax Dari Kode Tour [{$kode_tour}] ke [{$kode_tour_new}] Untuk [{$dt_product_customer[0]->first_name} {$dt_product_customer[0]->last_name}][Single Adult][{$harga_single_adult}] Book Code baru adalah [{$kode}] Butuh Approval ",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       if($data_update > 0){
        $status_update = "4";
      }
        
    }

}

  }
      }
      
        $kirim = array(
            'status'                  => 2,
            'status_update'           => $status_update,
            'kode_book'               => $kode,
            'test'                    => $test,
          //  'last'                     => array($dss,$dss2,$dss3)
        );
    }else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
   
    print json_encode($kirim);
    die;
    
  }
  
  function insert_log_request_additional_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      //$pt_book = $this->global_models->get("product_tour_book", array("kode" => $pst["kode"]));
	   $sql = "SELECT A.id_product_tour_book,A.id_users"
        . " ,B.id_store,B.title"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.kode ='{$pst['kode']}'";
      $pt_book = $this->global_models->get_query($sql);
      $nama_store = $this->global_models->get_field("store", "title", array("id_store" => $pt_book[0]->id_store));
	              $users = $this->global_models->get("m_users", array("id_store" => $pt_book[0]->id_store));
           //  $users = $this->global_models->get("m_users", array("id_store" => 7));
            foreach ($users as $val) {
              $email_user .= $val->email.",";
            }
      $kirim = array(
            "id_product_tour_book"      => $pt_book[0]->id_product_tour_book,
            "name"                        => $pst['name'],
            "note"                        => $pst['note'],
            "tanggal"                     => date("Y-m-d H:i:s"),
              "create_by_users"           => $pst['create_by_users'],
            "create_date"                 => date("Y-m-d"),
        );

        $id_log_history_request_discount = $this->global_models->insert("product_tour_log_request_additional", $kirim);
      
        $kirim = array(
            'status'                  => 2,
			 'email'                   => $email_user,
            'store'                   => $nama_store,
            'link'                    => base_url()
        );
    }else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
   
    print json_encode($kirim);
    die;
    
  }
  

  function get_customer_tour(){
      
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->get_connect("default");
      
    
      if($pst['search_total_customer_tour']){
          $WHERE = "";
         
          
           if($pst['first_name']){
              $WHERE .= " AND LOWER(first_name) LIKE '%".trim(strtolower($pst['first_name']))."%'";
          } 
          
          if($pst['last_name']){
              $WHERE .= " AND LOWER(last_name) LIKE '%".trim(strtolower($pst['last_name']))."%'";
          } 
          
          if($pst['notelp']){
              $WHERE .= " AND telphone LIKE '".($pst['notelp'])."%'";
          } 
          
          if($pst['status']){
              $WHERE .= " AND status =".$pst['status'];
          }
          
         if($pst[0]['id_product_tour_information']){
              $WHERE .= " AND id_product_tour_information =".$pst[0]['id_product_tour_information'];
          }
          
        // $jumlah_list = $this->global_models->get_field("product_tour_book", "count(id_product_tour_book)", array());
       
        $jumlah_list = $this->global_models->get_query("
        SELECT 	count(id_product_tour_book) as total
        FROM product_tour_book 
        WHERE 1=1 {$WHERE}
        ");
      
         $kirim = array(
         'status'  => 2,    
        'data'  => $jumlah_list,
             
      );
      } elseif ($pst['search_data_customer_tour']) {
          
         
           $WHERE = "";
          $lIMIT = " {$pst['start']}, 10";
          
           if($pst['first_name']){
              $WHERE .= " AND LOWER(first_name) LIKE '%".trim(strtolower($pst['first_name']))."%'";
          } 
          
          if($pst['last_name']){
              $WHERE .= " AND LOWER(last_name) LIKE '%".trim(strtolower($pst['last_name']))."%'";
          } 
          
          if($pst['notelp']){
              $WHERE .= " AND telphone LIKE '".($pst['notelp'])."%'";
          } 
          
          if($pst['status']){
              $WHERE .= " AND status =".$pst['status'];
          }
          
          if($pst[0]['id_product_tour_information']){
              $WHERE .= " AND id_product_tour_information =".$pst[0]['id_product_tour_information'];
          }
          
           $list_data = $this->global_models->get_query("
        SELECT 	*
        FROM product_tour_book 
        WHERE 1=1 {$WHERE}
        LIMIT {$lIMIT}
        ");
        
      
    
         $kirim = array(
         'status'  => 2,    
        'data'  => $list_data);
        } elseif($pst['search_detail_customer_tour']){
            $data_cust = $this->global_models->get("product_tour_book",array("id_product_tour_book" => $pst['id_product_tour_book']));
            
            $cust_adult = $this->global_models->get("product_tour_customer",array("id_product_tour_book" => $pst['id_product_tour_book'],"type" => 1));
            $cust_child = $this->global_models->get("product_tour_customer",array("id_product_tour_book" => $pst['id_product_tour_book'],"type" => 2));
            $cust_inf = $this->global_models->get("product_tour_customer",array("id_product_tour_book" => $pst['id_product_tour_book'],"type" => 3));
            
         $kirim = array(
         'status'  => 2,    
         'data'  => array("first_name" =>$data_cust[0]->first_name,
                         "last_name" =>$data_cust[0]->last_name,
                         "telp" =>$data_cust[0]->telphone,
                         "email" =>$data_cust[0]->email,
                         "customer_adult" =>  $cust_adult,
                         "customer_child" =>  $cust_child,
                         "customer_inf" =>  $cust_inf,
            ));
        }elseif($pst['search_detail_customer_tour_payment']){
            
            $data_cust = $this->global_models->get("product_tour_book",array("id_product_tour_book" => $pst['id_product_tour_book']));
            $data_payment = $this->global_models->get("product_tour_book_payment",array("id_product_tour_book" => $pst['id_product_tour_book']));
            
           $tour_inf = $this->global_models->get("product_tour_information",array("id_product_tour_information" => $data_cust[0]->id_product_tour_information));
            
            $kirim = array(
            'status'        => 2,    
            'data'          => $data_cust,
            'data_payment'  => $data_payment,
            'tour_inf'      => $tour_inf
            );
        }elseif ($pst['save_data_payment']) {
          
          if($pst['payment'] >= $pst['dp']){
              $pos = 2;
              $status = 2;
          }  elseif($pst['payment'] < $pst['dp']){
              $status = 1;
              $pos = 2;
          }
          
          if ($pst['payment'] == $pst['total_price']) {
              $status = 3;
          }
         
           $update_data = array(
                "status"                            => $status,
                );
           
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $pst['id_product_tour_book']),$update_data);
     
          
          $kirim_info = array(
                "id_product_tour_book"              => $pst['id_product_tour_book'],
                "id_users"                          => $pst['id_users'],
                "nominal"                             => $pst['nominal'],
                "tanggal"                           => date("Y-m-d H:i:s"),
                "pos"                             => $pos,
                "status"                             => $status,
                "create_by_users"                   => $pst['id_users'],
                "create_date"                       => date("Y-m-d H:i:s")
                );
                 
               $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_info);
               $kirim = array('status' => 2,
               'id_product_tour_book_payment' => $id_product_tour_book_payment);
      }
      
      
     // $this->global_models->get_connect("default");
   
     
      
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    //$kirim1  =$this->db->last_query();
    print json_encode($kirim);
    die;
  }
  
  function insert_book_tour(){
    $this->load->model("json/mjson_tour");
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_information = $this->global_models->get("product_tour_information", array("kode" => $pst['code']));
      if($tour_information[0]->id_product_tour_information > 0){
        $adult_triple_twin  = json_decode(str_replace('\"', '"', $pst['adult_triple_twin']));
        $child_tb  = json_decode(str_replace('\"', '"', $pst['child_tb']));
        $child_eb = json_decode(str_replace('\"', '"', $pst['child_eb']));
        $child_nb = json_decode(str_replace('\"', '"', $pst['child_nb']));
        $single_supp = json_decode(str_replace('\"', '"', $pst['single_supp']));
        $additional = json_decode(str_replace('\"', '"', $pst['additional']));
          $single_adult = $tour_information[0]->adult_triple_twin + $tour_information[0]->sgl_supp;
        $total = (count($adult_triple_twin)*$tour_information[0]->adult_triple_twin) + (count($child_tb)*$tour_information[0]->child_twin_bed) + (count($child_eb)*$tour_information[0]->child_extra_bed) + (count($child_nb)*$tour_information[0]->child_no_bed) + (count($single_supp)*$single_adult);
          $currency = $this->global_models->get_field("master_currency", "id_master_currency", array("code" => $pst['currency']));
       
        if($pst['status_dp'] == "Persen"){
          $dp = ($pst['dp'] * $pst['total_price'])/100;
        }elseif($pst['status_dp'] == "Nominal"){
         $dp = $pst['dp'];
        }
        
//        if($pst['note_additional']){
//          $nt_additional = $pst['note_additional'];
//          $status_nt_additional = 1;
//        }else{
//          $nt_additional = "";
//          $status_nt_additional = 0;
//        }
        if($pst['status_discount'] == "Persen"){
          $status_discount = 1;
        }elseif($pst['status_discount'] == "Nominal"){
          $status_discount = 2;
        }
        
         $sort_bk = $this->global_models->get_query("SELECT max(sort) AS book_sort"
        . " FROM product_tour_book"
        . " WHERE id_product_tour_information = '{$tour_information[0]->id_product_tour_information}' AND status='1' ");
        
        if($sort_bk[0]->book_sort > 0){
          $sort_book  = $sort_bk[0]->book_sort + 1;
        }else{
          $sort_book  = "100";
        }
        
        $kirim_tour_pax = array(
          "first_name"                  => $pst['first_name'],
          "last_name"                   => $pst['last_name'],
          "telphone"                    => $pst['telp'],
          "email"                       => $pst['email'],
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_tour_pax = $this->global_models->insert("tour_pax", $kirim_tour_pax);
        
        $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
        if(!$id_store)
          $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
        
        $kirim_tour_leads = array(
          "id_tour_pax"                 => $id_tour_pax,
          "id_users"                    => $pst['id_users'],
          "id_store"                    => $id_store,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "status"                      => 3,
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_tour_leads = $this->global_models->insert("tour_leads", $kirim_tour_leads);
        
        $this->olah_book_code($kode);
        $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
       if(!$id_store)
         $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
       
        $kirim_book = array(
          "id_product_tour"             => $tour_information[0]->id_product_tour,
          "id_product_tour_information" => $tour_information[0]->id_product_tour_information,
          "id_master_sub_agent"         => $pst['id_master_sub_agent'],
          "id_users"                    => $pst['id_users'],
          "id_store"                    => $id_store,
          "kode"                        => $kode,
          "first_name"                  => $pst['first_name'],
          "last_name"                   => $pst['last_name'],
          "telphone"                    => $pst['telp'],
          "email"                       => $pst['email'],
          "tanggal"                     => date("Y-m-d H:i:s"),
          "status"                      => 1,
          "adult_triple_twin"           => count($adult_triple_twin),
          "child_twin_bed"              => count($child_tb),
          "child_extra_bed"             => count($child_eb),
          "child_no_bed"                => count($child_nb),
          "sgl_supp"                    => count($single_supp),
          "harga_adult_triple_twin"     => $tour_information[0]->adult_triple_twin,
          "harga_child_twin_bed"        => $tour_information[0]->child_twin_bed,
          "harga_child_extra_bed"       => $tour_information[0]->child_extra_bed,
          "harga_child_no_bed"          => $tour_information[0]->child_no_bed,
          "harga_single_adult"          => ($tour_information[0]->adult_triple_twin + $tour_information[0]->sgl_supp),
          "harga_airport_tax"           => $tour_information[0]->airport_tax,
          "room"                        => $pst['jumlah_room'],
          "discount"                    => $pst['discount'],
          "address"                     => $pst['address'],
          "stnb_discount"               => $status_discount,
          "DP"                          => $dp,
          "sort"                        => $sort_book,
          "additional_request"          => $nt_additional,
          "status_additional_request"   => $status_nt_additional,
          "remark"                      => $pst['remark'],
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        
        if($pst['id_tour_pameran']){
          $kirim_book['id_tour_pameran'] = $pst['id_tour_pameran'];
        }
        else{
          $tour_pameran = $this->global_models->get_query("SELECT A.id_tour_pameran"
          . " FROM tour_pameran_users AS A"
          . " LEFT JOIN tour_pameran AS B ON A.id_tour_pameran = B.id_tour_pameran"
          . " WHERE A.id_users = '{$pst['id_users']}'"
          . " AND ('".date("Y-m-d")."' BETWEEN B.date_start AND B.date_end)");
          if($tour_pameran){
            $kirim_book['id_tour_pameran'] = $tour_pameran[0]->id_tour_pameran;
          }
        }
        
        $id_product_tour_book = $this->global_models->insert("product_tour_book", $kirim_book);
        
        $name_user = $this->global_models->get_field("m_users", "name", array("id_users" => $pst['id_users']));
       
//          $kirim_addtional = array(
//            "id_product_tour_book"        => $id_product_tour_book,
//            "id_users"                    => $pst['id_users'],
//            "name"                        => $name_user,
//            "note"                        => "Request Additional ".$nt_additional,
//            "status"                      => 1,
//            "tanggal"                     => date("Y-m-d H:i:s"),
//            "create_by_users"             => $pst['id_users'],
//            "create_date"                 => date("Y-m-d"),
//        );
//
//        $this->global_models->insert("product_tour_log_request_additional", $kirim_addtional);
    
//        if($pst['discount_request']){
//            $kirim_discount = array(
//          "id_product_tour_book"        => $id_product_tour_book,
//          "discount_request"            => $pst['discount_request'],
//          "status"                      => 1,
//          "status_discount"             => $pst['stnb_discount_req'],
//           "id_currency"                => $currency,     
//          "create_by_users"             => $pst['id_users'],
//          "create_date"                 => date("Y-m-d H:i:s")
//          );
//          $this->global_models->insert("product_tour_discount_tambahan", $kirim_discount);
//        } 
        $sort_book_adult = 0;
          foreach($adult_triple_twin AS $adl){
            if(isset($adl)){
              $sort_book_adult = $sort_book_adult +1;
              $sort_bk_adult = $this->global_models->get_query("SELECT max(A.sort) AS cust_sort"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS C ON A.id_product_tour_book = C.id_product_tour_book"
              . " WHERE C.id_product_tour_information = '{$tour_information[0]->id_product_tour_information}' AND A.status='1' ");
              $sort_book_adult2 = $sort_bk_adult[0]->cust_sort;
              if($sort_book_adult2 > 0){
                $sort_book_adult3  = $sort_book_adult2 + $sort_book_adult;
              }else{
                $sort_book_adult3  = 99 + $sort_book_adult;
              }
                
        
              $this->olah_code($kode2, "product_tour_customer");
              $kirim_product_tour_customer[] = array(
            "id_product_tour_book"          => $id_product_tour_book,
            "id_product_tour_information"   => $tour_information[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $adl->first_name,
            "last_name"                     => $adl->last_name,
            "tempat_tanggal_lahir"          => $adl->tempat_lahir,
            "tanggal_lahir"                 => $adl->lahir,
            "room"                          => $adl->room,
            "passport"                      => $adl->passport,
            "status"                        => 1,    
            "type"                          => 1,
            "sort"                          => $sort_book_adult3,  
            "visa"                          => $adl->visa,    
            "less_ticket"                   => $adl->less_ticket,
            "telphone"                      => $adl->telp,
            "place_of_issued"               => $adl->place_issued,
            "date_of_issued"                => $adl->date_issued,
            "date_of_expired"               => $adl->date_expired,       
            "create_by_users"               => $users[0]->id_users,
            "create_date"                   => date("Y-m-d H:i:s")
          );
              if($adl->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book, $tour_information[0]->id_product_tour_information, $adl->first_name." ".$adl->last_name, 1);
              }
              if($adl->less_ticket){
                $this->mjson_tour->set_additional_less_ticket($id_product_tour_book, $tour_information[0]->id_product_tour_information, $adl->first_name." ".$adl->last_name, 1);
              }
            }
        }
         $sort_book_child_tb = 0;
          foreach($child_tb AS $chd_tb){
            if(isset($chd_tb)){
               $sort_book_child_tb = $sort_book_child_tb +1;
             
               $sort_book_adult3 = $sort_book_adult3 + $sort_book_child_tb;
              
              $this->olah_code($kode2, "product_tour_customer");
              $kirim_product_tour_customer[] = array(
            "id_product_tour_book"          => $id_product_tour_book,
            "id_product_tour_information"   => $tour_information[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $chd_tb->first_name,
            "last_name"                     => $chd_tb->last_name,
            "tempat_tanggal_lahir"          => $chd_tb->tempat_lahir,    
            "tanggal_lahir"                 => $chd_tb->lahir,
            "room"                          => $chd_tb->room,
            "passport"                      => $chd_tb->passport,
            "status"                        => 1,    
            "type"                          => 2,
            "sort"                          => $sort_book_adult3,    
            "visa"                          => $chd_tb->visa,
            "less_ticket"                   => $chd_tb->less_ticket,
            "telphone"                      => $chd_tb->telp,
            "place_of_issued"               => $chd_tb->place_issued,
            "date_of_issued"                => $chd_tb->date_issued,
            "date_of_expired"               => $chd_tb->date_expired,       
            "create_by_users"               => $users[0]->id_users,
            "create_date"                   => date("Y-m-d H:i:s")
          );
              if($chd_tb->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book, $tour_information[0]->id_product_tour_information, $chd_tb->first_name." ".$chd_tb->last_name, 2);
              }
              if($chd_tb->less_ticket){
                $this->mjson_tour->set_additional_less_ticket($id_product_tour_book, $tour_information[0]->id_product_tour_information, $chd_tb->first_name." ".$chd_tb->last_name, 2);
              }
            }
        }
        
         $sort_book_child_eb = 0;
          foreach($child_eb AS $chd_eb){
            if(isset($chd_eb)){
              $sort_book_child_eb = $sort_book_child_eb +1;
              
              $sort_book_adult3 = $sort_book_adult3 + $sort_book_child_eb;
              
              $this->olah_code($kode2, "product_tour_customer");
              $kirim_product_tour_customer[] = array(
            "id_product_tour_book"          => $id_product_tour_book,
            "id_product_tour_information"   => $tour_information[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $chd_eb->first_name,
            "last_name"                     => $chd_eb->last_name,
            "tempat_tanggal_lahir"          => $chd_eb->tempat_lahir,    
            "tanggal_lahir"                 => $chd_eb->lahir,
            "room"                          => $chd_eb->room,
            "passport"                      => $chd_eb->passport,
            "status"                        => 1,    
            "type"                          => 3,
            "sort"                          => $sort_book_adult3,    
            "visa"                          => $chd_eb->visa,
            "less_ticket"                   => $chd_eb->less_ticket,
            "telphone"                      => $chd_eb->telp,
            "place_of_issued"               => $chd_eb->place_issued,
            "date_of_issued"                => $chd_eb->date_issued,
            "date_of_expired"               => $chd_eb->date_expired,    
            "create_by_users"               => $users[0]->id_users,
            "create_date"                   => date("Y-m-d H:i:s")
          );
              if($chd_eb->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book, $tour_information[0]->id_product_tour_information, $chd_eb->first_name." ".$chd_eb->last_name, 3);
              }
              if($chd_eb->less_ticket){
                $this->mjson_tour->set_additional_less_ticket($id_product_tour_book, $tour_information[0]->id_product_tour_information, $chd_eb->first_name." ".$chd_eb->last_name, 3);
              }
            }
        }
        
            $sort_book_child_nb = 0;
          foreach($child_nb AS $chd_nb){
               if(isset($chd_nb)){
           $sort_book_child_nb = $sort_book_child_nb + 1;
              $sort_book_adult3 =  $sort_book_adult3 + $sort_book_child_nb;
              
              $this->olah_code($kode2, "product_tour_customer");
              $kirim_product_tour_customer[] = array(
            "id_product_tour_book"          => $id_product_tour_book,
            "id_product_tour_information"   => $tour_information[0]->id_product_tour_information,    
            "kode"                          => $kode2,
            "first_name"                    => $chd_nb->first_name,
            "last_name"                     => $chd_nb->last_name,
            "tempat_tanggal_lahir"          => $chd_nb->tempat_lahir,    
            "tanggal_lahir"                 => $chd_nb->lahir,
            "room"                          => $chd_nb->room,
            "passport"                      => $chd_nb->passport,
            "status"                        => 1,    
            "type"                          => 4,
            "sort"                          => $sort_book_adult3,    
            "visa"                          => $chd_nb->visa,
            "less_ticket"                   => $chd_nb->less_ticket,
            "telphone"                      => $chd_nb->telp,
            "place_of_issued"               => $chd_nb->place_issued,
            "date_of_issued"                => $chd_nb->date_issued,
            "date_of_expired"               => $chd_nb->date_expired,    
            "create_by_users"               => $users[0]->id_users,
            "create_date"                   => date("Y-m-d H:i:s")
          );
              if($chd_nb->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book, $tour_information[0]->id_product_tour_information, $chd_nb->first_name." ".$chd_nb->last_name, 4);
              }
              if($chd_nb->less_ticket){
                $this->mjson_tour->set_additional_less_ticket($id_product_tour_book, $tour_information[0]->id_product_tour_information, $chd_nb->first_name." ".$chd_nb->last_name, 4);
              }
            }
        }
        
        $sort_book_single_supp = 0; 
          foreach($single_supp AS $sgl){
            if(isset($sgl)){
              $sort_book_single_supp = $sort_book_single_supp + 1;
              
              $sort_book_adult3 = $sort_book_adult3 + $sort_book_single_supp;
              
              $this->olah_code($kode2, "product_tour_customer");
              $kirim_product_tour_customer[] = array(
                "id_product_tour_book"          => $id_product_tour_book,
                "id_product_tour_information"   => $tour_information[0]->id_product_tour_information,    
                "kode"                          => $kode2,
                "first_name"                    => $sgl->first_name,
                "last_name"                     => $sgl->last_name,
                "tempat_tanggal_lahir"          => $sgl->tempat_lahir,    
                "tanggal_lahir"                 => $sgl->lahir,
                "room"                          => $sgl->room,
                "passport"                      => $sgl->passport,
                "status"                        => 1,    
                "type"                          => 5,
                "sort"                          => $sort_book_adult3,    
                "visa"                          => $sgl->visa,
                "less_ticket"                   => $sgl->less_ticket,
                "telphone"                      => $sgl->telp,
                "place_of_issued"               => $sgl->place_issued,
                "date_of_issued"                => $sgl->date_issued,
                "date_of_expired"               => $sgl->date_expired,    
                "create_by_users"               => $users[0]->id_users,
                "create_date"                   => date("Y-m-d H:i:s")
              );
              if($sgl->visa){
                $this->mjson_tour->set_additional_visa($id_product_tour_book, $tour_information[0]->id_product_tour_information, $sgl->first_name." ".$sgl->last_name, 5);
              }
              if($sgl->less_ticket){
                $this->mjson_tour->set_additional_less_ticket($id_product_tour_book, $tour_information[0]->id_product_tour_information, $sgl->first_name." ".$sgl->last_name, 5);
              }
            }
        }
        
        $this->global_models->insert_batch("product_tour_customer", $kirim_product_tour_customer);
        
        if($additional){
          foreach($additional AS $key_add => $val_add){
            if(isset($val_add)){
              $additonal_val = $this->global_models->get_query("SELECT A.nominal,B.name"
          . " FROM product_tour_optional_additional AS A"
          . " LEFT JOIN product_tour_master_additional AS B ON A.id_product_tour_master_additional = B.id_product_tour_master_additional"
          . " WHERE id_product_tour_optional_additional = '{$val_add->id}'");
         
            $kirim_product_tour_additional[] = array(
            "id_product_tour_book"          => $id_product_tour_book,
            "name"                          => $additonal_val[0]->name,
            "nominal"                       => $additonal_val[0]->nominal,
            "create_by_users"               => $users[0]->id_users,
            "create_date"                   => date("Y-m-d H:i:s")
            );
          }
          
        }
        $this->global_models->insert_batch("product_tour_additional", $kirim_product_tour_additional);
        }
        
          $dtjml_adult_triple_twin            = count($adult_triple_twin);
          $dtjml_child_twin_bed               = count($child_tb);
          $dtjml_child_extra_bed              = count($child_eb);
          $dtjml_child_no_bed                 = count($child_nb);
          $dtjml_sgl_supp                     = count($single_supp);
          
          
          if($dtjml_adult_triple_twin){
            $adl_nominal  = $dtjml_adult_triple_twin * $tour_information[0]->adult_triple_twin;
            $kirim_payment = array(
              "id_product_tour_book"        => $id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $adl_nominal,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 0,
              "id_currency"                 => $currency,
              "note"                        => "Adult Triple Twin ".$dtjml_adult_triple_twin." x ".number_format($tour_information[0]->adult_triple_twin),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment);
        
          }
          if($dtjml_child_twin_bed){
            $ctb_nominal  = $dtjml_child_twin_bed * $tour_information[0]->child_twin_bed;
            $kirim_payment = array(
              "id_product_tour_book"        => $id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $ctb_nominal,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 0,
              "id_currency"                 => $currency,
              "note"                        => "Child Twin Bed ".$dtjml_child_twin_bed." x ".number_format($tour_information[0]->child_twin_bed),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment);
        
          }
          
          if($dtjml_child_extra_bed){
            $ceb_nominal  = $dtjml_child_extra_bed * $tour_information[0]->child_extra_bed;
            $kirim_payment = array(
              "id_product_tour_book"        => $id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $ceb_nominal,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 0,
              "id_currency"                 => $currency,
              "note"                        => "Child Extra Bed ".$dtjml_child_extra_bed." x ".number_format($tour_information[0]->child_extra_bed),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment);
        
          }
          
          if($dtjml_child_no_bed){
            $cnb_nominal  = $dtjml_child_no_bed * $tour_information[0]->child_no_bed;
            $kirim_payment_cnb = array(
              "id_product_tour_book"        => $id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $cnb_nominal,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 0,
              "id_currency"                 => $currency,
              "note"                        => "child_no_bed ".$dtjml_child_no_bed." x ".number_format($tour_information[0]->child_no_bed),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment_cnb);
        
          }
          
          if($dtjml_sgl_supp){
            $single_adult = ($tour_information[0]->sgl_supp + $tour_information[0]->adult_triple_twin);
            $sgl_supp_nominal  = $dtjml_sgl_supp * $single_adult;
            $kirim_payment_sgl_adul = array(
              "id_product_tour_book"        => $id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $sgl_supp_nominal,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 0,
              "id_currency"                 => $currency,
              "note"                        => "Single Adult ".$dtjml_sgl_supp." x ".number_format($single_adult),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment_sgl_adul);
        
          }
          
          $total_keseluruhan_customer_airport_tax = ($dtjml_adult_triple_twin + $dtjml_child_twin_bed + $dtjml_child_extra_bed + $dtjml_child_no_bed + $dtjml_sgl_supp);
          
          if($total_keseluruhan_customer_airport_tax){
              $airport_tax2  = $total_keseluruhan_customer_airport_tax * $tour_information[0]->airport_tax;
            $kirim_payment_airport_tax = array(
              "id_product_tour_book"        => $id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $airport_tax2,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 0,
              "id_currency"                 => $currency,
              "note"                        => "Airport Tax & Flight Insurance ".$total_keseluruhan_customer_airport_tax." x ".number_format($tour_information[0]->airport_tax),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment_airport_tax);
        
          }
          $beban_cust = $adl_nominal + $ctb_nominal + $ceb_nominal + $cnb_nominal + $sgl_supp_nominal + $airport_tax2;
          $ppn = (1 * ($beban_cust)/100);
             
            $kirim_payment_ppn = array(
              "id_product_tour_book"        => $id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $ppn,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 7,
              "id_currency"                 => $currency,
              "note"                        => "PPN 1% x ".number_format($beban_cust),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment_ppn);
        
         
//        $kirim_payment = array(
//          "id_product_tour_book"        => $id_product_tour_book,
//          "id_users"                    => $pst['id_users'],
//          "nominal"                     => $total,
//          "tanggal"                     => date("Y-m-d H:i:s"),
//          "pos"                         => 1,
//          "status"                      => 0,
//          "id_currency"                 => $currency,  
//          "create_by_users"             => $users[0]->id_users,
//          "create_date"                 => date("Y-m-d H:i:s")
//        );
//        $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_payment);
        
        $kirim = array(
          'status'    => 2,
          'book_code' => $kode,
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code Book Salah'
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
  // proses cancel
  function cancel_customer_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      $data_customer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $tour_book[0]->id_product_tour_book, "kode" => $pst['customer_code']));
      if($data_customer[0]->id_product_tour_customer > 0){
        
       $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $data_customer[0]->id_product_tour_customer), array("status" => 5));
        $kirim = array(
          'status'    => 2,
          'note' => "Data Diajukan",
         
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code Customer Salah'
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
  
  function update_customer_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      $this->load->model("json/mjson_tour");
      if($tour_book[0]->id_product_tour_book > 0){
        $adult_triple_twin  = json_decode(str_replace('\"', '"', $pst['adult_triple_twin']));
        $child_tb  = json_decode(str_replace('\"', '"', $pst['child_tb']));
        $child_eb = json_decode(str_replace('\"', '"', $pst['child_eb']));
        $child_nb = json_decode(str_replace('\"', '"', $pst['child_nb']));
        $single_supp = json_decode(str_replace('\"', '"', $pst['single_supp']));
        
//        $kirim = array(
//           "first_name"                   => $pst['first_name'],
//            "last_name"                   => $pst['last_name'],
//            "telphone"                    => $pst['telp'],
//            "email"                       => $pst['email'],
//            "address"                     => $pst['address'],
//            "update_by_users"             => $pst['id_users'],
//        );
//         $this->global_models->update("product_tour_book", array("id_product_tour_book" => $tour_book[0]->id_product_tour_book),$kirim);
        
        $generate_biaya = FALSE;
        
        $adl0 = $chl1 = $chl2 = $chl3 = $adl1 = $troom = 0;
        
         foreach($adult_triple_twin AS $adl){
            if(isset($adl)){
              $kirim_product_tour_customer_adl = array(
                "first_name"                    => $adl->first_name,
                "last_name"                     => $adl->last_name,
                "tempat_tanggal_lahir"          => $adl->tempat_lahir,
                "tanggal_lahir"                 => $adl->lahir,
                "passport"                      => $adl->passport,
                "telphone"                      => $adl->telp,
                "place_of_issued"               => $adl->place_issued,
                "date_of_issued"                => $adl->date_issued,
                "date_of_expired"               => $adl->date_expired, 
                "visa"                          => $adl->visa,
                "room"                          => $adl->room,
                "type"                          => $adl->type,
                "update_by_users"               => $pst['id_users'],
              );
              $product_tour_customer = $this->global_models->get("product_tour_customer", array("kode" => $adl->customer_code));
              
              if($product_tour_customer[0]->visa != $adl->visa AND $adl->visa){
                $this->mjson_tour->set_additional_visa($tour_book[0]->id_product_tour_book, $tour_book[0]->id_product_tour_information, $adl->first_name." ".$adl->last_name, 1);
              }
              
              if($product_tour_customer[0]->type != $adl->type)
                $generate_biaya = TRUE;
              $this->global_models->update("product_tour_customer", array("kode" => $adl->customer_code),$kirim_product_tour_customer_adl);
              
            }
        }
        
          foreach($child_tb AS $chd_tb){
            if(isset($chd_tb)){
              
              $kirim_product_tour_customer_chd_tb = array(
                "first_name"                    => $chd_tb->first_name,
                "last_name"                     => $chd_tb->last_name,
                "tempat_tanggal_lahir"          => $chd_tb->tempat_lahir,    
                "tanggal_lahir"                 => $chd_tb->lahir,
                "passport"                      => $chd_tb->passport,
                "telphone"                      => $chd_tb->telp,
                "place_of_issued"               => $chd_tb->place_issued,
                "date_of_issued"                => $chd_tb->date_issued,
                "date_of_expired"               => $chd_tb->date_expired,   
                "visa"                          => $chd_tb->visa,
                "room"                          => $chd_tb->room,
                "type"                          => $chd_tb->type,
                "update_by_users"               => $pst['id_users'],
              );
              $product_tour_customer = $this->global_models->get("product_tour_customer", array("kode" => $chd_tb->customer_code));
              
              if($product_tour_customer[0]->visa != $chd_tb->visa AND $chd_tb->visa){
                $this->mjson_tour->set_additional_visa($tour_book[0]->id_product_tour_book, $tour_book[0]->id_product_tour_information, $chd_tb->first_name." ".$chd_tb->last_name, 2);
              }
              
              if($product_tour_customer[0]->type != $chd_tb->type)
                $generate_biaya = TRUE;
              $this->global_models->update("product_tour_customer", array("kode" => $chd_tb->customer_code),$kirim_product_tour_customer_chd_tb);
              
            }
        }
        
          foreach($child_eb AS $chd_eb){
            if(isset($chd_eb)){
             
              $kirim_product_tour_customer_chd_eb = array(
                "first_name"                    => $chd_eb->first_name,
                "last_name"                     => $chd_eb->last_name,
                "tempat_tanggal_lahir"          => $chd_eb->tempat_lahir,    
                "tanggal_lahir"                 => $chd_eb->lahir,
                "passport"                      => $chd_eb->passport,
                "telphone"                      => $chd_eb->telp,
                "place_of_issued"               => $chd_eb->place_issued,
                "date_of_issued"                => $chd_eb->date_issued,
                "date_of_expired"               => $chd_eb->date_expired, 
                "visa"                          => $chd_eb->visa,
                "room"                          => $chd_eb->room,
                "type"                          => $chd_eb->type,
                "update_by_users"               => $pst['id_users'],
              );
              $product_tour_customer = $this->global_models->get("product_tour_customer", array("kode" => $chd_eb->customer_code));
              
              if($product_tour_customer[0]->visa != $chd_eb->visa AND $chd_eb->visa){
                $this->mjson_tour->set_additional_visa($tour_book[0]->id_product_tour_book, $tour_book[0]->id_product_tour_information, $chd_eb->first_name." ".$chd_eb->last_name, 3);
              }
              
              if($product_tour_customer[0]->type != $chd_eb->type)
                $generate_biaya = TRUE;
              $this->global_models->update("product_tour_customer", array("kode" => $chd_eb->customer_code),$kirim_product_tour_customer_chd_eb);
              
            }
        }
        
          foreach($child_nb AS $chd_nb){
            if(isset($chd_nb)){
             
              $kirim_product_tour_customer_chd_nb = array(
                "first_name"                    => $chd_nb->first_name,
                "last_name"                     => $chd_nb->last_name,
                "tempat_tanggal_lahir"          => $chd_nb->tempat_lahir,    
                "tanggal_lahir"                 => $chd_nb->lahir,
                "passport"                      => $chd_nb->passport,
                "telphone"                      => $chd_nb->telp,
                "place_of_issued"               => $chd_nb->place_issued,
                "date_of_issued"                => $chd_nb->date_issued,
                "date_of_expired"               => $chd_nb->date_expired,
                "visa"                          => $chd_nb->visa,
                "room"                          => $chd_nb->room,
                "type"                          => $chd_nb->type,
                "update_by_users"               => $pst['id_users'],
              );
              $product_tour_customer = $this->global_models->get("product_tour_customer", array("kode" => $chd_nb->customer_code));
              
              if($product_tour_customer[0]->visa != $chd_nb->visa AND $chd_nb->visa){
                $this->mjson_tour->set_additional_visa($tour_book[0]->id_product_tour_book, $tour_book[0]->id_product_tour_information, $chd_nb->first_name." ".$chd_nb->last_name, 4);
              }
              
              if($product_tour_customer[0]->type != $chd_nb->type)
                $generate_biaya = TRUE;
              $this->global_models->update("product_tour_customer", array("kode" => $chd_nb->customer_code),$kirim_product_tour_customer_chd_nb);
              
            }
        }
        
          foreach($single_supp AS $sgl){
            if(isset($sgl)){
             
              $kirim_product_tour_customer_sgl = array(
                "first_name"                    => $sgl->first_name,
                "last_name"                     => $sgl->last_name,
                "tempat_tanggal_lahir"          => $sgl->tempat_lahir,    
                "tanggal_lahir"                 => $sgl->lahir,
                "passport"                      => $sgl->passport,
                "telphone"                      => $sgl->telp,
                "place_of_issued"               => $sgl->place_issued,
                "date_of_issued"                => $sgl->date_issued,
                "date_of_expired"               => $sgl->date_expired,  
                 "visa"                         => $sgl->visa,
                "room"                          => $sgl->room,
                "type"                          => $sgl->type,
                "update_by_users"               => $pst['id_users'],
              );
              $product_tour_customer = $this->global_models->get("product_tour_customer", array("kode" => $sgl->customer_code));
              
              if($product_tour_customer[0]->visa != $sgl->visa AND $sgl->visa){
                $this->mjson_tour->set_additional_visa($tour_book[0]->id_product_tour_book, $tour_book[0]->id_product_tour_information, $sgl->first_name." ".$sgl->last_name, 5);
              }
              if($product_tour_customer[0]->type != $sgl->type)
                $generate_biaya = TRUE;
              $this->global_models->update("product_tour_customer", array("kode" => $sgl->customer_code),$kirim_product_tour_customer_sgl);
              
            }
        }
        
      $customer = $this->global_models->get_query("SELECT SUM(CASE WHEN type = 1 THEN 1 ELSE 0 END) AS att"
        . " ,SUM(CASE WHEN type = 2 THEN 1 ELSE 0 END) AS ctb"
        . " ,SUM(CASE WHEN type = 3 THEN 1 ELSE 0 END) AS ceb"
        . " ,SUM(CASE WHEN type = 4 THEN 1 ELSE 0 END) AS cnb"
        . " ,SUM(CASE WHEN type = 5 THEN 1 ELSE 0 END) AS sgl"
        . " ,MAX(room) AS rm"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$tour_book[0]->id_product_tour_book}'"
        . " AND (status = 1 OR status = 2 OR status = 3)");
        
       $this->global_models->update("product_tour_book", array("id_product_tour_book" => $tour_book[0]->id_product_tour_book), 
         array("adult_triple_twin" => $customer[0]->att, "child_twin_bed" => $customer[0]->ctb, "child_extra_bed" => $customer[0]->ceb, "child_no_bed" => $customer[0]->cnb, "sgl_supp" => $customer[0]->sgl, "room" => $customer[0]->rm));
        if($generate_biaya == TRUE){
          $this->mjson_tour->revert_all_payment($tour_book[0]->id_product_tour_book, $pst['id_users']);
          $this->mjson_tour->recount_payment($tour_book[0]->id_product_tour_book, $pst['id_users']);
          $kirim = array(
            'status'    => 4,
            'note' => "Terdapat Perubahan Harga", 
          );
        }
        else{
          $kirim = array(
            'status'    => 2,
            'note' => "Data Customer Update", 
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code Customer Salah'
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
  
  function request_additional_tour(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['book_code']));
      if($tour_book[0]->id_product_tour_book > 0){
         $additional = $this->global_models->get("product_tour_additional", array("kode" => $pst['kode_additional']));
         
        if($pst['status'] == 1){
         
        $kirim = array(
           
            "status"                              => 2,
            "id_user_approval"                    => $pst['id_users'],
            "update_by_users"                     => $pst['id_users'],
        );
       
       $this->global_models->update("product_tour_additional", array("id_product_tour_additional" => $additional[0]->id_product_tour_additional),$kirim);
       $hasil = array(1 => "Disetujui", 2 => "Ditolak");
       $kirim_log_add = array(
          "id_product_tour_book"      => $tour_book[0]->id_product_tour_book,
          "id_users"                  => $pst['id_users'],
          "name"                      => $pst['name'],
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => $additional[0]->name." - ".$hasil[$pst['status']],
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $id_product_tour_log_request_additional = $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
         $kirim_payment = array(
              "id_product_tour_book"        => $tour_book[0]->id_product_tour_book,
              "id_users"                    => $pst['id_users'],
              "nominal"                     => $additional[0]->nominal,
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => $additional[0]->pos,
              "pajak"                       => $additional[0]->pajak,
              "status"                      => 5,
              "id_currency"                 => 2,
              "note"                        => "[Additional] ".$additional[0]->name,
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("product_tour_book_payment", $kirim_payment);
            
        $this->load->model("json/mjson_tour");
        $this->mjson_tour->recalculation_ppn($tour_book[0]->id_product_tour_book);
        $this->mjson_tour->cek_status_book($tour_book[0]->id_product_tour_book, $pst['id_users']);
        
        $kirim = array(
          'status'    => 2,
          'note' => "Data berhasil disimpan",
         
        );
        
        }elseif($pst['status'] == 2){
             $kirim = array(
           
            "status"                              => 3,
            "id_user_approval"                    => $pst['id_users'],
            "update_by_users"                     => $pst['id_users'],
        );
       
       $this->global_models->update("product_tour_additional", array("id_product_tour_additional" => $additional[0]->id_product_tour_additional),$kirim);
         $kirim = array(
          'status'    => 2,
          'note' => "Data berhasil disimpan",
         
        );
         
        }else{
             $kirim = array(
          'status'  => 4,
          'note'    => 'Gagal'
        );
        }
        
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code Book Salah'
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
  
  function request_discount_tambahan(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
       
      $tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['kode']));
      if($tour_book[0]->id_product_tour_book > 0){
       $this->load->model("json/mjson_tour"); 
        
        if($pst['confirm'] == "data_submit"){
          $kirim = array(
           
           "discount_request"                     => $pst['nominal_request_discount'],
            "status"                              => 2,
            "id_user_approval"                    => $pst['user_approval'],
            "update_by_users"                     => $pst['user_approval'],
        );
       
       $this->global_models->update("product_tour_discount_tambahan", array("id_product_tour_book" => $tour_book[0]->id_product_tour_book),$kirim);
        
         $kirim_payment = array(
              "id_product_tour_book"        => $tour_book[0]->id_product_tour_book,
              "id_users"                    => $pst['user_approval'],
              "nominal"                     => $pst['nominal_request_discount'],
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 2,
              "status"                      => 8,
              "id_currency"                 => 2,
              "note"                        => "Approve Discount Tambahan Rp ".number_format($pst['nominal_request_discount']),
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s")
            );
            $this->global_models->insert("product_tour_book_payment", $kirim_payment);
         $this->mjson_tour->cek_status_book($tour_book[0]->id_product_tour_book, $pst['user_approval']);
            $kirim = array(
          'status'    => 2,
          'note' => "berhasil disimpan",
         
            );
        }elseif($pst['confirm'] == "data_reject"){
            
          $kirim = array(
            "status"                              => 3,
            "id_user_approval"                    => $pst['user_approval'],
            "update_by_users"                     => $pst['user_approval'],
          );
       
       $this->global_models->update("product_tour_discount_tambahan", array("id_product_tour_book" => $tour_book[0]->id_product_tour_book),$kirim);
        
          $kirim = array(
          'status'    => 2,
          'note' => "berhasil direject",
         
            );
        }
        
       
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code Book Salah'
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
  
   function get_fee_product_tour(){
      
    $pst = $_REQUEST;
   
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->get_connect("default");
      
     
      $this->global_models->get_connect("default");
      if($pst['search_data']){
          $WHERE = " AND A.id_product_tour_book=".$pst['id_product_tour_book'];
          
          
          $kirim = $this->global_models->get_query("
        SELECT 	A.id_product_tour_book,
		A.first_name,
		A.last_name,
		A.telphone,
		A.email,
		A.adult,
		A.child,
		A.infant,
		B.price_adult,
		B.price_child,
		B.price_infant,
		B.dp
        FROM product_tour_book AS A
        LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information
        WHERE 1=1 {$WHERE}
        ");
        $kirim = array('status' => 2,
       'detail_product_tour_book' => $kirim);
      }  elseif ($pst['save_data_payment']) {
          
          if($pst['nominal'] >= $pst['dp']){
              $pos = 1;
              $status = 2;
          }  else{
              $status = 1;
              $pos = 1;
          }
          
          if ($pst['nominal'] == $pst['total_all']) {
              $status = 3;
          }
         
           $update_data = array(
                "status"                            => $status,
                );
           
          $this->global_models->update("product_tour_book", array("id_product_tour_book" => $pst['id_product_tour_book']),$update_data);
     
          
          $kirim_info = array(
                "id_product_tour_book"              => $pst['id_product_tour_book'],
                "id_users"                          => $pst['id_users'],
                "nominal"                             => $pst['nominal'],
                "tanggal"                           => date("Y-m-d H:i:s"),
                "pos"                             => $pos,
                "status"                             => $status,
                "create_by_users"                   => $pst['id_users'],
                "create_date"                       => date("Y-m-d H:i:s")
                );
                 
               $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim_info);
               $kirim = array('status' => 2,
               'id_product_tour_book_payment' => $id_product_tour_book_payment);
      }
       // print_r($items);
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
  function get_tour_book_list(){
    $pst = $_REQUEST;
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
    
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $where = " And A.status IN(1,2,3,6,7) ";
      $lIMIT = "";
       if($pst['code']){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($pst['code'])."%' OR LOWER(A.kode) LIKE '%".strtolower($pst['code'])."%'"; 
      }
      
       if($pst['title']){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($pst['title'])."%'"; 
      }
      
      if($pst['start_date'] || $pst['$end_date']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start_date']} 00:00:00' AND '{$pst['end_date']} 23:59:59')";
      }
      
      if($pst['name']){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".$pst['name']."%'"; 
      }
      
      if($pst['status']){
        if($pst['status'] != 100)
          $where .= " AND A.status = '{$pst['status']}'"; 
        else
          $where .= " AND (A.status = '2' OR A.status = '3')"; 
      }
      
      
      $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
      if(!$id_store){
        $filter_users = "AND A.id_users IN ({$pst['id_users']})";
      }
      else{
        $filter_users = "AND (A.id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}') OR A.id_users IN (SELECT id_users FROM store_commited WHERE id_store = '{$id_store}'))";
      }
      
       if($pst['start'] > 0){
          $start_limit = $pst['start'];
        }else{
          $start_limit = 0;
        }
        
      if($pst['limit'] OR $pst['start']){
         $lIMIT .= " LIMIT {$start_limit}, {$pst['limit']}";
      }
      
       $data_total = $this->global_models->get_query("SELECT COUNT(A.id_product_tour_book) AS total"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " WHERE 1=1"
        . " {$filter_users}"
        . " $where"
        . " ORDER BY tanggal DESC");
      
      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,B.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " WHERE 1=1"
        . " {$filter_users}"
        . " $where"
        . " ORDER BY tanggal DESC"
         . $lIMIT );
      $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
         
      if($book){
        $additional_tour = "";
        foreach($book AS $ky => $bk){
//        $price_tax_inusrance =  ($bk->adult_triple_twin + $bk->child_twin_bed + $bk->child_extra_bed + $bk->child_no_bed + $bk->sgl_supp) * $bk->price_tax_insurance;
//        
//       if($bk->id_currency == 1){
//           $price_tax_inusrance = $price_tax_inusrance * $dropdown_rate[1];
//           
//       }elseif($bk->id_currency == 2){
//            $price_tax_inusrance = $price_tax_inusrance;
//       }
      
        /*$balance = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
            . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
            . " FROM product_tour_book_payment"
            . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); */
        
        
        
          
        //  $dp = $this->global_models->get_field("product_tour_information", "dp", array("id_product_tour_information" => $bk->id_product_tour_information));
//          $nominal_pertama = $this->global_models->get_field("product_tour_book_payment", "nominal", array("pos" => 1, "status" => 0, "id_product_tour_book" => $bk->id_product_tour_book));
//       $nominal_currency = $this->global_models->get_field("product_tour_book_payment", "id_currency", array("pos" => 1, "status" => 0, "id_product_tour_book" => $bk->id_product_tour_book));
//           if($nominal_currency == 1){
//              $nominal_pertama = $nominal_pertama * $dropdown_rate[1];
//          }elseif($nominal_currency == 2){
//              $nominal_pertama = $nominal_pertama;
//          }
       $balance[$ky] = $this->global_models->get_query("SELECT nominal,status, id_currency,pos"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); 
              
        foreach ($balance[$ky] as $val_balance) {
           if($val_balance->id_currency == 1){
//               $nom1_usd = $val_balance->nominal;
//               $nom1_idr = $val_balance->nominal * $dropdown_rate[1];
             }elseif($val_balance->id_currency == 2){
//                   $nom1_usd = $val_balance->nominal/$dropdown_rate[1];
                   $nom1_idr = $val_balance->nominal;
             }
                if($val_balance->pos == 1){
//                  $balance_debit_usd[$ky] += $nom1_usd;
                  $balance_debit_idr[$ky] += $nom1_idr;
                }
                elseif($val_balance->pos == 2 AND $val_balance->status != 2){
//                  $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_potongan_tambahan_idr[$ky] += $nom1_idr;
                }elseif($val_balance->pos == 2 AND $val_balance->status == 2){
//                    $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_pembayaran_idr[$ky] += $nom1_idr;
                }
        }
        
//        $additional = $this->global_models->get_query("SELECT name,nominal,id_currency,pos "
//        . " FROM product_tour_additional"
//        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
        // $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $bk->id_product_tour_book));
         $passenger = $this->global_models->get_query("SELECT status,visa "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
        
           $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status < '3' ");
        
        if($bk->id_currency == 1){
          $total_visa = ($bk->visa * $total_visa[0]->totl_visa) * $dropdown_rate[1];
        }elseif($bk->id_currency == 2){
          $total_visa = $bk->visa * $total_visa[0]->totl_visa;
        }
        
        
         /*foreach($passenger AS $kkys => $psng){
          if($psng->status == 1){
            $status_cust = "Book";
          }elseif($psng->status == 2){
            $status_cust = "Committed Book";
          }elseif($psng->status == 3){
            $status_cust = "Lunas";
          }elseif($psng->status == 4){
            $status_cust = "Cancel";
          }elseif($psng->status == 5){
            $status_cust = "[Cancel] Waiting Approval";
          }
          $passenger_tour[$kkys] = array(
            "first_name"            => $psng->first_name,
            "last_name"             => $psng->last_name,
            "tanggal_lahir"         => $psng->tanggal_lahir,
            "type"                  => array("code" => $psng->type, "desc" => $note_type[$psng->type]),
            "room"                  => $psng->room,
            "no_passport"           => $psng->passport,
            "status"                => $status_cust,
            "customer_code"         => $psng->kode,
          );
        } */
        
//        $discount_tambahan = $this->global_models->get_query("SELECT discount_request,status_discount"
//          . " FROM product_tour_discount_tambahan"
//          . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status= 1"); 
//          $ts = $this->db->last_query();
//          if($bk->stnb_discount == 1){
//            $status_disc = "Persen";
//          }elseif($bk->stnb_discount == 2){
//            $status_disc = "Nominal";
//          }
          
//          $tour_payment = $this->global_models->get_query("SELECT *"
//          . " FROM product_tour_book_payment"
//          . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"
//          . " ORDER BY pos ASC");
//        foreach($tour_payment AS $tp){
//          $payment[] = array(
//            "agent"                   => $tp->id_users,
//            "nominal"                 => $tp->nominal,
//            "tanggal"                 => $tp->tanggal,
//            "pos"                     => $tp->pos,
//            "status"                  => $tp->status,
//            "status_payment"          => $tp->payment,
//            "no_deposit"              => $tp->no_deposit,
//            "currency"                => $tp->id_currency,
//            "note"                    => $tp->note
//          );
//        }
        
         $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $bk->id_users));
      $name_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store));
      $name_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $bk->id_users));
     
          $book_detail[] = array(
            "tour"            => $bk->title,
            "tour_code"       => $bk->code_tour,
            "start_date"      => $bk->start_date,
            "end_date"        => $bk->end_date,
            "info_code"       => $bk->code_info,
            "code"            => $bk->kode,
            "first_name"      => $bk->first_name,
            "last_name"       => $bk->last_name,
            "telp"            => $bk->telphone,
            "email"           => $bk->email,
            "tanggal"         => $bk->tanggal,
            "status"          => $bk->status,
            "total_visa"      => $total_visa,
            "store"           => $name_store,
              "tc"            => $name_tc,
         //   "discount"        => $bk->discount,
          //  "status_discount"        => $status_disc,
          //  "discount_tambahan"        => $discount_tambahan,
          //  "tax_and_insurance" => $price_tax_inusrance,
           // "beban_awal"      => $nominal_pertama,
            "beban"           => $balance_debit_idr[$ky],
            "potongan"        => $balance_kredit_potongan_tambahan_idr[$ky],  
            "pembayaran"      => $balance_kredit_pembayaran_idr[$ky],
          //  "additional"      => $additional,
            'currency_rate' => $dropdown_rate[1],
            "passenger" => $passenger,
          );
        }
       
        $kirim = array(
          'status'  => 2,
          'book'    => $book_detail,
           'total'       =>$data_total[0]->total
          
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
  
  function get_tour_book_list_keseluruhan(){
    $pst = $_REQUEST;
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
    
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $where = "";
      $lIMIT = "";
       if($pst['code']){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($pst['code'])."%' OR LOWER(A.kode) LIKE '%".strtolower($pst['code'])."%'"; 
      }
      
       if($pst['title']){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($pst['title'])."%'"; 
      }
      
      if($pst['start_date'] || $pst['$end_date']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start_date']} 00:00:00' AND '{$pst['end_date']} 23:59:59')";
      }
      
      if($pst['name']){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".$pst['name']."%'"; 
      }
      
      if($pst['status']){
        if($pst['status'] == 100)
          $where .= " AND (A.status = '2' OR A.status = '3')"; 
        else
          $where .= " AND A.status = '".$pst['status']."'"; 
      }
      
      
//      $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
      
      if(!$id_store){
        $filter_users = "AND A.id_users IN ({$pst['id_users']})";
      }
      else{
        $filter_users = "AND A.id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}')";
      }
      
       if($pst['start'] > 0){
          $start_limit = $pst['start'];
        }else{
          $start_limit = 0;
        }
        
      if($pst['limit'] OR $pst['start']){
         $lIMIT .= " LIMIT {$start_limit}, {$pst['limit']}";
      }
      
       $data_total = $this->global_models->get_query("SELECT COUNT(A.id_product_tour_book) AS total"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " WHERE 1=1"
     //   . " {$filter_users}"
        . " $where"
        . " ORDER BY tanggal DESC");
      
      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,B.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " WHERE 1=1"
       // . " {$filter_users}"
        . " $where"
        . " ORDER BY tanggal DESC"
         . $lIMIT );
      $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
         
      if($book){
        $additional_tour = "";
        foreach($book AS $ky => $bk){
//        $price_tax_inusrance =  ($bk->adult_triple_twin + $bk->child_twin_bed + $bk->child_extra_bed + $bk->child_no_bed + $bk->sgl_supp) * $bk->price_tax_insurance;
//        
//       if($bk->id_currency == 1){
//           $price_tax_inusrance = $price_tax_inusrance * $dropdown_rate[1];
//           
//       }elseif($bk->id_currency == 2){
//            $price_tax_inusrance = $price_tax_inusrance;
//       }
       
       $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $bk->id_users));
      $name_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store));
      $name_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $bk->id_users));
     
      
        /*$balance = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE 0 END) AS debit"
            . " ,SUM(CASE WHEN pos = 2 THEN nominal ELSE 0 END) AS kredit"
            . " FROM product_tour_book_payment"
            . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); */
        
        
        
          
        //  $dp = $this->global_models->get_field("product_tour_information", "dp", array("id_product_tour_information" => $bk->id_product_tour_information));
          $nominal_pertama = $this->global_models->get_field("product_tour_book_payment", "nominal", array("pos" => 1, "status" => 0, "id_product_tour_book" => $bk->id_product_tour_book));
       $nominal_currency = $this->global_models->get_field("product_tour_book_payment", "id_currency", array("pos" => 1, "status" => 0, "id_product_tour_book" => $bk->id_product_tour_book));
           if($nominal_currency == 1){
              $nominal_pertama = $nominal_pertama * $dropdown_rate[1];
          }elseif($nominal_currency == 2){
              $nominal_pertama = $nominal_pertama;
          }
       $balance[$ky] = $this->global_models->get_query("SELECT nominal,status, id_currency,pos"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); 
              
        foreach ($balance[$ky] as $val_balance) {
           if($val_balance->id_currency == 1){
//               $nom1_usd = $val_balance->nominal;
//               $nom1_idr = $val_balance->nominal * $dropdown_rate[1];
             }elseif($val_balance->id_currency == 2){
//                   $nom1_usd = $val_balance->nominal/$dropdown_rate[1];
                   $nom1_idr = $val_balance->nominal;
             }
                if($val_balance->pos == 1){
//                  $balance_debit_usd[$ky] += $nom1_usd;
                  $balance_debit_idr[$ky] += $nom1_idr;
                }
                elseif($val_balance->pos == 2 AND $val_balance->status != 2){
//                  $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_potongan_tambahan_idr[$ky] += $nom1_idr;
                }elseif($val_balance->pos == 2 AND $val_balance->status == 2){
//                    $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_pembayaran_idr[$ky] += $nom1_idr;
                }
        }
        
//        $additional = $this->global_models->get_query("SELECT name,nominal,id_currency,pos "
//        . " FROM product_tour_additional"
//        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
        // $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $bk->id_product_tour_book));
         $passenger = $this->global_models->get_query("SELECT status,visa "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
        
           $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status < '3' ");
        
        if($bk->id_currency == 1){
          $total_visa = ($bk->visa * $total_visa[0]->totl_visa) * $dropdown_rate[1];
        }elseif($bk->id_currency == 2){
          $total_visa = $bk->visa * $total_visa[0]->totl_visa;
        }
        
        
         /*foreach($passenger AS $kkys => $psng){
          if($psng->status == 1){
            $status_cust = "Book";
          }elseif($psng->status == 2){
            $status_cust = "Committed Book";
          }elseif($psng->status == 3){
            $status_cust = "Lunas";
          }elseif($psng->status == 4){
            $status_cust = "Cancel";
          }elseif($psng->status == 5){
            $status_cust = "[Cancel] Waiting Approval";
          }
          $passenger_tour[$kkys] = array(
            "first_name"            => $psng->first_name,
            "last_name"             => $psng->last_name,
            "tanggal_lahir"         => $psng->tanggal_lahir,
            "type"                  => array("code" => $psng->type, "desc" => $note_type[$psng->type]),
            "room"                  => $psng->room,
            "no_passport"           => $psng->passport,
            "status"                => $status_cust,
            "customer_code"         => $psng->kode,
          );
        } */
        
//        $discount_tambahan = $this->global_models->get_query("SELECT discount_request,status_discount"
//          . " FROM product_tour_discount_tambahan"
//          . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status= 1"); 
////          $ts = $this->db->last_query();
//          if($bk->stnb_discount == 1){
//            $status_disc = "Persen";
//          }elseif($bk->stnb_discount == 2){
//            $status_disc = "Nominal";
//          }
          
        
          $book_detail[] = array(
            "tour"            => $bk->title,
            "tour_code"       => $bk->code_tour,
            "start_date"      => $bk->start_date,
            "end_date"        => $bk->end_date,
            "info_code"       => $bk->code_info,
            "code"            => $bk->kode,
            "first_name"      => $bk->first_name,
            "last_name"       => $bk->last_name,
            "telp"            => $bk->telphone,
            "email"           => $bk->email,
            "tanggal"         => $bk->tanggal,
            "status"          => $bk->status,
            "total_visa"      => $total_visa,
            "discount"        => $bk->discount,
            "name_store"      => $name_store,
            "name_tc"         => $name_tc,
         //   "status_discount"        => $status_disc,
         //   "discount_tambahan"        => $discount_tambahan,
         //   "tax_and_insurance" => $price_tax_inusrance,
          //  "beban_awal"      => $nominal_pertama,
            "beban"           => $balance_debit_idr[$ky],
            "potongan"      => $balance_kredit_potongan_tambahan_idr[$ky],
            "pembayaran"      => $balance_kredit_pembayaran_idr[$ky],
        //    "additional"      => $additional,
            'currency_rate' => $dropdown_rate[1],
            "passenger" => $passenger,
          );
        }
       
        $kirim = array(
          'status'  => 2,
          'book'    => $book_detail,
           'total'       =>$data_total[0]->total
          
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
  
  function get_tour_book_list_store(){
    $pst = $_REQUEST;
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
    
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
        $where .= " AND A.code LIKE '%{$pst['code']}%'";
      }
      if($pst['title']){
        $where .= " AND (A.first_name LIKE '%{$pst['title']}%' OR A.last_name LIKE '%{$pst['title']}%' OR A.telphone LIKE '%{$pst['title']}%' OR A.email LIKE '%{$pst['title']}%')";
      }
      if($pst['status']){
        $where .= " AND A.status = '{$pst['status']}'";
      }
      $sql = "SELECT A.*"
        . " ,SUM(CASE WHEN B.pos = 2 THEN B.nominal ELSE (B.nominal * -1) END) AS saldo"
        . " ,C.name AS bookers"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_book_payment AS B ON (A.id_product_tour_book = B.id_product_tour_book AND B.status <> 3)"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " WHERE (A.id_users IN (SELECT id_users FROM store_commited WHERE id_store = '{$id_store}')"
        . " OR A.id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}'))"
        . " {$where}"
        . " GROUP BY B.id_product_tour_book";
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
  function get_all_store(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
      if(!$id_store){
        $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
        $where = " AND id_store IN (SELECT id_store FROM store_commited WHERE id_users = '{$pst['id_users']}')";
      }
      else{
        $where = " AND id_store = '{$id_store}'";
      }
      
      if(!$id_store){
        if($pst['master']){
          $data = $this->global_models->get_query("SELECT *"
            . " FROM store"
            . " WHERE 1 = 1"
            . " AND master = '{$pst['master']}'"
            . " ORDER BY sort ASC");
//          $data = $this->global_models->get("store", array("master" => $pst['master']));
        }
        else{
          $data = $this->global_models->get_query("SELECT *"
            . " FROM store"
            . " ORDER BY sort ASC");
        }
      }
      else{
        $data = $this->global_models->get_query("SELECT *"
          . " FROM store"
          . " WHERE 1 = 1"
          . " {$where}"
          . " ORDER BY sort ASC");
      }
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
  function get_users_store(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data_tc = $this->global_models->get_query("SELECT C.*"
        . " FROM store_tc AS A"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " WHERE A.id_store = '{$pst['id_store']}'"
        . " GROUP BY A.id_users");
      foreach ($data_tc AS $dt){
        $result[] = array(
          'posisi'  => 1,
          'isi'     => $dt);
      }
      $data = $this->global_models->get_query("SELECT C.*"
        . " FROM store_commited AS A"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " WHERE A.id_store = '{$pst['id_store']}'"
        . " GROUP BY A.id_users");
      foreach ($data AS $d){
        $result[] = array(
          'posisi'  => 2,
          'isi'     => $d);
      }
      if($data){
        $kirim = array(
          'status'  => 2,
          'data'    => $result,
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
  function set_move_bookers(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      if($product_tour_book[0]->id_product_tour_book){
        $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("id_users" => $pst['id_users'], "note" => "move TC {$product_tour_book[0]->id_users} -> {$pst['id_users']}"));
        $kirim = array(
          'status'  => 2,
          'note'    => ""
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data Tidak Ada',
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
  
  function get_customer_cancel_list(){
    $pst = $_REQUEST;
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
    
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      if($pst['list_cancel'] == "2"){
        $where = "AND (A.status = '4' OR A.status = '5' OR A.status = '6')";
        
        }else if($pst['list_cancel'] == "1"){
        $where = "AND (A.status < 4 AND (D.status = '4' OR D.status = '5' OR D.status = '6'))";
      }
      
      $lIMIT = "";
       if($pst['code']){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($pst['code'])."%' OR LOWER(A.kode) LIKE '%".strtolower($pst['code'])."%'"; 
      }
      
       if($pst['title']){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($pst['title'])."%'"; 
      }
      
      if($pst['start_date'] || $pst['$end_date']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start_date']} 00:00:00' AND '{$pst['end_date']} 23:59:59')";
      }
      
      if($pst['name']){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".$pst['name']."%'"; 
      }
      
//      if($pst['status']){
//        if($pst['status'] != 100)
//          $where .= " AND A.status = '{$pst['status']}'"; 
//        else
//          $where .= " AND (A.status = '4' OR A.status = '5')"; 
//      }
      
      
      $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
      if(!$id_store){
        $filter_users = "AND A.id_users IN ({$pst['id_users']})";
      }
      else{
         $filter_users = "AND (A.id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}') OR A.id_users IN (SELECT id_users FROM store_commited WHERE id_store = '{$id_store}'))";
        
      }

	if($pst['id_users'] == 1){
        $filter_users = "";
      }

      
       if($pst['start'] > 0){
          $start_limit = $pst['start'];
        }else{
          $start_limit = 0;
        }
        
      if($pst['limit'] OR $pst['start']){
         $lIMIT .= " LIMIT {$start_limit}, {$pst['limit']}";
      }
      
       $data_total = $this->global_models->get_query("SELECT COUNT(*) AS total"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN product_tour_customer AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " WHERE 1=1"
        . " {$filter_users}"
        . " $where"
        . " GROUP BY A.id_product_tour_book"   
        . " ORDER BY tanggal DESC");
    
      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,B.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN product_tour_customer AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " WHERE 1=1"
        . " {$filter_users}"
        . " $where"
          . " GROUP BY A.id_product_tour_book"
        . " ORDER BY tanggal DESC"
         . $lIMIT );
         $cc = $this->db->last_query();
      $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
         
      if($book){
        $additional_tour = "";
        foreach($book AS $ky => $bk){

       $balance[$ky] = $this->global_models->get_query("SELECT nominal,status, id_currency,pos"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); 
              
        foreach ($balance[$ky] as $val_balance) {
           if($val_balance->id_currency == 1){
//               $nom1_usd = $val_balance->nominal;
//               $nom1_idr = $val_balance->nominal * $dropdown_rate[1];
             }elseif($val_balance->id_currency == 2){
//                   $nom1_usd = $val_balance->nominal/$dropdown_rate[1];
                   $nom1_idr = $val_balance->nominal;
             }
                if($val_balance->pos == 1){
//                  $balance_debit_usd[$ky] += $nom1_usd;
                  $balance_debit_idr[$ky] += $nom1_idr;
                }
                elseif($val_balance->pos == 2 AND $val_balance->status != 2){
//                  $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_potongan_tambahan_idr[$ky] += $nom1_idr;
                }elseif($val_balance->pos == 2 AND $val_balance->status == 2){
//                    $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_pembayaran_idr[$ky] += $nom1_idr;
                }
        }
        $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $bk->id_users));
      $name_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store));
      $name_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $bk->id_users));
     
//        $additional = $this->global_models->get_query("SELECT name,nominal,id_currency,pos "
//        . " FROM product_tour_additional"
//        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
        // $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $bk->id_product_tour_book));
         $passenger = $this->global_models->get_query("SELECT status,visa "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
         $jml_book = $this->global_models->get_query("SELECT COUNT(status) AS book "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='1'");
        
        $jml_deposit = $this->global_models->get_query("SELECT COUNT(status) AS deposit "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='2'");
        
        $jml_lunas = $this->global_models->get_query("SELECT COUNT(status) AS lunas "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='3'");
        
        $jml_cancel = $this->global_models->get_query("SELECT COUNT(status) AS cancel "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='4'");
        
        $jml_cancel_deposit = $this->global_models->get_query("SELECT COUNT(status) AS cancel_deposit "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='5'");
        
         $jml_cancel_aproval = $this->global_models->get_query("SELECT COUNT(status) AS cancel_aproval "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='6'");
        
           $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status < '3' ");
        
        if($bk->id_currency == 1){
          $total_visa = ($bk->visa * $total_visa[0]->totl_visa) * $dropdown_rate[1];
        }elseif($bk->id_currency == 2){
          $total_visa = $bk->visa * $total_visa[0]->totl_visa;
        }
        if($jml_book[0]->book > 0){
           $dt_status_book = "<span class='label label-warning'>{$jml_book[0]->book} Book</span><br >";
        }else{
          $dt_status_book = "";
        }
        
        if($jml_deposit[0]->deposit > 0){
           $dt_status_deposit = "<span class='label label-info'>{$jml_deposit[0]->deposit} Deposit</span><br >";
        }else{
          $dt_status_deposit = "";
        }
        
        if($jml_lunas[0]->lunas > 0){
          $dt_status_lunas = "<span class='label label-success'>{$jml_lunas[0]->lunas} Lunas</span><br >";
        }else{
          $dt_status_lunas = "";
        }
        
        if($jml_cancel_aproval[0]->cancel_aproval > 0){
          $dt_status_cancel_aproval = "<span class='label label-default'>{$jml_cancel_aproval[0]->cancel_aproval} Cancel Deposit<br>[Waiting Approval]</span><br >";
          $flag =1;
        }else{
          $dt_status_cancel_aproval = "";
          $flag = 2;
        }
        
        if($jml_cancel[0]->cancel > 0){
          $dt_status_cancel = "<span class='label label-default'>{$jml_cancel[0]->cancel} Cancel</span><br >";
        }else{
          $dt_status_cancel = "";
        }
        
        if($jml_cancel_deposit[0]->cancel_deposit > 0){
          $dt_status_cancel_deposit = "<span class='label label-danger'>{$jml_cancel_deposit[0]->cancel_deposit} Cancel Deposit</span><br >";
          
        }else{
          $dt_status_cancel_deposit = "";
          
        }
        
        $dt_status = $dt_status_book.$dt_status_deposit.$dt_status_lunas.$dt_status_cancel.$dt_status_cancel_deposit.$dt_status_cancel_aproval;
        
         $dt_customer = $this->global_models->get_query("SELECT first_name,last_name,note "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        $dt_note = "";
      foreach ($dt_customer as $val_cus) {
        if($val_cus ->note){
          $dt_note .= "Customer dengan nama ".$val_cus ->first_name." ".$val_cus->last_name." Note Cancel ".$val_cus->note."<br />";
        }
      }
        
        
        $status2 = array("status_customer" =>$dt_status,
                         "flag" => $flag);
          $book_detail[] = array(
            "tour"            => $bk->title,
            "tour_code"       => $bk->code_tour,
            "start_date"      => $bk->start_date,
            "end_date"        => $bk->end_date,
            "info_code"       => $bk->code_info,
            "code"            => $bk->kode,
            "first_name"      => $bk->first_name,
            "last_name"       => $bk->last_name,
            "telp"            => $bk->telphone,
            "email"           => $bk->email,
            "tanggal"         => $bk->tanggal,
            "status"          => $status2,
            "total_visa"      => $total_visa,
            "beban"           => $balance_debit_idr[$ky],
            "potongan"        => $balance_kredit_potongan_tambahan_idr[$ky],  
            "pembayaran"      => $balance_kredit_pembayaran_idr[$ky],
            'currency_rate' => $dropdown_rate[1],
            'note'          => $dt_note,
            "name_store"      => $name_store,
            "name_tc"         => $name_tc,
//            "passenger" => $passenger,
          );
        }
       
        $kirim = array(
          'status'  => 2,
          'book'    => $book_detail,
           'total'       =>$data_total,
//          'note' => $cc
          
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
  
  function status_customer_cancel(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
//      $product_tour_book_customer = $this->global_models->get("product_tour_customer", array("kode" => $pst['code'], "id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
     $usr = $this->global_models->get("users_channel", array("id_users" => $pst['id_users']));
          
      $cek_lunas = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE (nominal * -1) END) AS sisa"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'");
            
              $dt_note .= "";
              $no = 0;
              $no_adl_tt = $no_child_tb = $no_child_eb = $no_child_nb = $no_sgl = 0;
               $type_bed = array(
              1 => "Adult Triple/ Twin",
              2 => "Child Twin Bed",
              3 => "Child Extra Bed",
              4 => "Child No Bed",
              5 => "Adult Single",
            );
          $product_tour_book_customer = $this->global_models->get("product_tour_customer", array("status" => 6, "id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
                 
            foreach ($product_tour_book_customer as $key => $val) {
              if($val->type == 1){
                $no_adl_tt = $no + 1;
                $harga_jual = number_format($product_tour_book[0]->harga_adult_triple_twin);
                }
              elseif($val->type == 2){
                $no_child_tb = $no + 1;
                $harga_jual = number_format($product_tour_book[0]->harga_child_twin_bed);
              }
              elseif($val->type == 3){
                $no_child_eb = $no + 1;
                $harga_jual = number_format($product_tour_book[0]->harga_child_extra_bed);
              }
              elseif($val->type == 4){
                $no_child_nb = $no + 1;
                $harga_jual = number_format($product_tour_book[0]->harga_child_no_bed);
              }
              elseif($val->type == 5){
                $no_sgl = $no + 1;
                $harga_jual = number_format($product_tour_book[0]->harga_single_adult);
              }
              $no++;

              $dt_note .= "Pax : {$val->first_name} {$val->last_name} <br />"
              . "Bed Type : {$type_bed[$val->type]} <br />"
              . "Harga : {$harga_jual} <br />"
              . "Note Cancel : {$val->note} <br /><br />";
               if($pst['status'] == 1)
                {
                $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $val->id_product_tour_customer,"status" => 6), array("status" => 5, "sort" => 1000,"update_by_users" => $pst['id_users']));
                }else{
                   $cek_lunas = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 THEN nominal ELSE (nominal * -1) END) AS sisa"
                                                                . " FROM product_tour_book_payment"
                                                                . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'");
                                                                  if($cek_lunas[0]->sisa > 0){
                                                                    $dt_status = 2;
                                                                  }else{
                                                                    $dt_status = 3;
                                                                  }
                  $this->global_models->update("product_tour_customer", array("id_product_tour_customer" => $val->id_product_tour_customer), array("status" => $dt_status));
                }
            }
            
            if($pst['status'] == 1)
            {
              $this->load->model("json/mjson_tour");
             // $this->mjson_tour->revert_all_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);

             
              if($no_adl_tt){
                $update5 = array("adult_triple_twin" => ($product_tour_book[0]->adult_triple_twin - $no_adl_tt), "update_by_users" => $pst['id_users'],"sort" => 1000);
                 $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $update5);
               
              }elseif($no_child_tb){
                $update5 = array("child_twin_bed" => ($product_tour_book[0]->child_twin_bed - $no_child_tb), "update_by_users" => $pst['id_users'],"sort" => 1000);
                  $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $update5);
               
              }elseif($no_child_eb){
                $update5 = array("child_extra_bed" => ($product_tour_book[0]->child_extra_bed - $no_child_eb), "update_by_users" => $pst['id_users'],"sort" => 1000);
                  $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $update5);
               
              }elseif($no_child_nb){
                $update5 = array("child_no_bed" => ($product_tour_book[0]->child_no_bed - $no_child_nb), "update_by_users" => $pst['id_users'],"sort" => 1000);
                 $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $update5);
               
              }elseif($no_sgl){
                $update5 = array("sgl_supp" => ($product_tour_book[0]->sgl_supp - $no_sgl), "update_by_users" => $pst['id_users'],"sort" => 1000);
                $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), $update5);
               
              }
              
              $kirim_log = array(
              "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
              "id_users"                  => $usr[0]->id_users,
              "name"                      => $usr[0]->name,
              "tanggal"                   => date("Y-m-d H:i:s"),
              "status"                    => 1,
              "note"                      => $dt_note."Cancel Deposit di Setujui Oleh user {{$usr[0]->name}}. Jika ada biaya tambahan Team Operation, harap menambahkan biaya tambahan untuk Status Cancel Deposit",
                "create_by_users"         => $pst['id_users'],
              "create_date"               => date("Y-m-d H:i:s"),
            );
            $this->global_models->insert("product_tour_log_request_additional", $kirim_log);
            
			if($pst['flag'] == 1){
              $this->mjson_tour->revert_all_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
              $this->mjson_tour->recount_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
            
            }
			
           // $this->mjson_tour->recount_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
              if($product_tour_book[0]->status == "6" ){
                $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 5));
                
               }
             
                $payment = $this->global_models->get_query("SELECT SUM(nominal) AS nom"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " AND pos = 2 AND tampil IS NULL AND (status = 2 OR status = 4)");
           
               $kirim = array(
              'status'                  => 4,
              'note'                    => "Terdapat Pembayaran Sebelumnya ",
              'deposit'                => $payment[0]->nom,  
              'keterangan'              => $dt_note   
            );
            }else{
             
              if($product_tour_book[0]->status == "6" ){
                $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => $dt_status));
              }
              
               $kirim_log = array(
              "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
              "id_users"                  => $usr[0]->id_users,
              "name"                      => $usr[0]->name,
              "tanggal"                   => date("Y-m-d H:i:s"),
              "status"                    => 1,
              "note"                      => $dt_note." Cancel Deposit di Batalkan oleh user {$usr[0]->name}",
              "create_by_users"           => $pst['id_users'],
              "create_date"               => date("Y-m-d H:i:s"),
            );
            $this->global_models->insert("product_tour_log_request_additional", $kirim_log);
//            $this->mjson_tour->recount_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
            
              $kirim = array(
                'status'                  => 2,
                'note'                    => $kirim_log['note'],
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
  
  function get_customer_change_list(){
    $pst = $_REQUEST;
    $category = array(1 => "Low Season", 2 => "Hight Season Chrismast", 3 => "Hight Season Lebaran", 4 => "School Holiday Period");
    $sub_category = array(1 => "Eropa", 2 => "Middle East & Africa", 3 => "America", 4 => "Australia & New Zealand", 5 => "Asia", 6 => "China");
    
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      if($pst['list_change'] == "1"){
        $where = "AND (A.status = '7' OR A.status = '8' OR A.status = '9') ";
        
        }
        elseif($pst['list_change'] == "2"){
        $where = "AND A.status < 4 AND (D.status = '7' OR D.status = '8' OR D.status = '9')";
      }
      
      $lIMIT = "";
       if($pst['code']){
          $where .= " AND LOWER(B.kode) LIKE '%".strtolower($pst['code'])."%' OR LOWER(A.kode) LIKE '%".strtolower($pst['code'])."%'"; 
      }
      
       if($pst['title']){
          $where .= " AND LOWER(C.title) LIKE '%".strtolower($pst['title'])."%'"; 
      }
      
      if($pst['start_date'] || $pst['$end_date']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['start_date']} 00:00:00' AND '{$pst['end_date']} 23:59:59')";
      }
      
      if($pst['name']){
          $where .= " AND LOWER(CONCAT(A.first_name, ' ', A.last_name)) LIKE '%".$pst['name']."%'"; 
      }
      
//      if($pst['status']){
//        if($pst['status'] != 100)
//          $where .= " AND A.status = '{$pst['status']}'"; 
//        else
//          $where .= " AND (A.status = '4' OR A.status = '5')"; 
//      }
      
      
      $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
      if(!$id_store){
        $filter_users = "AND A.id_users IN ({$pst['id_users']})";
      }
      else{
      $filter_users = "AND (A.id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}') OR A.id_users IN (SELECT id_users FROM store_commited WHERE id_store = '{$id_store}'))";
      }
      
      if($pst['id_users'] == 1){
        $filter_users = "";
      }
      
       if($pst['start'] > 0){
          $start_limit = $pst['start'];
        }else{
          $start_limit = 0;
        }
        
      if($pst['limit'] OR $pst['start']){
         $lIMIT .= " LIMIT {$start_limit}, {$pst['limit']}";
      }
      
       $data_total = $this->global_models->get_query("SELECT COUNT(*) AS total"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN product_tour_customer AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " WHERE 1=1 AND id_product_tour_book_awal IS NOT NULL "
        . " {$filter_users}"
        . " $where"
        . " GROUP BY A.id_product_tour_book"   
        . " ORDER BY tanggal DESC");
    
      $book = $this->global_models->get_query("SELECT A.*,B.id_currency, B.start_date, B.end_date, B.kode AS code_info,B.airport_tax AS price_tax_insurance, C.title, C.kode AS code_tour,B.visa"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour_information = B.id_product_tour_information"
        . " LEFT JOIN product_tour AS C ON B.id_product_tour = C.id_product_tour"
        . " LEFT JOIN product_tour_customer AS D ON A.id_product_tour_book = D.id_product_tour_book"
        . " WHERE 1=1 AND id_product_tour_book_awal IS NOT NULL "
        . " {$filter_users}"
        . " $where"
          . " GROUP BY A.id_product_tour_book"
        . " ORDER BY tanggal DESC"
         . $lIMIT );
         $cc = $this->db->last_query();
      $dropdown_rate = $this->global_models->get_dropdown("master_currency_rate", "id_master_currency", "rate", FALSE);     
         
      if($book){
        $additional_tour = "";
        foreach($book AS $ky => $bk){

       $balance[$ky] = $this->global_models->get_query("SELECT nominal,status, id_currency,pos"
              . " FROM product_tour_book_payment"
              . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'"); 
              
        foreach ($balance[$ky] as $val_balance) {
           if($val_balance->id_currency == 1){
//               $nom1_usd = $val_balance->nominal;
//               $nom1_idr = $val_balance->nominal * $dropdown_rate[1];
             }elseif($val_balance->id_currency == 2){
//                   $nom1_usd = $val_balance->nominal/$dropdown_rate[1];
                   $nom1_idr = $val_balance->nominal;
             }
                if($val_balance->pos == 1){
//                  $balance_debit_usd[$ky] += $nom1_usd;
                  $balance_debit_idr[$ky] += $nom1_idr;
                }
                elseif($val_balance->pos == 2 AND $val_balance->status != 2){
//                  $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_potongan_tambahan_idr[$ky] += $nom1_idr;
                }elseif($val_balance->pos == 2 AND $val_balance->status == 2){
//                    $balance_kredit_usd[$ky] += $nom1_usd;
                  $balance_kredit_pembayaran_idr[$ky] += $nom1_idr;
                }
        }
        $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $bk->id_users));
      $name_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store));
      $name_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $bk->id_users));
     
//        $additional = $this->global_models->get_query("SELECT name,nominal,id_currency,pos "
//        . " FROM product_tour_additional"
//        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
        // $passenger = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $bk->id_product_tour_book));
         $passenger = $this->global_models->get_query("SELECT status,visa "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        
         $jml_book = $this->global_models->get_query("SELECT COUNT(status) AS book "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='1'");
        
        $jml_deposit = $this->global_models->get_query("SELECT COUNT(status) AS deposit "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='2'");
        
        $jml_lunas = $this->global_models->get_query("SELECT COUNT(status) AS lunas "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='3'");
        
        $jml_cancel = $this->global_models->get_query("SELECT COUNT(status) AS cancel "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='4'");
        
        $jml_cancel_deposit = $this->global_models->get_query("SELECT COUNT(status) AS cancel_deposit "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='5'");
        
         $jml_cancel_aproval = $this->global_models->get_query("SELECT COUNT(status) AS cancel_aproval "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='6'");
        
         $jml_wa_change_tour = $this->global_models->get_query("SELECT COUNT(status) AS wa_change_tour"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='7'");
        
        $jml_cancel_change_tour = $this->global_models->get_query("SELECT COUNT(status) AS cancel_change_tour"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='8'");
        
        $jml_reject_change_tour = $this->global_models->get_query("SELECT COUNT(status) AS reject_change_tour"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status='9'");
        
        
           $total_visa = $this->global_models->get_query("SELECT sum(visa) as totl_visa"
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}' AND status < '3' ");
        
        if($bk->id_currency == 1){
          $total_visa = ($bk->visa * $total_visa[0]->totl_visa) * $dropdown_rate[1];
        }elseif($bk->id_currency == 2){
          $total_visa = $bk->visa * $total_visa[0]->totl_visa;
        }
        if($jml_book[0]->book > 0){
           $dt_status_book = "<span class='label label-warning'>{$jml_book[0]->book} Book</span><br >";
        }else{
          $dt_status_book = "";
        }
        
        if($jml_deposit[0]->deposit > 0){
           $dt_status_deposit = "<span class='label label-info'>{$jml_deposit[0]->deposit} Deposit</span><br >";
        }else{
          $dt_status_deposit = "";
        }
        
        if($jml_lunas[0]->lunas > 0){
          $dt_status_lunas = "<span class='label label-success'>{$jml_lunas[0]->lunas} Lunas</span><br >";
        }else{
          $dt_status_lunas = "";
        }
        
        if($jml_cancel_aproval[0]->cancel_aproval > 0){
          $dt_status_cancel_aproval = "<span class='label label-default'>{$jml_cancel_aproval[0]->cancel_aproval} Cancel Deposit<br>[Waiting Approval]</span><br >";
         
        }else{
          $dt_status_cancel_aproval = "";
         
        }
        
        if($jml_cancel[0]->cancel > 0){
          $dt_status_cancel = "<span class='label label-default'>{$jml_cancel[0]->cancel} Cancel</span><br >";
        }else{
          $dt_status_cancel = "";
        }
        
        if($jml_cancel_deposit[0]->cancel_deposit > 0){
          $dt_status_cancel_deposit = "<span class='label label-danger'>{$jml_cancel_deposit[0]->cancel_deposit} Cancel Deposit</span><br >";
          
        }else{
          $dt_status_cancel_deposit = "";
          
        }
        
        if($jml_reject_change_tour[0]->reject_change_tour > 0){
          $dt_status_reject_change_tour = "<span class='label label-default'>{$jml_reject_change_tour[0]->reject_change_tour} Reject<br> Change Tour</span><br >";
          
        }else{
          $dt_status_reject_change_tour = "";
          
        }
        
        if($jml_cancel_change_tour[0]->cancel_change_tour > 0){
          $dt_status_cancel_change = "<span class='label label-danger'>{$jml_cancel_change_tour[0]->cancel_change_tour} Cancel Change Tour</span><br >";
          
        }else{
          $dt_status_cancel_change = "";
          
        }
        
        if($jml_wa_change_tour[0]->wa_change_tour > 0){
          $dt_status_wa_change_tour = "<span class='label label-default'>{$jml_wa_change_tour[0]->wa_change_tour} Waiting Approval<br> Change Tour</span><br >";
           $flag =1;
        }else{
          $dt_status_wa_change_tour = "";
           $flag = 2;
        }
        
        $dt_status = $dt_status_book.$dt_status_deposit.$dt_status_lunas.$dt_status_cancel.$dt_status_cancel_deposit.$dt_status_cancel_aproval.$dt_status_cancel_change.$dt_status_wa_change_tour.$dt_status_reject_change_tour;
        
         $dt_customer = $this->global_models->get_query("SELECT first_name,last_name,note "
        . " FROM product_tour_customer"
        . " WHERE id_product_tour_book = '{$bk->id_product_tour_book}'");
        $dt_note = "";
      foreach ($dt_customer as $val_cus) {
        if($val_cus ->note){
          $dt_note .= "Customer dengan nama ".$val_cus ->first_name." ".$val_cus->last_name." Note Cancel ".$val_cus->note."<br />";
        }
      }
        
        
        $status2 = array("status_customer" =>$dt_status,
                         "flag" => $flag);
          $book_detail[] = array(
            "tour"            => $bk->title,
            "tour_code"       => $bk->code_tour,
            "start_date"      => $bk->start_date,
            "end_date"        => $bk->end_date,
            "info_code"       => $bk->code_info,
            "code"            => $bk->kode,
            "first_name"      => $bk->first_name,
            "last_name"       => $bk->last_name,
            "telp"            => $bk->telphone,
            "email"           => $bk->email,
            "tanggal"         => $bk->tanggal,
            "status"          => $status2,
            "total_visa"      => $total_visa,
            "beban"           => $balance_debit_idr[$ky],
            "potongan"        => $balance_kredit_potongan_tambahan_idr[$ky],  
            "pembayaran"      => $balance_kredit_pembayaran_idr[$ky],
            'currency_rate' => $dropdown_rate[1],
            'note'          => $dt_note,
            "name_store"      => $name_store,
            "name_tc"         => $name_tc,
//            "passenger" => $passenger,
          );
        }
       
        $kirim = array(
          'status'  => 2,
          'book'    => $book_detail,
           'total'       =>$data_total,
          'note' => $cc
          
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
  
  function status_customer_change_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->load->model("json/mjson_tour");
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      
      $product_tour_book_new = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book_awal));
      
      $name_user = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
       
      $kode_tour = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information));

        $kode_tour_new = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $product_tour_book_new[0]->id_product_tour_information));
      
      //keterangan Status 1=> Approve, status 2=> Reject
      if($pst['status'] == 1)
      {
         $update = array(
          "status"              => 8,
          "sort"                => 2000, 
          "update_by_users"     => $pst['id_users'],
          "update_date"         => date("Y-m-d H:i:s")
         );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book),$update);

      if($data_update > 0){
     
        $update_cust = array(
            "status"              => 8,
            "sort"                => 2000,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
        $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book),$update_cust);
      
           $sort_cust2 = $this->global_models->get_query("SELECT max(A.sort) AS cust_sort"
              . " FROM product_tour_customer AS A"
              . " WHERE id_product_tour_information = '{$product_tour_book_new[0]->id_product_tour_information}' AND A.status < '4' ");
        
               $pro_tour_book = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book_new[0]->id_product_tour_book));
               $no_sort = 0;
               $sort_cust4 = $sort_cust2[0]->cust_sort;
               foreach ($pro_tour_book as $ky2 => $val2) {
                
                    $no_sort  = $no_sort + 1;
                  
              if($sort_cust4 > 0){
                $sort_book_3  = $sort_cust4 + $no_sort;
              }else{
                $sort_book_3  = $no_sort;
              }
              
                    $kirim_st = array("status" => 2,
                                      "sort"    =>$sort_book_3);
                    $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book_new[0]->id_product_tour_book), $kirim_st);
            
               }
               
               $sort_bk4 = $this->global_models->get_query("SELECT max(sort) AS book_sort"
                . " FROM product_tour_book"
                . " WHERE id_product_tour_information = '{$book[0]->id_product_tour_information}' AND status < '4' ");
                
                if($sort_bk4[0]->book_sort > 0){
                  $sort_book4  = $sort_bk4[0]->book_sort + 1;
                }else{
                  $sort_book4  = "1";
                }
                        $kirim_st3 = array("status" => 2,
                                            "sort"    => $sort_book4);
                $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book_new[0]->id_product_tour_book), $kirim_st3);
      
         
        
       $product_tour_payment = $this->global_models->get_query("SELECT id_product_tour_book_payment,id_inventory,nominal,pos,status,no_deposit,no_ttu,payment,note,remark"
          . " FROM product_tour_book_payment"
          . " WHERE id_product_tour_book = '{$product_tour_book[0]->id_product_tour_book}'"
          . " AND pos = '2' AND tampil IS NULL AND status IN(2,4) ");
           
       foreach ($product_tour_payment as $val_payment) {
           $krm = array("flag_history" => 2);
         $this->global_models->update("product_tour_book_payment",array("id_product_tour_book_payment" => "{$val_payment->id_product_tour_book_payment}"), $krm);
         $this->olah_payment_code($kode2);
         $kirim2 = array(
          "id_product_tour_book"        => $product_tour_book[0]->id_product_tour_book_awal,
          "id_users"                    => $pst['id_users'],
          "nominal"                     => $val_payment->nominal,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "kode"                        => $kode2,
          "no_deposit"                  => $val_payment->no_deposit,
          "no_ttu"                      => $val_payment->no_ttu,
          "pos"                         => $val_payment->pos,
          "payment"                     => $val_payment->payment,
          "id_inventory"                => $val_payment->id_inventory,
          "id_currency"                 => 2,
          "flag_history"                => 0,   
          "status"                      => $val_payment->status,
          "remark"                      => $val_payment->remark,   
          "note"                        => $val_payment->note."[Pindah payment dari {$pst["code"]}]",
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim2);
        if($val_payment->status == 4){
            $get_payment = $this->global_models->get("tour_payment",array("id_product_tour_book_payment" => $val_payment->id_product_tour_book_payment,"status" => 2));
        
        foreach($get_payment as $vp) {
             $kr = array(
          "id_product_tour_book_payment"    => $id_product_tour_book_payment,
          "id_users"                        => $vp->id_users,
          "id_users_confirm"                => $vp->id_users_confirm,
          "type"                            => $vp->type,
          "nominal"                         => $vp->nominal,
          "tanggal"                         => $vp->tanggal,
          "status"                          => $vp->status,
          "mdr"                             => $vp->mdr,
          "number"                          => $vp->number,
          "remarks"                         => $vp->remarks,
          "note"                            => $vp->note,
          "create_by_users"                 => $users[0]->id_users,
          "create_date"                     => date("Y-m-d H:i:s")
        );
        $id_pyt = $this->global_models->insert("tour_payment", $kr);
        $kr2 = array("note" => $vp->note."<br> Dipindahkan ke id payment".$id_pyt,
                     "status" => 3);
        $this->global_models->update("tour_payment",array("id_tour_payment" => "{$vp->id_tour_payment}"), $kr2);
          }
        }
         $this->olah_payment_code($kode);
         $kirim = array(
          "id_product_tour_book"        => $product_tour_book[0]->id_product_tour_book,
          "id_users"                    => $pst['id_users'],
          "nominal"                     => $val_payment->nominal,
          "tanggal"                     => date("Y-m-d H:i:s"),
          "kode"                        => $kode,
          "no_deposit"                  => $val_payment->no_deposit,
          "no_ttu"                      => $val_payment->no_ttu,
          "pos"                         => 1,
          "payment"                     => $val_payment->payment,
          "flag_history"                => 2,  
          "id_currency"                 => 2,
          "status"                      => 2,
          "note"                        => $val_payment->note."[Cancel Payment dipindahkan ke book {$product_tour_book_new[0]->kode}]",
          "create_by_users"             => $users[0]->id_users,
          "create_date"                 => date("Y-m-d H:i:s")
        );
        $id_product_tour_book_payment = $this->global_models->insert("product_tour_book_payment", $kirim);
        
       }
       
       $this->mjson_tour->cek_status_book($product_tour_book_new[0]->id_product_tour_book, $pst['id_users']);
        
       $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Cancel karena Pindah Tour di Approve oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}], Dari Kode Book [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       
         $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book_awal,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour di Approve oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}] Untuk Kode Book [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        $kirim = array(
              'status'  => 4,
              'note'    => 'Change Tour Approve'
            );
      }
      
     }elseif($pst['status'] == 2){
      
        $update = array(
          "status"                    => 9,
          "sort"                      => 2000, 
          "update_by_users"           => $pst['id_users'],
          "id_product_tour_book_awal" => $product_tour_book[0]->id_product_tour_book,
          "update_date"               => date("Y-m-d H:i:s"),
          "note"                      => "Reject Change Tour oleh user {$name_user}"
         );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$product_tour_book_new[0]->id_product_tour_book),$update);
 
       if($data_update > 0){
     
        $update_cust = array(
            "status"              => 9,
            "sort"                => 2000,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
        $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$product_tour_book_new[0]->id_product_tour_book),$update_cust);
       
        $update = array(
          "status"                    => 1,
          "update_by_users"           => $pst['id_users'],
          "id_product_tour_book_awal" => NULL,
          "update_date"               => date("Y-m-d H:i:s"),
         );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book),$update);

      $update_cust = array(
            "status"              => 1,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
        $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book),$update_cust);
       
        $this->mjson_tour->revert_all_payment($product_tour_book_new[0]->id_product_tour_book, $pst['id_users']);
        $this->mjson_tour->cek_status_book($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
        $this->mjson_tour->update_sort_book($product_tour_book[0]->id_product_tour_book);
        
       $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book_awal,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour di Reject oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}], Dari Kode Book [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       
         $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour di Reject oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}] Untuk Kode Book [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
       
       }
      
       $kirim = array(
              'status'  => 2,
              'kode'    => $product_tour_book_new[0]->kode,
              'note'    => 'Change Tour Reject'
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
  
  function status_customer_change_pax(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->load->model("json/mjson_tour");
      $product_tour_book = $this->global_models->get("product_tour_book", array("kode" => $pst['code']));
      
      $product_tour_book_new = $this->global_models->get("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book_awal));
      
      $name_user = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
       
      $kode_tour = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information));
       
        $kode_tour_new = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $product_tour_book_new[0]->id_product_tour_information));
      
      //keterangan Status 1=> Approve, status 2=> Reject
      if($pst['status'] == 1)
      {
          $no = 0;
              $no_adl_tt = $no_child_tb = $no_child_eb = $no_child_nb = $no_sgl = 0;
              
          $product_tour_book_customer = $this->global_models->get("product_tour_customer", array("status" => 7, "id_product_tour_book" => $product_tour_book[0]->id_product_tour_book));
                 
            foreach ($product_tour_book_customer as $key => $val) {
              if($val->type == 1){
                $no_adl_tt = ($product_tour_book[0]->adult_triple_twin - 1);
                
                }
              elseif($val->type == 2){
                $no_child_tb = ($product_tour_book[0]->child_twin_bed - 1);
              
              }
              elseif($val->type == 3){
                $no_child_eb = ($product_tour_book[0]->child_extra_bed - 1);
               
              }
              elseif($val->type == 4){
                $no_child_nb = ($product_tour_book[0]->child_no_bed - 1);
               
              }
              elseif($val->type == 5){
                $no_sgl = ($product_tour_book[0]->sgl_supp - 1);
               
              }
            }
            
            $update = array(
          "adult_triple_twin"   => $no_adl_tt,
          "child_twin_bed"      => $no_child_tb, 
          "child_extra_bed"     => $no_child_eb,
          "child_no_bed"        => $no_child_nb, 
          "sgl_supp"            => $no_sgl,    
          "update_by_users"     => $pst['id_users'],
          "update_date"         => date("Y-m-d H:i:s")
         );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book),$update);

              
        $update_cust = array(
            "status"              => 8,
            "sort"                => 2000,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
        $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book, "status" => 7),$update_cust);
      
           $sort_cust2 = $this->global_models->get_query("SELECT max(A.sort) AS cust_sort"
              . " FROM product_tour_customer AS A"
              . " WHERE id_product_tour_information = '{$product_tour_book_new[0]->id_product_tour_information}' AND A.status < '4' ");
        
               $pro_tour_book = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $product_tour_book_new[0]->id_product_tour_book));
               $no_sort = 0;
               $sort_cust4 = $sort_cust2[0]->cust_sort;
               foreach ($pro_tour_book as $ky2 => $val2) {
                
                    $no_sort  = $no_sort + 1;
                  
              if($sort_cust4 > 0){
                $sort_book_3  = $sort_cust4 + $no_sort;
              }else{
                $sort_book_3  = $no_sort;
              }
              
                    $kirim_st = array("status" => 2,
                                      "sort"    =>$sort_book_3);
                    $this->global_models->update("product_tour_customer", array("id_product_tour_book" => $product_tour_book_new[0]->id_product_tour_book), $kirim_st);
            
               }
               
               $sort_bk4 = $this->global_models->get_query("SELECT max(sort) AS book_sort"
                . " FROM product_tour_book"
                . " WHERE id_product_tour_information = '{$book[0]->id_product_tour_information}' AND status < '4' ");
                
                if($sort_bk4[0]->book_sort > 0){
                  $sort_book4  = $sort_bk4[0]->book_sort + 1;
                }else{
                  $sort_book4  = "1";
                }
                        $kirim_st3 = array("status" => 2,
                                            "sort"    => $sort_book4);
                $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book_new[0]->id_product_tour_book), $kirim_st3);
      
         
        $kode_tour = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $product_tour_book[0]->id_product_tour_information));
       
        $kode_tour_new = $this->global_models->get_field("product_tour_information", "kode", array("id_product_tour_information" => $product_tour_book_new[0]->id_product_tour_information));
      
       
       
        $this->mjson_tour->cek_status_book($product_tour_book_new[0]->id_product_tour_book, $pst['id_users']);
       
        $this->mjson_tour->revert_all_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
        $this->mjson_tour->recount_payment($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
            
       $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Cancel karena Pindah Tour per-Pax di Approve oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}], Dari Kode Book Sebelumnya [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       
         $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book_awal,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour per-Pax di Approve oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}] Untuk Kode Book Sebelumnya [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        $kirim = array(
              'status'  => 4,
              'note'    => 'Change Tour Approve'
            );
     
      
     }elseif($pst['status'] == 2){
      
        $update = array(
          "status"                    => 9,
          "sort"                      => 2000, 
          "update_by_users"           => $pst['id_users'],
          "id_product_tour_book_awal" => $product_tour_book[0]->id_product_tour_book,
          "update_date"               => date("Y-m-d H:i:s"),
          "note"                      => "Reject Change Tour oleh user {$name_user}"
         );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$product_tour_book_new[0]->id_product_tour_book),$update);

       if($data_update > 0){
     
        $update_cust = array(
            "status"              => 9,
            "sort"                => 2000,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
        $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$product_tour_book_new[0]->id_product_tour_book),$update_cust);
       
        $update = array(
          "status"                    => 1,
          "update_by_users"           => $pst['id_users'],
          "id_product_tour_book_awal" => NULL,
          "update_date"               => date("Y-m-d H:i:s"),
         );
      $data_update = $this->global_models->update("product_tour_book", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book),$update);

      $update_cust = array(
            "status"              => 1,
            "update_by_users"     => $pst['id_users'],
            "update_date"         => date("Y-m-d H:i:s")
            );
        $this->global_models->update("product_tour_customer", array("id_product_tour_book" =>$product_tour_book[0]->id_product_tour_book),$update_cust);
       
        $this->mjson_tour->revert_all_payment($product_tour_book_new[0]->id_product_tour_book, $pst['id_users']);
        $this->mjson_tour->cek_status_book($product_tour_book[0]->id_product_tour_book, $pst['id_users']);
        
       $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book_awal,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour di Reject oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}], Dari Kode Book Sebelumnya [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
        
       
         $kirim_log_add = array(
          "id_product_tour_book"      => $product_tour_book[0]->id_product_tour_book,
          "id_users"                  => 1,
          "name"                      => "System",
          "tanggal"                   => date("Y-m-d H:i:s"),
          "status"                    => 1,
          "note"                      => "Pindah Tour di Reject oleh user ({$name_user}) Dari Kode Tour [{$kode_tour}] Ke Kode Tour [{$kode_tour_new}] Untuk Kode Book Sebelumnya [{$pst["code"]}] Menjadi Kode Book [{$product_tour_book_new[0]->kode}]",
          "create_by_users"           => $this->session->userdata("id"),
          "create_date"               => date("Y-m-d H:i:s")
       );
       $this->global_models->insert("product_tour_log_request_additional", $kirim_log_add);
       
       }
       $kirim = array(
              'status'  => 2,
              "kode"    => $product_tour_book_new[0]->kode,
              'note'    => 'Change Tour Reject'
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
  
  function cancel_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
      $commited = false;
      if(!$id_store){
        $id_store = $this->global_models->get_field("store_commited", "id_store", array("id_users" => $pst['id_users']));
        $commited = true;
      }
      if($id_store){
        if($commited === true){
          $product_tour_book = $this->global_models->get_query("SELECT id_product_tour_book"
            . " FROM product_tour_book"
            . " WHERE kode = '{$pst['code']}'"
            . " AND (id_users IN (SELECT id_users FROM store_tc WHERE id_store = '{$id_store}')"
            . " OR id_users IN (SELECT id_users FROM store_commited WHERE id_store = '{$id_store}'))");
        }
        else{
          $product_tour_book = $this->global_models->get_query("SELECT id_product_tour_book"
            . " FROM product_tour_book"
            . " WHERE kode = '{$pst['code']}'"
            . " AND id_users = '{$pst['id_users']}");
        }
        if($product_tour_book[0]->id_product_tour_book){
          $cek_pembayaran = $this->global_models->get_field("product_tour_book_payment", "sum(nominal)", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book, "pos" => 2, "status <>" => 3));
          if($cek_pembayaran > 0){
            $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 5, "update_by_users" => $pst['id_users']));
            $kirim = array(
              'status'    => 2,
              'note' => "Cancel Tour Miliki Tanggungan",
            );
          }
          else{
            $this->global_models->update("product_tour_book", array("id_product_tour_book" => $product_tour_book[0]->id_product_tour_book), array("status" => 4, "update_by_users" => $pst['id_users']));
            $kirim = array(
              'status'  => 4,
              'note'    => 'Cancel Tour'
            );
          }
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Code Book Tidak Sesuai'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 5,
          'note'    => 'Store Tidak Diketahui'
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
  /**
   * end product tour
   */
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */