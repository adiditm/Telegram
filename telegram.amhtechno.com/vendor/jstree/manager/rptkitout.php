<? include_once("../framework/masterheader.php")?>

<?php

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

$vCrit.=" and date(ftgldist) >= '$vAwal' and date(ftgldist) <= '$vAkhir' and (fstatus = '2' or fstatus='3')" ;



 $vsql="select distinct ftgldist,fserno,fstatus,fpendistribusi  from tb_skit where 1 "; 
 $vsql.=$vCrit;
 $vsql.=" order by fidsys, ftgldist ";
 $db->query($vsql);
 $db->next_record();
 $vRecordCount=$db->num_rows();
 $vPageCount=ceil($vRecordCount/$vBatasBaris);

?>



<script language="JavaScript" type="text/JavaScript">


$(document).ready(function(){
    $('#caption').html('Report Starter KIT Keluar');


      $('#dc').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose : true
    }); 
  
  
       $('#dc1').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose : true
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
	<link rel="stylesheet" href="../css/screen.css">

	
	
 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />


<section>
    <!-- left side start-->
   <? include "../framework/leftadmin.php"; ?>
    <!-- main content start-->
    <div class="main-content" >

   <? include "../framework/headeradmin.php"; ?>
           <!--body wrapper start-->
 <section class="wrapper">
        <!-- page start-->

<form action="" method="post" name="demoform">

          <div style="display:inline" align="left">
          <strong>Mulai Tanggal : </strong>
          <input style="width:100px;display:inline" class="form-control" name="dc" id="dc" size="11" value="<?=$vAwal?>">&nbsp; <strong>s/d</strong>
          <input style="width:100px;display:inline" class="form-control" name="dc1" id="dc1" size="11" value="<?=$vAkhir?>"> &nbsp;&nbsp;
          <input style="display:inline" name="Submit22" type="submit" class="btn btn-primary" value="Refresh">
          
          </div>
          <br /><br />
<br />


    <div class="table-responsive">
        <table width="90%" border="0" class="table table-striped">
          <tr >
            <td style="height: 24px; width: 1%;"><strong>No.</strong></td>
            <td width="12%" style="height: 24px" align="center"><strong>Tanggal</strong></td>
            <td width="12%" style="height: 24px"><div align="center"><strong>KIT Number</strong></div></td>
            <td style="height: 24px; width: 16%;"><strong>ID Agent</strong></td>
            <td align="center" style="height: 24px; width: 8%;"><strong>Nama Agent </strong></td>
            <td align="center" style="width: 9%; height: 24px;"><strong>Status</strong></td>

            <td align="center" style="height: 24px; width: 23%;"><strong>Keterangan</strong></td>
          </tr>
          <? 
             $vNo=0;
			 $vsql="select distinct a.ftgldist,a.fserno,a.fstatus,a.fpendistribusi, b.fketerangan  from tb_skit a left join tb_trxkit b on a.frefpurc=b.fidpenjualan where 1 "; 
			 $vsql.=$vCrit;
			 $vsql.=" order by a.fidsys, ftanggal ";
			$vsql.="limit $vStartLimit ,$vBatasBaris ";
		     $db->query($vsql);
			 //$vTotJualL=0;$vTotalJualR=0;
			 while ($db->next_record()) {
			 $vNo++;
				 $vTanggal=$db->f('ftgldist');
				 $vSerno=$db->f('fserno');
				 $vKet=$db->f('fkindtrx');
				 $vIDMember=$db->f('fpendistribusi');
				 $vKet=$db->f('fketerangan');

				 $vNama=$oMember->getMemberName($vIDMember);
				 $vStatus=$db->f('fstatus');
				 if ($vStatus=='2')
				    $vStatus='Belum Terpakai';
				 else if ($vStatus=='3')   
				    $vStatus='Terpakai';
		  ?>
          <tr>
            <td style="width: 1%; height: 24px;" ><?=$vNo+$vStartLimit?></td>
            <td style="height: 24px" align="center"><?=$oPhpdate->YMD2DMY($vTanggal,"-")?></td>
            <td style="height: 24px" ><?=$vSerno?></td>
            <td style="height: 24px; width: 16%;"><?=$vIDMember?></td>
            <td style="height: 24px; width: 8%;" align="left"><?=$vNama?></td>
            <td style="width: 9%; height: 24px;" ><?=$vStatus?></td>


            <td style="width: 23%; height: 24px;" ><?=$vKet?></td>
          </tr>
           <? } ?>
          <tr style="display:none">
            <td style="width: 1%" >&nbsp;</td>
            <td >&nbsp;</td>
            <td ><div align="right"><strong>Grand Total </strong></div></td>
            <td style="width: 16%" >&nbsp;</td>
            <td style="width: 8%" ><div align="right"></div></td>
            <td style="width: 9%" >&nbsp;</td>
            <td align="right"><strong>
              <?=number_format($vTotalJualL,0,",",".")?>
            </strong></td>
            <td align="right" style="width: 23%">&nbsp;</td>
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
?>
          <a href="rptkitout.php?uPage=<?=$i?>&amp;uAwal=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAwal,"-"),"-")?>&amp;uAkhir=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAkhir,"-"),"-")?>" >
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
    <tr> 
      <td height="49" align="center" valign="middle"> <p><br>
        </p>
      <p>&nbsp;        </p></td>
    </tr>
    <?php
   
  if ($baris==$Akhiran)
  {
  ?>
    <?php
  }
  ?>
  </table>
  
</form>
      <!-- page end-->
        </section>
        <!--body wrapper end-->

        <!--footer section start-->
        <? include "../framework/footer.php";?>
        <!--footer section end-->


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



</body>
</html>
