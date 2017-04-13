<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_hotel extends MX_Controller {
    
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
  function get_master_nation(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->get_connect("terminal");
      $items = $this->global_models->get_query("
        SELECT *
        FROM master_hotel_nation
        WHERE 
        LOWER(title) LIKE '%{$pst['q']}%' OR LOWER(kode) LIKE '%{$pst['q']}%'
        LIMIT 0,10
        ");
      $this->global_models->get_connect("default");
      if($items){
        foreach($items as $tms){
          $kirim[] = array(
              "id"    => $tms->id_master_nation,
              "label" => $tms->title." - ".$tms->kode,
              "value" => $tms->title." - ".$tms->kode,
          );
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

  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   * @param string $q Keyword
   * @param string $nation Nation String
   */
  function get_master_city(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      $this->global_models->get_connect("terminal");
      $pst['nation'] = str_replace("_","-",urldecode($pst['nation']));
      $nation = explode("-", $pst['nation']);
      $where = "LOWER(title) LIKE '%".trim(strtolower($nation[0]))."%'";
      if($nation[1])
        $where = "LOWER(kode) LIKE '%".trim(strtolower($nation[1]))."%'";
      $master_nation = $this->global_models->get_query("SELECT id_master_hotel_nation"
        . " FROM master_hotel_nation"
        . " WHERE {$where}");
//      $this->debug($master_nation, true);
      $items = $this->global_models->get_query("
        SELECT *
        FROM master_hotel_city
        WHERE 
        (LOWER(title) LIKE '%{$pst['q']}%' OR LOWER(kode) LIKE '%{$pst['q']}%')
        AND id_master_hotel_nation = '{$master_nation[0]->id_master_hotel_nation}'
        LIMIT 0,10
        ");
      $this->global_models->get_connect("default");
      if($items){
        foreach($items as $tms){
          $kirim[] = array(
              "id"    => $tms->id_master_city,
              "label" => $tms->title." - ".$tms->kode,
              "value" => $tms->title." - ".$tms->kode,
          );
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
  
  /**
   * @version 1.0
   * @author Nugroho B Santoso <budhi_nusa@yahoo.com>
   * @copyright (c) 2015, AntaVaya
   * @param string $users User name
   * @param string $password Password Access
   * @param string(2) $NationalCd National Code
   * @param string(2) $NationCd Nation Code
   * @param string(4) $CitynCd City Code
   * @param date(Y-m-d) $CheckIn Date Start
   * @param date(Y-m-d) $CheckOut Date End
   * @param integer $Sgl Single Bad
   * @param integer $Dbl Double Bad
   * @param integer $Twn Twin Bad
   * @param integer $Trp Triple Bad
   * @param integer $Quad Quad Bad
   */
  function get_hotel_in_city(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
//      nation
      $this->global_models->get_connect("terminal");
      $pst['nation'] = str_replace("_","-",urldecode($pst['nation']));
      $nation = explode("-", $pst['nation']);
      $where = "LOWER(title) LIKE '%".trim(strtolower($nation[0]))."%'";
      if($nation[1])
        $where = "LOWER(kode) LIKE '%".trim(strtolower($nation[1]))."%'";
      $master_nation = $this->global_models->get_query("SELECT kode"
        . " FROM master_hotel_nation"
        . " WHERE {$where}");
      
//      city
      $pst['city'] = str_replace("_","-",urldecode($pst['city']));
      $city = explode("-", $pst['city']);
      $where = "LOWER(title) LIKE '%".trim(strtolower($city[0]))."%'";
      if($nation[1])
        $where = "LOWER(kode) LIKE '%".trim(strtolower($city[1]))."%'";
      $master_city = $this->global_models->get_query("SELECT kode"
        . " FROM master_hotel_city"
        . " WHERE {$where}");
      $this->global_models->get_connect("default");
      $CheckIn = date("Y-m-d", strtotime($pst['CheckIn']));
      $CheckOut = date("Y-m-d", strtotime($pst['CheckOut']));
      $string = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<Request>
  <Auth>
	<UserID>{$this->users}</UserID>
	<UserPass>{$this->pass}</UserPass>
	<XmlVersion>RestotalXML.201102.1</XmlVersion>
  </Auth>
  <SearchAvailRequest>
	<NationalCd>ID</NationalCd>
	<NationCd>{$master_nation[0]->kode}</NationCd>
	<CityCd>{$master_city[0]->kode}</CityCd>
	<CheckIn>{$CheckIn}</CheckIn>
	<CheckOut>{$CheckOut}</CheckOut>
	<Sgl>{$pst['Sgl']}</Sgl>
	<Dbl>{$pst['Dbl']}</Dbl>
	<Twn>{$pst['Twn']}</Twn>
	<Trp>{$pst['Trp']}</Trp>
	<Quad>{$pst['Quad']}</Quad>
	<HCode></HCode>
	<Avail>N</Avail>
	<TariffOnly>N</TariffOnly>
	<BestPrice>Y</BestPrice>
  </SearchAvailRequest>
</Request>
XML;
      $ch = curl_init("http://xmlsearch.hotelxml.com/TEST/restotalxml.asmx/XMLHotelSearch");
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "reqStr=".$string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $output = curl_exec($ch);
      curl_close($ch);
      $hasil = simplexml_load_string($output);
//      $this->debug($hasil, true);
      if($hasil->SearchAvailResponse->Hotel){
        foreach($hasil->SearchAvailResponse->Hotel AS $hotel){
          $hasil_hotel[] = array(
            "HotelNo"         => $hotel->HotelNo,
            "SplyCd"          => $hotel->SplyCd,
            "AvailSply"       => $hotel->AvailSply,
            "AvailSplyHotel"  => $hotel->AvailSplyHotel,
            "HCode"           => $hotel->HCode,
            "Name"            => $hotel->Name,
            "RmGrade"         => $hotel->RmGrade,
            "MealCd"          => $hotel->MealCd,
            "Meal"            => $hotel->Meal,
            "Currency"        => $hotel->Currency,
            "TotalRate"       => $hotel->TotalRate,
            "Status"          => $hotel->Status
          );
        }
        $kirim = array(
          'status'  => 2,
          'hotel'   => $hasil_hotel
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => $hasil
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
   * @param string(2) $NationalCd National Code
   * @param string(2) $NationCd Nation Code
   * @param string(4) $CitynCd City Code
   * @param date(Y-m-d) $CheckIn Date Start
   * @param date(Y-m-d) $CheckOut Date End
   * @param integer $Sgl Single Bad
   * @param integer $Dbl Double Bad
   * @param integer $Twn Twin Bad
   * @param integer $Trp Triple Bad
   * @param integer $Quad Quad Bad
   */
  function get_hotel_detail(){
    $pst = $_REQUEST;
    $this->global_models->get_connect("terminal");
    $users = $this->global_models->get("m_users", array("name" => $pst['users']));
    $this->global_models->get_connect("default");
    if($this->encrypt->decode($users[0]->pass) == $pst['password'] AND $users){
      
//      nation
      $this->global_models->get_connect("terminal");
      $pst['nation'] = str_replace("_","-",urldecode($pst['nation']));
      $nation = explode("-", $pst['nation']);
      $where = "LOWER(title) LIKE '%".trim(strtolower($nation[0]))."%'";
      if($nation[1])
        $where = "LOWER(kode) LIKE '%".trim(strtolower($nation[1]))."%'";
      $master_nation = $this->global_models->get_query("SELECT kode"
        . " FROM master_hotel_nation"
        . " WHERE {$where}");
      
//      city
      $pst['city'] = str_replace("_","-",urldecode($pst['city']));
      $city = explode("-", $pst['city']);
      $where = "LOWER(title) LIKE '%".trim(strtolower($city[0]))."%'";
      if($nation[1])
        $where = "LOWER(kode) LIKE '%".trim(strtolower($city[1]))."%'";
      $master_city = $this->global_models->get_query("SELECT kode"
        . " FROM master_hotel_city"
        . " WHERE {$where}");
      $this->global_models->get_connect("default");
      $CheckIn = date("Y-m-d", strtotime($pst['CheckIn']));
      $CheckOut = date("Y-m-d", strtotime($pst['CheckOut']));
      $string = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<Request>
  <Auth>
	<UserID>{$this->users}</UserID>
	<UserPass>{$this->pass}</UserPass>
	<XmlVersion>RestotalXML.201102.1</XmlVersion>
  </Auth>
  <SearchAvailRequest>
	<NationalCd>ID</NationalCd>
	<NationCd>{$master_nation[0]->kode}</NationCd>
	<CityCd>{$master_city[0]->kode}</CityCd>
	<CheckIn>{$CheckIn}</CheckIn>
	<CheckOut>{$CheckOut}</CheckOut>
	<Sgl>{$pst['Sgl']}</Sgl>
	<Dbl>{$pst['Dbl']}</Dbl>
	<Twn>{$pst['Twn']}</Twn>
	<Trp>{$pst['Trp']}</Trp>
	<Quad>{$pst['Quad']}</Quad>
	<HCode>{$pst['HCode']}</HCode>
	<SplyCd>N</SplyCd>
	<RoomRateFlag>N</RoomRateFlag>
	<TariffOnly>Y</TariffOnly>
  </SearchAvailRequest>
</Request>
XML;
      $ch = curl_init("http://xmlsearch.hotelxml.com/TEST/restotalxml.asmx/XMLHotelSearch");
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "reqStr=".$string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $output = curl_exec($ch);
      curl_close($ch);
      $hasil = simplexml_load_string($output);
//      $this->debug($hasil, true);
      if($hasil->SearchAvailResponse->Hotel){
		$kirim_hotel = array(
			"status"		=> (string)$hasil->SearchAvailResponse->Hotel->Status,
			"room_grade"	=> (string)$hasil->SearchAvailResponse->Hotel->RmGrade,
			"meal"			=> (string)$hasil->SearchAvailResponse->Hotel->Meal,
			"total"			=> (string)$hasil->SearchAvailResponse->Hotel->TotalRate,
			"cancel_day"	=> (string)$hasil->SearchAvailResponse->Hotel->CXLPolicy->CXLDay,
			"cancel_remark"	=> (string)$hasil->SearchAvailResponse->Hotel->CXLPolicy->CXLRemark,
			"cancel_fee"	=> (string)$hasil->SearchAvailResponse->Hotel->CXLPolicy->CXLFee,
//			"status"		=> 'asasas'
		);
        $kirim = array(
          'status'  => 2,
          'hotel'   => $kirim_hotel
        );
      }
      else{
        $kirim = array(
          'status'  => 3,
          'note'    => $hasil
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