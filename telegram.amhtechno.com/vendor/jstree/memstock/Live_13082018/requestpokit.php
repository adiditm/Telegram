<? 
		if ($_GET['op'] != '') 
           include_once("../framework/admin_headside.blade.php");
        else
           include_once("../framework/member_headside.blade.php") ;  
		   
		   include_once("../classes/systemclass.php") ;
		   include_once("../classes/ruleconfigclass.php") ;  
?>
<?
//echo "SAVESTOCK";  
 //print_r($_SESSION['savestock']);
  // echo "POST<br>";
  // print_r($_POST);
   while (list($key,$val)=each($_POST)) {
      $$key = $val;
   }
 
   $vHrgEco = $oRules->getSettingByField('fhrgeco');
   $vHrgBus = $oRules->getSettingByField('fhrgbus');
   $vHrgFirst = $oRules->getSettingByField('fhrgfirst');
   $vMinBelanja = $oRules->getSettingByField('fminregstockist');
 //  $vStockistBal = $oMember->getMemField('fsaldoro',$vUser);
  $vStockistBal = 99999999999999;
       
   if ($_POST['hPost'] != '1') {
      $_SESSION['savestock']='';
      $_SESSION['save']='';

      $_SESSION['del']='';
    
   } else {
    $vNextJual=$oJual->getNextIDKIT();
    $vBuyer=$_POST['tfSernoSpon'];
    $vAlamat=$oMember->getMemField('falamat',$vBuyer);
	$vUserHO=$oRules->getSettingByField('fuserho');
	$vJmlKitEx = $_POST['tfUKitE'];
	$vJmlKitEc = $_POST['tfUKitB'];
	$vJmlKitEt = $_POST['tfUKitF'];
    $oSystem->smtpmailer("a_didit_m@yahoo.com","Entri PO Unig","Debugger Unig","Reuest PO KIT",print_r($_POST,true)."\n\n\n".print_r($_SESSION['savestock'],true));
	//mail("a_didit_m@yahoo.com","Entri PO Uneeds",print_r($_POST,true)."\n\n\n".print_r($_SESSION['savestock'],true));
    $db->query('START TRANSACTION;');
  $vHrgKit = $oRules->getSettingByField('fhrgkit');    
   //Purchase
	if ($_POST['tfUKitE'] > 0) {        
        $vSubTot = $vHrgKit * $vJmlKitEx;    		
       	$vSQL="insert into tb_trxkit(fidpenjualan,fidseller,  fidmember, falamatkrm, fnostockist, fserno, fjumlah, ftanggal, fhargasat, fsubtotal,  fjenis,  fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";
    	$vSQL.=" values('$vNextJual','$vUserHO','$vBuyer','$vAlamat','$vUser','',$vJmlKitEx,now(),$vHrgKit,$vSubTot,'S','mTrans','KIT Order',now(),'0','0000-00-00 00:00:00','kitorder')";
  	 	$db->query($vSQL);
    }


	if ($_POST['tfUKitB'] > 0) {        
        $vSubTot = $vHrgKit * $vJmlKitEc;    		
       	$vSQL="insert into tb_trxkit(fidpenjualan,fidseller,  fidmember, falamatkrm, fnostockist, fserno, fjumlah, ftanggal, fhargasat, fsubtotal,  fjenis,  fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";
    	$vSQL.=" values('$vNextJual','$vUserHO','$vBuyer','$vAlamat','$vUser','',$vJmlKitEc,now(),$vHrgKit,$vSubTot,'G','mTrans','KIT Order',now(),'0','0000-00-00 00:00:00','kitorder')";
  	 	$db->query($vSQL);
    }	

	if ($_POST['tfUKitF'] > 0) {        
        $vSubTot = $vHrgKit * $vJmlKitEt;    		
       	$vSQL="insert into tb_trxkit(fidpenjualan,fidseller,  fidmember, falamatkrm, fnostockist, fserno, fjumlah, ftanggal, fhargasat, fsubtotal,  fjenis,  fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";
    	$vSQL.=" values('$vNextJual','$vUserHO','$vBuyer','$vAlamat','$vUser','',$vJmlKitEt,now(),$vHrgKit,$vSubTot,'P','mTrans','KIT Order',now(),'0','0000-00-00 00:00:00','kitorder')";
  	 	$db->query($vSQL);
    }			
    $db->query('COMMIT;');
     $oSystem->jsAlert("Request PO Serial Stockist Sukses dengan ID Transaksi $vNextJual, mohon tunggu approval dari admin!");

     //$oSystem->jsLocation("../manager/requestpo.php");
   }   
 
