<?php require_once ('fetchdetails.php');?>

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <div class="col-xs-6">
                <h3><?php   echo $engagementCOunt ?></h3>

              <p>Engagements</p>
              </div>
              <div class="col-xs-6">
                <h3><?php echo 0?></h3>

              <p>Pending Request</p>
              </div>
              
            </div>
            
              <a href="#" class="small-box-footer"> <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>







        <!-- ./col -->
        <div class="col-lg-6 ">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <div class="col-xs-4">
                <h3><?php echo 0?></h3>

              <p>Coprates Registered</p>
              </div>
              <div class="col-xs-4">
                <h3><?php echo $requestCOunt?></h3>

              <p>Registers Users</p>
              </div>

              <div class="col-xs-4">
                <h3><?php
                  echo $drivercont
                  ?></h3>

                <p>Registers Drivers</p>
              </div>
              
            </div>
            <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->


        <!-- ./col -->
       
        <!-- ./col -->
      
       
        <!-- ./col -->
      </div>
     