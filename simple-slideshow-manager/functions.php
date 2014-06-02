<?php
//************ get all data from database *****************
$rtp_slideshow_imageupload_complete_data=unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
$rtp_slideshow_gallery_data = unserialize(get_option('rtp_slideshow_gallery_data'));
$rtp_slideshow_misc_user_level = get_option('rtp_slideshow_misc_user_level');
if($rtp_slideshow_misc_user_level=="")
{
$rtp_slideshow_misc_user_level = "manage_options";
}
function rtp_slideshow_js() 
{
if(function_exists('wp_enqueue_media'))
{
	wp_enqueue_media();	
}	
?>
<script type="text/javascript">
function rtp_slideshow_media_upload_loader()
{
var custom_uploader;
jQuery(rtp_slideshow_upload_image_button).click(function(e) 
{
e.preventDefault();
//If the uploader object has already been created, reopen the dialog
if (custom_uploader) 
{
custom_uploader.open();
return;
}
//Extend the wp.media object
custom_uploader = wp.media.frames.file_frame = wp.media({
title: '<?php __('Choose Image','simple-slideshow-manager') ?>',
button: {
text: '<?php _e('Choose Image','simple-slideshow-manager') ?>'
},
multiple: false						});
//When a file is selected, grab the URL and set it as the text field's value
custom_uploader.on('select', function() 
{
attachment = custom_uploader.state().get('selection').first().toJSON();
var rtp_slideshow_upload_image_url="#rtp_slideshow_upload_image_url";
jQuery(rtp_slideshow_upload_image_url).val(attachment.url);
var rtp_slideshow_title="#rtp_slideshow_title";
jQuery(rtp_slideshow_title).val(attachment.title);
var rtp_slideshow_caption="#rtp_slideshow_caption";
jQuery(rtp_slideshow_caption).val(attachment.caption);
var rtp_slideshow_alttext="#rtp_slideshow_alttext";
jQuery(rtp_slideshow_alttext).val(attachment.alt);
var rtp_slideshow_desc="#rtp_slideshow_desc";
jQuery(rtp_slideshow_desc).val(attachment.description);
upload_image();
});
//Open the uploader dialog
custom_uploader.open();
});
}
</script>
<?php
} 
add_action('admin_head', 'rtp_slideshow_js'); 
function rtp_slideshow_jquery()
{
	wp_enqueue_script( 'jquery' );
}add_action( 'wp_enqueue_scripts', 'rtp_slideshow_jquery' );
 add_action('admin_enqueue_script','rtp_slideshow_jquery');
