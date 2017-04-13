<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  private function curl_mentah($pst, $url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $pst);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function curl_mentah_with_cookie($pst, $url, $cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $pst);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
    $hasil = curl_exec($ch);
    curl_close($ch);
    return $hasil;
  }
  
  private function curl_mentah_with_cookie_ref($url, $url1, $cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_REFERER, $url1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function curl_mentah_with_cookie_ref_post($post, $url, $url1, $cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_REFERER, $url1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function olah_waktu($tgl, $waktu, $compare = 0){
    $bulan = array(
      date("F", strtotime("2015-01-01")) => '01',
      date("F", strtotime("2015-02-01")) => '02',
      date("F", strtotime("2015-03-01")) => '03',
      date("F", strtotime("2015-04-01")) => '04',
      date("F", strtotime("2015-05-01")) => '05',
      date("F", strtotime("2015-06-01")) => '06',
      date("F", strtotime("2015-07-01")) => '07',
      date("F", strtotime("2015-08-01")) => '08',
      date("F", strtotime("2015-09-01")) => '09',
      date("F", strtotime("2015-10-01")) => '10',
      date("F", strtotime("2015-11-01")) => '11',
      date("F", strtotime("2015-12-01")) => '12',
    );
    $pecah = explode(" ", $tgl);
  //    $this->debug($pecah, true);
    $time_tgl = date("Y-m-d", strtotime($pecah[2]."-".$bulan[$pecah[1]]."-".$pecah[0]));
    $time = substr($waktu, 0, 2).":".substr($waktu, -2);
  //    $this->debug($time_tgl." ".$time, true);
    if($compare <> 0 AND $compare > strtotime($time_tgl." ".$time)){
      $time_tgl = date("Y-m-d", strtotime("+1 day", strtotime($pecah[2]."-".$bulan[$pecah[1]]."-".($pecah[0]))));
    }
    return strtotime($time_tgl." ".$time);
  }
  
  private function bookstep1_set_cookie_private($link1st, &$cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link1st);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    
    return $hasil_1;
  }
  
  private function bookstep2_send_book_private($link1st, $kirim, &$cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link1st);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_REFERER, $link1st);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function bookstep3_set_session_private($link2nd, &$cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link2nd);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_REFERER, $link2nd);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function bookstep4_confirm_book_private($link2nd, $kirim, &$cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link2nd);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_REFERER, $link2nd);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $kirim);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function bookstep5_wait_private($link2nd, $link3rd, &$cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link3rd);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_REFERER, $link2nd);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function bookstep6_final_private($link3rd, $link4th, &$cookie_jar){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link4th);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_REFERER, $link3rd);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    $hasil_1 = curl_exec($ch);
    curl_close($ch);
    return $hasil_1;
  }
  
  private function format_rhrg($id_website_flight_temp, $adult, $id_tiket_book, $child = 0, $infant = 0, $flight_ke = 1){
    $this->global_models->get_connect("terminal");
    $website_flight_temp = $this->global_models->get("website_flight_temp", array("id_website_flight_temp" => $id_website_flight_temp));
    $website_flight_temp_items = $this->global_models->get("website_flight_temp_items", array("id_website_flight_temp" => $id_website_flight_temp));
    $this->global_models->get_connect("default");
    
    $kirim_tiket_flight = array(
      "id_tiket_book"         => $id_tiket_book,
      "flight"                => $flight_ke,
      "dari"                  => $website_flight_temp[0]->dari,
      "ke"                    => $website_flight_temp[0]->ke,
      "stop"                  => $website_flight_temp[0]->stop,
      "departure"             => $website_flight_temp[0]->departure,
      "arrive"                => $website_flight_temp[0]->arrive,
      "maskapai"              => $website_flight_temp[0]->maskapai,
      "img"                   => $website_flight_temp[0]->img,
      "tanggal"               => date("Y-m-d H:i:s"),
      "create_date"           => date("Y-m-d H:i:s"),
    );
    $id_tiket_flight = $this->global_models->insert("tiket_flight", $kirim_tiket_flight);
    
    $send_flight_code = $send_dari = $send_ke = $send_dept = $send_arrive = $send_class = $send_maskapai = "";
    foreach($website_flight_temp_items AS $kj => $wfti){
      
      $kirim_tiket_flight_items[] = array(
        "id_tiket_flight"       => $id_tiket_flight,
        "id_website_class_code" => $website_flight_temp[0]->maskapai.$wfti->class,
        "flight_no"             => $wfti->flight_no,
        "dari"                  => $wfti->dari,
        "ke"                    => $wfti->ke,
        "departure"             => $wfti->departure,
        "arrive"                => $wfti->arrive,
        "create_date"           => date("Y-m-d H:i:s")
      );
      
      if($kj > 0){
        $send_flight_code   .= "<BR><BR>";
        $send_dari          .= "<BR><BR>";
        $send_ke            .= "<BR><BR>";
        $send_dept          .= "<BR><BR>";
        $send_arrive        .= "<BR><BR>";
        $send_class         .= "<BR><BR>";
      }
      $send_flight_code   .= $wfti->flight_no;
      $send_dari          .= $this->global_models->array_kota($wfti->dari);
      $send_ke            .= $this->global_models->array_kota($wfti->ke);
      $send_dept          .= date("Hi", strtotime($wfti->departure));
      $send_arrive        .= date("Hi", strtotime($wfti->arrive));
      $send_class         .= $wfti->class;
    }
    
    $this->global_models->insert_batch("tiket_flight_items", $kirim_tiket_flight_items);
    
    $child_sign = $harga_child = $diskon_child = $infant_sign = 0;
    $diskon_adult = $this->global_models->diskon($website_flight_temp[0]->maskapai, ($adult * $website_flight_temp[0]->price));
    if($child > 0){
      $child_sign = $child;
      $harga_child = $child_sign * $website_flight_temp[0]->child;
      $diskon_child = $this->global_models->diskon($website_flight_temp[0]->maskapai, $harga_child);
    }
    if($infant > 0)
      $infant_sign = $infant;
    
    $hasil = "{$send_flight_code}/{$send_dari}/{$send_ke}/{$send_dept}/{$send_arrive}/".($adult*$website_flight_temp[0]->price)."/"
      . date("d F Y", strtotime($website_flight_temp[0]->departure))."/{$adult}/{$send_class}/{$website_flight_temp[0]->maskapai}/"
      . ($infant_sign*$website_flight_temp[0]->infant)."/{$infant_sign}/{$child_sign}/{$harga_child}/"
      . ($diskon_adult+$diskon_child)."/{$diskon_adult}/{$diskon_child}";
    
    return $hasil;
  }
  
  private function olah_book_and_time($hasil){
    $hasil1 = explode("Booking Code", $hasil);
    $cari_book = explode("B>", $hasil1[1]);
    $fix_book = explode("<", $cari_book[1]);
    if($hasil1[2]){
      $cari_book2nd = explode("B>", $hasil1[2]);
      $fix_book2nd  = explode("<", $cari_book2nd[1]);
    }
    
    $time = explode("Time Limit", $hasil1[1]);
    $time1 = explode(":", $time[1]);
    $fix_time = trim($time1[1]).":".$time1[2].":00";
    
    $price1 = explode("Price", $hasil);
    $price2 = explode("IDR", $price1[1]);
    $price = explode(":", $price2[0]);
    if($price1[2]){
//      $price2nd1 = explode("Price", $hasil1[2]);
      $pricert = explode("IDR", $price1[2]);
      $price2nd = explode(":", $pricert[0]);
    }
    
    $diskon1 = explode("Discount", $hasil);
    $diskon2 = explode("IDR", $diskon1[1]);
    $diskon = explode(":", $diskon2[0]);
    
    return array(
      "book"    => $fix_book[0],
      "book2nd" => $fix_book2nd[0],
      "limit"   => $fix_time,
      "price"   => trim(str_replace(",", "", $price[1])),
      "price2nd"=> trim(str_replace(",", "", $price2nd[1])),
      "diskon"  => trim(str_replace(",", "", $diskon[1])),
    );
  }
  
  /**
   * panding
   */
  private function cek_bo_book($id_tiket_book){
    
    $cookie_jar = tempnam('/tmp','cookie');
    
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
    
    $book = $this->global_models->get("tiket_book", array("status" => 1, "id_tiket_book" => $id_tiket_book));
    if($book){
      
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
      curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
      curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
      $hasil = curl_exec($ch);
      curl_close($ch);
      
      $step1st = explode($book[0]->email, $hasil);
      $step2nd = explode($book[0]->email, $hasil);
    }
    
    return array(0 => array(
      "book"    => $fix_book[0],
      "book2nd" => $fix_book2nd[0],
      "limit"   => $fix_time,
      "price"   => trim(str_replace(",", "", $price[1])),
      "price2nd"=> trim(str_replace(",", "", $price2nd[1])),
      "diskon"  => trim(str_replace(",", "", $diskon[1])),
    ));
  }
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   * @param int $adl Jumlah Dewasa
   * @param int $chd Jumlah Anak
   * @param int $inf Jumlah Bayi
   * @param int $id_flight ID Flight
   * @param int $id_flight2 ID Flight Kembali
   */
  function bookstep1_set_cookie(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){

      $link2nd = "http://tiket.antavaya.com/index.php?option=com_jadwal&view=jadwal&layout=hsl&Itemid=8&act=step2";

      $data = $this->curl_mentah(array(), "http://tiket.antavaya.com/widgets.php");
      $dt_temp = explode('tnow"', $data);
      $dt_temp = explode('"', $dt_temp[2]);
      $link1st = "http://tiket.antavaya.com/index.php?option=com_jadwal&view=jadwal&layout=hsl&itemid=8&c={$dt_temp[1]}&err=&agen=";

      $cookie_jar = tempnam('/tmp','cookie');
      $this->bookstep1_set_cookie_private($link1st, $cookie_jar);
      
      if($pst['id_users'] <= 0)
        $pst['id_users'] = NULL;
      
      $kirim_tiket_book = array(
        "id_users"          => $pst['id_users'],
        "harga_bayar"       => $pst['harga_bayar'],
        "harga_normal"      => ($pst['harga_bayar'] + $pst['diskon']),
        "diskon"            => $pst['diskon'],
        "first_name"        => $pst['first_name'],
        "last_name"         => $pst['last_name'],
        "tanggal"           => date("Y-m-d H:i:s"),
        "status"            => 1,
        "telphone"          => $pst['telphone'],
        "email"             => $pst['email'],
        "create_by_users"   => $users[0]->id_users,
        "create_date"       => date("Y-m-d H:i:s"),
      );
      $id_tiket_book = $this->global_models->insert("tiket_book", $kirim_tiket_book);

      $kirim_book = array(
        'rhrg'		=> $this->format_rhrg( $pst['id_flight'], $pst['adl'], $id_tiket_book, $pst['chd'], $pst['inf'], 1),
        'next'		=> 'Book'
      );

      $this->global_models->get_connect("terminal");
      $flight = $this->global_models->get_field("website_flight_temp", "flight_no", array("id_website_flight_temp" => $pst['id_flight']));
      $this->global_models->get_connect("default");
      if($pst['pp'] == 2){
        $kirim_book['rhrg2'] = $this->format_rhrg($pst['id_flight2'], $pst['adl'], $id_tiket_book, $pst['chd'], $pst['inf'],2);
        $this->global_models->get_connect("terminal");
        $flight2 = $this->global_models->get_field("website_flight_temp", "flight_no", array("id_website_flight_temp" => $pst['id_flight2']));
        $this->global_models->get_connect("default");
      }
      $this->global_models->get_connect("default");

      $this->bookstep2_send_book_private($link1st, $kirim_book, $cookie_jar);

      $hasil = $this->bookstep3_set_session_private($link2nd, $cookie_jar);
      
//      $this->debug("<textarea>{$hasil}</textarea>", true);

      if(strstr($hasil,$flight) AND $flight){
        if($pst['pp'] == 2){
          if(strstr($hasil,$flight2) AND $flight2){
            $kirim = array(
              'status'        => 2,
              'key'           => $this->encrypt->encode($cookie_jar),
              'id_tiket_book' => $this->encrypt->encode($id_tiket_book),
            );
          }
          else{
            $kirim = array(
              'status'  => 5,
              'note'    => 'Booking Pulang Pergi Gagal'
            );
          }
        }
        else{
          $kirim = array(
            'status'    => 2,
            "key"       => $this->encrypt->encode($cookie_jar),
            'id_tiket_book' => $this->encrypt->encode($id_tiket_book),
          );
        }
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Booking Gagal'
        );
      }
    }
    else{
      $kirim = array(
        'status'  => 1,
        'note'    => 'Tidak Ada Akses'
      );
    }
    $this->global_models->get_connect("default");
    print json_encode($kirim);
    die;
  }
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   * @param string $key Session Key
   * @param json $adl Penumpang Dewasa
   * @param json $chl Penumpang Anak
   * @param json $inf Penumpang Bayi
   * @param string $telphone Telphone Pemesan
   * @param string $email Email Pemesan
   */
  function bookstep2_confirm_book(){
    $pst = $this->input->post();
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){

      $link2nd = "http://tiket.antavaya.com/index.php?option=com_jadwal&view=jadwal&layout=hsl&Itemid=8&act=step2";
      $link3rd = "http://tiket.antavaya.com/components/com_jadwal/views/jadwal/tmpl/waiting.php?act=Please";
      $cookie_jar = $this->encrypt->decode($pst['key']);
      $id_tiket_book = $this->encrypt->decode($pst['id_tiket_book']);

      $adult = json_decode($pst['adl']);
//      $this->debug($pst, true);
      foreach ($adult AS $k => $adl){
        $kirim_tiket_passenger[] = array(
          "id_tiket_book"         => $id_tiket_book,
          "first_name"            => $adl->first_name,
          "last_name"             => $adl->last_name,
          "title"                 => $adl->title,
          "tanggal_lahir"         => $adl->tahun."-".$adl->bulan."-".$adl->tanggal,
          "type"                  => 1,
          "pax"                   => "",
          "create_by_users"       => $users[0]->id_users,
          "create_date"           => date("Y-m-d H:i:s"),
        );
        $f = "";
        if($k >= 1){
          $f = $k + 1;
        }
        $kirim_passenger["dtitle{$f}"]  = $adl->title;
        $kirim_passenger["tfirst{$f}"]  = $adl->first_name;
        $kirim_passenger["tlast{$f}"]   = $adl->last_name;
        $kirim_passenger["dtgl{$f}"]    = $adl->tanggal;
        $kirim_passenger["dbln{$f}"]    = $adl->bulan;
        $kirim_passenger["dthn{$f}"]    = $adl->tahun;
        $kirim_passenger["ffp{$f}"]     = "";
      }
      
      $child = json_decode($pst['chd']);
      if($child){
        foreach($child AS $c => $chd){
          $kirim_tiket_passenger[] = array(
            "id_tiket_book"         => $id_tiket_book,
            "first_name"            => $chd->first_name,
            "last_name"             => $chd->last_name,
            "title"                 => $chd->title,
            "tanggal_lahir"         => $chd->tahun."-".$chd->bulan."-".$chd->tanggal,
            "type"                  => 2,
            "pax"                   => "",
            "create_by_users"       => $users[0]->id_users,
            "create_date"           => date("Y-m-d H:i:s"),
          );
          $tr = $c + 1;
          $kirim_passenger["dtitlec{$tr}"]  = $chd->title;
          $kirim_passenger["tfirstc{$tr}"]  = $chd->first_name;
          $kirim_passenger["tlastc{$tr}"]   = $chd->last_name;
          $kirim_passenger["dtglc{$tr}"]    = $chd->tanggal;
          $kirim_passenger["dblnc{$tr}"]    = $chd->bulan;
          $kirim_passenger["dthnc{$tr}"]    = $chd->tahun;
          $kirim_passenger["ffpc{$tr}"]     = "";
        }
      }

      $infant = json_decode($pst['inf']);
      if($infant){
        foreach($infant AS $i => $inf){
          $kirim_tiket_passenger[] = array(
            "id_tiket_book"         => $id_tiket_book,
            "first_name"            => $inf->first_name,
            "last_name"             => $inf->last_name,
            "title"                 => $inf->title,
            "tanggal_lahir"         => $inf->tahun."-".$inf->bulan."-".$inf->tanggal,
            "type"                  => 3,
            "pax"                   => $inf->pax,
            "create_by_users"       => $users[0]->id_users,
            "create_date"           => date("Y-m-d H:i:s"),
          );
          $tl = $i + 1;
          $kirim_passenger["dtitlei{$tl}"]  = $inf->title;
          $kirim_passenger["tfirsti{$tl}"]  = $inf->first_name;
          $kirim_passenger["tlasti{$tl}"]   = $inf->last_name;
          $kirim_passenger["dtgli{$tl}"]    = $inf->tanggal;
          $kirim_passenger["dblni{$tl}"]    = $inf->bulan;
          $kirim_passenger["dthni{$tl}"]    = $inf->tahun;
          $kirim_passenger["dpax{$tl}"]     = $inf->pax;
        }
      }
      
      $kirim_passenger["thp2"]      = $pst['telphone'];
      $kirim_passenger["tmail"]     = $pst['email'];
      $kirim_passenger["submit"]    = "Book";
      $kirim_passenger["checkbox1"] = "on";
      $kirim_passenger["tadl"]      = count($adult);
//      $this->debug($kirim_tiket_passenger, true);
      $this->global_models->insert_batch("tiket_passenger", $kirim_tiket_passenger);
      
      $this->bookstep4_confirm_book_private($link2nd, $kirim_passenger, $cookie_jar);
      
      $hasil = $this->bookstep5_wait_private($link2nd, $link3rd, $cookie_jar);
      file_put_contents("files/antavaya/logconfirmbook/".date("YmdHis").".html", $hasil);
        $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from("no-reply@antavaya.com","AntaVaya Online");
        $this->email->to('nugroho.budi@antavaya.com');
        $this->email->subject('System');
        $isihtml = "Log data MiddleSystem<br> Module Json, function bookstep2_confirm_book()<br> lokasi log files/antavaya/logconfirmbook";
        $this->email->message($isihtml);  
        $this->email->send();
//      print $hasil;die;
      if(strstr($hasil,"Sistem kami sedang melakukan reservasi ke maskapai penerbangan")){
        $kirim = array(
          'status'  => 2,
          'key'     => $this->encrypt->encode($cookie_jar),
          'id_tiket_book' => $this->encrypt->encode($id_tiket_book),
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Sistem Reservasi Gagal"
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
   * @param int $ke Generate Maskapai Ke
   */
  function bookstep3_final(){
    $pst = $this->input->post();
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){

      $link3rd = "http://tiket.antavaya.com/components/com_jadwal/views/jadwal/tmpl/waiting.php?act=Please";
      $link4th = "http://tiket.antavaya.com/index.php?option=com_jadwal&view=jadwal&layout=hsl&Itemid=8&act=step3&cek=0";
      $cookie_jar = $this->encrypt->decode($pst['key']);
      $id_tiket_book = $this->encrypt->decode($pst['id_tiket_book']);

      $terima_kasih = $this->bookstep6_final_private($link3rd, $link4th, $cookie_jar);
      file_put_contents("files/antavaya/logbook/".date("YmdHis").".html", $terima_kasih);
        $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from("no-reply@antavaya.com","AntaVaya Online");
        $this->email->to('nugroho.budi@antavaya.com');
        $this->email->subject('System');
        $isihtml = "Log data MiddleSystem<br> Module Json, function bookstep3_final()<br> lokasi log files/antavaya/logbook/";
        $this->email->message($isihtml);  
        $this->email->send();
//      file_put_contents("coba.html", $terima_kasih);
//      $this->debug("<textarea>{$terima_kasih}</textarea>", true);
      $book_code = $this->olah_book_and_time($terima_kasih);
//      print json_encode($book_code);die;
      if($book_code['book']){
        $update_book = array(
          "book_code"   => $book_code['book'],
          "harga_bayar" => ($book_code['price']+$book_code['price2nd']),
          "diskon"      => $book_code['diskon'],
          "timelimit"   => $book_code['limit'],
        );
        $this->global_models->update("tiket_book", array("id_tiket_book" => $id_tiket_book), $update_book);
        $this->global_models->update("tiket_flight", array("id_tiket_book" => $id_tiket_book, "flight" => 1), array("book_code" => $book_code['book'], "harga_jual" => $book_code['price']));
        $this->global_models->update("tiket_flight", array("id_tiket_book" => $id_tiket_book, "flight" => 2), array("book_code" => $book_code['book2nd'], "harga_jual" => $book_code['price2nd']));
          
        $kirim = array(
          'status'  => 2,
          "book"    => $book_code['book'],
          "book2nd" => $book_code['book2nd'],
          "limit"   => $book_code['limit'],
          "price"   => $book_code['price'],
          "price2nd"=> $book_code['price2nd'],
          "diskon"  => $book_code['diskon'],
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => "Book Gagal"
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
   * @param int $ke Generate Maskapai Ke
   * @param date $tgl Tanggal Berangkat Format d F Y
   * @param date $tglkembali Tanggal Kembali Format d F Y
   * @param string $asal Kode Kota Asal
   * @param string $tujuan Kode Kota Tujuan
   * @param int $adl Jumlah Dewasa
   * @param int $chd Jumlah Anak
   * @param int $inf Jumlah Bayi
   */
  function get_flight(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->get_connect("terminal");
      $this->global_models->query("DELETE FROM website_flight_temp WHERE create_date < '".date("Y-m-d H:i:s", (strtotime("now") - (30*60)))."'");
      $max = $this->global_models->get_field("website_flight_temp", "MAX(id_website_flight_temp)");
      $max += 1;
      $this->global_models->query("ALTER TABLE website_flight_temp AUTO_INCREMENT = ".$max);
      $this->global_models->query("DELETE FROM website_flight_temp_items WHERE create_date < '".date("Y-m-d H:i:s", (strtotime("now") - (30*60)))."'");
      $this->global_models->get_connect("default");
      $kirim = array(
        "asal"              => $pst['asal'],
        "tujuan"            => $pst['tujuan'],
        "tanggalpergi"      => $pst['tgl'],
        "tanggalkembali"    => $pst['tglkembali'],
        "adl"               => $pst['adl'],
        "chd"               => $pst['chd'],
        "inf"               => $pst['inf'],
        "sig"               => "",
      );
//      $this->debug($kirim, true);
      $data = $this->curl_mentah($kirim, "http://tiket.antavaya.com/data/dataxml{$pst['ke']}.php");
      $data_array = json_decode($data);
      file_put_contents("files/antavaya/loggetflight/".date("YmdHis").".html", $data_array);
       $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from("no-reply@antavaya.com","AntaVaya Online");
        $this->email->to('nugroho.budi@antavaya.com');
        $this->email->subject('System');
        $isihtml = "Log data MiddleSystem<br> Module Json function get_flight()<br> lokasi log file:files/antavaya/loggetflight/";
        $this->email->message($isihtml);  
        $this->email->send();
//      $this->debug($data_array, true);
      foreach($data_array AS $f => $da){
        if($da->Harga > 0 AND !strpos($da->Harga, "@")){
          $flight       = explode("@", $da->NoFlt);
          $departure    = explode("@", $da->JamDepart);
          $arrive       = explode("@", $da->JamArrive);
          $class        = explode("@", $da->Class);
          $dari         = explode("@", $da->dari);
          $tujuan       = explode("@", $da->ke);
          
          if($dari[0] <> $pst['asal']){
            $tgl_cetak        = $pst['tglkembali'];
            $tglkembali_cetak = $pst['tgl'];
          }
          else{
            $tgl_cetak        = $pst['tgl'];
            $tglkembali_cetak = $pst['tglkembali'];
          }
          
          $keberangkatan_inti = $this->olah_waktu($tgl_cetak, $departure[0]);
          if(substr($flight[0], 0, 2) != "GA"){
            $hasil[$f] = array(
              "harga"     => ($da->Harga/$pst['adl']),
              "img"       => $da->img,
              "child"     => ($da->HargaChild/$pst['chd']),
              "infant"    => ($da->HargaInfant/$pst['inf']),
              "stop"      => count($flight),
              "dari"      => $dari[0],
              "ke"        => $tujuan[(count($flight)-1)],
              "maskapai"  => substr($flight[0], 0, 2),
            );
          }
          else{
            $hasil[$f] = array(
              "harga"     => $da->Harga,
              "img"       => $da->img,
              "child"     => $da->HargaChild,
              "infant"    => $da->HargaInfant,
              "stop"      => count($flight),
              "dari"      => $dari[0],
              "ke"        => $tujuan[(count($flight)-1)],
              "maskapai"  => substr($flight[0], 0, 2),
            );
          }

          $kirim = array(
            "dari"                  => $hasil[$f]['dari'],
            "ke"                    => $hasil[$f]['ke'],
            "stop"                  => $hasil[$f]['stop'],
            "price"                 => $hasil[$f]['harga'],
            "child"                 => $hasil[$f]['child'],
            "infant"                => $hasil[$f]['infant'],
            "maskapai"              => $hasil[$f]['maskapai'],
            "img"                   => $hasil[$f]['img'],
            "flight_no"             => $flight[0],
            "status"                => 1,
            "type"                  => $ke,
            "create_date"           => date("Y-m-d H:i:s"),
          );
          $this->global_models->get_connect("terminal");
          $id_website_flight_temp[$f] = $this->global_models->insert("website_flight_temp", $kirim);
          $this->global_models->get_connect("default");
          
          $diskon_maskapai = $this->global_models->diskon($hasil[$f]['maskapai'], (($hasil[$f]['harga']*$pst['adl'])+($hasil[$f]['child']*$pst['chd'])), 
            ($pst['adl'] + $pst['chd']));
          $hasil[$f]['diskon_maskapai'] = $diskon_maskapai;
          
          $hasil[$f]['total_harga'] = (($hasil[$f]['harga']*$pst['adl'])+($hasil[$f]['child']*$pst['chd'])+($hasil[$f]['infant']*$pst['inf']));
          
          
          $diskon_payment = $this->global_models->get_query("SELECT *"
            . " FROM tiket_discount"
            . " WHERE ('".date("Y-m-d")."' BETWEEN mulai AND akhir) AND status = 1");
          if($diskon_payment){
            foreach($diskon_payment AS $dp){
              $nilai_diskon_payment = 0;
              if($dp->type == 1){
                $nilai_diskon_payment = $dp->nilai/100 * (($hasil[$f]['harga']*$pst['adl'])+($hasil[$f]['child']*$pst['chd']));
              }
              else{
                $nilai_diskon_payment = $dp->nilai * ($pst['adl'] + $pst['chd']);
              }
              $hasil[$f]['diskon_payment'][] = array(
                "channel"     => $dp->channel,
                "nilai"       => $nilai_diskon_payment
              );
            }
          }

          $keberangkatan = array();
          foreach($flight AS $t => $noflight){
            $keberangkatan[$t] = $this->olah_waktu($tgl_cetak, $departure[$t], $keberangkatan[($t-1)]);
            
            $hasil[$f]['role'][$t] = array(
              "departure"         => date("Y-m-d H:i:s", $keberangkatan[$t]),
              "arrive"            => date("Y-m-d H:i:s", $this->olah_waktu($tgl_cetak, $arrive[$t], $keberangkatan[$t])),
              "flight"            => $noflight,
              "class"             => $class[$t],
              "dari"              => $dari[$t],
              "ke"                => $tujuan[$t],
            );

            $kirim_items = array(
              "id_website_flight_temp"  => $id_website_flight_temp[$f],
              "flight_no"               => $noflight,
              "dari"                    => $dari[$t],
              "ke"                      => $tujuan[$t],
              "class"                   => $class[$t],
              "departure"               => $hasil[$f]['role'][$t]['departure'],
              "arrive"                  => $hasil[$f]['role'][$t]['arrive'],
              "create_date"             => date("Y-m-d H:i:s"),
            );
            $this->global_models->get_connect("terminal");
            $id_website_flight_temp_items = $this->global_models->insert("website_flight_temp_items", $kirim_items);
            $this->global_models->get_connect("default");

          }
      //    $hasil[$f]["departure"] = "ju";
      //    $hasil[$f]["arrive"] = "ft";
          $hasil[$f]["id_flight"] = $id_website_flight_temp[$f];
          $hasil[$f]["departure"] = date("Y-m-d H:i:s", $keberangkatan[0]);
          $hasil[$f]["arrive"] = $hasil[$f]['role'][(count($flight)-1)]["arrive"];
          $hasil[$f]["status"] = 2;
          $kirim_update = array(
            "departure" => $hasil[$f]["departure"],
            "arrive"    => $hasil[$f]["arrive"],
          );
          $this->global_models->get_connect("terminal");
          $this->global_models->update("website_flight_temp", array("id_website_flight_temp" => $id_website_flight_temp[$f]), $kirim_update);
          $this->global_models->get_connect("default");
        }
      }
      if($hasil){
        $kirim = $hasil;
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data tidak ada'
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
   * @param int $ke Generate Maskapai Ke
   * @param date $tgl Tanggal Berangkat Format d F Y
   * @param date $tglkembali Tanggal Kembali Format d F Y
   * @param string $asal Kode Kota Asal
   * @param string $tujuan Kode Kota Tujuan
   * @param int $adl Jumlah Dewasa
   * @param int $chd Jumlah Anak
   * @param int $inf Jumlah Bayi
   */
  function cek_diskon_payment(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tiket_diskon = $this->global_models->get_query("SELECT *"
        . " FROM tiket_discount"
        . " WHERE ('{$pst['tanggal']}' BETWEEN mulai AND akhir) AND status = 1");
      foreach($tiket_diskon AS $td){
        $logo = array(
          1 => array("BCA", base_url()."files/logo/bca.png"),
          2 => array("Credit Card Mega", base_url()."files/logo/mega.png"),
          3 => array("Visa/Master", base_url()."files/logo/visa.png"),
          4 => array("Mega Priority", base_url()."files/logo/mega.png"),
        );
        if($td->nilai > 0){
          $olah_tiket_diskon_payment[$td->channel] = array(
            "id"                    => $td->id_tiket_discount,
            "diskon"                => $td->nilai,
            "type"                  => $td->type,
            "logo"                  => $logo[$td->channel][1],
            "name"                  => $logo[$td->channel][0],
          );
        }

      }
        
      if($olah_tiket_diskon_payment){
        $kirim = array(
          'status'  => 2,
          'diskon'  => $olah_tiket_diskon_payment
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data tidak ada'
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
   * @param int $ke Generate Maskapai Ke
   * @param date $tgl Tanggal Berangkat Format d F Y
   * @param date $tglkembali Tanggal Kembali Format d F Y
   * @param string $asal Kode Kota Asal
   * @param string $tujuan Kode Kota Tujuan
   * @param int $adl Jumlah Dewasa
   * @param int $chd Jumlah Anak
   * @param int $inf Jumlah Bayi
   */
  function cek_diskon_destination(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $code = explode("-", $pst['code']);
      $where_code = "destinationcode = '{$pst['code']}'";
      $tiket_diskon = $this->global_models->get_query("SELECT *"
        . " FROM tiket_discount_destination"
        . " WHERE ('{$pst['tanggal']}' BETWEEN mulai AND akhir) AND status = 1"
        . " AND ({$where_code})");
      foreach($tiket_diskon AS $td){
        if($td->nilai > 0){
          $olah_tiket_diskon_dest[$td->maskapai] = array(
            "id"                    => $td->id_tiket_discount_destination,
            "diskon"                => $td->nilai,
            "type"                  => $td->type,
            "logo"                  => base_url()."files/logo/dn.png",
            "name"                  => $td->title,
            "code"                  => $td->destinationcode,
          );
        }

      }
        
      if($olah_tiket_diskon_dest){
        $kirim = array(
          'status'  => 2,
          'diskon'  => $olah_tiket_diskon_dest
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data tidak ada'
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
   * @param string $book_code Book Code
   */
  function get_detail_tiket_book(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $tiket_book = $this->global_models->get("tiket_book", array("book_code" => $pst['book_code']));
      if($tiket_book){
        $olah_tiket_book = array(
          "harga_bayar"           => $tiket_book[0]->harga_bayar,
          "harga_normal"          => $tiket_book[0]->harga_normal,
          "diskon"                => $tiket_book[0]->diskon,
          "waktu"                 => $tiket_book[0]->tanggal,
          "timelimit"             => $tiket_book[0]->timelimit,
          "status"                => $tiket_book[0]->status,
        );
        $olah_tiket_pemesan = array(
          "id_users"              => $tiket_book[0]->id_users,
          "first_name"            => $tiket_book[0]->first_name,
          "last_name"             => $tiket_book[0]->last_name,
          "phone"                 => $tiket_book[0]->telphone,
          "email"                 => $tiket_book[0]->email,
        );
        
        $tiket_diskon = $this->global_models->get_query("SELECT *"
          . " FROM tiket_discount"
          . " WHERE ('{$tiket_book[0]->tanggal}' BETWEEN mulai AND akhir) AND status = 1");
        foreach($tiket_diskon AS $td){
          if($td->type == 1){
            $diskon = $td->nilai/100 * $tiket_book[0]->harga_bayar;
          }
          else{
            $diskon = $td->nilai;
          }
          $logo = array(
            1 => array("BCA", base_url()."files/logo/bca.png"),
            2 => array("Credit Card Mega", base_url()."files/logo/mega.png"),
            3 => array("Visa/Master", base_url()."files/logo/visa.png"),
            4 => array("Mega Priority", base_url()."files/logo/mega.png"),
          );
          $olah_tiket_diskon_payment[$td->channel] = array(
            "id"                    => $td->id_tiket_discount,
            "diskon"                => $diskon,
            "logo"                  => $logo[$td->channel][1],
            "name"                  => $logo[$td->channel][0],
          );
          
        }
        
        $tiket_flight = $this->global_models->get("tiket_flight", array("id_tiket_book" => $tiket_book[0]->id_tiket_book));
        
        foreach($tiket_flight AS $tf){
          $tiket_flight_items = $this->global_models->get("tiket_flight_items", array("id_tiket_flight" => $tf->id_tiket_flight));
          $olah_tiket_flight_items = array();
          foreach($tiket_flight_items AS $tfi){
            $olah_tiket_flight_items[] = array(
              "class_code"        => $tfi->id_website_class_code,
              "flight_no"         => $tfi->flight_no,
              "dari"              => $tfi->dari,
              "ke"                => $tfi->ke,
              "departure"         => $tfi->departure,
              "arrive"            => $tfi->arrive,
            );
          }
          $olah_tiket_flight[] = array(
            "flight"              => $tf->flight,
            "book_code"           => $tf->book_code,
            "issued_no"           => $tf->issued_no,
            "dari"                => $tf->dari,
            "ke"                  => $tf->ke,
            "stop"                => $tf->stop,
            "departure"           => $tf->departure,
            "arrive"              => $tf->arrive,
            "maskapai"            => $tf->maskapai,
            "img"                 => $tf->img,
            "penerbangan"         => $olah_tiket_flight_items,
          );
        }
        
        $tiket_issued = $this->global_models->get("tiket_issued", array("id_tiket_book" => $tiket_book[0]->id_tiket_book));
        if($tiket_issued){
          $olah_tiket_issued = array(
            "harga_bayar"         => $tiket_issued[0]->harga_bayar,
            "diskon"              => $tiket_issued[0]->diskon,
            "tanggal"             => $tiket_issued[0]->tanggal,
            "channel"             => $tiket_issued[0]->channel,
          );
        }
        
        $tiket_passenger = $this->global_models->get("tiket_passenger", array("id_tiket_book" => $tiket_book[0]->id_tiket_book));
        foreach($tiket_passenger AS $tp){
          $olah_tiket_passenger[] = array(
            "title"             => $tp->title,
            "first_name"        => $tp->first_name,
            "last_name"         => $tp->last_name,
            "tanggal_lahir"     => $tp->tanggal_lahir,
            "harga"             => $tp->price,
            "harga_kembali"     => $tp->price2nd,
            "type"              => $tp->type,
            "pax"               => $tp->pax,
          );
        }
        
        $kirim = array(
          'status'          => 2,
          'book'            => $olah_tiket_book,
          'flight'          => $olah_tiket_flight,
          'issued'          => $olah_tiket_issued,
          'passenger'       => $olah_tiket_passenger,
          'pemesan'         => $olah_tiket_pemesan,
          'diskon_payment'  => $olah_tiket_diskon_payment,
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => 'Data tidak ada, kemungkinan Book Code salah'
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
  
  function issued(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    $channel = array(
      1 => "BCA",
      2 => "Mega CC",
      3 => "Visa/Master",
      4 => "Mega Priority",
      5 => "Mandiri ClickPay"
    );
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $post = array(
        "usernm" 	=> "tiketantavaya",
        "userpas"	=> "anta888",
        "login"   => "Login"
      );
      $url = "http://tiket.antavaya.com/rpttkt/tktm.php";
      $url1 = "http://tiket.antavaya.com/rpttkt/tiketinguserlog.php";
      $cookie_jar = tempnam('/tmp','cookie');
      
      $book_flight = $this->global_models->get_query("SELECT A.id_tiket_flight, A.book_code, A.id_tiket_book, B.diskon"
        . " FROM tiket_flight AS A"
        . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
        . " WHERE B.book_code = '{$pst['book_code']}'"
        . " GROUP BY A.book_code");
      
      $data = $this->curl_mentah_with_cookie($post, $url, $cookie_jar);
      if(strpos($data, "rpttkt/tiketinguserlog.php")){
        $data1 = $this->curl_mentah_with_cookie_ref($url1, $url, $cookie_jar);
//        $this->debug("<textarea>{$data1}</textarea>");
        
        file_put_contents("files/antavaya/logissued1/".date("YmdHis").".html", $data1);
       /* $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from("no-reply@antavaya.com","AntaVaya Online");
        $this->email->to('nugroho.budi@antavaya.com');
        $this->email->subject('System');
        $isihtml = "Log data MiddleSystem<br> Module Json function issued()<br> lokasi log file:files/antavaya/logissued1/";
        $this->email->message($isihtml);  
        $this->email->send(); */
        if(strpos($data1, "PNRCODE")){
          $ft = 0;
          foreach($book_flight AS $bf){
            if($gt == 0){
              $price_passenger = "price";
            }
            else{
              $price_passenger = "price2nd";
            }
            $gt++;
            $post2 = array(
              "pnrcode" 	=> $bf->book_code,
              "tiketing"	=> "Tiketing",
            );
            $data2 = $this->curl_mentah_with_cookie_ref_post($post2, $url1, $url, $cookie_jar);
            file_put_contents("files/antavaya/logissued2/".date("YmdHis").".html", $data2);
            $this->load->library('email');
            $this->email->initialize($this->global_models->email_conf());
            $this->email->from("no-reply@antavaya.com","AntaVaya Online");
            $this->email->to('nugroho.budi@antavaya.com');
            $this->email->subject('System');
            $isihtml = "Log data MiddleSystem<br> Module Json function issued()<br> lokasi log file:files/antavaya/logissued2/";
            $this->email->message($isihtml);  
            $this->email->send();
//            if(strpos(strtolower($data2), "respon ok")){
              if(strpos(strtolower($data2), "tiketing ok")){
                
//                get no tiket
                $status = 2;
                $status_tiket = 3;
                $no_tiket = $this->just_no_tiket($bf->book_code, $bf->id_tiket_book, $bf->id_tiket_flight);
                if($no_tiket){
                  $f = $nta = 0;
                  foreach($no_tiket AS $id_tiket_passenger => $tn){
                    if($f == 0)
                      $tiket_pemesan = $tn['nomor'];
                    $this->global_models->update("tiket_passenger", array("id_tiket_passenger" => $id_tiket_passenger), array("issued_no" => $tn['nomor'], $price_passenger => $tn['harga']));
                    
                    $cek_file = $this->curl_mentah(array(), "http://tiket.antavaya.com/finish/dwnld.php?nmfile={$bf->book_code}");
                     
                    if(strpos("tidak ada", $cek_file)){
                      $cek_file = $this->curl_mentah(array(), "http://tiket.antavaya.com/finish/dwnld.php?nmfile={$bf->book_code}{$tn}");
                      if(strpos("tidak ada", $cek_file)){
                        $this->email_error("issued", 6, "File Tiket Tidak Ada, Issued Ulang {$pst['book_code']}");
                        $status = 6;
                        $status_tiket = 7;
                      }
                    }
                    
                     file_put_contents("files/antavaya/logissuedcekfile/".date("YmdHis").".html", $cek_file);
                      $this->load->library('email');
                      $this->email->initialize($this->global_models->email_conf());
                      $this->email->from("no-reply@antavaya.com","AntaVaya Online");
                      $this->email->to('nugroho.budi@antavaya.com');
                      $this->email->subject('System');
                      $isihtml = "Log data MiddleSystem<br> Module Json function issued()<br> lokasi log file:files/antavaya/logissuedcekfile/";
                      $this->email->message($isihtml);  
                      $this->email->send();
                     
                    $nta += $tn['nta'];
                    $f++;
                  }
                  $this->global_models->update("tiket_flight", array("id_tiket_flight" => $bf->id_tiket_flight), array("issued_no" => $tiket_pemesan, "price" => $nta));
                }
                else{
                  $this->email_error("issued", 4, "Issued Nomor Gagal. Issued Ulang {$pst['book_code']}");
                  $status = 4;
                  $status_tiket = 6;
                }
                
                $update_tiket_book = array(
                  "harga_bayar"       => $pst['harga_bayar'],
                  "cara_bayar"        => $channel[$pst['channel']],
                  "status"            => $status_tiket,
                );
                $this->global_models->update("tiket_book", array("id_tiket_book" => $bf->id_tiket_book), $update_tiket_book);
                
                
                $kirim = array(
                  "status"    => $status,
                  "note"      => "Issued",
                );
              }
              else{
                $this->email_error("issued", 5, "Tiketing Gagal di Web {$pst['book_code']}");
                $kirim = array(
                  "status"    => 5,
                  "note"      => "Tiketing Gagal",
                );
              }
//            }
//            else{
//              $kirim = array(
//                "status"    => 7,
//                "note"      => "Request Gagal",
//              );
//            }
          }
          if($kirim['status'] == 2 OR $kirim['status'] == 4 OR $kirim['status'] == 6){
            $kirim_issued = array(
              "id_tiket_book"         => $book_flight[0]->id_tiket_book,
              "harga_bayar"           => $pst['harga_bayar'],
              "diskon"                => $book_flight[0]->diskon,
              "tanggal"               => date("Y-m-d H:i:s"),
              "status"                => 1,
              "channel"               => $pst['channel'],
              "issued_no"             => $tiket_pemesan,
              "create_by_users"       => $users[0]->id_users,
              "create_date"           => date("Y-m-d H:i:s")
            );

            $id_tiket_issued = $this->global_models->insert("tiket_issued", $kirim_issued);

//                cek diskon
            $this->setup_discount($book_flight[0]->id_tiket_book, $id_tiket_issued, $pst['harga_bayar']);

          }
          
        }
        else{
          $this->email_error("issued", 9, "Halaman Issued Tidak Berhasil {$pst['book_code']}");
          $kirim = array(
            "status"    => 9,
            "note"      => "Akses Gagal",
          );
        }
      }
      else{
        $this->email_error("issued", 3, "Login Web Gagal {$pst['book_code']}");
        $kirim = array(
          'status'  => 3,
          'note'    => 'Akses Gagal'
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
  
  
  public function just_no_tiket($book_code, $id_tiket_book, $id_tiket_flight){
    $cookie_jar = tempnam('/tmp','cookie');
    $this->login_curl($cookie_jar);
    $hasil = $this->set_curl($cookie_jar, $book_code);
    $just_book = explode(">".$book_code."<", $hasil);
    $penumpang = $this->global_models->get("tiket_passenger", array("id_tiket_book" => $id_tiket_book, "type <" => 3));
    $lower_hasil = strtolower($just_book[1]);
    $flight = $this->global_models->get("tiket_flight_items", array("id_tiket_flight" => $id_tiket_flight));
    foreach($penumpang AS $pnm){
//      $tt[] = "<textarea>".$lower_hasil."</textarea>";
      $pecah_nama = explode(strtolower($pnm->first_name." ".$pnm->last_name), $lower_hasil);
      $lower_hasil = $pecah_nama[1];
      $flight_code = "";
      $pecah_flight_code = $pecah_tiket_no = $pecah_tiket_no2 = array();
      
      $harga = explode("right", $pecah_nama[1]);
      $harga_self = explode(">", $harga[1]);
      $harga = explode("</td", $harga_self[1]);
      $hasild[$pnm->id_tiket_passenger]['harga'] = (int)(str_replace(",","",trim($harga[0])));
      
      $nta = explode("</td", $harga_self[3]);
//      $nta = explode(">", $nta[1]);
//      $nta = explode("</", $nta[1]);
      $hasild[$pnm->id_tiket_passenger]['nta'] = (int)(str_replace(",","",trim($nta[0])));
//      $hasild[$pnm->id_tiket_passenger]['nta'] = "<textarea>{$nta[0]}</textarea>";
      
      $harga_pertama += str_replace(",","",trim($harga_penumpang[0]));
      
      foreach($flight AS $flg){
        if(strpos(strtolower($pecah_nama[0]), strtolower($flg->flight_no))){
          $flight_code = strtolower($flg->flight_no);
          break;
        }
      }
      
      $pecah_flight_code = explode($flight_code."</td>", strtolower($pecah_nama[0]));
      $pecah_tiket_no = explode("<td>", $pecah_flight_code[1]);
      $pecah_tiket_no2 = explode("</td>", $pecah_tiket_no[1]);
      if(trim($pecah_tiket_no2[0]))
        $hasild[$pnm->id_tiket_passenger]['nomor'] = trim($pecah_tiket_no2[0]);
    }
//    $this->debug($hasild, true);
    return $hasild;
  }
  
  private function login_curl(&$cookie_jar){
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
  
  private function set_curl($cookie_jar, $book_code){
    
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
  
  function email_error($fungsi, $code, $note){
   // $id_inquiry_costume = $this->global_models->insert("inquiry_costume", $kirim);
      
    $this->load->library('email');
    $config = $this->global_models->email_conf();
    
    $config['priority'] = 1;
//    $this->debug($config, true);
    $this->email->initialize($config);
 
    $this->email->from("no-reply@antavaya.com","Midlle System");
    $this->email->to('nugroho.budi@antavaya.com');
    $this->email->cc('hendri.prasetyo@antavaya.com');
    $this->email->bcc('7inus.5aint@gmail.com');
    // die;
    
    $this->email->subject('Error '.$fungsi);
    $isihtml = "Error Code {$code} ".str_replace("%20", " ", $note);
   
    $this->email->message($isihtml);  
//die;
    $this->email->send();

//    echo $this->email->print_debugger();
    return true;
  }
  
  private function setup_discount($id_tiket_book, $id_tiket_issued, $harga){
    $tiket_book = $this->global_models->get("tiket_book", array("id_tiket_book" => $id_tiket_book));
    $tiket_issued = $this->global_models->get("tiket_issued", array("id_tiket_issued" => $id_tiket_issued));
    $diskon = $this->global_models->get_query("SELECT *"
      . " FROM tiket_discount"
      . " WHERE ('{$tiket_book[0]->tanggal}' BETWEEN mulai AND akhir) AND status = 1"
      . " AND channel = '{$tiket_issued[0]->channel}'");
    if($diskon){
      if($diskon[0]->type == 1){
        $nilai_diskon = $diskon[0]->nilai/100 * ($tiket_book[0]->harga_normal - $tiket_book[0]->diskon);
      }
      else{
        $nilai_diskon = $diskon[0]->nilai;
      }
      $kirim_diskon = array(
        "id_tiket_book"         => $id_tiket_book,
        "id_tiket_issued"       => $id_tiket_issued,
        "id_discount"           => $diskon[0]->id_tiket_discount,
        "type"                  => 1,
        "nilai"                 => $nilai_diskon,
        "status"                => 2,
        "create_by_users"       => $this->session->userdata("id"),
        "create_date"           => date("Y-m-d H:i:s"),
      );
    }
    else{
      $diskon = $this->global_models->get_query("SELECT *"
      . " FROM tiket_discount"
      . " WHERE ('{$tiket_book[0]->tanggal}' BETWEEN mulai AND akhir) AND status = 1"
      . " AND channel = '{$tiket_issued[0]->channel}'");
    }
  }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */