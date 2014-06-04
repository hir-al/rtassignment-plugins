<?php

// ************** get array from the database **********

$rtp_selected_gallery_name = trim($_GET['name']);
$rtp_slideshow_imageupload_complete_data = unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
$rtp_slideshow_gallery_data = unserialize(get_option('rtp_slideshow_gallery_data'));
$rtp_slideshow_misc_hide_advert = get_option('rtp_slideshow_misc_hide_advert');

if ($rtp_slideshow_misc_hide_advert == "") {
	$rtp_slideshow_misc_hide_advert = "no";
}

if ($_POST['rtp_slideshow_hidden'] == 'Y') {

	// **********get the form data, validate it and update database with new value starts********

	$rtp_selected_gallery_name = trim($_GET['name']);
	$gallery_name = trim($_GET['name']);
	$show_alert = 0;
	if (is_numeric($_POST['rtp_slideshow_timespan'])) {
		$rtp_slideshow_timespan = $_POST['rtp_slideshow_timespan'];
	}
	else {
		$rtp_slideshow_timespan = 4;
		$show_alert = 1;
	}

	if (is_numeric($_POST['rtp_slideshow_fadeintime'])) {
		$rtp_slideshow_fadeintime = $_POST['rtp_slideshow_fadeintime'];
	}
	else {
		$rtp_slideshow_fadeintime = 1;
		$show_alert = 1;
	}

	if (is_numeric($_POST['rtp_slideshow_fadeouttime'])) {
		$rtp_slideshow_fadeouttime = $_POST['rtp_slideshow_fadeouttime'];
	}
	else {
		$rtp_slideshow_fadeouttime = 1;
		$show_alert = 1;
	}

	if (is_numeric($_POST['rtp_slideshow_height'])) {
		$rtp_slideshow_height = $_POST['rtp_slideshow_height'];
	}
	else {
		$show_alert = 1;
	}

	$rtp_slideshow_height_type = $_POST['rtp_slideshow_height_type'];
	if (is_numeric($_POST['rtp_slideshow_width'])) {
		$rtp_slideshow_width = $_POST['rtp_slideshow_width'];
	}
	else {
		$show_alert = 1;
	}

	$rtp_slideshow_width_type = $_POST['rtp_slideshow_width_type'];
	if ($rtp_slideshow_height != "") {
		$rtp_slideshow_height = $rtp_slideshow_height . $rtp_slideshow_height_type;
	}
	else {
		$rtp_slideshow_height = "";
	}

	if ($rtp_slideshow_width != "") {
		$rtp_slideshow_width = $rtp_slideshow_width . $rtp_slideshow_width_type;
	}
	else {
		$rtp_slideshow_width = "";
	}

	$rtp_slideshow_pauseon_hover = $_POST['rtp_slideshow_pauseon_hover'];
	if ($show_alert != 1) {
		$rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_timespan'] = $rtp_slideshow_timespan;
		$rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_fadeintime'] = $rtp_slideshow_fadeintime;
		$rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_fadeouttime'] = $rtp_slideshow_fadeouttime;
		$rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_pauseon_hover'] = $rtp_slideshow_pauseon_hover;
		$rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_height'] = $rtp_slideshow_height;
		$rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_width'] = $rtp_slideshow_width;
		update_option('rtp_slideshow_gallery_data', serialize($rtp_slideshow_gallery_data));
		update_option('rtp_slideshow_imageupload_complete_data', serialize($rtp_slideshow_imageupload_complete_data));
?>
	   <div class="updated"><p><strong><?php
		_e('Simple Slideshow Settings Saved!.', 'simple-slideshow-manager'); ?></strong></p></div>
   <?php
	}

	if ($show_alert == 1) {
		echo "<script type=\"text/javascript\">";
		echo "alert('" . __('The text you entered is in incorrect format..please enter a numeric value..', 'simple-slideshow-manager') . " ')";
		echo "</script>";
	}
}

// ********** get the form data, validate it and update database with new value ends ********

else {

	// ********** normal page display ***************

	if (is_serialized($rtp_slideshow_imageupload_complete_data)) {
		$rtp_slideshow_imageupload_complete_data = unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
	}

	$rtp_slideshow_gallery_data = unserialize(get_option('rtp_slideshow_gallery_data'));
	if (($_GET['rename_status']) == "yes") {
?>
<script type="text/javascript">
alert('<?php
		_e('Gallery Renamed Successfully', 'simple-slideshow-manager'); ?>');
</script>
	<?php
	}
	elseif (($_GET['rename_status']) == "no") {
?>
<script type="text/javascript">
alert('<?php
		_e('Gallery name already exist enter another name', 'simple-slideshow-manager'); ?>');
rtp_rename(1);
</script>
		<?php
	}
}

echo "<h2>" . __('Simple Slideshow Manager Settings', 'simple-slideshow-manager') . "</h2>"; ?>
	<form name="rtp_slideshow_form" method="post" action="<?php
echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="rtp_slideshow_hidden" id ="rtp_slideshow_hidden" value="Y">
		<?php

if (isset($_GET['name'])) {
	$rtp_selected_gallery_name = trim($_GET['name']);
	$plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
	$plugin_file = basename((__FILE__));
?>
			<p class="widefat" style="padding:8px;width:99%;margin-top:8px; ">	<?php
	_e('Selected Gallery ', 'simple-slideshow-manager'); ?>: <b><?php
	echo $rtp_selected_gallery_name; ?></b>
			<input type = "hidden" id = "gallery_selected" name = "gallery_selected" value = "<?php
	echo $rtp_selected_gallery_name; ?>">			
			<span id="shortc"><b><?php
	_e('Shortcode:-', 'simple-slideshow-manager'); ?></b>
			<input type="text" value='[rtp_slideshow name="<?php
	echo $rtp_selected_gallery_name; ?>"]' readonly size="60" onClick="javascript:this.focus();this.select();">
			<a href="admin.php?page=Acurax-Slideshow-Generate-Shortcode"><?php
	_e('Click Here For Custom Shortcode Generator', 'simple-slideshow-manager'); ?></a>
			</span> <!-- shortc -->
			
			<?php
	$rtp_slideshow_height = $rtp_slideshow_gallery_data[$rtp_selected_gallery_name]['rtp_slideshow_height'];
	$rtp_slideshow_width = $rtp_slideshow_gallery_data[$rtp_selected_gallery_name]['rtp_slideshow_width'];
	if ($rtp_slideshow_height == "" && $rtp_slideshow_width == "") {
?>
			<br/>
			<br/>
			<span style="color:red;">You have not yet configured height and width for this slideshow.Go to <a href="#rtp_advance" onclick="rtp_goto_advsett();">Advanced Settings</a> </span>
			<?php
	}

?>
			</p>
			<?php
}

?>
		</p>
		
		<p class="widefat" style="padding:8px;width:99%;margin-top:8px; ">
			<label for="upload_image">
				<input id="rtp_slideshow_upload_image_url" readonly type="hidden" size="78" name="rtp_slideshow_upload_image" value="" />
				 <a id="rtp_slideshow_upload_image_button" class="button  rtp_slide_upload" href="#" ><?php
_e('Add Image as Slide ', 'simple-slideshow-manager'); ?></a>
				 <input type = "hidden" name = "rtp_slideshow_title" id = "rtp_slideshow_title" />
				<input type = "hidden" name = "rtp_slideshow_caption" id = "rtp_slideshow_caption" />
				<input type = "hidden" name = "rtp_slideshow_alttext" id = "rtp_slideshow_alttext"/>
				<input type = "hidden" name = "rtp_slideshow_desc" id = "rtp_slideshow_desc"/>
				 </label>
		</p>

<?php

// ############################################################################################################

?>		
<script type="text/javascript">
var isrtp_add_advance = 1;
function rtp_goto_advsett()
{
jQuery("#rtp_advance").show();
jQuery("#plus_minus").html('[ - ]');
isrtp_add_advance = 2;
}
rtp_slideshow_media_upload_loader();
var gallery_name = document.getElementById("gallery_selected").value;


// display the advanced settings field

function rtp_add_advance()
{
if(isrtp_add_advance == 1)
{
jQuery("#rtp_advance").show();
jQuery("#plus_minus").html('[ - ]');
isrtp_add_advance = 2;
} else
{
jQuery("#rtp_advance").hide();
jQuery("#plus_minus").html('[+]');
isrtp_add_advance = 1;
}
}

// ********************* send the data to upload_image page to upload new image (Ajax) ********************


function upload_image()
{
var gallery_name = document.getElementById("gallery_selected").value;	
var image_url = document.getElementById("rtp_slideshow_upload_image_url").value;
var image_title = document.getElementById("rtp_slideshow_title").value;
var image_caption = document.getElementById("rtp_slideshow_caption").value;
var image_alttext = document.getElementById("rtp_slideshow_alttext").value;
var image_desc = document.getElementById("rtp_slideshow_desc").value;	
var type = "image";
if(image_url != "")
{
var rtp_load="<div id='rtp_slideshow_loading'><div class='load'></div></div>";
jQuery('body').append(rtp_load);
document.getElementById("rtp_slideshow_upload_image_url").value = "";
var order ='&gallery_name='+gallery_name+'&image_url='+image_url+'&image_title='+image_title+'&image_caption='+image_caption+'&image_alttext='+image_alttext+'&image_desc='+image_desc+'&type='+type+'&action=rtp_slideshow_ajax_upload'; 
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#response").html(theResponse);
rtp_slideshow_ajax_updateRecordsListings_js();
});
jQuery("#rtp_slideshow_loading").remove();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
}
else if(image_url=="")
{
alert('<?php
_e('Select an image to upload', 'simple-slideshow-manager'); ?>');
}
}
function rtp_change_vediotype()
{
var rtp_vedio_url = document.getElementById("rtp_video_url").value;
var dd = document.getElementById('rtp_video_type');
if ( rtp_vedio_url.indexOf( "vimeo" ) > -1 ) 
{
dd.selectedIndex = 1;	
}
else if ( rtp_vedio_url.indexOf( "youtube" ) > -1 ) 
{
dd.selectedIndex = 0;	
}
}
</script>
<br/>
<hr/>
	<?php
echo "<h4>" . __('Drag and Drop to Reorder Slides', 'simple-slideshow-manager') . "</h4>"; ?>
		<div id="response" style="padding:8px;width:99%;margin-top:8px;" class="widefat">
		<?php

if (is_serialized($rtp_slideshow_imageupload_complete_data)) {
	$rtp_slideshow_imageupload_complete_data = unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
}

$gallery_name = $rtp_selected_gallery_name;
$slide_count = count($rtp_slideshow_imageupload_complete_data[$gallery_name]);
echo "<ul id = \"rtp_slideshow_sortable\">";

for ($i = 0; $i < $slide_count; $i++) {
	if ($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type'] == "image") {
		echo "<li class=\"ui-state-default\" id = \"recordsArray_" . $i . "\">";
		echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
		echo "<img src = \"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url'] . "\" alt = \"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext'] . "\" title = \"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title'] . "\" > &nbsp;";
		echo "<div class=\"del_but\" id=\"rtp_delete_image_" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title'] . "\" onclick = \"rtp_delete(" . $i . ");\" title=\"Delete This Slide\"></div></br>";
		echo "<div class=\"edit_but\" id=\"rtp_edit_image_" . $i . "\" onclick = \"rtp_edit(" . $i . ");\" title=\"Edit This Slide\"></div></br>";
		echo "<div class=\"add_link\" id=\"rtp_edit_image_" . $i . "\" onclick = \"rtp_edit(" . $i . ");\" title=\"Add Link To This Slide\"></div></br>";
		echo "</li>";
	}
	else
	if ($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type'] == "youtube_video") {
		echo "<li class=\"ui-state-default\" id = \"recordsArray_" . $i . "\">";
		echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
		echo "<img src=\"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image'] . "\"  alt = \"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url'] . "\" title = \"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url'] . "\" > &nbsp;";
		echo "<div class=\"play_but\" title=\"\"></div></br>";
		echo "<div class=\"del_but\" id=\"rtp_delete_image_" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url'] . "\" onclick = \"rtp_delete(" . $i . ");\" title=\"Delete This Slide\"></div></br>";
		echo "</li>";
	}
	else
	if ($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type'] == "vimeo_video") {
		echo "<li class=\"ui-state-default\" id = \"recordsArray_" . $i . "\">";
		echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
		echo "<img src=\"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image'] . "\"  alt = \"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url'] . "\" title = \"" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url'] . "\" > &nbsp;";
		echo "<div class=\"play_but\" title=\"\"></div></br>";
		echo "<div class=\"del_but\" id=\"rtp_delete_image_" . $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url'] . "\" onclick = \"rtp_delete(" . $i . ");\" title=\"Delete This Slide\"></div></br>";
		echo "</li>";
	}
}

echo "</ul>";
?>
	</div>	
<script type="text/javascript">

// delete image from the selected gallery

function rtp_delete(value)
{
if (confirm('<?php
_e('Are You Sure to Delete This Slide?\n\nNOTE: You cannot undo this action.', 'simple-slideshow-manager'); ?>')) { 
var index = value;
var gallery_name = document.getElementById("gallery_selected").value;
var order ='&gallery_name='+gallery_name+'&index='+index+'&action=rtp_slideshow_ajax_deleteimage';
var rtp_load="<div id='rtp_slideshow_loading'><div class='load'></div></div>";
jQuery('body').append(rtp_load);
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#response").html(theResponse);
rtp_slideshow_ajax_updateRecordsListings_js();
jQuery("#rtp_slideshow_loading").remove();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
});
}
}
function rtp_edit(value)
{
var index = value;
var gallery_name = document.getElementById("gallery_selected").value;
var order ='&gallery_name='+gallery_name+'&index='+index+'&action=rtp_slideshow_ajax_editimage';
var rtp_load="<div id='rtp_slideshow_loading'><div class='load'></div></div>";
jQuery('body').append(rtp_load);
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#rtp_edit_image").show();
jQuery("#edit_image").html(theResponse);
rtp_slideshow_ajax_updateRecordsListings_js();
jQuery("#rtp_slideshow_loading").remove();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
});
}
function rtp_slideshow_change_edittext(value)
{
var index = value;
var gallery_name = document.getElementById("gallery_selected").value;
var title = document.getElementById("rtp_slideshow_edit_title").value;
var caption = ""; //document.getElementById("rtp_slideshow_edit_caption").value;
var alttext = document.getElementById("rtp_slideshow_edit_alt").value;
var desc = ""; //document.getElementById("rtp_slideshow_edit_desc").value;
var url = document.getElementById("rtp_slideshow_edit_url").value;
var target = document.getElementById("rtp_link_target").value;
var order ='&gallery_name='+gallery_name+'&index='+index+'&title='+title+'&caption='+caption+'&alttext='+alttext+'&desc='+desc+'&url='+url+'&target='+target+'&action=rtp_slideshow_ajax_changeedittext';
jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#rtp_edited").html(theResponse);
rtp_slideshow_ajax_updateRecordsListings_js();
var m_edited="<div id='s_s_notice'>Image edited</div>";
jQuery('#response').prepend(m_edited);
rtp_slideshow_change_edittext_cancel();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 5000); // <-- time in milliseconds
});
}
function rtp_slideshow_change_edittext_cancel()
{
jQuery('#rtp_edit_image').hide();
jQuery('#rtp_editimage_form').remove();
}
</script>
<script type="text/javascript">
var gallery_name = document.getElementById("gallery_selected").value;
function rtp_slideshow_ajax_updateRecordsListings_js()
{

// ************ Arrange the order of display of image (drag and drop)************

jQuery(function() 
{
jQuery("#rtp_slideshow_sortable").sortable(
{ 
opacity: 0.5, cursor: 'move', update: function() 
{
var order = jQuery(this).sortable("serialize")+'&gallery_name='+gallery_name+'&action=rtp_slideshow_ajax_updateRecordsListings'; 

// alert(order);

jQuery.post(ajaxurl, order, function(theResponse)
{
jQuery("#response").html(theResponse);
rtp_slideshow_ajax_updateRecordsListings_js();
setTimeout(function() {
jQuery('#s_s_notice').fadeOut('fast');
}, 3000); // <-- time in milliseconds
}); 
}								  
});
});
}
jQuery(document).ready(function()
{
rtp_slideshow_ajax_updateRecordsListings_js();
});	
</script>

<?php

// ###########################################################################################################

$rtp_slideshow_timespan = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_timespan'];
$rtp_slideshow_fadeouttime = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_fadeouttime'];
$rtp_slideshow_fadeintime = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_fadeintime'];
$rtp_slideshow_pauseon_hover = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_pauseon_hover'];

if ($rtp_slideshow_timespan == "" || $rtp_slideshow_timespan == 0) {
	$rtp_slideshow_timespan = 4;
}

if ($rtp_slideshow_fadeintime == "" || $rtp_slideshow_fadeintime == 0) {
	$rtp_slideshow_fadeintime = 1;
}

if ($rtp_slideshow_fadeouttime == "" || $rtp_slideshow_fadeouttime == 0) {
	$rtp_slideshow_fadeouttime = 1;
}

?>	

		<div class="widefat" style="padding:8px;width:99%;margin-top:8px;" >
			<p><?php
_e(' Advanced Settings', 'simple-slideshow-manager'); ?> <a href="javascript:rtp_add_advance();" class="close"><b id="plus_minus">[+]</b></a></p>
			<br />
			<div id = "rtp_advance" style = "display:none" >
				<?php
_e('Time span in seconds', 'simple-slideshow-manager'); ?> <input type = "text" name = "rtp_slideshow_timespan" id= "rtp_slideshow_timespan" size = "40" value = "<?php
echo $rtp_slideshow_timespan; ?>"/></br></br>
				<?php
_e('Fadein Time in seconds', 'simple-slideshow-manager'); ?><input type = "text" name = "rtp_slideshow_fadeintime" id= "rtp_slideshow_fadeintime" size = "40"  value = "<?php
echo $rtp_slideshow_fadeintime; ?>" /></br></br>
				<?php
_e('Fadeout Time in seconds', 'simple-slideshow-manager'); ?><input type = "text" name = "rtp_slideshow_fadeouttime" id= "rtp_slideshow_fadeouttime" size = "40"  value = "<?php
echo $rtp_slideshow_fadeouttime; ?>" /></br></br>
				<?php
_e('Enable Pause on Hover', 'simple-slideshow-manager'); ?>
				<select id="rtp_slideshow_pauseon_hover" name="rtp_slideshow_pauseon_hover">
				<option value="true" <?php

if ($rtp_slideshow_pauseon_hover == "true" || $rtp_slideshow_pauseon_hover == "") { ?> selected="selected" <?php
} ?> ><?php
_e('Enable', 'simple-slideshow-manager'); ?></option>
				<option value="false" <?php

if ($rtp_slideshow_pauseon_hover == "false") { ?> selected="selected" <?php
} ?> ><?php
_e('Disable', 'simple-slideshow-manager'); ?></option>
				</select>
				</br></br>
				<?php
$rtp_slideshow_height = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_height'];
$rtp_slideshow_width = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_width'];

if (preg_match('/%/', $rtp_slideshow_height)) {
	$rtp_slideshow_height_type = "%";
}
else
if (preg_match('/px/', $rtp_slideshow_height)) {
	$rtp_slideshow_height_type = "px";
}

if (preg_match('/%/', $rtp_slideshow_width)) {
	$rtp_slideshow_width_type = "%";
}
else
if (preg_match('/px/', $rtp_slideshow_width)) {
	$rtp_slideshow_width_type = "px";
}

$temp_height = str_replace("%", "", $rtp_slideshow_height);
$temp_height = str_replace("px", "", $temp_height);
$temp_width = str_replace("%", "", $rtp_slideshow_width);
$temp_width = str_replace("px", "", $temp_width);
?>
				
				<?php
_e('Slide height', 'simple-slideshow-manager'); ?> <input type = "text" name = "rtp_slideshow_height" id= "rtp_slideshow_height" size = "40"  value = "<?php
echo $temp_height; ?>" />
					<select id="rtp_slideshow_height_type" name="rtp_slideshow_height_type">
				<option value="px" <?php

if ($rtp_slideshow_height_type == "px") { ?> selected="selected" <?php
} ?> ><?php
_e('px', 'simple-slideshow-manager'); ?></option>
				<option value="%" <?php