//   echo $tfNama;
?>

<body class="sticky-header">
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


	</style>
<script src="../js/jquery.validate.min.js"></script>

<script language="javascript">

function validBuy() {
//alert($('#hTot').val());
   // $('#hTot').val() =0; 
	if(typeof $('#hTot').val() !== "undefined") {
	/*
	//alert(parseFloat($('#hTot').val()) + parseFloat($('#hTotKit').val()) +  parseFloat($('#hTotMand').val()));
       if ((parseFloat($('#hTot').val()) + parseFloat($('#hTotKit').val()) +  parseFloat($('#hTotMand').val())) < parseFloat($('#hMinBuy').val())) {
         alert('Belanja  kurang dari syarat minimum Stockist ('+$('#hMinBuy').val()+'), silakan tambahkan belanja!');
         return false;
       } else return true;
       */
       return true;

	} else { 
	   alert('Anda belum melakukan pembelanjaan!');
	   return false;
	} 
}

	$.validator.setDefaults({
	    
		submitHandler: function() {
		   
 /*var vPaket=document.getElementById('rbPaket').value;
		    vPaket = vPaket.split(';');
		    vPaket=vPaket[1];
		    alert(vPaket);
		    return false; */
		    if (confirm('Anda yakin melakukan Purchase Order Serial untuk Stockist?')==true) {
				var vValid= validBuy();
							
 				if (vValid)
 				   document.frmReg.submit();
				
			} else return false;
			
			
		}
	});
	
	
function reValidate() {
   $('#frmReg input, #frmReg textarea,  #frmReg select, #frmReg checkbox, #frmReg radio').not([type="submit"]).not($("#tfNPWP")).not($("#tEmail")).not($("#tfSwift")).addClass('required');  
   

		$("#frmReg").validate({
			rules: {
				tfTempat: "required",
				tfNama: { 
				    required : false,
				      
				},
				tfIdent: {
					required: true,
					minlength: 9
				},
				tfEmail: {
					required: false,
					email: true
				},
				
				tfRek :{
				    required : true,
				},
				
			
				
				
				
			},
			messages: {
			   // tfIdent: '<span style="color:red;font-weight:normal">This field is required with minimum 9 character length!</span>',
			   // tfRek : '<span style="color:red;font-weight:normal">This field is required with minimum 10 character length!</span>',
			},
			
			 errorPlacement: function(error,element){ 
                            error.insertAfter(element); 
                          //  alert(error.html()); 
                       },
	               showErrors: function(errorMap, errorList){ 
                              this.defaultShowErrors();
                       }
		});  
   
}	
$(document).ready(function(){
 //  alert('ssss');
  // alert($('#hHarga').val());
   $('#caption').html('Request Purchase Order');
   $('#tfTglLahir').datepicker({
                    format: "dd-mm-yyyy"
    });  
 // $.validator.messages.required = '<span style="color:red;font-weight:normal">This field is required!</span>';
  $('#frmReg input, #frmReg textarea,  #frmReg select, #frmReg checkbox, #frmReg radio').not([type="submit"]).not($("#tfNPWP")).not($("#tEmail")).not($("#tfSwift")).addClass('required');  
   
 
    
  $('#lmCountry').val('ID');
  $('#lmCountry').trigger('change');
  

		$("#frmReg").validate({
			rules: {
				tfTempat: "required",
				tfNama: { 
				    required : false,
				      
				},
				tfIdent: {
					required: true,
					minlength: 9
				},
				tfEmail: {
					required: false,
					email: true
				},
				
				tfRek :{
				    required : true,
				},
				
			
				
				
				
			},
			messages: {
			   // tfIdent: '<span style="color:red;font-weight:normal">This field is required with minimum 9 character length!</span>',
			   // tfRek : '<span style="color:red;font-weight:normal">This field is required with minimum 10 character length!</span>',
			},
			
			 errorPlacement: function(error,element){ 
                            error.insertAfter(element); 
                          //  alert(error.html()); 
                       },
	               showErrors: function(errorMap, errorList){ 
                              this.defaultShowErrors();
                       }
		});  
 $('#tfSernoSpon').trigger('blur');

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
   var vURL = "../memstock/register_purcstok_ajax.php";
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
      getAllPurc();
   });
   
}
 

function doDel(pNo, pKode,pSize,pColor,pNama,pJml,pHarga,pSubTot) {
//alert(pNo +':'+ pKode+':'+pSize+':'+pColor+':'+pNama+':'+pJml+':'+pHarga+':'+pSubTot);  
 var vURL = "register_purcstok_ajax.php";
   $('#tdLoad').html('<img src="../images/ajax-loader.gif" />');
  
 $.post(vURL,{ delNo : pNo, delKode: pKode, delSize: pSize, delColor : pColor, delNama : pNama, delJml : pJml, delHarga : pHarga, delSubTot : pSubTot, op : 'del' }, function(data) {
      $('#tbPurc').html(data);
      $('#tdLoad').empty();
   });
}
  
 



function checkKitStockist(pParam) {
   if (pParam.value=='')
      return false;
   else {    
   var vCountry=$('#lmCountry').val();
   var vURL="../main/mpurpose_ajax.php?op=kitstock";
   var vYes=/yesx/g;
   var vNo=/nox/g;
   var vNamaS='';
   var vNama='';
   var vStockist='';
   $('#loadNama').show();
   $('#statKitSpon').html('&nbsp;<img src="../images/ajax-loader-bar.gif" />');
   $.post(vURL, {sernospon : pParam.value},function(data) {

		   vNamaS=data.split('|');
		   vNama=vNamaS[1];
		   vPhone=vNamaS[2];
		   vEmail=vNamaS[3];
		   vAlamat=vNamaS[4];
           vStockist=vNamaS[5];
   //alert(vStockist);
      if (parseInt(vStockist)==0) {
         $('#statKitSpon').html('<font color="#f00">'+pParam.value+' : Member Tidak Valid, belum menjadi stockist!</font>');
         document.getElementById('btnSubmit').disabled=true;

     } else if (vNamaS[0]=='nox') {
         $('#statKitSpon').html('<font color="#f00">'+pParam.value+' : Member Tidak Valid!</font>');
         document.getElementById('btnSubmit').disabled=true;

     } else if (vNamaS[0]=='yesx') {

         $('#statKitSpon').html('<font color="#00f">'+pParam.value+' : Member valid (Stockist / Mobile Stockist)!</font>');
         $('#tfSponsor').val(vNama);
         $('#tfPhoneSpon').val(vPhone);
         $('#tfEmailSpon').val(vEmail);
         $('#tfAlamat').val(vAlamat);

      //  alert(vPhone+':'+vEmail);
      


         document.getElementById('btnSubmit').disabled=false;     
     }    
   $('#loadNama').hide();  
   });   

  }
}

function setUpper(pParam) {
   document.getElementById(pParam.name).value=document.getElementById(pParam.name).value.toUpperCase();
}
function submitForm() {
   //document.frmReg.submit();
   ;

}


function calcKit(pHrg,pQty) {
   
   var vQtyE=$('#tfUKitE').val();
   var vQtyB=$('#tfUKitB').val();
   var vQtyF=$('#tfUKitF').val();
      var vTot=parseFloat(pHrg) * (parseFloat(vQtyE) + parseFloat(vQtyB) + parseFloat(vQtyF));
   
   
   
   $('#sTotKit').html(vTot);
   $('#hTotKit').val(vTot);
   

      $('#sTotKit').priceFormat({     
                    prefix: ' ',
                    centsSeparator: ',',
                    thousandsSeparator: '.',
                    limit: 15,
                    centsLimit: 0
                });
 /*  $('#divKIT').html('<img   align="absmiddle" src="../images/ajax-loader.gif"/>');
   var vURL='kit_column_ajax.php?eco='+vQtyE+'&bus='+vQtyB+'&x1st='+vQtyF;
     $.get(vURL, function(data) {
      $('#divKIT').html(data);
      reValidate();
   });   
   
   */
                
   
}

function getAllPurc() {
   var vTot=0;
  
   if (document.getElementById('hTot')) {
      if (parseFloat($('#hTot').val()) > 0)
        // vTot= $('#hTot').val();
		 vTot= 0;
    }  

      
   var vAllPurc= parseFloat(vTot) + parseFloat($('#hTotKit').val());    
   $('#totBelanja').html(vAllPurc);
   if (parseFloat('<?=$vStockistBal?>') < parseFloat(vAllPurc)) {
	   alert('Saldo jaminan stockist (<?=number_format($vStockistBal,0)?>) tidak mencukupi!');
       document.getElementById('btnSubmit').disabled=true;	   
   } else document.getElementById('btnSubmit').disabled=false;	   
   
   $('#totBelanja').priceFormat({     
                    prefix: ' ',
                    centsSeparator: ',',
                    thousandsSeparator: '.',
                    limit: 15,
                    centsLimit: 0
   });
   
   
   return vAllPurc;
}

