<div align=center style="padding-top:220px;">
	<form name="upgrade_to_latest" method="post">
		<label style="font-size:2em;" id="step1">Upgrade to Latest Version 3.6</label>
		<input type="submit" class="btn btn-primary btn-sm" name="upgrade" id="upgrade" value="Click Here"/>
	</form>
	<form name="goto_plugin_page" method="post"
		  action="admin.php?page=<?php echo WP_CONST_ULTIMATE_CSV_IMP_SLUG; ?>/index.php&__module=settings">
		<label style="font-size:2em;display:none;" id='upgrade_state'>Upgrade is inprogress...</label>
		<input type="submit" style="display:none;" class="btn btn-success" name="gotopluginpage" id="gotopluginpage"
			   value="Goto Plugin Settings"/>
	</form>
</div>
<?php
if (isset($_POST['upgrade'])) {
	?>
	<script>
		document.getElementById('step1').style.display = 'none';
		document.getElementById('upgrade').style.display = 'none';
		document.getElementById('upgrade_state').style.display = '';
		document.getElementById('gotopluginpage').style.display = '';
	</script>
	<?php
	global $wpdb;
                $check_table1 = 'smackcsv_pie_log';
                if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . $check_table1 . "'")) != 1) {
		$sql1 = "CREATE TABLE `$check_table1` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `type` varchar(255) DEFAULT NULL,
                        `value` int(11) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB;";
		$wpdb->query($sql1);
                }
                $check_table2 = 'smackcsv_line_log';
                if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . $check_table2 . "'")) != 1) {

                $sql2 = "CREATE TABLE `$check_table2` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `month` varchar(60) DEFAULT NULL,
                        `year` varchar(60) DEFAULT NULL,
                        `imported_type` varchar(60) DEFAULT NULL, 
                        `imported_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `inserted` int(11) DEFAULT NULL,
                         PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB;";
                $wpdb->query($sql2);
                 }
	update_option('ULTIMATE_CSV_IMP_FREE_VERSION', '3.6');
	update_option('ULTIMATE_CSV_IMPORTER_UPGRADE_FREE_VERSION', '3.6');
	?>
	<script>
		document.getElementById('upgrade_state').innerHTML = 'Upgrade Completed! ';
	</script>
<?php
}
?>
