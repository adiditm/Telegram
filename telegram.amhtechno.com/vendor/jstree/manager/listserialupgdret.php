<? include_once("../framework/admin_headside.blade.php")?>
  <? include_once("../classes/networkclass.php");
     include_once("../classes/systemclass.php"); 
include_once("../server/config.php")
?>
  <?
  define("MENU_ID", "royal_setting_list_jual_kartu");
  $vRefUser=$_GET['uMemberId'];
  if (isset($vRefUser))
  	 $vUser=$vRefUser;
  else	 
  	 $vUser=$_SESSION['LoginUser'];

	$vFilterSerial=$_POST['tfSerial'];
	$vFilterSerialFrom=$_POST['tfSerialFrom'];
	$vFilterSerialTo=$_POST['tfSerialTo'];
	
	$vFilterPin=$_POST['tfPin']; 
	$vFilterStatus=$_POST['lmStatus'];
	$vPaket=$_POST['lmPaket'];
	$vBuyer=$_POST['tfBuyer'];

	$vAnd="";

	if ($vFilterSerial!="") $vAnd.=" and a.fserno like '%$vFilterSerial%' ";
	if ($vFilterSerialFrom!="" &&  $vFilterSerialTo!="") $vAnd.=" and a.fserno >= '$vFilterSerialFrom' and a.fserno <= '$vFilterSerialTo'";
	if ($vFilterPin!="")  $vAnd.=" and a.fpin like '%$vFilterPin%' "; 
	if ($vFilterStatus=="-")  ;
	
	  $vAnd.=" and a.fstatus = '2' "; 
	
	
	if ($vPaket=="B")  $vAnd.=" and a.fpaket = 'B' "; 
	if ($vPaket=="P")  $vAnd.=" and a.fpaket = 'P' "; 
	
	if (trim($vBuyer) !='') 
	   $vAnd.=" and a.fpendistribusi like '%$vBuyer%' "; 
	    
	
    $vsql="select * from tb_skit_upg a ";
	 $vsql.=" where 1  $vAnd order by fserno ";
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
	
	$vDelAct=$_POST['hDel'];
	$vDelSer=$_POST['hDelSer'];
	
	if ($vDelAct=="y") {
	  $vsqlin="delete from tb_skit_upg where fserno='$vDelSer'";
	  $dbin->query($vsqlin); 
	  $oSystem->jsAlert("Serial $vDelSer deleted!");
	}
   
   
//Hapus   
if ($_POST['hPostDel']=="1" ){
$vcbChoice=$_POST['cbChoice'];
$vCount=0;
while (list($key,$val) = @each($vcbChoice)) { 
      $vkd=$val;   
	  $vsqlin="delete from tb_skit_upg where fserno='$vkd'";
	  $dbin->query($vsqlin); 
   $vCount+=1;
}
  $oSystem->jsAlert("$vCount member terhapus!");
}
  
//Penjualan
if ($_POST['hPostJual']=="1" ){
	$vcbChoice=$_POST['cbChoice'];
	$vPembeli=$_POST['tfPembeli'];
	
//	if ($oMember->authActiveID($vPembeli)==1 ) {
	
		$vCount=0;
		$vIDJual='KIT'.date('YmdHis').".".$oSystem->generateRandomString(3);
		$vSeller=$_SESSION['LoginUser'];
		while (list($key,$val) = @each($vcbChoice)) { 
			  $vkd=$val;   
			  
			  $vSQL="select * from tb_skit_upg where fserno='$vkd'";
			  $dbin->query($vSQL);
			  $dbin->next_record();
			  $vPembeli=$dbin->f('fpendistribusi');
			  
			  $vsqlin="update tb_skit_upg set fpendistribusi='', ftgldist='1981-01-01 00:00:00',fstatus='1' where fserno='$vkd'";
			  $dbin->query($vsqlin); 
			  
			  $vSQL=" INSERT INTO tb_trxkit( fidpenjualan, fidseller, fidmember, falamatkrm, fnostockist, fserno,ftglentry,fkindtrx) ";			  
			  $vSQL .=" values( '$vIDJual', '$vSeller', '$vPembeli', '', '', '$vkd',now(),'returupg') ";		
			  $dbin->query($vSQL); 	  
			  
		   $vCount+=1;
		   
		}
		  $oSystem->jsAlert("$vCount kartu diretur dari \'$vPembeli\'/".$oMember->getMemberName($vPembeli)."!");
	// } else  $oSystem->jsAlert("Member \'$vPembeli\' tidak ada, atau tidak aktif! Anda tidak dapat retur dari pembeli tersebut!");
}
   
 
?>

  <script language="JavaScript" type="text/JavaScript">
<!--
<!--

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

function doDel(pSerial) {
   vSure=confirm('Apakah Anda yakin menghapus serial '+pSerial+' ini?');
   if (vSure==true) {      
       document.getElementById('hDel').value="y";
	   document.getElementById('hDelSer').value=pSerial;
	   document.demoform.submit();
   } 
}

function toggleCheck(cbChoice) {
    
    var vCount=0;
	for (i = 0; i < cbChoice.length; i++) {
		cbChoice[i].checked = document.demoform.cbAll.checked && !cbChoice[i].disabled;
		if (!cbChoice[i].disabled) vCount+=1;
		
	}	
	
	if (vCount>0 && document.demoform.cbAll.checked) {
	  
	   document.demoform.cbSelfJual.disabled=false;
	   alert(vCount+' selected!');
	}   
	else  { 
	   
	   document.demoform.cbSelfJual.disabled=true;
	}   
}  


function doSelfDel() {
  if (confirm('Yakin menghapus semua yang dipilih?')==true) { 
   document.demoform.hPostDel.value="1";
   document.demoform.submit();
  } 

}


function doSelfJual() {
  if (confirm('Warning!! Yakin meretur semua yang dipilih? ')==true) { 
       document.demoform.hPostJual.value="1";
     document.demoform.submit();

  } 

}

//-->

$(document).ready(function(){

    $('#caption').html('Retur  Serial Upgrade');


	

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
.style2 {font-weight: bold}
.style3 {
	font-size: 12px;
	font-weight: bold;
}
.style4 {font-weight: bold}
.style5 {
	font-family: Arial;
	font-weight: bold;
	font-size: 8.0pt;
}
-->
.divtr {margin-top:1em}
</style>
  <div class="content-wrapper">
    <section class="content">


            <form id="demoform" name="demoform" method="post" action="" >
              <input name="hPageNum" type="hidden" id="hPageNum">
              <input name="hPage" type="hidden" id="hPage" value="<?=$curpage?>">
              <input name="hBtn" type="hidden" id="hBtn">
              <input name="ref" type="hidden" id="ref" >
            
                <div class="row">
                  <div class="col-lg-2">
                  <label for="tfSerial">Serial</label>
                    </div>
                     <div class="col-lg-4">
                      <input name="tfSerial" type="text" class="form-control" id="tfSerial" value="<?=$vFilterSerial?>" />
                    </div>
                </div>
                
                 
                <div class="row divtr hide ">
                  <div class="col-lg-2">
                  <label for="tfSerial">Range Serial</label>
                  </div>
                  <div class="col-lg-2">                  
                      <input name="tfSerialFrom" type="text" class="inputborder" id="tfSerialFrom" value="<?=$vFilterSerialFrom?>" />
                   </div>   
                     <div class="col-lg-1"> 
                      s.d
                     </div> 
                      <div class="col-lg-2"> 
                      <input name="tfSerialTo" type="text" class="inputborder" id="tfSerialTo" value="<?=$vFilterSerialTo?>" />
                      </div>
                    </div>

               
                 
                    
<div class="row divtr hide">
                  <div class="col-lg-2">
                  <label for="tfSerial">Paket</label>
                  </div>
					<div class="col-lg-4">
                      <select name="lmPaket" id="lmPaket" class="form-control">
                        <option value="-" selected="selected">All</option>
                        <option value="B" <? if ($vPaket=="B") echo "selected" ?>>Basic</option>
                        <option value="P" <? if ($vPaket=="P") echo "selected" ?>>Premium</option>

                      </select>
                    </div>
                    </div>

<div class="row divtr">
                  <div class="col-lg-2 ">
                  <label for="tfSerial">Terjual Kepada</label>
                    </div>
                     <div class="col-lg-4">
                      <input name="tfBuyer" type="text" class="form-control" id="tfBuyer" value="<?=$vBuyer?>" placeholder="Masukkan ID Member (bukan nama)" />
                    </div>
                </div>
                    
              <p><font face="Verdana, Arial, Helvetica, sans-serif"><strong><br />
                </strong>&nbsp;&nbsp;
                <div class="row" ><div align="left" class="col-lg-6"><input name="Submit22" type="button" class="btn btn-success" onclick="MM_callJS('doSubmit(\'cari\')')" value="   Lihat   "  /></div></div>
                </font></p>
              <p align="right"><strong>Page
                <select name="select" id="select2" onchange="changePage(this)" class="form-control" style="max-width:70px">
                  <? for ($i=0;$i<$pagenum;$i++) {?>
                  <option value="<?=$i+1 ?>" <? if ($curpage==($i)) echo "selected";?>>&nbsp;
                  <?=$i+1?>
                  &nbsp;</option>
                  <? } ?>
                </select>
                </strong></p>
              <div class="row">
                <div class="col-lg-8">
                  <input name="hPostDel" type="hidden" id="hPostDel" />
                  <input name="cbSelfJual" type="button" id="cbSelfJual" onclick="doSelfJual();" value="Retur" disabled="disabled" class="btn btn-success btn-sm" />
                      <input name="hPostJual" type="hidden" id="hPostJual" />
                </div>
                      
              </div>
              <br />
              <div class="table-responsive">
              <table  border="0" cellspacing="0" cellpadding="0" width="65%" class="table table-striped">
                <tr style="height:12.75pt;background-color:#99CCFF">
                  <td width="73" align="center" valign="middle" nowrap="nowrap" ><span style="font-family:Arial; font-size:8.0pt; ">No</span></td>
                  <td width="159" align="center" valign="middle" nowrap="nowrap" >Serial</td>
                  <td width="116" align="center" valign="middle" nowrap="nowrap" >Paket</td>
                  <td width="277" align="center" valign="middle" nowrap="nowrap" >Status</td>
                  <td width="110" valign="bottom" nowrap="nowrap" align="center"><label>
                      <input name="cbAll" type="checkbox" id="cbAll" value="checkbox" onclick="toggleCheck(document.demoform.cbChoice);" />
                    </label></td>
                </tr>
                <?
  $vsql="select a.*,b.fidmember,b.fnama,a.fpendistribusi from tb_skit_upg a left join m_anggota b on a.frefsell=b.fidmember ";
  $vsql.="where 1  $vAnd order by fserno";
  $vsql.=" limit  $offset, $rowpage ";
  $db->query($vsql);
  $vNo=0;
  $vTotalSpon=0;$vTotalCoup=0;$vTotalMatch=0;$vTotalLevel=0;$vTotalBonus=0;
  while ($db->next_record()) {
    $vSerno=$db->f("fserno");
	$vPaket=$db->f("fpaket");
	$vNo+=1;
	
?>
                <tr style="height:12.75pt;" <? if(($vNo % 2)==1) echo "bgcolor='#FFFFF3'"; else echo "bgcolor='#CCCCFF'"; ?>>
                  <td width="73" nowrap="nowrap" valign="bottom" >
                    <?=$vNo+$offset?>
                    </span></td>
                  <td width="159" nowrap="nowrap" valign="bottom" >&nbsp;</span>                    <?=$vSerno?></td>
                  <td width="116" nowrap="nowrap" valign="bottom" >&nbsp;</span>                    <?=$vPaket?></td>
                  <td width="277" nowrap="nowrap" valign="bottom" ><span style="font-family:Arial; font-size:10.0pt; ">&nbsp;</span>                    <?
				$vUserID=$db->f("fidmember");
				$vNama=$db->f("fnama");
				$vBuyer=$db->f("fpendistribusi");
				if ($vUserID!="") {
				   echo "Terjual, Used by : ".$vUserID." / ".$vNama;
				   $vStatus=1;
				}  
				else if ($vBuyer!="" && $vUserID=="") {
				   echo "Terjual kepada member $vBuyer/".$oMember->getMemberName($vBuyer);   
				   $vStatus=2;
				}   
				else {
				   echo "Belum terjual"  ;
				   $vStatus=3;
				}   
			
			?></td>
                  <td width="110" nowrap="nowrap" valign="bottom" align="center" ><input name="cbChoice[]" type="checkbox" id="cbChoice" onclick="document.demoform.cbSelfJual.disabled=!this.checked" value="<?=$vSerno?>" <? if ($vStatus!=2) echo "disabled";?> /></td>
                </tr>
                <? } ?>
              </table>
              </div>
              <p align="right"><strong>
                <input name="hDelSer" type="hidden" id="hDelSer" value="" />
                <input name="hDel" type="hidden" id="hDel" value="" />
                <input name="ref" type="hidden" id="ref" />
                Page
                <select name="select2" id="select" onchange="changePage(this)" class="form-control" style="max-width:70px">
                  <? for ($i=0;$i<$pagenum;$i++) {?>
                  <option value="<?=$i+1 ?>" <? if ($curpage==($i)) echo "selected";?>>&nbsp;
                  <?=$i+1?>
                  &nbsp;</option>
                  <? } ?>
                </select>
                </strong></p>
            </form>
            <p>&nbsp;</p>
            <p>
              <input class="hide" name="btnBukti" type="button"  id="btnBukti" onClick="MM_goToURL('parent','loggedin.php?menu=showbukti&uUser=<?=base64_encode($vUser)?>');return document.MM_returnValue" value="Lihat Bukti Pembayaran">
              <br>
            </p>
            <blockquote>
              <div align="left"></div>
            </blockquote>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?
 include('../framework/admin_footside.blade.php');
?>
