<? include_once("../framework/masterheader.php")?>
<?php
define("MENU_ID", "mdm_member_aktif_detail");   
$vFall=$_GET['fallback'];
 if (!$oSystem->checkPriv($vUser,MENU_ID)) { ;
    //  $oSystem->jsAlert("Not Authorized!");
    //  $oSystem->jsLocation("../main/logout.php");
 }
$vOP=$_POST['hOP'];
$vSpy=md5('spy');
$vID=$_POST['tfID'];
$vNama=$_POST['tfNama'];
$vNoHP=$_POST['tfNoHP'];
$vKota=$_POST['tfKota'];
$vAktif=$_POST['lmAktif'];
$vSort=$_POST['lmSort'];
if ($vSort=="") $vSort=$_GET['lmSort'];
if ($vSort=="") $vSort=1;

if ($vAktif=="") $vAktif=$_GET['lmAktif'];
$vPrem=$_POST['lmMship'];
if ($vPrem=="") $vPrem=$_GET['lmMship'];
$vStockist=$_POST['lmStockist'];

if ($vSort=="1")
   $vOrder=" ftgldaftar ";
if ($vSort=="2")
   $vOrder=" fidmember ";
if ($vSort=="3")
   $vOrder=" fnama ";
    

if ($vStockist!="")
   $vCrit.=" and fstockist = '$vStockist' ";
if ($vID!="")
   $vCrit.=" and fidmember like '%$vID%' ";

if ($vNama!="")
   $vCrit.=" and fnama like '%$vNama%' ";
if ($vNoHP!="")
   $vCrit.=" and fnohp like '%$vNoHP%' ";
if ($vKota!="")
   $vCrit.=" and fkota like '%$vKota%' ";

if ($vAktif==2)
   $vCrit.=" and faktif = 0";
else if ($vAktif==1)   
   $vCrit.=" and faktif = 1";

if ($vPrem!="-" && $vPrem!="")
   $vCrit.=" and fpaket = '$vPrem' ";

 

$vPage=$_GET['uPage'];
$vBatasBaris=25;
if ($vPage=="")
 	$vPage=0;
$vStartLimit=$vPage * $vBatasBaris;	

$vsql="select * from m_anggota_temp where 1 ";
$vsql.=$vCrit;
$vsql.=" order by $vOrder ";
//echo "<br><br><br>".$vsql;
$db->query($vsql);
$db->next_record();
$vRecordCount=$db->num_rows();
$vPageCount=ceil($vRecordCount/$vBatasBaris);



$from="Uneeds <info@uneeds-style>";

if ($_GET['op']=='del') {
   $vID=$_GET['id'];
   $vSQL="delete from m_anggota_temp where fidsys=$vID";
   $db->query($vSQL);
   $oSystem->jsAlert("Member bebek hanyut sudah dibuang ke laut!");
   $oSystem->jsLocation("../manager/aktiftemp.php");
}

?>


<script language="JavaScript" type="text/JavaScript">
$(document).ready(function(){
    $('#caption').html('Members Temporary Registration / Bebek Hanyut');

});
<!--
function MM_goToURL() { //v3.0

  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  if (getValue()=="") {
      alert('Pilih salah satu member melalui Radio Button di kolom paling kanan, kemudian klik tombol ini kembali!');
	  return false;
  }	  
  for (i=0; i<(args.length-1); i+=2) 
      eval(args[i]+".location='"+args[i+1]+"'");
  
}

function doActivate(pURL,pOP) {
   var vMess='';
   if (pOP=='act') vMess='Apakah Anda yakin mengaktifkan member ini?';
   else if (pOP=='trial') vMess='Apakah Anda yakin mengaktifkan member ini untuk trial?';
   else if(pOP=='stop') vMess='Apakah Anda yakin stop member ini untuk trial?';
   else vMess='Apakah Anda yakin menghapus member ini?';
   vSure=confirm(vMess);
   if (vSure==true) {
	     window.location=pURL+"&uStockist=0";
   } 
}

function doDeActivate(pURL) {
   vSure=confirm('Apakah Anda yakin me-non-aktifkan member ini?');
   if (vSure==true) {
	     window.location=pURL+"&uOP=0";
   } 
}

function doDel(pID,pNama) {
   vSure=confirm('Apakah Anda yakin menghapus member'+pNama+'?');
   if (vSure==true) {
	     window.location='../manager/aktiftemp.php?op=del&id='+pID;
   } 
}


function getValue(){
   vLength=document.memberForm.rbSelected.length;   
   for (i=0;i<vLength;i++) {
      if (document.memberForm.rbSelected[i].checked) {
	     return document.memberForm.rbSelected[i].value; 
	  } 
   } 
      if (document.memberForm.rbSelected.value)
	     return document.memberForm.rbSelected.value;
	  else return '(Anda belum memilih member)';	 
}

function checkStatus(pStatus,pStockist) {
/*
   if (pStatus!='1') {
      document.getElementById('btKomisi').disabled=true;
	  if (document.getElementById('btX'))
	  document.getElementById('btX').disabled=true;
      if (document.getElementById('btJar'))
	  document.getElementById('btJar').disabled=true;
	  if (document.getElementById('btTTK'))
	  document.getElementById('btTTK').disabled=true;
      if (document.getElementById('btGen'))
	  document.getElementById('btGen').disabled=true;
	  if (document.getElementById('btGG'))
	  document.getElementById('btGG').disabled=true;
	  document.getElementById('btBH').disabled=true;
	  document.getElementById('btGS').disabled=true;
	  document.getElementById('btTitik').disabled=true;
	  document.getElementById('btBH2').disabled=true;
	  document.getElementById('btGS2').disabled=true;
	  document.getElementById('btTitik2').disabled=true;
	  document.getElementById('btMutasi').disabled=true;
	  document.getElementById('btButuan').disabled=true;
   } else {
      document.getElementById('btKomisi').disabled=false;
	  if (document.getElementById('btX'))	  
	  document.getElementById('btX').disabled=false;
      document.getElementById('btJar').disabled=false;
	  document.getElementById('btTTK').disabled=false;
      document.getElementById('btGen').disabled=false;
	  document.getElementById('btGG').disabled=false;
	  document.getElementById('btBH').disabled=false;
	  document.getElementById('btGS').disabled=false;
	  document.getElementById('btTitik').disabled=false;
	  document.getElementById('btBH2').disabled=false;
	  document.getElementById('btGS2').disabled=false;
	  document.getElementById('btTitik2').disabled=false;
	  document.getElementById('btButuan').disabled=false;
	  document.getElementById('btMutasi').disabled=false;

   }
   */
   
}

//-->
</script>
	<link rel="stylesheet" href="../css/screen.css">

	
	
 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />

<style type="text/css">
<!--
.style1 {color:#666666}
.style2 {
	color: #000000;
	font-weight: bold;
}
-->


@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

	/* Force table to not be like tables anymore */
	table, thead, tbody, th, td, tr { 
		display: block; 
	}
	
	/* Hide table headers (but not display: none;, for accessibility) */
	thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; }
	
	td { 
		/* Behave  like a "row" */
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}
	
	td:before { 
		/* Now like a table header */
		position: absolute;
		/* Top/left values mimic padding */
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
	}

</style>
<section>
    <!-- left side start-->
   <? include "../framework/leftadmin.php"; ?>
    <!-- main content start-->
    <div class="main-content" >

   <? include "../framework/headeradmin.php"; ?>
           <!--body wrapper start-->
 <section class="wrapper">

<div align="center" class="table-responsive"> 
      
       		<form method="post" >
       		<div style="border:1px solid grey;padding: 4px 4px 4px 4px">
		  <table border="0" cellpadding="4" cellspacing="0"  style="width:450px;border:0px solid silver;padding:6px 6px 6px 6px" >
            <tr>
              <td colspan="2" ><div align="center"><font size="3"><strong>Filter</strong></font></div></td>
            </tr>
            <tr>
              <td width="35%" height="25" ><div align="left">Member ID </div></td>
              <td width="62%" height="25"><div align="left">
                  <input name="tfID" type="text" class="form-control" id="tfID" value="<?=$vID?>" />
                  <input name="hOP" type="hidden" id="hOP" value="post" />
              </div></td>
            </tr>
            <tr>
              <td height="25"><div align="left">Nama</div></td>
              <td height="25"><div align="left">
                  <input name="tfNama" type="text" class="form-control" id="tfNama" value="<?=$vNama?>" />
              </div></td>
            </tr>
            <tr>
              <td style="height: 25px"><div align="left">No HP</div></td>
              <td style="height: 25px"><div align="left">
                  <input name="tfNoHP" type="text" class="form-control" id="tfNoHP" value="<?=$vNoHP?>" />
              </div></td>
            </tr>
            <tr>
              <td height="25"><div align="left">Kota</div></td>
              <td height="25"><div align="left">
                  <input name="tfKota" type="text" class="form-control" id="tfKota" value="<?=$vKota?>" />
              </div></td>
            </tr>
            <tr style="display:none">
              <td height="25"><div align="left">Stockist</div></td>
              <td height="25"><div align="left">
                  <select name="lmStockist" class="form-control" id="lmStockist">
                    <option value="" selected="selected">Semua</option>
                    <option value="0" <? if ($vStockist==0 && $vStockist!="") echo "selected"?>>Bukan Stockist</option>
                    <option value="1" <? if ($vStockist==1) echo "selected"?>>Stockist</option>
                  </select>
              </div></td>
            </tr>
            <tr style="display:none">
              <td style="height: 25px"><div align="left">Status Aktif</div></td>
              <td style="height: 25px"><div align="left">
                  <select name="lmAktif" class="form-control" id="lmAktif">
                    <option value="3" selected="selected">Semua</option>
                    <option value="2" <? if ($vAktif==2) echo "selected"?>>Tidak 
                      Aktif</option>
                    <option value="1" <? if ($vAktif==1) echo "selected"?>>Aktif</option>
                  </select>
              </div></td>
            </tr>
            <tr class="trhide" style="display:none">
              <td height="25"><div align="left">Paket</div></td>
              <td height="25"><div align="left">
                <select name="lmMship" class="form-control" id="lmMship">
                  <option value="-" selected="selected">Semua</option>
                  <option value="E" <? if ($vPrem=="E") echo "selected"?>>Economy</option>
                  <option value="B" <? if ($vPrem=="B") echo "selected"?>>Business</option>
				  <option value="F" <? if ($vPrem=="F") echo "selected"?>>First</option>
                </select>
              </div></td>
            </tr>
            <tr style="display:none">
              <td style="height: 25px"><div align="left">Sorting</div></td>
              <td style="height: 25px"><div align="left">
                <select name="lmSort" class="form-control" id="lmSort">
                  <option value="1" <? if ($vSort=="1") echo "selected";?>>Tgl Daftar</option>
                  <option value="2" <? if ($vSort=="2") echo "selected";?>>User ID / Kd Anggota</option>
                  <option value="3" <? if ($vSort=="3") echo "selected";?>>Nama</option>
                </select>
              </div></td>
            </tr>
            <tr>
              <td colspan="2"><div align="center"><br>
                  <input name="Submit" type="submit" class="btn btn-primary" value="Cari" />
                &nbsp; &nbsp;
                <input name="Submit2" type="reset" class="btn btn-default" value="Reset" />
              </div></td>
            </tr>
          </table>
          </div>

  
</form>
<form name="memberForm">
    &nbsp; 
    <div align="left">
           <br><br>
      
          <input alt="Detail & Edit Member" name="btDetail" type="button" class="btn btn-success btn-sm" id="btDetail2" onClick="MM_goToURL('parent','../memstock/profiletemp.php?op=<?=$vSpy?>'+CryptoJS.MD5(getValue())+'&uMemberId='+getValue());return document.MM_returnValue" value="Detail &raquo;" onMouseover="showhint('Lihat detail /  Edit Member '+getValue(), this, event, '220px')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <br>  
          <br>
      
      </div>
      <div class="table-responsive" >
    <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="table" >
      <tr style="color:#85d7ca;font-weight:bold"> 
        <td  height="26" style="width: 30px"> <div align="center" >Member ID </div></td>
        <td width="142"><div align="center" >Nama 
        </div></td>
        <td width="138"><div align="center" >Alamat 
          </div></td>
        <td width="68"><div align="center" >No HP 
          </div></td>
        <td width="58"><div align="center" >No Rek</div></td>
        <td width="70"><div align="center" > Tgl&nbsp;Aktif </div></td>
        <td width="99"><div align="center">Belanja&nbsp;Awal </div></td>

        <td width="187"><div align="center" >Operation</div></td>
        <td width="27"><div align="center" >&radic;</div></td>
      </tr>
      <?
		  if ($vOP=="post") $vStartLimit=0;
		  $vsql="select * from m_anggota_temp where 1 ";
		  $vsql.=$vCrit;
		  $vsql.=" order by $vOrder ";
		  $vsql.="limit $vStartLimit ,$vBatasBaris ";
			
		  $db->query($vsql);
		  $vHari=$oRules->getSettingByField("fbyyprint");
		  while ($db->next_record()) {
		     $vAktifList=$db->f('faktif'); 
			 $vTrial=$db->f('fisfree');
			 $vIDSys=$db->f('fidsys');

			  $vTglAktif=$oPhpdate->DMY2YMD($oPhpdate->YMD2DMY($db->f('ftglaktif')));
			     $vIDSys=$db->f('fidsys');
		?>
      <tr style="<? if ($oMember->isStockist($db->f('fidmember'))==1) echo "background-color:#880000;color:#fff"; ?>"  > 
        <td style="color:<? if ($oMember->isStockist($db->f('fidmember'))==1) echo 'lime'; else echo 'grey';?>; width: 30px;" onmouseover="showhint('<?=$vMess?>', this, event, '150px')">
        <a name="<?=$db->f('fidmember')?>"></a>
        <div align="left"><span  >
          <?=$db->f('fidmember')?>
          <? if ($vTrial=='1' && $vAktifList=='1') { ?>
          <br>
          Trial s/d <?=$oPhpdate->YMD2DMY($oMydate->dateAdd($vTglAktif,$vHari,"day")) ?>
          <? } ?>
           <? if ($oMember->isStockist($db->f('fidmember'))==1) echo "<br> St. ID: ".$db->f('fidstockist');?>
          </span></div></td>
        <td style="height: 39px"><div align="left"><span >
          <?=$db->f('fnama')?>
          <br>
          </span></div></td>
        <td width="138" style="height: 39px">          <div align="left"><span >
          <?=$db->f('falamat').", ".$oMember->getWilName('ID',$db->f('fpropinsi'),$db->f('fkota'),'00','00')?>
          <br>
          </span></div></td>
        <td  style="height: 39px"> 
          <div align="left"><span >
            <?=$db->f('fnohp')?>
          &nbsp;          </span></div></td>
        <td style="height: 39px"><div align="left"><span > 
          <?=$oMember->getBankName($db->f('fnamabank'))?>
          , 
          <?=$db->f('fnorekening')?>
          &nbsp; </span></div></td>
        <td  style="height: 39px"> 
          <span >
            <?=$db->f('ftglaktif')?>
          </span>        </td>
        <td  style="height: 39px;<? if ($db->f('fpriv')=='1') echo 'background-color:#ccc';?>"><div align="right"><?=number_format($db->f('ftotbelanja'),0,",",".")?></div></td>
              <td   style="height: 39px"><div align="center" > 
          <input style="display:none" name="Button" type="button"  onClick="doActivate('activate.php?uMemberId=<?=urlencode($db->f('fidmember'))?>&uSess=<?=md5('jalanku')?>&uOP=act','act');"  value="Aktifkan" <? if($vAktifList=='1') echo 'disabled'.' '.'class=\'vsmallbuttond\''; else echo 'class=\'vsmallbutton\'';  ?> onMouseover="showhint('Aktifkan Member <?=$db->f('fidmember')?>', this, event, '200px')">&nbsp;
          <? if ($vAktifList=='1' && $vTrial=='1') {?>
          &nbsp;<? } ?><input name="btDel" type="button"  onclick="doDel('<?=$vIDSys?>','<?=$db->f("fnama")?>');"  value="Delete" <? if($vAktifList=='1') echo 'disabled'.' '.'class=\'btn btn-primary vsmallbuttond\''; else echo 'class=\'btn btn-primary vsmallbutton\'';  ?> onmouseover="showhint('Del Member <?=$db->f('fidmember')?>', this, event, '200px')" />
          </div></td>
        <td  style="height: 39px" <? if($vFall==$db->f('fidmember')) echo "bgcolor=#0f0";?>><span >
          <input class="form-control" id="rbSelected" style="width:20px;height:20px" name="rbSelected" type="radio" value="<?=$db->f('fidmember')?>" onClick="checkStatus('<?=$db->f('faktif')?>','<?=$db->f('fstockist')?>')" >
          </span></td>
      </tr>
        <? } // while $db->next_record?>
      </table>
      </div>
    </form>
      <div align="center">
            <p align="left" style="display:none"><span >
			  <input alt="Detail & Edit Member" name="btDetail2" type="button" class="btn btn-primary" id="btDetail2" onClick="MM_goToURL('parent','admin.php?menu=changeprofil&uMemberId='+getValue());return document.MM_returnValue" value="Detail" onMouseover="showhint('Lihat detail /  Edit Member '+getValue(), this, event, '220px')">
			  <input name="btKomisi2" type="button" class="btn btn-default" id="btKomisi2" onClick="MM_goToURL('parent','admin.php?menu=stkomisi&uMemberId='+getValue());return document.MM_returnValue" value="Komisi" onMouseover="showhint('Lihat Komisi Member '+getValue(), this, event, '190px')">
              <input name="btJar2" type="button" class="vsmallbutton" id="btJar2" onClick="MM_goToURL('parent','admin.php?menu=stjaringan&uMemberId='+getValue());return document.MM_returnValue" value="Net/Sponsor" onMouseover="showhint('Lihat Status Network & Sponsor Member '+getValue(), this, event, '190px')">
              <input name="btGen2" type="button" class="vsmallbutton" id="btGen2" onClick="MM_goToURL('parent','admin.php?menu=genealogi&uMemberId='+getValue());return document.MM_returnValue" value="Geneologi" onMouseover="showhint('Lihat Genealogi Member '+getValue(), this, event, '210px')">
            </span></p>
        <p align="left">      
          <input alt="Detail & Edit Member" name="btDetail2" type="button" class="btn btn-success btn-sm" id="btDetail2" onClick="MM_goToURL('parent','../memstock/profiletemp.php?op=<?=$vSpy?>'+CryptoJS.MD5(getValue())+'&uMemberId='+getValue());return document.MM_returnValue" value="Detail &raquo;" onMouseover="showhint('Lihat detail /  Edit Member '+getValue(), this, event, '220px')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            </p>
            <p><br>
              <?
   for ($i=0;$i<$vPageCount;$i++) {
     $vOffset=$i*$vBatasBaris;
	   $idisp=$i;
	 if ($vOP=="post") $idisp=0;
     if ($idisp!=$vPage) {
?>
              <a  href="aktiftemp.php?lmAktif=<?=$vAktif?>&amp;lmMship=<?=$vPrem?>&amp;uPage=<?=$idisp?>&amp;lmSort=<?=$vSort?>" >
              <?=$i+1?>
              </a> 
              <?
  } else {
?>
              <?=$i+1?>
              <? } ?>
              <?  } //while?>
              <span >                </span><br>
              <br>
        </p>
      </div></td>
</tr>

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

