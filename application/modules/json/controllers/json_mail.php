<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_mail extends MX_Controller {
    
  function __construct() {
    $this->load->library('encrypt');
  }
  
  /**
   * TC -> Operation Tour
   */
  
  function request_additional(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
//	   detail tour
      $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,H.name AS own"
        . " ,J.title AS name_store"
        . " ,L.title AS name_store2"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " LEFT JOIN users_channel AS H ON A.id_users = H.id_users"
        
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        
        . " LEFT JOIN store_commited AS K ON A.id_users = K.id_users"
        . " LEFT JOIN store AS L ON L.id_store = K.id_store"
        
        . " WHERE A.kode ='{$pst['code']}'";
        $data = $this->global_models->get_query($sql);
	  
	  if($data[0]->id_product_tour_book){
       
            $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
                $sql2 = "SELECT B.email"
                . " FROM tour_settings_region AS A"
                . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
                . " WHERE A.id_store ='{$data[0]->id_store}' AND A.region ='{$data[0]->sub_category}' ";
                $data_user = $this->global_models->get_query($sql2);
                
              $no = 1;
              foreach ($data_user as $val) {
                 
                  if($no > 1){
                      $email .= ",{$val->email}";
//                      $name_user .= ",".$this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }else{
                       $email .= $val->email;
//                      $name_user .= $this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }
                  $no++;
            }
            
//             $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $data[0]->id_users));

       $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $subject = 'Notifikasi Request Additional TC '.$data[0]->own." [".$data[0]->name_store."]";
        $url = base_url()."inventory/tour-book/book-information/".$pst['code'];
        $link_url = "<a href='{$url}'>{$pst['code']}</a>";
      
       
        $html = "<html>
            <body>
              Dear Team Tour Operation<br><br>
                Bookers dari TC <b>{$data[0]->own} [{$data[0]->name_store}{$data[0]->name_store2}]</b> request additional untuk kode booking customer <b>[{$link_url}]</b>
                <br>Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>
		list request additional :<br>{$pst['note']}<br><br>
        Bila dari Request Additional ini ada tambahan atau pengurangan biaya,<br>
        Team Tour Operation dapat menginput data additional sesuai dengan  kode book customer <b>[{$link_url}]
       <br>
      
            </body>
          </html>";
        if($email !=""){
            $this->send_mail($email, $subject, $html);
        }
//          $data_store = array(
//            "nama_store"            => $nama_store,
//            "email_user"            => $email_user,
//            "nama_tour"             => $data[0]->title,
//            "url"                   => $url
//           
//          );
 
          $kirim = array(
            'status'  => 2,
            'note'    => "",
            
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
  
  function approval_additional(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
	    $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,H.name AS own"
        . " ,J.title AS name_store"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " LEFT JOIN users_channel AS H ON A.id_users = H.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " WHERE A.kode ='{$pst['code']}'";
        $data = $this->global_models->get_query($sql);
	  
	  if($data[0]->id_product_tour_book){
       
           
//           $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
//           $nama_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store));
          
            $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
                $sql2 = "SELECT B.email"
                . " FROM tour_settings_region AS A"
                . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
                . " WHERE A.id_store ='{$data[0]->id_store}' AND A.region ='{$data[0]->sub_category}' ";
                $data_user = $this->global_models->get_query($sql2);
               
              $no = 1;
              foreach ($data_user as $val) {
                 
                  if($no > 1){
                      $email .= ",".$val->email;
//                      $name_user .= ",".$this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }else{
                       $email .= $val->email;
//                      $name_user .= $this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }
                  $no++;
            }
            $additional = $this->global_models->get("product_tour_additional", array("kode" => $pst['kode_additional']));
         
                 $ket = $additional[0]->name." ".number_format($additional[0]->nominal);
             $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));

       $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $subject = 'Notifikasi Status Additional dari TC '.$nama_tc." [".$data[0]->name_store."]";
         $url = base_url()."inventory/tour-book/book-information/".$pst['code'];
        $link_url = "<a href='{$url}'>{$pst['code']}</a>";
      $hasil = array(1 => "Menyetujui", 2 => "Menolak");
       $hasil2 = array(1 => "Di setujui", 2 => "ditolak");
        $html = "<html>
            <body>
              Dear Team Tour Operation<br><br>
                Bookers dari TC <b>{$nama_tc} [{$data[0]->name_store}]</b> telah menginfokan ke customer atas biaya additional yang diajukan dari operation,<br>"
                . " untuk itu customer {$hasil[$pst['status']]}  additional tersebut. <br>"
                . "List additional yang {$hasil2[$pst['status']]}  :<br><b>{$ket}<b><br><br>"
                . "Informasi customer dari kode booking <b>[{$link_url}]</b>
                <br>Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>
		
       
      
            </body>
          </html>";
        if($email !=""){
            $this->send_mail($email, $subject, $html);
        }
//          $data_store = array(
//            "nama_store"            => $nama_store,
//            "email_user"            => $email_user,
//            "nama_tour"             => $data[0]->title,
//            "url"                   => $url
//           
//          );
 
          $kirim = array(
            'status'  => 2,
            'note'    => "",
            
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
  
  function request_discount(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,J.title AS name_store"
        . " ,G.name AS own_commit,G.email AS email_commit" 
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " LEFT JOIN store_commited AS F ON J.id_store = F.id_store"
        . " LEFT JOIN users_channel  AS G ON F.id_users  = G.id_users"    
        . " WHERE A.kode ='{$pst['code']}'";
       $aa = $this->db->last_query();
        $data = $this->global_models->get_query($sql);
        
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
           $no = 1;
           foreach ($data as $val) {
             if($no > 1){
               $email_user .= ",".$val->email_commit;
               $name_user .= ",".$val->own_commit;
            
             }else{
               $email_user  .= $val->email_commit;
               $name_user   .= $val->own_commit;
           
             }
             $no++;
            }
           
        $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
          
        $subject = 'Notifikasi Request Discount TC '.$nama_tc." [".$data[0]->name_store."]";
      
         $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
         $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $isi = "<html>
            <body>
              Dear User Approval {$name_user}<br><br>
              
                Bookers dari TC <b>{$nama_tc} [{$data[0]->name_store}]</b> Request Discount untuk kode booking customer <b>[{$link_url}]</b><br>
		Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>		
		list request discount :<br><b>{$pst['note']}<b><br>
                Request Discount ini butuh Approval dari <b>{$name_user}</b><br><br>
        
            </body>
          </html>";
        if($email_user !=""){
            $this->send_mail($email_user, $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => ""
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
  
  function chat_additional(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
       $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,J.title AS name_store" 
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
         . " WHERE A.kode ='{$pst['code']}'";
        $data = $this->global_models->get_query($sql);
	  
	  
            
//             $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
//           $nama_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store));
          
            $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
               $sql2 = "SELECT B.email"
                . " FROM tour_settings_region AS A"
                . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
                . " WHERE A.id_store ='{$data[0]->id_store}' AND A.region ='{$data[0]->sub_category}' ";
                $data_user = $this->global_models->get_query($sql2);
                
              $no = 1;
              foreach ($data_user as $val) {
                 
                  if($no > 1){
                      $email .= ",".$val->email;
//                      $name_user .= ",".$this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }else{
                       $email .= $val->email;
//                      $name_user .= $this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }
                  $no++;
            }
            
             $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));

       $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $subject = 'Notifikasi Request Additional TC '.$nama_tc." [".$data[0]->name_store."]";
        $url = base_url()."inventory/tour-book/book-information/".$pst['code'];
        $link_url = "<a href='{$url}'>{$pst['code']}</a>";
      
        $subject = 'Notifikasi Chat Request Additional TC '.$nama_tc." [".$data[0]->name_store."]";
       
        $html = "<html>
            <body>
              Dear Team Tour Operation<br><br>
              
             Bookers dari TC <b>{$nama_tc} [{$data[0]->name_store}]</b> Mengirimkan pesan chat di Request Additional dari kode booking customer <b>[{$link_url}]</b><br>
           <br>Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>		
            pesan chat di request Additional :<br>
         <b>{$pst['note']}</b><br><br>
            </body>
          </html>";
       
       if($email != ""){
           $this->send_mail($email, $subject, $html);
       }
        
        
        $kirim = array(
            'status'                  => 2,
            'note'                  =>""
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
  
  function chat_discount(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " WHERE A.kode ='{$pst['code']}'";
        
        $data = $this->global_models->get_query($sql);
        
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
          $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
       
          $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
        $subject = 'Notifikasi Chat Request Discount User ['.$nama_tc."]";
       
        $isi = "<html>
            <body>
              Dear {$pst['name_user']}<br><br>
              
                User <b>{$nama_tc}</b> Mengirimkan pesan chat di Request Discount dari kode booking customer <b>[{$link_url}]</b><br>
		Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>		
	pesan chat di request discount :<br>
         <b>{$pst['note']}</b><br><br>
        
            </body>
          </html>";
         
        if($pst['email_user'] !=""){
            $this->send_mail($pst['email_user'], $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => ""
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
  
   function post_req_discount(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " WHERE A.kode ='{$pst['code']}'";
        
        $data = $this->global_models->get_query($sql);
        
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
          $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
       
          $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
        $subject = 'Notifikasi Request Discount dari User ['.$nama_tc."]";
         
        $isi = "<html>
            <body>
              Dear {$pst['name_user']}<br><br>
              
                User <b>{$nama_tc}</b> membuat Request Discount baru untuk customer dengan kode booking <b>[{$link_url}]</b><br>
		Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>		
		list request discount :<br>
         <b>{$pst['note']}</b><br><br>
		 {$pst['keterangan']}
            </body>
          </html>";
         
        if($pst['email_user'] !=""){
            $this->send_mail($pst['email_user'], $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => ""
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
  
  function info_book_status_to_customer(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category,A.first_name,A.last_name,A.tanggal AS tanggal_book"
        . " ,A.status AS status_book,B.id_store,B.title,C.start_date,C.end_date,A.email"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " WHERE A.kode ='{$pst['code']}'";
        
        $data = $this->global_models->get_query($sql);
      
      $product_tour_book_customer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $data[0]->id_product_tour_book));
     
        
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        $status = array("1" => "BOOK", "2" => "DEPOSIT", "3" => "LUNAS");
          $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
          
//          $id_store_tc = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
//          
//          if($id_store_tc > 0){
//            $this->global_models->get_field("store", "title", array("id_users" => $pst['id_users']));
//          }else{
//            
//          }
          
 $sql_store = "SELECT A.name AS name_user,C.title AS name_store_TC,C.telp AS telp_tc
,C.fax AS fax_tc,C.alamat AS alamat_tc,E.title AS name_store_commited,E.telp AS telp_commited,
E.fax AS fax_commited,E.alamat AS alamat_commited        
FROM users_channel AS A
LEFT JOIN store_tc AS B ON A.id_users = B.id_users
LEFT JOIN store AS C ON B.id_store = C.id_store
LEFT JOIN store_commited AS D ON A.id_users = D.id_users
LEFT JOIN store AS E ON D.id_store = E.id_store
WHERE A.id_users='{$pst['id_users']}'";

$data_store = $this->global_models->get_query($sql_store);

$info_store = "Apabila ada pertanyaan dapat langsung menghubungi Store AntaVaya {$data_store[0]->name_store_TC} {$data_store[0]->name_store_commited} dibawah ini <br>
  Telp  Store  : {$data_store[0]->telp_tc}{$data_store[0]->telp_commited}<br>
  Fax   Store  : {$data_store[0]->fax_tc}{$data_store[0]->fax_commited}<br>
  Alamat Store : {$data_store[0]->alamat_tc}{$data_store[0]->alamat_commited}";
          
          $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
       
        $subject = 'Notifikasi Pembayaran Customer di Group Tour AntaVaya';
      $st_book = $status[$data[0]->status_book];
        $type = array("1" => "Adult Triple/ Twin", "2" => "Child Twin Bed", 
                      "3" => "Child Extra Bed", "4" => "Child No Bed", "5" => "Single Adult");
      foreach ($product_tour_book_customer as $val) {
        $pax .= "Nama      : {$val->first_name} {$val->last_name}<br>
                 Bed Type : {$type[$val->type]}<br><br>";
      }
  
        $isi = "<html>
            <body>
              Dear {$data[0]->first_name} {$data[0]->last_name}<br><br>
              
                Terima kasih telah melakukan pembayaran Group tour sebesar Rp. {$pst['nominal']} dari Store AntaVaya {$data_store[0]->name_store_TC} {$data_store[0]->name_store_commited} pada kode book <b>{$pst['code']}</b> <br>
                Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Untuk Tanggal Keberangkatan {$tanggal}<br>	
                Dengan Status : <b>{$st_book}</b><br><br>
                {$pax}
                {$info_store}
            </body>
          </html>";
                
        if($data[0]->email !=""){
            $this->send_mail($data[0]->email, $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => ""
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
  
  function info_book_to_customer(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category,A.first_name,A.last_name,A.tanggal AS tanggal_book"
        . " ,A.status AS status_book,B.id_store,B.title,C.start_date,C.end_date,A.email"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " WHERE A.kode ='{$pst['code']}'";
        
        $data = $this->global_models->get_query($sql);
      
      $product_tour_book_customer = $this->global_models->get("product_tour_customer", array("id_product_tour_book" => $data[0]->id_product_tour_book));
     
        
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        $status = array("1" => "BOOK", "2" => "DEPOSIT", "3" => "LUNAS");
          $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
          
//          $id_store_tc = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
//          
//          if($id_store_tc > 0){
//            $this->global_models->get_field("store", "title", array("id_users" => $pst['id_users']));
//          }else{
//            
//          }
          
 $sql_store = "SELECT A.name AS name_user,C.title AS name_store_TC,C.telp AS telp_tc
,C.fax AS fax_tc,C.alamat AS alamat_tc,E.title AS name_store_commited,E.telp AS telp_commited,
E.fax AS fax_commited,E.alamat AS alamat_commited        
FROM users_channel AS A
LEFT JOIN store_tc AS B ON A.id_users = B.id_users
LEFT JOIN store AS C ON B.id_store = C.id_store
LEFT JOIN store_commited AS D ON A.id_users = D.id_users
LEFT JOIN store AS E ON D.id_store = E.id_store
WHERE A.id_users='{$pst['id_users']}'";

$data_store = $this->global_models->get_query($sql_store);

$info_store = "Apabila ada pertanyaan dapat langsung menghubungi Store AntaVaya {$data_store[0]->name_store_TC} {$data_store[0]->name_store_commited} dibawah ini <br>
  Telp  Store  : {$data_store[0]->telp_tc}{$data_store[0]->telp_commited}<br>
  Fax   Store  : {$data_store[0]->fax_tc}{$data_store[0]->fax_commited}<br>
  Alamat Store : {$data_store[0]->alamat_tc}{$data_store[0]->alamat_commited}";
          
          $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
       
        $subject = 'Notifikasi Informasi Customer di Group Tour AntaVaya';
      $st_book = $status[$data[0]->status_book];
        $type = array("1" => "Adult Triple/ Twin", "2" => "Child Twin Bed", 
                      "3" => "Child Extra Bed", "4" => "Child No Bed", "5" => "Single Adult");
      foreach ($product_tour_book_customer as $val) {
        $pax .= "Nama    : {$val->first_name} {$val->last_name}<br>
                 Bed Type : {$type[$val->type]}<br><br>";
      }
  
        $isi = "<html>
            <body>
              Dear {$data[0]->first_name} {$data[0]->last_name}<br><br>
              
                TC {$data_store[0]->name_user} dari Store AntaVaya {$data_store[0]->name_store_TC} {$data_store[0]->name_store_commited} melakukan Book data Customer dengan kode book <b>{$pst['code']}</b> <br>
                Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Untuk Tanggal Keberangkatan {$tanggal}<br>	
                Dengan Status : <b>{$st_book}</b><br><br>
                {$pax}
                {$info_store}
            </body>
          </html>";
                
        if($data[0]->email !=""){
            $this->send_mail($data[0]->email, $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => ""
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
  
   function req_discount_approved(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " WHERE A.kode ='{$pst['code']}'";
        
        $data = $this->global_models->get_query($sql);
        
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
          $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
       
          $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
        $subject = 'Notifikasi Status Request Discount Dari User '.$nama_tc;
       
  
        $isi = "<html>
            <body>
              Dear {$pst['name_user']}<br><br>
              
                User <b>{$nama_tc}</b> Melakukan <b>{$pst['status']}</b> Request Discount dari kode booking customer <b>[{$link_url}]</b><br>
                Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>		
               
            </body>
          </html>";
                
        if($pst['email_user'] !=""){
            $this->send_mail($pst['email_user'], $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => ""
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
  
  function cancel_book_approve(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
	    $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,H.name AS own"
        . " ,J.title AS name_store"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"
        . " LEFT JOIN users_channel AS H ON A.id_users = H.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " WHERE A.kode ='{$pst['code']}'";
        $data = $this->global_models->get_query($sql);
	  
	  if($data[0]->id_product_tour_book){
       
           
//           $id_store = $this->global_models->get_field("store_tc", "id_store", array("id_users" => $pst['id_users']));
//           $nama_store = $this->global_models->get_field("store", "title", array("id_store" => $id_store));
          
            $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
                $sql2 = "SELECT B.email"
                . " FROM tour_settings_region AS A"
                . " LEFT JOIN m_users AS B ON A.id_users = B.id_users"
                . " WHERE A.id_store ='{$data[0]->id_store}' AND A.region ='{$data[0]->sub_category}' ";
                $data_user = $this->global_models->get_query($sql2);
               
              $no = 1;
              foreach ($data_user as $val) {
                 
                  if($no > 1){
                      $email .= ",".$val->email;
//                      $name_user .= ",".$this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }else{
                       $email .= $val->email;
//                      $name_user .= $this->global_models->get_field("m_users", "name", array("id_users" => $val->id_users));
                  }
                  $no++;
            }
              
             $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));

       $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $subject = 'Notifikasi Cancel Deposit dari User '.$nama_tc." [".$data[0]->name_store."]";
         $url = base_url()."inventory/tour-book/book-information/".$pst['code'];
        $link_url = "<a href='{$url}'>{$pst['code']}</a>";
      
        $html = "<html>
            <body>
              Dear Team Tour Operation<br><br>
                User <b>{$nama_tc} [{$data[0]->name_store}]</b> telah Menyetujui Cancel Deposit pada kode book {$link_url},<br>"
                . "<br>Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>
              Jika ada biaya tambahan Team Operation harap menambahkan biaya tambahan untuk Status Cancel Deposit 
                
            </body>
          </html>";
        if($email !=""){
            $this->send_mail($email, $subject, $html);
        }
//          $data_store = array(
//            "nama_store"            => $nama_store,
//            "email_user"            => $email_user,
//            "nama_tour"             => $data[0]->title,
//            "url"                   => $url
//           
//          );
 
          $kirim = array(
            'status'  => 2,
            'note'    => "",
            
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
  
  function cancel_book_reject(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,J.title AS name_store"
        . " ,D.name AS tc,D.email AS email_tc"    
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " WHERE A.kode ='{$pst['code']}'";
      
        $data = $this->global_models->get_query($sql);
       $gg = $this->db->last_query();
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
          
           if($pst['info_cancel'] == "1"){
             $info_cancel = "Untuk melakukan Approval Cancel book ke Menu Store => Group tour => klik Customer Cancel Per Book dan pilih Kode book [{$pst['code']}] dengan status Cancel Deposit [Waiting Approval]";
          
           }elseif($pst['info_cancel'] == "2"){
             $info_cancel = "Untuk melakukan Approval Cancel per pax ke Menu Store => Group tour => klik Customer Cancel Per Pax dan pilih Kode book [{$pst['code']}] dengan status Cancel Deposit [Waiting Approval]";
           }
        $nama_user = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
          
        $subject = 'Notifikasi Reject Cancel Book oleh User '.$nama_user." [".$data[0]->name_store."]";
      
//         $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
         $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $isi = "<html>
            <body>
              Dear User {$data[0]->tc}<br><br>
              
                User <b>{$nama_user} [{$data[0]->name_store}]</b> Telah Membatalkan Cancel Book dengan kode book <b>[{$pst['code']}]</b><br>
		Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>		
		
            </body>
          </html>";
        if($data[0]->email_tc !=""){
         $this->send_mail($data[0]->email_tc, $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => $gg
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
  
  function info_cancel_approval(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,J.title AS name_store"
        . " ,G.name AS own_commit,G.email AS email_commit" 
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " LEFT JOIN store_commited AS F ON J.id_store = F.id_store"
        . " LEFT JOIN users_channel  AS G ON F.id_users  = G.id_users"    
        . " WHERE A.kode ='{$pst['code']}'";
      
        $data = $this->global_models->get_query($sql);
        
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
           $no = 1;
           foreach ($data as $val) {
             if($no > 1){
               $email_user .= ",".$val->email_commit;
               $name_user .= ",".$val->own_commit;
            
             }else{
               $email_user  .= $val->email_commit;
               $name_user   .= $val->own_commit;
           
             }
             $no++;
            }
           if($pst['info_cancel'] == "1"){
             $info_cancel = "Untuk melakukan Approval Cancel book ke Menu Store => Group tour => klik Customer Cancel Per Book dan pilih Kode book [{$pst['code']}] dengan status Cancel Deposit [Waiting Approval]";
          
           }elseif($pst['info_cancel'] == "2"){
             $info_cancel = "Untuk melakukan Approval Cancel per pax ke Menu Store => Group tour => klik Customer Cancel Per Pax dan pilih Kode book [{$pst['code']}] dengan status Cancel Deposit [Waiting Approval]";
           }
        $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
          
          $email_tc = $this->global_models->get_field("users_channel", "email", array("id_users" => $pst['id_users']));
        
        
        $subject = 'Notifikasi Permintaan Cancel Book oleh User '.$nama_tc." [".$data[0]->name_store."]";
      
         $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
         $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $isi = "<html>
            <body>
              Dear User Approval {$name_user}<br><br>
              
                Bookers dari <b>{$nama_tc} [{$data[0]->name_store}]</b> Telah melakukan cancel book customer dengan kode book <b>[{$link_url}]</b><br>
		Dengan Tujuan Tour ke : {$data[0]->title}. di Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>		
		Note Cancel :<br><b>{$pst['note']}<b><br><br>
                cancel book ini butuh Approval dari <b>{$name_user}</b><br><br>
                {$info_cancel}
            </body>
          </html>";
        if($email_user !=""){
         $this->send_mail($email_user, $subject, $isi,$email_tc);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => ""
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
  
  function info_change_approval(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,A.id_product_tour_book_awal,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,J.title AS name_store"
        . " ,G.name AS own_commit,G.email AS email_commit"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " LEFT JOIN store_commited AS F ON J.id_store = F.id_store"
        . " LEFT JOIN users_channel  AS G ON F.id_users  = G.id_users"
        . " LEFT JOIN product_tour_book AS E ON A.id_product_tour_book_awal = E.id_product_tour_book"
           
        . " WHERE A.kode ='{$pst['code']}'";
      
        $data = $this->global_models->get_query($sql);
        
          $sql = "SELECT A.kode,B.sub_category"
        . " ,B.title,C.start_date,C.end_date"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"            
        . " WHERE A.id_product_tour_book ='{$data[0]->id_product_tour_book_awal}'";
       $new_data = $this->global_models->get_query($sql);
       
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
           $no = 1;
           foreach ($data as $val) {
             if($no > 1){
               $email_user .= ",".$val->email_commit;
               $name_user .= ",".$val->own_commit;
            
             }else{
               $email_user  .= $val->email_commit;
               $name_user   .= $val->own_commit;
           
             }
             $no++;
            }
         
            if($pst['status'] == 1){
                $info_change = "Untuk melakukan Approval change tour per book ke Menu TC Store => Group tour => klik Customer Change Per Book dan pilih Kode book [{$pst['code']}] dengan status Waiting Approval Change Tour";
                $status_data = "Per Book";
            }elseif($pst['status'] == 2){
                $info_change = "Untuk melakukan Approval change tour per pax ke Menu TC Store => Group tour => klik Customer Change Per Pax dan pilih Kode book [{$pst['code']}] dengan status Waiting Approval Change Tour";
                 $status_data = "Per Pax";
            }
             
            
        $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
        
        $email_tc = $this->global_models->get_field("users_channel", "email", array("id_users" => $pst['id_users']));
        
          
        $subject = 'Notifikasi Permintaan Pindah Tour per book oleh User '.$nama_tc." [".$data[0]->name_store."]";
      
         $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
         $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
         $new_tanggal = date("d M Y", strtotime($new_data[0]->start_date))." - ".date("d M Y", strtotime($new_data[0]->end_date));
        $isi = "<html>
            <body>
              Dear User Approval {$name_user}<br><br>
              
                Bookers dari <b>{$nama_tc} [{$data[0]->name_store}]</b> Telah melakukan change tour per book dengan kode book <b>[{$link_url}]</b><br>
		Dengan Tujuan Tour sebelumnya ke : {$data[0]->title}. Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>
                di pindahkan ke tujuan tour : {$new_data[0]->title}. Region {$data_region[$new_data[0]->sub_category]} Pada Tanggal {$new_tanggal}<br><br>    
		
                Pindah Tour {$status_data} ini butuh Approval dari <b>{$name_user}</b><br><br>
                {$info_change}<br>
                  
            </body>
          </html>";
        if($email_user !=""){
         $this->send_mail($email_user, $subject, $isi,$email_tc);
       }
            
          $kirim = array(
            'status'  => 2,
            'note'    => $data1
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
  
  function info_change_perpax_approval(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.kode AS kode_book,A.id_users,A.id_product_tour_book_awal,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,J.title AS name_store"
        . " ,G.name AS own_commit,G.email AS email_commit"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_customer AS H ON A.id_product_tour_book = H.id_product_tour_book"          
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " LEFT JOIN store_commited AS F ON J.id_store = F.id_store"
        . " LEFT JOIN users_channel  AS G ON F.id_users  = G.id_users"
        . " WHERE H.kode ='{$pst['code']}'";
      
        $data = $this->global_models->get_query($sql);
        
          $sql = "SELECT A.kode,B.sub_category"
        . " ,B.title,C.start_date,C.end_date"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"            
        . " WHERE A.id_product_tour_book ='{$data[0]->id_product_tour_book_awal}'";
       $new_data = $this->global_models->get_query($sql);
       
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
           $no = 1;
           foreach ($data as $val) {
             if($no > 1){
               $email_user .= ",".$val->email_commit;
               $name_user .= ",".$val->own_commit;
            
             }else{
               $email_user  .= $val->email_commit;
               $name_user   .= $val->own_commit;
           
             }
             $no++;
            }
         
            if($pst['status'] == 1){
                $info_change = "Untuk melakukan Approval change tour per book ke Menu TC Store => Group tour => klik Customer Change Per Book dan pilih Kode book [{$data[0]->kode_book}] dengan status Waiting Approval Change Tour";
                $status_data = "Per Book";
            }elseif($pst['status'] == 2){
                $info_change = "Untuk melakukan Approval change tour per pax ke Menu TC Store => Group tour => klik Customer Change Per Pax dan pilih Kode book [{$data[0]->kode_book}] dengan status Waiting Approval Change Tour";
                 $status_data = "Per Pax";
            }
             
            
        $nama_tc = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
          
          $email_tc = $this->global_models->get_field("users_channel", "email", array("id_users" => $pst['id_users']));
          
        $subject = 'Notifikasi Permintaan Pindah Tour per pax oleh User '.$nama_tc." [".$data[0]->name_store."]";
      
         $link_url = "<a href='{$pst['url']}'>{$data[0]->kode_book}</a>";
         $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
         $new_tanggal = date("d M Y", strtotime($new_data[0]->start_date))." - ".date("d M Y", strtotime($new_data[0]->end_date));
        $isi = "<html>
            <body>
              Dear User Approval {$name_user}<br><br>
              
                Bookers dari <b>{$nama_tc} [{$data[0]->name_store}]</b> Telah melakukan change tour per pax dengan kode book <b>[{$link_url}]</b><br>
		Dengan Tujuan Tour sebelumnya ke : {$data[0]->title}. Region {$data_region[$data[0]->sub_category]} Pada Tanggal {$tanggal}<br><br>
                di pindahkan ke tujuan tour : {$new_data[0]->title}. Region {$data_region[$new_data[0]->sub_category]} Pada Tanggal {$new_tanggal}<br><br>    
		
                Pindah Tour {$status_data} ini butuh Approval dari <b>{$name_user}</b><br><br>
                {$info_change}<br>
                  
            </body>
          </html>";
        if($email_user !=""){
         $this->send_mail($email_user, $subject, $isi,$email_tc);
       }
            
          $kirim = array(
            'status'  => 2,
            'note'    => $data1
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
  
   function change_tour_reject(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      	  $sql = "SELECT A.id_product_tour_book,A.id_users,B.sub_category"
        . " ,B.id_store,B.title,C.start_date,C.end_date"
        . " ,J.title AS name_store"
        . " ,D.name AS tc,D.email AS email_tc"
         . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"           
        . " LEFT JOIN users_channel AS D ON A.id_users = D.id_users"
        . " LEFT JOIN store_tc AS I ON A.id_users = I.id_users"
        . " LEFT JOIN store AS J ON I.id_store = J.id_store"
        . " WHERE A.kode ='{$pst['code']}'";
      
       
        $data = $this->global_models->get_query($sql);
      
          $sql = "SELECT A.kode,B.sub_category"
        . " ,B.title,C.start_date,C.end_date"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " LEFT JOIN product_tour_information AS C ON A.id_product_tour_information = C.id_product_tour_information"            
        . " WHERE A.kode ='{$pst['new_code']}'";
       $new_data = $this->global_models->get_query($sql);
       
        if($pst['status'] == 1){
                $status_data = "Per Book";
            }elseif($pst['status'] == 2){
                 $status_data = "Per Pax";
            }
       
        $data_region = array("1" =>"Eropa","2" =>"Africa","3" =>"America","4" => "Australia", "5" => "Asia", "6" =>"China","7" =>"New Zealand" );
        
        $nama_user = $this->global_models->get_field("users_channel", "name", array("id_users" => $pst['id_users']));
          
        $subject = 'Notifikasi Reject Change Tour oleh User '.$nama_user." [".$data[0]->name_store."]";
      
//         $link_url = "<a href='{$pst['url']}'>{$pst['code']}</a>";
//         $tanggal = date("d M Y", strtotime($data[0]->start_date))." - ".date("d M Y", strtotime($data[0]->end_date));
        $tanggal_new = date("d M Y", strtotime($new_data[0]->start_date))." - ".date("d M Y", strtotime($new_data[0]->end_date));
        $isi = "<html>
            <body>
              Dear User {$data[0]->tc}<br><br>
              
                User <b>{$nama_user} [{$data[0]->name_store}]</b> Telah Membatalkan change tour {$status_data} dengan kode book <b>[{$new_data[0]->kode}]</b><br>
		Dengan Tujuan Tour ke : {$new_data[0]->title}. di Region {$data_region[$new_data[0]->sub_category]} Pada Tanggal {$tanggal_new}<br><br>		
		
            </body>
          </html>";
        if($data[0]->email_tc !=""){
         $this->send_mail($data[0]->email_tc, $subject, $isi);
        }
            
          $kirim = array(
            'status'  => 2,
            'note'    => $gg
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
  
 function send_mail($email_tujuan,$subject,$isi,$cc){
      
        $this->load->library('email');
        $this->email->initialize($this->global_models->email_conf());
        $this->email->from('no-reply@antavaya.com', 'Administrator AV TMS');
        $this->email->to($email_tujuan);
        if($cc){
           $this->email->cc($cc);
        }
        $this->email->bcc('hendri.prasetyo@antavaya.com');
        $this->email->subject($subject);
        $this->email->message($isi);
        $this->email->send();
//        print $this->email->print_debugger();
//        die;
  }
  
}
