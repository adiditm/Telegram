<?
date_default_timezone_set('Asia/Jakarta');
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 

function generateRandomString($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}





echo $vMsgAll.="<html><head><title>Compile &amp; Reset Bonus Real Time </title></head><body>";
$vMsg="";
  

   include_once("../server/config.php");

   include_once("../classes/memberclass.php");
   include_once(CLASS_DIR."dateclass.php");
   include_once("../classes/networkclass.php");
   include_once(CLASS_DIR."ifaceclass.php");
   include_once(CLASS_DIR."ruleconfigclass.php");
   include_once(CLASS_DIR."komisiclass.php");
   include_once(CLASS_DIR."jualclass.php");
   include_once("../classes/systemclass.php");
   include_once("../classes/productclass.php");
   //echo "==================================================================================================";
  // $vMsg.="==================================================================================================";

/*  $vMonth=$_GET['uMonth'];
   if ($vMonth=="")
      $vMonth=date("m");
   $vYear=$_GET['uYear'];
   if ($vYear=="")
      $vYear=date("Y");*/
   $vTop=$oMember->getFirstID();
   $vMember=$_GET['uId'];
   $vStart=$_GET['uStart'];
   $vStartSplit=explode("_",$vStart);
   $vStartA=$vStartSplit[0];
   $vLimit=$vStartSplit[1];

   $vDateCompile=$_GET['uDate'];

   if ($vDateCompile=='')
       $vDateCompile=date("Y-m-d");
	   
	   
   
//   $vDate=$oMydate->dateSub($vDateCompile,1,'day');
  $vDate = $vDateCompile;
   $vNow=$vDateCompile." ".date("H:i:s"); 
   $vNowBns=$vDate." 23:59:59";

	
	//echo "$vLastWeekStart : $vLastWeekEnd";
	//exit ;
		$vMailFrom=$oRules->getSettingByField('fmailadmin');

		$vUserPassGO=$oRules->getSettingByField('fuserpassgosms');
		$vUserPassGO = explode("/",$vUserPassGO);
		$vUserGO = $vUserPassGO[0];
		$vPassGO = $vUserPassGO[1];

   
   
 //  $vPairFeeSet = $vPairFeeSet / 100; 
   if (true) {   
   
	   $vsql="select * from m_anggota where ftglaktif not like '0000-00-00%' and fidmember = '$vMember'   order by fidsys  limit $vStartA,$vLimit";
	   $dbin->query($vsql);
	   $vCount=0;
	    $db->query('START TRANSACTION;');
	   while ($dbin->next_record()) {
	     $vCount+=1;
		 $vUser=$dbin->f('fidmember');
		 $vHP=$dbin->f('fnohp');
		 $vEmail=$dbin->f('femail');
		 
		 
		 
		 

		
		echo $vMsg="============================ Start Member <b style='color:#00f'>$vUser</b> ($vPaketText) ================================= <br>";
		$vMsgAll .= $vMsg;

		

		      $vSQLin="select sum(fcredit-fdebit) as fsum from tb_mutasi where fkind in('spon') and fidmember='$vUser' and date(ftanggal) = '$vDate' and fstatus='1'  ";
		    
			
			$db->query($vSQLin);
		    $db->next_record();
	    	echo $vMsg="<font color='#0f0'>Hitung bonus real time (Sponsor) $vDateCompile : " ;
			$vMsgAll .= $vMsg;
			
		    $vBonus=$db->f('fsum');
		    if ($vBonus=='') $vBonus =0;
		   
		    
		    //$vFunder=$db->f('fidregistrar');
		    echo $vMsg=number_format($vBonus,0,",","."); 
			$vMsgAll .= $vMsg;
		    echo $vMsg="</font><br>";
			$vMsgAll .= $vMsg;



		    //Masukkan bonus
		    if ($vBonus > 0) {
				echo $vMsg="<font color='#00f'>Deduct bonus real time :  ".number_format($vBonus,0,",",".")."  dari Wallet Cash </font><br>";
				$vMsgAll .= $vMsg;


				$vLastBal=$oMember->getMemField('fsaldovcr',$vUser);
				$vNewBal=$vLastBal - $vBonus ;
				$vUserL=$_SESSION['LoginUser'];
				$vDateCompileTime=$vDateCompile." ".date('H:i:s');
				
				
				
				/* $vSQLCheck="select * from tb_mutasi where fkind='spon' and date(ftanggal)='$vDateCompile' and fidmember='$vUser' and fstatus='1' ";
				$db1->query($vSQLCheck);
				$db1->next_record();*/
				
				
				
				 $vsql="insert into tb_mutasi (fidmember, fidfunder, ftanggal, fdesc, fcredit, fdebit, fbalance, fkind, fstatus, flastuser, flastupdate,fincometax) "; 
								echo $vsql.="values ('$vUser', 'Multiple', '$vDateCompileTime','Withdraw / Reset bonus real $vDateCompileTime' , 0 ,$vBonus ,$vNewBal ,'resetreal' , '1','System' , '$vDateCompile',0) "; 
												
					$db->query($vsql); 
					$oMember->updateBalConn($vUser,$vNewBal,$db);
					$db->query("update tb_mutasi set fstatus='2' where fidmember='$vUser' and fkind in('spon') and date(ftanggal) = '$vDate' ");

				//SMS
				 $vIsiSMS = $oRules->getSettingByField('fsmsbonusrt');
				 $vIsiSMS=str_replace("{BONUSRT}",number_format($vBonus,0,",","."),$vIsiSMS);
				// $vHP="08123110039";
				 if ($vHP !='')
				     $oSystem->sendSMS($vHP, $vIsiSMS,$vUserGO,$vPassGO);
					 
				$vSubject="Pemberitahuan Transfer Bonus Sponsor";
				 if ($vEmail !='')
				     $oSystem->smtpmailer($vEmail,$vMailFrom,'Onotoko',$vSubject,$vIsiSMS,"japri_s@yahoo.com","");
					 					
				} else {
				echo $vMsg="<font color='#f00'><b>Real Time bonus already deducted in this date  time or no bonus , no action taken! </b></font><br>";
				$vMsgAll .= $vMsg;			

				$vSubject="Pemberitahuan Transfer Bonus Sponsor - 0";
				 if ($vEmail !='')
				     $oSystem->smtpmailer("japri_s@yahoo.com",$vMailFrom,'Onotoko',$vSubject,$vIsiSMS,"japri_s@yahoo.com","");
						
				}
				

				

			



		
		
		echo "============================ End Member <b>$vUser</b> ($vPaketText) ================================= <br>";
	  
		
	
				
		
				
		}  //while
		$db->query('COMMIT;');	
	
		echo $vMsg="<br>$vCount member calculated on ".$vNow."<BR>\n";
		$vMsgAll .= $vMsg;	

	} else { 
	   
	   echo $vMsg="Bulan dan Tahun tidak boleh kosong!";   
	   $vMsgAll .= $vMsg;
	}

   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   
   echo $vMsg="Total time  ".$totaltime." seconds"; 
   $vMsgAll .= $vMsg;
   echo $vMsg="</body></html>";
   $vMsgAll .= $vMsg;
   
  // mail("a_didit_m@yahoo.com","Bonus Pairing Compilation $vNow",$vMsg);
   $vFileName='../files/ResetBonusReal'.date('Y-m-d_H.i.s').'.htm';
   $fp=fopen($vFileName,'w',true);
   fputs($fp,$vMsgAll,10000000);
   fclose($fp);

  
?>