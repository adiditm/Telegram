<?
session_start();
ini_set('display_errors', true);
error_reporting(E_ERROR);
include_once("../server/config.php");

//print_r($_POST);
   include_once(CLASS_DIR."memberclass.php");
   include_once(CLASS_DIR."dateclass.php");
   include_once(CLASS_DIR."networkclass.php");
   include_once(CLASS_DIR."ifaceclass.php");
   include_once(CLASS_DIR."ruleconfigclass.php");
  include_once(CLASS_DIR."komisiclass.php");
   include_once(CLASS_DIR."jualclass.php");
   include_once(CLASS_DIR."systemclass.php");
   include_once(CLASS_DIR."productclass.php");
   include_once(CLASS_DIR."texttoimageclass.php");
   
  // echo "xxx".$oProduct->getColor('C-0001');
  
  while (list($key,$val)=each($_POST)) {
      $$key = $val;
   }
   $vTot=0;
   
   $vSQL = "select * from m_product where fidproduk='$lmKode'";
   $db->query($vSQL);
   $db->next_record();
   $vNama=$db->f('fnamaproduk');
   $vOp=$_POST['op'];
   
   if ($vOp != 'del') {
		   $vDup=0;
		   if (is_array($_SESSION['save'])) {
			   while (list($key,$val)=each($_SESSION['save'])) {
			      if ($_POST['lmKode']==$val['lmKode'] && $_POST['lmSize']==$val['lmSize'] && $_POST['lmColor']==$val['lmColor']) {
			         $vDup=1;  
		
			      }
		       }	
		    }      
		
		   if ($vDup==0) {  
		
		
					   $_SESSION['record']['lmKode']=$_POST['lmKode'];
					   $_SESSION['record']['nama']=$vNama;   
					   $_SESSION['record']['lmSize']=$_POST['lmSize'];
					   $_SESSION['record']['lmColor']=$_POST['lmColor'];
		
					   $_SESSION['record']['txtJml']=$_POST['txtJml'];
					   $_SESSION['record']['hHarga']=$_POST['hHarga'];
					   $_SESSION['record']['hSubTot']=$_POST['hSubTot'];
					   $_SESSION['save'][]=$_SESSION['record'];
			    
			   }   else {
			   
			      echo '<font color="#f00"><b>Kode produk '.$lmKode.' dengan ukuran '.$lmSize.' dan warna '.$lmColor.' sudah ada dalam list item Anda, silakan hapus dan tambahakan lagi jika ingin mengoreksi Ukuran dan atau Jumlah!</b></font>';
			   
			   }
	} else {
	//delete
					   $vArrDel='';		//   $_SESSION['record']['lmKode']=$_POST['delKode'];
			  		   $vDelno=$_POST['delNo'];
 					   $_SESSION['record']['lmKode']=$_POST['delKode'];
					   $_SESSION['record']['nama']=$_POST['delNama'];
  
					   $_SESSION['record']['lmSize']=$_POST['delSize'];
					   $_SESSION['record']['lmColor']=$_POST['delColor'];
		
					   $_SESSION['record']['txtJml']=$_POST['delJml'];
					   $_SESSION['record']['hHarga']=$_POST['delHarga'];
					   $_SESSION['record']['hSubTot']=$_POST['delSubTot'];
					   $vArrDel[$vDelno]=$_SESSION['record'];


	  
	  $vArrDiff = array_diff_assoc($_SESSION['save'],$vArrDel);	  
	  $_SESSION['save']=$vArrDiff;

	  $vCountArrBaru = count( $_SESSION['save']);
	  
	  //Re Index
	  $vArrTemp="";
      while (list($key,$val)=each($_SESSION['save'])) {
         $vArrTemp[]=$val;
         
      }
	  
     $_SESSION['save']=$vArrTemp;
     if (trim($_POST['delKode']) =='' )
        unset( $_SESSION['save']);
  
	
	}
    
   
   
   
   

   
   
//print_r($_SESSION['save']);
    $_SESSION['savestock']=$_SESSION['save'];

