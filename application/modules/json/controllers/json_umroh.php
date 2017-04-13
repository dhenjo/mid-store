<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_umroh extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  private function generate_kode(&$kode, $table, $char){
    $this->load->helper('string');
    $kode_random = random_string('alnum', $char);
    $st_upper = strtoupper($kode_random);
    $kode = $st_upper;
    $cek = $this->global_models->get_field($table, "id_".$table, array("kode" => $kode));
    if($cek > 0){
      $this->olah_tour_code($kode, $table, $char);
    }
  }
  
  function set_master_umroh(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $this->debug($pst, true);
      if($pst['id_umroh_master']){
        if($pst['code'])
          $kode = $pst['code'];
        else
          $this->generate_kode($kode, "umroh_master", 6);
        
        $post = array(
          "title"                   => $pst['title'],
          "id_users"                => $pst['id_users'],
          "id_tour_keberangkatan"   => $pst['id_tour_keberangkatan'],
          "kode"                    => $kode,
          "status"                  => $pst['status'],
          "kategori"                => $pst['kategori'],
          "create_by_users"         => $users[0]->id_users,
          "create_date"             => date("Y-m-d H:i:s"),
          "destination"             => $pst['destination'],
          "sub_title"               => $pst['sub_title'],
          "detail"                  => $pst['detail'],
        );
        if($pst['file'])
          $post['file'] = $pst['file'];
        if($pst['thumb'])
          $post['thumb'] = $pst['thumb'];
        if($pst['promo'])
          $post['promo'] = $pst['promo'];
        $id_umroh_master = $this->global_models->update("umroh_master", array("id_umroh_master" => $pst['id_umroh_master']),$post);
        if($id_umroh_master){
          $kirim = array(
            'status'  => 4,
            'id'      => $pst['id_umroh_master'],
            'note'    => ''
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Data Gagal Simpan'
          );
        }
      }
      else{
        if($pst['code'])
          $kode = $pst['code'];
        else
          $this->generate_kode($kode, "umroh_master", 6);
        
        $post = array(
          "title"                   => $pst['title'],
          "id_users"                => $pst['id_users'],
          "id_tour_keberangkatan"   => $pst['id_tour_keberangkatan'],
          "kode"                    => $kode,
          "status"                  => $pst['status'],
          "kategori"                => $pst['kategori'],
          "create_by_users"         => $users[0]->id_users,
          "create_date"             => date("Y-m-d H:i:s"),
          "destination"             => $pst['destination'],
          "sub_title"               => $pst['sub_title'],
          "detail"                  => $pst['detail'],
        );
        if($pst['file'])
          $post['file'] = $pst['file'];
        if($pst['thumb'])
          $post['thumb'] = $pst['thumb'];
        $id_umroh_master = $this->global_models->insert("umroh_master", $post);
        if($id_umroh_master){
          $kirim = array(
            'status'  => 2,
            'id'      => $id_umroh_master,
            'note'    => ''
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Data Gagal Simpan'
          );
        }
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
  
  function set_master_umroh_schedule(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
//      $this->debug($pst, true);
      if($pst['id_detail']){
        $post = array(
          "id_users"                => $pst['id_users'],
          "id_umroh_master"         => $pst['id_umroh_master'],
          "status"                  => $pst['status'],
          "depart"                  => $pst['depart'],
          "arrive"                  => $pst['arrive'],
          "hotel"                   => $pst['hotel'],
          "double"                  => $pst['double'],
          "triple"                  => $pst['triple'],
          "quad"                    => $pst['quad'],
          "promo"                   => $pst['promo'],
        );
        $id_umroh_schedule = $this->global_models->update("umroh_schedule", array("id_umroh_schedule" => $pst['id_detail']), $post);
        if($id_umroh_schedule){
          $kirim = array(
            'status'  => 4,
            'id'      => $pst['id_umroh_master'],
            'note'    => ''
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Data Gagal Simpan'
          );
        }
      }
      else{
        if($pst['code'])
          $kode = $pst['code'];
        else
          $this->generate_kode($kode, "umroh_schedule", 8);
        
        $post = array(
          "id_users"                => $pst['id_users'],
          "id_umroh_master"         => $pst['id_umroh_master'],
          "kode"                    => $kode,
          "status"                  => $pst['status'],
          "depart"                  => $pst['depart'],
          "arrive"                  => $pst['arrive'],
          "hotel"                   => $pst['hotel'],
          "double"                  => $pst['double'],
          "triple"                  => $pst['triple'],
          "quad"                    => $pst['quad'],
          "promo"                   => $pst['promo'],
        );
        $id_umroh_schedule = $this->global_models->insert("umroh_schedule", $post);
        if($id_umroh_schedule){
          $kirim = array(
            'status'  => 2,
            'id'      => $id_umroh_schedule,
            'note'    => ''
          );
        }
        else{
          $kirim = array(
            'status'  => 3,
            'note'    => 'Data Gagal Simpan'
          );
        }
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
  
  function set_master_keberangkatan(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['title'] AND $pst['id_users']){
        if($pst["id"]){
          $kirim = array(
            "id_users"            => $pst['id_users'],
            "title"               => $pst['title'],
            "update_by_users"     => $pst['id_users'],
          );
          $id_tour_keberangkatan = $this->global_models->update("tour_keberangkatan", array("id_tour_keberangkatan" => $pst['id']), $kirim);
        }
        else{
          $kirim = array(
            "id_users"            => $pst['id_users'],
            "title"               => $pst['title'],
            "create_by_users"     => $pst['id_users'],
            "create_date"         => date("Y-m-d H:i:s"),
          );
          $id_tour_keberangkatan = $this->global_models->insert("tour_keberangkatan", $kirim);
        }
        if($id_tour_keberangkatan){
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
          'note'    => 'Terdapat Field yang Wajib Diisi'
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
  
  function set_master_store_region(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['title'] AND $pst['id_users']){
        if($pst["id"]){
          $kirim = array(
            "id_users"            => $pst['id_users'],
            "title"               => $pst['title'],
            "update_by_users"     => $pst['id_users'],
          );
          $id_store_region = $this->global_models->update("store_region", array("id_store_region" => $pst['id']), $kirim);
        }
        else{
          $kirim = array(
            "id_users"            => $pst['id_users'],
            "title"               => $pst['title'],
            "create_by_users"     => $pst['id_users'],
            "create_date"         => date("Y-m-d H:i:s"),
          );
          $id_store_region = $this->global_models->insert("store_region", $kirim);
        }
        if($id_store_region){
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
          'note'    => 'Terdapat Field yang Wajib Diisi'
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
  
  function set_master_store(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['title'] AND $pst['id_users']){
        if($pst["id"]){
          $kirim = array(
            "id_users"            => $pst['id_users'],
            "id_store_region"     => $pst['id_store_region'],
            "title"               => $pst['title'],
            "telp"                => $pst['telp'],
            "fax"                 => $pst['fax'],
            "master"              => $pst['master'],
            "alamat"              => $pst['alamat'],
            "update_by_users"     => $pst['id_users'],
          );
          $id_store = $this->global_models->update("store", array("id_store" => $pst['id']), $kirim);
        }
        else{
          $kirim = array(
            "id_users"            => $pst['id_users'],
            "id_store_region"     => $pst['id_store_region'],
            "title"               => $pst['title'],
            "telp"                => $pst['telp'],
            "fax"                 => $pst['fax'],
            "master"              => $pst['master'],
            "alamat"              => $pst['alamat'],
            "create_by_users"     => $pst['id_users'],
            "create_date"         => date("Y-m-d H:i:s"),
          );
          $id_store = $this->global_models->insert("store", $kirim);
        }
        if($id_store){
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
          'note'    => 'Terdapat Field yang Wajib Diisi'
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
  
  function get_master_keberangkatan(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id']){
        $list = $this->global_models->get("tour_keberangkatan", array("id_tour_keberangkatan" => $pst['id']));
        $kirim = array(
          'status'  => 4,
          'data'    => $list[0],
          'note'    => 'Berhasil'
        );
      }
      else{
        $list = $this->global_models->get("tour_keberangkatan");
        $kirim = array(
          'status'  => 2,
          'data'    => $list,
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
  
  function get_master_store_region(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id']){
        $list = $this->global_models->get("store_region", array("id_store_region" => $pst['id']));
        $kirim = array(
          'status'  => 4,
          'data'    => $list[0],
          'note'    => 'Berhasil'
        );
      }
      else{
        $list = $this->global_models->get("store_region");
        $kirim = array(
          'status'  => 2,
          'data'    => $list,
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
  
  function get_master_store(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id']){
        $list = $this->global_models->get_query("SELECT A.*, B.title AS region"
          . " FROM store AS A"
          . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region"
          . " WHERE A.id_store = '{$pst['id']}'");
        $kirim = array(
          'status'  => 4,
          'data'    => $list[0],
          'note'    => 'Berhasil'
        );
      }
      else{
        $list = $this->global_models->get_query("SELECT A.*, B.title AS region"
          . " FROM store AS A"
          . " LEFT JOIN store_region AS B ON A.id_store_region = B.id_store_region");
        $kirim = array(
          'status'  => 2,
          'data'    => $list,
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
  
  function get_master_umroh(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id']){
        $list = $this->global_models->get_query("SELECT A.*"
          . " FROM umroh_master AS A"
          . " WHERE A.id_umroh_master = '{$pst['id']}'"
          . "");
        $kirim = array(
          'status'  => 4,
          'data'    => $list,
          'note'    => 'Berhasil'
        );
      }
      else{
        $list = $this->global_models->get_query("SELECT A.*"
          . " FROM umroh_master AS A"
          . " ORDER BY A.title ASC LIMIT {$pst['start']},{$pst['limit']}"
          . "");
        $kirim = array(
          'status'  => 2,
          'data'    => $list,
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
  
  function get_master_umroh_front(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $kate = explode(",",$pst['kategori']);
      if($pst['kategori']){
        $no = '';
        $kategori = " AND (";
        foreach ($kate AS $kat){
          if($no)
            $kategori .= 'OR';
          $kategori .= " A.kategori = '{$kat}'";
          $no++;
        }
        $kategori .= ")";
      }
      
      $list = $this->global_models->get_query("SELECT A.*"
        . " ,B.kode AS sch"
        . " FROM umroh_master AS A"
        . " LEFT JOIN umroh_schedule AS B ON A.id_umroh_master = B.id_umroh_master"
        . " WHERE A.status = 2 AND B.status = 2 AND (B.depart > '".date("Y-m-d")."')"
        . " {$kategori}"
        . " GROUP BY A.id_umroh_master"
        . " ORDER BY B.depart ASC LIMIT 0,8"
        . "");
      $kirim = array(
        'status'  => 2,
        'data'    => $list,
        'note'    => 'Berhasil'
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
  
  function get_master_umroh_promo(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $list = $this->global_models->get_query("SELECT A.*"
        . " ,B.kode AS sch"
        . " FROM umroh_master AS A"
        . " LEFT JOIN umroh_schedule AS B ON A.id_umroh_master = B.id_umroh_master"
        . " WHERE A.status = 2 AND B.status = 2 AND (B.depart > '".date("Y-m-d")."')"
        . " AND A.promo IS NOT NULL AND B.promo = 2"
        . " GROUP BY A.id_umroh_master"
        . " ORDER BY B.depart ASC LIMIT {$pst['start']},{$pst['limit']}"
        . "");
      $kirim = array(
        'status'  => 2,
        'data'    => $list,
        'note'    => 'Berhasil'
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
  
  function get_master_umroh_web(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['code']){
        $list = $this->global_models->get_query("SELECT A.*"
          . " ,B.depart, B.hotel, B.double, B.triple, B.quad"
          . " FROM umroh_master AS A"
          . " LEFT JOIN umroh_schedule AS B ON A.id_umroh_master = B.id_umroh_master"
          . " WHERE B.kode = '{$pst['code']}'"
          . "");
        $kirim = array(
          'status'  => 4,
          'data'    => $list,
          'note'    => 'Berhasil'
        );
      }
      else{
        $order = "B.depart ASC";
        $where = "";
        $kate = explode(",",$pst['kategori']);
        if($pst['kategori']){
          $no = '';
          $where .= " AND (";
          foreach ($kate AS $kat){
            if($no)
              $where .= 'OR';
            $where .= " A.kategori = '{$kat}'";
            $no++;
          }
          $where .= ")";
        }
//        
        $list = $this->global_models->get_query("SELECT A.*"
          . " FROM umroh_master AS A"
          . " LEFT JOIN umroh_schedule AS B ON A.id_umroh_master = B.id_umroh_master"
          . " WHERE A.status = 2 AND B.status = 2 AND (B.depart > '".date("Y-m-d")."')"
          . " {$where}"
          . " GROUP BY A.id_umroh_master"
          . " ORDER BY {$order} LIMIT {$pst['start']}, {$pst['limit']}"
          . "");
        foreach($list AS $key => $ls){
          $schedule = $this->global_models->get_query(""
            . " SELECT A.kode, A.depart, A.double, A.triple, A.quad"
            . " FROM umroh_schedule AS A"
            . " WHERE A.status = 2 AND (A.depart > '".date("Y-m-d")."')"
            . " AND A.id_umroh_master = '{$ls->id_umroh_master}'"
            . " ORDER BY A.depart ASC"
            . "");
          $sc[$key] = $schedule;
        }
        $kirim = array(
          'status'  => 2,
          'data'    => $list,
          'schedule'=> $sc,
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
  
  function get_master_umroh_schedule(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      if($pst['id']){
        $list = $this->global_models->get_query("SELECT A.*"
          . " FROM umroh_schedule AS A"
          . " WHERE A.id_umroh_schedule = '{$pst['id']}'"
          . "");
        $kirim = array(
          'status'  => 4,
          'data'    => $list,
          'note'    => 'Berhasil'
        );
      }
      else{
        $list = $this->global_models->get_query("SELECT A.*"
          . " FROM umroh_schedule AS A"
          . " WHERE A.id_umroh_master = '{$pst['id_umroh_master']}'"
          . " {$pst['where']}"
          . " ORDER BY A.depart ASC LIMIT {$pst['start']},{$pst['limit']}"
          . "");
        $sch = $this->global_models->get_query("SELECT A.kode, A.depart"
          . " FROM umroh_schedule AS A"
          . " WHERE A.id_umroh_master = '{$pst['id_umroh_master']}'"
          . " AND A.depart > '".date("Y-m-d")."'"
          . "");
        $kirim = array(
          'status'  => 2,
          'data'    => $list,
          'schedule'=> $sch,
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
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */