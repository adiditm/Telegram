<?
  include_once "../server/config.php";
  include_once CLASS_DIR."networkclass.php";
 // echo "Resetting tables...!";
  $vOP=$_GET['uOP'];
  if ($vOP=="reset") {
	  echo "Resetting Pair & CF data...!";
	  $vsql="delete from tb_mutasi where fkind='pairing' ;"; 
	  $db->query($vsql);


	  $vsql="delete from tb_mutasi where fkind='resetday' ;"; 
	  $db->query($vsql);

	  $vsql="delete from tb_mutasi_wprod where fkind='pairing';"; 
	  $db->query($vsql);


	  $vsql="delete from tb_mutasi_wkit where fkind='pairing';"; 
	  $db->query($vsql);


	  $vsql="delete from tb_mutasi_wacc where fkind='pairing';"; 
	  $db->query($vsql);

	  $vsql="truncate table tb_kom_couple;";
	  $db->query($vsql);	  
	  $vsql="truncate table tb_kom_coupcf;";
	  $db->query($vsql);	  

	  echo "<BR>Member pair & CF resetted!!";
	  
	//  $vSQL="update m_anggota set fsaldovcr=0 where fidmember not in (select fidmember from tb_mutasi);";
	//  $db->query($vSQL);
	 /* 
	  $vSQL="select a.*, b.fbalance from (select max(fidsys) as  idsys , fidmember from tb_mutasi group by fidmember) as a left join tb_mutasi b on a.idsys=b.fidsys"; 
	  $db->query($vSQL);
	  while ($db->next_record()) {
	     $vMember= $db->f('fidmember');
	     $vBalance= $db->f('fbalance');
	     $vSQLin="update m_anggota set fsaldovcr = $vBalance where fidmember='$vMember'";
	     $dbin->query($vSQLin);
	 // echo "<br>";
	  }


	  $vSQL="select a.*, b.fbalance from (select max(fidsys) as  idsys , fidmember from tb_mutasi_ro group by fidmember) as a left join tb_mutasi_ro b on a.idsys=b.fidsys"; 
	  $db->query($vSQL);
	  while ($db->next_record()) {
	     $vMember= $db->f('fidmember');
	     $vBalance= $db->f('fbalance');
	     $vSQLin="update m_anggota set fsaldoro = $vBalance where fidmember='$vMember'";
	     $dbin->query($vSQLin);
	 // echo "<br>";
	  }
	  */
  }
  
?>