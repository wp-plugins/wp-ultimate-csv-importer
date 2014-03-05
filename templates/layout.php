<!-- Fix for div shown up at the footer of the wordpress-->
<style> #ui-datepicker-div { display:none } </style>
<div id = 'notification_wp_csv'> </div>
<?php
 	$impCEM = CallWPImporterObj::getInstance();
     	$impCEM->renderMenu();
	if(isset($_REQUEST['action'])){
		$impCEM->requestedAction($_REQUEST['action'],isset($_REQUEST['step']));
	}
	else if(isset($_REQUEST['__module']))
	{
		print_r($skinny_content);
	}
	else
	{
		echo "<div align='center' style='width:100%;'> <p class='warnings' style='width:50%;text-align:center;color:red;'>This feature is only available in PRO!.</p></div>";
	}

?>
