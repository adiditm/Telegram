<? include_once("../framework/outer_headside.blade.php")?>
<?php
//print_r($_POST);
       session_start();
        include_once("../server/config.php");
        require_once '../simage/securimage.php';
        require_once '../classes/systemclass.php';
		require_once '../classes/ruleconfigclass.php';
 	$vUserPassGO=$oRules->getSettingByField('fuserpassgosms');
	$vUserPassGO = explode("/",$vUserPassGO);
	$vUserGO = $vUserPassGO[0];
	$vPassGO = $vUserPassGO[1];

 if ($_POST['hPost']=='1') {
  // print_r($_POST);   
//   print_r($_SESSION);
   if ($_POST['ct_captcha'] != $_SESSION['securimage_code_value']['default']) {
      $oSystem->jsAlert('Security Code does not match!');
      $oSystem->jsLocation('../main/forgotpass.php');
      exit;
   } else {
	  $vUserPost=$_POST['tfUser'];
	  $vPassPost=$_POST['tfNewPass'];
	  $vPasswd=$oSystem->doED('encrypt',$vPassPost);
	  $vSQL="select fpassword,fnohp from m_anggota where fidmember='$vUserPost'";
	  $db->query($vSQL);
	  $db->next_record();
	  $vPass=$db->f('fpassword');
	  $vHP=$db->f('fnohp');
	  $vPasswd=$oSystem->doED('decrypt',$vPass);
	  $oSystem->sendSMS($vHP,"Your password for Onotoko MLM System is $vPasswd".". Thank You",$vUserGO,$vPassGO);
	     $oSystem->jsAlert('Success, password was sent to HP Number!');
	     $oSystem->jsLocation('../login.php');

   }
   
 
 }
 
 ?>

  
<style type="text/css">


input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance:textfield;
}
</style>


		<div id="content" class="content" style="margin-top:10em">
			<ol class="breadcrumb pull-right">
				<br><br>
                <h4>
				  <li><a href="javascript:;">Forgot Password</a></li></h4>
				
			</ol>
	<h1 class="page-header">&nbsp;</small></h1>

<form name="frmForgot" id="frmForgot" method="post" >     
 <div class="login-wrap col-lg-6">
          <input style="border-width:1px; border-color:#ccc" type="text" name="tfUser" id="tfUser" class="form-control" placeholder="Username" autofocus onblur="this.value=this.value.toUpperCase();">
           <br> <input style="border-width:1px; border-color:#ccc;background-color:#EAEAEC;font-size:small" type="text" name="tfIdent" id="tfIdent" class="form-control" onblur="getName(document.getElementById('tfUser').value,document.getElementById('tfIdent').value)" placeholder="HP No. Contoh: 628123456781" >
			<br><input style="border-width:1px; border-color:#ccc;" type="text" name="tfNama" id="tfNama" class="form-control" placeholder="Your Name" readonly>
			<input type="hidden" name="hPost" id="hPost" value="1" />
     
            

            

                     <div style="display: inline;">
<br>
					<?php 
							require_once '../simage/securimage.php';
							$options = array();
							$options['input_name'] = 'ct_captcha'; // change name of input element for form post

							if (!empty($_SESSION['ctform']['captcha_error'])) {
								// error html to show in captcha output
								$options['error_html'] = $_SESSION['ctform']['captcha_error'];
							}
							echo Securimage::getCaptchaHtml($options);							
                    ?>  

                    </div>                              



<br>
			<div class="col-lg-3" style="margin-left:-1em;padding-bottom:2em">
            <button id="btnSubmit" name="btnSubmit" class="btn btn-login btn-block" type="button" onClick="document.frmForgot.submit();" disabled="">
                Submit
            </button> <br> 
            </div>

            <div class="registration" style="color:#00f">
              
                           </div>
    <br>
            <!--
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                </span>
            </label> -->

        </div>

        <!-- Modal -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Forgot Password ?</h4>
                    </div>
                    <div class="modal-body">
                        <p>Enter your e-mail address below to reset your password.</p>
                        <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                        <button class="btn btn-primary" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal -->

    </form>



</div>



<!-- Placed js at the end of the document so the pages load faster -->

<!-- Placed js at the end of the document so the pages load faster -->
<script src="../js/jquery-1.10.2.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/modernizr.min.js"></script>
<script language="javascript">
function checkValid() {
   if (document.getElementById('captcha_code').value  == '') {
      alert('Please fill Security Code!');
	  return false;	
   } else {
      if (confirm('Are you sure to retrieve your password?'))
       return true;
      else return false;
   }

}


function getName(pUser,pIdent) {
   var vURL='../main/mpurpose_ajax.php?op=forgotpass&ident='+pIdent+'&user='+pUser;
   $.get(vURL,function(data){
       if(data.trim() != 'failed') {
          $('#tfNama').val(data); 
         // document.getElementById('tfNewPass').readOnly=false;
          //document.getElementById('tfConfirmPass').readOnly=false;
		  document.getElementById('btnSubmit').disabled=false;


           alert('HP Number was match, enter security code and please  submit !');
       } else {
          if (pUser.trim() =='' || pIdent.trim() =='') {
              ;
            } else  {
               alert('Username or HP No. not match!');
	         //  document.getElementById('tfNewPass').readOnly=true;
	           //document.getElementById('tfConfirmPass').readOnly=true;
	            document.getElementById('btnSubmit').disabled=true;

            }
          
       }
   });
}
var isMobile = false; 
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;
   $(document).ready(function(){
       var $target = $('html,body'); 
	   if (!isMobile) {	
		    $target.animate({scrollTop: $target.height()}, 300);   
		}
   });
   
</script>  
	</div>
	<!-- end page container -->
	
<? include_once("../framework/outer_bottomjs.blade.php")?>	
	<script>
		$(document).ready(function() {
			App.init();
			Dashboard.init();
		});
	</script>

<? include_once("../framework/outer_footside.blade.php") ; ?>
