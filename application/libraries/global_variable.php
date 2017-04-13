<?php
class Global_variable{
  function __construct(){
      
  }
  function bulan(){
    $bulan = array(
      1 => array("id" => "Januari", "en" => "January"),
      2 => array("id" => "Februari", "en" => "February"),
      3 => array("id" => "Maret", "en" => "March"),
      4 => array("id" => "April", "en" => "April"),
      5 => array("id" => "Mei", "en" => "May"),
      6 => array("id" => "Juni", "en" => "June"),
      7 => array("id" => "Juli", "en" => "July"),
      8 => array("id" => "Agustus", "en" => "August"),
      9 => array("id" => "September", "en" => "September"),
      10 => array("id" => "Oktober", "en" => "October"),
      11 => array("id" => "Nopember", "en" => "November"),
      12 => array("id" => "Desember", "en" => "December"),
    );
    return $bulan;
  }
  
  function table_discount(){
    return array(
      1 => "tiket_discount",
      2 => "tiket_discount_destination"
    );
  }
}
?>
