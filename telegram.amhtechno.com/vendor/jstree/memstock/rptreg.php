<? include_once("../framework/masterheader.php")?>

<?php

$vAwal=$_POST['dc'];
$vAkhir=$_POST['dc1'];

if ($vAwal=="")
	$vAwal=$_GET['uAwal'];
if ($vAkhir=="")
	$vAkhir=$_GET['uAkhir'];

$vSpy = md5('spy').md5($_GET['uMemberId']);

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

$vFilterText='';

$vCrit.=" and date(ftgldaftar) >= '$vAwal' and date(ftgldaftar) <= '$vAkhir' " ;
$vFilterText.="[Date : $vAwal - $vAkhir]";

if ($vPaket !='') {
   $vCrit.=" and fpaket='$vPaket' ";
   $vFilterText.="[Paket: $vPaket]";
 }

if ($_SESSION['Priv']=='administrator')
   $vMem=$_POST['tfMem'];
else $vMem=$vUser;   
   
/*if ($vMem!='') {
   $vCrit.=" and fidmember='$vMem' ";  
   $vFilterText.="[Username: $vMem]"; 
   } */

if ($vSort =='T')
   $vOrder.=" order by ftgldaftar ";
else  if ($vSort =='P') 
   $vOrder.=" order by ftotbelanja desc ";
else    $vOrder.=" order by ftgldaftar desc ";



			 $vsql="select  fidmember,ftgldaftar,fnama,falamat,fstockfrom from m_anggota where factivator='$vUser'  "; 

			 $vsql.=$vCrit;
			 
			 
			 $vsql.=$vOrder;
			 
 $db->query($vsql);
 $db->next_record();
 $vRecordCount=$db->num_rows();
 $vPageCount=ceil($vRecordCount/$vBatasBaris);

?>



<script language="JavaScript" type="text/JavaScript">


$(document).ready(function(){
    $('#caption').html('Activation Report ');


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
	<link rel="stylesheet" href="../css/screen.css">

	
	
 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />


<section>
    <!-- left side start-->
      <?  
      
     
      if ($_SESSION['Priv']=='administrator') 
           include "../framework/leftadmin.php"; 
        else
           include "../framework/leftmem.php"; 
     
     ?>
    <!-- main content start-->
    <div class="main-content" >

     <?   if ($_SESSION['Priv']=='administrator') 
           include "../framework/headeradmin.php"; 
        else
           include "../framework/headermem.php";    
     
     ?>
           <!--body wrapper start-->
 <section class="wrapper">
        <!-- page start-->

<form action="" method="post" name="demoform">

          <div style="display:inline" align="left">
                   &nbsp; &nbsp;&nbsp;&nbsp;
          
          </div>
          <br />
          <div class="row">
          <div class="col-lg-8">

		  <table style="width: 75%">
			  <tr>
				  <td style="height: 22px; width: 154px;"> <strong>Date</strong>&nbsp;</td>
				  <td style="height: 22px">  <input style="width:100px;display:inline" class="form-control" name="dc" id="dc" size="11" value="<?=$vAwal?>"><strong> to</strong>
          <input style="width:100px;display:inline" class="form-control" name="dc1" id="dc1" size="11" value="<?=$vAkhir?>"> </td>
			  </tr>
			  <tr>
				  <td style="width: 154px; height: 26px;"><strong>Reg. Package</strong>
</td>
				  <td style="height: 26px"><select name="lmPaket" id="lmPaket" class="form-control">
				  <option value="">--All--</option>
				  <option value="S" <? if ($vPaket=='E' ||  $vPaket=="S") echo 'selected'; ?>>Silver</option>
				  <option value="G" <? if ($vPaket=='B' ||  $vPaket=="G") echo 'selected'; ?>>Gold</option>
				  <option value="P" <? if ($vPaket=='F' ||  $vPaket=="P") echo 'selected'; ?>>Platinum</option>




				  </select></td>
				  
			  </tr>
			  <tr>
				  <td style="width: 154px; height: 22px"><strong>Sort by</strong></td>
				  <td style="height: 22px">
				  <select name="lmSort" id="lmSort"  class="form-control">
				  <option value="">All</option>
				  <option value="T" <? if ($vSort=='T') echo 'selected'; ?>>Tgl Daftar</option>
				  <option value="P" <? if ($vSort=='P') echo 'selected'; ?>>Purchase Amount</option>
				 



				  </select></td>
			  </tr>
	<? if ($_SESSION['Priv'] == 'administrator') {?>		  
 <tr>
				  <td style="width: 154px; height: 22px"><strong>Username</strong>
					</td>
				  <td style="height: 22px">
				  <input type="text" name="tfMem" id="tfMem" value="<?=$_POST['tfMem']?>" class="form-control">
				</td>
			  </tr>
			  
			  <? } ?>
			  
			  <tr>
				  <td style="width: 154px">&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
		  </table>
		  </div>
		  </div>
          <input style="display:inline" name="Submit22" type="submit" class="btn btn-success" value="Refresh"><br />
		  <br>
		  <br>


    <div class="table-responsive">
        <table border="0" class="table table-striped" style="width:98%">
          <tr >
            <td style="height: 24px; width: 4%;"><strong>No.</strong></td>
            <td style="height: 24px; width: 11%;"><div align="center"><strong>Date</strong></div></td>
            <td style="height: 24px; width: 14%;" align="center"><strong>Username</strong></td>
            <td style="height: 24px; width: 14%;" align="center"><strong>Reg. Package</strong></td>
            <td align="center" style="height: 24px; width: 88px;"><strong>Name </strong></td>
            <td align="center" style="height: 24px; width: 88px;"><strong>Purc. Amt </strong></td>
            <td align="center" style="height: 24px; "><strong>Address </strong></td>
            <td align="center" style="height: 24px; "><strong>Country </strong></td>
            <td align="center" style="height: 24px; "><strong>Phone </strong></td>
            <td align="center" style="height: 24px; "><strong>Sponsor </strong></td>
          </tr>
          <? 
            

		

             $vNo=0;
			 $vsql="select  * from m_anggota where factivator='$vUser' "; 
			$vsql.=$vCrit;
			 
			  $vsql.= $vOrder ;

			//Export Excel
				$db->query($vsql);
				$vArrData="";
				$vArrHead=array('No.','Date','Reg. Package','Name','Purc. Amount','Address','Phone','Sponsor');
				$vArrBlank=array('','','','','','','','');
				$vArrDateFilter=array('Filter :  '.$vFilterText,'','','','','','','');
				
				$i=0;
				$vArrData[]=$vArrDateFilter;
				$vArrData[]=$vArrBlank;
				$vArrData[]=$vArrHead;
				
				while ($db->next_record()) { //Convert Excel
				     $i++;
					 $vIdMember=$db->f('fidmember');
					 $vTanggal=$db->f('ftgldaftar');
					 $vNama=$db->f('fnama');
					 $vAlamat=$db->f('falamat');
					 $vKota=$db->f('fkota');
					 $vProp=$db->f('fpropinsi');
					 $vBelanja=$db->f('ftotbelanja');

				     $vKotaName = $oMember->getWilName('ID',$vProp,$vKota,'00','0000');
					 //$vArrHead=array('No.','Date','Reg. Package','Name','Purc. Amount','Address','Phone','Sponsor');

					 $vArrData[]=array($i,$vTanggal,$db->f('fpaket'),$vNama,$vBelanja,$vAlamat.' '.$vKotaName," ".$db->f('fnohp'),$db->f('fidressponsor'));
					//$vArrMem['fpassword'][$i]=$oSystem->doED('decrypt',$db->f('fpassword'));
				
				}
				
				$_SESSION['rptreg']=$vArrData;


			  
			// $vsql.="limit $vStartLimit ,$vBatasBaris ";
		     $db->query($vsql);
		     $vCount=$db->num_rows();
			 //$vTotJualL=0;$vTotalJualR=0;
			 $vTot=0;
			 while ($db->next_record()) {
			 $vNo++;
				 $vIdMember=$db->f('fidmember');
				 $vTanggal=$db->f('ftgldaftar');
				 $vNama=$db->f('fnama');
				 $vAlamat=$db->f('falamat');
				$vKota=$db->f('fkota');
				$vProp=$db->f('fpropinsi');
				$vBelanja=$db->f('ftotbelanja');

				 $vKotaName = $oMember->getWilName('ID',$vProp,$vKota,'00','0000');
				 
				 

		  ?>
          <tr>
            <td style="width: 4%; height: 24px;" ><?=$vNo+$vStartLimit?></td>
            <td style="height: 24px; width: 11%;" align="center"><?=$vTanggal?></td>
            <td style="height: 24px; width: 14%;" align="center"><?=$vIdMember?></td>
            <td style="height: 24px; width: 7%;" align="center"><?=$db->f('fpaket')?></td>
            <td style="height: 24px; width: 88px;" align="left"><?=$vNama?></td>
            <td style="height: 24px; width: 88px;" align="right"><?=number_format($vBelanja,0,",",".")?></td>
            <td style="height: 24px; " align="left"><?=$vAlamat.' '.$vKotaName?></td>
            <td style="height: 24px; " align="left"><?=$db->f('fcountry')?></td>
            <td style="height: 24px; " align="left"><?=$db->f('fnohp')?></td>
            <td style="height: 24px; " align="left"><?=$db->f('fidressponsor')?></td>
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
          <a href="rptreg.php?uPage=<?=$i?>&amp;uAwal=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAwal,"-"),"-")?>&amp;uAkhir=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAkhir,"-"),"-")?>" >
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
       <h3>        Total : <?=number_format($vCount,0,",",".");?></h3><br><br>
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
  
</form><button class="btn btn-info btn-sm" onClick="document.location.href='../manager/getexcel.php?arr=rptreg&file=activati_report'"><i class="fa fa-file-text-o"></i> Export Excel</button>

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
