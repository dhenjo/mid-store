<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_tour_umum extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  private $users  = "antavaya";
  private $pass   = "antavaya!#%*";


  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   * @param string $q Keyword
   */
  function tour_series_destination_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $items = $this->global_models->get_query("
        SELECT destination
        FROM product_tour
        WHERE 
        LOWER(destination) LIKE '%".strtolower($pst['q'])."%'
        LIMIT 0,20
        ");
      if($items){
        foreach($items as $tms){
          if($tms->destination){
            $strip = explode("-",$tms->destination);
            foreach ($strip AS $st){
              if($st){
                $koma = explode(",",trim($st));
                foreach ($koma AS $km){
                  if($km){
                    $aneh = explode("–",trim($km));
                    foreach ($aneh AS $nh){
                      if($nh AND !  in_array(trim(strtolower($nh)), $block)){
                        $kirim[] = array(
                          "id"    => trim(strtolower($nh)),
                          "label" => trim(strtolower($nh)),
                          "value" => trim(strtolower($nh)),
                        );
                        $block[] = trim(strtolower($nh));
                      }
                    }
                    
                  }
                }
              }
            }
          }
        }
      }
      else{
        $kirim[] = array(
            "id"    => 0,
            "label" => "No Found",
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
    print json_encode($kirim);
    die;
  }
  
  function tour_series_get(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['start_date']){
        $where .= " AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$pst['end_date']}')";
      }
      
      if($pst['destination']){
        $where .= " AND (LOWER(A.title) LIKE '%".strtolower($pst['destination'])."%' OR LOWER(A.destination) LIKE '%".strtolower($pst['destination'])."%' OR LOWER(A.landmark) LIKE '%".strtolower($pst['destination'])."%')";
      }
      
      if($pst['region']){
        $where .= " AND A.sub_category = '{$pst['region']}'";
      }
      
      if($pst['urut']){
        if($pst['urut'] == 1){
          $order = "ORDER BY B.adult_triple_twin ASC";
        }
        else if($pst['urut'] == 2){
          $order = "ORDER BY B.adult_triple_twin DESC";
        }
        else if($pst['urut'] == 3){
          $order = "ORDER BY B.start_date ASC";
        }
        else if($pst['urut'] == 4){
          $order = "ORDER BY B.start_date DESC";
        }
        else if($pst['urut'] == 5){
          $order = "ORDER BY A.title ASC";
        }
        else if($pst['urut'] == 6){
          $order = "ORDER BY A.title DESC";
        }
      }
      else{
        $order = "ORDER BY B.adult_triple_twin ASC";
      }
      
      $items = $this->global_models->get_query("SELECT A.title, A.days, A.night, A.destination, A.id_product_tour, A.file_thumb"
        . " ,B.id_product_tour_information, B.start_date, B.end_date, B.adult_triple_twin, B.kode"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE B.tampil = 1 AND (B.status = 1 OR B.status IS NULL OR B.status = 5)"
        . " AND B.start_date > '".date("Y-m-d")."'"
        . " AND A.status = 1"
        . " AND B.adult_triple_twin > 0"
        . " {$where}"
        . " GROUP BY A.id_product_tour"
        . " {$order}"
        . " LIMIT {$pst['start']}, {$pst['limit']}");
      $total = 0;
      foreach($items AS $tem){
        $total++;
        if($pst['start_date']){
          $tanggal .= " AND (B.start_date BETWEEN '{$pst['start_date']}' AND '{$pst['end_date']}')";
        }
        $schedule = $this->global_models->get_query("SELECT B.start_date, B.kode, B.adult_triple_twin"
          . " FROM product_tour_information AS B"
          . " WHERE B.id_product_tour = '{$tem->id_product_tour}'"
          . " AND B.start_date > '".date("Y-m-d")."'"
          . " {$tanggal}"
          . " ORDER BY B.start_date ASC"
          . " LIMIT 0,40");
        $data[$tem->id_product_tour] = array(
          "title"       => $tem->title,
          "days"        => $tem->days,
          "night"       => $tem->night,
          "destination" => $tem->destination,
          "harga"       => $tem->adult_triple_twin,
          "file_thumb"  => $tem->file_thumb,
          "schedule"    => $schedule,
        );
      }
      
      if($items){
        $kirim = array(
          'status'  => 2,
          'total'   => $total,
          'data'    => $data,
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Akses'
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

  function tour_series_get_top_8(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $items = $this->global_models->get_query("SELECT A.title, A.days, A.night, A.destination, A.id_product_tour, A.file_thumb, A.file"
        . " ,B.id_product_tour_information, B.start_date, B.end_date, B.adult_triple_twin, B.kode"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE B.tampil = 1"
        . " AND B.start_date > '".date("Y-m-d")."'"
        . " AND A.hot_deal = 1"
        . " AND A.status = 1"
        . " AND (A.file_thumb IS NOT NULL OR A.file_thumb <> '')"
        . " AND (B.adult_triple_twin > 0)"
        . " GROUP BY A.id_product_tour"
        . " ORDER BY RAND()"
        . " LIMIT 0, 8");
      
      if($items){
        $kirim = array(
          'status'  => 2,
          'data'    => $items
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Akses'
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

  function tour_series_get_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $items = $this->global_models->get_query("SELECT A.title, A.days, A.night, A.destination, A.landmark, A.sub_category, A.note, A.file, A.id_product_tour, A.toc"
        . " ,B.id_product_tour_information, B.start_date, B.end_date, B.keberangkatan, B.kode"
        . " ,B.adult_triple_twin, B.child_twin_bed, B.child_extra_bed, B.child_no_bed, B.sgl_supp, B.airport_tax, B.visa"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE B.kode = '{$pst['code']}'"
        . "");
      if($items){
        $sc = $this->global_models->get_query("SELECT A.start_date, A.kode"
          . " FROM product_tour_information AS A"
          . " WHERE A.id_product_tour = '{$items[0]->id_product_tour}'"
          . " AND A.start_date > '".date("Y-m-d")."'"
          . " AND A.adult_triple_twin > 0"
          . "");
        $kirim = array(
          'status'  => 2,
          'data'    => $items[0],
          'schedule'=> $sc,
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Tidak Ada Akses'
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