<? 
   include_once("../framework/masterheader.php");
   include_once("../classes/systemclass.php");
   include_once("../classes/ruleconfigclass.php");
   
 if ($_GET['uMemberId'] != '')
    $vUserActive=$_GET['uMemberId'];
 else  $vUserActive=$vUser;
 
 $vSpy = md5('spy').md5($_GET['uMemberId']);
 
 if ($oMember->isStockist($vUserActive)==1) 
    $vDispStock='inline';
 else   
    $vDispStock='none'; 

 if ($oMember->stockistCode($vUserActive)==2) {
     $vDispMStock='inline';
     $vDispStock='none';
     $vDispInv='none';
 } else if ($oMember->stockistCode($vUserActive)==1)  {
    $vDispMStock='none';
    $vDispStock='inline'; 
    $vDispInv='none';
 } else if ($oMember->stockistCode($vUserActive)==3)  {
    $vDispMStock='none';
    $vDispStock='none'; 
    $vDispInv='inline';    
 } else if ($oMember->stockistCode($vUserActive)==0)  {
    $vDispMStock='none';
    $vDispStock='none'; 
    $vDispInv='none';    
 } 
 
 if ($oMember->isMasterStockist($vUserActive)==3) {
 }


//print_r($_SESSION); 
 $vSQL="select max(fidstockist) as fmaxidst from m_anggota a ";
 
 $db1->query($vSQL);
 $db1->next_record();
 $vMaxStockist=$db1->f('fmaxidst');
 $vPrefix=substr($vMaxStockist,0,2);
 $vSuffix=substr($vMaxStockist,2,4);
 $vSuffix = (int) $vSuffix;
 $vSuffix++;
 $vSuffix=str_pad($vSuffix, 4, "0", STR_PAD_LEFT); 
 $vNextStID=$vPrefix.$vSuffix;
 


 $vSQL="select a.* from m_anggota a  where a.fidmember='$vUserActive'";
 
 $db->query($vSQL);
 $db->next_record();
   $vUserList=$db->f('fidmember');
   $vActiveList=$db->f('faktif');
   $vRef=$db->f('fref');
   $_SESSION['refj']=$vRef;
   

 
   $vIsStockist = $db->f('fstockist');
   $vIdStockist = $db->f('fidstockist');
  $vAutoShipList= $db->f('fautoship');
   $vCountryBank=$db->f('fcountrybank');
   $vNamaBank=$db->f('fnamabank');


   
      
   while (list($key,$val)=each($_POST)) {
      $$key = $val;
   }
 

   if ($_POST['hPost'] != '1') {
      $_SESSION['save']='';
      $_SESSION['del']='';
    
   } else {
   	  if ($_POST['hAct']=='1')	{
   	     //print_r($_POST);
   	     $vRefj=$_POST['hRefj'];
   	     $oNetwork->putMember($tfSerno,$db);
 	     $vSQL="update m_anggota set factivator='$vUser' where fidmember='$tfSerno'";  
          $db->query($vSQL);

   	     
   	   $vSQL="insert into tb_trxstok_member(`fidpenjualan`, `fidseller`, `fidmember`, `falamatkrm`, `fnostockist`, `fidproduk`, `fjumlah`, `ftanggal`, `fhargasat`, `fsubtotal`, `fsize`, `fcolor`, `ftgltrans`, `fjenis`, `fjmltrans`, `fserial`, `fpin`, `fmethod`, `fketerangan`, `ftglentry`, `fprocessed`, `ftglprocessed`, `fshipcost`) ";
   	     $vSQL.="select `fidpenjualan`, `fidseller`, `fidmember`, `falamatkrm`, `fnostockist`, `fidproduk`, `fjumlah`, `ftanggal`, `fhargasat`, `fsubtotal`, `fsize`, `fcolor`, `ftgltrans`, `fjenis`, `fjmltrans`, `fserial`, `fpin`, `fmethod`, `fketerangan`, `ftglentry`, `fprocessed`, `ftglprocessed`, `fshipcost` from tb_trxstok_member_temp where fidpenjualan='$vRefj'";
   	     $db->query($vSQL);

   	     $vSQL="delete from tb_trxstok_member_temp where fidpenjualan='$vRefj'";
   	     $db->query($vSQL);
   	     $_SESSION['refj']='';
		 $vHost='http://'.$_SERVER['HTTP_HOST'];
   	     
   	     $oSystem->jsAlert("Member sudah diaktifkan! URL Anda $vHost/$tfSerno");
		 $vMailFrom=$oRules->getSettingByField('fmailbroad');
	 	 $vSubject=$oRules->getSettingByField('fsubjactive');
	 	 $vIsi = $oRules->getSettingByField('fisiactive');
		 $vBcc = $oRules->getSettingByField('fmailbcc');
		 $vBcc2 = $oRules->getSettingByField('fmailbcc2');
	 	 $vDataMember="\nID Anda : $tfSerno\n";
	 	 $vDataMember.="Password Anda : sesuai tanggal lahir dalam format ddmmyyyy\n\n";
	 	 $vIsi=str_replace("{MEMVAR}",$vDataMember,$vIsi);
	 	 
		 
	 	 //@mail($tfEmail,$vSubject,$vIsi,"From:Admin Nexukses<admin@nexsukses.com>\n");
		 $oSystem->smtpmailer($tfEmail,$vMailFrom,'Nexsukses',$vSubject,$vIsi,$vBcc,$vBcc2);


		 
   	     $oSystem->jsLocation('../manager/approval.php');
   	  
   	  }
   
   }   
 
//   echo $tfNama;


?>

<body class="sticky-header" >
<style type="text/css">

.divtr {
	margin-top:10px;
	
	}
.divtrsmall {
	margin-top:-10px;
	
}

}
.bold {
	font-weight:bold;
	
}

@media (max-width: 600px) {
  .divtr {
	margin-top:0px;
	
	}

.divtrsmall {
	margin-top:-15px;
	
}

  } 

.zoomx{
	/*transform: scale(0.65, 0.65); */
	transform: scale(0.68, 0.68); 
}
	</style>
<script src="../js/jquery.validate.min.js"></script>
<script language="javascript">
	$.validator.setDefaults({
		submitHandler: function() {
		    document.getElementById('hOPStock').value='no';

		    if (confirm('Anda yakin melakukan menyimpan?')==true) 
			   document.frmReg.submit();
			else return false;
		}
	});
$(document).ready(function(){
 //  alert('ssss');
  // alert($('#hHarga').val());
  <? if ($_GET['uMemberId'] != '') { ?>
  $('#caption').html('Activation for member <?=$vUserActive?> (<?=$oMember->getMemberName($vUserActive);?>)');
  <?} else { ?>
  $('#caption').html('My Profile');
  <? } ?>
   $('#tfTglLahir').datepicker({
                    format: "dd-mm-yyyy"
    });  
 
 $.validator.messages.required = '<span style="color:red;font-weight:normal">This field is required!</span>';
  $('#frmReg input, #frmReg textarea,  #frmReg select, #frmReg checkbox, #frmReg radio').not([type="submit"]).not($("#tfNPWP")).not($("#tEmail")).not($("#tfSwift")).not($("#tfEmailSpon")).addClass('required');  
  $('#lmCountry').val('ID');
  $('#lmCountry').trigger('change');
  
  

		$("#frmReg").validate({
			rules: {
				tfTempat: "required",
				tfNama: "required",
				tfIdent: {
					required: true,
					minlength: 2
				},
				tfEmail: {
					required: false,
					email: false
				},
				tfEmailSpon: {
					required: false,
					email: false
				},
				
				
				
			},
			messages: {
			    tfIdent: '<span style="color:red;font-weight:normal">This field is required with minimum 9 character length!</span>',
			},
			
			 errorPlacement: function(error,element){ 
                            error.insertAfter(element); 
                          //  alert(error.html()); 
                       },
	               showErrors: function(errorMap, errorList){ 
                              this.defaultShowErrors();
                       }
		});  



$('#frmReg input').attr('readonly', 'readonly');
$('#frmReg textarea').attr('readonly', 'readonly');
$("select").attr("disabled", "disabled");

});

   function doAdd() {
       $('#lmKode').show();
       $('#lmKode').val('');
       $('#btCancel').show();  
       $('#txtJml').show();   
       $('#lmSize').show(); 
       $('#lmColor').show();
        $('#trAdd').show(); 
       $('#btSaveRow').show(); 
       

   }
   
   function doCancel() {
      $('#lmKode').hide();
      $('#btCancel').hide();
      $('#txtJml').hide();  
      $('#lmSize').hide(); 
      $('#trAdd').hide(); 
      $('#btSaveRow').hide();

	  
   }
   
   function selectProd(pParam) {
      var vNama=$('[name=lmKode] option:selected').text();
      vNama=vNama.split(';');
      vNama=vNama[1];
      
      var vHarga=  $(pParam).find('option:selected').attr("price");     
      $('#thNama').html(vNama);
      $('#thHarga').html(vHarga);
       $('#hHarga').val(vHarga);
      $('#thHarga').priceFormat({     
                    prefix: ' ',
                    centsSeparator: ',',
                    thousandsSeparator: '.',
                    limit: 15,
                    centsLimit: 0
                });

      
      var vSize=  $(pParam).find('option:selected').attr("sizes");  
      if (vSize) {
	      vSize=vSize.split(',');
	      var vOpt='<option value="">---Pilih---</option>';
	      for(i = 0; i < vSize.length; i++){
	         vOpt+='<option value="'+vSize[i]+'">'+vSize[i]+'</option>';
		  }
		  
		  if (pParam.value !='')
		     $('#lmSize').html(vOpt);
		  else   
		     $('#lmSize').html('<option value="">---Pilih---</option>');
	  } else 
	      $('#lmSize').html('<option value="">---Pilih---</option>'); 


      var vColor=  $(pParam).find('option:selected').attr("colors");  
      if (vColor) {
	      vColor=vColor.split(',');
	      var vOpt='<option value="">---Pilih---</option>';
	      for(i = 0; i < vColor.length; i++){
	         vOpt+='<option value="'+vColor[i]+'">'+$('#'+vColor[i]).val()+'</option>';
		  }
		  
		  //alert(vOpt);
		  if (pParam.value !='')
		     $('#lmColor').html(vOpt);
		  else   
		     $('#lmColor').html('<option value="">---Pilih---</option>');
	  } else 
	      $('#lmColor').html('<option value="">---Pilih---</option>'); 



   }
   
 function calcSub(pParam) {
     var vJum=pParam.value;
     var vHrg = $('#hHarga').val();
     
     var vSubTot = parseFloat(vJum) * parseFloat(vHrg);
   //  alert(vJum);alert(vHrg );alert(vSubTot );
     $('#thSubTot').html(vSubTot);
     $('#hSubTot').val(vSubTot);
     
      $('#thSubTot').priceFormat({     
                    prefix: ' ',
                    centsSeparator: ',',
                    thousandsSeparator: '.',
                    limit: 15,
                    centsLimit: 0
                });
     
     
 
}  

function doSaveRow() {
   var vURL = "register_purc_ajax.php";
   if ($('#lmKode').val()=='' || $('#lmSize').val()=='' || $('#lmColor').val()=='') {
      alert('Pilih kode produk, ukuran, dan warna!');
      return false;
   }


   
   if (parseFloat($('#txtJml').val()) <=0 || $('#txtJml').val()=='') {
      alert('Isikan jumlah item!');
      $('#txtJml').focus();
      return false;
   }
   $('#tdLoad').html('<img src="../images/ajax-loader.gif" />');
   $.post(vURL,$("#frmReg").serialize(), function(data) {
      $('#tbPurc').html(data);
      $('#tdLoad').empty();
   });
}
 

function doDel(pNo, pKode,pSize,pColor,pNama,pJml,pHarga,pSubTot) {
//alert(pNo +':'+ pKode+':'+pSize+':'+pColor+':'+pNama+':'+pJml+':'+pHarga+':'+pSubTot);  
 var vURL = "register_purc_ajax.php";
   $('#tdLoad').html('<img src="../images/ajax-loader.gif" />');
  
 $.post(vURL,{ delNo : pNo, delKode: pKode, delSize: pSize, delColor : pColor, delNama : pNama, delJml : pJml, delHarga : pHarga, delSubTot : pSubTot, op : 'del' }, function(data) {
      $('#tbPurc').html(data);
      $('#tdLoad').empty();
   });
}
  

function doReject(pKode) {
//alert(pKode);  
	 if (confirm('Are you sure to reject/delete this registrant ('+pKode+')?')==true) {
	 var vURL = "processing_ajax.php?op=del&code="+pKode;
	   $('#tdLoad').html('<img src="../images/ajax-loader.gif" />');
	  
	 $.get(vURL, function(data) {
	      if (data.trim()=='deleted') {
	         alert('Registrant was deleted!');
	         document.location.href='../manager/approval.php';
	      } else {
	      
	         alert('Delete failed!');
	      }
	      $('#tdLoad').empty();
	   });
	  }
}

function doActivate(pKode) {
//alert(pKode);  
	 if (confirm('Are you sure to activate this registrant ('+pKode+')?')==true) {
	      $('#hAct').val('1');
	      document.frmReg.submit();
	  }
}

  
function prepareProp(pParam) {
   
   var vURL="../main/mpurpose_ajax.php?op=wil&wil=prop&kodewil="+pParam.value;
 // $('#divProp').css({'background':'transparent url("../images/ajax-loader.gif")','background-repeat': 'no-repeat','background-position': 'center','z-index' : '10'});
  $('#loadProp').show();
  $.get(vURL, function(data) {
      $('#lmProp').html(data);
      $('#loadProp').hide();
	  $('#lmProp').val('<?=$db->f("fpropinsi")?>');
	  $('#lmProp').trigger('change');

   });   
}


function prepareKota(pParam) {
   var vCountry=$('#lmCountry').val();
   var vURL="../main/mpurpose_ajax.php?op=wil&neg="+vCountry+"&wil=kota&kodewil="+pParam.value;
   $('#loadKota').show();

   $.get(vURL, function(data) {
      $('#lmKota').html(data);
       $('#loadKota').hide();
 		 $('#lmKota').val('<?=$db->f("fkota")?>');

   });   
}


function checkKit(pParam) {
   if (pParam.value=='')
      return false;
   else {    
   var vCountry=$('#lmCountry').val();
   var vURL="../main/mpurpose_ajax.php?op=kit";
   var vValid=/yes/g;
   var vNotfound=/notfound/g;
   var vUsed=/used/g;

   $('#statKit').html('&nbsp;<img src="../images/ajax-loader-bar.gif" />');
   $.post(vURL, {serno : pParam.value},function(data) {
      if (vNotfound.test(data)) {
         $('#statKit').html('<font color="#f00">Nomor KIT Tidak Valid!</font>');
         document.getElementById('btnSubmit').disabled=true;

     } else if (vUsed.test(data)) {
         $('#statKit').html('<font color="#f00">Nomor KIT Sudah pernah digunakan, cobalah nomor yg lain!</font>');
         document.getElementById('btnSubmit').disabled=true;     
     } else   {
         $('#statKit').html('<font color="#00f">Nomor KIT Valid!</font>');
         document.getElementById('btnSubmit').disabled=false;

     }
   });   

  }
}

function checkKitSpon(pParam) {
   if (pParam.value=='')
      return false;
   else {    
   var vCountry=$('#lmCountry').val();
   var vURL="../main/mpurpose_ajax.php?op=kitspon";
   var vYes=/yes/g;
   var vNo=/no/g;
  
   $('#statKitSpon').html('&nbsp;<img src="../images/ajax-loader-bar.gif" />');
   $.post(vURL, {sernospon : pParam.value},function(data) {
      if (vNo.test(data)) {
         $('#statKitSpon').html('<font color="#f00">Sponsor KIT Tidak Valid!</font>');
         document.getElementById('btnSubmit').disabled=true;

     } else if (vYes.test(data)) {
         $('#statKitSpon').html('<font color="#00f">Sponsor KIT valid!</font>');
         document.getElementById('btnSubmit').disabled=false;     
     }    
     
   });   

  }
}

function setUpper(pParam) {
   document.getElementById(pParam.name).value=document.getElementById(pParam.name).value.toUpperCase();
}
function submitForm() {
   ;//document.frmReg.submit();

}

function setMaxLenRek() {
   var vSplit=$('#tfBank').val();
   vSplit=vSplit.split(';');
   var vMaxLen=vSplit[1];
  $('#tfRek').attr('maxlength',vMaxLen);
   $('#tfRek').attr('placeholder','Bank Account No* ('+vMaxLen+' digits)');



$('[id*=tfRek]').rules('add', {

   required: true,
   rangelength: [vMaxLen,vMaxLen],
   messages: {
       rangelength: 'Yeeeahh! Must be '+vMaxLen+' characters length!'
   }
});

   
   if ($('#tfBank').val() !='')
      $('#tfRek').attr('readonly', false);
   else   {
      $('#tfRek').attr('readonly', true);
      $('#tfRek').val('');
  }
	
}


function setStock(){
  if (confirm('Yakin mengangkat stockist untuk member '+document.getElementById('tfSerno').value+'?')==true) { 
    document.getElementById('hStock').value='1';
    document.getElementById('hOPStock').value='pro';
    document.frmReg.submit();
  }
}


function unsetStock(){
   if (confirm('Yakin demote stockist untuk member '+document.getElementById('tfSerno').value+'?')==true) { 
    document.getElementById('hStock').value='0';
    document.getElementById('hOPStock').value='dem';
    document.frmReg.submit();
  }
}


function setFilterBank(pParam) {
   var vURL="../main/mpurpose_ajax.php?op=bankcnt&cnt="+pParam;
 
  $('#loadBank').show();
  $.get(vURL, function(data) {
      $('#tfBank').html(data);
      $('#loadBank').hide();

   });   
   

}
function doPreview() {

$("#printarea").addClass('zoomx');

        html2canvas($("#printarea"), {onrendered: function(canvas) {
                theCanvas = canvas;
				
				var img = canvas.toDataURL();
		
				
			 var doc = new jsPDF('portrait', 'mm', 'a4');
					doc.addImage(img, 'PNG', 10, 10);
					var bloburi = doc.output('bloburi');
					window.open(bloburi);
					
			}
		});
	      
		

		$("#printarea").removeClass('zoomx');
}


</script>
<!--	<link rel="stylesheet" href="../css/screen.css">-->

	
	
 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />

<section>
    <!-- left side start-->
    <?  include "../framework/leftadmin.php"; ?>

    <!-- main content start-->
    <div class="main-content" >


      <?      include "../framework/headeradmin.php"; ?>
     

           <!--body wrapper start-->
 <section class="wrapper">
        <!-- page start-->

<form method="post" id="frmReg" name="frmReg" action="<?=$_SERVER['PHP_SELF']?>">
	<div class="container" >
    <div id="printarea"  >
    <div class="row" style="width:98%;margin-top:8px">
    
     
    
    
        <div class="col-md-6">
                <div class="panel panel-default" id="panelkiri" >
                    <div class="panel-heading" >
                             <div class="panel-title" style="margin-top:-10px">
        						<label for="exampleInputEmail1" style="font-weight:bold;display:inline">Member Data</label>
        						<div id="divStock" style="text-align:center;width:280px;background-color:maroon;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;display:<?=$vDispStock?>;border-radius:10px">&nbsp;&nbsp;&nbsp;THIS MEMBER IS A MS&nbsp;&nbsp;&nbsp;</div>
        						<div id="divMStock" style="text-align:center;width:280px;background-color:purple;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;display:<?=$vDispMStock?>;border-radius:10px">&nbsp;&nbsp;&nbsp;THIS MEMBER IS A MASTER STOCKIST&nbsp;&nbsp;&nbsp;</div>
        						<div id="divInv" style="text-align:center;width:280px;background-color:navy;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;display:<?=$vDispInv?>;border-radius:10px">&nbsp;&nbsp;&nbsp;THIS IS A WAREHOUSE&nbsp;&nbsp;&nbsp;</div>
                                 <br style="display: block;margin: -5px 0;" />                                  
                     		</div> 
                     		
                     </div>
                     
<div class="col-lg-6 col-md-6 divtr" >
                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Country 
								   *</span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-bars"></i>
                                <!--<input type="text" class="form-control" id="tfCountryBank" name="tfCountryBank" placeholder="Bank Country*"> -->
                                <select class="form-control m-bot15" id="lmCountryBank" name="lmCountryBank" onChange="setFilterBank(this.value)">
                                <?  if ($oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) {?>
                                <option  value="" selected="selected" >--Pilih / Choose--</option>
                                <? } ?>
								<? 
								    if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank"))
								       $vAnd=" and fcountry_code='".$db->f('fcountrybank')."'";
								    else $vAnd='';   
								    
								    $vSQL="select * from m_country where 1 $vAnd order by fcountry_name";
								    $dbin->query($vSQL);
								    while ($dbin->next_record()) {
								?>                               
								 <option value="<?=$dbin->f('fcountry_code')?>" <? if ($dbin->f('fcountry_code')==$db->f('fcountrybank'))  echo ' selected';?>><?=$dbin->f('fcountry_name')?></option>
								 <? } ?>
                            </select>
<? if ($_SESSION['Priv']=='administrator') { ?>
                     		 <b>Member Attribute</b> <select name="lmAttr" id="lmAttr" class="form-control">
				<option value="0" <? if ($vIsStockist == '0') echo'selected';?>>Member</option>
				<option value="1" <? if ($vIsStockist == '1') echo'selected';?>>MS</option>
				<option value="2" <? if ($vIsStockist == '2') echo'selected';?>>Master Stockist</option>
				<option value="3" <? if ($vIsStockist == '3') echo'selected';?>>Warehouse</option>
			</select>
			<? } ?>
                                </div>
                              </div>                     
                     <div class="panel-body">
                            
                            <div class="col-lg-6">Username.* 
                              <div align="left" style="display:inline" id="statKit"></div></label>
                                    <div class="iconic-input">
                                       <i class="fa fa-credit-card"></i>
                                    <input readonly="readonly" type="text" class="form-control" id="tfSerno" name="tfSerno" placeholder="Username*"  onkeyup="setUpper(this)" value="<?=$db->f('fidmember')?>" >
                                    <input type="hidden" name="hPost" id="hPost" value="1" />
                                    <input type="hidden" name="hRefj" id="hRefj" value="<?=$vRef?>" />

                                    </div>                            
                                    
                            
                            </div>     
                            
                             <div class="divtr col-lg-6">
                                    <label for="exampleInputEmail1">ID Card*</label>
                                     <div class="iconic-input">
                                        <i class="fa fa-user"></i>
                                    	<input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_ident")) echo 'readonly';?> type="text" class="form-control" id="tfIdent" name="tfIdent" placeholder="ID Card/Driving License/Passport No.*" value="<?=$db->f('fnoktp')?>">
                                      </div>
                             </div>  

                            <div class="divtr">
                                <label for="exampleInputEmail1">Full Name*</label>
                                 <div class="iconic-input">
                                    <i class="fa fa-user"></i>
                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_ident")) echo 'readonly';?> onBlur="$('#tfAtasNama').val(this.value)" onKeyUp="$('#tfAtasNama').val(this.value)" type="text" class="form-control" id="tfNama" name="tfNama" placeholder="Full Name*" value="<?=$db->f('fnama')?>" >
                                </div>
                            </div>
                               
                            <div class="form-group" style="margin-left:-15px">
                                
                               <div class="col-lg-8 col-md-8 col-xs-8 divtr " >
                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Place of Birth </span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-user"></i>
                                <input type="text" class="form-control" id="tfTempat" name="tfTempat" value="<?=$db->f('ftempatlahir')?>" placeholder="Place of Birth*">
                                </div>
                              </div>
                                            <br>                    
                                  <div class="col-lg-4 col-md-6 col-xs-8 divtrsmall" >
                                     <label for="exampleInputEmail1">&nbsp;</label>
                                     <label for="exampleInputEmail1" ><span style="font-weight:bold">Date of Birth </span></label>

                                     <div class="iconic-input">
                                    <i class="fa fa-calendar"></i>

                                        <input id="tfTglLahir" name="tfTglLahir" value="<?=$oPhpdate->YMD2DMY($db->f('ftgllahir'))?>" class="form-control default-date-picker"   type="text" placeholder="DD-MM-YYYY">
                                        </div>

                                  </div>
                                
                            </div>
                            <div class="form-group" style="margin-left:-15px" id="jenkelwn" >                      
                               <div class="col-lg-7 col-md-7 divtr" style="display:none">
                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Nationality*</span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-user"></i>
                                <select class="form-control m-bot15" id="lmNation" name="lmNation">
                                <option  value="" selected="selected" >--Pilih / Choose--</option>
								<? 
								    $vSQL="select * from m_country order by fcountry_name";
								    $dbin->query($vSQL);
								    $vDataNation=$db->f('fnation');
								    while ($dbin->next_record()) {
								?>                               
								 <option value="<?=$dbin->f('fcountry_code')?>" <? if ($dbin->f('fcountry_code')==$vDataNation) echo ' selected';?> ><?=$dbin->f('fcountry_name')?></option>
								 <? } ?>
                            </select>

                                </div>
                              </div>
                                                                
                                  <div class="col-md-5 col-xs-6 divtr" >
                                     <label for="exampleInputEmail1"><span style="font-weight:bold">Sex*</span></label>
                                     <div class="iconic-input">
                                    <i class="fa fa-male"></i>

                                        
                            <select class="form-control m-bot15" id="tfSex" name="tfSex">
                                <option value="" selected="selected">---Pilih / Choose---</option>
                                <option value="F" <? if ($db->f('fsex')=='F') echo ' selected';?>>Perempuan / Female</option>
                                <option value="M" <? if ($db->f('fsex')=='M') echo ' selected';?>>Laki-laki / Male</option>
                                <option value="O" <? if ($db->f('fsex')=='O') echo ' selected';?>>Lainnya / Other </option>
                            </select>
                           </div>

                          </div>
                                
                       </div>
                            
                        <div class="divtr">
                            <label for="exampleInputEmail1">Full Address (with postal code)*</label>
                             <div class="iconic-input">
                                <i class="fa "></i>
                                <textarea id="taAlamat" name="taAlamat" class="form-control custom-control" rows="3" style="resize:none"><?=$db->f('falamat')?></textarea>
                          <!--  <input type="text" class="form-control" id="tfNama" placeholder="Full Address along with Postal Code*"> -->
                            </div>
                        </div>

  					 <div class="form-group" style="margin-left:-15px" id="kotaprovneg">                      
                               
                                                                

   							
                               <div class="col-lg-4 col-md-4 divtr" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">Country*</span></label>
                                                                 <!-- <input type="text" class="form-control" id="tfNama" placeholder="Country*"> -->
                                <select class="form-control m-bot15" id="lmCountry" name="lmCountry" onChange="prepareProp(this)">
                                <option  value="" selected="selected" >--Pilih / Choose--</option>
								<? 
								    $vCntID=$db->f('fcountry');
								    $vSQL="select * from m_country order by fcountry_name";
								    $dbin->query($vSQL);
								    
								    while ($dbin->next_record()) {
								?>                               
								 <option value="<?=$dbin->f('fcountry_code')?>" <? if ($dbin->f('fcountry_code')==$vCntID) echo ' selected';?>><?=$dbin->f('fcountry_name')?></option>
								 <? } ?>
                            </select>

                                 </div>
                              
     							                    
                               <div class="col-lg-4 col-md-4 divtr" id="divProp">
                               <img id="loadProp"  align="absmiddle" src="../images/ajax-loader.gif" style="position:absolute;z-index:2;margin-left:45px;margin-top:24px;opacity: 0.5;display:none" />
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">Province*</span></label>
                                                                <select class="form-control m-bot15" id="lmProp" name="lmProp" onChange="prepareKota(this)">
                                <option  value="" selected="selected" >--Pilih / Choose--</option>
                                

								</select>
								
                                </div>
                            
                              
     						 <div class="col-lg-4 col-md-4 divtr" id="divKota">
                                <img id="loadKota"  align="absmiddle" src="../images/ajax-loader.gif" style="position:absolute;z-index:2;margin-left:45px;margin-top:24px;opacity: 0.5;display:none" />
                                 <label for="exampleInputEmail1" >
								 <span style="font-weight:bold">City*</span></label>
                                 
                                <select class="form-control m-bot15" id="lmKota" name="lmKota">
                                <option  value="" selected="selected" >--Pilih / Choose--</option>
                                <option  value="<?=$db->f('fkota')?>" selected="selected" ><?=$db->f('fkotaname')?></option>

								</select>

                               </div>                        
                              
                              
						</div> <!--form-group-->
   
  
 					 <div class="form-group " style="margin-left:-15px" id="phonehp">                      
                               <div class="col-lg-6 col-md-6 divtr hide" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">Phone No*</span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-phone"></i>
                                <input value="<?=$db->f('fnophone')?>" type="text" class="form-control" id="tfPhone" name="tfPhone" placeholder="Phone Number*">
                                </div>
                              </div>
                                                                
   							                    
                               <div class="col-lg-6 col-md-6 divtr" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">&nbsp;Handphone*</span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-mobile"></i>
                                <input value="<?=$db->f('fnohp')?>" type="text" class="form-control" id="tfHP" name="tfHP" placeholder="Cellular Phone*">
                                </div>
                              </div>
						</div>
    					 <div class="col-lg-6 col-md-6 divtr" >
                                <label for="exampleInputEmail1"><div ></div>Email*</label>
                                 <div class="iconic-input">
                                    <i class="fa fa-envelope"></i>
                                <input value="<?=$db->f('femail')?>" type="text" class="form-control" id="tfEmail" name="tfEmail" placeholder="Email Address*">
                                </div>
                         </div>
                               
						
	
<!-- ========================= -->
<div class="form-group " style="margin-left:-15px" id="phonehp">     
<div class="col-lg-6 col-md-3 col-sm-4 divtr" >
			<label for="tfAutoShip" ><span style="font-weight:bold">Placement*</span></label>		                               
			<select name="rbPosition" id="rbPosition" class="form-control">
							                                <option value="">--Choose Position--</option>
							                                <option <? if ($db->f('fposition')=='L') echo 'selected'; ?> value="L">Left</option>
							                                <option <? if ($db->f('fposition')=='R') echo 'selected'; ?> value="R">Right</option>
							                                </select>
                            </div>


	<div class="col-lg-6 col-md-3 col-sm-4 divtr" >
										                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Package*</span></label>
							                                     
							                               
													
							                                <select name="rbPaket" id="rbPaket" class="form-control">
							                                <option value="">--Reg. Package--</option>
							                                <option <? if ($db->f('fpaket')=='S') echo 'selected'; ?> value="S">Silver</option>
							                                <option <? if ($db->f('fpaket')=='G') echo 'selected'; ?> value="G">Gold</option>
							                                <option <? if ($db->f('fpaket')=='P') echo 'selected'; ?> value="P">Platinum</option>
							                                </select>
                            </div>
<!-- ========================= -->

<div class="col-lg-6 col-md-3 col-sm-4 divtr" >
			<label for="tfAutoShip" ><span style="font-weight:bold">Autoship Item(s)*</span></label>		                               
			<select  class="form-control m-bot15" id="tfAutoShip" name="tfAutoShip"  >
                                <option  value="" >--Choose--</option>
								<? 
								    $vSQL="select * from m_product where faktif='1' order by fidsys";
								    $dbin->query($vSQL);
								    while ($dbin->next_record()) {
								?>                               
								 <option  value="<?=$db->f('fidproduk')?>" <? if ($vAutoShipList == $dbin->f('fidproduk')) echo 'selected';?>  ><?=$dbin->f('fnamaproduk')?></option>
								 <? } ?>
                            </select>
                            </div>
</div>


			<div class="row" style="margin-top:2em">
      <div class="col-lg-12 col-md-6 divtr" style="display:none">
                                <label  for="exampleInputEmail1" ><span style="font-weight:bold">Password</span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-key"></i>
                                    <?
                                       $vPass=$oSystem->doED('decrypt',$db->f('fpassword'));
                                    ?>
                                <input  type="button" class="btn btn-success" value="Show Password" onClick="alert('Password :<?=$vPass?>')">
                                <input value="<?=$oSystem->doED('decrypt',$db->f('fpassword'))?>" type="hidden" id="tfPass" name="tfPass" >
                                </div>
                              </div>
       </div>
                         </div> <!--Panel Body-->
                         
                         
                         
                         
                </div> <!--Col-md-6 kiri-->
				     
        </div>
        <div class="col-md-6" id="kolomkanan">
                    <div class="panel panel-default" id="panelkanan">
                    <div class="panel-heading" >
                             <div class="panel-title" style="margin-top:-10px">
        						<label for="exampleInputEmail1" style="font-weight:bold;">
								 Member Bank Account</label>
                               <br style="display: block;margin: -5px 0;" />                                  
                     		</div>
                     </div>
                     <div class="panel-body">

 <div class="form-group" style="margin-left:-15px" >   
    
                                                 
                         <div class="col-lg-6 col-md-6 divtr">
                         <img id="loadBank"  align="absmiddle" src="../images/ajax-loader.gif" style="position:absolute;z-index:2;margin-left:45px;margin-top:24px;opacity: 0.5;display:none" />
                             <label for="exampleInputEmail1"><b>Bank 
							 Name*</b></label>
                                   <div class="iconic-input">
                                   <i class="fa fa-building-o"></i>
                                               <!-- <input readonly="readonly" value="<?=$db->f('fnamabank')?>" type="text" class="form-control" id="tfBank" name="tfBank" placeholder="Bank Name*"> -->
                                                
                                                <select  class="form-control m-bot15" id="tfBank" name="tfBank"  onchange="setMaxLenRek()">
                                <? if ($oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) { ?>
                                <option readonly  value="" selected="selected" >--Choose--</option>
                                <? } ?>
								<? 
								    if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank"))
								       $vAnd=" and fkodebank='".$db->f('fnamabank')."'";
								    else $vAnd='';   
   
								$vSQL="select * from m_bank where faktif='1' and fcountry_code='$vCountryBank'  $vAnd order by fnamabank";
								    $dbin->query($vSQL);
								    while ($dbin->next_record()) {
								?>                               
								 <option <? if ($vNamaBank == $dbin->f('fkodebank')) echo 'selected';?>   value="<?=$dbin->f('fkodebank').';'.$dbin->f('fmaxdigit')?>"  ><?=$dbin->f('fnamabank')?></option>
								 <? } ?>
                            </select>

                                   </div>
                         </div>       
 </div> <br> <br> <br>
                         <div class="divtr">
                             <label for="exampleInputEmail1">Bank Account Number.*</label>
                                   <div class="iconic-input">
                                   <i class="fa fa-building-o"></i>
                                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?>  value="<?=$db->f('fnorekening')?>" type="text" class="form-control" id="tfRek" name="tfRek" placeholder="Bank Account No*">
                                   </div>
                         </div>       
          				<div class="divtr">
                                    <label for="exampleInputEmail1">Name in Account*</label>
                                     <div class="iconic-input">
                                        <i class="fa fa-user"></i>
                                    	<input readonly="readonly" value="<?=$db->f('fatasnama')?>" type="text" class="form-control" id="tfAtasNama" name="tfAtasNama" placeholder="Bank Account Name (Same With The Distributor's Name)*">
                                      </div>
                             </div>  

			 <div class="form-group" style="margin-left:-15px" id="kotacabnegbank">                      
                               <div class="col-lg-6 col-md-6 divtr" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">Bank City*</span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-bars"></i>
                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?> value="<?=$db->f('fkotabank')?>" type="text" class="form-control" id="tfKotaBank" name="tfKotaBank" placeholder="Bank City*">
                                </div>
                              </div>
                                                                
   							                    
                               <div class="col-lg-6 col-md-6 divtr" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">
								   Bank Branch*</span></label>
                                 <div class="iconic-input">
                                    <i class="fa fa-bars"></i>
                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?> value="<?=$db->f('fcabbank')?>" type="text" class="form-control" id="tfBranchBank" name="tfBranchBank" placeholder="Bank State/Branch*">
                                </div>
                              </div>

   							
                               
						
             				<div class="col-lg-6 col-md-6 divtr">
                                    <label for="exampleInputEmail1"><div class="divtr"></div>Bank Swift Code (outside Indonesia only)</label>
                                     <div class="iconic-input">
                                        <i class="fa fa-user"></i>
                                    	<input value="<?=$db->f('fswift')?>" type="text" class="form-control" id="tfSwift" name="tfSwift" placeholder="Outside Indonesia Only*">
                                      </div>
                             </div>  

             				<div class="col-lg-6 col-md-6 divtr">
                                    <label for="exampleInputEmail1"><div class="divtr"></div>
									Tax Card Number</label>
                                     <div class="iconic-input">
                                        <i class="fa fa-user"></i>
                                    	<input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?> value="<?=$db->f('fnpwp')?>" type="text" class="form-control" id="tfNPWP" name="tfNPWP" placeholder="Taxpayer Registration Number*">
                                      </div>
                             </div>  
</div> <!--form-group -->
                            
                            </div>  




                     </div> <!--Panel Body -->
                     
                     							<!-- <div class="divtr">
                            <!-- Panel Sponsor -->

			                    <div class="panel panel-default" id="panelkanan">
					                    <div class="panel-heading" >
					                             <div class="panel-title" style="margin-top:-10px">
					        						 <label for="exampleInputEmail1" style="font-weight:bold;">Sponsor 
													 Data</label>
					                               <br style="display: block;margin: -5px 0;" />                                    
					                     		</div>
					                     </div>
					                     <div class="panel-body">
					                            
					                            
					                            
					                            <div class="">
													<label for="exampleInputEmail1">
													Username* 
													<div align="left" style="display:inline" id="statKitSpon"></div></label>
					                                    <div class="iconic-input">
					                                       <i class="fa fa-credit-card"></i>
					                                    <input readonly="readonly" value="<?=$db->f('fsernospon')?>" type="text" class="form-control" id="tfSernoSpon" name="tfSernoSpon" placeholder="Sponsor Card/Starter Kit No*" onBlur="checkKitSpon(this)" onKeyUp="setUpper(this)">
					                                    </div>                            
					                            
					                            </div>    					                            

					                            <div class="divtr">
					                                <label for="exampleInputEmail1">
													Sponsor Name*</label>
					                                 <div class="iconic-input">
					                                    <i class="fa fa-user"></i>
					                                    <?
					                                       $vSQL="select * from m_anggota where fidmember='".$db->f('fidressponsor')."'";
					                                        $dbin->query($vSQL);
					                                        $dbin->next_record();
					                                    ?>
					                                <input readonly="readonly" value="<?=$dbin->f('fnama')?>" type="text" class="form-control" id="tfSponsor" name="tfSponsor" placeholder="Sponsor Name*">
					                                </div>
					                            </div>


												 <div class="form-group" style="margin-left:-15px" id="phonemailspon">                      
							                               <div class="col-lg-6 col-md-6 divtr" >
							                                   <label for="exampleInputEmail1" >
															   <span style="font-weight:bold">
															   Sponsor's Phone 
															   Number*</span></label>
							                                 <div class="iconic-input">
							                                    <i class="fa fa-phone"></i>
							                                <input readonly="" value="<?=$dbin->f('fnohp')?>" type="text" class="form-control" id="tfPhoneSpon" name="tfPhoneSpon" placeholder="Sponsor Phone Number*">
							                                </div>
							                              </div>
							                                                                
							   							                    
							                               <div class="col-lg-6 col-md-6 divtr" >
							                                   <label for="exampleInputEmail1" >
															   <span style="font-weight:bold">
															   Sponsor's Email 
															   Address*</span></label>
							                                 <div class="iconic-input">
							                                    <i class="fa fa-envelope"></i>
							                                <input readonly="" value="<?=$dbin->f('femail')?>" type="text" class="form-control" id="tfEmailSpon" name="tfEmailSpon" placeholder="Sponsor's Email Address*">
							                                </div>
							                              </div>



													</div>


					                     </div>
			                     </div>


                </div> <!--Kolom Kanan -->
<div class="table-responsive" id="tbPurc">
<table class="table" id="detail">
                            <thead>
                            <tr>
                                <th width="3%">#</th>
                                <th width="15%">Product Code</th>
                                <th width="25%">Product Name</th>
                                <th width="9%" class="hide">Ukuran</th>
                                <th width="9%" align="right">Set Qty</th>
                                <th style="width: 10%" align="right">Item Qty</th>
                                <th style="width: 104px" align="right">Set Price</th>
                                <th style="width: 94px" align="right">Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                               $vSQL="SELECT * FROM `tb_trxstok_member_temp`  where fidmember= '$vUserActive'";
                               $db1->query($vSQL);
                               $vTotal=0;
                               while($db1->next_record()) {
                            ?>
                            <tr id="trAdd" style="display:">
                                <th style="width: 33px; height: 30px;"></th>
                                <th style="width: 208px; height: 30px;">
                                <?=$db1->f('fidproduk')?></th>
                                <th id="thNama" style="height: 30px;text-align:left" ><?=$oProduct->getProductName($db1->f('fidproduk'))?></th>
                                <th id="thUkur" style="height: 30px" class="hide">&nbsp;
                                
                                </th>
                                <th style="height: 30px"> 
                                <?=number_format($db1->f('fjumlah'),0,",",".")?></th>
                                <th style="height: 30px; width: 10%;" align="left" id="thJmlItem"> 
                                
                                

                                <?=number_format($db1->f('fcolor'),0,",",".")?></th>
                                <th style="width: 104px; height: 30px;" id="thHarga">
								<?=number_format($db1->f('fhargasat'),0,",",".")?></th>
                                <th align="right" id="thSubTot" style="height: 30px; width: 94px;">
								<?=number_format($db1->f('fsubtotal'),0,",",".");$vTotal+=$db1->f('fsubtotal');?></th>
                                <th style="display:none; height: 30px; width: 30px;"></th><input type="hidden" name="hSubTot" id="hSubTot" value="" /></th>
                            </tr>
                            
                            <? } ?>

<tr id="trAdd" style="display:">
                                <th style="width: 33px; height: 30px;"></th>
                                <th style="width: 208px; height: 30px;">&nbsp;
                                </th>
                                <th id="thNama" style="height: 30px;text-align:left" >&nbsp;
								</th>
                                <th id="thUkur" style="height: 30px" class="hide">&nbsp;
                                
                                </th>
                                <th style="height: 30px">&nbsp; 
                                </th>
                                <th style="height: 30px; width: 10%;" align="left" id="thJmlItem">&nbsp; 
                                
                                

                                </th>
                                <th style="width: 104px; height: 30px;" id="thHarga">
								Total : </th>
                                <th align="right" id="thSubTot" style="height: 30px; width: 94px;">
								<?=number_format($vTotal,0,",",".")?></th>
                                <th style="display:none; height: 30px; width: 30px;"></th><input type="hidden" name="hSubTot" id="hSubTot" value="" /></th>
                            </tr>
                           
                            </tbody>
                        </table>
                    </div>                
        </div>
        </div>
 <div class="row">
                           
<?
   if ($vActiveList=='1')
      $vDis='disabled';
   else $vDis='';   
?>
					  <div class="col-md-6 form-group ">
									
                               <div>
  								<div class="iconic-input" style="display:inline">
                                <i class="fa fa-print"></i>
                                <input  type="button" class="btn  btn-primary" value="Print Preview" onClick="doPreview()" >
                                </div>
                               
                                    <button  <?=$vDis?> type="button" class="btn  btn-primary"  onClick="doActivate('<?=$vUserList?>')">
                                    <i class="fa fa-check"></i>
                                    
                                   Activate
                                    </button>
 									<input type="hidden" id="hAct" name="hAct" value="">
                            
                                
  								<button <?=$vDis?> type="button" class="btn  btn-danger"  onClick="doReject('<?=$vUserList?>')" >
                                    <i class="fa fa-trash-o"></i>
									
                                    Reject / Delete
                                    
                                </button> <span id="tdLoad"></span>

  								<div class="iconic-input" style="display:inline">
                                   

                                    <input  type="button" class="btn  btn-default" value="Cancel" onClick="document.location.href='../manager/approval.php'" ><span id="tdLoad"></span>
                                </div>

								<? if (false) {?>
								<? if ($oSystem->checkPriv($_SESSION['LoginUser'],"mdm_admin")) { ?>
								<? if ($oMember->isStockist($vUserActive)==0) { ?>
                               
								 <div class="iconic-input" style="display:inline">
                                    <i class="fa fa-building-o" style="display:inline"></i>
                                        <input  id="btnSubmit" type="button" class="btn btn-success"  onclick="setStock();" value="Promote Stockist">
                                </div>        
                                <? } ?>
                                <? if ($oMember->isStockist($vUserActive)==1) { ?>

                                <div class="iconic-input" style="display:inline">
                                    <i class="fa fa-times" style="display:inline"></i>
                                        <input  id="btnSubmit" type="button" class="btn btn-danger"  onclick="unsetStock();" value="Demote Stockist">
                                </div> 
                                <? } ?>       
                                <? } ?>
                                <? } ?>

                                </div>
                                
                                
                                 <div id="divLoad" style="display:inline"></div>

                                      


                                        <input type="hidden" name="hStock" id="hStock" value="<?=$db->f('fstockist')?>">
                                        
                                        <input type="hidden" name="hOPStock" id="hOPStock" value="no">

                            </div>

 </div>            
    </div>
<hr /><br />
        
   
        
                                                  
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
<script src="../js/FileSaver.js"></script>
<script src="../js/html2canvas.js"></script>
<script src="../js/jquery.printElement.js"></script>
<script src="../js/jspdf.debug.js"></script>

</body>
</html>
