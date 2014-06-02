<?php	
$rtp_slideshow_misc_hide_advert = get_option('rtp_slideshow_misc_hide_advert');
if($rtp_slideshow_misc_hide_advert == "")
{
$rtp_slideshow_misc_hide_advert = "no";
}
$rtp_slideshow_imageupload_complete_data=unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
if($rtp_slideshow_imageupload_complete_data=="")
{
	$rtp_slideshow_imageupload_complete_data=array();
}
//print_r($rtp_slideshow_imageupload_complete_data);
?>
<script type="text/javascript">
function rtp_generate()
{
var rtp_gallery_name = document.getElementById("rtp_shortcode_gallery").value;
var rtp_height = document.getElementById("rtp_shortcode_gall_height").value;
var rtp_width = document.getElementById("rtp_shortcode_gall_width").value;
var rtp_height_type = document.getElementById("rtp_height_type").value;
var rtp_width_type =  document.getElementById("rtp_width_type").value;
if(rtp_gallery_name!="")
{
if(!isNaN(document.getElementById("rtp_shortcode_gall_height").value))
{
rtp_height = document.getElementById("rtp_shortcode_gall_height").value;
} else
{
rtp_height = "";
alert('<?php _e('Height needs to be a number','simple-slideshow-manager'); ?>');
}
if(!isNaN(document.getElementById("rtp_shortcode_gall_width").value))
{
rtp_width = document.getElementById("rtp_shortcode_gall_width").value;
} else
{
rtp_width = "";
alert('<?php _e('Width needs to be a number','simple-slideshow-manager'); ?>');
}
jQuery("#rtp_shortcode").show();
if(rtp_height == "")
{
shortcode_ht = "";
} else
{
shortcode_ht = " height=\""+rtp_height+rtp_height_type+"\"";
}
if(rtp_width == "")
{
shortcode_wth = "";
} else
{
shortcode_wth = " width=\""+rtp_width+rtp_width_type+"\"";
}
var shortcode_cnt = "[rtp_slideshow name=\""+rtp_gallery_name+"\""+shortcode_ht+shortcode_wth+"]";
document.getElementById("rtp_shortcode_display").value = shortcode_cnt;	
}
else
{
	alert("There is no gallery exist.");
}
}
</script>

<div class="wrap">
	<?php
	echo "<h2>" . __( 'Generate Shortcode', 'simple-slideshow-manager' ) . "</h2>"; 
	?>
	<div style="padding:8px;width:99%;margin-top:8px;" class="widefat">
		<table style="border:0px;" id="rtp_short_gen" cellspacing="0">
			<tr>
				<td> <?php _e('Select Gallery','simple-slideshow-manager'); ?></td>
				<td>
				<select id="rtp_shortcode_gallery" name="rtp_shortcode_gallery">	
				<?php
				foreach ($rtp_slideshow_imageupload_complete_data as $key => $value)
				{
				?>
				<option value="<?php echo $key; ?>"><?php echo $key; ?></option>
				<?php
				}
				?>
				</select></td>
			</tr>
			<tr>
				<td><?php _e('Height','simple-slideshow-manager'); ?></td>
				<td>
				<input type="text" name="rtp_shortcode_gall_height" id="rtp_shortcode_gall_height" value=""/>
				<select id="rtp_height_type" name="rtp_height_type">
				<option value="px"><?php _e('px','simple-slideshow-manager'); ?></option>
				<option value="%"><?php _e('%','simple-slideshow-manager'); ?></option>
				</select>
				</td>
			</tr>
			<tr>
				<td>
				<?php _e('Width','simple-slideshow-manager'); ?></td><td>
				<input type="text" name="rtp_shortcode_gall_width" id="rtp_shortcode_gall_width" value=""/>
				<select id="rtp_width_type" name="rtp_width_type">
				<option value="px"><?php _e('px','simple-slideshow-manager'); ?></option>
				<option value="%"><?php _e('%','simple-slideshow-manager'); ?></option>
				</select>
				</td>
			</tr>
			<tr>
				<td></td><td>	<a id="rtp_slideshow_generate_shortcode" class="manage_gallery"  href="#" style="background: none repeat scroll 0px 0px lightseagreen; color: white; padding: 0px 5px 1px; text-decoration: none; border: 1px solid gray;" alt = "Click to Manage this Gallery" onclick = "rtp_generate()"><?php _e('Generate Shortcode','simple-slideshow-manager'); ?></a></td>
			</tr>
			<tr>
				<td colspan="2">
				<div id="rtp_shortcode" style = "display:none">
				<b><?php _e('Shortcode:-','simple-slideshow-manager'); ?></b>
				<input type="text" name="rtp_shortcode_display" id="rtp_shortcode_display" value="" readonly size="60" onClick="javascript:this.focus();this.select();">
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>