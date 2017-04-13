<?php
class Mflight extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('PHPExcel');
    }
   
    function get_btc_book(){
      
    if($this->session->userdata('flight_btc_start_date') != "" OR $this->session->userdata('flight_btc_end_date') != ""){
      $date = " AND A.tanggal BETWEEN {$this->session->userdata('flight_btc_start_date')} AND {$this->session->userdata('flight_btc_end_date')}  ";
    }
    
    if($this->session->userdata('flight_btc_book_code')){
      $book_code = " AND A.book_code ='{$this->session->userdata('flight_btc_book_code')}'";
    }
    
    if($this->session->userdata('flight_btc_maskapai')){
      $maskapai = " AND B.maskapai ='{$this->session->userdata('flight_btc_maskapai')}'";
    }
    
    if($this->session->userdata('flight_btc_status')){
      $status = " AND A.status ='{$this->session->userdata('flight_btc_status')}'";
    }
    
    if($this->session->userdata('flight_btc_pemesan')){
      $pemesan = " AND CONCAT(A.first_name, ' ',A.last_name) LIKE '%{$this->session->userdata('flight_btc_pemesan')}%' OR A.telphone LIKE '%{$this->session->userdata('flight_btc_pemesan')}%' OR A.email LIKE '%{$this->session->userdata('flight_btc_pemesan')}%'";
    }
    
     $data = $this->global_models->get_query("SELECT A.*"
      . " FROM tiket_book AS A"
      . " INNER JOIN tiket_flight AS B ON B.id_tiket_book = A.id_tiket_book"
      . " WHERE A.book_code IS NOT NULL {$date} {$book_code} {$maskapai} {$status} {$pemesan}"
      . " GROUP BY A.book_code ORDER BY A.tanggal DESC");
      
      return $data;
    }
    
    function get_btc_issued($sort,$field){
      
    if($this->session->userdata('flight_report_transaksi_booking_from') != "" OR $this->session->userdata('flight_report_transaksi_booking_to') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_report_transaksi_booking_from')}' AND '{$this->session->userdata('flight_report_transaksi_booking_to')}'";
    }else{
      if(!$this->session->userdata('flight_report_transaksi_book_code')){
        $date = " AND A.tanggal BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-t")."'";
      }
    }
    
    if($this->session->userdata('flight_report_transaksi_book_code')){
      $book_code = " AND (C.book_code LIKE '%{$this->session->userdata('flight_report_transaksi_book_code')}%' OR B.book_code LIKE '%{$this->session->userdata('flight_report_transaksi_book_code')}%')";
    }
    
    if($this->session->userdata('flight_report_transaksi_payment')){
      $channel2 = " AND A.channel ='{$this->session->userdata('flight_report_transaksi_payment')}'";
    }
    
    if($this->session->userdata('flight_report_transaksi_maskapai')){
      $maskapai = " AND C.maskapai LIKE '%{$this->session->userdata('flight_report_transaksi_maskapai')}%'";
    }
    
    if($this->session->userdata('flight_report_transaksi_tiket_no')){
      $issued_no = " AND A.issued_no LIKE '{$this->session->userdata('flight_report_transaksi_tiket_no')}%'";
    }
    
    if($field){
          $orderby = " ORDER BY ".$field." ".$sort;
      }else{
          $orderby = " ORDER BY A.tanggal DESC";
      }
      
      $report = $this->global_models->get_query("SELECT A.*, B.book_code, B.tanggal AS tglbook, B.harga_normal"
      . " FROM tiket_issued AS A"
      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
      . " LEFT JOIN tiket_flight AS C ON B.id_tiket_book = C.id_tiket_book"
      . " WHERE 1=1 {$date} {$book_code} {$channel2} {$maskapai} {$issued_no}"
      . " GROUP BY B.book_code"
        . " {$orderby}");
      
      return $report;
    }
    
    function get_btc_sales($sort,$field){
      
      if($this->session->userdata('flight_report_transaksi_sales_booking_from') != "" OR $this->session->userdata('flight_report_transaksi_sales_booking_to') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_report_transaksi_sales_booking_from')}' AND '{$this->session->userdata('flight_report_transaksi_sales_booking_to')}'";
    }else{
      if(!$this->session->userdata('flight_report_transaksi_book_code')){
        $date = " AND A.tanggal BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-t")."'";
      }
    }
    
    if($this->session->userdata('flight_report_transaksi_sales_book_code')){
      $book_code = " AND B.book_code LIKE '%{$this->session->userdata('flight_report_transaksi_sales_book_code')}%'";
    }
    
    if($this->session->userdata('flight_report_transaksi_sales_payment')){
      $channel2 = " AND A.channel ='{$this->session->userdata('flight_report_transaksi_sales_payment')}'";
    }
    if($this->session->userdata('flight_report_transaksi_sales_maskapai')){
      $maskapai = " AND C.maskapai LIKE '%{$this->session->userdata('flight_report_transaksi_sales_maskapai')}%'";
    }
    
    if($this->session->userdata('flight_report_transaksi_sales_tiket_no')){
      $issued_no = " AND A.issued_no LIKE '{$this->session->userdata('flight_report_transaksi_sales_tiket_no')}%'";
    }
    
    if($field){
          $orderby = " ORDER BY ".$field." ".$sort;
      }else{
          $orderby = " ORDER BY A.tanggal DESC";
      }
    
      $report = $this->global_models->get_query("SELECT A.*, B.book_code, B.tanggal AS tglbook, B.harga_normal"
      . " FROM tiket_issued AS A"
      . " LEFT JOIN tiket_book AS B ON A.id_tiket_book = B.id_tiket_book"
      . " LEFT JOIN tiket_flight AS C ON B.id_tiket_book = C.id_tiket_book"
      . " WHERE 1=1 {$date} {$book_code} {$channel2} {$maskapai} {$issued_no}"
      . " GROUP BY B.book_code"
      . " {$orderby}");
      
      return $report;
    }
    
    function get_btc_maskapai($sort,$field){
      if($this->session->userdata('flight_report_maskapai_booking_from') != "" OR $this->session->userdata('flight_report_maskapai_booking_to') != ""){
      $date = " AND A.tanggal BETWEEN '{$this->session->userdata('flight_report_maskapai_booking_from')}' AND '{$this->session->userdata('flight_report_maskapai_booking_to')}'";
    }else{
      if(!$this->session->userdata('flight_report_maskapai_book_code')){
        $date = " AND A.tanggal BETWEEN '".date("Y-m")."-01' AND '".date("Y-m-t")."'";
      }
    }
    
    if($this->session->userdata('flight_report_maskapai_book_code')){
      $book_code = " AND (C.book_code LIKE '%{$this->session->userdata('flight_report_maskapai_book_code')}%' OR B.book_code LIKE '%{$this->session->userdata('flight_report_transaksi_book_code')}%')";
    }
    
    if($this->session->userdata('flight_report_maskapai_payment')){
      $channel2 = " AND A.channel ='{$this->session->userdata('flight_report_maskapai_payment')}'";
    }
    
    if($this->session->userdata('flight_report_maskapai_maskapai')){
      $maskapai = " AND C.maskapai LIKE '%{$this->session->userdata('flight_report_maskapai_maskapai')}%'";
    }
    
    if($this->session->userdata('flight_report_maskapai_tiket_no')){
      $maskapai = " AND C.issued_no LIKE '{$this->session->userdata('flight_report_maskapai_tiket_no')}%'";
    }
    
     if($field){
          $orderby = " ORDER BY ".$field." ".$sort;
      }else{
          $orderby = " ORDER BY A.tanggal DESC";
      }
      
      $report = $this->global_models->get_query("SELECT C.*, IFNULL(C.book_code, B.book_code) AS code"
      . " ,A.channel"
      . " FROM tiket_flight AS C"
      . " LEFT JOIN tiket_issued AS A ON A.id_tiket_book = C.id_tiket_book"
      . " LEFT JOIN tiket_book AS B ON C.id_tiket_book = B.id_tiket_book"
      . " WHERE (B.status = 3 OR B.status = 5)"
      . " AND C.price > 0"
      . " {$date} {$book_code} {$channel2} {$maskapai}"
      . " GROUP BY C.book_code"
      . " {$orderby}");
      
      return $report;
    }

    function export_btc_issued_xls($filename,$sort,$field){
     
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data Flight Report Transaksi ")
							 ->setSubject("Data Flight Report Transaksi ")
							 ->setDescription("Report Data Transaksi Flight.")
							 ->setKeywords("Report Data Transaksi Flight")
							 ->setCategory("Data Transaksi Flight");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:K2');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Report Data Transaksi ');
      $objPHPExcel->getActiveSheet()->getStyle('A1:K2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:K2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Tanggal Issued');
      $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Book Code');
      $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Tiket No');
      $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Maskapai');
      $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Payment');
      $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Harga Tiket');
      $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Diskon');
      $objPHPExcel->getActiveSheet()->setCellValue('I4', 'Uang Terima');
      $objPHPExcel->getActiveSheet()->setCellValue('J4', 'HPP');
      $objPHPExcel->getActiveSheet()->setCellValue('K4', 'Rugi/Laba');
      $objPHPExcel->getActiveSheet()->getStyle('A4:K4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      $data = $this->get_btc_issued($sort,$field);
      if(is_array($data)){
        $channel = array(
          0 => "All",
          1 => "BCA",
          2 => "Mega CC",
          3 => "Visa/Master",
          4 => "Mega Priority"
        );
        
        $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
        
        $r = date("Y-m-d");
        $detail_harga = "";
        $no = 1;
        foreach ($data as $key => $value) {
          $flight = $this->global_models->get_query("SELECT SUM(price) AS jml"
        . " FROM tiket_flight"
        . " WHERE "
        . " id_tiket_flight IN (SELECT id_tiket_flight FROM tiket_flight WHERE id_tiket_book = '{$value->id_tiket_book}' GROUP BY issued_no)");
        
        $dt_flag = $this->global_models->get("tiket_flight", array("id_tiket_book" => $value->id_tiket_book));
        $maskapai1 = "";
        $bk_code = "";
        foreach($dt_flag AS $flg){
          if($flg->flight == 1){
            $maskapai1 .= $data_maskapai[$flg->maskapai];
            $bk_code .= $flg->book_code;
          }else{
            $maskapai1 .= "\n".$data_maskapai[$flg->maskapai];
            if($flg->book_code){
              $bk_code .= "\n".$flg->book_code;
            }
          }
        }
     // $discount = $this->mdiscount->btc_payment($value->tglbook, $value->harga_normal, $value->channel);
      $detail_harga = "";
         
      $total['harga_tiket'] += $value->harga_bayar + $value->diskon;
      $total['diskon'] += $value->diskon;
      $total['uang_terima'] += ($value->harga_bayar);
      $total['hpp'] += $flight[0]->jml;
      $total['rugilaba'] += ($value->harga_bayar-$flight[0]->jml);
      
          $objPHPExcel->getActiveSheet()->setCellValue('A'.(5+$key),$no++);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.(5+$key),date("Y-m-d H:i:s", strtotime($value->tanggal)));
          $objPHPExcel->getActiveSheet()->setCellValue('C'.(5+$key),$bk_code);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.(5+$key),$value->issued_no);
          $objPHPExcel->getActiveSheet()->setCellValue('E'.(5+$key),$maskapai1);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.(5+$key),$channel[$value->channel]);
          $objPHPExcel->getActiveSheet()->setCellValue('G'.(5+$key),($value->harga_bayar + $value->diskon));
          $objPHPExcel->getActiveSheet()->getStyle('G'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
          $objPHPExcel->getActiveSheet()->setCellValue('H'.(5+$key),$value->diskon);
          $objPHPExcel->getActiveSheet()->getStyle('H'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
          $objPHPExcel->getActiveSheet()->setCellValue('I'.(5+$key),$value->harga_bayar);
          $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
          $objPHPExcel->getActiveSheet()->setCellValue('J'.(5+$key),$flight[0]->jml);
          $objPHPExcel->getActiveSheet()->getStyle('J'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
          $objPHPExcel->getActiveSheet()->setCellValue('K'.(5+$key),($value->harga_bayar-$flight[0]->jml));
          $objPHPExcel->getActiveSheet()->getStyle('K'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->getActiveSheet()->getStyle('A5:K'.(5+$key))->applyFromArray(
          array(
            'font'    => array(
              'bold'      => false
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
        
        $jml =4+$no;
        $ak = "A".$jml.":F".$jml;
        $akj = "G".$jml.":K".$jml;
        $asj = "A".$jml.":K".$jml;
         $objPHPExcel->getActiveSheet()->mergeCells($ak);
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($jml),'TOTAL ');
      $objPHPExcel->getActiveSheet()->setCellValue('G'.($jml),$total['harga_tiket']);
      $objPHPExcel->getActiveSheet()->getStyle('G'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->setCellValue('H'.($jml),$total['diskon']);
      $objPHPExcel->getActiveSheet()->getStyle('H'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->setCellValue('I'.($jml),$total['uang_terima']);
      $objPHPExcel->getActiveSheet()->getStyle('I'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->setCellValue('J'.($jml),$total['hpp']);
      $objPHPExcel->getActiveSheet()->getStyle('J'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->setCellValue('K'.($jml),$total['rugilaba']);
      $objPHPExcel->getActiveSheet()->getStyle('K'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      
      $objPHPExcel->getActiveSheet()->getStyle($ak)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
          )
      ); 
      $objPHPExcel->getActiveSheet()->getStyle($akj)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->getStyle($asj)->applyFromArray(
          array(
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      }
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(50);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    function export_btc_maskapai_xls($filename,$sort,$field){
     
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data Flight Report Maskapai ")
							 ->setSubject("Data Flight Report Maskapai ")
							 ->setDescription("Report Data Maskapai Flight.")
							 ->setKeywords("Report Data Maskapai Flight")
							 ->setCategory("Data Maskapai Flight");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:F2');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Report Data Maskapai ');
      $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Tanggal Issued');
      $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Book Code');
      $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Tiket No');
      $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Maskapai');
      $objPHPExcel->getActiveSheet()->setCellValue('F4', 'HPP');
      $objPHPExcel->getActiveSheet()->getStyle('A4:F4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      $data = $this->get_btc_maskapai($sort,$field);
      if(is_array($data)){
        $channel = array(
          0 => "All",
          1 => "BCA",
          2 => "Mega CC",
          3 => "Visa/Master",
          4 => "Mega Priority"
        );
        
        $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
        
        $r = date("Y-m-d");
        $detail_harga = "";
        $no = 1;
        foreach ($data as $key => $value) {
          $flight = $this->global_models->get_query("SELECT SUM(price) AS jml"
        . " FROM tiket_flight"
        . " WHERE "
        . " id_tiket_flight IN (SELECT id_tiket_flight FROM tiket_flight WHERE id_tiket_book = '{$value->id_tiket_book}' GROUP BY issued_no)");
        
        $dt_flag = $this->global_models->get("tiket_flight", array("id_tiket_book" => $value->id_tiket_book));
        $maskapai1 = "";
        $bk_code = "";
        foreach($dt_flag AS $flg){
          if($flg->flight == 1){
            $maskapai1 .= $data_maskapai[$flg->maskapai];
            $bk_code .= $flg->book_code;
            
          }else{
            $maskapai1 .= "\n".$data_maskapai[$flg->maskapai];
            if($flg->book_code){
              $bk_code .= "\n".$flg->book_code;
            }
          }
        }
      //$discount = $this->mdiscount->btc_payment($value->tglbook, $value->harga_normal, $value->channel);
      $detail_harga = "";
         
      $total['hpp'] += $flight[0]->jml;
      
          $objPHPExcel->getActiveSheet()->setCellValue('A'.(5+$key),$no++);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.(5+$key),date("Y-m-d H:i:s", strtotime($value->tanggal)));
          $objPHPExcel->getActiveSheet()->setCellValue('C'.(5+$key),$bk_code);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.(5+$key),$value->issued_no);
          $objPHPExcel->getActiveSheet()->setCellValue('E'.(5+$key),$maskapai1);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.(5+$key),$flight[0]->jml);
          $objPHPExcel->getActiveSheet()->getStyle('F'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->getActiveSheet()->getStyle('A5:F'.(5+$key))->applyFromArray(
          array(
            'font'    => array(
              'bold'      => false
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
        
        $jml =4+$no;
        $ak = "A".$jml.":E".$jml;
        $akj = "E".$jml.":F".$jml;
        $asj = "A".$jml.":F".$jml;
      $objPHPExcel->getActiveSheet()->mergeCells($ak);
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($jml),'TOTAL ');
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($jml),$total['hpp']);
      $objPHPExcel->getActiveSheet()->getStyle('F'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      
       
      
      $objPHPExcel->getActiveSheet()->getStyle($ak)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
          )
      ); 
      $objPHPExcel->getActiveSheet()->getStyle($akj)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->getStyle($asj)->applyFromArray(
          array(
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      }
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      
      //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(50);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    
    function export_btc_summary_maskapai_xls($filename,$data){
     
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data Flight Report Transaksi ")
							 ->setSubject("Data Flight Report Transaksi ")
							 ->setDescription("Report Data Transaksi Flight.")
							 ->setKeywords("Report Data Transaksi Flight")
							 ->setCategory("Data Transaksi Flight");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Report Data Transaksi ');
      $objPHPExcel->getActiveSheet()->getStyle('A1:B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:B2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Maskapai');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'HPP');
      $objPHPExcel->getActiveSheet()->getStyle('A4:B4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      $objPHPExcel->getActiveSheet()->setCellValue('A5', "Periode : {$this->session->userdata('flight_report_maskapai_booking_from')} s/d {$this->session->userdata('flight_report_maskapai_booking_to')}");
      
//      $data = $this->get_btc_maskapai($sort,$field);
      if(is_array($data)){
        $channel = array(
          0 => "All",
          1 => "BCA",
          2 => "Mega CC",
          3 => "Visa/Master",
          4 => "Mega Priority"
        );
        
        $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
        
        $r = date("Y-m-d");
        $detail_harga = "";
        $no = 1;
        foreach ($data as $rt => $value) {
//          $flight = $this->global_models->get_query("SELECT SUM(price) AS jml"
//        . " FROM tiket_flight"
//        . " WHERE "
//        . " id_tiket_flight IN (SELECT id_tiket_flight FROM tiket_flight WHERE id_tiket_book = '{$value->id_tiket_book}' GROUP BY issued_no)");
//        
//        $dt_flag = $this->global_models->get("tiket_flight", array("id_tiket_book" => $value->id_tiket_book));
//        $maskapai1 = "";
//        $bk_code = "";
//        foreach($dt_flag AS $flg){
//          if($flg->flight == 1){
//            $maskapai1 .= $data_maskapai[$flg->maskapai];
//            $bk_code .= $flg->book_code;
//            
//          }else{
//            $maskapai1 .= "\n".$data_maskapai[$flg->maskapai];
//            if($flg->book_code){
//              $bk_code .= "\n".$flg->book_code;
//            }
//          }
//        }
//      //$discount = $this->mdiscount->btc_payment($value->tglbook, $value->harga_normal, $value->channel);
//      $detail_harga = "";
//         
//      $total['hpp'] += $flight[0]->jml;
      
          $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$no),$data_maskapai[$rt]);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$no),$value);
          $objPHPExcel->getActiveSheet()->getStyle('B'.(6+$no))->getNumberFormat()->setFormatCode('#,##0');
          $no++;
        }
        $objPHPExcel->getActiveSheet()->getStyle('A5:B'.(6+$no))->applyFromArray(
          array(
            'font'    => array(
              'bold'      => false
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
//        
//        $jml =4+$no;
//        $ak = "A".$jml.":E".$jml;
//        $akj = "E".$jml.":F".$jml;
//        $asj = "A".$jml.":F".$jml;
//      $objPHPExcel->getActiveSheet()->mergeCells($ak);
//      $objPHPExcel->getActiveSheet()->setCellValue('A'.($jml),'TOTAL ');
//      $objPHPExcel->getActiveSheet()->setCellValue('F'.($jml),$total['hpp']);
//      $objPHPExcel->getActiveSheet()->getStyle('F'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      
       
      
//      $objPHPExcel->getActiveSheet()->getStyle($ak)->applyFromArray(
//          array(
//            'font'    => array(
//              'bold'      => true
//            ),
//            'alignment' => array(
//              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//            ),
//          )
//      ); 
//      $objPHPExcel->getActiveSheet()->getStyle($akj)->applyFromArray(
//          array(
//            'font'    => array(
//              'bold'      => true
//            ),
//            'alignment' => array(
//              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
//            ),
//          )
//      );
//      
//      $objPHPExcel->getActiveSheet()->getStyle($asj)->applyFromArray(
//          array(
//            'borders' => array(
//              'bottom'     => array(
//                'style' => PHPExcel_Style_Border::BORDER_THIN
//              ),
//              'left'     => array(
//                'style' => PHPExcel_Style_Border::BORDER_THIN
//              ),
//              'right'     => array(
//                'style' => PHPExcel_Style_Border::BORDER_THIN
//              ),
//            ),
//            'fill' => array(
//              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
//                'rotation'   => 90,
//              'startcolor' => array(
//                'argb' => 'FFA0A0A0'
//              ),
//              'endcolor'   => array(
//                'argb' => 'FFFFFFFF'
//              )
//            )
//          )
//      );
      
      }
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      
      //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(50);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    
    
    function export_btc_sales_xls($filename,$sort,$field){
     
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data Flight Report Transaksi Sales")
							 ->setSubject("Data Flight Report Transaksi Sales")
							 ->setDescription("Report Data Transaksi Sales Flight.")
							 ->setKeywords("Report Data Transaksi Sales Flight")
							 ->setCategory("Data Transaksi Sales Flight");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Report Data Transaksi Sales  ');
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:I2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Tanggal Issued');
      $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Book Code');
      $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Tiket No');
      $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Maskapai');
      $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Payment');
      $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Harga Tiket');
      $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Diskon');
      $objPHPExcel->getActiveSheet()->setCellValue('I4', 'Uang Terima');
      $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      $data = $this->get_btc_sales($sort,$field);
      if(is_array($data)){
        $channel = array(
          0 => "All",
          1 => "BCA",
          2 => "Mega CC",
          3 => "Visa/Master",
          4 => "Mega Priority"
        );
        
        $data_maskapai = array("0"	=> "All",
				  "GA" => "Garuda",
				  "ID"	=> "Batik Air",
				  "IW"  => "Wings Air",
				  "JT"	=> "Lion Air",
				  "QG"	=> "Citylink",
				  "QZ"	=> "Air Asia",
				  "SJ"	=> "Sriwijaya Air");
        
        $r = date("Y-m-d");
        $detail_harga = "";
        $no = 1;
        foreach ($data as $key => $value) {
          $flight = $this->global_models->get_query("SELECT SUM(price) AS jml"
        . " FROM tiket_flight"
        . " WHERE "
        . " id_tiket_flight IN (SELECT id_tiket_flight FROM tiket_flight WHERE id_tiket_book = '{$value->id_tiket_book}' GROUP BY issued_no)");
        
        $dt_flag = $this->global_models->get("tiket_flight", array("id_tiket_book" => $value->id_tiket_book));
        $maskapai1 = "";
        $bk_code = "";
        foreach($dt_flag AS $flg){
          if($flg->flight == 1){
            $maskapai1 .= $data_maskapai[$flg->maskapai];
            $bk_code .= $flg->book_code;
          }else{
            $maskapai1 .= "\n".$data_maskapai[$flg->maskapai];
            if($flg->book_code){
              $bk_code .= "\n".$flg->book_code;
            }
          }
        }
     // $discount = $this->mdiscount->btc_payment($value->tglbook, $value->harga_normal, $value->channel);
      $detail_harga = "";
         
      $total['harga_tiket'] += $value->harga_bayar + $value->diskon;
      $total['diskon'] += $value->diskon;
      $total['uang_terima'] += ($value->harga_bayar);
      $total['hpp'] += $flight[0]->jml;
      $total['rugilaba'] += ($value->harga_bayar-$flight[0]->jml);
      
          $objPHPExcel->getActiveSheet()->setCellValue('A'.(5+$key),$no++);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.(5+$key),date("Y-m-d H:i:s", strtotime($value->tanggal)));
          $objPHPExcel->getActiveSheet()->setCellValue('C'.(5+$key),$bk_code);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.(5+$key),$value->issued_no);
          $objPHPExcel->getActiveSheet()->setCellValue('E'.(5+$key),$maskapai1);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.(5+$key),$channel[$value->channel]);
          $objPHPExcel->getActiveSheet()->setCellValue('G'.(5+$key),($value->harga_bayar + $value->diskon));
          $objPHPExcel->getActiveSheet()->getStyle('G'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
          $objPHPExcel->getActiveSheet()->setCellValue('H'.(5+$key),$value->diskon);
          $objPHPExcel->getActiveSheet()->getStyle('H'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
          $objPHPExcel->getActiveSheet()->setCellValue('I'.(5+$key),$value->harga_bayar);
          $objPHPExcel->getActiveSheet()->getStyle('I'.(5+$key))->getNumberFormat()->setFormatCode('#,##0');
        }
        $objPHPExcel->getActiveSheet()->getStyle('A5:I'.(5+$key))->applyFromArray(
          array(
            'font'    => array(
              'bold'      => false
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
        
        $jml =4+$no;
        $ak = "A".$jml.":F".$jml;
        $akj = "G".$jml.":I".$jml;
        $asj = "A".$jml.":I".$jml;
         $objPHPExcel->getActiveSheet()->mergeCells($ak);
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($jml),'TOTAL ');
      $objPHPExcel->getActiveSheet()->setCellValue('G'.($jml),$total['harga_tiket']);
      $objPHPExcel->getActiveSheet()->getStyle('G'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->setCellValue('H'.($jml),$total['diskon']);
      $objPHPExcel->getActiveSheet()->getStyle('H'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->setCellValue('I'.($jml),$total['uang_terima']);
      $objPHPExcel->getActiveSheet()->getStyle('I'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      
      $objPHPExcel->getActiveSheet()->getStyle($ak)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
          )
      ); 
      $objPHPExcel->getActiveSheet()->getStyle($akj)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
          )
      );
      
      $objPHPExcel->getActiveSheet()->getStyle($asj)->applyFromArray(
          array(
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      }
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(50);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    function export_btc_book_xls($filename,$sort,$field){
     
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data Flight Book")
							 ->setSubject("Data Flight Book")
							 ->setDescription("Report Data Flight Book.")
							 ->setKeywords("report Data Flight Book")
							 ->setCategory("Flight Book");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:M2');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Data Flight Book ');
      $objPHPExcel->getActiveSheet()->getStyle('A1:M2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $objPHPExcel->getActiveSheet()->getStyle('A1:M2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('A4', 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Tanggal Book');
      $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Tanggal Issued');
      $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Book Code');
      $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Tipe');
      $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Maskapai');
      $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Pemesan');
      $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Email');
      $objPHPExcel->getActiveSheet()->setCellValue('I4', 'No Telp');
      $objPHPExcel->getActiveSheet()->setCellValue('J4', 'Time Limit');
      $objPHPExcel->getActiveSheet()->setCellValue('K4', 'Payment');
      $objPHPExcel->getActiveSheet()->setCellValue('L4', 'Status');
      $objPHPExcel->getActiveSheet()->setCellValue('M4', 'Harga');
      $objPHPExcel->getActiveSheet()->getStyle('A4:M4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      
      $data = $this->get_btc_book();
      if(is_array($data)){
        $status = array(
      1 => "Proses",
      3 => "Issued",
      5 => "IBA",
      4 => "Cancel",
      6 => "No Tiket Gagal",
      7 => "File Tiket Gagal",
    );
        
        $r = date("Y-m-d");
        $detail_harga = "";
        $no = 1;
        foreach ($data as $key => $value) {
          
          $tanggal_issued = $this->global_models->get("tiket_issued", array("id_tiket_book" => $value->id_tiket_book));
          $issued_tanggal = "";
      if($tanggal_issued[0]->tanggal){
        
        if($value->status == 3){
          $issued_tanggal = $tanggal_issued[0]->tanggal;
          }elseif($value->status == 6){
          $issued_tanggal = $tanggal_issued[0]->tanggal;
        }elseif ($value->status == 7) {
            $issued_tanggal = $tanggal_issued[0]->tanggal;
       
          }
        
      }
          
          $flight = $this->global_models->get("tiket_flight", array("id_tiket_book" => $value->id_tiket_book));
          $maskapai1 = "";
          foreach($flight AS $flg){
        
        $items = $this->global_models->get("tiket_flight_items", array("id_tiket_flight" => $flg->id_tiket_flight));
        if($flg->flight == 1){
          $maskapai1 .= $flg->maskapai;
          $items1st = $penerbangan_kembali = $penumpang = "";
          foreach($items AS $itm){
            $items1st .= $itm->flight_no." {$itm->dari} - {$itm->ke} ".date("Y/M/d H:s", strtotime($itm->departure))."-".date("H:s", strtotime($itm->arrive))."<br />";
          }
          $tipe = "One Way";
          $penerbangan_dari = "<tr><td><h4>Penerbangan {$this->global_models->array_kota($flg->dari)} - {$this->global_models->array_kota($flg->ke)}</h4><td></tr><tr><td>{$items1st}<td></tr>";
        }
        else{
          $maskapai1 .= ", ".$flg->maskapai;
          $items2nd = "";
          foreach($items AS $itm){
            $items2nd .= $itm->flight_no." {$itm->dari} - {$itm->ke} ".date("Y/M/d H:s", strtotime($itm->departure))."-".date("H:s", strtotime($itm->arrive))."<br />";
          }
          $penerbangan_kembali = "<tr><td><h4>Penerbangan {$this->global_models->array_kota($flg->dari)} - {$this->global_models->array_kota($flg->ke)}</h4><td></tr><tr><td>{$items2nd}<td></tr>";
          $tipe = "Round Trip";
        }
      }
      $maskapai = $maskapai1;
      
      $type = array(
        1 => "Adult", 2 => "Child", 3 => "Infant"
      );
      
      $passenger = $this->global_models->get("tiket_passenger", array("id_tiket_book" => $value->id_tiket_book));
      $price1st = $value->diskon;
      $price2nd = 0;
      foreach($passenger AS $psgr){
        $price1st += $psgr->price;
        $price2nd += $psgr->price2nd;
        $penumpang .= "<tr><td>{$psgr->title} {$psgr->first_name} {$psgr->last_name} ".date("Y M d", strtotime($psgr->tanggal_lahir))." {$type[$psgr->type]} "
        . number_format(($psgr->price + $psgr->price2nd),0,".",",")." </td></tr>";
      }
      $no_telp = " ".$value->telphone;
     
          $objPHPExcel->getActiveSheet()->setCellValue('A'.(5+$key), $no++);
          $objPHPExcel->getActiveSheet()->setCellValue('B'.(5+$key), date("Y-m-d H:i:s", strtotime($value->tanggal)));
          $objPHPExcel->getActiveSheet()->setCellValue('C'.(5+$key), $issued_tanggal);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.(5+$key), $value->book_code);
          $objPHPExcel->getActiveSheet()->setCellValue('E'.(5+$key), $tipe);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.(5+$key), $maskapai);
          $objPHPExcel->getActiveSheet()->setCellValue('G'.(5+$key), $value->pemesan);
          $objPHPExcel->getActiveSheet()->setCellValue('H'.(5+$key), $value->email);
          $objPHPExcel->getActiveSheet()->setCellValue('I'.(5+$key), $no_telp);
          $objPHPExcel->getActiveSheet()->setCellValue('J'.(5+$key), date("Y-m-d H:i", strtotime($value->timelimit)));
          $objPHPExcel->getActiveSheet()->setCellValue('K'.(5+$key), $value->cara_bayar);
          $objPHPExcel->getActiveSheet()->setCellValue('L'.(5+$key), $status[$value->status]);
          $objPHPExcel->getActiveSheet()->setCellValue('M'.(5+$key), number_format($value->harga_bayar, 0, ".", ","));
        }
      }
      $objPHPExcel->getActiveSheet()->getStyle('A5:M'.(5+$key))->applyFromArray(
          array(
            'font'    => array(
              'bold'      => false
            ),
            'alignment' => array(
              'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'borders' => array(
              'bottom'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'left'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              'right'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            'fill' => array(
              'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
              'startcolor' => array(
                'argb' => 'FFA0A0A0'
              ),
              'endcolor'   => array(
                'argb' => 'FFFFFFFF'
              )
            )
          )
      );
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
      //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(50);
//      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    
}
?>
