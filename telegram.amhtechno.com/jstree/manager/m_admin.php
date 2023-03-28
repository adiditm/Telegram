<? include_once("../framework/masterheader.php")?>
<!-- <style type="text/css">
	hr.pme-hr		     { border: 0px solid; padding: 0px; margin: 0px; border-top-width: 1px; height: 1px; }
	table.pme-main 	     { border: #004d9c 1px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	table.pme-navigation { border: #004d9c 0px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	td.pme-navigation-0, td.pme-navigation-1 { white-space: nowrap; }
	th.pme-header	     { border: #004d9c 1px solid; padding: 4px; background: #add8e6; }
	td.pme-key-0, td.pme-value-0, td.pme-help-0, td.pme-navigation-0, td.pme-cell-0,
	td.pme-key-1, td.pme-value-1, td.pme-help-0, td.pme-navigation-1, td.pme-cell-1,
	td.pme-sortinfo, td.pme-filter { border: #004d9c 1px solid; padding: 3px; }
	td.pme-buttons { text-align: left;   }
	td.pme-message { text-align: center; }
	td.pme-stats   { text-align: right;  }
</style> -->

<style type="text/css">
  table {
	  table;
  }
</style>
<body class="sticky-header">

<section>
    <!-- left side start-->
   <? include "../framework/leftmem.php"; ?>
    <!-- main content start-->
    <div class="main-content" >

   <? include "../framework/headermem.php"; ?>
   
    <section class="wrapper">
    <h4 >&nbsp;&nbsp;&nbsp;User Admin Editor</h4>
<?php


// MySQL host name, user name, password, database, and table
$opts['hn'] = 'localhost';
$opts['un'] = 'unstyle_uneeds';
$opts['pw'] = 'un33d5';
$opts['db'] = 'unstyle_uneeds';
$opts['tb'] = 'm_admin';

// Name of field which is the unique key
$opts['key'] = 'fidmember';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'string';

// Sorting field(s)
$opts['sort_field'] = array('fidmember');

// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = 15;

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'ACPVDF';

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => true,
	'tabs'  => true
);

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

/* Get the user's default language and use it if possible or you can
   specify particular one you want to use. Refer to official documentation
   for list of available languages. */
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '-UTF8';


$opts['fdd']['fidmember'] = array(
  'name'     => 'ID Member',
  'select'   => 'T',
  'maxlen'   => 10,
  'sort'     => true
);
$opts['fdd']['fnama'] = array(
  'name'     => 'Nama',
  'select'   => 'T',
  'maxlen'   => 50,
  'sort'     => true
);
$opts['fdd']['fpassword'] = array(
  'name'     => 'Password',
  'select'   => 'T',
  'maxlen'   => 254,
  'sort'     => true
);
$opts['fdd']['fpriv'] = array(
  'name'     => 'Privilege',
  'select'   => 'T',
  'maxlen'   => 25,
  'default'  => 'administrator',
  'sort'     => true
);
$opts['fdd']['faktif'] = array(
  'name'     => 'Aktif',
  'select'   => 'T',
  'maxlen'   => 1,
  'default'  => '',
  'sort'     => true
);
$opts['fdd']['fpin'] = array(
  'name'     => 'PIN',
  'select'   => 'T',
  'maxlen'   => 20,
  'sort'     => true
);
$opts['fdd']['fserno'] = array(
  'name'     => 'Serial No',
  'select'   => 'T',
  'maxlen'   => 20,
  'sort'     => true
);
$opts['fdd']['fstatusrow'] = array(
  'name'     => 'Status',
  'select'   => 'T',
  'maxlen'   => 12,
  'default'  => '0',
  'sort'     => true
);

// Now important call to phpMyEdit
require_once '../classes/phpmyedit.class.php';
new phpMyEdit($opts);

?>
 </section>
    <? include "../framework/footer.php";?>
        <!--footer section end-->


    </div>
    <!-- main content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="../js/jquery-1.10.2.min.js"></script>
<script src="../js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="../js/jquery-migrate-1.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/modernizr.min.js"></script>
<script src="../js/jquery.nicescroll.js"></script>

<!--common scripts for all pages-->
<script src="../js/scripts.js"></script>

</body>
</html>