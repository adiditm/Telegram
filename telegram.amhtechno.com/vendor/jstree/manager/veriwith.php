<? include_once("../framework/masterheader.php")?>
<?
  define("MENU_ID", "mdm_jual_verifikasi");   
  $vRefUser=$_GET['uMemberId'];
  if (isset($vRefUser))
  	 $vUser=$vRefUser;
  else	 
  	 $vUser=$_SESSION['LoginUser'];



  if ($vTanggal=="")
     $vTanggal=$oPhpdate->getNowYMD("-");  
  $vAwal=$_POST['dc'];
  $vAkhir=$_POST['dc1'];
  if ($vAwal=="") 
     $vAwal=$oMydate->dateSub(date("Y-m-d"),30,"day");
  if ($vAkhir=="") 
  	 $vAkhir=date("Y-m-d"); 
	 //FIlter

   $ref=$_REQUEST['ref'];


	$vIDJual=$_POST['tfIDJual']; 
	$vID=$_POST['tfID'];
	$vAnd="";
	if ($vID!="") $vAnd.=" and fidmember = '$vID' ";
	if ($vIDJual!="")  $vAnd.=" and fidwithdraw like '%$vIDJual%' "; 
	$vAnd.=" and date(ftglupdate) between date('$vAwal') and date('$vAkhir')";
	//$vAnd.=" and fstatusrow !=4 ";
	
    $vsql="SELECT *  FROM tb_withdraw WHERE 1  $vAnd ";
    $vsql.="ORDER BY ftglupdate DESC";
    $db->query($vsql);

    $curpage=$_POST['hPageNum'];
    if ($curpage=="" || $_POST['hBtn']=="cari") {
       $curpage="1";
    }
	
	$rows=$db->num_rows();
	$jml=$rows;
	$rowpage=25;
	$curpage=$curpage-1;
	$offset=$curpage*$rowpage;
	
	$pagenum=ceil($rows/$rowpage);

 

	
	
?>

  <link rel="stylesheet" href="../css/screen.css">
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />





<script language="JavaScript" type="text/JavaScript">
<!--
<!--
function doShow(windowHeight, windowWidth, windowName, windowUri)
{
    var centerWidth = (window.screen.width - windowWidth) / 2;
    var centerHeight = (window.screen.height - windowHeight) / 2;

    newWindow = window.open(windowUri, windowName, 'scrollbars=1, resizable=0,width=' + windowWidth + 
        ',height=' + windowHeight + 
        ',left=' + centerWidth + 
        ',top=' + centerHeight);

    newWindow.focus();
    return newWindow.name;
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->

   function changePage(pMenu) {

     document.demoform.hPageNum.value=pMenu.value;
	 doSubmit("refresh");
   }

   function doSubmit(btn) {

      document.demoform.hBtn.value=btn;
	  if (btn=="refresh")
	     document.getElementById("ref").value="posting";
	  else if (btn=="cari")
	     document.getElementById("ref").value="posting";
	  document.demoform.submit();
   }

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->

function doProcess(pURL) {
   vSure=confirm('Apakah Anda yakin memproses withdrawal ini?');
   if (vSure==true) {
	     window.location=pURL+'&ref=withdraw';
   } 
}

function doCancel(pURL) {
   vSure=confirm('Apakah Anda yakin membatalkan withdrawal ini?');
   if (vSure==true) {
	     window.location=pURL;
   } 
}




function doDetail(pID){
	doShow(400,500,'wDet','../manager/detwd.php?uID='+pID);
}

$(document).ready(function(){
    $('#caption').html('Verifikasi & Approval Withdraw');


      $('#dc').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose : true
    }); 
  
  
       $('#dc1').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose : true
    }); 

});

</script>
<style type="text/css">
<!--
table.MsoNormalTable {
font-size:10.0pt;
font-family:"Times New Roman";
}
p.MsoNormal {
margin:0cm;
margin-bottom:.0001pt;
font-size:12.0pt;
font-family:"Times New Roman";
}
.style3 {
	font-size: 12px;
	font-weight: bold;
}
.style4 {font-size: 10px}
.style5 {
	color: #FF0000;
	font-weight: bold;
	font-family: Tahoma;
}
-->
</style>
<section>
    <!-- left side start-->
   <? include "../framework/leftadmin.php"; ?>
    <!-- main content start-->
    <div class="main-content" >

   <? include "../framework/headeradmin.php"; ?>
           <!--body wrapper start-->
 <section class="wrapper">
 <div class="table-responsive">
<table width="100%" border="0" cellpadding="0" cellspacing="0" dwcopytype="CopyTableRow" class="contentfont">
  <!--DWLayoutTable-->
   
  
  <tr> 
    <td height="5" align="center" valign="top" style="width: 592px"> <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="3"><br />
      </font></strong></font>
        <form id="demoform" name="demoform" method="post" action="" >
		<input name="hPageNum" type="hidden" id="hPageNum">   
        <input name="hPage" type="hidden" id="hPage" value="<?=$curpage?>">
        <input name="hBtn" type="hidden" id="hBtn">
         <input name="ref" type="hidden" id="ref" >
<div style="border:1px solid black;border-radius:5px;width:50%;padding:5px 5px 5px 5px">
          <table style="width:100%" border="0" cellpadding="2" cellspacing="0"  >
            <tr>
              <td colspan="3" ><div align="center" class="style3">Filter</div></td>
            </tr>
            <tr>
              <td width="33%" height="25" >
			  Member ID
                <div align="left"></div></td>
              <td width="2%" height="25">:</td>
              <td width="65%" height="25" ><input name="tfID" type="text" class="form-control" id="tfID" value="<?=$vID?>" /></td>
            </tr>
            <tr>
              <td height="25" >ID Withdraw </td>
              <td height="25" >:</td>
              <td height="25" ><input name="tfIDJual" type="text" class="form-control" id="tfIDJual" value="<?=$vIDJual?>" /></td>
            </tr>
          </table>
          </div>
    <p><font face="Verdana, Arial, Helvetica, sans-serif"><strong><br />
            Mulai Tanggal : </strong>
            <input name="dc" id="dc" value="<?=$vAwal?>" size="20" />&nbsp;	 <strong>s/d</strong>
            <input  name="dc1" id="dc1" size="20" value="<?=$vAkhir?>" /> &nbsp;&nbsp;
            <input name="Submit22" type="button" class="smallbutton" onclick="MM_callJS('doSubmit(\'cari\')')" value="   Lihat   "  />
          </font></p>
          <p align="right"><strong><br /><table width="100%" border="0">
  <tr>
    <td><div align="left"><strong>Page
      <select name="select3" id="select3" onchange="changePage(this)">
              <? for ($i=0;$i<$pagenum;$i++) {?>
              <option value="<?=$i+1 ?>" <? if ($curpage==($i)) echo "selected";?>>&nbsp;
              <?=$i+1?>
                &nbsp;</option>
              <? } ?>
            </select>
    </strong>
     </div></td>
    <td><div align="right"><strong>Page
          <select name="select" id="select2" onchange="changePage(this)">
            <? for ($i=0;$i<$pagenum;$i++) {?>
            <option value="<?=$i+1 ?>" <? if ($curpage==($i)) echo "selected";?>>&nbsp;
            <?=$i+1?>
  &nbsp;</option>
            <? } ?>
          </select>
    </strong></div></td>
  </tr>
</table>

          </strong></p>
         

          <table width="99%" border="0" align="center" cellpadding="0" cellspacing="0" class="table table-striped" style="width:99%;margin-left:5.15pt;border-collapse:collapse;">
            <tr style="height:12.75pt;">
              <td  align="center" width="10" nowrap="nowrap" valign="middle" style="border-top:solid windowtext 1.0pt;border-left:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><strong><span style="font-family:Arial; font-size:10.0pt; ">No</span></strong></td>
              <td align="center" width="30" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><strong>Tgl</strong></td>
              <td align="center" width="30" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><strong><span style="font-family:Arial; font-size:10.0pt; ">ID Withdraw </span></strong></td>
              <td align="center" width="30" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><strong>ID Member</strong></td>
              <td align="center" width="40" nowrap="nowrap" valign="middle" style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;border-top:solid windowtext 1.0pt;font-family:Arial; font-size:10.0pt;text-align:center"><strong>Nama</strong></td>
              <td align="center" width="20" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><strong>Total Withdraw </strong></td>
              <td align="center" width="50" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><strong><span style="font-family:Arial; font-size:10.0pt;">Rek Tujuan</span></strong></td>
              <td align="center" width="30" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;">
			  <strong>Status</strong></td>
              <td align="center" width="130" nowrap="nowrap" valign="middle" style=" border-top:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt; border-left-style: none; border-left-color: inherit; border-left-width: medium;"><strong>Action</strong></td>
            </tr>
			
			<?
			   $vsql="SELECT * FROM tb_withdraw WHERE 1 $vAnd ";
			   $vsql.="ORDER BY fidwithdraw DESC ";
			   $vsql.="limit  $offset, $rowpage ";
			   $db->query($vsql);
			   $vNo=0;$vTotHarga=0;$vTotHargaV=0;
			   while ($db->next_record()) {
			     $vNo+=1;
				 $vIDJual=$db->f("fidwithdraw");
				 $vTgl=$db->f("ftglupdate");
				 $vUserID=$db->f("fidmember");
				 $vSubtotal=$db->f("fnominal");
				 $vCurr = $db->f("fcurr");
				 $vProcessed=$db->f("fstatusrow");
				 if ($vProcessed == '4')
				    $vProctext='<span style="color:#f00">Rejected</span>';
				 else if ($vProcessed == '2')   
				    $vProctext='<span style="color:#0f0">Approved</span>';
				 else if ($vProcessed == '0')   
				    $vProctext='Pending';

				 $vRekFrom=$db->f("frekfrom");
				 $vRekTo=$db->f("frekto");
				 $vTotHarga+=$vSubtotal;
				 if ($vProcessed==2)
				    $vTotHargaV+=$vSubtotal;
			?>
			
            <tr style="height:12.75pt;">
              <td width="10" height="19" valign="middle" nowrap="nowrap" style="border-top:none;border-left:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><p align="right" class="MsoNormal style4"><span style="font-family:Arial;font-size:10px"><?=$vNo+$offset?></span></p></td>
              <td width="30" valign="middle" nowrap="nowrap" style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><div align="left"><span style="font-family:Verdana; font-size:10px">
                <?=$oPhpdate->YMD2DMY($vTgl,"-")?>
              </span></div>              </td>
              <td width="30" valign="middle" nowrap="nowrap" style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><div align="left">
                <?=$vIDJual?>
              </div></td>
              <td width="30" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><div align="left"><?=$vUserID?>
              </div></td>
              <td width="40" nowrap="nowrap" valign="middle" style="border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"> <?=$oMember->getMemberName($vUserID)?>
                </td>
              <td align="right" width="15" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><?=$vCurr?> <?=number_format($vSubtotal,0,",",".")?></td>
              <td width="105" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;">
                <?=$oMember->getBankName($db->f('frekto'))?>, <?=$oMember->getMemField('fnorekening',$vUserID)?>
                </td>
              <td width="30" nowrap="nowrap" valign="middle" style="border:solid windowtext 1.0pt;border-top:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;">
                <?=$vProctext?></td>
              <td width="79" nowrap="nowrap" valign="middle" style="width:57.2pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><p class="MsoNormal style4">
                <input class="btn btn-primary btn-xs" type="button" name="Button" onClick="doProcess('processwd.php?uIDJual=<?=$vIDJual?>&uSess=<?=md5('jalanku')?>&uUserID=<?=$vUserID?>');" value="Process" <? if ($vProcessed==2 || $vProcessed==4) echo "disabled";?> /><input class="btn btn-default btn-xs" type="button" name="Button2" onclick="doCancel('processwd.php?uIDJual=<?=$vIDJual?>&uSess=<?=md5('jalanku')?>&uCanc=<?=md5('bataldeh')?>&uUserID=<?=$vUserID?>');" value="Cancel" <? if ($vProcessed==2 || $vProcessed==4) echo "disabled";?> /><input class="btn btn-success btn-xs" name="btnDetail" type="button" id="btnDetail" onclick="doDetail('<?=$vIDJual?>');" value="Detail"  />
              </p></td>
            </tr>
			
			<? } ?>
            <tr>
              <td nowrap="nowrap" colspan="5" valign="middle" style="width:362.0pt;border-left:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:13pt; border-right-style: none; border-right-color: inherit; border-right-width: medium; border-top-style: none; border-top-color: inherit; border-top-width: medium;"><div align="left"><strong><span style="font-family:Arial; font-size:10.0pt; ">Total Withdrawal (halaman ini - Verified) </span></strong></div></td>
              <td nowrap="nowrap" valign="middle" style="width:32.0pt;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;border-left:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:13pt; border-top-style: none; border-top-color: inherit; border-top-width: medium;"><div align="right"><strong><span style="font-family:Verdana;font-size:10px">
                <?=number_format($vTotHargaV,0,",",".")?>
              </span></strong></div></td>
              <td rowspan="4" valign="middle" nowrap="nowrap" style="width:32.0pt;border-left:none;border-left:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt; border-right-style: none; border-top-style: none; border-top-color: inherit; border-top-width: medium; border-bottom-style: none; border-bottom-color: inherit; border-bottom-width: medium;border-right:none">&nbsp;
			  </td>
              <td rowspan="4" valign="middle" nowrap="nowrap" style="width:32.0pt;border-left:none;padding:0cm 5.4pt 0cm 5.4pt; border-right-style: none; border-right-color: inherit; border-right-width: medium; border-top-style: none; border-top-color: inherit; border-top-width: medium; border-bottom-style: none; border-bottom-color: inherit; border-bottom-width: medium;">&nbsp;
			  </td>
              <td nowrap="nowrap" valign="middle" style="width:35pt; padding:0cm 5.4pt 0cm 5.4pt;height:13pt;">
			    </td>
            </tr>
            <tr style="height:12.75pt;">
              <td nowrap="nowrap" colspan="5" valign="middle" style="width:362.0pt;border-top:none;border-left:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><div align="left"><strong><span style="font-family:Arial; font-size:10.0pt; ">Total Withdrawal (halaman ini - All) </span></strong></div></td>
              <td nowrap="nowrap" valign="middle" style="width:32.0pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;border-left:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><div align="right"><strong><span style="font-family:Verdana;font-size:10px">
                <?=number_format($vTotHarga,0,",",".")?>
              </span></strong></div></td>
              <td nowrap="nowrap" valign="middle" style="width:35pt; padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;">&nbsp;
			  </td>
            </tr>
            <tr style="height:12.75pt;">
              <td nowrap="nowrap" colspan="5" valign="middle" style="width:362.0pt;border-top:none;border-left:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;border-right:none;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><div align="left"><span class="MsoNormal" style="text-align:left;"><strong><span style="font-family:Arial; font-size:10.0pt; ">Total Withdrawal (Verified) </span></strong></span></div></td>
              <td nowrap="nowrap" valign="middle" style="width:32.0pt;border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;border-left:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;"><div align="right"><strong><span style="font-family:Verdana;font-size:10px">
                <?=number_format($oJual->getWDByStatus(2,$vAwal,$vAkhir),0,",",".")?>
              </span></strong></div></td>
              <td nowrap="nowrap" valign="middle" style="width:35pt; padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt;">&nbsp;
			  </td>
            </tr>
            <tr style="height:12.75pt;">
              <td nowrap="nowrap" colspan="5" valign="middle" style="width:362.0pt;border-left:solid windowtext 1.0pt;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:13pt; border-right-style: none; border-right-color: inherit; border-right-width: medium; border-top-style: none; border-top-color: inherit; border-top-width: medium;"><p align="justify" class="MsoNormal" style="text-align:left;"><strong><span style="font-family:Arial; font-size:10.0pt; ">Total Withdrawal (All) </span></strong></p></td>
              <td width="94" nowrap="nowrap" valign="middle" style="width:32.0pt;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;border-left:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:13pt; border-top-style: none; border-top-color: inherit; border-top-width: medium;"><p align="right" class="MsoNormal"><strong><span style="font-family:Verdana;font-size:10px">
                <?=number_format($oJual->getWDByStatus(0,$vAwal,$vAkhir),0,",",".")?>
              </span></strong></p></td>
              <td nowrap="nowrap" valign="middle" style="width:35pt; padding:0cm 5.4pt 0cm 5.4pt;height:13pt;">
			    </td>
            </tr>
          </table>
          
	      <p><strong>
          <input name="ref" type="hidden" id="ref" />
          <br />
	      </strong>
	      <table width="100%" border="0">
            <tr>
              <td><div align="left"><strong>Page
                <select name="select4" id="select4" onchange="changePage(this)">
                        <? for ($i=0;$i<$pagenum;$i++) {?>
                        <option value="<?=$i+1 ?>" <? if ($curpage==($i)) echo "selected";?>>&nbsp;
                        <?=$i+1?>
                          &nbsp;</option>
                        <? } ?>
                </select>
              </strong></div></td>
              <td><div align="right"><strong>Page 
                <select name="select2" id="select" onchange="changePage(this)">
                      <? for ($i=0;$i<$pagenum;$i++) {?>
                      <option value="<?=$i+1 ?>" <? if ($curpage==($i)) echo "selected";?>>&nbsp;
                      <?=$i+1?>
  &nbsp;</option>
                      <? } ?>
                </select>
              </strong></div></td>
            </tr>
          </table>
	      <p align="right">&nbsp;</p>
        </form>
      <p align="center">&nbsp;</p>
      <p><br>      
      </p>
  
      <p style="display:none">
        <input  name="btnBukti" type="button"  id="btnBukti" onClick="MM_goToURL('parent','loggedin.php?menu=showbukti&uUser=<?=base64_encode($vUser)?>');return document.MM_returnValue" value="Lihat Bukti Pembayaran">      
        <br>
      </p>
      <blockquote>
        <div align="left"></div>
    </blockquote></td>
  </tr>
</table>
</div>

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

