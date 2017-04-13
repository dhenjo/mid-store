<?php
class Mgroup_status_tour extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('PHPExcel');
    }
    
    function get_tour($where){
    
      $report = $this->global_models->get_query("SELECT A.title,A.id_product_tour"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE 1=1 {$where} ");
      
      return $report;
    }
    
    function get_tour_information($where,$where_information){
      $items = $this->global_models->get_query("SELECT A.title,"
        . "B.id_product_tour_information,A.days,B.start_date,B.start_time,B.end_time,B.end_date,B.id_currency,B.flt,B.in,B.out,B.available_seat,B.adult_triple_twin,B.child_twin_bed,B.child_extra_bed,B.child_no_bed,B.sgl_supp,B.airport_tax"
        . " FROM product_tour AS A"
        . " LEFT JOIN product_tour_information AS B ON A.id_product_tour = B.id_product_tour"
         . " WHERE A.status=1 {$where}");

     if($items){
      
        foreach($items AS $it){
          
          $dropdown = $this->global_models->get_dropdown("master_currency", "id_master_currency", "code", TRUE, array("status" => 1));     
   
              $book = $this->global_models->get_query("SELECT count(A.kode) AS aid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2 OR A.status = 3)");
              
              $totl_book = $this->global_models->get_query("SELECT count(A.kode) AS id"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 1)");
              
              $totl_commit  = $this->global_models->get_query("SELECT count(A.kode) AS cid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 2)");
              
              $totl_lunas  = $this->global_models->get_query("SELECT count(A.kode) AS lid"
              . " FROM product_tour_customer AS A"
              . " LEFT JOIN product_tour_book AS B ON A.id_product_tour_book = B.id_product_tour_book"
              . " WHERE B.id_product_tour_information = '{$it->id_product_tour_information}'"
              . " AND (A.status = 3)");
              
              
             
          $tour[] = array(
            "title"             => $it->title,
            "days"              => $it->days,
            "start_date"        => $it->start_date,
            "start_time"        => $it->start_time,
            "end_date"          => $it->end_date,
            "end_time"          => $it->end_time,
            "currency"          => $dropdown[$it->id_currency],
            "flt"               => $it->flt,
            //  "sts"             => $it->sts,  
              "in"              => $it->in,
              "out"             => $it->out,
              "seat"            => $it->available_seat,
              "book"            => $totl_book[0]->id,
              "deposit"         => $totl_commit[0]->cid,  
              "lunas"           => $totl_lunas[0]->lid,
              "available_seat"  => ($it->available_seat - ($book[0]->aid)),
              "price"           => array("adult_triple_twin" => $it->adult_triple_twin,"child_twin_bed" => $it->child_twin_bed,"child_extra_bed" => $it->child_extra_bed,"child_no_bed" => $it->child_no_bed,"sgl_supp" => $it->sgl_supp, "airport_tax" => $it->airport_tax),
          );
        }
        
      }    
      return $tour;
    }

    function export_xls($filename,$where_tour,$where_information){
//     print_r($where_information); die;
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Data Group Tour Status AntaVaya ")
							 ->setSubject("Data Group Tour Status AntaVaya ")
							 ->setDescription("Group Tour Status AntaVaya")
							 ->setKeywords("Group Tour Status AntaVaya")
							 ->setCategory("Group Tour Status AntaVaya");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:U3');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Group Tour Status AntaVaya ');
      $objPHPExcel->getActiveSheet()->getStyle('A1:U3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1:U3')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
               'size'  => 24,
              'name'  => 'Verdana'
              
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
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Group');
      $objPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
      $objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A5:B5')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
               'size'  => 18,
              'name'  => 'Verdana'
              
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
      $objPHPExcel->getActiveSheet()->setCellValue('C5', 'DAYS');
      $objPHPExcel->getActiveSheet()->setCellValue('D5', 'DEP');
      $objPHPExcel->getActiveSheet()->setCellValue('E5', 'ETD');
      $objPHPExcel->getActiveSheet()->setCellValue('F5', 'ARR');
      $objPHPExcel->getActiveSheet()->setCellValue('G5', 'ETA');
      $objPHPExcel->getActiveSheet()->setCellValue('H5', 'FLT');
     // $objPHPExcel->getActiveSheet()->setCellValue('I5', 'STS');
      $objPHPExcel->getActiveSheet()->setCellValue('I5', 'IN/OUT');
      $objPHPExcel->getActiveSheet()->setCellValue('J5', 'SEATS');
      $objPHPExcel->getActiveSheet()->setCellValue('K5', 'BOOK');
      $objPHPExcel->getActiveSheet()->setCellValue('L5', 'DEPOSIT');
      $objPHPExcel->getActiveSheet()->setCellValue('M5', 'LUNAS');
      $objPHPExcel->getActiveSheet()->setCellValue('N5', 'AVAIL SEAT');
      $objPHPExcel->getActiveSheet()->setCellValue('O5', 'CURRENCY');
      $objPHPExcel->getActiveSheet()->setCellValue('P5', 'AD TWN');
      $objPHPExcel->getActiveSheet()->setCellValue('Q5', 'C TWN');
      $objPHPExcel->getActiveSheet()->setCellValue('R5', 'C E-BED');
      $objPHPExcel->getActiveSheet()->setCellValue('S5', 'C N-BED');
      $objPHPExcel->getActiveSheet()->setCellValue('T5', 'SGL');
      $objPHPExcel->getActiveSheet()->setCellValue('U5', 'APO');
      $objPHPExcel->getActiveSheet()->freezePane('U6');
      $objPHPExcel->getActiveSheet()->getStyle('A5:U5')->applyFromArray(
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
      
      $data = $this->get_tour_information($where_tour,$where_information);
//      print $where;
//      print "<pre>";
//      print_r($data); 
//      print "</pre>";
//      die;
//      if(is_array($data)){
        $no = 0;
        foreach ($data as $key => $value) {
//          foreach($value['information'] AS $ky => $info){
            $no = $no + 1;
            $day = $value['days'];
           $dep = date("d M y", strtotime($value['start_date']));
           $etd = date("H:i", strtotime($value['start_time']));
           $arr = date("d M y", strtotime($value['end_date']));
           $eta = date("H:i", strtotime($value['end_time']));
           $flt = $value['flt'];
           $seat = $value['seat'];
          // $sts = $value['sts'];
           $in = $value['in'];
           $out = $value['out'];
           if($in){
             $in = $in."/";
           }else{
             $in="";
           }
           if($out){
             $out = $out;
           }else{
             $out ="";
           }
           $in_out = $in.$out;
           $book = $value['book'];
           $deposit = $value['deposit'];
           $lunas = $value['lunas'];
           $available_seat = $value['available_seat'];
           $currency = $value['currency'];
           $adult_triple_twin = $value['price']['adult_triple_twin'];
           $child_twin_bed = $value['price']['child_twin_bed'];
           $child_extra_bed = $value['price']['child_extra_bed'];
           $child_no_bed = $value['price']['child_no_bed'];
           $sgl_supp = $value['price']['sgl_supp'];
           $airport_tax = $value['price']['airport_tax'];
             
            $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$key),$no);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$key),$value['title']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.(6+$key),$day);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$key),$dep);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$etd);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.(6+$key),$arr);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.(6+$key),$eta);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.(6+$key),$flt);
           // $objPHPExcel->getActiveSheet()->setCellValue('I'.(6+$key),$sts);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.(6+$key),$in_out);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.(6+$key),$seat);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.(6+$key),$book);
            $objPHPExcel->getActiveSheet()->setCellValue('L'.(6+$key),$deposit);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.(6+$key),$lunas);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.(6+$key),$available_seat);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.(6+$key),$currency);
            $objPHPExcel->getActiveSheet()->setCellValue('P'.(6+$key),$adult_triple_twin);
            $objPHPExcel->getActiveSheet()->getStyle('P'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.(6+$key),$child_twin_bed);
            $objPHPExcel->getActiveSheet()->getStyle('Q'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValue('R'.(6+$key),$child_extra_bed);
            $objPHPExcel->getActiveSheet()->getStyle('R'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValue('S'.(6+$key),($child_no_bed));
            $objPHPExcel->getActiveSheet()->getStyle('S'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValue('T'.(6+$key),($sgl_supp));
            $objPHPExcel->getActiveSheet()->getStyle('T'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->setCellValue('U'.(6+$key),($airport_tax));
            $objPHPExcel->getActiveSheet()->getStyle('U'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
//             }
                
           }
        
      
//      }
      
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
      $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
    //  $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
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
    
    function export_report_payment_xls($filename,$data){
      
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Report Payment AntaVaya ")
							 ->setSubject("Report Payment AntaVaya ")
							 ->setDescription("Report Payment AntaVaya")
							 ->setKeywords("Report Payment AntaVaya")
							 ->setCategory("Report Payment AntaVaya");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:H3');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Report Payment AntaVaya ');
      $objPHPExcel->getActiveSheet()->getStyle('A1:H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1:H3')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
               'size'  => 21,
              'name'  => 'Verdana'
              
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
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
      $styleArray = array(
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),);
      
      $styleArray1 = array(
          'borders' => array(
          'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
          ));
      
      $objPHPExcel->getActiveSheet()->setCellValue('A5', 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B5', 'Date');
      $objPHPExcel->getActiveSheet()->setCellValue('C5', 'Book Code');
      $objPHPExcel->getActiveSheet()->setCellValue('D5', 'Name');
      $objPHPExcel->getActiveSheet()->setCellValue('E5', 'Status');
      $objPHPExcel->getActiveSheet()->setCellValue('F5', 'Payment Type');
      $objPHPExcel->getActiveSheet()->setCellValue('G5', 'Currency');
      $objPHPExcel->getActiveSheet()->setCellValue('H5', 'Nominal IDR');
      $objPHPExcel->getActiveSheet()->freezePane('A6');
      $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
            $styleArray,
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
      $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->applyFromArray($styleArray1);
       $channel2 = array(
                  1 => "Cash",
                  2 => "BCA",
                  3 => "Mega",
                  4 => "Mandiri",
                  5 => "CC");
        $status2 = array(1 => "Draft", 2 => "Confirm", 3 => "Not Paid");
        
        $no = 1;
        foreach ($data as $key => $value) {

           
           if($value['currency'] == "IDR"){
            $nom_idr = $value['nominal'];
            $nom_idr_tot += $value['nominal'];
           
          }elseif($value['currency'] == "USD"){
            $nom_usd = $value['nominal'];
            $nom_usd_tot += $value['nominal'];
            
          }
          if($nom_idr_tot){
            $nom_idr_tot = $nom_idr_tot;
          }else{
            $nom_idr_tot = 0;
          }
           if($nom_idr){
              $nom_idr = $nom_idr;
            }else{
              $nom_idr =0;
            }
            
            if($nom_usd_tot){
              $nom_usd_tot = $nom_usd_tot;
            }else{
              $nom_usd_tot = 0;
            }
            if($nom_usd){
              $nom_usd = $nom_usd;
            }else{
              $nom_usd=0;
            }
             
            $objPHPExcel->getActiveSheet()->setCellValue('A'.(6+$key),$no++);
            
              $objPHPExcel->getActiveSheet()->setCellValue('B'.(6+$key),date("Y-m-d H:i:s", strtotime($value['tanggal'])));
              $objPHPExcel->getActiveSheet()->setCellValue('C'.(6+$key),$value['book_code']);
              $objPHPExcel->getActiveSheet()->setCellValue('D'.(6+$key),$value['name']);
              $objPHPExcel->getActiveSheet()->setCellValue('E'.(6+$key),$status2[$value['status']]);
              $objPHPExcel->getActiveSheet()->setCellValue('F'.(6+$key),$channel2[$value['payment_type']]);
              $objPHPExcel->getActiveSheet()->setCellValue('G'.(6+$key),$value['currency']);
            //  $objPHPExcel->getActiveSheet()->setCellValue('H'.(6+$key),$nom_usd);
            //  $objPHPExcel->getActiveSheet()->getStyle('H'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
              $objPHPExcel->getActiveSheet()->setCellValue('H'.(6+$key),$nom_idr);
              $objPHPExcel->getActiveSheet()->getStyle('H'.(6+$key))->getNumberFormat()->setFormatCode('#,##0');
              $dt_style = "A".(6+$key).":H".(6+$key);
             $objPHPExcel->getActiveSheet()->getStyle($dt_style)->applyFromArray($styleArray);
             $objPHPExcel->getActiveSheet()->getStyle($dt_style)->applyFromArray($styleArray1);
           }
        $jml =5+$no;
        $ak = "A".$jml.":G".$jml;
        $akj = "H".$jml.":I".$jml;
      
      $objPHPExcel->getActiveSheet()->mergeCells($ak);
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($jml),'TOTAL ');
    //  $objPHPExcel->getActiveSheet()->setCellValue('H'.($jml),$nom_usd_tot);
     // $objPHPExcel->getActiveSheet()->getStyle('H'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->getActiveSheet()->setCellValue('H'.($jml),$nom_idr_tot);
      $objPHPExcel->getActiveSheet()->getStyle('H'.($jml))->getNumberFormat()->setFormatCode('#,##0');
      
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
    //  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename."-".date("Y-m-d").'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    function export_report_passport_xls($filename,$id_product_tour_information){
      
       $detail = $this->global_models->get("product_tour_leader", array("id_product_tour_information" => $id_product_tour_information));
     $flight_detail = $this->global_models->get("product_tour_flight_detail", array("id_product_tour_information" => $id_product_tour_information));
     
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour,B.title, A.start_date, A.end_date"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour_information = '{$id_product_tour_information}' ORDER BY B.id_product_tour DESC");
     
        
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Report Passport AntaVaya ")
							 ->setSubject("Report Passport AntaVaya ")
							 ->setDescription("Report Passport AntaVaya")
							 ->setKeywords("Report Passport AntaVaya")
							 ->setCategory("Report Passport AntaVaya");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:I4');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', $info[0]->title."\n ".date("d M", strtotime($info[0]->start_date))." ".date("d M Y", strtotime($info[0]->end_date)));
        $objPHPExcel->getActiveSheet()->getStyle('A1:I4')->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle('A1:I4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  
//      $objPHPExcel->getActiveSheet()->getStyle('A1:V2')->getFill()->getStartColor()->setARGB('FF808080');
      $objPHPExcel->getActiveSheet()->getStyle('A1:I4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
               'size'  => 14,
              'name'  => 'Verdana'
              
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
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
       $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Flight details :');
     $ank1 = 0;
       foreach ($flight_detail as $ky => $val) {
        $objPHPExcel->getActiveSheet()->setCellValue('B'.(7+$ank1),$val->name);
            $ank1 = $ank1 + 1;
          }
          $ank = 9 + $ank1;
          
           $brd = "A".$ank.":I".$ank;
          $brd2 = ($ank - 1);
           $objPHPExcel->getActiveSheet()->setCellValue('H'.($brd2), 'TOUR LEADER : '.$detail[0]->first_name." ".$detail[0]->last_name);
//           $brd = 'A'.$ank.':I'.$ank;
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank), 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank), 'Name');
      $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank), 'Passport No');
      $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank), 'Place Of Issued');
      $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank), 'DATE OF ISSUED & EXPIRED');
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank), 'PLACE OF BIRTH');
      $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank), 'DATE OF BIRTH');
      $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank), 'ADDRESS');
      $objPHPExcel->getActiveSheet()->setCellValue('I'.($ank), 'PHONE');
   //   $objPHPExcel->getActiveSheet()->freezePane('A6');
     
      $objPHPExcel->getActiveSheet()->getStyle($brd)->applyFromArray(
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
      $cek_dt_lead = "no";
     if($detail[0]->id_product_tour_leader > 0 ){
       $cek_dt_lead = "yes";
        if($detail[0]->tanggal_lahir == "0000-00-00"){
              $tanggal_lahir = "";
            }else{
              $tanggal_lahir = date("d M Y", strtotime($detail[0]->tanggal_lahir));
            }

            if($detail[0]->date_of_issued == "0000-00-00"){
              $date_of_issued = "";
            }else{
              $date_of_issued = date("d M Y", strtotime($detail[0]->date_of_issued)).' - '.date("Y", strtotime($detail[0]->date_of_expired));
            }
              $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+1),"1");
              $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+1),$detail[0]->first_name." ".$detail[0]->last_name);
              $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+1),$detail[0]->passport);
              $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+1),$detail[0]->place_of_issued);
              $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+1),$date_of_issued);
              $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+1),$detail[0]->tempat_tanggal_lahir);
              $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+1),$tanggal_lahir);
              $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+1),$detail[0]->address);
              $objPHPExcel->getActiveSheet()->setCellValue('I'.($ank+1),$detail[0]->telphone);
              $nor = $ank+1;
               $brd_old = "A".$nor.":I".$nor;
              $objPHPExcel->getActiveSheet()->getStyle($brd_old)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true
            ),
           
          )
      );
     }
       
              
      $data = $this->global_models->get_query("SELECT A.id_product_tour_book,B.id_product_tour_customer,A.address,B.first_name,B.last_name,B.passport,"
        . " B.place_of_issued,B.date_of_issued,B.date_of_expired,B.tanggal_lahir,B.tempat_tanggal_lahir,B.telphone"
        . " FROM product_tour_book AS A"
        . " LEFT JOIN product_tour_customer AS B ON A.id_product_tour_book = B.id_product_tour_book"
        . " WHERE A.id_product_tour_information = '$id_product_tour_information'");
      
      if($cek_dt_lead == "yes"){
        $no =1;
      }else{
        $no =0;
      }
        foreach ($data as $key => $value) {
          $no = $no + 1;
      if($value->tanggal_lahir == "0000-00-00"){
              $tanggal_lahir = "";
            }else{
              $tanggal_lahir = date("d M Y", strtotime($value->tanggal_lahir));
            }

            if($value->date_of_issued == "0000-00-00"){
              $date_of_issued = "";
            }else{
              $date_of_issued = date("d M Y", strtotime($value->date_of_issued)).' - '.date("Y", strtotime($value->date_of_expired));
            }
             
              $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+$no),$no);
              $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+$no),$value->first_name.' '.$value->last_name);
              $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+$no),$value->passport);
              $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+$no),$value->place_of_issued);
              $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+$no),$date_of_issued);
              $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+$no),$value->tempat_tanggal_lahir);
              $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+$no),$tanggal_lahir);
              $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+$no),$value->address);
              $objPHPExcel->getActiveSheet()->setCellValue('I'.($ank+$no),$value->telphone);
             
           }
//        $jml =5+$no;
//        $ak = "A".$jml.":G".$jml;
//        $akj = "H".$jml.":I".$jml;
//      
//      $objPHPExcel->getActiveSheet()->mergeCells($ak);
//      $objPHPExcel->getActiveSheet()->setCellValue('A'.($jml),'TOTAL ');
    //  $objPHPExcel->getActiveSheet()->setCellValue('H'.($jml),$nom_usd_tot);
     // $objPHPExcel->getActiveSheet()->getStyle('H'.($jml))->getNumberFormat()->setFormatCode('#,##0');
//      $objPHPExcel->getActiveSheet()->setCellValue('H'.($jml),$nom_idr_tot);
//      $objPHPExcel->getActiveSheet()->getStyle('H'.($jml))->getNumberFormat()->setFormatCode('#,##0');
//      
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
//            
//          )
//      );
      

      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    function export_room_list_xls($filename,$id_product_tour_information){
      
       $detail = $this->global_models->get("product_tour_leader", array("id_product_tour_information" => $id_product_tour_information));
     $flight_detail = $this->global_models->get("product_tour_flight_detail", array("id_product_tour_information" => $id_product_tour_information));
     
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour,B.title, A.start_date, A.end_date"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour_information = '{$id_product_tour_information}' ORDER BY B.id_product_tour DESC");
     
        
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Report Passport AntaVaya ")
							 ->setSubject("Report Passport AntaVaya ")
							 ->setDescription("Report Passport AntaVaya")
							 ->setKeywords("Report Passport AntaVaya")
							 ->setCategory("Report Passport AntaVaya");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:H4');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', "ROOMING LIST \n ".$info[0]->title."\n ".date("d M", strtotime($info[0]->start_date))." ".date("d M Y", strtotime($info[0]->end_date)));
        $objPHPExcel->getActiveSheet()->getStyle('A1:H4')->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle('A1:H4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  

      $objPHPExcel->getActiveSheet()->getStyle('A1:H4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
               'size'  => 14,
              'name'  => 'Verdana'
              
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
      $styleArray = array(
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),);
      
      $styleArray1 = array(
          'borders' => array(
          'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
          ));
      
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
       $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Flight details :');
     $ank1 = 0;
       foreach ($flight_detail as $ky => $val) {
        $objPHPExcel->getActiveSheet()->setCellValue('B'.(7+$ank1),$val->name);
            $ank1 = $ank1 + 1;
          }
          $ank = 9 + $ank1;
          
           $brd = "A".$ank.":H".$ank;
          $brd2 = ($ank - 1);
           $objPHPExcel->getActiveSheet()->setCellValue('H'.($brd2), 'TOUR LEADER : '.$detail[0]->first_name." ".$detail[0]->last_name);
//           $brd = 'A'.$ank.':I'.$ank;
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank), 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank), 'Name Of Pax');
      $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank), 'Room Type');
      $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank), 'Room No.');
      $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank), 'Passport No');
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank), 'Passport Expired Date');
      $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank), 'DATE OF BIRTH');
      $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank), 'Remarks');
   //   $objPHPExcel->getActiveSheet()->freezePane('A6');
     $objPHPExcel->getActiveSheet()->getStyle($brd)->applyFromArray($styleArray1);
      $objPHPExcel->getActiveSheet()->getStyle($brd)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
              
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
      $cek_dt_lead = "no";
     if($detail[0]->id_product_tour_leader > 0 ){
       $cek_dt_lead = "yes";
        if($detail[0]->tanggal_lahir == "0000-00-00"){
              $tanggal_lahir = "";
            }else{
              $tanggal_lahir = date("d M Y", strtotime($detail[0]->tanggal_lahir));
            }

            if($detail[0]->date_of_issued == "0000-00-00"){
              $date_of_issued = "";
            }else{
              $date_of_issued = date("d M Y", strtotime($detail[0]->date_of_issued)).' - '.date("Y", strtotime($detail[0]->date_of_expired));
            }
              $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+1),"1");
              $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+1),$detail[0]->first_name." ".$detail[0]->last_name);
              $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+1),"SGL");
              $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+1),"");
              $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+1),$detail[0]->passport);
              $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+1),$date_of_issued);
              $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+1),$tanggal_lahir);
              $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+1),"Tour Leader");
              $nor = $ank+1;
               $brd_old = "A".$nor.":H".$nor;
               
               $objPHPExcel->getActiveSheet()->getStyle($brd_old)->applyFromArray($styleArray);
               $objPHPExcel->getActiveSheet()->getStyle($brd_old)->applyFromArray($styleArray1);
               
              $objPHPExcel->getActiveSheet()->getStyle($brd_old)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
              'size'  => 12,
            ),
           
          )
      );
     }
       
              
