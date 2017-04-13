<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_tour_fit extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
    $this->load->model("json/mjson_tour_fit");
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
  
  function set_tour_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $kode = "";
      $this->olah_code($kode, "tour_fit_request", 6);
      $post = array(
        "id_users"            => $pst['id_users'],
        "id_store"            => $pst['id_store'],
        "adult"               => $pst['adult'],
        "child"               => $pst['child'],
        "budget_start"        => $pst['budget_start'],
        "budget_end"          => $pst['budget_end'],
        "destination"         => $pst['destination'],
        "hotel"               => $pst['hotel'],
        "fare_est"            => $pst['fare_est'],
        "pnr"                 => $pst['pnr'],
        "other"               => $pst['other'],
        "kode"                => $kode,
        "tanggal"             => $pst['tanggal'],
        "departure"           => $pst['departure'],
        "arrive"              => $pst['arrive'],
        "title"               => $pst['title'],
        "client"              => $pst['client'],
        "airline"             => $pst['airline'],
        "status"              => 1,
        "create_by_users"     => $users[0]->id_users,
        "create_date"         => date("Y-m-d H:i:s"),
      );
      $id_tour_fit_request = $this->global_models->insert("tour_fit_request", $post);
      if($id_tour_fit_request){
        $log_note = ""
          . "<ul>"
            . "<li>Book Code : {$kode}</li>"
            . "<li>Client : {$kode}</li>"
            . "<li>Project Name : {$pst['title']}</li>"
            . "<li>Project Date : {$pst['departure']}</li>"
            . "<li>Number of Pax : {$pst['adult']} Adult {$pst['child']} Child</li>"
            . "<li>Destination : {$pst['destination']}</li>"
            . "<li>Budget : ".number_format($pst['budget_start'])." - ".number_format($pst['budget_end'])."</li>"
            . "<li>Airline : {$pst['airline']}</li>"
            . "<li>Hotel : {$pst['hotel']}</li>"
            . "<li>Other Request : {$pst['other']}</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note,"Create Request");
        $quo = array(
          "id_users"            => $pst['id_users'],
          "id_tour_fit_request" => $id_tour_fit_request,
          "destination"         => $pst['destination'],
          "airline"             => $pst['airline'],
          "hotel"               => $pst['hotel'],
          "status"              => 1,
          "create_by_users"     => $users[0]->id_users,
          "create_date"         => date("Y-m-d H:i:s"),
        );
        $id_tour_fit_quotation = $this->global_models->insert("tour_fit_quotation", $quo);
        
        $kirim = array(
          'status'  => 2,
          'id'      => $id_tour_fit_request,
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
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    print json_encode($kirim);
    die;
  }
  
  function set_status_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $req_status = $this->global_models->get_field("tour_fit_quotation", "status", array("id_tour_fit_request" => $id_tour_fit_request));
        $this->global_models->update("tour_fit_request", array("id_tour_fit_request" => $id_tour_fit_request), array("status" => $pst['status']));
        $update_quo = array("status" => $pst['status']);
        if($pst['note_cancel'])
          $update_quo['note_cancel'] = $pst['note_cancel'];
        $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $id_tour_fit_request), $update_quo);
        if($pst['status'] == 3){
          $this->global_models->update("tour_fit_request_detail", array("id_tour_fit_request" => $id_tour_fit_request, "type" => 2), array("type" => 3));
        }
        $status = array(
          1 => "<span class='label label-warning'>Request</span>",
          2 => "<span class='label label-info'>Proposal</span>",
          3 => "<span class='label label-success'>Book</span>",
          4 => "<span class='label label-success'>DP</span>",
          5 => "<span class='label label-danger'>Cancel</span>",
          6 => "<span class='label label-success'>Lunas</span>",
          7 => "<span class='label label-success'>Quotation</span>",
          8 => "<span class='label label-success'>Req Timelimit</span>",
          9 => "<span class='label label-success'>Set Timelimit</span>",
        );
        $log_note = ""
          . "Change status From {$status[$req_status]} -> {$status[$pst['status']]}"
          . "";
        $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note,"Update Status Request");
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil',
          'users_opt' => $this->global_models->get_field("tour_fit_quotation", "id_users", array("id_tour_fit_request" => $id_tour_fit_request)),
          'users'   => $this->global_models->get_field("tour_fit_request", "id_users", array("id_tour_fit_request" => $id_tour_fit_request)),
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function generate_price_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      
      if($id_tour_fit_request){
        $this->mjson_tour_fit->revert_system_price($id_tour_fit_request,$pst['id_users']);
        $this->mjson_tour_fit->revert_system_price2($id_tour_fit_request,$pst['id_users']);
        $quo = $this->global_models->get("tour_fit_quotation", array("id_tour_fit_request" => $id_tour_fit_request));
        $quo2 = $this->global_models->get("tour_fit_request_price_tag", array("id_tour_fit_request" => $id_tour_fit_request, "pilih" => 2));
//        jumlah pax
        $pax_tiket = $this->global_models->get_query("SELECT type, COUNT(id_tour_fit_request_pax) AS jumlah"
          . " FROM tour_fit_request_pax"
          . " WHERE id_tour_fit_request = '{$id_tour_fit_request}'"
          . " GROUP BY type");
        $field_tiket = array(
          1 => "adult_fare",
          2 => "child_fare",
          3 => "infant_fare",
        );
        $field_tiket_view = array(
          1 => "Adult Fare",
          2 => "Child Fare",
          3 => "Infant Fare",
        );
        
        foreach($pax_tiket AS $pt){
//          $p_tiket[$pt->type] = $pt->jumlah;
          $kode = "";
          $this->olah_code($kode, "tour_fit_request_price");
          $price[] = array(
            "id_tour_fit_request"         => $id_tour_fit_request,
            "id_users"                    => $pst['id_users'],
            "kode"                        => $kode,
            "type"                        => 1,
            "title"                       => $pt->jumlah." ".$field_tiket_view[$pt->type],
            "price"                       => $quo[0]->{$field_tiket[$pt->type]},
            "qty"                         => $pt->jumlah,
            "total"                       => ($quo[0]->$field_tiket[$pt->type] * $pt->jumlah),
            "tanggal"                     => date("Y-m-d H:i:s"),
            "pos"                         => 1,
            "status"                      => 1,
            "create_by_users"             => $users[0]->id_users,
            "create_date"                 => date("Y-m-d H:i:s"),
          );
          $price2[] = array(
            "id_tour_fit_request"         => $id_tour_fit_request,
            "id_users"                    => $pst['id_users'],
            "kode"                        => $kode,
            "type"                        => 1,
            "title"                       => $pt->jumlah." ".$field_tiket_view[$pt->type],
            "price"                       => $quo2[0]->{$field_tiket[$pt->type]},
            "qty"                         => $pt->jumlah,
            "total"                       => ($quo2[0]->$field_tiket[$pt->type] * $pt->jumlah),
            "tanggal"                     => date("Y-m-d H:i:s"),
            "pos"                         => 1,
            "status"                      => 1,
            "create_by_users"             => $users[0]->id_users,
            "create_date"                 => date("Y-m-d H:i:s"),
          );
          $log_note = ""
            . "<ul>"
              . "<li>Code : {$kode}</li>"
              . "<li>Title : {$pt->jumlah} {$field_tiket_view[$pt->type]}</li>"
              . "<li>Price : ".number_format($quo[0]->$field_tiket[$pt->type])." * {$pt->jumlah} = ".number_format(($quo[0]->$field_tiket[$pt->type] * $pt->jumlah))."</li>"
            . "</ul>"
            . "";
          $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note, "Create Price");
        }
        
        $pax_bed = $this->global_models->get_query("SELECT bed_type, COUNT(id_tour_fit_request_pax) AS jumlah"
          . " FROM tour_fit_request_pax"
          . " WHERE id_tour_fit_request = '{$id_tour_fit_request}'"
          . " GROUP BY bed_type");
        $field_tiket = array(
          1 => "adult_triple_twin",
          2 => "adult_sgl_supp",
          3 => "child_twin_bed",
          4 => "child_extra_bed",
          5 => "child_no_bed",
        );
        $field_tiket_view = array(
          1 => "Adult Triple Twin",
          2 => "Adult Sgl SUPP",
          3 => "Child Twin Bed",
          4 => "Child Extra Bed",
          5 => "Child No Bed",
        );
        foreach($pax_bed AS $pb){
//          $p_bed[$pt->bed_type] = $pt->jumlah;
          $quo = $this->global_models->get("tour_fit_quotation", array("id_tour_fit_request" => $id_tour_fit_request));
          $kode = "";
          $this->olah_code($kode, "tour_fit_request_price");
          if($pb->bed_type == 2){
            $pp = $quo[0]->adult_triple_twin + $quo[0]->adult_sgl_supp;
            $pto = (($quo[0]->adult_triple_twin + $quo[0]->adult_sgl_supp) * $pb->jumlah);
            $pp2 = $quo2[0]->adult_triple_twin + $quo2[0]->adult_sgl_supp;
            $pto2 = (($quo2[0]->adult_triple_twin + $quo2[0]->adult_sgl_supp) * $pb->jumlah);
          }
          else{
            $pp = $quo[0]->{$field_tiket[$pb->bed_type]};
            $pto = ($quo[0]->{$field_tiket[$pb->bed_type]} * $pb->jumlah);
            $pp2 = $quo2[0]->{$field_tiket[$pb->bed_type]};
            $pto2 = ($quo2[0]->{$field_tiket[$pb->bed_type]} * $pb->jumlah);
          }
          $price[] = array(
            "id_tour_fit_request"         => $id_tour_fit_request,
            "id_users"                    => $pst['id_users'],
            "kode"                        => $kode,
            "type"                        => 1,
            "title"                       => $pb->jumlah." ".$field_tiket_view[$pb->bed_type],
            "price"                       => $pp,
            "qty"                         => $pb->jumlah,
            "total"                       => $pto,
            "tanggal"                     => date("Y-m-d H:i:s"),
            "pos"                         => 1,
            "status"                      => 1,
            "create_by_users"             => $users[0]->id_users,
            "create_date"                 => date("Y-m-d H:i:s"),
          );
          $price2[] = array(
            "id_tour_fit_request"         => $id_tour_fit_request,
            "id_users"                    => $pst['id_users'],
            "kode"                        => $kode,
            "type"                        => 1,
            "title"                       => $pb->jumlah." ".$field_tiket_view[$pb->bed_type],
            "price"                       => $pp2,
            "qty"                         => $pb->jumlah,
            "total"                       => $pto2,
            "tanggal"                     => date("Y-m-d H:i:s"),
            "pos"                         => 1,
            "status"                      => 1,
            "create_by_users"             => $users[0]->id_users,
            "create_date"                 => date("Y-m-d H:i:s"),
          );
          $log_note = ""
            . "<ul>"
              . "<li>Code : {$kode}</li>"
              . "<li>Title : {$pb->jumlah} {$field_tiket_view[$pb->bed_type]}</li>"
              . "<li>Price : ".number_format($quo[0]->{$field_tiket[$pb->bed_type]})." * {$pb->jumlah} = ".number_format(($quo[0]->{$field_tiket[$pb->bed_type]} * $pb->jumlah))."</li>"
            . "</ul>"
            . "";
          $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note, "Create Price");
          $entrance = $this->global_models->get("tour_fit_request_detail", array("type" => 3, "entrance >" => 0, "id_tour_fit_request" => $id_tour_fit_request));
          foreach($entrance AS $enter){
            $kode = "";
            $this->olah_code($kode, "tour_fit_request_price");
            $price[] = array(
              "id_tour_fit_request"         => $id_tour_fit_request,
              "id_users"                    => $pst['id_users'],
              "kode"                        => $kode,
              "type"                        => 1,
              "title"                       => $pt->jumlah." Entrance Fee",
              "price"                       => $enter->entrance,
              "qty"                         => $pb->jumlah,
              "total"                       => ($enter->entrance * $pb->jumlah),
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 1,
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s"),
            );
            $price2[] = array(
              "id_tour_fit_request"         => $id_tour_fit_request,
              "id_users"                    => $pst['id_users'],
              "kode"                        => $kode,
              "type"                        => 1,
              "title"                       => $pt->jumlah." Entrance Fee",
              "price"                       => $enter->entrance,
              "qty"                         => $pb->jumlah,
              "total"                       => ($enter->entrance * $pb->jumlah),
              "tanggal"                     => date("Y-m-d H:i:s"),
              "pos"                         => 1,
              "status"                      => 1,
              "create_by_users"             => $users[0]->id_users,
              "create_date"                 => date("Y-m-d H:i:s"),
            );
            $log_note = ""
              . "<ul>"
                . "<li>Code : {$kode}</li>"
                . "<li>Title : {$pb->jumlah} Entrance Fee</li>"
                . "<li>Price : ".number_format($enter->entrance)." * {$pb->jumlah} = ".number_format(($enter->entrance * $pb->jumlah))."</li>"
              . "</ul>"
              . "";
            $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note, "Create Price");
          }
        }
