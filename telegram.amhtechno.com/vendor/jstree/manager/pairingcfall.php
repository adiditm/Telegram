<?php


           include_once("../framework/admin_headside.blade.php");
?>	



<?php

$vSpy = md5('spy').md5($_GET['uMemberId']);



 if ($_GET['uMemberId'] != '')

    $vUserActive=$_GET['uMemberId'];

 else  $vUserActive=$_POST['uname'];









$vAwal=$_POST['dc'];

$vAkhir=$_POST['dc1'];



if ($vAwal=="")

	$vAwal=$_GET['uAwal'];

if ($vAkhir=="")

	$vAkhir=$_GET['uAkhir'];





if ($vAwal=="")

   $vAwal=date('Y-m-d', strtotime('-30 days'));

   

if ($vAkhir=="")

   $vAkhir=$oPhpdate->getNowYMD("-");



   

$vPrevWeek=$oMydate->getPrevWeek($vTanggal);

$vWeek=$oMydate->getWeek($vTanggal);

$vYear=$oMydate->getYear($vTanggal);   

if ($vWeek==52)

    $vPrevYear=$vYear-1;

else

     $vPrevYear= $vYear;	

$oMydate->setCritPrevDate("ftglkomisi",$vTanggal);	 



$vPage=$_GET['uPage'];

$vBatasBaris=25;

if ($vPage=="")

 	$vPage=0;

$vStartLimit=$vPage * $vBatasBaris;	



$vCrit.=" and date(ftanggal) >= '$vAwal' and date(ftanggal) <= '$vAkhir'" ;







 $vsql="select * from tb_kom_coupcf where fidreceiver='$vUserActive' ";

 $vsql.=$vCrit;

 $vsql.=" order by ftanggal ";



				$db->query($vsql);
				$vArrData="";
				$vArrHead=array('No.','Date','ID CF','Username','Name','LGV','RGV');
				$vArrBlank=array('','','','','','','');
				$vArrDateFilter=array('Filter :  '.$vFilterText,'','','','','','');
				
				
				$i=0;$vTot=0;
				$vArrData[]=$vArrDateFilter;
				$vArrData[]=$vArrBlank;
				$vArrData[]=$vArrHead;
				
				while ($db->next_record()) { //Convert Excel
				     $i++;

				 $vTanggal=$db->f('ftanggal');
				 $vIdMember=$db->f('fidreceiver');
				 $vNama=$oMember->getMemberName($vIdMember);
				 $vIDFee=$db->f('fidfee');
				 $vSubTotL=$oKomisi->getCFPosByID($vUserActive,'L',$vIDFee);	
				 $vSubTotR=$oKomisi->getCFPosByID($vUserActive,'R',$vIDFee);	
   
				     

				 //$vArrHead=array('No.','Date','ID CF','Username','Name','LGV','RGV');


				 $vArrData[]=array($i,$vTanggal,$vIDFee,$vIdMember,$vNama,$vSubTotL,$vSubTotR);
					//$vArrMem['fpassword'][$i]=$oSystem->doED('decrypt',$db->f('fpassword'));
				
				}
				
				//$vArrTot=array('','','','','','',$vTot);
				//$vArrData[]=$vArrTot;
				$_SESSION['cfstatus']=$vArrData;




 $db->query($vsql);

 $db->next_record();

 $vRecordCount=$db->num_rows();

 $vPageCount=ceil($vRecordCount/$vBatasBaris);



?>







<script language="JavaScript" type="text/JavaScript">





$(document).ready(function(){



  <? if ($_GET['uMemberId'] != '') { ?>

  $('#caption').html('Report Carry Forward Omzet for member <?=$vUserActive?> (<?=$oMember->getMemberName($vUserActive);?>)');

  <?} else { ?>

  $('#caption').html('Report Carry Forward Omzet');

  <? } ?>



   





      $('#dc').datepicker({

                    format: "yyyy-mm-dd",

                    autoclose : true

    }).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    });

  

  

       $('#dc1').datepicker({

                    format: "yyyy-mm-dd",

                    autoclose : true

    }).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    });


});