function rtp_slideshow_jquery_ui()
{
	wp_enqueue_script('jquery-ui-sortable');
	
}add_action('admin_enqueue_script','rtp_slideshow_jquery_ui');
function rtp_slideshow_admin_style()  // Adding Style For Admin
{
	echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('style.css', __FILE__). '">';
	
}add_action('admin_head', 'rtp_slideshow_admin_style'); // ADMIN
function rtp_slideshow_css()
{
	wp_enqueue_style ( 'my_plugin2_style', plugins_url('style.css', __FILE__) );
}add_action( 'wp_print_styles', 'rtp_slideshow_css' );
function rtp_slideshow_api_js()
{
?>
<script src="http://a.vimeocdn.com/js/froogaloop2.min.js?25f83-1376905454"></script>
<script type="text/javascript" id="rtp_js_api">
function call_rtp_y_player(frame_id, func,id,u_id, args){
frame_id_dpl = frame_id+u_id;
var frame ='#'+frame_id+u_id;
var imageid = '#rtp_image_'+u_id+'_'+id;
var vedio_stat_field ='#rtp_hidden_id_'+u_id;
var palybuttn = '.rtp_dis_yplay_but_'+u_id;
var pausebuttn = '.rtp_dis_ypause_but_'+u_id;
if(func=="playVideo")
{
jQuery(imageid).hide();
jQuery(frame).fadeIn('slow');
jQuery(palybuttn).hide();
jQuery(pausebuttn).show();
jQuery(vedio_stat_field).val('play');
}
else if(func=="stopVideo")
{
jQuery(frame).hide();
jQuery(imageid).fadeIn('slow');
jQuery(palybuttn).show();
jQuery(pausebuttn).hide();
jQuery(vedio_stat_field).val('stop');
}
if(!frame_id) return;
if(frame_id_dpl.id) frame_id_dpl = frame_id_dpl.id;
else if(typeof jQuery != "undefined" && frame_id_dpl instanceof jQuery && frame_id_dpl.length) frame_id = frame_id_dpl.get(0).id;
if(!document.getElementById(frame_id_dpl)) return;
args = args || [];
/*Searches the document for the IFRAME with id=frame_id*/
var all_iframes = document.getElementsByTagName("iframe");
for(var i=0, len=all_iframes.length; i<len; i++){
if(all_iframes[i].id == frame_id_dpl || all_iframes[i].parentNode.id == frame_id){
/*The index of the IFRAME element equals the index of the iframe in
the frames object (<frame> . */
window.frames[i].postMessage(JSON.stringify({
"event": "command",
"func": func,
"args": args,
"id": frame_id
}), "*");
}
}
}
function rtp_play_vimeo_video(vedio_id,id,u_id)
{
	var iframe_id = "#player_"+vedio_id+u_id;
	var iframe = jQuery(iframe_id)[0],
	player = iframe;
var frame ='#'+vedio_id+u_id;
var imageid = '#rtp_image_vimeo_'+u_id+'_'+id;
var vedio_stat_field ='#rtp_hidden_id_'+u_id;
var palybuttn = '.rtp_dis_vplay_but_'+u_id;
var pausebuttn = '.rtp_dis_vpause_but_'+u_id;
jQuery(vedio_stat_field).val('play');
jQuery(imageid).hide();
jQuery(frame).fadeIn('slow');
jQuery(palybuttn).hide();
jQuery(pausebuttn).show();
player.api('play');
}
function rtp_stop_vimeo_video(vedio_id,id,u_id)
{
	var iframe_id = "#player_"+vedio_id+u_id;
	var iframe = jQuery(iframe_id)[0],
	player = iframe;
	
var frame ='#'+vedio_id+u_id;
var imageid = '#rtp_image_vimeo_'+u_id+'_'+id;
var vedio_stat_field ='#rtp_hidden_id_'+u_id;
var palybuttn = '.rtp_dis_vplay_but_'+u_id;
var pausebuttn = '.rtp_dis_vpause_but_'+u_id;
jQuery(frame).hide();
jQuery(imageid).fadeIn('slow');
jQuery(palybuttn).show();
jQuery(pausebuttn).hide();
jQuery(vedio_stat_field).val('stop');
player.api('unload');
}
</script>
<?php
}
add_action('wp_head','rtp_slideshow_api_js');
// rename the gallery with new value
$uid =0;
if(!isset($rtp_slideshow_js_array))
{
	$rtp_slideshow_js_array = array();
} 
function rtp_slideshow($gallery_name="",$gallery_height="",$gallery_width="")
{
	global $rtp_slideshow_imageupload_complete_data,$rtp_slideshow_gallery_data,$uid,$rtp_slideshow_js_array;
	if($rtp_slideshow_js_array=="")
{
	$rtp_slideshow_js_array = array();
}
	if($gallery_height == "")
	{
		$rtp_slideshow_height = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_height'];
	} else
	{
		$rtp_slideshow_height = $gallery_height;
	}
	if($gallery_width == "")
	{
		$rtp_slideshow_width =  $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_width'];
	}
	else
	{
		$rtp_slideshow_width = $gallery_width;
	}
	
	$slide_count=count($rtp_slideshow_imageupload_complete_data[$gallery_name]);
	$gal_found = 0;
	foreach($rtp_slideshow_imageupload_complete_data as $key => $value)
	{
		if($key==$gallery_name)
		{
			$gal_found = 1;
		}
	}
		
	$rtp_gallery_uid = str_replace(' ','_',$gallery_name);
	if($gal_found==0)
	{
		?>
		<div class="rtp_ss_error">
		The gallery group <b><?php echo $gallery_name; ?></b> is no longer available , Please reconfigure your widget/shortcode/phpcode which display's this slideshow.
		</div>
		<?php
	}
	elseif($rtp_slideshow_height==""&&$rtp_slideshow_width=="")
	{
		?>
		<div class="rtp_ss_error">
		You have not configured height or width for the slideshow group <b><?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name]; ?></b>, Please go to wp-admin -> Slideshow -> Manage Gallery -> Advanced Settings and configure Slideshow width and height. 
		</div>
		<?php
	}
	else {
	
	?>
	<ul class="rtp_ppt rtp_ppt_<?php echo $uid; ?> rtp_ppt_uid_<?php echo$rtp_gallery_uid; ?>" style="<?php if ($rtp_slideshow_height != ""){ ?>height:<?php echo $rtp_slideshow_height;?>; <?php }  if($rtp_slideshow_width!="") { ?> width:<?php echo $rtp_slideshow_width; ?>; <?php } if($rtp_slideshow_height!=""&&$rtp_slideshow_width!=""){ echo "overflow:hidden;";}  ?>">
	<?php 
	for($i = 0 ; $i<$slide_count; $i++)
	{
		if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image") 
		{
			$rtp_slideshow_link_url = $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['link_url'];
			$rtp_slideshow_target = $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['link_target']; 
			if($rtp_slideshow_target=="")
			{
				$rtp_slideshow_target = "_blank";
			}
			?>
			<li style="<?php if ($rtp_slideshow_height != ""){ ?>height:<?php echo $rtp_slideshow_height;?>; <?php }  if($rtp_slideshow_width!="") { ?> width:<?php echo $rtp_slideshow_width; ?>;<?php } ?>">
			<?php
			if($rtp_slideshow_link_url!="")
			{
				?>
				<a href="<?php echo $rtp_slideshow_link_url; ?>" target="<?php echo $rtp_slideshow_target; ?>" title="<?php echo  $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']; ?>">
				<?php
			}
			?>
			<img  src="<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']; ?>" title="<?php echo  $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']; ?>" alt="<?php $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']; ?>" style="<?php if ($rtp_slideshow_height != ""){ ?>height:<?php echo $rtp_slideshow_height;?>; <?php }  if($rtp_slideshow_width!="") { ?> width:<?php echo $rtp_slideshow_width; ?>; <?php } ?>">
			<?php
			if($rtp_slideshow_link_url!="")
			{
				?>
				</a>
				<?php
			}
			?>
			</li>
			<?php
		}
		else if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{	
			$vedio_id = "http://www.youtube.com/embed/".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']."?enablejsapi=1";
			//$vedio_tn = $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image'];
			?>
			<li style="<?php if ($rtp_slideshow_height != ""){ ?>height:<?php echo $rtp_slideshow_height;?>; <?php }  if($rtp_slideshow_width!="") { ?> width:<?php echo $rtp_slideshow_width; ?>;<?php } ?>">
			<div style="display:none;<?php if ($rtp_slideshow_height != ""){ ?>height:<?php echo $rtp_slideshow_height;?>;<?php }  if($rtp_slideshow_width!="") { ?>width:<?php echo $rtp_slideshow_width; ?>;<?php } ?>" class="rtp_v_hold" id="<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'].$uid; ?>">
			<iframe src="http://www.youtube.com/embed/<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>?enablejsapi=1&autoplay=1&controls=0&wmode=opaque&cc_load_policy=1&iv_load_policy=3&loop=1" width="100%" height="100%"></iframe>
			<div class="rtp_no_mo" style="height:100%;width:100%;"></div>
			</div>
			
			<img id="rtp_image_<?php echo $uid; ?>_<?php echo $i; ?>" src="<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']; ?>" <?php if ($rtp_slideshow_height != ""){ ?>height= "<?php echo $rtp_slideshow_height;?>" <?php }  if($rtp_slideshow_width!="") { ?> width = "<?php echo $rtp_slideshow_width; ?>" <?php } ?> />
			
			<div class="rtp_dis_play_but rtp_dis_yplay_but_<?php echo $uid; ?>" title="" onclick ="call_rtp_y_player('<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>','<?php echo"playVideo"; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			<div class="player_cntrls">
			<div class="rtp_dis_pause_but rtp_dis_ypause_but_<?php echo $uid; ?>" title="" onclick ="call_rtp_y_player('<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>','<?php echo"stopVideo"; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			</div>
			
			</li>
			<?php	
		}
		else if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
			$vedio_id = "http://player.vimeo.com/video/".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'];
		//	$vedio_tn = $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']; 
			?>
			<li style="<?php if ($rtp_slideshow_height != ""){ ?>height:<?php echo $rtp_slideshow_height;?>; <?php }  if($rtp_slideshow_width!="") { ?> width:<?php echo $rtp_slideshow_width; ?>;<?php } ?>">
			<div style="display:none;<?php if ($rtp_slideshow_height != ""){ ?>height:<?php echo $rtp_slideshow_height;?>;<?php }  if($rtp_slideshow_width!="") { ?>width:<?php echo $rtp_slideshow_width; ?>;<?php } ?>" class="rtp_v_hold" id="<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id'].$uid; ?>">
			<iframe src="https://player.vimeo.com/video/<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>?api=1&player_id=player&autoplay=1&title=0&byline=0&portrait=0&loop=1" <?php if ($rtp_slideshow_height != ""){ ?>height= "<?php echo $rtp_slideshow_height;?>" <?php }  if($rtp_slideshow_width!="") { ?> width = "<?php echo $rtp_slideshow_width; ?>" <?php } ?> ></iframe>
			<div class="rtp_no_mo" style="height:100%;width:100%;"></div>
			</div>
			<img  id="rtp_image_vimeo_<?php echo $uid; ?>_<?php echo $i; ?>" src="<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']; ?>" <?php if ($rtp_slideshow_height != ""){ ?>height= "<?php echo $rtp_slideshow_height;?>" <?php }  if($rtp_slideshow_width!="") { ?> width = "<?php echo $rtp_slideshow_width; ?>" <?php } ?>/>			
            <div class="rtp_dis_play_but rtp_dis_vplay_but_<?php echo $uid; ?>" title="" onclick ="rtp_play_vimeo_video('<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			<div class="player_cntrls">
			
			
			<div class="rtp_dis_pause_but rtp_dis_vpause_but_<?php echo $uid; ?>" title="" onclick ="rtp_stop_vimeo_video('<?php echo $rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['vedio_id']; ?>',<?php echo $i; ?>,<?php echo $uid; ?>);"> </div>
			</div>
			</li>
			<?php
		}
	}
		
	?>
	<input type="hidden" id="rtp_hidden_id_<?php echo $uid; ?>" value="stop"/>
	</ul>
	<?php
	global $rtp_slideshow_gallery_data;
	$rtp_slideshow_timespan = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_timespan'];
	$rtp_slideshow_fadeouttime = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_fadeouttime'];
	$rtp_slideshow_fadeintime = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_fadeintime'];
	$rtp_slideshow_pauseon_hover = $rtp_slideshow_gallery_data[$gallery_name]['rtp_slideshow_pauseon_hover'];
	if($rtp_slideshow_timespan==""||$rtp_slideshow_timespan==0)
	{
		$rtp_slideshow_timespan = 4000;
	}
	else
	{
		$rtp_slideshow_timespan = $rtp_slideshow_timespan * 1000;
	}
	if($rtp_slideshow_fadeintime=="" || $rtp_slideshow_fadeintime ==0)
	{
		$rtp_slideshow_fadeintime = 1000;
	}
	else
	{
		$rtp_slideshow_fadeintime = $rtp_slideshow_fadeintime * 1000;
	}
	if($rtp_slideshow_fadeouttime == "" || $rtp_slideshow_fadeouttime == 0)
	{
		$rtp_slideshow_fadeouttime = 1000;
	}
	else
	{
		$rtp_slideshow_fadeouttime = $rtp_slideshow_fadeouttime * 1000;
	}
	$rtp_slideshow_js_array[$uid]= (array(
										"rtp_slideshow_timespan"=>$rtp_slideshow_timespan,
										"rtp_slideshow_fadeouttime"=>$rtp_slideshow_fadeouttime,
										"rtp_slideshow_fadeintime"=>$rtp_slideshow_fadeintime,
										"rtp_slideshow_pauseon_hover"=>$rtp_slideshow_pauseon_hover
									));
	}
	$uid = $uid+1;	
	
}
function rtp_slideshow_js_show()
{
	global $rtp_slideshow_js_array;	?>
<!-- Starting of Javascript Generated by Advanced Slideshow Manager -->
<script type="text/javascript">
<?php
foreach($rtp_slideshow_js_array as $key => $value)
{
 $rtp_slideshow_timespan = $rtp_slideshow_js_array[$key]['rtp_slideshow_timespan'];
 $rtp_slideshow_fadeouttime = $rtp_slideshow_js_array[$key]['rtp_slideshow_fadeouttime'];
 $rtp_slideshow_fadeintime = $rtp_slideshow_js_array[$key]['rtp_slideshow_fadeintime'];
 $rtp_slideshow_pauseon_hover = $rtp_slideshow_js_array[$key]['rtp_slideshow_pauseon_hover'];
 $uid = $key;
 ?>
jQuery('.rtp_ppt_<?php echo $uid; ?> li:gt(0)').hide();
jQuery('.rtp_ppt_<?php echo $uid; ?> li:last').addClass('last');
var cur_<?php echo $uid; ?> = jQuery('.rtp_ppt_<?php echo $uid; ?> li:first');
var sliderHover_<?php echo $uid; ?> = false;
function animate_<?php echo $uid; ?>() 
{
if(sliderHover_<?php echo $uid; ?> == false)
{
cur_<?php echo $uid; ?>.fadeOut( <?php echo $rtp_slideshow_fadeouttime; ?> );
if ( cur_<?php echo $uid; ?>.attr('class') == 'last' )
cur_<?php echo $uid; ?> = jQuery('.rtp_ppt_<?php echo $uid; ?> li:first');
else
cur_<?php echo $uid; ?> = cur_<?php echo $uid; ?>.next();
cur_<?php echo $uid; ?>.fadeIn(<?php echo $rtp_slideshow_fadeintime; ?>);
}
cur_<?php echo $uid; ?>.hover(
function () {
var hidden_id = 'rtp_hidden_id_'+<?php echo $uid; ?>;
var vedio_status = document.getElementById(hidden_id).value;
<?php if($rtp_slideshow_pauseon_hover=="true"){ ?>
sliderHover_<?php echo $uid; ?> = true;
<?php }else{  ?>
if(vedio_status!="play")
{
sliderHover_<?php echo $uid; ?> = false;
}
<?php } ?>
},
function () {
var hidden_id = 'rtp_hidden_id_'+<?php echo $uid; ?>;
var vedio_status = document.getElementById(hidden_id).value;
if(vedio_status=="play")
{
sliderHover_<?php echo $uid; ?> = true;
}
else
{
sliderHover_<?php echo $uid; ?> = false;
}
}
);
}
jQuery(function() 
{
setInterval( "animate_<?php echo $uid; ?>()",<?php echo $rtp_slideshow_timespan; ?>);
} );
<?php
}
?>
</script>
<!-- Ending of Javascript Generated by Advanced Slideshow Manager -->	
<?php
}
add_action('wp_footer', 'rtp_slideshow_js_show');
function rtp_slideshow_sc( $atts ) 
{
	extract( shortcode_atts( array(
									'name' => "",
									'height' => "",
									'width' => "",), $atts 
								) );
	$temp_height = str_replace("%","",$height);
	$temp_height = str_replace("px","",$temp_height);
	if(!is_numeric($temp_height))
	{
		$height = "";
	}
	$temp_width = str_replace("%","",$width);
	$temp_width = str_replace("px","",$temp_width);
	if(!is_numeric($temp_width))
	{
		$width = "";
	}
	ob_start();
	rtp_slideshow($name,$height,$width);
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}add_shortcode( 'rtp_slideshow', 'rtp_slideshow_sc' );
// Starting Widget Code
class rtp_Slideshow_Widget extends WP_Widget
{
    // Register the widget
    function rtp_Slideshow_Widget() 
	{
        // Set some widget options
        $widget_options = array('description' => __('Allow users to include a slideshow  From Acurax Slideshow Plugin','simple-slideshow-manager'),'classname' => 'rtp_slideshow_desc');
		$control_options = array('width' => 300);
		$this->WP_Widget('rtp_slideshow_widget','Simple Slideshow Manager', $widget_options ,$control_options );
    }
    // Output the content of the widget
    function widget($args, $instance) 
	{
        extract( $args ); // Don't worry about this
        // Get our variables
        $title = apply_filters( 'widget_title', $instance['title'] );
		$gallery_name = $instance['gallery_name'];
		$gallery_height = $instance['gallery_height'];
		$gallery_width = $instance['gallery_width'];
        // This is defined when you register a sidebar
        echo $before_widget;
        // If our title isn't empty then show it
        if ( $title ) 
		{
            echo $before_title . $title . $after_title;
        }
		rtp_slideshow($gallery_name,$gallery_height,$gallery_width);
        // This is defined when you register a sidebar
        echo $after_widget;
    }
	// Output the admin options form
	function form($instance) 
	{
	// These are our default values
		$defaults = array( 'title' => __('Simple Slideshow','simple-slideshow-manager'),'gallery_name' => '','gallery_height' => '','gallery_width' => '100%' );
		// This overwrites any default values with saved values
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
				<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" 
				value="<?php echo $instance['title']; ?>" type="text" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('gallery_name'); ?>"><?php _e('Gallery Name:'); ?></label>
				<select class="widefat" name="<?php echo $this->get_field_name('gallery_name'); ?>" id="<?php echo $this
				->get_field_id('gallery_name'); ?>">
				<?php
				$rtp_slideshow_gallery_data = unserialize(get_option('rtp_slideshow_gallery_data'));
				$rtp_slideshow_imageupload_complete_data=unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
				foreach ($rtp_slideshow_imageupload_complete_data as $key => $value)
			    {
				?>
				<option value="<?php echo $key; ?>"<?php if ($instance['gallery_name'] == $key) { echo 'selected="selected"'; } ?>><?php echo $key; ?> </option>
				<?php
				}
				?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('gallery_height'); ?>"><?php _e('Slide Height:'); ?></label>
				<input id="<?php echo $this->get_field_id('gallery_height'); ?>" name="<?php echo $this->get_field_name('gallery_height'); ?>" 
				value="<?php echo $instance['gallery_height']; ?>" type="text" class="widefat" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('gallery_width'); ?>"><?php _e('Slide Width:'); ?></label>
				<input id="<?php echo $this->get_field_id('gallery_width'); ?>" name="<?php echo $this->get_field_name('gallery_width'); ?>" 
				value="<?php echo $instance['gallery_width']; ?>" type="text" class="widefat" />
			</p>
		<?php
	}
	// Processes the admin options form when saved
	function update($new_instance, $old_instance) 
	{
		// Get the old values
		$instance = $old_instance;
		// Update with any new values (and sanitise input)
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['gallery_name'] = strip_tags( $new_instance['gallery_name'] );
		$instance['gallery_height'] = strip_tags( $new_instance['gallery_height'] );
		$instance['gallery_width'] = strip_tags( $new_instance['gallery_width'] );
		return $instance;
	}
} add_action('widgets_init', create_function('', 'return register_widget("rtp_Slideshow_Widget");'));
// Ending Widget Codes
// Starting Ajax
function rtp_slideshow_ajax_upload_callback()
{
	global $wpdb,$rtp_slideshow_misc_user_level;
	$image_url = $_POST['image_url'];
	$gallery_name = $_POST['gallery_name'];
	$image_title = $_POST['image_title'];
	$image_caption = $_POST['image_caption']; 
	$image_alttext = $_POST['image_alttext'];
	$image_desc = $_POST['image_desc'];
	$video_url = $_POST['video_url'];
	$type = $_POST['type'];
	$rtp_slideshow_imageupload_complete_data = unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
	$slide_count=count($rtp_slideshow_imageupload_complete_data[$gallery_name]);
	//************ insert image info to array ***************
	if($type=="image")
	{
		$rtp_slideshow_imageupload_complete_data[$gallery_name][$slide_count]=(array(
																		"type"=>$type,
																		"image_url" =>$image_url,
																		"image_title"=>$image_title,
																		"image_caption" =>$image_caption,
																		"image_alttext" =>$image_alttext,
																		"image_desc" =>$image_desc,
																		"link_url"=>"",
																		"link_target"=>""
																	  ));
	}
	else if($type=="youtube_video")
	{
		$regexstr = '~
            # Match Youtube link and embed code
            (?:                             # Group to match embed codes
                (?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
                |(?:                        # Group to match if older embed
                    (?:<object .*>)?      # Match opening Object tag
                    (?:<param .*</param>)*  # Match all param tags
                    (?:<embed [^>]*src=")?  # Match embed tag to the first quote of src
                )?                          # End older embed code group
            )?                              # End embed code groups
            (?:                             # Group youtube url
                https?:\/\/                 # Either http or https
                (?:[\w]+\.)*                # Optional subdomains
                (?:                         # Group host alternatives.
                youtu\.be/                  # Either youtu.be,
                | youtube\.com              # or youtube.com
                | youtube-nocookie\.com     # or youtube-nocookie.com
                )                           # End Host Group
                (?:\S*[^\w\-\s])?           # Extra stuff up to VIDEO_ID
                ([\w\-]{11})                # $1: VIDEO_ID is numeric
                [^\s]*                      # Not a space
            )                               # End group
            "?                              # Match end quote if part of src
            (?:[^>]*>)?                       # Match any extra stuff up to close brace
            (?:                             # Group to match last embed code
                </iframe>                 # Match the end of the iframe
                |</embed></object>          # or Match the end of the older embed
            )?                              # End Group of last bit of embed code
            ~ix';
 
        preg_match($regexstr, $video_url, $matches);
		$imgid = $matches[1];
		$thumbnail_image = "http://img.youtube.com/vi/".$imgid."/hqdefault.jpg";
		$rtp_slideshow_imageupload_complete_data[$gallery_name][$slide_count]=(array(
																		"type"=>$type,
																		"video_url" =>$video_url,
																		"thumbnail_image"=>$thumbnail_image,
																		"vedio_id" =>$imgid
																		
																	  ));
	}
	else if($type=="vimeo_video")
	{
		$regexstr = '~
				# Match Vimeo link and embed code
				(?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
				(?:                         # Group vimeo url
				https?:\/\/             # Either http or https
				(?:[\w]+\.)*            # Optional subdomains
				vimeo\.com              # Match vimeo.com
				(?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
				\/                      # Slash before Id
				([0-9]+)                # $1: VIDEO_ID is numeric
				[^\s]*                  # Not a space
				)                           # End group
				"?                          # Match end quote if part of src
				(?:[^>]*></iframe>)?        # Match the end of the iframe
				(?:<p>.*</p>)?              # Match any title information stuff
				~ix';
				preg_match($regexstr, $video_url, $matches); 
				$imgid = $matches[1];
				$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$imgid.".php"));
				if($hash==false)
				{
					alert('invalid url');
					die();
				}
				$thumbnail_image= $hash[0]['thumbnail_medium']; 
				//$xml = simplexml_load_file("http://vimeo.com/api/v2/video/".$imgid.".xml");
				//$xml = $xml->video;
				//$thumbnail_image = $xml->thumbnail_medium;
		$rtp_slideshow_imageupload_complete_data[$gallery_name][$slide_count]=(array(
																		"type"=>$type,
																		"video_url" =>$video_url,
																		"thumbnail_image"=>$thumbnail_image,
																		"vedio_id"=>$imgid
																	  ));
	}
	if (current_user_can($rtp_slideshow_misc_user_level)) 
	{
		update_option('rtp_slideshow_imageupload_complete_data',serialize($rtp_slideshow_imageupload_complete_data));
	}
	
	$slide_count=count($rtp_slideshow_imageupload_complete_data[$gallery_name]);
	//************** response display ********************
	echo "<div id='s_s_notice'>". __('Slides Inserted','simple-slideshow-manager') ."</div>";
	echo"<ul id = \"rtp_slideshow_sortable\">";
	for($i = 0  ; $i <$slide_count ; $i++)
	{
		
		if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
			echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
			echo "<img src = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']."\" alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" > &nbsp;";
			echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";
			echo "<div class=\"edit_but\" id=\"rtp_edit_image_".$i."\" onclick = \"rtp_edit(".$i.");\" title=\"Edit This Slide\"></div></br>";
			echo "<div class=\"add_link\" id=\"rtp_edit_image_".$i."\" onclick = \"rtp_edit(".$i.");\" title=\"Add Link To This Slide\"></div></br>";	
			echo "</li>";
		}
		else  if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{
				echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
		else  if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
 
					echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
	}
	echo"</ul>";
	die(); // this is required to return a proper result
} add_action('wp_ajax_rtp_slideshow_ajax_upload', 'rtp_slideshow_ajax_upload_callback');
function rtp_slideshow_ajax_updateRecordsListings_callback()
{
	global $wpdb,$rtp_slideshow_misc_user_level;
	$social_icon_array_order = $_POST['recordsArray'];
	$gallery_name = $_POST['gallery_name'];
	$rtp_slideshow_imageupload_complete_data=unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
	$countr = count($social_icon_array_order);
	for($i=0;$i<$countr; $i++)
	{
		$num = $social_icon_array_order[$i];
		$local[$i]=$rtp_slideshow_imageupload_complete_data[$gallery_name][$num];
	}	
	$rtp_slideshow_imageupload_complete_data[$gallery_name] = $local;
	// ********** response *************
	if (current_user_can($rtp_slideshow_misc_user_level)) 
	{
		update_option('rtp_slideshow_imageupload_complete_data',serialize($rtp_slideshow_imageupload_complete_data));
	}
	$slide_count=count($rtp_slideshow_imageupload_complete_data[$gallery_name]);
	echo "<div id='s_s_notice'>".__('Slides Sorted','simple-slideshow-manager') ."</div>";
	echo"<ul id = \"rtp_slideshow_sortable\">";
	//echo $slide_count;
	for($i = 0  ; $i <$slide_count ; $i++)
	{
			if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
			echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
			echo "<img src = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']."\" alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" > &nbsp;";
			echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";
			echo "<div class=\"edit_but\" id=\"rtp_edit_image_".$i."\" onclick = \"rtp_edit(".$i.");\" title=\"Edit This Slide\"></div></br>";
			echo "<div class=\"add_link\" id=\"rtp_edit_image_".$i."\" onclick = \"rtp_edit(".$i.");\" title=\"Add Link To This Slide\"></div></br>";	
			echo "</li>";
		}
		else  if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{
				echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
		else  if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		
		}
	}
	echo"</ul>";
	die(); // this is required to return a proper result
} add_action('wp_ajax_rtp_slideshow_ajax_updateRecordsListings', 'rtp_slideshow_ajax_updateRecordsListings_callback');
function rtp_slideshow_ajax_deleteimage_callback()
{
	global $wpdb,$rtp_slideshow_misc_user_level;
	$gallery_name = $_POST['gallery_name'];
	$index = $_POST['index'];
	$rtp_slideshow_imageupload_complete_data = unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
	$key = array_search($index, $rtp_slideshow_imageupload_complete_data[$gallery_name]); 
	unset($rtp_slideshow_imageupload_complete_data[$gallery_name][$index]);
	$rtp_slideshow_imageupload_complete_data[$gallery_name] = array_values($rtp_slideshow_imageupload_complete_data[$gallery_name]);
	if (current_user_can($rtp_slideshow_misc_user_level)) 
	{
		update_option('rtp_slideshow_imageupload_complete_data',serialize($rtp_slideshow_imageupload_complete_data));
	}
	$slide_count=count($rtp_slideshow_imageupload_complete_data[$gallery_name]);
	echo "<div id='s_s_notice'>". __('Slides Deleted','simple-slideshow-manager') ."</div>";
	echo"<ul id = \"rtp_slideshow_sortable\">";
	// ************** response display ******************** 
	for($i = 0  ; $i <$slide_count ; $i++)
	{
			if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="image")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
			echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
			echo "<img src = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_url']."\" alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_alttext']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" > &nbsp;";
			echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['image_title']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";
			echo "</li>";
		}
		else  if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="youtube_video")
		{
			   echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
		else  if($rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['type']=="vimeo_video")
		{
			echo "<li class=\"ui-state-default\" id = \"recordsArray_".$i."\">";
				echo "<span class=\"ui-icon ui-icon-arrowthick-2-n-s\"></span>";
				echo "<img src=\"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['thumbnail_image']."\"  alt = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" title = \"".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" > &nbsp;";
				echo "<div class=\"play_but\" title=\"\"></div></br>";
				echo "<div class=\"del_but\" id=\"rtp_delete_image_".$rtp_slideshow_imageupload_complete_data[$gallery_name][$i]['video_url']."\" onclick = \"rtp_delete(".$i.");\" title=\"Delete This Slide\"></div></br>";	
				echo "</li>";
		}
	}
	echo"</ul>";	
	die(); // this is required to return a proper result
} add_action('wp_ajax_rtp_slideshow_ajax_deleteimage', 'rtp_slideshow_ajax_deleteimage_callback');
function rtp_slideshow_ajax_editimage_callback()
{
	global $wpdb;
	$gallery_name = $_POST['gallery_name'];
	$index = $_POST['index'];
	$rtp_slideshow_imageupload_complete_data = unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
	$title = $rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['image_title'];
	$alttext = $rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['image_alttext'];
	$url= $rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['link_url'];
	$target =  $rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['link_target'];
	echo"<form name=\"rtp_editimage_form\" id=\"rtp_editimage_form\" method=\"post\" action=\"\">";
	echo"<table cellpadding=\"0\"><tr><td>". __('Image Title','simple-slideshow-manager') ."</td><td><input type=\"text\" id=\"rtp_slideshow_edit_title\" name=\"rtp_slideshow_edit_title\" size=\"40\" value=\"".$title."\" /></td></tr>";
	echo"<tr><td>". __('Image Alt Text','simple-slideshow-manager') ."</td><td><input type=\"text\" id=\"rtp_slideshow_edit_alt\" name=\"rtp_slideshow_edit_alt\" size=\"40\" value=\"".$alttext."\" /></td></tr>";
	echo"<tr><td>". __('Link URL','simple-slideshow-manager') ."</td><td><input type=\"text\" id=\"rtp_slideshow_edit_url\" name=\"rtp_slideshow_edit_url\" size=\"40\" value=\"".$url."\" /></td></tr>"; ?>
	<tr><td><?php _e('Target','simple-slideshow-manager'); ?></td><td><select id="rtp_link_target">
	<option value="_blank" <?php if ($target == "_blank") { echo 'selected="selected"'; } ?>><?php _e('_blank','simple-slideshow-manager'); ?></option>
	<option value="_self" <?php if ($target == "_self") { echo 'selected="selected"'; } ?>><?php _e('_self','simple-slideshow-manager'); ?></option>
	<option value="_parent" <?php if ($target == "_parent") { echo 'selected="selected"'; } ?>><?php _e('_parent','simple-slideshow-manager'); ?></option>
	<option value="_top" <?php if ($target == "_top") { echo 'selected="selected"'; } ?>><?php _e('_top','simple-slideshow-manager'); ?></option>
	</select></td></tr>
	<?php
	echo"<tr><td colspan=\"2\"><a href=\"#\" class=\"button can_edit\" id=\"rtp_edit_image_link\" onclick=\"rtp_slideshow_change_edittext_cancel();\" >". __('Cancel','simple-slideshow-manager') ."</a>";
	echo"<a href=\"#\" class=\"button edit_edit\" id=\"rtp_edit_image_link\" onclick=\"rtp_slideshow_change_edittext(".$index.");\" >".__('Update','simple-slideshow-manager') ."</a></td></tr></table>";
	echo"</form>";
	die(); // this is required to return a proper result
}add_action('wp_ajax_rtp_slideshow_ajax_editimage','rtp_slideshow_ajax_editimage_callback');
function rtp_slideshow_ajax_changeedittext_callback()
{
	global $wpdb,$rtp_slideshow_misc_user_level;
	$gallery_name = $_POST['gallery_name'];
	$index = $_POST['index'];
	$title = $_POST['title'];
	//$caption = $_POST['caption'];
	$alttext = $_POST['alttext'];
	//$desc = $_POST['desc'];
	$url = $_POST['url'];
	$target = $_POST['target'];
	$rtp_slideshow_imageupload_complete_data = unserialize(get_option('rtp_slideshow_imageupload_complete_data'));
	$rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['image_title']= $title;
	//$rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['image_caption']= $caption;
	$rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['image_alttext']= $alttext;
	//$rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['image_desc']= $desc;
	$rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['link_url']= $url;
	$rtp_slideshow_imageupload_complete_data[$gallery_name][$index]['link_target']= $target;
	if (current_user_can($rtp_slideshow_misc_user_level)) 
	{
		update_option('rtp_slideshow_imageupload_complete_data',serialize($rtp_slideshow_imageupload_complete_data));
	}
	die(); // this is required to return a proper result
	
}add_action('wp_ajax_rtp_slideshow_ajax_changeedittext','rtp_slideshow_ajax_changeedittext_callback');
function rtp_slideshow_pluign_promotion()
{
	$rtp_tweet_text_array = array
						(
						"I am using Simple Slideshow Manager worspress plugin from @acuraxdotcom and you should too",
						"It looks awesome thanks @acuraxdotcom for developing such a wonderful Slideshow plugin",
						"Simple Slideshow Manager from @acuraxdotcom very simple and easy to configure",
						"I have been using Simple Slideshow Manager plugin for a while and it looks nice",
						"Its very nice and flexible Slideshow plugin thanks @acuraxdotcom",
						"Simple Slideshow Managers user interface looks very simple and easy to understand.Good job @acuraxdotcom..", 
						"Simple Slideshow Manager from @acuraxdotcom is too much understandable to beginners.",
						"I installed Simple Slideshow Manager wordpress plugin from @acuraxdotcom and it looks awesome.",
						);
$rtp_tweet_text = array_rand($rtp_tweet_text_array, 1);
$rtp_tweet_text = $rtp_tweet_text_array[$rtp_tweet_text];
 //echo $rtp_tweet_text;
    echo '<div id="rtp_td" class="error" style="background: none repeat scroll 0pt 0pt infobackground; border: 1px solid inactivecaption; padding: 5px;line-height:16px;">
	<p>It looks like you have been enjoying using Simple Slideshow Manager plugin from <a href="http://www.acurax.com?utm_source=plugin&utm_medium=thirtyday&utm_campaign=ssm" title="Acurax Web Designing Company" target="_blank">Acurax</a> for atleast 30 days.Would you consider upgrading to <a href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=thirtyday_yellow&utm_campaign=ssm" title="Premium Simple Slideshow Manager" target="_blank">premium version</a> to enjoy more features and help support continued development of the plugin? - Spreading the world about this plugin. Thank you for using the plugin</p>
	<p>
	<a href="http://wordpress.org/support/view/plugin-reviews/simple-slideshow-manager" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Rate it 5★\'s on wordpress</a>
	<a href="https://twitter.com/share?url=http://www.acurax.com/products/simple-advanced-slideshow-manager/&text='.$rtp_tweet_text.' -" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Tell Your Followers</a>
	<a href="http://www.acurax.com/products/simple-advanced-slideshow-manager/?utm_source=plugin&utm_medium=thirtyday&utm_campaign=ssm" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Order Premium Version</a>
	<a href="admin.php?page=Acurax-Slideshow-Misc&td=hide" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;margin-left:20px;">Don\'t Show This Again</a>
</p>
		  
		  </div>';
}
$rtp_slideshow_installed_date = get_option('rtp_slideshow_installed_date');
if ($rtp_slideshow_installed_date=="") {
$rtp_slideshow_installed_date = time();
update_option('rtp_slideshow_installed_date',$rtp_slideshow_installed_date);
}
$rtp_slideshow_installed_date = get_option('rtp_slideshow_installed_date');
if($rtp_slideshow_installed_date < ( time() - 2952000 ))
{
	if (get_option('rtp_ssm_td') != "hide")
	{
		add_action('admin_notices', 'rtp_slideshow_pluign_promotion',1);
	}
}
function rtp_slideshow_pluign_finish_version_update()
{
    echo '<div id="message" class="updated">
		  <p><b>Thanks for updating Simple Slideshow Manager plugin... You need to visit <a href="admin.php?page=Acurax-Slideshow-Settings&status=updated#updated">Plugin\'s Settings Page</a> to Complete the Updating Process - <a href="admin.php?page=Acurax-Slideshow-Settings&status=updated#updated">Click Here Visit Simple Slideshow Manager Plugin Settings</a></b></p>
		  </div>';
}
$rtp_slideshow_version = get_option('rtp_slideshow_version');
if($rtp_slideshow_version < "1.2.2") // << Old Version // Current Verison
{
	add_action('admin_notices', 'rtp_slideshow_pluign_finish_version_update',1);
}
function rtp_slideshow_pluign_old_version()
{
    echo '<div id="message" class="error">
		  <p><b>You are using an old version of wordpress, You need to have wordpress version 3.5 or above to have the plugin to work properly, please update your wordpress installation.</b></p>
		  </div>';
}
if(!function_exists('wp_enqueue_media'))
{
	add_action('admin_notices', 'rtp_slideshow_pluign_old_version',1);
}
function rtp_ssm_admin_style()  // Adding Style For Admin
{
global $rtp_si_fsmi_menu_highlight;
	echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('style_admin.css', __FILE__). '">';
}	add_action('admin_head', 'rtp_ssm_admin_style'); // ADMIN
?>