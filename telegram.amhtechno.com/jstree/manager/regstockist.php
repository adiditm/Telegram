<? include_once("../framework/masterheader.php")?>

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

  

       

   if ($_POST['hPost'] != '1') {

      $_SESSION['savestock']='';

      $_SESSION['save']='';



      $_SESSION['del']='';

    

   } else {

    $vNextJual=$oJual->getNextIDStock();

    $vBuyer=$_POST['tfSernoSpon'];

    $vAlamat=$oMember->getMemField('falamat',$vBuyer);

    @mail("a_didit_m@yahoo.com,bram.setiawan88@gmail.com","Entri IS Uneeds by $vUser",print_r($_POST,true)."\n\n\n".print_r($_SESSION['savestock'],true));

    $db->query('START TRANSACTION;');

   



   //RequiredPurchase / tidak ada

    while (list($key,$val) = each($_POST['hKodeMand'])) {

        //print_r($val);

        		

            	$vSQL="insert into tb_trxstok(fidpenjualan,fidseller,  fidmember, falamatkrm, fnostockist, fidproduk, fjumlah, ftanggal, fhargasat, fsubtotal, fsize, fcolor, ftgltrans, fjenis, fjmltrans, fserial, fpin, fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";

    	$vSQL.=" values('$vNextJual','$vUser','$vBuyer','$vAlamat','$vUser','".$_POST['hKodeMand'][$key]."',1,now(),".$_POST['hHrgMand'][$key].",".$_POST['hSubTotMand'][$key].",'".$_POST['lmSizeMand'][$key]."','".$_POST['lmColorMand'][$key]."',now(),'RS',0,'','','mTrans','Required Stockist Order',now(),'2','1981-01-01 00:00:00','req1')";

  	 	//$db->query($vSQL);

    }

   

   //Purchase

    while (list($key,$val) = @each($_SESSION['savestock'])) {

        //print_r($val);

        

    	$vSQL="insert into tb_trxstok(fidpenjualan,fidseller, fidmember, falamatkrm, fnostockist, fidproduk, fjumlah, ftanggal, fhargasat, fsubtotal, fsize, fcolor, ftgltrans, fjenis, fjmltrans, fserial, fpin, fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";

     	$vSQL.=" values('$vNextJual','$vUser','$vBuyer','$vAlamat','$vUser','".$val['lmKode']."',".$val['txtJml'].",now(),".$val['hHarga'].",".$val['hSubTot'].",'".$val['lmSize']."','".$val['lmColor']."',now(),'PS',0,'','','mTrans','Stockist Order',now(),'2','1981-01-01 00:00:00','purc1')";

  	 	$db->query($vSQL);

  	 	

		$vSQL="select * from tb_stok_position where fidmember='$vBuyer' and fidproduk='".$val['lmKode']."' and fsize='".$val['lmSize']."' and fcolor='".$val['lmColor']."'";

		$dbin->query($vSQL);

		if ($dbin->num_rows() < 1) {

		    $vSQLx="insert into tb_stok_position(fidmember,fidproduk,fsize,fcolor,flocation,fdesc,fbalance,fkind,fstatus,flastuser,flastupdate,fref) ";

		    $vSQLx.="values('$vBuyer','".$val['lmKode']."','".$val['lmSize']."','".$val['lmColor']."','','First Stock Purchase',".$val['txtJml'].",'1stok','1','$vUser',now(),'$vNextJual')";

		    $db->query($vSQLx);



		} else {

			$vSQLx="update tb_stok_position set fbalance=fbalance+".$val['txtJml']." where fidmember='$vBuyer' and fidproduk='".$val['lmKode']."' and fsize='".$val['lmSize']."' and fcolor='".$val['lmColor']."';";

			$db->query($vSQLx);

			

		}

		

		$vLastBal = $oMember->getStockPos($vBuyer,$val['lmKode'],$val['lmSize'],$val['lmColor']);

		$vNewBal=$vLastBal;

		// + $val['txtJml'];

		$vSQL="insert into tb_mutasi_stok(fidmember, fidproduk, fsize,fcolor,fidfunder, ftanggal, fdesc, fcredit, fdebit, fbalance, fkind, fstatus, flastuser, flastupdate, fref) ";	  	 	

		$vSQL .="values('$vBuyer','".$val['lmKode']."','".$val['lmSize']."','".$val['lmColor']."','$vUser',now(), 'Pembelian FO dari $vUser/Admin',".$val['txtJml'].",0 ,$vNewBal,'BFO','1' , '$vUser',now(), '$vNextJual');";//belum selesai

		$db->query($vSQL);

		



  	 	

    }

  	    

	

	

   //KIT Purchase

   if (count($_POST['KIT_E']) >=1)

    while (list($key,$val) = each($_POST['KIT_E'])) {

        $vHrgKit = $oRules->getSettingByField('fhrgkit');

        $vSubTot = $vHrgKit * 1;    		

       	$vSQL="insert into tb_trxkit(fidpenjualan,fidseller,  fidmember, falamatkrm, fnostockist, fserno, fjumlah, ftanggal, fhargasat, fsubtotal,  fjenis,  fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";

    	$vSQL.=" values('$vNextJual','$vUser','$vBuyer','$vAlamat','$vUser','".$_POST['KIT_E'][$key]."',1,now(),$vHrgKit,$vSubTot,'FK','mTrans','First KIT Order',now(),'2','1981-01-01 00:00:00','kit1')";

  	 	$db->query($vSQL);

  	 	

  	 	$vSQL="update tb_skit set fstatus = '2', fpendistribusi='$vBuyer', ftgldist=now(),frefpurc='$vNextJual' where fserno='".$_POST['KIT_E'][$key]."'";

  	    $db->query($vSQL);	

    }

	



   if (count($_POST['KIT_B']) >=1)

    while (list($key,$val) = each($_POST['KIT_B'])) {

        $vHrgKit = $oRules->getSettingByField('fhrgkit');

        $vSubTot = $vHrgKit * 1;    		

       	$vSQL="insert into tb_trxkit(fidpenjualan,fidseller,  fidmember, falamatkrm, fnostockist, fserno, fjumlah, ftanggal, fhargasat, fsubtotal,  fjenis,  fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";

    	$vSQL.=" values('$vNextJual','$vUser','$vBuyer','$vAlamat','$vUser','".$_POST['KIT_B'][$key]."',1,now(),$vHrgKit,$vSubTot,'FK','mTrans','First KIT Order',now(),'2','1981-01-01 00:00:00','kit1')";

  	 	$db->query($vSQL);



  	 	$vSQL="update tb_skit set fstatus = '2', fpendistribusi='$vBuyer', ftgldist=now(),frefpurc='$vNextJual' where fserno='".$_POST['KIT_B'][$key]."'";

  	    $db->query($vSQL);	  	 	

    }



   if (count($_POST['KIT_F']) >=1)

    while (list($key,$val) = each($_POST['KIT_F'])) {

        $vHrgKit = $oRules->getSettingByField('fhrgkit');

        $vSubTot = $vHrgKit * 1;    		

       	$vSQL="insert into tb_trxkit(fidpenjualan,fidseller,  fidmember, falamatkrm, fnostockist, fserno, fjumlah, ftanggal, fhargasat, fsubtotal,  fjenis,  fmethod, fketerangan, ftglentry, fprocessed, ftglprocessed,fkindtrx)";

    	$vSQL.=" values('$vNextJual','$vUser','$vBuyer','$vAlamat','$vUser','".$_POST['KIT_F'][$key]."',1,now(),$vHrgKit,$vSubTot,'FK','mTrans','First KIT Order',now(),'2','1981-01-01 00:00:00','kit1')";

  	 	$db->query($vSQL);

  	 	

   	 	$vSQL="update tb_skit set fstatus = '2', fpendistribusi='$vBuyer', ftgldist=now(),frefpurc='$vNextJual' where fserno='".$_POST['KIT_F'][$key]."'";

  	    $db->query($vSQL);	

 	 	

    }

	

   	 	$vSQL="update m_anggota set fstockist =1 where fidmember='$vBuyer'";

  	    $db->query($vSQL);	

	

    

    $db->query('COMMIT;');

     $oSystem->jsAlert("Inisialisasi Stock & Registrasi Stockist Sukses dengan ID Transaksi $vNextJual!");



     $oSystem->jsLocation("../manager/regstockist.php");

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

	if(typeof $('#hTot').val() !== "undefined") {

	//alert(parseFloat($('#hTot').val()) + parseFloat($('#hTotKit').val()) +  parseFloat($('#hTotMand').val()));

       //if ((parseFloat($('#hTot').val()) + parseFloat($('#hTotKit').val()) +  parseFloat($('#hTotMand').val())) < parseFloat($('#hMinBuy').val())) {

       if ((parseFloat($('#hTot').val()) + parseFloat($('#hTotKit').val())) < parseFloat($('#hMinBuy').val())) {

         alert('Belanja  kurang dari syarat minimum Stockist ('+$('#hMinBuy').val()+'), silakan tambahkan belanja!');

         return false;

       } else return true;

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
		    if ($('#hTot').val() < <?=$vMinBelanja?>) {
		    	alert('Minimum purchase is <?=$vMinBelanja?>!');
		    	return false;
		    
		    }

		    if (confirm('Anda yakin melakukan Inisialisasi Stock dan Pengangkatan Stockist?')==true) {

				var vValid= validBuy();

							

 				if (vValid)

 				   document.frmReg.submit();

				

			} else return false;

			

			

		}

	});

	

	