function checkValidKit(pParam) {
  var vKIT= CryptoJS.MD5(pParam.value);  
  var vURL='../main/mpurpose_ajax.php?op=cvalidkit&kit='+vKIT+'&jen='+pParam.lang;      
  
   $.get(vURL, function(data) {
      if (data.trim()=='no' && pParam.value !='') {
      	alert(pParam.value+': Not valid KIT Number!');
      	pParam.style.backgroundColor = "red";       
      	pParam.style.color = "white";
      } else if (pParam.value !=''){ 
        pParam.style.backgroundColor = "green";       
        pParam.style.color = "white";

      } else {
        pParam.style.backgroundColor = "white";       
        pParam.style.color = "grey";
      
      }
   });   

  findDuplicates();
}


function findDuplicates() {
    var isDuplicate = false;
    jQuery("input[id^='KIT_']").each(function (i,el1) {
        var current_val = jQuery(el1).val();
        if (current_val != "") {
            jQuery("input[id^='KIT_']").each(function (i,el2) {
                if (jQuery(el2).val() == current_val && jQuery(el1).attr("id") != jQuery(el2).attr("id")) {
                    isDuplicate = true;
                    jQuery(el2).css("background-color", "yellow");
                    jQuery(el1).css("background-color", "yellow");
                    jQuery(el2).css("color", "black");
                    jQuery(el1).css("color", "black");
                    return;
                }
            });
        }
    });

    if (isDuplicate) {
        alert ("Duplicate values found.");
        return false;
    } else {
        return true;
    }
}

 </script>
	

	
	
 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />

<?
 $vSQL="select fidcolor,fcolor from m_color where faktif='1' order by fcolor";
  $db->query($vSQL);
  $i=0;
  while($db->next_record()) {
      $vCode=$db->f('fidcolor');
      $vColor=$db->f('fcolor');
      $i++;
?>
  <input type="hidden" name="hArrColor<?=$i?>" id="<?=$vCode?>" value="<?=$vColor?>" >
  <input type="hidden" name="hTot" id="hTot" value="" />

<? } ?>
<div id="content" class="content">
			<ol class="breadcrumb pull-right">
				<h4>
				  <li><a href="javascript:;">Request PO for KIT</a></li></h4>
				
			</ol>
<h1 class="page-header">&nbsp;</small></h1>
        <!-- page start-->

<form method="post" id="frmReg" name="frmReg" action="<?=$_SERVER['PHP_SELF']?>">
	<div class="container">
    <div class="row" style="width:98%;margin-top:8px">
    
     
    
    
        <div class="col-md-12">
               				<!--Panel Body -->
                     
                     							<!-- <div class="divtr">
                            <!-- Panel Sponsor -->

			                    <div class="panel panel-default" id="panelkanan">
									<div class="panel-heading" >
										<div class="panel-title" style="margin-top:-10px">
											<label for="exampleInputEmail1" style="font-weight:bold;">
											Data Member Stockist </label>
											<br style="display: block;margin: -5px 0;" />
											
										</div>
									</div>
									<div class="panel-body">
										<div class="">
											<label for="exampleInputEmail1">ID 
											Member* 
											<div align="left" style="display:inline" id="statKitSpon">
											</div>
											</label>
											<div class="iconic-input">
												<i class="fa fa-credit-card">
												</i>
												<input type="text" class="form-control" id="tfSernoSpon" name="tfSernoSpon" placeholder="Member Card/Starter Kit No*" onBlur="checkKitStockist(this)" onKeyUp="setUpper(this)" value="<?=$vUser?>">
											</div>
										</div>
										<div class="divtr">
											<img id="loadNama"  align="absmiddle" src="../images/ajax-loader.gif" style="position:absolute;z-index:2;margin-left:45px;margin-top:24px;opacity: 0.5;display:none" />
											<label for="exampleInputEmail1">Nama 
											Member*</label>
											<div class="iconic-input">
												<i class="fa fa-user"></i>
												<input readonly type="text" class="form-control" id="tfSponsor" name="tfSponsor" placeholder="Member Name*">
											</div>
										</div>
										<div class="form-group" style="margin-left:-15px" id="phonemailspon">
											<div class="col-lg-6 col-md-6 divtr" >
												<label for="exampleInputEmail1" >
												<span style="font-weight:bold">No Telepon 
												Member*</span></label>
												<div class="iconic-input">
													<i class="fa fa-phone"></i>
													<input readonly type="text" class="form-control" id="tfPhoneSpon" name="tfPhoneSpon" placeholder="Member Phone Number*">
												</div>
											</div>
											<div class="col-lg-6 col-md-6 divtr" >
												<label for="exampleInputEmail1" >
												<span style="font-weight:bold">Alamat Email 
												Member</span></label>
												<div class="iconic-input">
													<i class="fa fa-envelope">
													</i>
													<input readonly type="email" class="form-control" id="tfEmailSpon" name="tfEmailSpon" placeholder="Member's Email Address">
												</div>
											</div>
											
											<div class="col-lg-6 col-md-6 divtr" >
												<label for="exampleInputEmail1" >
												<span style="font-weight:bold">Alamat Surat
												</span></label>
												<div class="iconic-input" >
													<i class="fa fa-envelope">
													</i>
													<textarea  style="padding-left:30px" readonly class="form-control" id="tfAlamat" name="tfAlamat" placeholder="Mailing Address"></textarea>
												</div>
											</div>

											
										</div>
										</div>
				</div>
				     
        </div>
		<!--Kolom Kanan -->
        </div>
    </div>