?>
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
                                 <th style="width: 45px">QOH</th>

                                <th width="12%">Sub Total</th>
                                <th width="12%">&radic;</th>
                            </tr>
                            </thead>
                            <tbody>
 <tr id="trAdd" style="display:">
                                <th style="width: 33px; height: 30px;"></th>
                                <th style="width: 208px; height: 30px;">
                                

							    <select onChange="selectProd(this)" name="lmKode" id="lmKode" class="form-control" style="display:none;width:140px;max-width:140px">
								
								<option value="" selected="selected">---Pilih---</option>
								<?
								    $vSQL="select distinct a.fidmember, a.fidproduk, a.fsize, a.fcolor, a.fbalance, b.fnamaproduk, b.fhargajual1 from tb_stok_position a left join m_product b on a.fidproduk=b.fidproduk  where  b.faktif='1' and a.fidmember='$vUser' order by a.fidproduk";
								    $db->query($vSQL);
								    $vColorText="";
								    while($db->next_record()) {
								       $vCode=$db->f('fidproduk');
								       $vSize=$db->f('fsize');
								       $vColor = $db->f('fcolor');
								       $vColName=$oProduct->getColor($vColor);
								       
								       $vNama=$db->f('fnamaproduk').";$vSize;$vColor/$vColName";
									  // $vNama="$vSize;$vColor/$vColName;".$db->f('fnamaproduk');
								       $vHarga=number_format($db->f('fhargajual1'),0,"","");
								        
											$vQoh=number_format($db->f('fbalance'),0);
										
								      								       
								?>
								<option colors="<?=$vColor?>" qoh="<?=$vQoh?>"   price="<?=$vHarga?>" sizes="<?=$vSize?>" value="<?=$vCode?>" selected="selected"><?=$vCode.";".$vNama?></option>

								<? } ?>
								</select>
							
							
								
								</th>
                                <th id="thNama" style="height: 30px" ></th>
                                <th id="thUkur" style="height: 30px">
                                
                                <select name="lmSize" id="lmSize" style="display:none;min-width:80px" class="form-control">
								<option value="">---Pilih---</option>
								</select>
								
								</th>
                                <th style="height: 30px"> 
                                <select name="lmColor" id="lmColor" style="display:none;min-width:80px" class="form-control">
								<option value="">---Pilih---</option>
								</select>
                                
                                
                                </th>
                               <th style="height: 30px"> <div class="form-group col-lg-12 col-md-12 col-xs-12 "><input name="txtJml" id="txtJml" class="form-control"  type="text" dir="rtl" style="display:none;min-width:55px" size="10" onKeyUp="calcSub(this)" onBlur="calcSub(this)" ></div></th>
                                <th style="width: 162px; height: 30px;" id="thHarga"></th>
                                 <th style="width: 45px; height: 30px;" id="thQoh"></th>

                                <th align="right" id="thSubTot" style="height: 30px"></th>
                                <th align="center" id="thSubTot" style="height: 30px"><input id="btSaveRow" type="button" onClick="return doSaveRow()" class="btn btn-success btn-sm" value="Save Item" style="display:none"/></th>
                                <th style="display:none; height: 30px;"></th><input type="hidden" name="hSubTot" id="hSubTot" value="" /></th>
                            </tr>
                            <?
                                $vCount = count($_SESSION['save']);
                                $vTot=0;$vTotJum=0;$vNo=0;
                                for ($i=0;$i<$vCount;$i++) {
                                    
                            ?>
                            <tr>
                                <td><?=$i+1?></td>
                                <td style="width: 33px"><?=$_SESSION['save'][$i]['lmKode']?></td>
                                <td align="left" style="width: 208px"><?=$_SESSION['save'][$i]['nama']?></td>
                                <td align="left"><?=$_SESSION['save'][$i]['lmSize']?></td>
                                <td align="left"><?=$oProduct->getColor($_SESSION['save'][$i]['lmColor'])?></td>
                                <td align="right"><?=number_format($_SESSION['save'][$i]['txtJml'],0,",",".");$vTotJum+=$_SESSION['save'][$i]['txtJml'];?></td>
                                <td align="right"><?=number_format($_SESSION['save'][$i]['hHarga'],0,",",".")?></td>
                                <td style="width: 162px" align="right"><?=number_format($_SESSION['save'][$i]['hSubTot'],0,",",".");$vTot+=$_SESSION['save'][$i]['hSubTot'];?></td>
                                <?
                                   $sKode=$_SESSION['save'][$i]['lmKode'];
                                   $sSize=$_SESSION['save'][$i]['lmSize'];
                                   $sColor=$_SESSION['save'][$i]['lmColor'];
                                   $vNamaX=$_SESSION['save'][$i]['nama'];
                                   $vJmlX=$_SESSION['save'][$i]['txtJml'];
                                   $vHargaX=$_SESSION['save'][$i]['hHarga'];
                                   $vSubTotX=$_SESSION['save'][$i]['hSubTot'];
                                ?>
                                <td><input id="btAdd" type="button" onclick="doDel('<?=$vNo?>','<?=$sKode?>','<?=$sSize?>','<?=$sColor?>','<?=$vNamaX?>','<?=$vJmlX?>','<?=$vHargaX?>','<?=$vSubTotX?>')" class="btn btn-success btn-xs" value="&nbsp;&nbsp;-&nbsp;&nbsp; "/>
                                <? $vNo++; ?>
                                </td>
                            </tr>
                            
                            <? } ?>
                            <tr>
                                <td style="width: 33px">&nbsp;<input type="hidden"  id="hHarga" name="hHarga" value=""></td>
                                <td align="left" style="width: 208px" colspan="2"><input id="btAdd" type="button" onclick="doAdd()" class="btn btn-info btn-sm" value="Add Item +"/>&nbsp;<input type="button" onclick="doCancel()" class="btn btn-default  btn-sm" value="Cancel" id="btCancel" style="display:none"/></td>
                                <td align="left" id="tdLoad"></td>
                                <td>&nbsp;</td>
                                <td align="right"><span style="display:<? if ($vTotJum==0) echo 'none';?>"><?=number_format($vTotJum,0,",",".")?></span><input type="hidden" name="hTotJum" id="hTotJum" value="<?=$vTotJum?>" /></td>
                                <td>&nbsp;</td>
                                <td style="width: 162px" align="right"><span style="display:<? if ($vTot==0) echo 'none';?>"><?=number_format($vTot,0,",",".")?></span><input type="hidden" name="hTot" id="hTot" value="<?=$vTot?>" /></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            </tbody>
                        </table>