function doBayar(pKode,pKomisi,pSisa,pBatas) {

   pSisa=parseInt(pSisa);

   pBatas=parseInt(pBatas);



    if (pSisa<pBatas) {

	   alert('Komisi tidak bisa dibayarkan karena sisa komisi kurang dari batas minimum transfer!');

	   return false;

	}

	window.location='admin.php?menu=buktitfr&uKd='+pKode+'&uKom='+pKomisi;

}





function doBayarBuy(pKode,pKomisi,pSisa,pBatas) {

   pSisa=parseInt(pSisa);

   pBatas=parseInt(pBatas);



    if (pSisa<pBatas) {

	   alert('Komisi tidak bisa dibayarkan karena sisa komisi kurang dari batas minimum transfer!');

	   return false;

	}

	window.location='admin.php?menu=buktitfrsell&uKd='+pKode+'&uKom='+pKomisi;

}//-->

</script>

	<!-- <link rel="stylesheet" href="../css/screen.css">-->



	

	

 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />



	<div id="content" class="content">
			<ol class="breadcrumb pull-right">
				<h4><li><a href="javascript:;">Carry Forward Status</a></li></h4>
				
			</ol>
<h1 class="page-header">&nbsp;</small></h1>

<form action="" method="post" name="demoform">

<div style="display:inline" align="left">
 <strong>Username: </strong>
 <input style="width:200px;display:inline" class="form-control" name="uname" id="uname" size="11" value="<?=$vUserActive?>" onKeyUp="this.value=this.value.toUpperCase()">&nbsp; 
 </div> <br>        
          <div style="display:inline" align="left">
 
<br>
          <strong>Date: </strong>

          <input style="width:100px;display:inline" class="form-control" name="dc" id="dc" size="11" value="<?=$vAwal?>">&nbsp; 
			  <strong>to</strong>

          <input style="width:100px;display:inline" class="form-control" name="dc1" id="dc1" size="11" value="<?=$vAkhir?>"> &nbsp;&nbsp;

          <input style="display:inline" name="Submit22" type="submit" class="btn btn-success" value="Refresh">

          

          </div>

          <br /><br />

<br />





    <div class="table-responsive">

        <table width="90%" border="0" class="table table-striped">

          <tr >

            <td width="4%" style="height: 24px; width: 5%;"><strong>No.</strong></td>

            <td width="12%" style="height: 24px"><div align="center"><strong>Date</strong></div></td>

            <td style="height: 24px; width: 20%;"><strong>ID CF</strong></td>

            <td align="center" style="height: 24px; width: 16%;"><strong>
			Username  </strong></td>

            <td width="21%" align="center" style="width: 23%; height: 24px;"><strong>Name</strong></td>

            <td align="center" style="height: 24px; width: 9%;"><strong>&nbsp;			LGV</strong></td>

            <td align="center" style="height: 24px; width: 8%;"><strong> RGV</strong></td>

          </tr>

          <? 

             $vNo=0;

			 $vsql="select distinct fidreceiver, ffee,date(ftanggal) as ftanggal,fidfee from tb_kom_coupcf where fidreceiver='$vUserActive' "; 

			 $vsql.=$vCrit;

			 $vsql.=" order by ftanggal ";

			$vsql.="limit $vStartLimit ,$vBatasBaris ";

		     $db->query($vsql);

			 $vTotJualL=0;$vTotalJualR=0;

			 while ($db->next_record()) {

			 $vNo++;

				 $vTanggal=$db->f('ftanggal');
				// $vTanggal=$oMydate->dateSub($vTanggal,1,'day');

				 $vIdMember=$db->f('fidreceiver');

				 $vNama=$oMember->getMemberName($vIdMember);

				 $vIDFee=$db->f('fidfee');

		  ?>

          <tr>

            <td style="width: 5%; height: 24px;" ><?=$vNo?></td>

            <td style="height: 24px" ><?=$oPhpdate->YMD2DMY($vTanggal,"-")?></td>

            <td style="height: 24px; width: 20%;"><?=$db->f('fidfee')?></td>

            <td style="height: 24px; width: 16%;" ><?=$vIdMember?></td>

            <td style="width: 23%; height: 24px;" ><?=$vNama?></td>

            <td style="width: 9%; height: 24px;" ><div align="right">

            <? 

				$vSubTot=$oKomisi->getCFPosByID($vUserActive,'L',$vIDFee);				

				echo  number_format($vSubTot,0,",",".");

				$vTotalJualL=$vSubTot;

            

            ?></div></td>

            <td style="width: 8%; height: 24px;" >

            <div align="right"><? 

				$vSubTot=$oKomisi->getCFPosByID($vUserActive,'R',$vIDFee);				

				echo  number_format($vSubTot,0,",",".");

             	$vTotalJualR=$vSubTot;

            

            ?></div></td>

          </tr>

           <? } ?>

          <tr style="display:">

            <td style="width: 5%" >&nbsp;</td>

            <td ><div align="right"><strong>Current Status </strong></div></td>

            <td style="width: 20%" >&nbsp;</td>

            <td style="width: 16%" ><div align="right"></div></td>

            <td >&nbsp;</td>

            <td align="right"><strong>

              <?=number_format($vTotalJualL,0,",",".")?>

            </strong></td>

            <td align="right"><strong>

              <?=number_format($vTotalJualR,0,",",".")?>

            </strong></td>

          </tr>

        </table>    

  </div>  

            

     <table width="90%">

     <tr>

      <td align="center">

        

        <p>

          <?

   for ($i=0;$i<$vPageCount;$i++) {

     $vOffset=$i*$vBatasBaris;

     if ($i!=$vPage) {

          $vOP=$_GET['op'];

?>

          <a href="../memstock/pairingcf.php?uMemberId=<?=$vUserActive?>&op=<?=$vOP?>&uPage=<?=$i?>&uAwal=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAwal,"-"),"-")?>&uAkhir=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAkhir,"-"),"-")?>" >

          <?=$i+1?>

          </a>

          <?

  } else {

?>

          <?=$i+1?>

          <? } ?>

          <?  } //while?>

<br>

        </p></td>

    </tr>

    <tr> 

      <td height="5" align="center" valign="middle"> <div align="right"></div>

        <hr> </td>

    </tr>

    <?php

   

  if ($baris==$Akhiran)

  {

  ?>

    <?php

  }

  ?>

  </table>

Legend : <br><br>

Date : Pairing Date<br>

ID CF : Carry Forward Calculation ID<br>

		  Username : Your Username<br>

Name : Your Name<br>

LGV : Your Left Group Value Turnover<br>

RGV : Your Right Group Value Turnover  <br>

  

</form>

      <!-- page end-->


<button style="margin-left:2em" class="btn btn-info btn-sm" onClick="document.location.href='../manager/getexcel.php?arr=cfstatus&file=cfstatus_report_<?=$vUserChoosed?>'"><i class="fa fa-file-text-o"></i> Export Excel</button> 
        <!--body wrapper end-->





<!-- Placed js at the end of the document so the pages load faster -->



<script src="../js/jquery-ui-1.9.2.custom.min.js"></script>

<script src="../js/jquery-migrate-1.2.1.min.js"></script>



<script src="../js/modernizr.min.js"></script>

<script src="../js/jquery.nicescroll.js"></script>

<script src="../js/jquery.price_format.js"></script>









<script type="text/javascript" src="../js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="../js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

<script type="text/javascript" src="../js/bootstrap-daterangepicker/moment.min.js"></script>

<script type="text/javascript" src="../js/bootstrap-daterangepicker/daterangepicker.js"></script>

<script type="text/javascript" src="../js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>

<script type="text/javascript" src="../js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!--common scripts for all pages-->

<script src="../js/pickers-init.js"></script>

<script src="../js/scripts.js"></script>


	</div>
	<!-- end page container -->
	
<? include_once("../framework/admin_bottomjs.blade.php")?>	
	<script>
		$(document).ready(function() {
			App.init();
			Dashboard.init();
		});
	</script>

<? include_once("../framework/admin_footside.blade.php") ; ?>