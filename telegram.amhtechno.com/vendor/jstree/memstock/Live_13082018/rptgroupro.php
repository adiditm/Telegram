<? 
		if ($_GET['op'] != '') 
           include_once("../framework/admin_headside.blade.php");
        else
           include_once("../framework/member_headside.blade.php") ;  
?>
<?

  $vRefURL=$_SERVER['HTTP_REFERER'];

  if ($vRefURL=="")

      $oSystem->jsLocation("../main/logout.php");

 

  $vRefUser=$_GET['uMemberId'];

  $vUser=$_SESSION['LoginUser'];

  $vSpy = md5('spy').md5($_GET['uMemberId']);

   if ($_GET['op']==$vSpy)

      $vUserChoosed=$_GET['uMemberId'];



  if (isset($vRefUser))

  	 $vUserChoosed=$vRefUser;

  else	 

  	 $vUserChoosed=$_SESSION['LoginUser'];

	$dateTimeStamp = strtotime(date('Y-m-d') . " GMT");
	date_default_timezone_set('Asia/Jakarta');
	$firstDayOfLastWeek = mktime(0,0,0,date("m",$dateTimeStamp),date("d",$dateTimeStamp)- date("w",$dateTimeStamp)-6);
	$lastDayOfLastWeek = mktime(0,0,0,date("m",$dateTimeStamp),date("d",$dateTimeStamp)-date("w",$dateTimeStamp)+1);
	
	$vLastWeekStart=date('Y-m-d',$firstDayOfLastWeek);
	$vLastWeekEnd=date('Y-m-d',$lastDayOfLastWeek);



 $vPage=$_POST['lmPage'];	 

    if ($vPage=="") $vPage=1;



 $vStart=$_POST['dc'];$vEnd=$_POST['dc1'];
 
 if ($vStart=='') $vStart=$vLastWeekStart;
 if ($vEnd=='') {
	 
	 $vEnd=date('Y-m-d');
 
 	$vLastWeekEnd=strtotime($vLastWeekEnd . ' +1 day');
 	$vLastWeekEnd = date("Y-m-d",$vLastWeekEnd);
	
 }
 

 

 $vStartString=$vStart;

 $vEndString=$vEnd;

 $vSearch=$_POST['tfSearch'];



 $vCrit="";$vFilterText="";

 if ($vStart!="" || $vEnd!="") {

	    $vCrit.=" and date(ftanggal) >= '$vStartString' and date(ftanggal) <= '$vEndString' or (date(ftanggal)='$vLastWeekEnd' and fkind='resetweek' and fidmember='$vUserChoosed') " ;
	    $vFilterText.="[Date: ".$oPhpdate->YMD2DMY($vStartString)." - ".$oPhpdate->YMD2DMY($vEndString)."]";
}

 if ($vSearch!="")	{	

        $vCrit.=" and fdesc like '%$vSearch%' ";
	    $vFilterText.="[Description: $vSearch]";
}        



  $vSQL="select count(*) as fjumrec from tb_mutasi where fidmember='$vUserChoosed' and fkind in('bonusrogroup') $vCrit";

 

  	 // echo "xxx";

 //exit;	

 $db->query($vSQL);

 $db->next_record();

 

 $vJumRec=$db->f("fjumrec");

 $vRecPerPage=$_POST['lmPP'];

 if ($vRecPerPage=="") $vRecPerPage=50;

 $vRecPerPage;

 $vJumPage=ceil($vJumRec / $vRecPerPage);

 $vOffset=($vPage-1) *  $vRecPerPage ;

// $oKomisi->delZeroMut();

 $vByy=	 $oRules->getSettingByField('fbyyadmin');
 $vPersenRO=$oRules->getSettingByField('fprosenauto');
 $vPersenNex=$oRules->getSettingByField('fprosencash');

?>





<script language="Javascript">

$(document).ready(function(){

      
  <? if ($oDetect->isMobile()) {?>
  $('#caption').html('<span data-toggle="tooltip" data-placement="top" title="Bonus Belanja Group <?=$oMember->getMemberName($vUserChoosed)?>"><?=substr("Bns Belanja Group ".$oMember->getMemberName($vUserChoosed),0,20);?>...</span>');
  <? } else { ?>
	$('#caption').html('Bonus Belanja Group <?=$vUserChoosed?>');
  <? } ?>

$('[data-toggle="tooltip"]').tooltip({tooltipClass:"ttclass"});  

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

	

	
function showDet(pTgl,pDesc,pCred,pDeb,pFullNex,pFullRO,pFullAll,pTax) {
   $('#lblTgl').html(pTgl);
   $('#lblDesc').html(pDesc);
   $('#lblCred').html(pCred);
   $('#lblDeb').html(pDeb);      
   $('#bnsFullNex').html(pFullNex);  
   $('#bnsFullRO').html(pFullRO);  
   $('#bnsFullAll').html(pFullAll);  
   $('#lblTax').html(pTax);


}

function showDetail(pTgl,pDesc,pCred,pDeb,pFullNex,pFullRO,pFullAll,pTax) {
   $('#lblTgl').html(pTgl);
   $('#lblDesc').html(pDesc);
   $('#lblCred').html(pCred);
   $('#lblDeb').html(pDeb);      
   $('#bnsFullNex').html(pFullNex);  
   $('#bnsFullRO').html(pFullRO);  
   $('#bnsFullAll').html(pFullAll);  
   $('#lblTax').html(pTax);


}

</script>



<script language="javascript">

   function clearForm() {    

	 document.demoform.dc.value='';

	 document.demoform.dc1.value='';

	 document.demoform.lmPP.selectedIndex=0;

	 document.demoform.tfSearch.value='';

   }

   

   function doSearch() {

     document.demoform.lmPage.selectedIndex=0;

	 document.demoform.submit();

   }

</script>





<style type="text/css">

<!--

.style1 {	color: #000000;

	font-weight: bold;

}

.style4 {color: #000000}

-->

table td {

	padding:3px 3px 3px 3px;

}

.auto-style1 {

	font-weight: bold;

}

</style>








 <link rel="stylesheet" type="text/css" href="../js/bootstrap-datepicker/css/datepicker-custom.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-timepicker/css/timepicker.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-colorpicker/css/colorpicker.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-daterangepicker/daterangepicker-bs3.css" />

  <link rel="stylesheet" type="text/css" href="../js/bootstrap-datetimepicker/css/datetimepicker-custom.css" />



 	
     <div class="content-wrapper" >
<section class="content" >	       

      <form name="demoform" method="post" id="demoform" >





<div class="row">
            <div align="left" class="col-sm-2"><strong>Date </strong></div>


              <div  class="col-lg-2  col-sm-2 col-md-2 col-xs-4" style="min-width:10em">
              <input class="dpick form-control form-inline"  name="dc"  id="dc"  value="<?=$vStart?>"   />
              </div>
              
              <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><strong>To</strong></div>
                            
              <div  class="col-lg-2 col-sm-2 col-md-2 col-xs-4" style="min-width:10em">
              <input  class="dpick form-control form-inline" name="dc1" id="dc1" value="<?=$vEnd?>" /></div>

          </div>
<br>
         <div class="row hide">
            <div align="left" class="col-sm-4"><strong>Record </strong></div>
            <div align="left" class="col-sm-6 col-md-6 col-xs-6">
   

              <select name="lmPP" id="lmPP" class="form-control">

                <option selected="selected" value="">--Default--</option>

                <option value="10" <? if ($vRecPerPage==10) echo "selected";?>>10</option>

                <option value="20" <? if ($vRecPerPage==20) echo "selected";?>>20</option>

                <option value="30" <? if ($vRecPerPage==30) echo "selected";?>>30</option>

                <option value="40" <? if ($vRecPerPage==40) echo "selected";?>>40</option>

                <option value="50" <? if ($vRecPerPage==50) echo "selected";?>>50</option>

              </select>

            </div>
          </div>

<br>
         <div class="row hide" >
            <div align="left" class="col-sm-4"><strong>Desc. Search </strong></div>

           <div align="left" class="col-sm-6 col-md-6 col-xs-6">
              <input name="tfSearch" type="text" id="tfSearch" value="<?=$vSearch?>" class="form-control" />
            </div></div>
<br>

          
        <div class="row">
                      <div class="col-lg-6">


        <input name="btRefresh" type="button" id="btRefresh" value="Search" onClick="doSearch()" class="btn btn-success" /> 

        &nbsp;

        <input name="btReset" type="button" id="btReset" value="Reset" onClick="clearForm()" class="btn btn-default"  />
    </div>
    </div>
  


      

           

        <div align="right" style="font-family:Arial, Helvetica, sans-serif;font-size:14px"><strong>

		Page :</strong> 

          <select name="lmPage" id="lmPage" onChange="document.demoform.submit()" class="form-control" style="width:65px">

            <? for ($i=1;$i<=$vJumPage;$i++) {?>

			<option value="<?=$i?>" <? if ($vPage==$i) echo "selected";?>>-<?=$i?>-</option>

			<? } ?>

          </select><br />

</div><div class="table-responsive" style="min-height:5em">

        <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" >

        <tr style="font-weight:bold">

          <td width="3%" align="center">No</td>

          <td width="11%" align="center" class="auto-style1">Date</td>

          <td width="48%" align="center" class="auto-style1">Description</td>

          <td width="13%" align="center">Nilai Bonus</td>

          <td width="13%" align="center">Referensi</td>

          <td width="12%" align="center" class="hide">Balance</td>

        </tr>

        <?

			   

			  //  $vSQL="select * from tb_mutasi where  1 and fidmember='$vUserChoosed' and fkind in('resetweek','spon','pairing') ";
				 $vSQL="select * from tb_mutasi where  1 and fidmember='$vUserChoosed' and fkind in('bonusrogroup') ";
				$vSQL.=$vCrit;
				
				

				

				 $vSQL.=" order by ftanggal,fidsys ";
				 

				$db->query($vSQL);
				$vArrData="";
				$vArrHead=array('No.','Date','Description','Bonus','Withdraw');
				$vArrBlank=array('','','','','');
				$vArrDateFilter=array('Filter :  '.$vFilterText,'','','','','');
				
				
				$i=0;$vTot=0;$vTotDeb=0;
				$vArrData[]=$vArrDateFilter;
				$vArrData[]=$vArrBlank;
				$vArrData[]=$vArrHead;
				
				while ($db->next_record()) { //Convert Excel
				     $i++;

					   $vDateList=$db->f("ftanggal");
					   $vFee=$db->f("ffee");
					   $vTanggalKom=$oPhpdate->YMD2DMY($db->f("ftanggal"),"-");
					   $vDesc=$db->f("fdesc");
					   $vFunder=$db->f("fidfunder");
					   $vCred=$db->f("fcredit");
					   $vTot+=$vCred;
					   $vKind=$db->f('fkind');
					   $vDeb=$db->f("fdebit");
					   $vTotDeb+=$vDeb;
					   $vBal=$db->f("fbalance");
   
				     

				 //$vArrHead=array('No.','Date','Description','Credit','Debit','Balance');

				 $vArrData[]=array($i,$vTanggalKom,$vDesc,$vCred,$vDeb);
					//$vArrMem['fpassword'][$i]=$oSystem->doED('decrypt',$db->f('fpassword'));
				
				}
				
				$vArrTot=array('','','Total: ',$vTot,$vTotDeb);
				$vArrData[]=$vArrTot;
				$_SESSION['weekstate']=$vArrData;







				 
				 $vSQL.=" limit $vRecPerPage offset $vOffset ";

				

				$db->query($vSQL);

				$vTotFee=0;

				$vNo=0;$vTotBon=0;$vTotWd=0;

				while ($db->next_record()) {
				   $vNo+=1;
				   $vDateList=$db->f("ftanggal");
				   $vFee=$db->f("ffee");
				   $vTanggalKom=$oPhpdate->YMD2DMY($db->f("ftanggal"),"-");
				   $vDesc=$db->f("fdesc");
				   $vFunder=$db->f("fidfunder");
				   $vCred=$db->f("fcredit");
				   $vTaxPph=$db->f("fincometax");
				   $vKind=$db->f('fkind');
				   $vDeb=$db->f("fdebit");
				   $vBal=$db->f("fbalance");
				   $vRef=$db->f("fref");
				   
				   if (in_array($vKind,array('uni','proshare','peringkat'))) {
				       $vFullNex=$vCred / (1 - ($vByy/100));
					   $vFullAll=$vFullNex;
					   $vFullRO=0;
					   $vTax=$vFullNex * $vByy / 100;
					   $vTax=number_format($vTax,0,",",".");
					   
				   } else {
				   
					   $vFullNex=$vCred / (1 - ($vByy/100));
					//   echo "$vCred / (1 - $vByy/100)";
					   $vFullAll=$vFullNex / ( $vPersenNex / 100);
					   $vFullRO=$vFullAll * $vPersenRO / 100;
					   $vTax=$vFullNex * $vByy / 100;
					   $vTax=number_format($vTax,0,",",".");
				   }


				  

		  ?>

        <tr class="tbrekapfont">

          <td  align="center"><div align="right"><?=$vNo+$vOffset?></div></td>

          <td  align="center"><?=$oPhpdate->YMD2DMY($vDateList,"-")?></td>

          <td  align="center"><div align="left"><a onClick=" //showDet('<?=$oPhpdate->YMD2DMY($vDateList,"-")?>', '<?=$vDesc?>','<?=number_format($vCred,0,",",".");?>','<?=number_format($vDeb,0,",",".");?>','<?=number_format($vFullNex,0,",",".");?>','<?=number_format($vFullRO,0,",",".");?>','<?=number_format($vFullAll,0,",",".");?>','<?=$vTax?>')" href="#" data-toggle="modalx" data-target="#detailModalx" >

                <?

                   if (!in_array($vKind,array('koreksi','resetday')))

                    //   $vDesc .= " (nett - tax $vByy%)";
                       $vDesc=str_replace("[]","[$vFunder]",$vDesc);

                   echo $vDesc. " [$vFunder]";    

                

                ?>

            </a></div></td>

          <td align="center"><div align="right">

            <?=number_format($vCred+$vTaxPph,0,",",".");$vTotBon+=$vCred+$vTaxPph;?>

          </div></td>

          <td align="center"><div align="center">
            <?=$vRef?>
            
          </div></td>

          <td align="center" class="hide"><div align="right">

<?=number_format($vBal,0,",",".");?>

</div></td>

        </tr>

        <? } ?>
        <tr class="tbrekapfont">
          <td colspan="3"  align="right"><strong>Total :</strong></td>
          <td align="center"><div align="right">
            <strong>
            <?=number_format($vTotBon,0,",",".");$vTotBon+=$vCred;?>
          </strong></div></td>
          <td align="center"><div align="right"></div></td>
          <td align="center" class="hide">&nbsp;</td>
        </tr>

      </table>

      </div>

        <p align="right" style="font-family:Arial, Helvetica, sans-serif;font-size:14px"><strong>

		Page :</strong>

		<select style="width:65px" class="form-control" name="lmPage1" id="lmPage1" onChange="document.demoform.lmPage.value=document.demoform.lmPage1.value; document.demoform.submit()">

            <? for ($i=1;$i<=$vJumPage;$i++) {?>

			<option value="<?=$i?>" <? if ($vPage==$i) echo "selected";?>>-<?=$i?>-</option>

			<? } ?>

        </select></p>

  </form>
 

  <button style="margin-left:2em" class="btn btn-info btn-sm" onClick="document.location.href='../manager/getexcel.php?arr=weekstate&file=weekly_bonus_<?=$vUserChoosed?>'"><i class="fa fa-file-text-o"></i> Export Excel</button> 
  <?
  																														 //function showDetail(pTgl,pDesc,pCred,pDeb,pFullNex,pFullRO,pFullAll,pTax)
  ?>
  <button  data-toggle="modal" data-target="#detailModal" style="margin-left:2em" class="btn btn-success btn-sm" onClick="showDetail('<?=$vFilterText?>','Weekly Bonus&nbsp;','<?=number_format($vTotBon,0,",",".")?>','0','<?=number_format($vFullBonus,0,",",".")?>','<?=number_format($vFullRO,0,",",".")?>','<?=number_format($vFullAll,0,",",".")?>','<?=$vTax?>')"><i class="fa fa-bars"></i> Detail Statement</button> 



</section>
    <!-- /.content -->
  </div>



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



<?

           include_once("../framework/member_footside.blade.php");

?>