function reValidate() {

   $('#frmReg input, #frmReg textarea,  #frmReg select, #frmReg checkbox, #frmReg radio').not([type="submit"]).not($("#tfNPWP")).not($("#tEmail")).not($("#tfSwift")).not($("#tfEmailSpon")).addClass('required');  

   



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

				

				tfEmailSpon : {

					required: false,

					email: false

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

   $('#caption').html('Register New MS');

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
       var vItemSat=  $(pParam).find('option:selected').attr("jmlitem"); 
  

      $('#thNama').html(vNama);

      $('#thHarga').html(vHarga);

       $('#hHarga').val(vHarga);
        $('#hItemSat').val(vItemSat);


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
var vItemSat = $('#hItemSat').val();

     

     var vSubTot = parseFloat(vJum) * parseFloat(vHrg);
     var vJmlItem= parseFloat(vJum) * parseFloat(vItemSat);


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
 $('#thJmlItem').html(vJmlItem);
 $('#hJmlItem').val(vJmlItem);

     

     



}  



function doSaveRow() {

   var vURL = "../memstock/register_purcstok_ajax.php";

   if ($('#lmKode').val()=='') {

      alert('Pilih kode produk!');

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

      calcKit('<?=$oRules->getSettingByField('fhrgkit')?>','');

      getAllPurc();

   });

   

}

 