if ($rtp_slideshow_height_type == "%") { ?> selected="selected" <?php
} ?>><?php
_e('%', 'simple-slideshow-manager'); ?></option>
				</select>
				</br></br>
				<?php
_e('Slide width', 'simple-slideshow-manager'); ?><input type = "text" name = "rtp_slideshow_width" id= "rtp_slideshow_width" size = "40"  value = "<?php
echo $temp_width; ?>" />
				<select id="rtp_slideshow_width_type" name="rtp_slideshow_width_type">
				<option value="px" <?php

if ($rtp_slideshow_width_type == "px") { ?> selected="selected" <?php
} ?> ><?php
_e('px', 'simple-slideshow-manager'); ?></option>
				<option value="%" <?php

if ($rtp_slideshow_width_type == "%") { ?> selected="selected" <?php
} ?> ><?php
_e('%', 'simple-slideshow-manager'); ?></option>
				</select>
				</br></br>
				<p class="submit">
					<input type="submit" class ="button" name="Submit" value="<?php
_e('Save Settings', 'simple-slideshow-manager') ?>" />
				</p>			
			</div>
		</div>
	</form>

<hr/>
</div>	
<div id="rename_gallery" class="widefat" style = "display:none">
	<div id="rename_lb">
		<form name="gall_renameform" method="post" action="admin.php?page=Acurax-Slideshow-Rename-Gallery">
			<input type = "text" id = "rename" name = "rename" class="field"value="<?php
echo $_GET['name']; ?>" onblur="if (this.value == '') {this.value = '<?php
echo $_GET['name']; ?>';}" onfocus="if (this.value == '<?php
echo $_GET['name']; ?>') {this.value = '';}" />
			<input type = "hidden" id="old_name" name="old_name" value = "<?php
echo $_GET['name']; ?>"/>
			<input type = "hidden" id = "url" name = "url" value = "<?php
echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>"/>
			<input type = "submit" id="confirm" name="confirm" value ="Rename"  class="button rtp_rename_buttn"/>
		</form>
		<a href="#" class="close" onclick = "rtp_rename('2');">X</a>
	</div>
</div>

<div id="rtp_upload_video"  class="widefat" style="display:none">
	<div id="upload_vedio">
	
	<?php
_e('Video Url', 'simple-slideshow-manager'); ?>
	<input type="text" name="rtp_video_url" id="rtp_video_url" onChange="rtp_change_vediotype();" /></br>
	<?php
_e('Video Type', 'simple-slideshow-manager'); ?>
			<select name="rtp_video_type" id="rtp_video_type">
			<option value="youtube_video"><?php
_e('Youtube Video', 'simple-slideshow-manager'); ?> 
			<option value="vimeo_video"><?php
_e('Vimeo Video', 'simple-slideshow-manager'); ?> 
			</select></br></br>
	
    <input type="button" value="Upload Video"  class="button rtp_vedio_upload_buttn" onclick="upload_video()" />
	<input type = "hidden" id="rtp_gall_name" name="rtp_gall_name"  value = "<?php
echo $_GET['name']; ?>"/>
	<a href="#" class="close" onclick = "rtp_upload_video('2');">X</a>
	</div>
</div>
<div id="rtp_edit_image" style="display:none">
	<div id="edit_image">
		
	</div>
</div>
<div id="rtp_edited">
</div>