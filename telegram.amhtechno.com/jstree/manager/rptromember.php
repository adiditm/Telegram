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

$vPaket=$_POST['lmPaket'];
$vSort=$_POST['lmSort'];


$vPage=$_GET['uPage'];
$vBatasBaris=25;
if ($vPage=="")
 	$vPage=0;
$vStartLimit=$vPage * $vBatasBaris;	

$vCrit.=" and date(a.ftanggal) >= '$vAwal' and date(a.ftanggal) <= '$vAkhir' " ;

if ($vPaket !='')
   $vCrit.=" and b.fpaket='$vPaket' ";

if ($vSort =='T')
   $vOrder.=" order by ftanggal ";
else  if ($vSort =='P') 
   $vOrder.=" order by sum(a.fsubtotal) desc ";
else    $vOrder.=" order by a.ftanggal desc ";



			 $vsql="select  distinct date(a.ftanggal) as ftang,a.fidseller,a.fidmember,a.fidpenjualan,sum(a.fsubtotal) as ftot from tb_trxstok_member a,m_anggota b where a.fidmember=b.fidmember  and a.fjenis='RO' $vCrit group by date(a.ftanggal),a.fidseller,a.fidmember,a.fidpenjualan"; 

						 
			 
			 $vsql.=$vOrder;
			 
 $db->query($vsql);
 $db->next_record();
 $vRecordCount=$db->num_rows();
 $vPageCount=ceil($vRecordCount/$vBatasBaris);

?>



<script language="JavaScript" type="text/JavaScript">


$(document).ready(function(){
    $('#caption').html('Report RO Member');


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
    <div class="main-content" style="background-color:#fff">

   <? include "../framework/headeradmin.php"; ?>
           <!--body wrapper start-->
 <section class="wrapper">
        <!-- page start-->

<form action="" method="post" name="demoform">

          <div style="display:inline" align="left">
                   &nbsp; &nbsp;&nbsp;&nbsp;
          
          </div>
          <br />
		  <table style="width: 75%">
			  <tr>
				  <td style="height: 22px; width: 154px;"> <strong>Mulai Tanggal :  </strong>&nbsp;</td>
				  <td style="height: 22px"><strong>  <input style="width:100px;display:inline" class="form-control" name="dc" id="dc" size="11" value="<?=$vAwal?>">s/d</strong>
          <input style="width:100px;display:inline" class="form-control" name="dc1" id="dc1" size="11" value="<?=$vAkhir?>"> </td>
			  </tr>
			  <tr>
				  <td style="width: 154px"><strong>Paket</strong>
</td>
				  <td><select name="lmPaket" id="lmPaket" class="form-control">
				  <option value="">All</option>
				  <option value="E" <? if ($vPaket=='E') echo 'selected'; ?>>Economy</option>
				  <option value="B" <? if ($vPaket=='B') echo 'selected'; ?>>Business</option>
				  <option value="F" <? if ($vPaket=='F') echo 'selected'; ?>>First</option>




				  </select></td>
				  
			  </tr>
			  <tr>
				  <td style="width: 154px; height: 22px"><strong>Sort</strong>
</td>
				  <td style="height: 22px">
				  <select name="lmSort" id="lmSort"  class="form-control">
				  <option value="">All</option>
				  <option value="T" <? if ($vSort=='T') echo 'selected'; ?>>Tgl Daftar</option>
				  <option value="P" <? if ($vSort=='P') echo 'selected'; ?>>Purchase Amount</option>
				 



				  </select></td>
			  </tr>
			  <tr>
				  <td style="width: 154px">&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
		  </table>
          <input style="display:inline" name="Submit22" type="submit" class="btn btn-primary" value="Refresh"><br />
		  <br>
		  <br>


    <div class="table-responsive">
        <table border="0" class="table table-striped" style="width:98%">
          <tr >
            <td style="height: 24px; width: 4%;"><strong>No.</strong></td>
            <td style="height: 24px; width: 11%;"><div align="center"><strong>Tanggal</strong></div></td>
            <td style="height: 24px; width: 14%;" align="center"><strong>ID Distributor</strong></td>
            <td style="height: 24px; width: 7%;" align="center"><strong>ID Jual</strong></td>
            <td align="center" style="height: 24px; width: 88px;"><strong>Nama </strong></td>
            <td align="center" style="height: 24px; width: 88px;"><strong>Belanja </strong></td>
            <td align="center" style="height: 24px; "><strong>Alamat </strong></td>
            <td align="center" style="height: 24px; "><strong>Negara </strong></td>
            <td align="center" style="height: 24px; "><strong>No HP </strong></td>
            <td align="center" style="height: 24px; "><strong>Stockist </strong></td>
          </tr>
          <? 
            

		

             $vNo=0;
			 $vsql="select  distinct date(a.ftanggal) as ftang,a.fidseller,a.fidmember,a.fidpenjualan,sum(a.fsubtotal) as ftot from tb_trxstok_member a,m_anggota b where a.fidmember=b.fidmember  and fjenis='RO' $vCrit group by date(a.ftanggal),a.fidseller,a.fidmember,a.fidpenjualan"; 
			// $vsql.=$vCrit;
			 
			  $vsql.= $vOrder ;
			// $vsql.="limit $vStartLimit ,$vBatasBaris ";
		     $db->query($vsql);
		     $vCount=$db->num_rows();
			 //$vTotJualL=0;$vTotalJualR=0;
			 $vTot=0;
			 while ($db->next_record()) {
			 $vNo++;
				 $vIdMember=$db->f('fidmember');
				 $vTanggal=$db->f('ftang');
				// $vNama=$db->f('fnama');
				// $vAlamat=$db->f('falamat');
				//$vKota=$db->f('fkota');
			//	$vProp=$db->f('fpropinsi');
				$vBelanja=$db->f('ftot');

				 $vKotaName = $oMember->getWilName('ID',$vProp,$vKota,'00','0000');
				 
				 

		  ?>
          <tr>
            <td style="width: 4%; height: 24px;" ><?=$vNo+$vStartLimit?></td>
            <td style="height: 24px; width: 11%;" align="center"><?=$vTanggal?></td>
            <td style="height: 24px; width: 14%;" align="center"><?=$vIdMember?></td>
            <td style="height: 24px; width: 7%;" align="center"><?=$db->f('fidpenjualan')?></td>
            <td style="height: 24px; width: 88px;" align="left"><?=$vNama?></td>
            <td style="height: 24px; width: 88px;" align="right"><?=number_format($vBelanja,0,",",".")?></td>
            <td style="height: 24px; " align="left"><?=$vAlamat.' '.$vKotaName?></td>
            <td style="height: 24px; " align="left"><?=$db->f('fcountry')?></td>
            <td style="height: 24px; " align="left"><?=$db->f('fnohp')?></td>
            <td style="height: 24px; " align="left"><?=$db->f('fidseller')?></td>
          </tr>
           <? } ?>
          </table>    
     </div>  
            
     <table width="90%">
     <tr>
      <td align="center" style="display:none">
        
        <p>
          <?
   for ($i=0;$i<$vPageCount;$i++) {
     $vOffset=$i*$vBatasBaris;
     if ($i!=$vPage) {
?>
          <a href="rptromember.php?uPage=<?=$i?>&amp;uAwal=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAwal,"-"),"-")?>&amp;uAkhir=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAkhir,"-"),"-")?>" >
          <?=$i+1?>
          </a>
          <?
  } else {
?>
          <?=$i+1?>
          <? } ?>
          <?  } //while?>

        </p>
        
        </td>
    </tr>
        <tr> 
      <td  align="left" valign="top" style="font-weight:bold">
       <h3>        Total keseluruhan&nbsp; : <?=number_format($vCount,0,",",".");?></h3><br><br>
      </td>
      
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