<hr /><br />





        
<div class="panel panel-default hide" id="panelkanan">
					                    <div class="panel-heading" >
					                             <div class="panel-title" style="margin-top:-10px">
					        						<label for="exampleInputEmail1" style="font-weight:bold;">Pembelanjaan Produk </label>
					        						<input type="hidden" name="hMinBuy" id="hMinBuy" value="<?=$vMinBelanja?>">
					                               <br style="display: block;margin: -5px 0;" /><label for="exampleInputEmail1" style="font-size:12px;">Product Purchase</label>                                    
					                     		</div>
					                     </div>
					                     <div class="panel-body">

<div class="table-responsive hide" id="tbPurc" >
<table class="table" style="table-layout:fixed;">
                            <thead>
                            <tr>
                                <th width="3%">#</th>
                                <th width="15%">Kd. Produk</th>
                                <th width="25%">Nama Produk</th>
                                <th width="9%">Ukuran</th>
                                <th width="9%">Warna</th>
                                <th width="9%">Jumlah</th>
                                <th width="11%">Hrg. Satuan</th>
                                <th width="12%">Sub Total</th>
                                <th width="12%">&radic;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="trAdd" style="display:">
                                <td style="width: 33px; height: 30px;"></th>
                                <td style="width: 208px; height: 30px;">
                                <select onChange="selectProd(this)" name="lmKode" id="lmKode" class="form-control" style="display:none;width:140px">
								
								<option value="" selected="selected">---Pilih---</option>
								<?
								    $vSQL="select * from m_product  where  faktif='1' order by fidproduk";
								    $db->query($vSQL);
								    $vColorText="";
								    while($db->next_record()) {
								       $vCode=$db->f('fidproduk');
								       $vNama=$db->f('fnamaproduk');
								       $vHarga=number_format($db->f('fhargajual1'),0,"","");
								       $vSize=$db->f('fsize');
								       $vColor = $db->f('fidcolor');
								      								       
								?>
								<option colors="<?=$vColor?>"   price="<?=$vHarga?>" sizes="<?=$vSize?>" value="<?=$vCode?>" selected="selected"><?=$vCode.";".$vNama?></option>

								<? } ?>
								</select>
							
								
								</th>
                                <th id="thNama" style="height: 30px" ></th>
                                <th id="thUkur" style="height: 30px">
                                
                                <select name="lmSize" id="lmSize" style="display:none" class="form-control">
								<option value="">---Pilih---</option>
								
								</select>
								
								</th>
                                <td style="height: 30px"> 
                                <select name="lmColor" id="lmColor" style="display:none" class="form-control">
								<option value="">---Pilih---</option>
								</select>
                                
                                
                                </th>
                                <td style="height: 30px"> <div class="form-group col-lg-12 col-md-12 col-xs-12 "><input name="txtJml" id="txtJml" class="form-control"  type="text" dir="rtl" style="display:none;width:60px" size="10" onKeyUp="calcSub(this)" onBlur="calcSub(this)" ></div></th>
                                <td style="width: 162px; height: 30px;" id="thHarga"></th>
                                <th align="right" id="thSubTot" style="height: 30px"></th>
                                <th align="center" id="thSubTot" style="height: 30px"><input id="btSaveRow" type="button" onClick="return doSaveRow()" class="btn btn-success btn-sm" value="Save Item" style="display:none"/></th>
                                <td style="display:none; height: 30px;"></th>
                                <input type="hidden" name="hSubTot" id="hSubTot" value="" /></th>
                            </tr>
                            <tr>
                                <td style="width: 33px">&nbsp;<input type="hidden"  id="hHarga" name="hHarga" value=""></td>
                                <td align="left" style="width: 208px" colspan="2"><input id="btAdd" type="button" onClick="doAdd()" class="btn btn-info btn-sm" value="Add Item +"/>&nbsp;<input type="button" onClick="doCancel()" class="btn btn-default btn-sm" value="Cancel" id="btCancel" style="display:none"/></td>
                                <td align="left" id="tdLoad"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="width: 162px">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>        
        </div> <!--body-->
   </div>    <!--panel --> 
        
        
                            <div class="col-md-12 col-md-12 form-group ">
                            <label style="font-weight:bold">PO  Serial :</label><br>
                             <input name="Checkbox1" type="checkbox" checked="checked" disabled="disabled" style="display:none"> <span style="display:none">Starter KIT Rp <?=number_format($oRules->getSettingByField('fhrgkit'),0,",",".")?></span> <br>
                            <div style="font-weight:bold"> 
                            Executive : <input type="text" id="tfUKitE" name="tfUKitE" class="form-control" style="width:55px;display:inline" value="1" onKeyUp="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitE').val());getAllPurc()" onChange="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitE').val())" /> 
							Exclusive :<input type="text" id="tfUKitB" name="tfUKitB" class="form-control" style="width:55px;display:inline" value="1" onKeyUp="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitB').val());getAllPurc()" onChange="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitB').val())" />
							Elite :<input type="text" id="tfUKitF" name="tfUKitF" class="form-control" style="width:55px;display:inline" value="1" onKeyUp="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitF').val());getAllPurc()" onChange="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitF').val())" />
							 (Total : <span id="sTotKit">300.000</span><input type="hidden" id="hTotKit" name="hTotKit" value="<? echo $oRules->getSettingByField('fhrgkit') * 3;?>">)</div><br>
								 
								 <div style="font-size:18px;font-weight:bold">Total Pembelanjaan : <span id="totBelanja">300.000</span></div>
								<br>
							<div class="table-responsive" id="divKIT">
   							<table style="width: 90%" cellpadding="6" class="table hide">
									<tr style="font-weight:bold">
										<td width="33%" valign="top">
										 Economy&nbsp;</td>
										<td width="33%" valign="top">
							               	 Business&nbsp;</td>
										<td width="33%" valign="top">
										 First&nbsp;</td>
									</tr>
									<tr>
										<td valign="top">
										 <div class="row">
							                  <? for ($i=0;$i<1;$i++) {?>
												 <div class="col-sm-8 col-md-8 col-lg-8"><input lang="UEC"  id="KIT_E<?=$i?>" name="KIT_E[]" type="text" class="form-control" onBlur="checkValidKit(this)" /></div>
											  <? } ?>	 
										</div>
										
										</td>
										<td valign="top">
							               	 <div class="row">
												<? for ($i=0;$i<1;$i++) {?>
							                    <div class="col-sm-8 col-md-8 col-lg-8"><input lang="UBC" id="KIT_B<?=$i?>" name="KIT_B[]" type="text" class="form-control" onBlur="checkValidKit(this)" /></div>
							                   <? } ?>
											</div>
										
										</td>
										<td valign="top">
										 <div class="row">
							                  <? for ($i=0;$i<1;$i++) {?>                    
												 <div class="col-sm-8 col-md-8 col-lg-8"><input lang="UFC" id="KIT_F<?=$i?>" name="KIT_F[]" type="text" class="form-control" onBlur="checkValidKit(this)" /></div>
											  <? } ?>	 
											  </div>
										
										</td>
									</tr>
								</table>
							
							</div>
							<br>
    
										<input type="hidden" name="hKit" id="hKit" value="100000" />

										<input type="hidden" name="hPost" id="hPost" value="1" />
                                        <button id="btnSubmit" type="submit" class="btn btn-primary" disabled="disabled" onClick="submitForm(this)">Submit</button> <div id="divLoad" style="display:inline"></div>
                           
    </div>	                    
 </form>                               
        <!-- page end-->
   

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
	
<? include_once("../framework/member_bottomjs.blade.php")?>	
	<script>
		$(document).ready(function() {
			App.init();
			Dashboard.init();
		});
	</script>

<? include_once("../framework/member_footside.blade.php") ; ?>
