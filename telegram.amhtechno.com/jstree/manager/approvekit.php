<? include_once("../framework/admin_headside.blade.php")?>
	

<?php


$vAwal=$_POST['dc'];
$vAkhir=$_POST['dc1'];
$vSpy = md5('spy').md5($_GET['uMemberId']);

 if ($_GET['uMemberId'] != '')
    $vUserActive=$_GET['uMemberId'];
 else  $vUserActive=$vUser;
 
// echo $vUserActive;

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
$vUserHO=$oRules->getSettingByField('fuserho');
$vPage=$_GET['uPage'];
$vBatasBaris=25;
if ($vPage=="")
 	$vPage=0;
$vStartLimit=$vPage * $vBatasBaris;	

$vCrit.=" and date(ftanggal) >= '$vAwal' and date(ftanggal) <= '$vAkhir'" ;


 
 $vsql="select distinct ftanggal, fidpenjualan,fidmember, fketerangan  from tb_trxkit where fidseller='$vUserHO' ";
 $vsql.=$vCrit;
 $vsql.=" order by ftanggal ";
 $db->query($vsql);
 $db->next_record();
 $vRecordCount=$db->num_rows();
 $vPageCount=ceil($vRecordCount/$vBatasBaris);

?>



<script language="JavaScript" type="text/JavaScript">

<?
  $vNow = date('H:i:s');
  if ($vNow >= "00:00:00" && $vNow <="03:00:00") {
?>
  alert('Sistem sedang memproses bonus pukul 00:00:00 - 03:00:00, silakan melakukan approval di luar jam tersebut!');
  document.location.href='../index.php';
  
<? } ?>

$(document).ready(function(){
    $('#caption').html('PO Approval');


      $('#dc').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose : true
    }).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    });  
; 
  
  
       $('#dc1').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose : true
    }).on('changeDate', function (ev) {
    $(this).datepicker('hide');
    });  
; 

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

function doApprove(pIdSys,pIdTrx) {
   var vURL='../manager/processing_ajax.php?op=approvekit&idsys='+pIdSys+'&idtrx='+pIdTrx;
   if (confirm('Are you sure to approve PO for serial '+pIdTrx+'?')) {
	   $.get(vURL,function(data) {
	      if(data.trim()=='successappv') {
	        alert('Approval succeed, serial stock updated!');
	        $('#tdstat'+pIdSys).html('Approved');
  			document.getElementById('btnAppv'+pIdTrx).disabled=true;
  			document.getElementById('btnReject'+pIdTrx).disabled=true;
	      }
	   });
   }
}


function doReject(pIdSys,pIdTrx) {
   var vURL='../manager/processing_ajax.php?op=rejectkit&idsys='+pIdSys+'&idtrx='+pIdTrx;
   if (confirm('Are you sure to reject & delete permanently PO '+pIdTrx+'?')) {
	   $.get(vURL,function(data) {
	      alert(data);
	      if (data.trim()=='successdel') {
	         alert('PO already rejected!');
	         $('#tr'+pIdTrx).hide('slow');
	      }
	   });
   }
}

</script>
<!--	<link rel="stylesheet" href="../css/screen.css">-->

	
	
 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />


				
<div class="right_col" role="main">
		<div><label><h3>PO Approval for Serial</h3></label></div>

<form action="" method="post" name="demoform">

          <div style="display:inline" align="left">
          <strong>Date : </strong>
          <input style="width:100px;display:inline" class="form-control" name="dc" id="dc" size="11" value="<?=$vAwal?>">&nbsp; <strong>
			  to</strong>
          <input style="width:100px;display:inline" class="form-control" name="dc1" id="dc1" size="11" value="<?=$vAkhir?>"> &nbsp;&nbsp;
          <input style="display:inline" name="Submit22" type="submit" class="btn btn-success" value="Refresh">
          
          </div>
          <br /><br />
<br />


    <div class="table-responsive">
        <table width="90%" border="0" class="table table-striped">
          <tr >
            <td style="height: 24px; width: 5%;"><strong>No.</strong></td>
            <td width="15%" style="height: 24px"><div align="center"><strong>Date</strong></div></td>
            <td class="" width="15%" style="height: 24px" align="center"><strong>ID TRX</strong></td>
            <td class="hide" width="15%" style="height: 24px"><strong>Stockist Username</strong></td>
            <td width="10%" align="center" style="height: 24px"><strong>Username </strong></td>
            <td align="center" style="width: 23%; height: 24px;"><strong>Name</strong></td>
            <td width="12%" align="center" style="height: 24px"><strong>&nbsp;Detail Product </strong></td>
            <td width="12%" align="center" style="height: 24px"><strong>Note</strong></td>
            <td width="14%" align="center" style="height: 24px"><strong>Total </strong></td>
            <td width="14%" align="center" style="height: 24px"><strong>Status</strong></td>
            <td width="14%" align="center" style="height: 24px"><strong>Action</strong></td>
          </tr>
          <? 
             $vNo=0;


			 
			 $vsql ="select distinct ftanggal, fidpenjualan,fidseller,fidmember, fketerangan,fprocessed  from tb_trxkit where   fkindtrx='kitorder'"; 
			 $vsql.=$vCrit;
			 
			 $vsql.=" order by ftanggal ";

			 
			  $vsql.="limit $vStartLimit ,$vBatasBaris ";




		     $db->query($vsql);
			 $vTotJual=0;
			 while ($db->next_record()) {
			 $vNo++;
				 $vTanggal=$db->f('ftanggal');
				 $vIdMember=$db->f('fidmember');
				 $vIdProd=$db->f('fidproduk');
				 $vNama=$oMember->getMemberName($vIdMember);
				 $vKet=$db->f('fketerangan');
				 $vStat=$db->f('fprocessed');
				 $vIdSys=$db->f('fidsys');
				 $vIdTrx=$db->f('fidpenjualan');
				 if ($vStat=='0')
				    $vStatus='Pending';
				 else if ($vStat=='2')   
				    $vStatus='Approved';
				 else if ($vStat=='4')  
				    $vStatus='Rejected';    
				 //$vtgltrans=$db->f('ftanggal');
		  ?>
          <tr id="tr<?=$vIdSys?>">
            <td style="width: 5%" ><?=$vNo+$vStartLimit?></td>
            <td ><?=$oPhpdate->YMD2DMY($vTanggal,"-")?></td>
            <td class=""><?=$db->f('fidpenjualan')?></td>
            <td class="hide"><?=$db->f('fidseller')?></td>
            <td ><?=$vIdMember?></td>
            <td style="width: 23%" ><?=$vNama?></td>
            <td ><div align="left"><?=$oJual->dispDetPOKit($db->f('fidpenjualan'))?></div></td>
            <td align="center"><?=$vKet?></td>
            <td ><div align="right">
            <?
             $vSubTot=$oJual->getPOTotKit($db->f('fidpenjualan'));
             echo  number_format($vSubTot,0,",",".");
             $vTotalJual+=$vSubTot;
            
            ?>
			</div></td>
            <td id="tdstat<?=$vIdSys?>"> <?=$vStatus?></td>
            <td nowrap="nowrap"> <input <? if ($vStat!='0') echo 'disabled';?> onclick="doApprove('<?=$vIdSys?>','<?=$vIdTrx?>')" class="btn btn-success btn-xs" name="btnAppv" id="btnAppv<?=$vIdTrx?>" type="button" value="Approve">&nbsp;<input <? if ($vStat!='0') echo 'disabled';?> onclick="doReject('<?=$vIdSys?>','<?=$vIdTrx?>')"  class="btn btn-danger btn-xs" name="btnReject" id="btnReject<?=$vIdTrx?>"  type="button" value="Reject"></td>
          </tr>
           <? } ?>
          <tr class="hide">
            <td style="width: 5%" >&nbsp;</td>
            <td ><div align="right"><strong>Grand Total </strong></div></td>
            <td class="hide">&nbsp;</td>
            <td class="hide">&nbsp;</td>
            <td colspan="5" ><div align="right"><strong>
              <?=number_format($vTotalJual,0,",",".")?>
            </strong></div></td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
          </tr>
        </table>    
        </div>  
            
     <table width="90%">
     <tr>
      <td align="center">
        
       
        <ul class="pagination">
          <?
   for ($i=0;$i<$vPageCount;$i++) {
     $vOffset=$i*$vBatasBaris;
     if ($i!=$vPage) {
?>
          <li class="active" ><a href="../manager/approvekit.php?uPage=<?=$i?>&amp;uAwal=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAwal,"-"),"-")?>&amp;uAkhir=<?=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($vAkhir,"-"),"-")?>" >
          <?=$i+1?>
          </a></li>
          <?
  } else {
?>
         <li style="cursor:pointer"> <a href="#" > <?=$i+1?></a></li>
          <? } ?>
          <?  } //while?>
<br>
       </ul></td>
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


<? include_once("../framework/admin_footside.blade.php") ; ?>