//      $data = $this->global_models->get_query("SELECT A.id_product_tour_book,B.id_product_tour_customer,A.address,B.first_name,B.last_name,B.passport,"
//        . " B.place_of_issued,B.date_of_issued,B.date_of_expired,B.tanggal_lahir,B.tempat_tanggal_lahir,B.telphone"
//        . " FROM product_tour_book AS A"
//        . " LEFT JOIN product_tour_customer AS B ON A.id_product_tour_book = B.id_product_tour_book"
//        . " WHERE A.id_product_tour_information = '$id_product_tour_information'");
     
//     $data = $this->global_models->get_query("SELECT A.id_product_tour_book,B.id_product_tour_customer,"
//        . " GROUP_CONCAT(CONCAT(B.first_name,' ',B.last_name)) AS name,"
//        . " GROUP_CONCAT(B.passport) AS no_passport,GROUP_CONCAT(B.date_of_issued) AS date_of_issued,GROUP_CONCAT(B.date_of_expired) AS date_of_expired,"
//        . " GROUP_CONCAT(B.tanggal_lahir) AS tanggal_lahir"
//        . " FROM product_tour_book AS A"
//        . " LEFT JOIN product_tour_customer AS B ON A.id_product_tour_book = B.id_product_tour_book"
//        . " WHERE A.id_product_tour_information = '$id_product_tour_information'"
//        . " GROUP BY A.id_product_tour_book");
     