//        $this->debug($price,true);
        if($price){
          $this->global_models->insert_batch("tour_fit_request_price", $price);
          $this->global_models->insert_batch("tour_fit_request_price_hpp", $price2);
        }
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function set_tour_fit_quotation(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_fit_request = $this->global_models->get("tour_fit_request", array("kode" => $pst['code']));
      if($tour_fit_request){
        $post = array(
          "id_users"            => $pst['id_users'],
          "id_store_region"     => $pst['id_store_region'],
          "destination"         => $pst['destination'],
          "airline"             => $pst['airline'],
          "hotel"               => $pst['hotel'],
          "stars"               => $pst['stars'],
          "date_limit"          => $pst['date_limit'],
          "time_limit"          => $pst['time_limit'],
          "note"                => $pst['note'],
          "update_by_users"     => $users[0]->id_users,
        );
        $data = $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $tour_fit_request[0]->id_tour_fit_request), $post);
        if($data){
          $log_note = ""
            . "<ul>"
              . "<li>Destination : {$pst['destination']}</li>"
              . "<li>Airline : {$pst['airline']}</li>"
              . "<li>Hotel : {$pst['hotel']} * {$pst['stars']}</li>"
              . "<li>Note : ".number_format($pst['note'])."</li>"
            . "</ul>"
            . "";
          $this->mjson_tour_fit->log_fit_request($tour_fit_request[0]->id_tour_fit_request,$pst['id_users'],$log_note, "Create Quotation");
          $kirim = array(
            'status'  => 2,
            'id'      => $tour_fit_request[0]->id_tour_fit_request,
            'users'   => $tour_fit_request[0]->id_users,
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
          'note'    => 'Code tidak diketahui'
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
  
  function set_tour_fit_price_quotation(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $post = array(
          "adult_triple_twin"   => $pst['adult_triple_twin'],
          "adult_sgl_supp"      => $pst['adult_sgl_supp'],
          "child_twin_bed"      => $pst['child_twin_bed'],
          "child_extra_bed"     => $pst['child_extra_bed'],
          "child_no_bed"        => $pst['child_no_bed'],
          "adult_fare"          => $pst['adult_fare'],
          "child_fare"          => $pst['child_fare'],
          "infant_fare"         => $pst['infant_fare'],
          "update_by_users"     => $users[0]->id_users,
        );
        $data = $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $id_tour_fit_request), $post);
        $this->global_models->update("tour_fit_request_price_tag", array("id_tour_fit_request" => $id_tour_fit_request), array("pilih" => 0));
        $this->global_models->update("tour_fit_request_price_tag", array("id_tour_fit_request" => $id_tour_fit_request, "sort" => $pst['bracket']), array("pilih" => 2));
        if($data){
          $log_note = ""
            . "<ul>"
              . "<li>Adult Triple/Twin : {$pst['adult_triple_twin']}</li>"
              . "<li>Adult Single SUPP : {$pst['adult_sgl_supp']}</li>"
              . "<li>Child Twin Bed : {$pst['child_twin_bed']}</li>"
              . "<li>Child Extra Bed : {$pst['child_extra_bed']}</li>"
              . "<li>Child No Bed : {$pst['child_no_bed']}</li>"
              . "<li>Adult Fare : {$pst['adult_fare']}</li>"
              . "<li>Child Fare : {$pst['child_fare']}</li>"
              . "<li>Infant Fare : {$pst['infant_fare']}</li>"
              . "<li>Bracket : {$pst['bracket']}</li>"
            . "</ul>"
            . "";
          $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note, "Create Quotation");
          $kirim = array(
            'status'  => 2,
            'id'      => $id_tour_fit_request,
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
          'note'    => 'Code tidak diketahui'
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
  
  function set_tour_fit_request_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $itinerary  = json_decode($pst['itinerary']);
      $entrance   = json_decode($pst['entrance']);
      $meal       = json_decode($pst['meal']);
      $specific   = json_decode($pst['specific']);
      
      foreach($itinerary AS $key => $itin){
        $post[] = array(
          "id_tour_fit_request"     => $pst['id_tour_fit_request'],
          "id_users"                => $pst['id_users'],
          "itinerary"               => $itin,
          "sort"                    => ($key + 1),
          "entrance"                => str_replace(",", "", str_replace("Rp ", "", $entrance[$key])),
          "meal"                    => $meal[$key],
          "specific"                => $specific[$key],
          "type"                    => 1,
          "create_by_users"         => $users[0]->id_users,
          "create_date"             => date("Y-m-d H:i:s"),
        );
        $post[] = array(
          "id_tour_fit_request"     => $pst['id_tour_fit_request'],
          "id_users"                => $pst['id_users'],
          "itinerary"               => $itin,
          "sort"                    => ($key + 1),
          "entrance"                => str_replace(",", "", str_replace("Rp ", "", $entrance[$key])),
          "meal"                    => $meal[$key],
          "specific"                => $specific[$key],
          "type"                    => 2,
          "create_by_users"         => $users[0]->id_users,
          "create_date"             => date("Y-m-d H:i:s"),
        );
        $meal_code = array(
          0 => "<span class='label label-default'>None</span>",
          1 => "<span class='label label-success'>FB</span>",
          2 => "<span class='label label-info'>HB</span>",
        );
        $log_note = ""
          . "<ul>"
            . "<li>Days : {$key}</li>"
            . "<li>Streches : {$itin}</li>"
            . "<li>Meal Plan : {$meal_code[$meal[$key]]}</li>"
            . "<li>Entrance Fee : {$entrance[$key]}</li>"
            . "<li>Specific : {$specific[$key]}</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($pst['id_tour_fit_request'],$pst['id_users'],$log_note,"Add Itin");
      }
      if($post){
        $this->global_models->insert_batch("tour_fit_request_detail", $post);
        $kirim = array(
          'status'  => 2,
          'id'      => $id_tour_fit_request,
          'note'    => 'Berhasil'
        );
      }
      else{
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
  
  function set_tour_fit_request_pax(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $title = array(
          1 => "Mr",
          2 => "Mrs",
        );
        $type = array(
          1 => "Adult",
          2 => "Child",
          3 => "Infant",
        );
        if($pst['id']){
          $tour_fit_request_pax = $this->global_models->get("tour_fit_request_pax", array("id_tour_fit_request_pax" => $pst['id']));
          
          $post = array(
            "id_users"              => $pst['id_users'],
            "id_tour_fit_request"   => $id_tour_fit_request,
            "first_name"            => $pst['first_name'],
            "last_name"             => $pst['last_name'],
            "email"                 => $pst['email'],
            "telp"                  => $pst['telp'],
            "ticket"                => $pst['ticket'],
            "title"                 => $pst['title'],
            "type"                  => $pst['type'],
            "bed_type"              => $pst['bed_type'],
            "tempat_lahir"          => $pst['tempat_lahir'],
            "tanggal_lahir"         => $pst['tanggal_lahir'],
            "passport"              => $pst['passport'],
            "tempat_passport"       => $pst['tempat_passport'],
            "tanggal_passport"      => $pst['tanggal_passport'],
            "expired_passport"      => $pst['expired_passport'],
            "note"                  => $pst['note'],
            "update_by_users"       => $users[0]->id_users,
          );
          $id_tour_fit_request_pax = $this->global_models->update("tour_fit_request_pax", array("id_tour_fit_request_pax" => $pst['id']), $post);
          
          $log_note = ""
            . "<ul>"
              . "<li>Name : ".$title[$tour_fit_request_pax[0]->title]." {$tour_fit_request_pax[0]->first_name} {$tour_fit_request_pax[0]->last_name} (".$type[$tour_fit_request_pax[0]->type].") -> ".$title[$pst['title']]." {$pst['first_name']} {$pst['last_name']} (".$type[$pst['type']].")</li>"
              . "<li>Email : {$tour_fit_request_pax[0]->email} -> {$pst['email']}</li>"
              . "<li>Telp : {$tour_fit_request_pax[0]->telp} -> {$pst['telp']}</li>"
              . "<li>Kelahiran : {$tour_fit_request_pax[0]->tempat_lahir}, {$tour_fit_request_pax[0]->tanggal_lahir} -> {$pst['tempat_lahir']}, {$pst['tanggal_lahir']}</li>"
              . "<li>Passport : {$tour_fit_request_pax[0]->passport} -> {$pst['passport']}</li>"
              . "<li>Place of Issued : {$tour_fit_request_pax[0]->tempat_passport} -> {$pst['tempat_passport']}</li>"
              . "<li>Validity : {$tour_fit_request_pax[0]->tanggal_passport} - {$tour_fit_request_pax[0]->expired_passport} -> {$pst['tanggal_passport']} - {$pst['expired_passport']}</li>"
              . "<li>Note : {$tour_fit_request_pax[0]->note} -> {$pst['note']}</li>"
            . "</ul>"
            . "";
          $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note,"Update Pax");
          
          $kirim = array(
            'status'  => 4,
            'id'      => $pst['id'],
            'note'    => 'insert'
          );
        }
        else{
          $post = array(
            "id_users"              => $pst['id_users'],
            "id_tour_fit_request"   => $id_tour_fit_request,
            "first_name"            => $pst['first_name'],
            "last_name"             => $pst['last_name'],
            "email"                 => $pst['email'],
            "telp"                  => $pst['telp'],
            "ticket"                => $pst['ticket'],
            "title"                 => $pst['title'],
            "type"                  => $pst['type'],
            "bed_type"              => $pst['bed_type'],
            "tempat_lahir"          => $pst['tempat_lahir'],
            "tanggal_lahir"         => $pst['tanggal_lahir'],
            "passport"              => $pst['passport'],
            "tempat_passport"       => $pst['tempat_passport'],
            "tanggal_passport"      => $pst['tanggal_passport'],
            "expired_passport"      => $pst['expired_passport'],
            "note"                  => $pst['note'],
            "create_by_users"       => $users[0]->id_users,
            "create_date"           => date("Y-m-d H:i:s"),
          );
          $id_tour_fit_request_pax = $this->global_models->insert("tour_fit_request_pax", $post);
          $log_note = ""
          . "<ul>"
            . "<li>Name : ".$title[$pst['title']]." {$pst['first_name']} {$pst['last_name']} (".$type[$pst['type']].")</li>"
            . "<li>Email : {$pst['email']}</li>"
            . "<li>Telp : {$pst['telp']}</li>"
            . "<li>Kelahiran : {$pst['tempat_lahir']}, {$pst['tanggal_lahir']}</li>"
            . "<li>Passport : {$pst['passport']}</li>"
            . "<li>Place of Issued : {$pst['tempat_passport']}</li>"
            . "<li>Validity : {$pst['tanggal_passport']} - {$pst['expired_passport']}</li>"
            . "<li>Note : {$pst['note']}</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note,"Insert Pax");
          $kirim = array(
            'status'  => 2,
            'id'      => $id_tour_fit_request_pax,
            'note'    => 'insert'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function set_tour_fit_request_payment(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $kode = "";
        $this->olah_code($kode, "tour_fit_request_price");
        $post = array(
          "id_users"              => $pst['id_users'],
          "id_tour_fit_request"   => $id_tour_fit_request,
          "kode"                  => $kode,
          "nomor"                 => $pst['nomor'],
          "nomor_ttu"             => $pst['nomor_ttu'],
          "rekening"              => $pst['rekening'],
          "type"                  => 6,
          "title"                 => "Payment",
          "price"                 => $pst['price'],
          "qty"                   => 1,
          "total"                 => $pst['price'],
          "tanggal"               => date("Y-m-d H:i:s"),
          "pos"                   => 2,
          "status"                => 1,
          "note"                  => $pst['note'],
          "create_by_users"       => $users[0]->id_users,
          "create_date"           => date("Y-m-d H:i:s"),
        );
        $id_tour_fit_request_price = $this->global_models->insert("tour_fit_request_price", $post);
        $rekening = array(
          1 => "Cash", 
          2 => "BCA",
          3 => "Mega", 
          4 => "Kartu Kredit Mega", 
          5 => "Kartu Kredit Mega Priority", 
          6 => "Kartu Kredit", 
          7 => "Mandiri", 
          8 => "BNI", 
          9 => "CIMB");
        $log_note = ""
          . "<ul>"
            . "<li>Code : {$kode}</li>"
            . "<li>Title : {$jml_pax} Pax</li>"
            . "<li>Nomor : {$pst['nomor']} / {$pst['nomor_ttu']}</li>"
            . "<li>Rekening : {$rekening[$pst['rekening']]}</li>"
            . "<li>Price : ".number_format($pst['price'])."</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note, "Create Payment");
        
        $kirim = array(
          'status'  => 2,
          'id'      => $id_tour_fit_request_price,
          'note'    => 'insert'
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function set_tour_fit_request_price(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $kode = "";
        $this->olah_code($kode, "tour_fit_request_price");
        $post = array(
          "id_users"              => $pst['id_users'],
          "id_tour_fit_request"   => $id_tour_fit_request,
          "kode"                  => $kode,
          "type"                  => $pst['type'],
          "title"                 => $pst['title'],
          "price"                 => $pst['price'],
          "qty"                   => $pst['qty'],
          "total"                 => ($pst['price'] * $pst['qty']),
          "tanggal"               => date("Y-m-d H:i:s"),
          "pos"                   => $pst['pos'],
          "status"                => 1,
          "note"                  => $pst['note'],
          "create_by_users"       => $users[0]->id_users,
          "create_date"           => date("Y-m-d H:i:s"),
        );
        $id_tour_fit_request_price = $this->global_models->insert("tour_fit_request_price", $post);
        $pos = array(
          1 => "Penambahan Biaya",
          2 => "Pengurangan Biaya",
        );
        $type = array(
          2 => "Additional",
          3 => "PPN",
          4 => "Visa",
          5 => "Discount",
        );
        $log_note = ""
          . "<ul>"
            . "<li>Code : {$kode}</li>"
            . "<li>Title : {$pst['title']}</li>"
            . "<li>Price : ".number_format($pst['price'])." * ".number_format($pst['qty'])." = ".number_format(($pst['price']*$pst['qty']))."</li>"
            . "<li>Type : {$type[$pst['type']]}</li>"
            . "<li>Pos : {$pos[$pst['pos']]}</li>"
            . "<li>Note : {$pst['note']}</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note, "Create Price Tag");
        
        $kirim = array(
          'status'  => 2,
          'id'      => $id_tour_fit_request_price,
          'note'    => 'insert'
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function set_timelimit_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $post = array(
          "date_limit"            => $pst['date_limit'],
          "time_limit"            => $pst['time_limit'],
          "status"                => $pst['status'],
        );
        $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $id_tour_fit_request), $post);
        
        $log_note = ""
          . "Set Timelimit {$pst['date_limit']} {$pst['time_limit']}"
          . "";
        $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note,"Set Timelimit");
        
        $kirim = array(
          'status'  => 2,
          'note'    => ''
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function set_tour_fit_request_price_tag(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_fit_request = $this->global_models->get("tour_fit_request",array("kode" => $pst['code']));
      if($tour_fit_request){
        if($pst['id_tour_fit_request_price_tag']){
          $post = array(
            "id_users"                => $pst['id_users'],
            "id_store_region"         => $pst['id_store_region'],
            "title"                   => $pst['title'],
            "sort"                    => $pst['sort'],
            "adult_triple_twin"       => $pst['adult_triple_twin'],
            "adult_triple_twin_sell"  => $pst['adult_triple_twin_sell'],
            "adult_sgl_supp"          => $pst['adult_sgl_supp'],
            "adult_sgl_supp_sell"     => $pst['adult_sgl_supp_sell'],	
            "child_twin_bed"          => $pst['child_twin_bed'],
            "child_twin_bed_sell"     => $pst['child_twin_bed_sell'],
            "child_extra_bed"         => $pst['child_extra_bed'],
            "child_extra_bed_sell"    => $pst['child_extra_bed_sell'],
            "child_no_bed"            => $pst['child_no_bed'],
            "child_no_bed_sell"       => $pst['child_no_bed_sell'],
            "adult_fare"              => $pst['adult_fare'],
            "adult_fare_sell"         => $pst['adult_fare_sell'],
            "child_fare"              => $pst['child_fare'],
            "child_fare_sell"         => $pst['child_fare_sell'],
            "infant_fare"             => $pst['infant_fare'],
            "infant_fare_sell"        => $pst['infant_fare_sell'],
            "update_by_users"         => $users[0]->id_users,
          );
          $this->global_models->update("tour_fit_request_price_tag", array("id_tour_fit_request_price_tag" => $pst['id_tour_fit_request_price_tag']), $post);
          $id_tour_fit_request_price_tag = $pst['id_tour_fit_request_price_tag'];
          $update = 2;
        }
        else{
          $post = array(
            "id_users"                => $pst['id_users'],
            "id_tour_fit_request"     => $tour_fit_request[0]->id_tour_fit_request,
            "id_store_region"         => $pst['id_store_region'],
            "title"                   => $pst['title'],
            "sort"                    => $pst['sort'],
            "adult_triple_twin"       => $pst['adult_triple_twin'],
            "adult_triple_twin_sell"  => $pst['adult_triple_twin_sell'],
            "adult_sgl_supp"          => $pst['adult_sgl_supp'],
            "adult_sgl_supp_sell"     => $pst['adult_sgl_supp_sell'],	
            "child_twin_bed"          => $pst['child_twin_bed'],
            "child_twin_bed_sell"     => $pst['child_twin_bed_sell'],
            "child_extra_bed"         => $pst['child_extra_bed'],
            "child_extra_bed_sell"    => $pst['child_extra_bed_sell'],
            "child_no_bed"            => $pst['child_no_bed'],
            "child_no_bed_sell"       => $pst['child_no_bed_sell'],
            "adult_fare"              => $pst['adult_fare'],
            "adult_fare_sell"         => $pst['adult_fare_sell'],
            "child_fare"              => $pst['child_fare'],
            "child_fare_sell"         => $pst['child_fare_sell'],
            "infant_fare"             => $pst['infant_fare'],
            "infant_fare_sell"        => $pst['infant_fare_sell'],
            "create_by_users"         => $users[0]->id_users,
            "create_date"             => date("Y-m-d H:i:s"),
          );
          $id_tour_fit_request_price_tag = $this->global_models->insert("tour_fit_request_price_tag", $post);
        }
        $log_note = ""
          . "<ul>"
            . "<li>Title : {$pst['title']}</li>"
            . "<li>Adult Triple/Twin : ".number_format($pst['adult_triple_twin'])." || ".number_format($pst['adult_triple_twin_sell'])."</li>"
            . "<li>Adult Sgl SUPP : ".number_format($pst['adult_sgl_supp'])." || ".number_format($pst['adult_sgl_supp_sell'])."</li>"
            . "<li>Child Triple Bed : ".number_format($pst['child_twin_bed'])." || ".number_format($pst['child_twin_bed_sell'])."</li>"
            . "<li>Child Extra Bed : ".number_format($pst['child_extra_bed'])." || ".number_format($pst['child_extra_bed_sell'])."</li>"
            . "<li>Child No Bed : ".number_format($pst['child_no_bed'])." || ".number_format($pst['child_no_bed_sell'])."</li>"
            . "<li>Adult Fare : ".number_format($pst['adult_fare'])." || ".number_format($pst['adult_fare_sell'])."</li>"
            . "<li>Child Fare : ".number_format($pst['child_fare'])." || ".number_format($pst['child_fare_sell'])."</li>"
            . "<li>Infant Fare : ".number_format($pst['infant_fare'])." || ".number_format($pst['infant_fare_sell'])."</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($tour_fit_request[0]->id_tour_fit_request,$pst['id_users'],$log_note, "Create Price Tag");
        
        $kirim = array(
          'status'  => 2,
          'id'      => $id_tour_fit_request_price_tag,
          'update'  => $update,
          'users'   => $tour_fit_request[0]->id_users,
          'note'    => 'insert'
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function set_tour_fit_request_toc(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tour_fit_request = $this->global_models->get("tour_fit_request",array("kode" => $pst['code']));
      if($tour_fit_request){
        if($pst['toc']){
          $post = array(
            "toc"                => $pst['toc'],
          );
          $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $tour_fit_request[0]->id_tour_fit_request), $post);
          $kirim = array(
            'status'  => 2,
            'note'    => ""
          );
        }
        else{
          $kirim = array(
            'status'  => 5,
            'note'    => 'ToC Not Set'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      if($pst['code']){
        $where .= " AND A.kode LIKE '%{$pst['code']}%'";
      }
      
      if($pst['tanggal_start']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['tanggal_start']}' AND '{$pst['tanggal_end']}')";
      }
      
      if($pst['departure_start']){
        $where .= " AND (A.departure BETWEEN '{$pst['departure_start']}' AND '{$pst['departure_end']}')";
      }
      
      if($pst['title']){
        $where .= " AND A.title LIKE '%{$pst['title']}%'";
      }
      
      if($pst['code']){
        $where .= " AND A.kode LIKE '%{$pst['code']}%'";
      }
      
      if($pst['client']){
        $where .= " AND A.client LIKE '%{$pst['client']}%'";
      }
      
      if($pst['status']){
        $where .= " AND A.status = '{$pst['status']}'";
      }
      
      if($pst['destination']){
        $where .= " AND A.destination LIKE '%{$pst['destination']}%'";
      }
      
      if($pst['id_store']){
        $where .= " AND A.id_store = '{$pst['id_store']}'";
      }
      
      $data = $this->global_models->get_query("SELECT A.*"
        . " ,count(B.id_tour_fit_request) AS days"
        . " ,C.name"
        . " FROM tour_fit_request AS A"
        . " LEFT JOIN tour_fit_request_detail AS B ON A.id_tour_fit_request = B.id_tour_fit_request"
        . " LEFT JOIN users_channel AS C ON A.id_users = C.id_users"
        . " WHERE B.type = 1"
        . " {$where}"
        . " GROUP BY A.id_tour_fit_request"
        . " ORDER BY A.tanggal ASC"
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
  
  function get_chart_tour_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['date_start']){
        $where .= " AND (A.tanggal BETWEEN '{$pst['date_start']}' AND '{$pst['date_end']}')";
      }
      if($pst['status']){
        $where .= " AND A.status = '{$pst['status']}'";
      }
      
      $data = $this->global_models->get_query("SELECT A.tanggal, A.title, A.status, A.id_store"
        . " ,(SELECT B.title FROM store AS B WHERE B.id_store = A.id_store) AS store"
        . " ,(SELECT COUNT(C.id_tour_fit_request_pax) FROM tour_fit_request_pax AS C WHERE C.id_tour_fit_request = A.id_tour_fit_request) AS pax"
        . " ,(SELECT SUM(D.total) FROM tour_fit_request_price AS D WHERE D.pos = 1 AND D.id_tour_fit_request = A.id_tour_fit_request AND D.status = 1) AS debit"
        . " ,(SELECT SUM(E.total) FROM tour_fit_request_price AS E WHERE E.pos = 2 AND E.id_tour_fit_request = A.id_tour_fit_request AND E.status = 1) AS kredit"
        . " FROM tour_fit_request AS A"
        . " WHERE 1 = 1"
        . " {$where}"
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
  
  function get_tour_fit_quotation(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $data = $this->global_models->get("tour_fit_quotation", array("id_tour_fit_request" => $id_tour_fit_request));
        $kirim = array(
          'status'  => 2,
          'data'    => $data,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $where = "WHERE A.id_tour_fit_request = '{$id_tour_fit_request}'";
        if($pst['type']){
          if($pst['type'] == 23 ){
            $where .= " AND (A.type = '2' OR A.type = '3')";
          }
          else{
            $where .= " AND A.type = '{$pst['type']}'";
          }
        }
        else{
          $where .= " AND A.type = '1'";
        }
        $detail = $this->global_models->get_query("SELECT A.*"
          . " FROM tour_fit_request_detail AS A"
          . " {$where}"
          . " ORDER BY A.sort ASC");
        if($detail){
          $kirim = array(
            'status'  => 2,
            'data'    => $detail,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request_pax(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $detail = $this->global_models->get_query("SELECT A.*"
          . " FROM tour_fit_request_pax AS A"
          . " WHERE A.id_tour_fit_request = '{$id_tour_fit_request}'"
          . "");
        if($detail){
          $kirim = array(
            'status'  => 2,
            'data'    => $detail,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request_price(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $detail = $this->global_models->get_query("SELECT A.*"
          . " FROM tour_fit_request_price AS A"
          . " WHERE A.id_tour_fit_request = '{$id_tour_fit_request}'"
          . " AND A.status = 1"
          . "");
        if($detail){
          $kirim = array(
            'status'  => 2,
            'data'    => $detail,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request_hpp(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $detail = $this->global_models->get_query("SELECT A.*"
          . " FROM tour_fit_request_price_hpp AS A"
          . " WHERE A.id_tour_fit_request = '{$id_tour_fit_request}'"
          . " AND A.status = 1"
          . "");
        if($detail){
          $kirim = array(
            'status'  => 2,
            'data'    => $detail,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request_price_tag(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $detail = $this->global_models->get_query("SELECT A.*"
          . " FROM tour_fit_request_price_tag AS A"
          . " WHERE A.id_tour_fit_request = '{$id_tour_fit_request}'"
          . " ORDER BY A.sort ASC");
        if($detail){
          $kirim = array(
            'status'  => 2,
            'data'    => $detail,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request_log(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $detail = $this->global_models->get_query("SELECT A.*"
          . " ,B.name"
          . " FROM log_fit_request AS A"
          . " LEFT JOIN users_channel AS B ON A.id_users = B.id_users"
          . " WHERE A.id_tour_fit_request = '{$id_tour_fit_request}'"
          . " ORDER BY A.tanggal DESC"
          . "");
        if($detail){
          $kirim = array(
            'status'  => 2,
            'data'    => $detail,
            'note'    => 'Berhasil'
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Gagal'
          );
        }
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function generate_tour_fit_request_status(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code'], "status >" => 2));
      if($id_tour_fit_request){
        $price = $this->global_models->get_query("SELECT SUM(CASE WHEN pos = 1 AND status = 1 THEN total ELSE 0 END) AS debit"
          . ",SUM(CASE WHEN pos = 2 AND status = 1 AND type = 6 THEN total ELSE 0 END) AS bayar"
          . ",SUM(CASE WHEN pos = 2 AND status = 1 THEN total ELSE 0 END) AS kredit"
          . " FROM tour_fit_request_price"
          . " WHERE id_tour_fit_request = '{$id_tour_fit_request}'"
          . "");
        if($price[0]->bayar > 0){
          $sisa = $price[0]->debit - $price[0]->kredit;
          if($sisa > 0){
            $status = 4;
          }
          else{
            $status = 6;
          }
        }
        else{
          $status = 3;
        }
        
        $status_awal = $this->global_models->get_field("tour_fit_quotation", "status", array("id_tour_fit_request" => $id_tour_fit_request));
        if($status_awal == 7 OR $status_awal == 8 OR $status_awal == 9){
          $status = $status_awal;
        }
        
        $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $id_tour_fit_request), array("status" => $status));
        $this->global_models->update("tour_fit_request", array("id_tour_fit_request" => $id_tour_fit_request), array("status" => $status));
        $kirim = array(
          'status'  => 2,
          'data'    => $status,
          'note'    => 'Berhasil'
        );
      }
      else {
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_tour_fit_request_pax_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $data = $this->global_models->get("tour_fit_request_pax", array("id_tour_fit_request_pax" => $pst['id']));
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
  
  function get_tour_fit_request_detail_items(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $detail = $this->global_models->get("tour_fit_request_detail", array("id_tour_fit_request_detail" => $pst['id_tour_fit_request_detail']));
      if($detail){
        $kirim = array(
          'status'  => 2,
          'data'    => $detail[0],
          'note'    => 'Berhasil'
        );
      }
      else{
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
  
  function update_tour_fit_request_detail_items(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id_tour_fit_request_detail']){
        $old = $this->global_models->get("tour_fit_request_detail", array("id_tour_fit_request_detail" => $pst['id_tour_fit_request_detail']));
        $post = array(
          "id_tour_fit_request"       => $pst['id_tour_fit_request'],
          "id_users"                  => $pst['id_users'],
          "itinerary"                 => $pst['itinerary'],
          "sort"                      => $pst['sort'],
          "entrance"                  => $pst['entrance'],
          "meal"                      => $pst['meal'],
          "specific"                  => $pst['specefic'],
          "type"                      => $pst['type'],
          "note"                      => $pst['note'],
          "update_by_users"           => $users[0]->id_users
        );
        $hasil = $this->global_models->update("tour_fit_request_detail", array("id_tour_fit_request_detail" => $pst['id_tour_fit_request_detail']), $post);
        $meal = array(
          0 => "<span class='label label-default'>None</span>",
          1 => "<span class='label label-success'>FB</span>",
          2 => "<span class='label label-info'>HB</span>",
        );
        $log_note = ""
          . "<ul>"
            . "<li>Days : {$old[0]->sort} -> {$pst['sort']}</li>"
            . "<li>Streches : {$old[0]->itinerary} -> {$pst['itinerary']}</li>"
            . "<li>Meal Plan : {$meal[$old[0]->meal]} -> {$meal[$pst['meal']]}</li>"
            . "<li>Entrance Fee : ".number_format($old[0]->entrance)." -> ".number_format($pst['entrance'])."</li>"
            . "<li>Specific : {$old[0]->specefic} -> {$pst['specefic']}</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($pst['id_tour_fit_request'],$pst['id_users'],$log_note,"Change Itin");
//        $this->global_models->update("tour_fit_request", array("id_tour_fit_request" => $pst['id_tour_fit_request']), array("status" => 2));
//        $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $pst['id_tour_fit_request']), array("status" => 2));
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
        );
      }
      else{
        $post = array(
          "id_tour_fit_request"       => $pst['id_tour_fit_request'],
          "id_users"                  => $pst['id_users'],
          "itinerary"                 => $pst['itinerary'],
          "sort"                      => $pst['sort'],
          "entrance"                  => $pst['entrance'],
          "meal"                      => $pst['meal'],
          "specific"                  => $pst['specefic'],
          "type"                      => $pst['type'],
          "note"                      => $pst['note'],
          "create_by_users"           => $users[0]->id_users,
          "create_date"               => date("Y-m-d H:i:s"),
        );
        $hasil = $this->global_models->insert("tour_fit_request_detail", $post);
        $log_note = ""
          . "<ul>"
            . "<li>Days : {$pst['sort']}</li>"
            . "<li>Streches : {$pst['itinerary']}</li>"
            . "<li>Meal Plan : {$meal[$pst['meal']]}</li>"
            . "<li>Entrance Fee : ".number_format($pst['entrance'])."</li>"
            . "<li>Specific : {$pst['specefic']}</li>"
          . "</ul>"
          . "";
        $this->mjson_tour_fit->log_fit_request($pst['id_tour_fit_request'],$pst['id_users'],$log_note,"Add Itin");
        $this->global_models->update("tour_fit_request", array("id_tour_fit_request" => $pst['id_tour_fit_request']), array("status" => 2));
        $this->global_models->update("tour_fit_quotation", array("id_tour_fit_request" => $pst['id_tour_fit_request']), array("status" => 2));
        $kirim = array(
          'status'  => 2,
          'note'    => 'Berhasil'
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
  
  function set_contact_person_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $tour_fit_contact_person = $this->global_models->get("tour_fit_contact_person", array("id_tour_fit_request" => $id_tour_fit_request));
        if($tour_fit_contact_person){
          $post = array(
            "id_users"                  => $pst['id_users'],
            "name"                      => $pst['name'],
            "email"                     => $pst['email'],
            "telp"                      => $pst['telp'],
            "alamat"                    => $pst['alamat'],
            "note"                      => $pst['note'],
            "update_by_users"           => $users[0]->id_users
          );
          $hasil = $this->global_models->update("tour_fit_contact_person", array("id_tour_fit_contact_person" => $tour_fit_contact_person[0]->id_tour_fit_contact_person), $post);
          
          $log_note = ""
            . "<ul>"
              . "<li>Name : {$tour_fit_contact_person[0]->name} -> {$pst['name']}</li>"
              . "<li>Email : {$tour_fit_contact_person[0]->email} -> {$pst['email']}</li>"
              . "<li>Telp : {$tour_fit_contact_person[0]->telp} -> {$pst['telp']}</li>"
              . "<li>Alamat : {$tour_fit_contact_person[0]->alamat} -> {$pst['alamat']}</li>"
              . "<li>Note : {$tour_fit_contact_person[0]->note} -> {$pst['note']}</li>"
            . "</ul>"
            . "";
          $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note,"Update Contact Person");
          
          $kirim = array(
            'status'  => 2,
            'note'    => 'Update'
          );
        }
        else{
          $post = array(
            "id_users"                  => $pst['id_users'],
            "id_tour_fit_request"       => $id_tour_fit_request,
            "name"                      => $pst['name'],
            "email"                     => $pst['email'],
            "telp"                      => $pst['telp'],
            "alamat"                    => $pst['alamat'],
            "note"                      => $pst['note'],
            "create_by_users"           => $users[0]->id_users,
            "create_date"               => date("Y-m-d H:i:s")
          );
          $hasil = $this->global_models->insert("tour_fit_contact_person", $post);
          $log_note = ""
            . "<ul>"
              . "<li>Name : {$pst['name']}</li>"
              . "<li>Email : {$pst['email']}</li>"
              . "<li>Telp : {$pst['telp']}</li>"
              . "<li>Alamat : {$pst['alamat']}</li>"
              . "<li>Note : {$pst['note']}</li>"
            . "</ul>"
            . "";
          $this->mjson_tour_fit->log_fit_request($id_tour_fit_request,$pst['id_users'],$log_note,"Create Contact Person");
          $kirim = array(
            'status'  => 4,
            'note'    => 'Insert'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
  function get_contact_person_fit_request(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $id_tour_fit_request = $this->global_models->get_field("tour_fit_request", "id_tour_fit_request", array("kode" => $pst['code']));
      if($id_tour_fit_request){
        $data = $this->global_models->get("tour_fit_contact_person", array("id_tour_fit_request" => $id_tour_fit_request));
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
            'note'    => 'Data kosong'
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Code tidak diketahui'
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
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */