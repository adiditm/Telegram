<? 
        if ($_GET['op'] != '') 
           include_once("../framework/admin_headside.blade.php");
        else
           include_once("../framework/member_headside.blade.php") ;  
     


   
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
 

 
   $vIsStockist = $db->f('fstockist');
   $vIdStockist = $db->f('fidstockist');
  $vAutoShipList= $db->f('fautoship');
   $vCountryBank=$db->f('fcountrybank');
   $vNamaBank=$db->f('fnamabank');
    $vOutletList=$db->f('foutlet');
	$vImage=trim($db->f('fimage'));


   
      
   while (list($key,$val)=each($_POST)) {
      $$key = $val;
   }
 

   if ($_POST['hPost'] != '1') {
      $_SESSION['save']='';
      $_SESSION['del']='';
    
   } else {
    //mail("a_didit_m@yahoo.com","Update Uneeds",print_r($_POST,true)."\n\n\n".print_r($_SESSION['save'],true));
	$oSystem->smtpmailer('japri_s@yahoo.com', 'no-reply@spectra2u.com', 'Spectra Debug', 'Update Profile', print_r($_POST,true)."\n\n\n".print_r($_SESSION['save'],true),'','',false);
    	 $tfBank = explode(";",$tfBank);
  	 $tfBank = $tfBank[0];
  	 $rbPaket = explode(";",$rbPaket);
  	 $rbPaket = $rbPaket[0];
  	 
  	 $vNomPaket=$rbPaket[1];
  	 //echo $tfTempat."sssssssssss";
  	 
     $tfTglLahir=$oPhpdate->DMY2YMD($tfTglLahir);
     if ($_SESSION['Priv']=='administrator')
        $vRedir="../manager/aktif.php";
     else    $vRedir="../memstock/profile.php";

  	 if ($oMember->updateMember($tfSerno,$tfIdent,$tfNama,$tfTempat,$tfTglLahir,$lmNation,$tfSex,$taAlamat,$lmCountry,$lmProp,$lmKota,$tfPhone,$tfHP,$tfEmail,$tfBank,$tfRek,$tfAtasNama,$tfKotaBank,$tfBranchBank,$lmCountryBank,$tfSwift,$tfNPWP,$tfSernoSpon,$hStock,$tfAutoShip,$hPict)==1) {  
  	       	     if ($_POST['hOPStock'] == 'pro')
  	       	        $vAdd="(promote stockist $tfSerno)";
  	       	     else if ($_POST['hOPStock'] == 'dem')
  	       	        $vAdd="(demote stockist $tfSerno)";
  	       	     else if ($_POST['hOPStock'] == 'no' || $_POST['hOPStock'] =='')   
  	       	        $vAdd="";
  	       	     
				if ($vIsStockist==0 && $_POST['hOPStock'] == 'pro') {
				    $vSQLX="update m_anggota set fidstockist='$vNextStID' where fidmember='$tfSerno'";
				    $db->query($vSQLX);
				}   

				$vNewStockStat = $_POST['hStock'];
				$vOldStockStat = $_POST['hStockOld'];
				if ($vOldStockStat != $vNewStockStat ) { //logging
				    if ($vOldStockStat =='0' && $vNewStockStat =='1') {
						$vKet="Promote mobile stockist $tfSerno";
					} else if ($vOldStockStat =='0' && $vNewStockStat =='2') {
						$vKet="Promote mobile stockist $tfSerno";
					} else if ($vOldStockStat =='0' && $vNewStockStat =='3') {
						$vKet="Promote master stockist $tfSerno";
					} else if ($vOldStockStat =='1' && $vNewStockStat =='2') {
						$vKet="Promote  stockist from mobile $tfSerno";
					} else if ($vOldStockStat =='1' && $vNewStockStat =='3') {
						$vKet="Promote master stockist from mobile $tfSerno ";
					} else if ($vOldStockStat =='2' && $vNewStockStat =='3') {
						$vKet="Promote master stockist from stockist $tfSerno ";
					} else if ($vOldStockStat =='2' && $vNewStockStat =='0') {
						$vKet="Demote stockist $tfSerno";
					} else if ($vOldStockStat =='3' && $vNewStockStat =='0') {
						$vKet="Demote master stockist $tfSerno";
					} else if ($vOldStockStat =='1' && $vNewStockStat =='0') {
						$vKet="Demote mobile stockist $tfSerno";
					}
					
					$vSQL="INSERT INTO tb_logchange(fkdanggota, fold, fnew, ftipe, fket,  ftglentry) VALUES ('$tfSerno', '$vOldStockStat', '$vNewStockStat', 'promo-demo', '$vKet',  now());";
				    $db1->query($vSQL);
					

				$vSQL="select * from tb_stockist_temp where fidmember='$tfSerno' ";
					$db1->query($vSQL);
					$db1->next_record();
					$vIdMember= $db1->f('fidmember');
					$vIdSponsor= $db1->f('fidsponsor');
					$vLevel = $db1->f('ftype');

					$vPoin = 20; 
					$vFeeOneMob=$oRules->getSettingByField('ffeeonestmob');
					$vFeeOneStd=$oRules->getSettingByField('ffeeoneststd');
					$vFeeOneMst=$oRules->getSettingByField('ffeeonestmst');
				   if ($vOldStockStat =='1' && $vNewStockStat =='2') {
						  $vFee = $vPoin * 3 ;
						  $vFeeSpon = 16;
						  $vFeeOne = $vFeeOneStd - $vFeeOneMob;
					   } else    if ($vOldStockStat =='1' && $vNewStockStat =='3') {
						  $vFee = $vPoin * 9 ;
						  $vFeeSpon = 36;
						  $vFeeOne = $vFeeOneMst - $vFeeOneMob;
					   } else    if ($vOldStockStat =='2' && $vNewStockStat =='3') {
						  $vFee = $vPoin * 6 ;
						  $vFeeSpon = 20;
						  $vFeeOne = $vFeeOneMst - $vFeeOneStd;
					   }
			
					 $oKomisi->spreadStBonus($vIdMember,0,$vFee,'bnstock','poin','Bonus Aktifasi sebagai Stockist',$vIdMember,$vKet);
					//$vSpon = $oNetwork->getSponsor($vIdMember);
					 $vSpon = $vIdSponsor;
 					//Spon
					$oKomisi->spreadStBonus($vSpon,0,$vFeeSpon,'bnstockspon','poin',"Bonus Sponsor Aktifasi Stockist $vIdMember",$vIdMember,$vKet);					
					 //Onetime
					 $oKomisi->spreadStBonus($vSpon,0,$vFeeOne,'bnsone','nom',"Bonus Onetime aktifasi sebagai Sponsor Stockist $vIdMember",$vIdMember,$vKet);
					 
					 
		 					
				}   
				
				
  	       	     $oSystem->jsAlert("Update $vAdd sukses!");
  	       	     $oSystem->jsLocation($vRedir);
  	       	     
	 }	else {
	 
				//  $oSystem->jsAlert("Update gagal, atau mungkin tidak ada perubahan yg Anda lakukan!");
				 $oSystem->jsAlert("Update $vAdd sukses!");

				  $oSystem->jsLocation($vRedir);


	 }  
  	  $oMember->setAttr($tfSerno,$lmAttr);
  	  }   
 
//   echo $tfNama;


?>

<link href="https://hayageek.github.io/jQuery-Upload-File/4.0.10/uploadfile.css" rel="stylesheet">
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

@media only screen and (min-width: 850px)  {
	.mar-right {
   margin-right:4em;	
  
}

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
  <? if ($oDetect->isMobile()) {?>
  $('#caption').html('<span title="<?=$oMember->getMemberName($vUserActive)?>"><?=substr($oMember->getMemberName($vUserActive),0,25);?>...</span>');
  <? } else { ?>
  $('#caption').html('<span>Detil <?=$vUserActive?> (<?=$oMember->getMemberName($vUserActive)?>)</span>');
  <? } ?>
  <?} else { ?>
  $('#caption').html('My Profile');
  <? } ?>
   $('#tfTglLahir').datepicker({
                    format: "dd-mm-yyyy"
    });  
 
 $.validator.messages.required = '<span style="color:red;font-weight:normal">This field is required!</span>';
  $('#frmReg input, #frmReg textarea,  #frmReg select, #frmReg checkbox, #frmReg radio').not([type="submit"]).not($("#tfNPWP")).not($("#tEmail")).not($("#tfSwift")).not($("#tfEmailSpon")).not($("#tfKotaBank")).not($("#tfBranchBank")).addClass('required');  
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

$("#divUpload").uploadFile({
		url:"../memstock/upload-act.php",
		multiple:false,
		dragDrop:false,
		maxFileCount:1,
		fileName:"image",
		showPreview: true,
		uploadStr: "Upload / Change",
		previewWidth: 100,
		previewHeight: 100,
		maxFileSize:102400,
		
		showDelete: true,	
		

		
		deleteCallback: function(){
			
		   document.getElementById('hPict').value=''; 	
		   
		}	,
		
		onSuccess: function(files,data,xhr,pd) {		
			var vExt=files+'';
			vExt=vExt.split('.').pop();
			document.getElementById('hPict').value='<?=$_SESSION['LoginUser']?>'+'.'+vExt;
			$('#divImg').hide();
			alert('Photo profile berhasil diupdate!');
		//	alert(document.getElementById('hPict').value);
			
		}
	});

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
   var vURL = "ter_purc_ajax.php";
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
  if (confirm('Yakin mengangkat mobile stockist untuk member '+document.getElementById('tfSerno').value+'?')==true) { 
    document.getElementById('hStock').value='1';
    document.getElementById('hOPStock').value='pro';
    document.frmReg.submit();
  }
}


function setMStock(){
  if (confirm('Yakin mengangkat stockist untuk member '+document.getElementById('tfSerno').value+'?')==true) { 
    document.getElementById('hStock').value='2';
    document.getElementById('hOPStock').value='pro';
    document.frmReg.submit();
  }
}

function setMaStock(){
  if (confirm('Yakin mengangkat master stockist untuk member '+document.getElementById('tfSerno').value+'?')==true) { 
    document.getElementById('hStock').value='3';
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

</script>
<!--	<link rel="stylesheet" href="../css/screen.css">-->

	
	
 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />

<div class="right_col" role="main">
		<div><label><h3>Member Profile</h3></label></div>
<form method="post" id="frmReg" name="frmReg" action="<?=$_SERVER['PHP_SELF']?>">
	<div class="container">
    <span style="color:black;font-weight:bold">URL Anda : <?
        $vHost='http://'.$_SERVER['HTTP_HOST'];
        echo "$vHost/?id=$vUserActive";
    ?></span>
    <div class="row" style="wmargin-top:8px">
    
     
    
    
        <div class="col-md-6">
                <div class="panel panel-default" id="panelkiri" >
                    <div class="panel-heading" style="background-color:#1D96B2">
                             <div class="panel-title">
        						<label for="exampleInputEmail1" style="font-weight:bold;display:inline">Member Data</label>
        						<div id="divStock" style="text-align:center;width:280px;background-color:maroon;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;display:<?=$vDispStock?>;border-radius:10px;color:white">&nbsp;&nbsp;&nbsp;THIS MEMBER IS MOBILE STOCKIST&nbsp;&nbsp;&nbsp;</div>
        						<div id="divMStock" style="text-align:center;width:280px;background-color:purple;color:white;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;display:<?=$vDispMStock?>;border-radius:10px">&nbsp;&nbsp;&nbsp;THIS MEMBER IS STOCKIST&nbsp;&nbsp;&nbsp;</div>
        						<div id="divInv" style="text-align:center;width:280px;background-color:navy;font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;display:<?=$vDispInv?>;border-radius:10px">&nbsp;&nbsp;&nbsp;THIS IS A WAREHOUSE&nbsp;&nbsp;&nbsp;</div>
                                 <br style="display: block;margin: -5px 0;" />                                  
                     		</div> 
                     		
                     </div>
    <div class="panel-body">  
 <div class="row">     
 
 
               
<div class="col-lg-6 hide" >
                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Country 
								   *</span></label>
                                 <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-bars"></i></span>
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
                            
<? if (false) { ?>
                     		 <b>Member Attribute</b> <select name="lmAttr" id="lmAttr" class="form-control">
				<option value="0" <? if ($vIsStockist == '0') echo'selected';?>>Member</option>
				<option value="1" <? if ($vIsStockist == '1') echo'selected';?>>MS</option>
				<option value="2" <? if ($vIsStockist == '2') echo'selected';?>>Master Stockist</option>
				<option value="3" <? if ($vIsStockist == '3') echo'selected';?>>Warehouse</option>
			</select>
			<? } ?>
                                </div>
                              </div>                     


                          
                                              
                            <div class="col-lg-6">
                            <label><span style="font-weight:bold">Username.* </span>
                              </label>
                                    <div class="input-group">
                                      <span class="input-group-addon"> <i class="fa fa-credit-card"></i></span>
                                    <input readonly="readonly" type="text" class="form-control" id="tfSerno" name="tfSerno" placeholder="Username*"  onkeyup="setUpper(this)" value="<?=$db->f('fidmember')?>" >
                                    <input type="hidden" name="hPost" id="hPost" value="1" />
                                    <input type="hidden" name="hPict" id="hPict" value="" />
                                    </div>                            
                            
                            </div>    
                            
<div class="col-lg-6 col-md-6  ">
                                    <label for="exampleInputEmail1">ID Card*</label>
                                     <div class="input-group">
                                       <span class="input-group-addon"> <i class="fa fa-user"></i></span>
                                    	<input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?>  type="text" class="form-control" id="tfIdent" name="tfIdent" placeholder="ID Card/Driving License/Passport No.*" value="<?=$db->f('fnoktp')?>">
                                      </div>
                             </div>                             
</div>    

 <div class="row">                         
                               

                            <div class=" col-lg-12 divtr">
                                <label for="exampleInputEmail1">Full Name*</label>
                                 <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?> onBlur="this.value=this.value.replace(/,/g,'');$('#tfAtasNama').val(this.value);" onKeyUp="this.value=this.value.replace(/,/g,'');$('#tfAtasNama').val(this.value);" type="text" class="form-control" id="tfNama" name="tfNama" placeholder="(Tanpa tanda koma) Full Name*" value="<?=$db->f('fnama')?>" >
                                </div>
                            </div>
</div>                            
                               

 <div class="row">                                 
                               <div class="col-lg-8 col-md-8 col-xs-8 divtr " >
                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Place of Birth </span></label>
                                 <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="tfTempat" name="tfTempat" value="<?=$db->f('ftempatlahir')?>" placeholder="Place of Birth*">
                                </div>
                              </div>
                                                            
                                  <div class="col-lg-4 col-md-6 col-xs-8 divtr" >
                                     
                                     <label for="exampleInputEmail1" ><span style="font-weight:bold">Date of Birth </span></label>

                                     <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                                        <input id="tfTglLahir" name="tfTglLahir" value="<?=$oPhpdate->YMD2DMY($db->f('ftgllahir'))?>" class="form-control default-date-picker"   type="text" placeholder="DD-MM-YYYY">
                                        </div>

                                  </div>
 </div>                                
                           
                   
                               <div class="col-lg-7 col-md-7 divtr" style="display:none">
                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Nationality*</span></label>
                                 <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
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
<div class="row">                                                                
                                  <div class="col-md-5 col-xs-6 col-lg-12 divtr" >
                                     <label for="exampleInputEmail1"><span style="font-weight:bold">Sex*</span></label>
                                     <div class="input-group">
                                   <span class="input-group-addon"> <i class="fa fa-male"></i></span>

                                        
                            <select class="form-control m-bot15" id="tfSex" name="tfSex">
                                <option value="" selected="selected">---Pilih / Choose---</option>
                                <option value="F" <? if ($db->f('fsex')=='F') echo ' selected';?>>Perempuan / Female</option>
                                <option value="M" <? if ($db->f('fsex')=='M') echo ' selected';?>>Laki-laki / Male</option>
                                <option value="O" <? if ($db->f('fsex')=='O') echo ' selected';?>>Lainnya / Other </option>
                            </select>
                           </div>

                          </div>
                                
   </div>
    <div class="row">               
                            
                        <div class="col-lg-12 divtr">
                            <label for="exampleInputEmail1">Full Address (with postal code)*</label>
                             <div class="input-group">
                               <span class="input-group-addon"> <i class="fa fa-bars"></i></span>
                                <textarea id="taAlamat" name="taAlamat" class="form-control custom-control" rows="3" style="resize:none"><?=$db->f('falamat')?></textarea>
                          <!--  <input type="text" class="form-control" id="tfNama" placeholder="Full Address along with Postal Code*"> -->
                            </div>
                        </div>
</div>
  					 <div class="row" >                      
                               
   							
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
   
  
 					 <div class="row"  id="phonehp">                      
                               <div class="col-lg-6 col-md-6 divtr hide" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">Phone No*</span></label>
                                 <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                <input value="<?=$db->f('fnohp')?>" type="text" class="form-control" id="tfPhone" name="tfPhone" placeholder="Phone Number*">
                                </div>
                              </div>
                                                                
   							                    
                               <div class="col-lg-6 col-md-6 divtr" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">&nbsp;Handphone*</span></label>
                                 <div class="input-group">
                                   <span class="input-group-addon"> <i class="fa fa-mobile"></i></span>
                                <input value="<?=$db->f('fnohp')?>" type="text" class="form-control" id="tfHP" name="tfHP" placeholder="Cellular Phone*">
                                </div>
                              </div>
						
    					 <div class="col-lg-6" >
                                <label for="exampleInputEmail1"><div class="divtr"></div>Alamat Email / Email Address*</label>
                                 <div class="input-group">
                                   <span class="input-group-addon"> <i class="fa fa-envelope"></i></span>
                                <input value="<?=$db->f('femail')?>" type="text" class="form-control" id="tfEmail" name="tfEmail" placeholder="Email Address*">
                                </div>
                         </div>
                               
						</div>
	
<!-- ========================= -->


	<div class="col-lg-8 col-md-8 col-sm-8 divtr " style="margin-left:-1.3em">
							                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Registration&nbsp;Package</span></label>
							                             	<div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6   " >
                                                            <label style="color:blue">
                                                        <?
                  										     echo $oProduct->getPackName($db->f('fpaket'));			
														?>
                                                        </label>
                                                           </div>
                                                               </div>
</div>
<? if (false) if ($db->f('ftglupgrade') != '1981-01-01 00:00:00') { ?>
<div class="col-lg-8 col-md-8 col-sm-8 divtr " >
							                                <label for="exampleInputEmail1" style="color:blue"><span style="font-weight:bold">Member Upgrade : Yes [<?=$oPhpdate->YMD2DMY($db->f('ftglupgrade'))?>]</span></label>						                              
</div>
<? } ?>

							                               
<!-- ========================= -->

<div class="col-lg-5 col-md-3 col-sm-4 divtr hide" >
			<label for="tfAutoShip" ><span style="font-weight:bold">Autoship Item(s)*</span></label>		                               
			<select  class="form-control m-bot15" id="tfAutoShip" name="tfAutoShip"  >
                                <option  value="" >--Choose--</option>
								<? 
								    $vSQL="select * from m_product where faktif='1' order by fidsys";
								    $dbin->query($vSQL);
								    while ($dbin->next_record()) {
								?>                               
								 <option  value="<?=$dbin->f('fidproduk')?>" <? if ($vAutoShipList == $dbin->f('fidproduk')) echo 'selected';?>  ><?=$dbin->f('fnamaproduk')?></option>
								 <? } ?>
                            </select>
                            </div>



			<div class="row" style="margin-top:2em">
      <div class="col-lg-12 col-md-6 divtr" >
                                <label for="exampleInputEmail1" ><span style="font-weight:bold">Password</span></label>
                                 <div class="input-group">

                                    <?
                                       $vPass=$oSystem->doED('decrypt',$db->f('fpassword'));
                                    ?>
                                <button type="button" class="btn btn-success" onClick="alert('Password :<?=$vPass?>')"> <i class="fa fa-key"></i> Show Password</button>
                                <input value="<?=$oSystem->doED('decrypt',$db->f('fpassword'))?>" type="hidden" id="tfPass" name="tfPass" >
                                </div>
                              </div>
       </div>
                         </div> <!--Panel Body-->
                         
                         
                         
                         
                </div> <!--Col-md-6 kiri-->
				     
        </div>
        <div class="col-md-6" id="kolomkanan" >
                    <div class=" mar-right panel panel-default " id="panelkanan" >
                    <div class="panel-heading" style="background-color:#1D96B2">
                             <div class="panel-title">
        						<label for="exampleInputEmail1" style="font-weight:bold;">
								 Member Bank Account</label>
                               <br style="display: block;margin: -5px 0;" />                                  
                     		</div>
                     </div>
                     <div class="panel-body">

 <div class="row"  >   
    
                                                 
                         <div class="col-lg-6 col-md-6 divtr">
                         <img id="loadBank"  align="absmiddle" src="../images/ajax-loader.gif" style="position:absolute;z-index:2;margin-left:45px;margin-top:24px;opacity: 0.5;display:none" />
                             <label for="exampleInputEmail1"><b>Bank Name*</b></label>
                                   <div class="input-group">
                                  <span class="input-group-addon"> <i class="fa fa-building-o"></i> </span>
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

                         <div class="col-lg-6 divtr">
                             <label for="exampleInputEmail1">Bank Account Number.*</label>
                                   <div class="input-group">
                                   <span class="input-group-addon"><i class="fa fa-building-o"></i></span>
                                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?>  value="<?=$db->f('fnorekening')?>" type="text" class="form-control" id="tfRek" name="tfRek" placeholder="Bank Account No*" >
                                   </div>
                         </div>     
                         
 </div> 
 <div class="row">                           
          				<div class="col-lg-6 divtr">
                                    <label for="exampleInputEmail1">Name in Account*</label>
                                     <div class="input-group">
                                       <span class="input-group-addon"> <i class="fa fa-user"></i></span>
                                    	<input  <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?> value="<?=$db->f('fatasnama')?>" type="text" class="form-control" id="tfAtasNama" name="tfAtasNama" placeholder="(Tanpa tanda koma) Bank Account Name (Same With The Distributor's Name)*" onkeyup="this.value=this.value.replace(/,/g,'');$('#tfAtasNama').val(this.value);" onblur="this.value=this.value.replace(/,/g,'');$('#tfAtasNama').val(this.value);">
                                      </div>
                             </div>  

			 <div   id="kotacabnegbank">                      
                               <div class="col-lg-6 col-md-6 divtr" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">Bank City*</span></label>
                                 <div class="input-group">
                                   <span class="input-group-addon"> <i class="fa fa-bars"></i></span>
                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?> value="<?=$db->f('fkotabank')?>" type="text" class="form-control" id="tfKotaBank" name="tfKotaBank" placeholder="Bank City*">
                                </div>
                              </div>
                        </div>
</div>      
                                                                
   <div class="row"> 							                    
                               <div class="col-lg-12 col-md-6 divtr" >
                                   <label for="exampleInputEmail1" >
								   <span style="font-weight:bold">
								   Bank Branch*</span></label>
                                 <div class="input-group">
                                  <span class="input-group-addon">  <i class="fa fa-bars"></i></span>
                                <input <? if (!$oSystem->checkPriv($_SESSION['LoginUser'],"mdm_setting_bank")) echo 'readonly';?> value="<?=$db->f('fcabbank')?>" type="text" class="form-control" id="tfBranchBank" name="tfBranchBank" placeholder="Bank State/Branch*">
                                </div>
                              </div>

   							
                               
						
             				<div class="col-lg-6 col-md-6 divtr ">
                                    <label for="exampleInputEmail1"><div class="divtr"></div>Bank Swift Code (outside Indonesia only)</label>
                                     <div class="input-group">
                                       <span class="input-group-addon"> <i class="fa fa-user"></i></span>
                                    	<input value="<?=$db->f('fswift')?>" type="text" class="form-control" id="tfSwift" name="tfSwift" placeholder="Outside Indonesia Only*">
                                      </div>
                             </div>  
</div>
             	   <div class="row"> 	
                			<div class="col-lg-12 col-md-6 divtr">
                                    <label for="exampleInputEmail1"><div class="divtr"></div>
									Tax Card Number</label>
/ NPWP                                    
<div class="input-group">
                                      <span class="input-group-addon">  <i class="fa fa-user"></i></span>
                                    	<input  value="<?=$db->f('fnpwp')?>" type="text" class="form-control" id="tfNPWP" name="tfNPWP" placeholder="Taxpayer Registration Number*">
                                      </div>
                             </div> 


<div class="col-lg-6 col-md-6 divtr hide">
													<label for="exampleInputEmail1">Upline Username* <div align="left" style="display:inline" id="statKitSpon"></div></label>
					                 <div class="input-group">
                                      <span class="input-group-addon">  <i class="fa fa-user"></i></span>
					                                    <input readonly="readonly" value="<?=$vUpline=$oNetwork->getUpline($db->f('fidmember'))?>" type="text" class="form-control" id="tfSernoSpon" name="tfSernoSpon" placeholder="Upline Card/Starter Kit No*" onBlur="checkKitSpon(this)" onKeyUp="setUpper(this)">
					                                    </div>                            
					                            
					                            </div>    					                            

					                            <div class="col-lg-6 col-md-6 divtr hide"> <label for="exampleInputEmail1">Upline Name*</label>
					                                 <div class="input-group">
					                                    <span class="input-group-addon">  <i class="fa fa-user"></i></span>
					                                <input readonly="readonly" value="<?=$oMember->getMemberName($vUpline)?>" type="text" class="form-control" id="tfSponsor" name="tfSponsor" placeholder="Upline Name*">
					                                </div>
					                            </div>


												                      
							                               <div class="col-lg-6 col-md-6 divtr hide" >
							                                   <label for="exampleInputEmail1" >
															   <span style="font-weight:bold">
															   Upline's Phone 
															   Number*</span></label>
							                                 <div class="input-group">
							                                   <span class="input-group-addon">  <i class="fa fa-phone"></i></span>
							                                <input readonly="" value="<?=$oMember->getMemField('fnohp',$vUpline)?>" type="text" class="form-control" id="tfPhoneSpon" name="tfPhoneSpon" placeholder="Upline Phone Number*">
							                                </div>
							                              </div>
							                                                                
							   							                    
							                               <div class="col-lg-6 col-md-6 divtr hide" >
							                                   <label for="exampleInputEmail1" >
															   <span style="font-weight:bold">
															   Upline's Email 
															   Address*</span></label>
							                                 <div class="input-group">
							                                    <span class="input-group-addon">  <i class="fa fa-envelope"></i></span>
							                                <input readonly="" value="<?=$oMember->getMemField('femail',$vUpline)?>" type="text" class="form-control" id="tfEmailSpon" name="tfEmailSpon" placeholder="Upline's Email Address*">
							                                </div>
							                              </div>



  								<div class="col-lg-6 col-md-6 divtr">
													<label for="exampleInputEmail1">Sponsor Username* <div align="left" style="display:inline" id="statKitSpon"></div></label>
					                 <div class="input-group">
                                      <span class="input-group-addon">  <i class="fa fa-user"></i></span>
					                                    <input readonly="readonly" value="<?=$db->f('fidressponsor')?>" type="text" class="form-control" id="tfSernoSpon" name="tfSernoSpon" placeholder="Sponsor Card/Starter Kit No*" onBlur="checkKitSpon(this)" onKeyUp="setUpper(this)">
					                                    </div>                            
					                            
					                            </div>    					                            

					                            <div class="col-lg-6 col-md-6 divtr"> <label for="exampleInputEmail1">Sponsor Name*</label>
					                                 <div class="input-group">
					                                    <span class="input-group-addon">  <i class="fa fa-user"></i></span>
					                                <input readonly="readonly" value="<?=$oMember->getMemberName($db->f('fidressponsor'))?>" type="text" class="form-control" id="tfSponsor" name="tfSponsor" placeholder="Sponsor Name*">
					                                </div>
					                            </div>


												                      
							                               <div class="col-lg-6 col-md-6 divtr" >
							                                   <label for="exampleInputEmail1" >
															   <span style="font-weight:bold">
															   Sponsor's Phone 
															   Number*</span></label>
							                                 <div class="input-group">
							                                   <span class="input-group-addon">  <i class="fa fa-phone"></i></span>
							                                <input readonly="" value="<?=$oMember->getMemField('fnohp',$db->f('fidressponsor'))?>" type="text" class="form-control" id="tfPhoneSpon" name="tfPhoneSpon" placeholder="Sponsor Phone Number*">
							                                </div>
							                              </div>
							                                                                
							   							                    
							                               <div class="col-lg-6 col-md-6 divtr" >
							                                   <label for="exampleInputEmail1" >
															   <span style="font-weight:bold">
															   Sponsor's Email 
															   Address*</span></label>
							                                 <div class="input-group">
							                                    <span class="input-group-addon">  <i class="fa fa-envelope"></i></span>
							                                <input readonly="" value="<?=$oMember->getMemField('femail',$db->f('fidressponsor'))?>" type="text" class="form-control" id="tfEmailSpon" name="tfEmailSpon" placeholder="Sponsor's Email Address*">
							                                </div>
							                              </div>

													
							                               <div class="col-lg-6 col-md-6 divtr" >
							                                   <label for="exampleInputEmail1" >
															   <span style="font-weight:bold">
															   Profile Picture</span></label>
							                                 
							                              <div id="divUpload">Upload</div>
                                                          
                                                          <? if ($vImage !='') {?>
                                                          <div id="divImg"><img src='<?="../images/user/$vImage";?>' width="100" height="120" /></div>
                                                          <? } ?>
                                                          
                                                          </div>

                             
                             
                        </div>     
</div> <!--form-group -->
                            
                            </div>  




                     </div> <!--Panel Body -->
                     
                     							<!-- <div class="divtr">
                            <!-- Panel Sponsor -->

			                    <div class="panel panel-default hide" id="panelkanan">
					                    <div class="panel-heading" >
					                             <div class="panel-title" style="margin-top:-10px">
					        						 <label for="exampleInputEmail1" style="font-weight:bold;">Sponsor 
													 Data</label>
					                               <br style="display: block;margin: -5px 0;" />                                    
					                     		</div>
					                     </div>
					                     <div class="panel-body">
					                            
					                            
					                            
					                          


					                     </div>
			                     </div>


                </div> <!--Kolom Kanan -->
        </div>
        
 <div class="row">
                             <div class="col-lg-6 " style="margin-left:1.3em">

                             
  								<div class="input-group" style="display:inline">
                                    
                                    <button type="submit" class="btn  btn-primary"  ><i class="fa fa-floppy-o"></i> Save</button>
                                </div>
                                
								<? if ($vPriv=='administrator') {?>

								<? if ($oMember->isStockist($vUserActive)==0 ) { ?>
                               
								 <div class="input-group" style="display:inline">
                                    
                                        <button  id="btnSubmit" type="button" class="btn btn-success hide"  onclick="setStock();" ><i class="fa fa-car" style="display:inline"></i> Promote Mobile Stockist</button>
                                </div>        
                                <? } ?>
                                
								<? if ($oMember->isStockist($vUserActive)==1) { ?>
                               
								 <div class="input-group" style="display:inline">
                                    
                                        <button  id="btnSubmit" type="button" class="btn btn-success "  onclick="setMStock();" ><i class="fa fa-building-o" style="display:inline"></i> Promote Stockist</button>
                                </div>        
                                <? } ?>

<? if ($oMember->isStockist($vUserActive)==1 || $oMember->isStockist($vUserActive)==2) { ?>
                               
								 <div class="input-group" style="display:inline">
                                    
                                        <button  id="btnSubmit" type="button" class="btn btn-success "  onclick="setMaStock();" ><i class="fa fa-building-o" style="display:inline"></i> Promote Master Stockist</button>
                                </div>        
                                <? } ?>
                                
                                <? if ($oMember->isStockist($vUserActive)==1 || $oMember->isStockist($vUserActive)==2 || $oMember->isStockist($vUserActive)==3) { ?>

                                <div class="input-group" style="display:inline">
                                    
                                        <button  id="btnSubmit" type="button" class="btn btn-danger"  onclick="unsetStock();" ><i class="fa fa-times" style="display:inline"></i> Demote Stockist</button>
                                </div> 
                                <? } ?>       
                               
                                <? } ?>

                                
                                
                                
                                 <div id="divLoad" style="display:inline"></div>

                                      


                                        <input type="hidden" name="hStock" id="hStock" value="<?=$db->f('fstockist')?>">
                                        <input type="hidden" name="hStockOld" id="hStockOld" value="<?=$db->f('fstockist')?>">
                                        <input type="hidden" name="hOPStock" id="hOPStock" value="no">

                            </div>

 </div>            
   
<hr /><br />
        
   
        
                                                  
 </form>                               
        <!-- page end-->
  


<!-- Placed js at the end of the document so the pages load faster -->


<script src="https://hayageek.github.io/jQuery-Upload-File/4.0.10/jquery.uploadfile.min.js"></script>

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
  <!-- /.content-wrapper -->
 <? include_once("../framework/member_theme.blade.php")?>	      
        <!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	


<? include_once("../framework/member_footside.blade.php") ; ?>