//     $data = $this->global_models->get_query("SELECT A.id_product_tour_book,A.room AS jml_room,B.id_product_tour_customer,"
//        . " GROUP_CONCAT(CONCAT(B.first_name,' ',B.last_name)) AS name,"
//        . " GROUP_CONCAT(B.passport) AS no_passport,GROUP_CONCAT(B.date_of_issued) AS date_of_issued,GROUP_CONCAT(B.date_of_expired) AS date_of_expired,"
//        . " GROUP_CONCAT(B.tanggal_lahir) AS tanggal_lahir,GROUP_CONCAT(B.room) AS room"
//        . " FROM product_tour_book AS A"
//        . " LEFT JOIN product_tour_customer AS B ON A.id_product_tour_book = B.id_product_tour_book"
//        . " WHERE A.id_product_tour_information = '$id_product_tour_information'"
//        . " GROUP BY A.id_product_tour_book ORDER BY B.id_product_tour_customer ASC");
//     print $last = $this->db->last_query(); die;
     
     $data = $this->global_models->get("product_tour_book", array("id_product_tour_information" => $id_product_tour_information));
     
      if($cek_dt_lead == "yes"){
        $no =1;
      }else{
        $no =0;
      }
        
        $no_k = 0;
        foreach ($data as $key => $value) {
         $no_k = $no_k + 1;
//      if($value->tanggal_lahir == "0000-00-00"){
//              $tanggal_lahir = "";
//            }else{
//              $tanggal_lahir = date("d M Y", strtotime($value->tanggal_lahir));
//            }
//
//            if($value->date_of_issued == "0000-00-00"){
//              $date_of_issued = "";
//            }else{
//              $date_of_issued = date("d M Y", strtotime($value->date_of_issued)).' - '.date("Y", strtotime($value->date_of_expired));
//            }
          
           $name = explode(",", $value->name);
            $no_passport = explode(",", $value->no_passport);
            $date_of_issued = explode(",", $value->date_of_issued);
            $date_of_expired = explode(",", $value->date_of_expired);
            $tanggal_lahir = explode(",", $value->tanggal_lahir);
//           $jumlah =  count($name);
            $i = 0;
            $len = count($name);
           foreach ($name as $ky_name => $val_name) {
             $no = $no + 1;
              if ($i == 0) {
                    $nilai_data_pertama = $no;
                } else if ($i == $len - 1) {
                  $nilai_data_terakhir = $no;
                }
              
              if($date_of_issued[$ky_name] == "0000-00-00"){
              $date_of_issued2 = "";
            }else{
              $date_of_issued2 = date("d M Y", strtotime($date_of_issued[$ky_name]));
            }
            
            if($date_of_expired[$ky_name] == "0000-00-00"){
              $date_of_expired2 = "";
            }else{
              $date_of_expired2 = date("d M Y", strtotime($date_of_expired[$ky_name]));
            }
            $hs_date_of_passport =  $date_of_issued2."-".$date_of_expired2;
     
       if($tanggal_lahir[$ky_name] == "0000-00-00"){
        $tanggal_lahir2 = "";
          }else{
            $tanggal_lahir2 = date("d M Y", strtotime($tanggal_lahir[$ky_name]));
          }
          $hs_tanggal_lahir2 = $tanggal_lahir2;
          
             $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+$no),$no);
              $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+$no),$val_name);
              $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+$no),"");
              $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+$no),"");
              $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+$no),$no_passport[$ky_name]);
              $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+$no),$hs_date_of_passport);
              $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+$no),$hs_tanggal_lahir2);
              $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+$no),"");
               
             
              
        }
         if($jumlah > 1){
                
                $nor3_new = $ank+$nilai_data_pertama;
                $nor3 = $ank+$nilai_data_terakhir;
                $brd_old2 = "B".$nor3_new.":G".$nor3;
                $angk_no = "A".$nor3_new;
               $objPHPExcel->getActiveSheet()->getStyle($angk_no)->applyFromArray($styleArray1);
               
             $objPHPExcel->getActiveSheet()->getStyle($brd_old2)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
              
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
            
          )
      );
//             $nilai_data_pertama +=$nilai_data_pertama; 
//             $nilai_data_terakhir +=$nilai_data_terakhir;
              }else{
                $nor3 = $ank+$no;
                $brd_old2 = "A".$nor3.":H".$nor3;
               
               $objPHPExcel->getActiveSheet()->getStyle($brd_old2)->applyFromArray($styleArray);
               $objPHPExcel->getActiveSheet()->getStyle($brd_old2)->applyFromArray($styleArray1);
              }
         
             
           }
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    function export_room_list_xls1($filename,$id_product_tour_information){
      
       $detail = $this->global_models->get("product_tour_leader", array("id_product_tour_information" => $id_product_tour_information));
     $flight_detail = $this->global_models->get("product_tour_flight_detail", array("id_product_tour_information" => $id_product_tour_information));
     
          $info = $this->global_models->get_query("SELECT A.id_product_tour_information,A.id_product_tour,B.title, A.start_date, A.end_date"
        . " FROM product_tour_information AS A"
        . " LEFT JOIN product_tour AS B ON A.id_product_tour = B.id_product_tour"
        . " WHERE A.id_product_tour_information = '{$id_product_tour_information}' ORDER BY B.id_product_tour DESC");
     
        
      $objPHPExcel = $this->phpexcel;
      $objPHPExcel->getProperties()->setCreator("AntaVaya")
							 ->setLastModifiedBy("AntaVaya")
							 ->setTitle("Report Passport AntaVaya ")
							 ->setSubject("Report Passport AntaVaya ")
							 ->setDescription("Report Passport AntaVaya")
							 ->setKeywords("Report Passport AntaVaya")
							 ->setCategory("Report Passport AntaVaya");

      $objPHPExcel->setActiveSheetIndex(0);
      
      $objPHPExcel->getActiveSheet()->mergeCells('A1:H4');
      $objPHPExcel->getActiveSheet()->setCellValue('A1', "ROOMING LIST \n ".$info[0]->title."\n ".date("d M", strtotime($info[0]->start_date))." ".date("d M Y", strtotime($info[0]->end_date)));
        $objPHPExcel->getActiveSheet()->getStyle('A1:H4')->getAlignment()->setWrapText(true);
      $objPHPExcel->getActiveSheet()->getStyle('A1:H4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
  

      $objPHPExcel->getActiveSheet()->getStyle('A1:H4')->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
               'size'  => 14,
              'name'  => 'Verdana'
              
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
      $styleArray = array(
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,),);
      
      $styleArray1 = array(
          'borders' => array(
          'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
          ));
      
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      
       $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Flight details :');
     $ank1 = 0;
       foreach ($flight_detail as $ky => $val) {
        $objPHPExcel->getActiveSheet()->setCellValue('B'.(7+$ank1),$val->name);
            $ank1 = $ank1 + 1;
          }
          $ank = 9 + $ank1;
          
           $brd = "A".$ank.":J".$ank;
          $brd2 = ($ank - 1);
           $objPHPExcel->getActiveSheet()->setCellValue('H'.($brd2), 'TOUR LEADER : '.$detail[0]->first_name." ".$detail[0]->last_name);
//           $brd = 'A'.$ank.':I'.$ank;
      $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank), 'No');
      $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank), 'Name Of Pax');
      $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank), 'Room Type');
      $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank), 'Room No.');
      $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank), 'Passport No');
      $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank), 'Passport Expired Date');
      $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank), 'DATE OF BIRTH');
      $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank), 'Remarks');
   //   $objPHPExcel->getActiveSheet()->freezePane('A6');
     $objPHPExcel->getActiveSheet()->getStyle($brd)->applyFromArray($styleArray1);
      $objPHPExcel->getActiveSheet()->getStyle($brd)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
              
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
      $cek_dt_lead = "no";
     if($detail[0]->id_product_tour_leader > 0 ){
       $cek_dt_lead = "yes";
        if($detail[0]->tanggal_lahir == "0000-00-00"){
              $tanggal_lahir = "";
            }else{
              $tanggal_lahir = date("d M Y", strtotime($detail[0]->tanggal_lahir));
            }

            if($detail[0]->date_of_issued == "0000-00-00"){
              $date_of_issued2 = "";
            }else{
              $date_of_issued2 = date("d M Y", strtotime($detail[0]->date_of_issued));
            }

                if($detail[0]->date_of_expired == "0000-00-00"){
                  $date_of_expired2 = "";
                }else{
                  $date_of_expired2 = date("d M Y", strtotime($detail[0]->date_of_expired));
                }
                
                $hs_date_of_passport =  $date_of_issued2."-".$date_of_expired2;

              $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+1),"1");
              $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+1),$detail[0]->first_name." ".$detail[0]->last_name);
             
              $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+1),"SGL");
              $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+1),"");
              $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+1),$detail[0]->passport);
              $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+1),$hs_date_of_passport);
              $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+1),$tanggal_lahir);
              $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+1),"Tour Leader");
              $nor = $ank+1;
               $brd_old = "A".$nor.":H".$nor;
               
               $objPHPExcel->getActiveSheet()->getStyle($brd_old)->applyFromArray($styleArray);
               $objPHPExcel->getActiveSheet()->getStyle($brd_old)->applyFromArray($styleArray1);
               
              $objPHPExcel->getActiveSheet()->getStyle($brd_old)->applyFromArray(
          array(
            'font'    => array(
              'bold'      => true,
              'size'  => 12,
            ),
           
          )
      );
     }
       
     $data = $this->global_models->get("product_tour_book", array("id_product_tour_information" => $id_product_tour_information));
     
      if($cek_dt_lead == "yes"){
        $no =1;
        $td_room = 1;
        
      }else{
        $no =0;
       
      }
        
        $no_k = 0;
        $td_group = 0;
        foreach ($data as $key => $value) {
         $td_group = $td_group + 1;
          $td_room = $td_room + 1;
         $group_pmsan = "Group Pemesan ".$td_group;
         $group_room = "Room ".$td_room;
         $data2 = $this->global_models->get_query("SELECT A.id_product_tour_book,A.room AS jml_room,
           B.id_product_tour_customer, CONCAT(B.first_name,' ',B.last_name) AS name,
           B.passport AS no_passport,B.date_of_issued AS date_of_issued,B.date_of_expired AS date_of_expired, 
           B.tanggal_lahir AS tanggal_lahir,B.room AS room 
           FROM product_tour_book AS A 
           LEFT JOIN product_tour_customer AS B ON A.id_product_tour_book = B.id_product_tour_book 
           WHERE A.id_product_tour_book = '$value->id_product_tour_book'   ORDER BY B.id_product_tour_customer ASC");
//         print $tes = $this->db->last_query(); die;
         
           $id_product_tour_book =  0;
         foreach($data2 as $key2 => $value2){
             $no = $no + 1;
             if($value2->tanggal_lahir == "0000-00-00"){
              $tanggal_lahir = "";
            }else{
              $tanggal_lahir = date("d M Y", strtotime($value2->tanggal_lahir));
            }

            if($value2->date_of_issued == "0000-00-00"){
              $date_of_issued2 = "";
            }else{
              $date_of_issued2 = date("d M Y", strtotime($value2->date_of_issued));
            }

                if($value2->date_of_expired == "0000-00-00"){
                  $date_of_expired2 = "";
                }else{
                  $date_of_expired2 = date("d M Y", strtotime($value2->date_of_expired));
                }
                
                 $hs_date_of_passport =  $date_of_issued2."-".$date_of_expired2;
                 
             if($value2->id_product_tour_book != $id_product_tour_book){
               
               $id_product_tour_book = $value2->id_product_tour_book;
                $id_room = $value2->room;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+$no),$no);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+$no),$value2->name);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+$no),"");
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+$no),"");
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+$no),$value2->no_passport);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+$no),$hs_date_of_passport);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+$no),$tanggal_lahir);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+$no),"");
                
                 $brd = "A".($ank+$no).":J".($ank+$no);
                $objPHPExcel->getActiveSheet()->getStyle($brd)->applyFromArray(
          array(
            
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
              
            ),
            
          )
      );
             }else{
               if($value2->room != $id_room){
                 $id_room = $value2->room;
                  $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+$no),$no);
                  $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+$no),$value2->name);
                  $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+$no),"");
                  $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+$no),"");
                  $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+$no),$value2->no_passport);
                  $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+$no),$hs_date_of_passport);
                  $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+$no),$tanggal_lahir);
                  $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+$no),"");
                  
                   $brd = "B".($ank+$no).":I".($ank+$no);
                $objPHPExcel->getActiveSheet()->getStyle($brd)->applyFromArray(
          array(
            'borders' => array(
              'top'     => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              ),
            ),
            
          )
      );
               }else{
                 
                  $objPHPExcel->getActiveSheet()->setCellValue('A'.($ank+$no),$no);
                  $objPHPExcel->getActiveSheet()->setCellValue('B'.($ank+$no),$value2->name);
                  $objPHPExcel->getActiveSheet()->setCellValue('C'.($ank+$no),"");
                  $objPHPExcel->getActiveSheet()->setCellValue('D'.($ank+$no),"");
                  $objPHPExcel->getActiveSheet()->setCellValue('E'.($ank+$no),$value2->no_passport);
                  $objPHPExcel->getActiveSheet()->setCellValue('F'.($ank+$no),$hs_date_of_passport);
                  $objPHPExcel->getActiveSheet()->setCellValue('G'.($ank+$no),$tanggal_lahir);
                  $objPHPExcel->getActiveSheet()->setCellValue('H'.($ank+$no),"");
                  
               }
              
              
//                $nor3 = $ank+$no;
//                $brd_old2 = "A".$nor3.":H".$nor3;
//               
//               $objPHPExcel->getActiveSheet()->getStyle($brd_old2)->applyFromArray($styleArray);
//               $objPHPExcel->getActiveSheet()->getStyle($brd_old2)->applyFromArray($styleArray1);
             }
             
              
            
         }
           
          
              
               
             
              
        }
       
         
      
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
//      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    
      
      $objPHPExcel->setActiveSheetIndex(0);
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
      header('Cache-Control: max-age=0');
      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
      $objWriter->save('php://output');die;
    }
    
    
}
?>
