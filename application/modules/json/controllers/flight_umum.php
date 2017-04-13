<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flight_umum extends MX_Controller {
    
  function __construct() {      
    
  }
  
  public function status_tiket_issued(){
    $cookie_jar = tempnam('/tmp','cookie');
    
    $this->login_curl($cookie_jar);
    $book = $this->global_models->get_query("SELECT A.*"
      . " FROM tiket_issued AS A"
      . " WHERE issued_no IS NULL");
//    $this->debug($book, true);
    foreach($book AS $bk){
//      if($bk->book_code){
        $book1st = $this->global_models->get_field("tiket_flight", "book_code", array("id_tiket_book" => $bk->id_tiket_book, "flight" => 1));
        $book2nd = $this->global_models->get_field("tiket_flight", "book_code", array("id_tiket_book" => $bk->id_tiket_book, "flight" => 2));
        $hasil = $this->olah_utama_issued($cookie_jar, $book1st, $bk->id_tiket_issued, $book2nd);
        print date("Y-m-d H:i:s")." {$book1st} {$book2nd} {$bk->id_tiket_book} {$hasil['status']} {$hasil['note']}<br />";
//      }
    }
    die;
  }
  
  public function status_tiket(){
    $cookie_jar = tempnam('/tmp','cookie');
    
    $this->login_curl($cookie_jar);
    $book = $this->global_models->get_query("SELECT A.*"
      . " FROM tiket_book AS A"
      . " WHERE status = 1 OR status IS NULL");
    foreach($book AS $bk){
      if($bk->book_code){
        $hasil = $this->olah_utama($cookie_jar, 
          $this->global_models->get_field("tiket_flight", "book_code", array("id_tiket_book" => $bk->id_tiket_book, "flight" => 1)), 
          $bk->id_tiket_book, 
          $this->global_models->get_field("tiket_flight", "book_code", array("id_tiket_book" => $bk->id_tiket_book, "flight" => 2))
          );
        print date("Y-m-d H:i:s")." {$bk->book_code} {$bk->id_tiket_book} {$hasil['status']} {$hasil['note']}<br />";
      }
    }
    die;
  }
  
  
  public function status_tiket_single($book_code){
    $cookie_jar = tempnam('/tmp','cookie');
    
    $this->login_curl($cookie_jar);
    $book = $this->global_models->get_query("SELECT *"
      . " FROM tiket_book"
      . " WHERE book_code = '{$book_code}'");
    if($book[0]->status == 1){
      $hasil = $this->set_curl($cookie_jar, $book_code);

  //    cari book 1st
      $book_code1st_array = $this->find_1st_book_code($hasil, $book);
      $yh = count($book_code1st_array) - 1;
      $book_code1st = $book_code1st_array[$yh];
  //    end cari book 1st
  //    cari book 1st
      $book_code2nd = $this->find_2nd_book_code($hasil, $book_code1st_array[1]);
  //    end cari book 1st
  //    print "<textarea>";
  //    $this->debug($hasil);
  //    $this->debug($book_code2nd);
  //    print "</textarea>";
      if($book_code1st != $book_code2nd){
        $this->global_models->update("tiket_book", array("id_tiket_book" => $book[0]->id_tiket_book), 
          array("book_code" => $book_code1st));
        $this->global_models->update("tiket_flight", array("id_tiket_book" => $book[0]->id_tiket_book, "flight" => 1), 
          array("book_code" => $book_code1st));
        $this->global_models->update("tiket_flight", array("id_tiket_book" => $book[0]->id_tiket_book, "flight" => 2), 
          array("book_code" => $book_code2nd));
      }
      else{
        $book_code2nd = NULL;
      }

      $hasil = $this->olah_utama($cookie_jar, $book_code1st, $book[0]->id_tiket_book, $book_code2nd);
      print date("Y-m-d H:i:s")." {$bk->book_code} {$bk->id_tiket_book} {$hasil['status']} {$hasil['note']}<br />";
    }
    else if($book[0]->status == 3 OR $book[0]->status == 5){
      $book1st = $book[0]->book_code;
      $book2nd = $this->global_models->get_field("tiket_flight", "book_code", array("id_tiket_book" => $book[0]->id_tiket_book, "flight" => 2));
      $hasil = $this->olah_utama_issued($cookie_jar, $book1st, $bk->id_tiket_issued, $book2nd);
      print date("Y-m-d H:i:s")." {$book1st} {$book2nd} {$bk->id_tiket_book} {$hasil['status']} {$hasil['note']}<br />";
    }
    die;
  }
  
  private function find_1st_book_code($hasil, $book){
    $hasil_temp = explode(date("Y-m-d", strtotime($book[0]->tanggal))."</td>", $hasil);
    $hasil_temp = explode("</td>", $hasil_temp[1]);
    $hasil_temp = explode(">", $hasil_temp[1]);
    return $hasil_temp;
  }
  
  private function find_2nd_book_code($hasil, $param){
    $hasil_temp = explode($param.">", $hasil);
    $hasil_temp = explode("</td>", $hasil_temp[4]);
    return $hasil_temp[0];
  }
  
  function olah_utama(&$cookie_jar, $book_code, $id_tiket_book, $book2nd = NULL){
    $hasil = $this->set_curl($cookie_jar, $book_code);
    
    if(strpos($hasil, ">{$book_code}<")){
    
      $hanya_hasil = explode(">{$book_code}<", $hasil);
      $after_payment = explode("<font color=#ff0000><b>", $hanya_hasil[1]);
      $after_payment = explode("</b></font></td>", $after_payment[1]);

      $timelimit = explode("'>", $after_payment[1]);
      $after_timelimit = explode("</td><td>", $timelimit[1]);
      $hpp = explode("</td>", $timelimit[4]);

      $passenger = $this->global_models->get_query("SELECT A.*"
        . " FROM tiket_passenger AS A"
        . " WHERE A.id_tiket_book = '{$id_tiket_book}'");
      $harga_total = 0;  
  //    $gy = 1;
      $harga_pertama = 0;
      foreach($passenger AS $psg){
        $harga_penumpang = explode(strtolower("{$psg->first_name} {$psg->last_name}"), strtolower($hanya_hasil[1]));
        $harga_penumpang = explode("right", $harga_penumpang[1]);
        $harga_penumpang = explode(">", $harga_penumpang[1]);
        $harga_penumpang = explode("</td>", $harga_penumpang[1]);
        $harga[$psg->id_tiket_book_passenger] = str_replace(",","",trim($harga_penumpang[0]));
        $harga_total += str_replace(",","",trim($harga_penumpang[0]));
        $this->global_models->update("tiket_passenger", array("id_tiket_passenger" => $psg->id_tiket_passenger), 
          array("price" => str_replace(",","",trim($harga_penumpang[0]))));

        $harga_pertama += str_replace(",","",trim($harga_penumpang[0]));
  //      $gy++;
      }
  //    $this->debug($passenger);
  //    print "<textarea>";
  //    print strtolower("</span>{$psg->first_name} {$psg->last_name}");
  //    print_r($hanya_hasil);
  //    print "</textarea>";die;
      $harga_bayar = explode("Harga Tiket Rp", $hanya_hasil[1]);
      $harga_bayar = explode("</td>", $harga_bayar[1]);
      $hasil_array = array(
        "cara_bayar"    => $after_payment[0],
        "timelimit"     => $after_timelimit[0],
        "tiket_no"      => $after_timelimit[9],
        "hpp"           => str_replace(",", "", trim($hpp[0])),
        "harga"         => $harga_total,
        "harga_bayar"   => str_replace(",", "", trim($harga_bayar[0])),
      );
  //    $this->debug($harga);
  //    $this->debug($hasil_array, true);
      if($hasil_array['timelimit']){
        $note = "Proses";
        $status = 1;
        $tiket_before = $this->global_models->get("tiket_book", array("id_tiket_book" => $id_tiket_book));
        if($hasil_array['tiket_no']){
          $status = 3;
          $note = "Issued";
          if($tiket_before[0]->harga_bayar > $hasil_array['harga_bayar']){
            $update_book['diskon'] = $tiket_before[0]->diskon + ($tiket_before[0]->harga_bayar-$hasil_array['harga_bayar']);
          }
          $channel = array(
            "BCA"           => 1,
            "MEGA CC"       => 2,
            "BANK CC"       => 3,
            "MEGAFIRST"     => 4,
            "MANDIRI CLICK" => 5,
          );
          $cb = trim($hasil_array['cara_bayar']);
          $kirim_issued = array(
            "id_tiket_book"           => $id_tiket_book,
            "harga_bayar"             => $hasil_array['harga_bayar'],
            "diskon"                  => $update_book['diskon'],
            "issued_no"               => $hasil_array['tiket_no'],
            "tanggal"                 => date("Y-m-d H:i:s"),
            "status"                  => 1,
            "channel"                 => $channel[$cb],
            "create_date"             => date("Y-m-d H:i:s")
          );
          $id_tiket_issued = $this->global_models->insert("tiket_issued", $kirim_issued);
          $update_flight['issued_no'] = $hasil_array['tiket_no'];
        }
        else if(strtotime("now") > strtotime($hasil_array['timelimit'])){
          $status = 4;
          $note = "Time Limit";
        }
        
        $update_book = array(
          "timelimit"     => $hasil_array['timelimit'],
          "harga_bayar"   => $hasil_array['harga_bayar'],
          "harga_normal"  => ($hasil_array['harga_bayar'] + $tiket_before[0]->diskon),
          "status"        => $status
        );

        if(trim($hasil_array['cara_bayar'])){
          $update_book['cara_bayar']  = $hasil_array['cara_bayar'];
        }

        $this->global_models->update("tiket_book", array("id_tiket_book" => $id_tiket_book), $update_book);
        $update_flight['price'] = $hasil_array['hpp'];
        $this->global_models->update("tiket_flight", array("id_tiket_book" => $id_tiket_book, "flight" => 1), $update_flight);

        $return = array(
          'status'  => $status,
          'note'    => $note);
      }
      else{
        $return = array(
          'status'  => 1,
          'note'    => 'Data kosong');
      }
      if($book2nd){
        $hanya_hasil = explode(">{$book2nd}<", $hasil);
  //      $after_payment = explode("<font color=#ff0000><b>", $hanya_hasil[1]);
  //      $after_payment = explode("</b></font></td>", $after_payment[1]);

        $timelimit = explode("</td>", $hanya_hasil[1]);
        $timelimit_fix = explode(">", $timelimit[0]);
        $after_timelimit = explode("<td>", $timelimit[10]);
        $hpp = explode("'>", $timelimit[14]);

        $passenger = $this->global_models->get_query("SELECT A.*"
          . " FROM tiket_passenger AS A"
          . " WHERE A.id_tiket_book = '{$id_tiket_book}'");
        $harga_total = 0;  
        foreach($passenger AS $psg){
          $harga_penumpang = explode(strtolower("{$psg->first_name} {$psg->last_name}"), strtolower($hanya_hasil[1]));
          $harga_penumpang = explode("<td align='right'>", $harga_penumpang[1]);
          $harga_penumpang = explode("</td>", $harga_penumpang[1]);
          $harga[$psg->id_tiket_book_passenger] = str_replace(",","",trim($harga_penumpang[0]));
          $harga_total += str_replace(",","",trim($harga_penumpang[0]));
          $this->global_models->update("tiket_passenger", array("id_tiket_passenger" => $psg->id_tiket_passenger), 
            array("price2nd" => str_replace(",","",trim($harga_penumpang[0]))));
        }
        $harga_bayar = explode("Harga Tiket Rp", $hanya_hasil[1]);
        $harga_bayar = explode("</td>", $harga_bayar[1]);
        $hasil_array2 = array(
          "timelimit"     => trim($timelimit_fix[1]),
          "tiket_no"      => trim($after_timelimit[1]),
          "harga"         => $harga_total,
          "harga_bayar"   => str_replace(",", "", trim($harga_bayar[0])),
          "hpp"           => str_replace(",", "", trim($hpp[1])),
        );

        if($hasil_array2['timelimit']){
          $note = "Proses";
          if($hasil_array2['tiket_no']){
            $status = 3;
            $note = "Issued";
            $update_flight2['issued_no'] = $hasil_array2['tiket_no'];
          }
          $update_flight2['price'] = trim($hasil_array2['hpp']);
          $this->global_models->update("tiket_flight", array("id_tiket_book" => $id_tiket_book, "flight" => 2),$update_flight2);
          $return = array(
            'status'  => $status,
            'note'    => $note);
        }

      }
    }
    else{
      $return = array(
        'status'  => 1,
        'note'    => 'Data kosong');
    }
//    $harga_bayar = explode("Harga Tiket Rp", $hasil);
//    $harga_bayar2 = explode("</td>", $harga_bayar[1]);
//    $book_final = $this->global_models->get("tiket_book", array("id_tiket_book" => $id_tiket_book));
//    if(str_replace(",", "", trim($harga_bayar2[0])) != ($book_final[0]->price + $book_final[0]->child)){
//      $hemat_mega = $this->global_models->get_query("SELECT id_website_hemat_mega"
//        . " FROM website_hemat_mega"
//        . " WHERE '{$book_final[0]->tanggal}' BETWEEN mulai AND akhir");
//      if($hemat_mega[0]->id_website_hemat_mega){
//        $this->global_models->update("tiket_book", array("id_tiket_book" => $id_tiket_book), 
//          array("id_website_hemat_mega" => $hemat_mega[0]->id_website_hemat_mega));
//      }
//    }
    return $return;
  }
  
  function olah_utama_issued(&$cookie_jar, $book_code, $id_tiket_issued, $book2nd = NULL){
    $hasil = $this->set_curl($cookie_jar, $book_code);
    
    if(strpos($hasil, ">{$book_code}<")){
    
      $hanya_hasil = explode(">{$book_code}<", $hasil);
      $after_payment = explode("<font color=#ff0000><b>", $hanya_hasil[1]);
      $after_payment = explode("</b></font></td>", $after_payment[1]);

      $timelimit = explode("'>", $after_payment[1]);
      $after_timelimit = explode("</td><td>", $timelimit[1]);
      $hpp = explode("</td>", $timelimit[4]);

      $harga_total = 0;  
  //    $gy = 1;
      $harga_pertama = 0;
      
      $harga_bayar = explode("Harga Tiket Rp", $hanya_hasil[1]);
      $harga_bayar = explode("</td>", $harga_bayar[1]);
      
      $kirim_issued = array(
        "issued_no"               => $after_timelimit[9],
      );
      if($kirim_issued['issued_no']){
        $this->global_models->update("tiket_issued", array("id_tiket_issued" => $id_tiket_issued), $kirim_issued);
        $kirim_issued["price"] = trim(str_replace(",","",$hpp[0]));
        $this->global_models->update("tiket_flight", array("book_code" => $book_code), $kirim_issued);
        $return = array(
          'status'  => 2,
          'note'    => 'Issued No '.$kirim_issued['issued_no']);
      }
      else{
        $return = array(
          'status'  => 3,
          'note'    => 'Issued No Not Found');
      }
      
  
      if($book2nd){
        $hanya_hasil = explode(">{$book2nd}<", $hasil);
  
        $timelimit = explode("</td>", $hanya_hasil[1]);
        $timelimit_fix = explode(">", $timelimit[0]);
        $after_timelimit = explode("<td>", $timelimit[10]);
        $hpp = explode("'>", $timelimit[14]);

        $harga_total = 0;  
        $harga_bayar = explode("Harga Tiket Rp", $hanya_hasil[1]);
        $harga_bayar = explode("</td>", $harga_bayar[1]);
        $hasil_array2 = array(
          "issued_no"      => trim($after_timelimit[1]),
          "price"          => trim(str_replace(",","",$hpp[0])),
        );
        if($hasil_array2['issued_no']){
          $this->global_models->update("tiket_flight", array("book_code" => $book2nd), $hasil_array2);
        }

      }
    }
    else{
      $return = array(
        'status'  => 1,
        'note'    => 'Data kosong');
    }
//    $harga_bayar = explode("Harga Tiket Rp", $hasil);
//    $harga_bayar2 = explode("</td>", $harga_bayar[1]);
//    $book_final = $this->global_models->get("tiket_book", array("id_tiket_book" => $id_tiket_book));
//    if(str_replace(",", "", trim($harga_bayar2[0])) != ($book_final[0]->price + $book_final[0]->child)){
//      $hemat_mega = $this->global_models->get_query("SELECT id_website_hemat_mega"
//        . " FROM website_hemat_mega"
//        . " WHERE '{$book_final[0]->tanggal}' BETWEEN mulai AND akhir");
//      if($hemat_mega[0]->id_website_hemat_mega){
//        $this->global_models->update("tiket_book", array("id_tiket_book" => $id_tiket_book), 
//          array("id_website_hemat_mega" => $hemat_mega[0]->id_website_hemat_mega));
//      }
//    }
    return $return;
  }
  
  function login_curl(&$cookie_jar){
    $login = "http://tiket.antavaya.com/finish/logincek.php";
    $post = array(
      "username" 	=> "donny",
      "pass"		=> "donny789",
      "Submit"	=> "Login"
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $login);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  function set_curl($cookie_jar, $book_code){
    
    $post_diskon = array(
      "rbrpt" 			=> 'pnr',
      "tgla"			=> '',
      "tglb"			=> '',
      "tktnopnr"			=> $book_code,
      "submit"			=> "Search",
      "hairln"			=> "",
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://tiket.antavaya.com/finish/vayatkt.php");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_diskon);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    $hasil_1 = curl_exec($ch);
    curl_close($ch);

    return $hasil_1;
  }
  
  
  function migrasi_data_book(){
    $this->global_models->get_connect("anta2");
    $tiket_book = $this->global_models->get("tiket_book");
    $this->global_models->get_connect("default");
    foreach($tiket_book AS $tb){
      $harga_awal = $tb->price + $tb->child;
      $diskon_maskapai = $tb->infant;
      $diskon_mega = 0;
      if($tb->id_website_hemat_mega){
        $this->global_models->get_connect("anta2");
        $mega = $this->global_models->get("website_hemat_mega", array("id_website_hemat_mega" => $tb->id_website_hemat_mega));
        $this->global_models->get_connect("default");
        if($mega[0]->nilai > 0){
          $diskon_mega = $mega[0]->nilai;
        }
        else{
          $diskon_mega = $mega[0]->hemat/100 * ($harga_awal - $diskon_maskapai);
        }
      }
//      if($tb->id_users <= 0)
        $usersusers = NULL;
//      else
//        $usersusers = $tb->id_users;
      $kirim_tiket_book = array(
        "id_users"            => $usersusers,
        "harga_bayar"         => $tb->harga_bayar,
        "harga_normal"        => ($tb->harga_bayar + $diskon_maskapai + $diskon_mega),
        "cara_bayar"          => $tb->cara_bayar,
        "diskon"              => ($diskon_maskapai + $diskon_mega),
        "first_name"          => $tb->first_name,
        "last_name"           => $tb->last_name,
        "book_code"           => $tb->book_code,
        "tanggal"             => $tb->tanggal,
        "status"              => $tb->status,
        "timelimit"           => $tb->timelimit,
        "telphone"            => $tb->telphone,
        "email"               => $tb->email,
        "create_by_users"     => $tb->create_by_users,
        "create_date"         => $tb->create_date,
        "update_by_users"     => $tb->update_by_users,
        "update_date"         => $tb->update_date,
      );
      $id_tiket_book = $this->global_models->insert("tiket_book", $kirim_tiket_book);
      if($tb->status == 3){
        $channel = array(
          "BCA"           => 1,
          "MEGA CC"       => 2,
          "BANK CC"       => 3,
          "MEGAFIRST"     => 4,
          "MANDIRI CLICK" => 5,
        );
        $kirim_tiket_issued = array(
          "id_tiket_book"       => $id_tiket_book,
          "harga_bayar"         => $tb->harga_bayar,
          "diskon"              => ($diskon_maskapai + $diskon_mega),
          "tanggal"             => $tb->tanggal,
          "status"              => 1,
          "issued_no"           => $tb->tiket_no,
          "channel"             => $channel[$tb->cara_bayar],
          "create_by_users"     => $tb->create_by_users,
          "create_date"         => $tb->create_date,
          "update_by_users"     => $tb->update_by_users,
          "update_date"         => $tb->update_date,
        );
        $id_tiket_issued = $this->global_models->insert("tiket_issued", $kirim_tiket_issued);
      }
      
      $this->global_models->get_connect("anta2");
      $tiket_flight1st = $this->global_models->get("tiket_flight", array("id_tiket_flight" => $tb->id_tiket_flight));
      $tiket_flight1st_items = $this->global_models->get("tiket_flight_items", array("id_tiket_flight" => $tb->id_tiket_flight));
      if($tb->id_tiket_flight2nd){
        $tiket_flight2nd = $this->global_models->get("tiket_flight", array("id_tiket_flight" => $tb->id_tiket_flight2nd));
        $tiket_flight2nd_items = $this->global_models->get("tiket_flight_items", array("id_tiket_flight" => $tb->id_tiket_flight2nd));
      }
      $this->global_models->get_connect("default");
      
      $kirim_tiket_flight1st = array(
        "id_tiket_book"           => $id_tiket_book,
        "flight"                  => 1,
        "book_code"               => $tb->book_code,
        "issued_no"               => $tb->tiket_no,
        "dari"                    => $tiket_flight1st[0]->dari,
        "ke"                      => $tiket_flight1st[0]->ke,
        "stop"                    => $tiket_flight1st[0]->stop,
        "departure"               => $tiket_flight1st[0]->departure,
        "arrive"                  => $tiket_flight1st[0]->arrive,
        "price"                   => $tb->hpp,
        "maskapai"                => $tiket_flight1st[0]->maskapai,
        "img"                     => $tiket_flight1st[0]->img,
        "tanggal"                 => $tiket_flight1st[0]->tanggal,
        "create_by_users"         => $tiket_flight1st[0]->create_by_users,
        "create_date"             => $tiket_flight1st[0]->create_date,
        "update_by_users"         => $tiket_flight1st[0]->update_by_users,
        "update_date"             => $tiket_flight1st[0]->update_date,
      );
      $id_tiket_flight1st = $this->global_models->insert("tiket_flight", $kirim_tiket_flight1st);
      foreach($tiket_flight1st_items AS $tki1st){
        $kirim_tiket_flight_items[] = array(
          "id_tiket_flight"         => $id_tiket_flight1st,
          "id_website_class_code"   => $tki1st->id_website_class_code,
          "flight_no"               => $tki1st->flight_no,
          "dari"                    => $tki1st->dari,
          "ke"                      => $tki1st->ke,
          "departure"               => $tki1st->departure,
          "arrive"                  => $tki1st->arrive,
          "create_by_users"         => $tki1st->create_by_users,
          "create_date"             => $tki1st->create_date,
          "update_by_users"         => $tki1st->update_by_users,
          "update_date"             => $tki1st->update_date,
        );
      }
      if($tb->id_tiket_flight2nd){
        $kirim_tiket_flight2nd = array(
          "id_tiket_book"           => $id_tiket_book,
          "flight"                  => 2,
          "book_code"               => $tb->book2nd,
          "issued_no"               => $tb->tiket_no2nd,
          "dari"                    => $tiket_flight2nd[0]->dari,
          "ke"                      => $tiket_flight2nd[0]->ke,
          "stop"                    => $tiket_flight2nd[0]->stop,
          "departure"               => $tiket_flight2nd[0]->departure,
          "arrive"                  => $tiket_flight2nd[0]->arrive,
          "price"                   => $tb->hpp2nd,
          "maskapai"                => $tiket_flight2nd[0]->maskapai,
          "img"                     => $tiket_flight2nd[0]->img,
          "tanggal"                 => $tiket_flight2nd[0]->tanggal,
          "create_by_users"         => $tiket_flight2nd[0]->create_by_users,
          "create_date"             => $tiket_flight2nd[0]->create_date,
          "update_by_users"         => $tiket_flight2nd[0]->update_by_users,
          "update_date"             => $tiket_flight2nd[0]->update_date,
        );
        $id_tiket_flight2nd = $this->global_models->insert("tiket_flight", $kirim_tiket_flight2nd);
        foreach($tiket_flight2nd_items AS $tki2nd){
          $kirim_tiket_flight_items[] = array(
            "id_tiket_flight"         => $id_tiket_flight2nd,
            "id_website_class_code"   => $tki2nd->id_website_class_code,
            "flight_no"               => $tki2nd->flight_no,
            "dari"                    => $tki2nd->dari,
            "ke"                      => $tki2nd->ke,
            "departure"               => $tki2nd->departure,
            "arrive"                  => $tki2nd->arrive,
            "create_by_users"         => $tki2nd->create_by_users,
            "create_date"             => $tki2nd->create_date,
            "update_by_users"         => $tki2nd->update_by_users,
            "update_date"             => $tki2nd->update_date,
          );
        }
      }
      $this->global_models->get_connect("anta2");
      $passanger = $this->global_models->get_query("SELECT B.*, A.harga, A.harga2nd"
        . " FROM tiket_book_passenger AS A"
        . " LEFT JOIN tiket_passenger AS B ON A.id_tiket_passenger = B.id_tiket_passenger"
        . " WHERE A.id_tiket_book = '{$tb->id_tiket_book}'");
      $this->global_models->get_connect("default");
      foreach($passanger AS $psgr){
        $kirim_tiket_passanger[] = array(
          "id_tiket_book"         => $id_tiket_book,
          "first_name"            => $psgr->first_name,
          "last_name"             => $psgr->last_name,
          "title"                 => $psgr->title,
          "tanggal_lahir"         => $psgr->tanggal_lahir,
          "pax"                   => $psgr->pax,
          "price"                 => $psgr->harga,
          "price2nd"              => $psgr->harga2nd,
          "type"                  => $psgr->type,
          "create_by_users"       => $psgr->create_by_users,
          "create_date"           => $psgr->create_date,
          "update_by_users"       => $psgr->update_by_users,
          "update_date"           => $psgr->update_date,
        );
      }
    }
    $this->global_models->insert_batch("tiket_flight_items", $kirim_tiket_flight_items);
    $this->global_models->insert_batch("tiket_passenger", $kirim_tiket_passanger);
  }
  function migrasi_data_discount(){
    $this->global_models->get_connect("anta2");
    $tiket_book = $this->global_models->get("website_hemat_mega", array("status" => 1));
    $this->global_models->get_connect("default");
    foreach($tiket_book AS $tb){
      if($tb->hemat > 0){
        $type = 1;
        $nilai = $tb->hemat;
      }
      else{
        $type = 2;
        $nilai = $tb->nilai;
      }
      $kirim[] = array(
          "type"            => $type,
          "channel"         => 2,
          "title"           => $tb->title,
          "nilai"           => $nilai,
          "mulai"           => $tb->mulai,
          "akhir"           => $tb->akhir,
          "note"            => $tb->note,
          "status"          => $tb->status,
          "create_by_users" => $tb->create_by_users,
          "create_date"     => $tb->create_date,
          "update_by_users" => $tb->update_by_users,
      );
      $kirim[] = array(
          "type"            => $type,
          "channel"         => 4,
          "title"           => $tb->title,
          "nilai"           => $nilai,
          "mulai"           => $tb->mulai,
          "akhir"           => $tb->akhir,
          "note"            => $tb->note,
          "status"          => $tb->status,
          "create_by_users" => $tb->create_by_users,
          "create_date"     => $tb->create_date,
          "update_by_users" => $tb->update_by_users,
      );
    }
    $this->global_models->insert_batch("tiket_discount", $kirim);
  }
  
  function cari_no_file_tiket(){
    $book = $this->global_models->get_query("SELECT A.*, B.channel"
      . " FROM tiket_book AS A"
      . " LEFT JOIN tiket_issued AS B ON A.id_tiket_book = B.id_tiket_book"
      . " WHERE A.status = 6 OR A.status = 7");
    foreach($book AS $bk){
      $post = array(
        'users'             => USERSSERVER, 
        'password'          => PASSSERVER,
        "book_code"         => $bk->book_code,
        'harga_bayar'       => $bk->harga_bayar,
        'channel'           => $bk->channel,
      );
      $data = $this->curl_mentah($post, site_url("json/issued"));
      $data_array = json_decode($data);
      print date("Y-m-d H:i:s")." - ".$data_array->status." ".$data_array->note."<br />";
    }
    die;
  }
  
  function alert_timelimit(){
    $book = $this->global_models->get_query("SELECT A.*"
      . " FROM tiket_book AS A"
      . " WHERE (status = 1 OR status IS NULL)"
      . " AND (timelimit BETWEEN '".date("Y-m-d H:i:s", (strtotime("now") - (60*60)))."' AND '".date("Y-m-d H:i:s")."')"
      . " AND (alert_email <> 2 OR alert_email IS NULL)");
//    $this->debug("SELECT A.*"
//      . " FROM tiket_book AS A"
//      . " WHERE (status = 1 OR status IS NULL)"
//      . " AND (timelimit BETWEEN '".date("Y-m-d H:i:s", (strtotime("now") - (30*60)))."' AND '".date("Y-m-d H:i:s")."')"
//      . " AND (alert_email <> 2 OR alert_email IS NULL)", true);
    $this->load->library('email');
    $this->email->initialize($this->global_models->email_conf());
    foreach($book AS $bk){
      $kirim = array(
        'users'             => USERSSERVER, 
        'password'          => PASSSERVER,
        'book_code'         => $bk->book_code
      );
      $tiket_book_json = $this->curl_mentah($kirim, site_url("json/get-detail-tiket-book"));
      $tiket_book = json_decode($tiket_book_json);
     // print_r($tiket_book->diskon_payment[0]->book_code);

     // echo $tiket_book->pemesan->email;
     /*print "<pre>";
      print_r($tiket_book); 
      print "</pre>";
      die; */

      $this->email->from("no-reply@antavaya.com","No Reply");

      $this->email->to($tiket_book->pemesan->email);
      $this->email->bcc('nugroho.budi@antavaya.com');
      // die;

      $this->email->subject('Tiket Book '.$book_code);
      $isihtml = "<html xmlns='http://www.w3.org/1999/xhtml'>"
        . "<head>"
          . "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>"
          . "<meta name='viewport' content='width=device-width'/>"
          . "<style>#outlook a{padding:0;}body{width:100%!important;min-width:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;margin:0;padding:0;}.ExternalClass{width:100%;}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:100%;}#backgroundTable{margin:0;padding:0;width:100%!important;line-height:100%!important;}img{outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;width:auto;max-width:100%;float:left;clear:both;display:block;}center{width:100%;min-width:580px;}a img{border:none;}p{margin:0 0 0 10px;}table{border-spacing:0;border-collapse:collapse;}td{word-break:break-word;-webkit-hyphens:auto;-moz-hyphens:auto;hyphens:auto;border-collapse:collapse!important;}table,tr,td{padding:0;vertical-align:top;text-align:left;}hr{color:#d9d9d9;background-color:#d9d9d9;height:1px;border:none;}table.body{height:100%;width:100%;}table.container{width:580px;margin:0 auto;text-align:inherit;}table.row{padding:0px;width:100%;position:relative;}table.container table.row{display:block;}td.wrapper{padding:10px 300px 0px 0px;position:relative;}table.columns,table.column{margin:0 auto;}table.columns td,table.column td{padding:0px 0px 10px;}table.columns td.sub-columns,table.column td.sub-columns,table.columns td.sub-column,table.column td.sub-column{padding-right:10px;}td.sub-column,td.sub-columns{min-width:0px;}table.row td.last,table.container td.last{padding-right:0px;}table.one{width:30px;}table.two{width:80px;}table.three{width:130px;}table.four{width:180px;}table.five{width:230px;}table.six{width:280px;}table.seven{width:330px;}table.eight{width:380px;}table.nine{width:430px;}table.ten{width:480px;}table.eleven{width:530px;}table.twelve{width:580px;}table.one center{min-width:30px;}table.two center{min-width:80px;}table.three center{min-width:130px;}table.four center{min-width:180px;}table.five center{min-width:230px;}table.six center{min-width:280px;}table.seven center{min-width:330px;}table.eight center{min-width:380px;}table.nine center{min-width:430px;}table.ten center{min-width:480px;}table.eleven center{min-width:530px;}table.twelve center{min-width:580px;}table.one .panel center{min-width:10px;}table.two .panel center{min-width:60px;}table.three .panel center{min-width:110px;}table.four .panel center{min-width:160px;}table.five .panel center{min-width:210px;}table.six .panel center{min-width:260px;}table.seven .panel center{min-width:310px;}table.eight .panel center{min-width:360px;}table.nine .panel center{min-width:410px;}table.ten .panel center{min-width:460px;}table.eleven .panel center{min-width:510px;}table.twelve .panel center{min-width:560px;}.body .columns td.one,.body .column td.one{width:8.333333%;}.body .columns td.two,.body .column td.two{width:16.666666%;}.body .columns td.three,.body .column td.three{width:25%;}.body .columns td.four,.body .column td.four{width:33.333333%;}.body .columns td.five,.body .column td.five{width:41.666666%;}.body .columns td.six,.body .column td.six{width:50%;}.body .columns td.seven,.body .column td.seven{width:58.333333%;}.body .columns td.eight,.body .column td.eight{width:66.666666%;}.body .columns td.nine,.body .column td.nine{width:75%;}.body .columns td.ten,.body .column td.ten{width:83.333333%;}.body .columns td.eleven,.body .column td.eleven{width:91.666666%;}.body .columns td.twelve,.body .column td.twelve{width:100%;}td.offset-by-one{padding-left:50px;}td.offset-by-two{padding-left:100px;}td.offset-by-three{padding-left:150px;}td.offset-by-four{padding-left:200px;}td.offset-by-five{padding-left:250px;}td.offset-by-six{padding-left:300px;}td.offset-by-seven{padding-left:350px;}td.offset-by-eight{padding-left:400px;}td.offset-by-nine{padding-left:450px;}td.offset-by-ten{padding-left:500px;}td.offset-by-eleven{padding-left:550px;}td.expander{visibility:hidden;width:0px;padding:0!important;}table.columns .text-pad,table.column .text-pad{padding-left:10px;padding-right:10px;}table.columns .left-text-pad,table.columns .text-pad-left,table.column .left-text-pad,table.column .text-pad-left{padding-left:10px;}table.columns .right-text-pad,table.columns .text-pad-right,table.column .right-text-pad,table.column .text-pad-right{padding-right:10px;}.block-grid{width:100%;max-width:580px;}.block-grid td{display:inline-block;padding:10px;}.two-up td{width:270px;}.three-up td{width:173px;}.four-up td{width:125px;}.five-up td{width:96px;}.six-up td{width:76px;}.seven-up td{width:62px;}.eight-up td{width:52px;}table.center,td.center{text-align:center;}h1.center,h2.center,h3.center,h4.center,h5.center,h6.center{text-align:center;}span.center{display:block;width:100%;text-align:center;}img.center{margin:0 auto;float:none;}.show-for-small,.hide-for-desktop{display:none;}body,table.body,h1,h2,h3,h4,h5,h6,p,td{color:#222222;font-family:'Helvetica','Arial',sans-serif;font-weight:normal;padding:0;margin:0;text-align:left;line-height:1.3;}h1,h2,h3,h4,h5,h6{word-break:normal;}h1{font-size:40px;}h2{font-size:36px;}h3{font-size:32px;}h4{font-size:28px;}h5{font-size:24px;}h6{font-size:20px;}body,table.body,p,td{font-size:14px;line-height:19px;}p.lead,p.lede,p.leed{font-size:18px;line-height:21px;}p{margin-bottom:10px;}small{font-size:10px;}a{color:#2ba6cb;text-decoration:none;}a:hover{color:#2795b6!important;}a:active{color:#2795b6!important;}a:visited{color:#2ba6cb!important;}h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{color:#2ba6cb;}h1 a:active,h2 a:active,h3 a:active,h4 a:active,h5 a:active,h6 a:active{color:#2ba6cb!important;}h1 a:visited,h2 a:visited,h3 a:visited,h4 a:visited,h5 a:visited,h6 a:visited{color:#2ba6cb!important;}.panel{background:#f2f2f2;border:1px solid #d9d9d9;padding:10px!important;}.sub-grid table{width:100%;}.sub-grid td.sub-columns{padding-bottom:0;}table.button,table.tiny-button,table.small-button,table.medium-button,table.large-button{width:120%;overflow:hidden;}table.button td,table.tiny-button td,table.small-button td,table.medium-button td,table.large-button td{display:block;width:auto!important;text-align:center;background:#2ba6cb;border:1px solid #2284a1;color:#ffffff;padding:8px 0;}table.tiny-button td{padding:5px 0 4px;}table.small-button td{padding:8px 0 7px;}table.medium-button td{padding:12px 0 10px;}table.large-button td{padding:21px 0 18px;}table.button td a,table.tiny-button td a,table.small-button td a,table.medium-button td a,table.large-button td a{font-weight:bold;text-decoration:none;font-family:Helvetica,Arial,sans-serif;color:#ffffff;font-size:16px;}table.tiny-button td a{font-size:12px;font-weight:normal;}table.small-button td a{font-size:16px;}table.medium-button td a{font-size:20px;}table.large-button td a{font-size:24px;}table.button:hover td,table.button:visited td,table.button:active td{background:#2795b6!important;}table.button:hover td a,table.button:visited td a,table.button:active td a{color:#fff!important;}table.button:hover td,table.tiny-button:hover td,table.small-button:hover td,table.medium-button:hover td,table.large-button:hover td{background:#2795b6!important;}table.button:hover td a,table.button:active td a,table.button td a:visited,table.tiny-button:hover td a,table.tiny-button:active td a,table.tiny-button td a:visited,table.small-button:hover td a,table.small-button:active td a,table.small-button td a:visited,table.medium-button:hover td a,table.medium-button:active td a,table.medium-button td a:visited,table.large-button:hover td a,table.large-button:active td a,table.large-button td a:visited{color:#ffffff!important;}table.secondary td{background:#e9e9e9;border-color:#d0d0d0;color:#555;}table.secondary td a{color:#555;}table.secondary:hover td{background:#d0d0d0!important;color:#555;}table.secondary:hover td a,table.secondary td a:visited,table.secondary:active td a{color:#555!important;}table.success td{background:#5da423;border-color:#457a1a;}table.success:hover td{background:#457a1a!important;}table.alert td{background:#c60f13;border-color:#970b0e;}table.alert:hover td{background:#970b0e!important;}table.radius td{-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;}table.round td{-webkit-border-radius:500px;-moz-border-radius:500px;border-radius:500px;}body.outlook p{display:inline!important;}@media only screen and (max-width: 600px) {table[class='body'] img{width:auto!important;height:auto!important;}table[class='body'] center{min-width:0!important;}table[class='body'] .container{width:95%!important;}table[class='body'] .row{width:100%!important;display:block!important;}table[class='body'] .wrapper{display:block!important;padding-right:0!important;}table[class='body'] .columns,table[class='body'] .column{table-layout:fixed!important;float:none!important;width:100%!important;padding-right:0px!important;padding-left:0px!important;display:block!important;}table[class='body'] .wrapper.first .columns,table[class='body'] .wrapper.first .column{display:table!important;}table[class='body'] table.columns td,table[class='body'] table.column td{width:100%!important;}table[class='body'] .columns td.one,table[class='body'] .column td.one{width:8.333333%!important;}table[class='body'] .columns td.two,table[class='body'] .column td.two{width:16.666666%!important;}table[class='body'] .columns td.three,table[class='body'] .column td.three{width:25%!important;}table[class='body'] .columns td.four,table[class='body'] .column td.four{width:33.333333%!important;}table[class='body'] .columns td.five,table[class='body'] .column td.five{width:41.666666%!important;}table[class='body'] .columns td.six,table[class='body'] .column td.six{width:50%!important;}table[class='body'] .columns td.seven,table[class='body'] .column td.seven{width:58.333333%!important;}table[class='body'] .columns td.eight,table[class='body'] .column td.eight{width:66.666666%!important;}table[class='body'] .columns td.nine,table[class='body'] .column td.nine{width:75%!important;}table[class='body'] .columns td.ten,table[class='body'] .column td.ten{width:83.333333%!important;}table[class='body'] .columns td.eleven,table[class='body'] .column td.eleven{width:91.666666%!important;}table[class='body'] .columns td.twelve,table[class='body'] .column td.twelve{width:100%!important;}table[class='body'] td.offset-by-one,table[class='body'] td.offset-by-two,table[class='body'] td.offset-by-three,table[class='body'] td.offset-by-four,table[class='body'] td.offset-by-five,table[class='body'] td.offset-by-six,table[class='body'] td.offset-by-seven,table[class='body'] td.offset-by-eight,table[class='body'] td.offset-by-nine,table[class='body'] td.offset-by-ten,table[class='body'] td.offset-by-eleven{padding-left:0!important;}table[class='body'] table.columns td.expander{width:1px!important;}table[class='body'] .right-text-pad,table[class='body'] .text-pad-right{padding-left:10px!important;}table[class='body'] .left-text-pad,table[class='body'] .text-pad-left{padding-right:10px!important;}table[class='body'] .hide-for-small,table[class='body'] .show-for-desktop{display:none!important;}table[class='body'] .show-for-small,table[class='body'] .hide-for-desktop{display:inherit!important;}}</style>"
          . "<style>table.facebook td{background:#3b5998;border-color:#2d4473;}table.facebook:hover td{background:#2d4473!important;}table.twitter td{background:#00acee;border-color:#0087bb;}table.twitter:hover td{background:#0087bb!important;}table.google-plus td{background-color:#DB4A39;border-color:#CC0000;}table.google-plus:hover td{background:#CC0000!important;}.template-label{color:#ffffff;font-weight:bold;font-size:11px;}.callout .panel{background:#ECF8FF;border-color:#b9e5ff;}.header{background:#6B8CF5;}.footer .wrapper{background:#ebebeb;}.footer h5{padding-bottom:10px;}table.columns .text-pad{padding-left:10px;padding-right:10px;}table.columns .left-text-pad{padding-left:10px;}table.columns .right-text-pad{padding-right:10px;}@media only screen and (max-width: 600px) {table[class='body'] .right-text-pad{padding-left:10px!important;}table[class='body'] .left-text-pad{padding-right:10px!important;}}</style>"
        . "</head>"
        . "<body>"
          . "<table class='body'>"
            . "<tr>"
              . "<td class='center' align='center' valign='top'>"
                . "<center>"
                  . "<table class='row header'>"
                    . "<tr>"
                      . "<td class='center' align='center'>"
                        . "<center>"
                          . "<table class='container'>"
                            . "<tr>"
                              . "<td class='wrapper last'>"
                                . "<table class='twelve columns'>"
                                  . "<tr>"
                                    . "<td class='six sub-columns'>"
                                      . "<img src='".base_url()."themes/antavaya/images/logo.png'>"
                                    . "</td>"
                                    . "<td class='six sub-columns last' align='right' style='text-align:right; vertical-align:middle;'>"
                                      . "<span class='template-label'></span>"
                                    . "</td>"
                                    . "<td class='expander'></td>"
                                  . "</tr>"
                                . "</table>"
                              . "</td>"
                            . "</tr>"
                          . "</table>"
                        . "</center>"
                      . "</td>"
                    . "</tr>"
                  . "</table>"
                  . "<br>"
                  . "<table class='container'>"
                    . "<tr>"
                      . "<td>"
                        . "<table class='row'>"
                          . "<tr>"
                            . "<td class='wrapper last'>"
                              . "<table class='twelve columns'>"
                                . "<tr>"
                                  . "<td>"
                                    . "<h1>Dear, {$tiket_book->pemesan->first_name} {$tiket_book->pemesan->last_name}</h1>"
                                  . "</tr>"
                                  . "<tr>"
                                    . "<td><b>Anda belum melakukan pembayaran, harap melakukan pembayaran sebelum reservasi tiket anda mencapai time limit.</b></td>"
                                  . "</tr>"
                                  . "<tr>"
                                    . "<td>"
                                      . "<table class='twelve columns'>"
                                        . "<tr>"
                                          . "<td>"
                                            . "<hr><b>Silahkan pilih cara pembayaran</b> <br><hr>"
                                          . "</td>"
                                        . "</tr>"
                                        . "<tr>"
                                          . "<td>"
                                            . "<div style='width: 20%; float: left; font-size: 13px;'>"
                                              . "<a href='".site_url('payment/gunakan-bca/'.$tiket_book->flight[0]->book_code)."'>"
                                                . "<img src='".base_url()."themes/antavaya/images/bca.png' style='max-width: 80px' /><br /><br />"
                                                . "<br />Transfer BCA"
                                              . "</a>"
                                            . "</div>"
                                            . "<div style='width: 20%; float: left; font-size: 13px;'>"
                                              . "<a href='http://tiket.antavaya.com/index.php/component/mandiripayment/?view=mandiripayment&layout=default&thepnr={$tiket_book->flight[0]->book_code}'>"
                                                . "<img src='".base_url()."themes/antavaya/images/mandiri.png' style='max-width: 80px' /><br />"
                                                . "<br /><br />Mandiri ClickPay"
                                              . "</a>"
                                            . "</div>"
                                            . "<div style='width: 20%; float: left; font-size: 13px;'>"
                                              . "<a href='".site_url('payment/gunakan-cc-bank/3/'.$tiket_book->flight[0]->book_code)."'>"
                                                . "<img src='".base_url()."themes/antavaya/images/visa.png' style='max-width: 80px' /><br /><br />"
                                                . "<br />Visa/Master"
                                              . "</a>"
                                            . "</div>"
                                            . "<div style='width: 20%; float: left; font-size: 13px;'>"
                                              . "<a href='".site_url('payment/gunakan-cc-bank/2/'.$tiket_book->flight[0]->book_code)."'>"
                                                . "<img src='".base_url()."themes/antavaya/images/mega.png' style='max-width: 80px' /><br /><br />"
                                                . "<br />Mega Credit Card"
                                              . "</a>"
                                            . "</div>"
                                            . "<div style='width: 20%; float: left; font-size: 13px;'>"
                                              . "<a href='".site_url('payment/gunakan-cc-bank/4/'.$tiket_book->flight[0]->book_code)."'>"
                                                . "<img src='".base_url()."themes/antavaya/images/mega.png' style='max-width: 80px' /><br /><br />"
                                                . "<br />Mega Priority"
                                              . "</a>"
                                            . "</div>"
                                          . "</td>"
                                        . "</tr>"
                                      . "</table>"
                                    . "</td>"
                                  . "</tr>"
                                . "</td>"
                                . "<td class='expander'></td>"
                              . "</tr>"
                            . "</table>"
                          . "</td>"
                        . "</tr>"
                      . "</table>";
             if($tiket_book->diskon_payment){                     
                      $isihtml .= "<table class='row callout'>"
                        . "<tr>"
                          . "<td class='wrapper last'>"
                            . "<table class='twelve columns'>"
                              . "<tr>"
                                . "<td class='panel'>"
                                  . "<table style='font-size:12px;FONT-FAMILY:sans-serif'>"
                                    . "<tbody>";
                            foreach ($tiket_book->diskon_payment as $value) {
                                      $isihtml .= "<tr>"
                                        . "<td width='50%'>Diskon khusus bila melakukan pembayaran menggunakan <b>{$value->name}</b></td>" 
                                          . "<td width='15%'> <img src='{$value->logo}' style='max-width: 50px' /><br /></td>"
                                        . "<td ><b>Discount Rp ".  number_format($value->diskon, 0, ".", ",")."</b></td>"
                                      . "</tr>";
                           }              
                                    $isihtml .= "</tbody>"
                                  . "</table>"
                                . "</td>"
                                . "<td class='expander'></td>"
                              . "</tr>"
                            . "</table>"
                          . "</td>"
                        . "</tr>"
                      . "</table>";
              }
                      $isihtml .= "<table class='row callout'>"
                        . "<tr>"
                          . "<td class='wrapper last'>"
                            . "<table class='twelve columns'>"
                              . "<tr>"
                                . "<td class='panel'>"
                                  . "<table style='font-size:12px;FONT-FAMILY:sans-serif'>"
                                    . "<tbody>"
                                      . "<tr>"
                                        . "<td>Kode Booking </td>"
                                        . "<td>: <b>{$tiket_book->flight[0]->book_code}</b></td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td>Time Limit </td>"
                                        . "<td>: <b>".date("d F Y H:i:s", strtotime($tiket_book->book->timelimit))." WIB</b></td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<br><td style='padding-top: 5%;'>"
                                          . "<table class='button'>"
                                            . "<tr>"
                                              . "<td><a href='".site_url("antavaya/thank-you/{$tiket_book->flight[0]->book_code}")."'>Proses Payment</a></td>"
                                            . "</tr>"
                                          . "</table>"
                                        . "</td>"
                                      . "</tr>"
                                    . "</tbody>"
                                  . "</table>"
                                . "</td>"
                                . "<td class='expander'></td>"
                              . "</tr>"
                            . "</table>"
                          . "</td>"
                        . "</tr>"
                      . "</table>";        
                      $isihtml .= "<table class='row'>"
                        . "<tr>"
                          . "<td class='wrapper last'>"
                            . "<table class='twelve columns'>"
                              . "<tr>"
                                . "<td><hr><b>Berikut perincian reservasi anda</b> <hr></td>"
                                . "<td class='expander'></td>"
                              . "</tr>"
                            . "</table>"
                          . "</td>"
                        . "</tr>"
                      . "</table>"
                      . "<table class='row footer'>"
                        . "<tr>"
                          . "<td class='wrapper'>"
                            . "<table class='six columns'>"
                              . "<tr>"
                                . "<td class='left-text-pad'></td>"
                                . "<td>"
                                  . "<table style='font-size:12px;FONT-FAMILY:sans-serif; width: 207%;'>"
                                    . "<tbody>"
                                      . "<tr bgcolor='darkgray' style='padding-left:3%'>"
                                        . "<td colspan='2' style='padding-bottom: 0%;padding-left:1%'>"
                                          . "<span style='margin-up:5%'>Informasi Pemesan</span>"
                                        . "</td>"
                                      . "</tr>"
                                      . "<tr style='padding-left:20%'>"
                                        . "<td width='45%'><span style='padding-bottom: 0%;padding-left:6%'>Name </span></td>"
                                        . "<td width='50%'>: {$tiket_book->pemesan->first_name} {$tiket_book->pemesan->last_name}</td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td ><span style='padding-bottom: 0%;padding-left:6%'>Handphone </span> </td>"
                                        . "<td>: <a href='tel:08767673574' value='+628767673574' target='_blank'>{$tiket_book->pemesan->phone}</a></td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td ><span style='padding-bottom: 0%;padding-left:6%'>Email </span> </td>"
                                        . "<td>: {$tiket_book->pemesan->email}</td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td><span style='padding-bottom: 0%;padding-left:6%'>Book date </span> </td>"
                                        . "<td>: ".date("d F Y", strtotime($tiket_book->book->waktu))."</td>"
                                      . "</tr>";
            $no_adult = 0; 
            $no_child = 0;
            $no_inf   = 0;
          foreach ($tiket_book->passenger as $value) {
          if($value->type == 1){
          $no_adult += 1;
                                     // . if($aaa =123){
                                      $isihtml .= "<tr><td colspan='2' height='5px'><br></td></tr>"
                                      . "<tr bgcolor='darkgray' style='padding-left:3%'>"
                                        . "<td colspan='2' style='padding-bottom: 0%;padding-left:1%'>"
                                          . "<span style='margin-up:5%'>Informasi Penumpang {$no_adult}</span>"
                                        . "</td>"
                                      . "</tr>"
                                      . "<tr style='padding-left:20%'>"
                                        . "<td width='45%'><span style='padding-bottom: 0%;padding-left:6%'>Name </span></td>"
                                        . "<td width='50%'>: {$value->title} {$value->first_name} {$value->last_name}</td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td ><span style='padding-bottom: 0%;padding-left:6%'>Date of Birth </span> </td>"
                                        . "<td>:".date("d F Y", strtotime($value->tanggal_lahir))."</td>"
                                      . "</tr>";
                } elseif($value->type == 2){  
                    $no_child += 1;
                                      $isihtml .= "<tr><td colspan='2' height='5px'><br></td></tr>"
                                      . "<tr bgcolor='darkgray' style='padding-left:3%'>"
                                        . "<td colspan='2' style='padding-bottom: 0%;padding-left:1%'>"
                                          . "<span style='margin-up:5%'>Informasi Penumpang Child {$no_child}</span>"
                                        . "</td>"
                                      . "</tr>"
                                      . "<tr style='padding-left:20%'>"
                                        . "<td width='45%'><span style='padding-bottom: 0%;padding-left:6%'>Name </span></td>"
                                        . "<td width='50%'>: {$value->title} {$value->first_name} {$value->last_name}</td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td ><span style='padding-bottom: 0%;padding-left:6%'>Date of Birth </span> </td>"
                                        . "<td>: ".date("d F Y", strtotime($value->tanggal_lahir))."</td>"
                                      . "</tr><br>";
                }  elseif($value->type == 3){  
                    $no_inf += 1;
                                    $isihtml  .= "<tr><td colspan='2' height='5px'><br></td></tr>"
                                      . "<tr bgcolor='darkgray' style='padding-left:3%'>"
                                        . "<td colspan='2' style='padding-bottom: 0%;padding-left:1%'>"
                                          . "<span style='margin-up:5%'>Informasi Penumpang Infant {$no_inf}</span>"
                                        . "</td>"
                                      . "</tr>"
                                      . "<tr style='padding-left:20%'>"
                                        . "<td width='45%'><span style='padding-bottom: 0%;padding-left:6%'>Name </span></td>"
                                        . "<td width='50%'>: {$value->title} {$value->first_name} {$value->last_name}</td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td ><span style='padding-bottom: 0%;padding-left:6%'>Date of Birth </span> </td>"
                                        . "<td>: ".date("d F Y", strtotime($value->tanggal_lahir))."</td>"
                                      . "</tr>"
                                      . "<tr><td ><span style='padding-bottom: 0%;padding-left:6%'>Pax </span> </td><td>: {$value->pax}</td></tr>"
                                      . "<tr><td colspan='2' height='5px'><br></td></tr>";
             //                         . "<tr>"
    }           }   
             if($tiket_book->flight[0]->flight == 1){
                                      $isihtml .= "<tr bgcolor='darkgray' style='padding-left:3%'>"
                                        . "<td colspan='2' style='padding-bottom: 0%;padding-left:1%'>"
                                          . "<span style='margin-up:5%'>Outgoing Trip</span>"
                                        . "</td>"
                                      . "</tr>"
                                      . "<tr style='padding-left:20%'>"
                                        . "<td width='45%'><span style='padding-bottom: 0%;padding-left:6%'>Flight Date </span></td>"
                                        . "<td width='50%'>: ".date("d F Y", strtotime($tiket_book->flight[0]->departure))."</td>"
                                      . "</tr>"
                                      . "<tr>"
                                        . "<td ><span style='padding-bottom: 0%;padding-left:6%'>Flight No </span> </td>"
                                        . "<td>: {$tiket_book->flight[0]->penerbangan[0]->flight_no}</td>"
                                      . "</tr>"
                                      . "<tr><td ><span style='padding-bottom: 0%;padding-left:6%'>Depart</span> </td><td>: {$this->global_models->array_kota($tiket_book->flight[0]->dari)}</td></tr>"
                                      . "<tr><td ><span style='padding-bottom: 0%;padding-left:6%'>Arrive</span> </td><td>: {$this->global_models->array_kota($tiket_book->flight[0]->ke)}</td></tr>"      
                                      . "<tr><td colspan='2' height='5px'><br></td></tr>";
           }if($tiket_book->flight[1]->flight == 2){
                                      $isihtml .= "<tr bgcolor='darkgray' style='padding-left:3%'>"
                                      ."<td colspan='2' style='padding-bottom: 0%;padding-left:1%'><span style='margin-up:5%'>Return Trip</span></td>"
                                      . "</tr>"
                                      . "<tr style='padding-left:20%'>"
                                        . "<td width='45%'><span style='padding-bottom: 0%;padding-left:6%'>Flight Date </span></td>"
                                        . "<td width='50%'>: ".date("d F Y", strtotime($tiket_book->flight[1]->departure))."</td>"
                                      . "</tr>"
                                      . "<tr><td ><span style='padding-bottom: 0%;padding-left:6%'>Flight No </span> </td><td>: {$tiket_book->flight[1]->penerbangan[0]->flight_no}</td></tr>"
                                      . "<tr><td ><span style='padding-bottom: 0%;padding-left:6%'>Depart</span> </td><td>: {$this->global_models->array_kota($tiket_book->flight[1]->dari)}</td></tr>"
                                      . "<tr><td ><span style='padding-bottom: 0%;padding-left:6%'>Arrive</span> </td><td>: {$this->global_models->array_kota($tiket_book->flight[1]->ke)}</td></tr>"                                    
                                      . "<tr><td colspan='2' height='5px'><br></td></tr>";
           }                  
                                      $isihtml .= "<tr bgcolor='darkgray' style='padding-left:3%'>"
                                        . "<td colspan='2' style='padding-bottom: 0%;padding-left:1%'>"
                                          . "<span style='margin-up:5%'>Biaya Keseluruhan</span>"
                                        . "</td>"
                                      . "</tr>"
                                      . "<tr><td><span style='padding-bottom: 0%;padding-left:6%'>Price</span> </td><td>: Rp ".number_format(($tiket_book->book->harga_bayar + $tiket_book->book->diskon), 0, ".", ",")."</td></tr>"
                                      . "<tr><td><span style='padding-bottom: 0%;padding-left:6%'>Admin Fee</span> </td><td>: Gratis</td></tr>"
                                      . "<tr><td><span style='padding-bottom: 0%;padding-left:6%'>Discount</span> </td><td>: Rp ".number_format($tiket_book->book->diskon, 0, ".", ",")." </td></tr>"
                                      . "<tr><td colspan='2' height='10px'><br></td></tr>"
                                      . "<tr><td><span style='padding-bottom: 0%;padding-left:6%'>Total</span> </td><td>: <b>Rp ".number_format($tiket_book->book->harga_bayar, 0, ".", ",")." </b></td></tr>"
                                    . "</tbody>"
                                  . "</table>"
                                  . "<hr style='padding-left:200%'>"
                                . "</td>"
                              . "</td>"
                              . "<td class='expander'></td>"
                            . "</tr>"
                          . "</table>"
                        . "</td>"
                      . "</tr>"
                    . "</table>"
                    . "<table class='row'>"
                      . "<tr>"
                        . "<td style='font-size:11px'>"
                          . "<b>PERHATIAN UNTUK PEMBAYARAN DENGAN SISTEM PEMBAYARAN TRANSFER</b> <br> "
                          . "Bila nilai nominal pembayaran anda tidak sesuai dengan nilai yang tertera di tiket, tiket anda tidak akan tercetak secara otomatis. Sistem e-ticketing kami tidak dapat melakukan pengecekan transaksi pembayaran tiket anda dari pukul 21.00-05.00. Kami harapkan anda untuk melakukan pembayaran dan Konfirmasi Transfer sebelum jam 21.00 ataupun setelah jam 05.00. Bila ingin melakukan pembayaran tiket antara jam 21.00-05.00 lakukan dengan menggunakan Mandiri ClickPay.<br> Jika ada pertanyaan hubungi <b>Layanan Konsumen</b> kami di <b><a href='tel:02129227888' value='+622129227888' target='_blank'>(021) 2922 7888</a></b> atau kirimkan email ke <b><a href='mailto:cs@antavaya.com' target='_blank'>cs@antavaya.com</a></b> dengan mencantumkan Kode Booking, apabila anda mengalami kesulitan atau masalah dalam melakukan pembayaran di website kami."
                          . "<br><br>"
                        . "</td>"
                      . "</tr>"
                      . "<tr>"
                        . "<td class='wrapper last' style='background: url(".base_url()."themes/antavaya/images/back-foot.png) no-repeat center center; color: white'>"
                          . "<table class='twelve columns' style='font-size:10px;FONT-FAMILY:sans-serif'>"
                            . "<tr>"
                              . "<th style='padding-left:5%'>Contact Center 24 jam <br>+6221 2922 7888 <br>cs@antavaya.com <br></th>"
                              . "<th style='padding-left:7%'>Tour inquiries <br>+6221 625 3919 <br>+6221 386 2747 <br>tour@antavaya.com </th>"
                              . "<th style='padding-left:8%'>Complaint & compliment <br>customercare@antavaya.com</th>"
                            . "</tr>"
                          . "</table>"
                        . "</td>"
                      . "</tr>"
                    . "</table>"
                  . "</td>"
                . "</tr>"
              . "</table>"
            . "</center>"
          . "</td>"
        . "</tr>"
        . "</table>"
        . "</body>"
        . "</html>"
        . "";
     // print $isihtml; die;
      $this->email->message($isihtml);  
  //die;
      print date("Y-m-d H:i:s")." - ".$bk->id_tiket_book;
//      if($this->email->send()){
        $kirim_book = array(
          "alert_email"      => 2
        );
        print " email berhasil";
//      }
//      else{
//        $kirim_book = array(
//          "alert_email"      => 1
//        );
//        print " email gagal";
//      }
//      print "<br />";
//      $this->global_models->update("tiket_book", array("id_tiket_book" => $bk->id_tiket_book),$kirim_book);
    }
    if(!$book){
      print "tidak ada data <br />";
    }
    die;
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */