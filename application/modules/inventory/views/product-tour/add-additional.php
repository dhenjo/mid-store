<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header">
                <!--<h3 class="box-title">Quick Example</h3>-->
            </div><!-- /.box-header -->
            <!-- form start -->
            <?php print $this->form_eksternal->form_open("", 'role="form"', 
                    array("id_detail" => $detail[0]->id_product_tour_information))?>
              <div class="box-body">
               
                  <div class="control-group">
                      <label style="width: 100%">Product Tour Information</label>
                      <table class="table table-striped">
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Avail<br>Seat</th>
                        <th>Adult<br>Triple/<br>Twin</th>
                        <th>Child<br>Twin<br>Bed</th>
                        <th>Child<br>Extra<br>Bed</th>
                        <th>Child<br>No<br>Bed</th>
                        <th>SGL<br>SUPP</th>
                        <th>DP</th>
                        <th>Discount</th>
                        <th>Airport<br>Tax &<br>Flight<br>Insurance</th>
                    </tr>
                    
                    <?php
                  print "<tr>";
                  print "<td>".$detail[0]->start_date."</td>";
                  print "<td>".$detail[0]->end_date."</td>";
                  print "<td>".$detail[0]->available_seat."</td>";
                  print "<td>".$detail[0]->adult_triple_twin."</td>";
                  print "<td>".$detail[0]->child_twin_bed."</td>";
                  print "<td>".$detail[0]->child_extra_bed."</td>";  
                  print "<td>".$detail[0]->child_no_bed."</td>";
                  print "<td>".$detail[0]->sgl_supp."</td>";
                  print "<td>".$detail[0]->dp." % </td>";
                  print "<td>".$detail[0]->discount."</td>";
                  print "<td>".$detail[0]->airport_tax."</td>";
                 print "</tr>";
            ?>
              </table>
              <br><br>
        <?php
            if($item){
              $no = 0;
              $no1 = 0;
                  foreach ($item AS $inf){ ?>
    <div class='box-body col-sm-6'>
    <div class="control-group">
      <label><div class='number_additional'>Additional <?php print $no = $no + 1; ?> </div></label>
      <?php print $this->form_eksternal->form_dropdown('name_additional[]', $dropdown, array($inf->id_product_tour_master_additional), 'class="form-control" placeholder="Additional"')?>
      </div>
      </div>
     <div class='box-body col-sm-6'>
      <div class="control-group">
      <label>Nominal <?php print $no1 = $no1 + 1; ?></label>
      <?php print $this->form_eksternal->form_input('nominal_additional[]', $inf->nominal, ' class="form-control input-sm" placeholder="Nominal"');?>
      </div>
      </div>           
    <br><br><br><br>
              <?php
                  print $this->form_eksternal->form_input('id_product_tour_optional_additional[]', $inf->id_product_tour_optional_additional, 
                      'style="display: none"');
                 print "</tr>";
                  }
                }
            ?>      
    
       <span id="tambah-additional">
                    </span>
     <a href="javascript:void(0)" onclick="tambah_items_additional()" class="btn btn-info"><?php print lang("Add")?></a>
             
     <br><br>
              </div>
              <div class="box-footer" >
                  <button class="btn btn-primary" type="submit">Save changes</button>
                  <a href="<?php print site_url("inventory/product-tour")?>" class="btn btn-warning"><?php print lang("cancel")?></a>
              </div>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div>   <!-- /.row -->