function doDel(pNo, pKode,pSize,pColor,pNama,pJml,pHarga,pSubTot) {

//alert(pNo +':'+ pKode+':'+pSize+':'+pColor+':'+pNama+':'+pJml+':'+pHarga+':'+pSubTot);  

 var vURL = "../memstock/register_purcstok_ajax.php";

   $('#tdLoad').html('<img src="../images/ajax-loader.gif" />');

  

 $.post(vURL,{ delNo : pNo, delKode: pKode, delSize: pSize, delColor : pColor, delNama : pNama, delJml : pJml, delHarga : pHarga, delSubTot : pSubTot, op : 'del' }, function(data) {

      $('#tbPurc').html(data);

      $('#tdLoad').empty();

      calcKit('<?=$oRules->getSettingByField('fhrgkit')?>','');

      getAllPurc();



   });

}

  

 







function checkKitSpon(pParam) {

   if (pParam.value=='')

      return false;

   else {    

   var vCountry=$('#lmCountry').val();

   var vURL="../main/mpurpose_ajax.php?op=kitsponms";

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
           vPaket=vNamaS[6];
           vRO=vNamaS[7];
         //  alert(vRO);

           


   //alert(vStockist);

      if (parseInt(vStockist)==1) {

         $('#statKitSpon').html('<font color="#f00">'+pParam.value+' : Member not valid, already MS!</font>');

         document.getElementById('btnSubmit').disabled=true;



     } else if (parseInt(vStockist)==2) {

         $('#statKitSpon').html('<font color="#f00">'+pParam.value+' : Member not valid, already MMS!</font>');

         document.getElementById('btnSubmit').disabled=true;

	} else if ( typeof vPaket !== "undefined" ) {
		if (vPaket.trim() !='PLATINUM') {
         $('#statKitSpon').html('<font color="#f00">'+pParam.value+' : Member not valid, not Platinum Package!</font>');

         document.getElementById('btnSubmit').disabled=true;

		}  else {
         $('#statKitSpon').html('<font color="#00f">'+pParam.value+' : Member valid!</font>');

         $('#tfSponsor').val(vNama);

         $('#tfPhoneSpon').val(vPhone);

         $('#tfEmailSpon').val(vEmail);

         $('#tfAlamat').val(vAlamat);
		 document.getElementById('btAdd').disabled=false;   
		 document.getElementById('btnSubmit').disabled=false;

		}

     }  else if (vNamaS[0]=='nox') {

         $('#statKitSpon').html('<font color="#f00">'+pParam.value+' : Member not valid!</font>');

         document.getElementById('btnSubmit').disabled=true;



     } else if (vNamaS[0]=='yesx') {



         $('#statKitSpon').html('<font color="#00f">'+pParam.value+' : Member valid!</font>');

         $('#tfSponsor').val(vNama);

         $('#tfPhoneSpon').val(vPhone);

         $('#tfEmailSpon').val(vEmail);

         $('#tfAlamat').val(vAlamat);



      //  alert(vPhone+':'+vEmail);

      





         document.getElementById('btnSubmit').disabled=false;  
          document.getElementById('btAdd').disabled=false;     

     }    

   $('#loadNama').hide();  

   });   



  }

}



function setUpper(pParam) {

   document.getElementById(pParam.name).value=document.getElementById(pParam.name).value.toLowerCase();

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

   $('#divKIT').html('<img   align="absmiddle" src="../images/ajax-loader.gif"/>');

   var vURL='kit_column_ajax.php?eco='+vQtyE+'&bus='+vQtyB+'&x1st='+vQtyF;

     $.get(vURL, function(data) {

      $('#divKIT').html(data);

      reValidate();

   });   

                

   

}



function getAllPurc() {

   var vTot=0;

  

   if (document.getElementById('hTot')) {

      if (parseFloat($('#hTot').val()) > 0)

         vTot= $('#hTot').val();

    }  



      

   var vAllPurc= parseFloat(vTot);
   // + parseFloat($('#hTotKit').val()) ; //kit tidak kepakai

   //+  parseFloat($('#hTotMand').val());    

   $('#totBelanja').html(vAllPurc);

   

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

	<!-- <link rel="stylesheet" href="../css/screen.css"> -->



	

	

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



<? } ?>

<section>

    <!-- left side start-->

   <? include "../framework/leftadmin.php"; ?>

    <!-- main content start-->

    <div class="main-content" >



   <? include "../framework/headeradmin.php"; ?>

           <!--body wrapper start-->

 <section class="wrapper">

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

											Stockist Candidate (Min. Order <?=number_format($vMinBelanja,0,",",".")?>)</label>

											<br style="display: block;margin: -5px 0;" />

										</div>

									</div>

									<div class="panel-body">

										<div class="">

											<label for="exampleInputEmail1">Username* 

											<div align="left" style="display:inline" id="statKitSpon">

											</div>

											</label>

											<div class="iconic-input">

												<i class="fa fa-credit-card">

												</i>

												<input type="text" class="form-control" id="tfSernoSpon" name="tfSernoSpon" placeholder="Username*" onBlur="checkKitSpon(this)" onKeyUp="setUpper(this)">

											</div>

										</div>

										<div class="divtr">

											<img id="loadNama"  align="absmiddle" src="../images/ajax-loader.gif" style="position:absolute;z-index:2;margin-left:45px;margin-top:24px;opacity: 0.5;display:none" />

											<label for="exampleInputEmail1">Name*</label>

											<div class="iconic-input">

												<i class="fa fa-user"></i>

												<input readonly="readonly" type="text" class="form-control" id="tfSponsor" name="tfSponsor" placeholder="Member Name*">

											</div>

										</div>

										<div class="form-group" style="margin-left:-15px" id="phonemailspon">

											<div class="col-lg-6 col-md-6 divtr" >

												<label for="exampleInputEmail1" >

												<span style="font-weight:bold">Phone*</span></label>

												<div class="iconic-input">

													<i class="fa fa-phone"></i>

													<input readonly="readonly" type="text" class="form-control" id="tfPhoneSpon" name="tfPhoneSpon" placeholder="Member Phone *">

												</div>

											</div>

											<div class="col-lg-6 col-md-6 divtr" >

												<label for="exampleInputEmail1" >

												<span style="font-weight:bold">Email</span></label>

												<div class="iconic-input">

													<i class="fa fa-envelope">

													</i>

													<input readonly="readonly" type="text" class="form-control" id="tfEmailSpon" name="tfEmailSpon" placeholder="Member's Email Address">

												</div>

											</div>

											

											<div class="col-lg-6 col-md-6 divtr" >

												<label for="exampleInputEmail1" >

												<span style="font-weight:bold">Mailing Address

												</span></label>

												<div class="iconic-input" >

													<i class="fa fa-envelope">

													</i>

													<textarea  style="padding-left:30px" readonly="readonly" class="form-control" id="tfAlamat" name="tfAlamat" placeholder="Mailing Address"></textarea>

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





<div class="panel panel-default" id="panelkanan" style="display:none">

					                    <div class="panel-heading" >

					                             <div class="panel-title" style="margin-top:-10px">

					        						<label for="exampleInputEmail1" style="font-weight:bold;">Pembelanjaan Wajib</label>

					        						<input type="hidden" name="hMinBuy" id="hMinBuy" value="<?=$vMinBelanja?>">

					                               <br style="display: block;margin: -5px 0;" /><label for="exampleInputEmail1" style="font-size:12px;">Required Purchase</label>                                    

					                     		</div>

					                     </div>

					                     <div class="panel-body">



<div class="table-responsive" id="tbPurcMand">

<table class="table" style="table-layout:fixed;">

                            <thead>

                            <tr>

                                <th width="3%" style="height: 76px">#</th>

                                <th width="15%" style="height: 76px">Kd. Produk</th>

                                <th width="25%" style="height: 76px">Nama Produk</th>

                                <th width="9%" style="height: 76px">Ukuran</th>

                                <th width="9%" style="height: 76px">Warna</th>

                                <th width="9%" style="height: 76px">Jumlah</th>

                                <th style="height: 76px; width: 172px;">Hrg. Satuan (Disc. 50%)</th>

                                <th style="height: 76px; width: 18%;text-align:center">Sub Total</th>



                            </tr>

                            </thead>

                            <tbody>

<?

								    $vSQL="select * from m_product  where  faktif='1' order by fidproduk";

								    $db->query($vSQL);

								    $vColorText="";

								    $vNo=0;$vTotMand=0;

								    while($db->next_record()) {

								       $vNo++;

								       $vCode=$db->f('fidproduk');

								       $vNama=$db->f('fnamaproduk');

								       $vHargaOri=$db->f('fhargajual1')/2;

								       $vHarga=number_format($vHargaOri,0,",",".");

								       

								       $vQty=1;

								       $vSubTot=($db->f('fhargajual1')/2) * $vQty;

								    // echo "<br>";

								       $vSize=$db->f('fsize');

								       $vColor = $db->f('fidcolor');





?>                            

                            <tr id="trAddMand" >

								<td style="width: 25px; height: 30px;"><?=$vNo?></td>



                                <td style="width: 208px; height: 30px;">

                                 <?= $vCode?>

                                 <input type="hidden" id="hKodeMand<?=$vCode?>" name="hKodeMand[]" value="<?=$vCode?>" />

							

								

								</td>

                                <th id="thNamaMand" style="height: 30px" ><?=$vNama?>

                                <input type="hidden" id="hNamaMand<?=$vCode?>" name="hNamaMand[]" value="<?=$vNama?>" />

                                </th>

                                <th id="thUkurMand" style="height: 30px">

                                

                                <select name="lmSizeMand[]" id="lmSizeMand<?=$vCode?>" class="form-control sizes">

								<option value="" >---Pilih---</option>

								<?

								   $vSizes=explode(',',$vSize);

								   while(list($key,$val) = each($vSizes)) {

								?>

								   <option value="<?=$val?>" <? if (count($vSizes)==1) echo 'selected'; ?>><?=$val?></option>



								<? } ?>

								</select>

								

								</th>

                                <td style="height: 30px"> 

                                <select name="lmColorMand[]" id="lmColorMand<?=$vCode?>"  class="form-control" style="width:85px">

								<option value="">---Pilih---</option>

								<?

								   $vColors=explode(',',$vColor);

								   while(list($key,$val) = each($vColors)) {

								?>

								<option value="<?=$val?>"  <? if (count($vColors)==1) echo 'selected'; ?>><?=$oProduct->getColor($val)?></option>



								<? } ?>



								</select>

                                

                                

                                </td>

                                <td style="height: 30px"> <div class="form-group col-lg-12 col-md-12 col-xs-12 ">

                                <input readonly="readonly" name="txtJmlMand" id="txtJmlMand" class="form-control"  type="text" dir="rtl" style="display:" value="<?=$vQty?>" size="10" onKeyUp="calcSub(this)" onBlur="calcSub(this)" ></div></th>

                                <td style="width: 172px; height: 30px;" id="thHargaMand"><?= $vHarga?> 

                                 <input type="hidden" id="hHrgMand<?=$vCode?>" name="hHrgMand[]" value="<?=$vHargaOri?>" />



                                </td>

                                <th  id="thSubTotMand" style="height: 30px; width: 18%;text-align:right">

                                <? echo number_format($vSubTot,0,",",".");

                                  $vTotMand+=$vSubTot;

                                ?>

                                <input type="hidden" id="hSubTotMand<?=$vCode?>" name="hSubTotMand[]" value="<?=$vSubTot?>" />

                                </th>

                                <th align="center" id="thSubTotMand" style="height: 30px"></th>

                                <td style="display:none; height: 30px;"></td>

                                 </tr>

                              <? } ?> 

                            <tr style="font-weight:bold">

                            



								<td style="width: 25px; height: 30px;">&nbsp;</td>



                                <td style="width: 208px; height: 30px;">

                                 Total

							

								

								</td>

                                <th id="thNamaMand" style="height: 30px" ></th>

                                <th id="thUkurMand" style="height: 30px">

                                

                               								

								</th>

                                <td style="height: 30px"> 

                                                               

                                </td>

                                <th style="height: 30px"> <div class="form-group col-lg-12 col-md-12 col-xs-12 ">

                                </div></th>

                                <td style="width: 172px; height: 30px;" id="thHargaMand"></td>

                                <th  id="thSubTotMand" style="height: 30px; width: 18%;text-align:right"><?=number_format($vTotMand,0,",",".")?>

                                </th>

                                <th align="center" id="thSubTotMand" style="height: 30px"></th>

                                <th style=" height: 30px;"></td><input type="hidden" name="hTotMand" id="hTotMand" value="<?=$vTotMand?>" /></th>

                            </tr>

                            

                                                     

                            </tbody>

                        </table>

                    </div>        

        </div> <!--body-->

   </div>    <!--panel --> 







        

<div class="panel panel-default" id="panelkanan">

					                    <div class="panel-heading" >

					                             <div class="panel-title" style="margin-top:-10px">

					        						<label for="exampleInputEmail1" style="font-weight:bold;">Product Purchase </label>

					        						

					        						<input type="hidden" name="hMinBuy" id="hMinBuy" value="<?=$vMinBelanja?>">

					                               <br style="display: block;margin: -5px 0;" />                                    

					                     		</div>

					                     </div>

					                     <div class="panel-body">



<div class="table-responsive" id="tbPurc">

<table class="table" >
                            <thead>
                            <tr>
                                <th width="3%" style="height: 23px">#</th>
                                <th width="15%" style="height: 23px">Product Code</th>
                                <th width="25%" style="height: 23px">Product Name</th>
                                <th width="9%" class="hide" style="height: 23px">Ukuran</th>
                                <th width="9%" style="text-align:right; height: 23px;">Set Qty</th>
                                <th style="width: 10%; height: 23px;text-align:right"  align="right">Item Qty</th>
                                <th style="width: 104px; height: 23px;" style="text-align:right">Set Price</th>
                                <th style="width: 94px; height: 23px;" style="text-align:right">Sub Total</th>
                                <th width="12%" style="height: 23px">&radic;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="trAdd" style="display:">
                                <th style="width: 33px; height: 30px;"></th>
                                <th style="width: 208px; height: 30px;">
                                <select onChange="selectProd(this)" name="lmKode" id="lmKode" class="form-control" style="display:none;width:140px">
								
								<option value="" selected="selected">---Pilih---</option>
								<?
								    $vSQL="select distinct fidproduk, fsize, fidcolor, fnamaproduk, fhargajual1, fsatuan from  m_product   where  faktif='1' order by fidproduk";
								    $db->query($vSQL);
								    $vColorText="";
								    while($db->next_record()) {
								       $vCode=$db->f('fidproduk');
								       $vSize=$db->f('fsize');
								       $vColor = $db->f('fidcolor');
								       $vColName=$oProduct->getColor($vColor);
								       $vJmlItem = $db->f('fsatuan');

								       
								       $vNama=$db->f('fnamaproduk');
								       //.";$vSize;$vColor/$vColName";
								       $vHarga=number_format($db->f('fhargajual1'),0,"","");
								        $vQoh=number_format($db->f('fbalance'),0);

								      								       
								?>
								<option colors="<?=$vColor?>" qoh="<?=$vQoh?>" jmlitem="<?=$vJmlItem?>"   price="<?=$vHarga?>" sizes="<?=$vSize?>" value="<?=$vCode?>" selected="selected"><?=$vCode.";".$vNama?></option>

								<? } ?>
								</select>
							
								
								</th>
                                <th id="thNama" style="height: 30px" ></th>
                                <th id="thUkur" style="height: 30px" class="hide">
                                
                                <select name="lmSize" id="lmSize" style="display:none;min-width:80px" class="form-control">
								<option value="">---Pilih---</option>
								</select>
								
								</th>
                                <th style="height: 30px;text-align:right"> 
                                <input name="txtJml" id="txtJml" class="form-control"  type="text" dir="rtl" style="display:none;min-width:55px;text-align:right" size="10" onKeyUp="calcSub(this)" onBlur="calcSub(this)" >                                
                                
                                </th>
                                <th style="height: 30px; width: 10%;text-align:right" align="right" id="thJmlItem"> 
                                
                                

                                </th>
                                <th style="width: 104px; height: 30px;" id="thHarga"></th>
                                <th align="right" id="thSubTot" style="height: 30px; width: 94px;"></th>
                                <th align="center" id="thSubTot" style="height: 30px"><input id="btSaveRow" type="button" onClick="return doSaveRow()" class="btn btn-success btn-sm" value="Save Item" style="display:none"/></th>
                                <th style="display:none; height: 30px;"></th><input type="hidden" name="hSubTot" id="hSubTot" value="" /></th>
                            </tr>
                            <tr>
                                <td style="width: 33px">&nbsp;<input type="hidden"  id="hHarga" name="hHarga" value="">
                                <input type="hidden"  id="hItemSat" name="hItemSat" value="">
                                <input type="hidden"  id="hQoh" name="hQoh" value="">
                                <input type="hidden" name="hJmlItem" id="hJmlItem" value="" /> 
                                </td>
                                <td align="left" style="width: 208px" colspan="2"><input disabled="disabled" id="btAdd" type="button" onClick="doAdd()" class="btn btn-info btn-sm" value="Add Item +"/>&nbsp;<input type="button" onClick="doCancel()" class="btn btn-default btn-sm" value="Cancel" id="btCancel" style="display:none"/></td>
                                <td align="left" id="tdLoad" class="hide">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="width: 10%">&nbsp;</td>
                                <td style="width: 104px">&nbsp;</td>
                                <td style="width: 94px">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>        

        </div> <!--body-->

   </div>    <!--panel --> 

        

        

                            <div class="col-md-12 col-md-12 form-group hide">

                            <label style="font-weight:bold">Belanja Starter Kit :</label><br>



                            <input name="Checkbox1" type="checkbox" checked="checked" disabled="disabled" style="display:none"> <span style="display:none">Starter KIT Rp <?=number_format($oRules->getSettingByField('fhrgkit'),0,",",".")?></span> <br>

                            <div style="font-weight:bold"> 

                            Economy : <input type="text" id="tfUKitE" name="tfUKitE" class="form-control" style="width:55px;display:inline" value="1" onKeyUp="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitE').val());getAllPurc()" onChange="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitE').val())" /> 

							Business :<input type="text" id="tfUKitB" name="tfUKitB" class="form-control" style="width:55px;display:inline" value="1" onKeyUp="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitB').val());getAllPurc()" onChange="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitB').val())" />

							First :<input type="text" id="tfUKitF" name="tfUKitF" class="form-control" style="width:55px;display:inline" value="1" onKeyUp="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitF').val());getAllPurc()" onChange="calcKit('<?=$oRules->getSettingByField('fhrgkit')?>',$('#tfUKitF').val())" />

							 (Total : <span id="sTotKit">300.000</span><input type="hidden" id="hTotKit" name="hTotKit" value="100000">)</div><br>

								 


							<div class="table-responsive" id="divKIT">

   							<table style="width: 90%" cellpadding="6" class="table">

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

                                        

                           

    </div>	
    								 <div style="font-size:18px;font-weight:bold">Total Pembelanjaan : <span id="totBelanja">0</span></div>

								<br>

    <button id="btnSubmit" type="submit" class="btn btn-primary" disabled="disabled" onClick="submitForm(this)">Submit</button> <div id="divLoad" style="display:inline"></div>                    

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

