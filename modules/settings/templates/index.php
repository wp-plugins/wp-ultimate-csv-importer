<div id="ShowMsg" style="display:none;"><p id="warning-msg" class="alert alert-warning"><?php echo $skinnyData['plugStatus'];?></p></div>
<?php if(isset($skinnyData['savesettings']) && $skinnyData['savesettings'] == 'done'){ ?>
<div id="deletesuccess"><p class="alert alert-success">Settings Saved</p></div>
<?php
	$skinnyData['savesettings'] == 'notdone';
?>
    <script type="text/javascript"> 
      jQuery(document).ready( function() {
	jQuery('#ShowMsg').delay(2000).fadeOut();
	jQuery('#ShowMsg').css("display", "none");
        jQuery('#deletesuccess').delay(2000).fadeOut();
      });
    </script>
<?php
} ?>
<form class="add:the-list: validate" action="" name="importerSettings" method="post" enctype="multipart/form-data">
	<div class="container-fluid">
		<div class="accordion" id="accordion2">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> MODULES </a>
				</div>
				<div id="collapseTwo" class="accordion-body in collapse">
					<div class="accordion-inner">
						<div id = 'moduleBox' class = 'switchercontent newboxes2'>
							<table>
							<tr>
								<td>
								<ul>
									<li>
										<label class="<?php echo $skinnyData['post']; ?>"><input type='checkbox' name='post' id='post' value='post' <?php echo $skinnyData['post']; ?> onclick="check_if_avail(this.name);" > Post </label>
									<label class="<?php echo $skinnyData['users']; ?>" ><input type='checkbox' name='users' id='users' value='users' <?php echo $skinnyData['users']; ?> onclick="check_if_avail(this.name);" > Users/Roles </label>									
									<label class="<?php echo $skinnyData['page']; ?>"><input type='checkbox' name='page' id='page' value='page' <?php echo $skinnyData['page']; ?> onclick="check_if_avail(this.name);" > Page </label>
										<label class="<?php echo $skinnyData['comments']; ?>"><input type='checkbox' name='comments' id='comments' value='comments' <?php echo $skinnyData['comments']; ?> onclick="check_if_avail(this.name);" > Comments </label>
										<label class="<?php echo $skinnyData['categories']; ?>"><input type='checkbox' name='categories' id='categories' value='categories' <?php echo $skinnyData['categories']; ?> onclick="check_if_avail(this.name);" > Categories/Tags </label>
										<label class="<?php echo $skinnyData['customtaxonomy']; ?>" ><input type='checkbox' name='customtaxonomy' id='customtaxonomy' value='customtaxonomy' <?php echo $skinnyData['customtaxonomy']; ?> onclick="check_if_avail(this.name);" > Custom Taxonomy </label>

 <label class="<?php echo $skinnyData['custompost']; ?>"><input type='checkbox' name='custompost' id='custompost' value='custompost' <?php echo $skinnyData['custompost']; ?> onclick="check_if_avail(this.name);" > Custom Post </label>

	<label style='color:red'> Note: Supports Wordpress Custom Post by default. For Custom Post Type UI plugin enable it under supported 3rd party plugins<label></li>
								</ul>
								</td>
							</tr>
							</table>
<!--							<input type='hidden' name='post' value='post' />
							<input type='hidden' name='custompost' value='custompost' />
							<input type='hidden' name='page' value='page' />-->
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree"> THIRD PARTY PLUGINS </a>
				</div>
				<div id="collapseThree" class="accordion-body collapse">
					<div class="accordion-inner">
						<div id='thirdPartyBox' class = 'switchercontent newboxes2'>
							<table class='supportedplugins'>
								<tr class='typeofplugin'>
									<td class="plugintype" colspan=4> <b> Ecommerce </b> </td>
								</tr>
								<tr>
									<td> 
										<label class="<?php echo $skinnyData['nonerecommerce']; ?>"><input type = 'radio' name ='recommerce' id='nonerecommerce' value='nonerecommerce' <?php echo $skinnyData['nonerecommerce']; ?> class='ecommerce' checked> None </label>
									</td>
									<td>
										<label class="<?php echo $skinnyData['eshoptd'].' '.$skinnyData['eshop']; ?>">
										<input type='radio' name='recommerce' id='eshop' value='eshop' <?php echo $skinnyData['eshop']; ?> class='ecommerce' onclick='check_if_avail(this.id);'> Eshop
										</label>
									</td>
									<td> 
										<label class="<?php echo $skinnyData['wpcomtd'].' '.$skinnyData['wpcommerce']; ?>">
										<input type='radio' name='recommerce' id='wpcommerce' value='wpcommerce' <?php echo $skinnyData['wpcommerce']; ?>  class = 'ecommerce' onclick='check_if_avail(this.id);'> WP e-Commerce </label>
									</td>
									<td>			
										<label class="<?php echo $skinnyData['woocomtd'].' '.$skinnyData['woocommerce']; ?>"><input type='radio' name='recommerce' id='woocommerce' value='woocommerce' <?php echo $skinnyData['woocommerce']; ?>  class = 'woocommerce' onclick='check_if_avail(this.id);'> WooCommerce </label>
									</td>
								</tr>
<!-- WP e-Commerce Custom Fields support -->
							       	<tr id='wpcustomfieldstr'>
									<td></td><td></td>
									<td><input type='checkbox' name='wpcustomfields' id='wpcustomfields' onclick='check_if_avail(this.id);'/> WP e-Commerce Custom Fields </td>
								</tr>
							<tr class='typeofplugin'><td colspan=4><b> Custom Post and Custom Fields </b></td></tr>
							<tr>
								<td><label class="<?php echo $skinnyData['nonercustompost']; ?>" ><input type = 'radio' name ='rcustompost' id='nonercustompost' value='nonercustompost' <?php echo $skinnyData['nonercustompost']; ?> class='ecommerce' onclick="check_if_avail(this.id);" > Default </label></td>
								<td><label class="<?php echo $skinnyData['cptutd'].' '.$skinnyData['custompostuitype'];?>" ><input type ='radio' name = 'rcustompost' id='custompostuitype' value='custompostuitype' <?php echo $skinnyData['custompostuitype']; ?> > Custom Post Type UI </label></td>
								<td><label class="<?php echo $skinnyData['cctmtd'].' '.$skinnyData['cctm'];?>" ><input type ='radio' name = 'rcustompost' id='cctm' value='cctm' <?php echo $skinnyData['cctm']; ?> onclick="check_if_avail(this.id);" > CCTM </label></td>
								<td><label class="<?php echo $skinnyData['acftd'].' '.$skinnyData['acf'];?>" ><input type ='checkbox' name = 'rcustomfield' id='acf' value='acf' <?php echo $skinnyData['acf']; ?> onclick="check_if_avail(this.id);" > ACF </label></td>
							</tr>
							<tr class='typeofplugin'>
								<td colspan=4><b> SEO Options </b></td>
							</tr>
							<tr>
								<td><label class="<?php echo $skinnyData['nonerseooption'];?>" ><input type = 'radio' name ='rseooption' id='nonerseooption' value='nonerseooption' <?php echo $skinnyData['nonerseooption']; ?> class='ecommerce' onclick="check_if_avail(this.id);" > None </label></td>
								<td><label class="<?php echo $skinnyData['aioseotd'].' '.$skinnyData['aioseo']; ?>" ><input type ='radio' name = 'rseooption' id='aioseo' value='aioseo' <?php echo $skinnyData['aioseo']; ?> onclick="check_if_avail(this.id);" > All-in-SEO </label></td>
								<td><label class="<?php echo $skinnyData['yoasttd'].' '.$skinnyData['yoastseo']; ?>" ><input type ='radio' name = 'rseooption' id='yoastseo' value='yoastseo' <?php echo $skinnyData['yoastseo']; ?> onclick="check_if_avail(this.id);" > Yoast SEO </label></td>
							</tr>
							<tr class='typeofplugin'>
								<td colspan=4><b> Category Icons </b></td>
							</tr>
							<tr>
								<td><label class="<?php echo $skinnyData['enable'];?>" ><input type = 'radio' name ='rcateicons' id='caticonenable' value='enable' <?php echo $skinnyData['enable']; ?> class='ecommerce' onclick="check_if_avail(this.id);"> Enable </label> </td>
								<td>
									<label><input type ='radio' name = 'rcateicons' id = 'caticondisable' value='disable' <?php echo $skinnyData['disable']; ?> checked > Disable </label></td>
							</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"> FEATURES </a>
				</div>
				<div id="collapseOne" class="accordion-body collapse" style="height: 0px; ">
					<div class="accordion-inner">
						<div id='featuresBox' class = 'switchercontent newboxes2'>
							<table>
							<tr>
								<td>
									<label class=$automapping>
										<input type='checkbox' name='automapping' id='automapping' value='automapping' <?php echo $skinnyData['automapping']; ?> onclick="check_if_avail(this.id);" >
										Enable Auto Mapping
									</label>
								</td>
								<td>
									<label class=$utfsupport><input type='checkbox' name='rutfsupport' id='utfsupport' value='utfsupport' <?php echo $skinnyData['utfsupport']; ?> onclick="check_if_avail(this.id);" >
									Enable UTF Support</label>
								</td>
							</tr>
							</table>
						</div>

					</div>
				</div>
			</div>
			<button type='submit' class='action btn btn-primary' name='savesettings' value='Save' style='float:right;' onclick="saveSettings();">Save</button>
		</div>
	</div>
</form>
