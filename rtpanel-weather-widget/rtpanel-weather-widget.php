<?php
/*
	Plugin Name: rtPanel Weather Widget
	Description: This plugin displays beautiful and simple weather widget. 
	Version: 2.5
	Author: rtcamp
	Author URI: https://rtcamp.com
	Contributors: rtCampers ( https://rtcamp.com/about/rtcampers/ )
	License: GNU General Public License, v2 (or newer)
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
 
add_action( 'widgets_init', 'rtp_weather_load_widgets' );

function rtp_weather_load_widgets() {
	register_widget( 'rtp_weather_widget' );
}
function rtp_weather_custom_styles() {
    wp_enqueue_style( 'rtp-weather-css', plugins_url( plugin_basename(dirname(__FILE__)).'/css/style.css' ) );
}
add_action( 'wp_print_styles', 'rtp_weather_custom_styles' );
 
class rtp_weather_widget extends WP_Widget {
	/**
	 * Widget setup.
	 */
	function rtp_weather_widget() {
		/* Widget settings. */
		$widget_options = array( 
		 'classname' => 'rtp_weather_widget', 
		 'description' => __('Displays weather forecast.') );

		/* Widget control settings. */
		$control_options = array( 
		'width' => 300, 
		'height' => 230, 
		'id_base' => 'rtp_weather_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'rtp_weather_widget', 'RTP - Weather Widget', $widget_options, $control_options );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
			
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$esoid =($instance['esoid'] != "")?$instance['esoid'] :"budapest";
		$name =($instance['name'] != "")?$instance['name'] :"Surat";
		$image = empty($instance['image']) ? '' : $instance['image'];
		$is_template_path = isset($instance['is_template_path']) ? $instance['is_template_path'] : false;
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
			
		
		/* Make Cache for optimization */
		
		 $cache = dirname(__FILE__) . '/cache';
		 
		/* Load the weather data from sotanc.hu */
		 
		function retrieveYahooWeather($zipCode="HUXX0002_f.xml") {
		$cache = dirname(__FILE__) . '/cache';
		$yahooUrl = "http://xml.weather.yahoo.com/forecastrss/";
		$yahooZip = "$zipCode";
		$yahooFullUrl = $yahooUrl . $yahooZip; 
		$curlObject = curl_init();
		curl_setopt($curlObject,CURLOPT_URL,$yahooFullUrl);
		curl_setopt($curlObject,CURLOPT_HEADER,false);
		curl_setopt($curlObject,CURLOPT_RETURNTRANSFER,true);
		$returnYahooWeather = curl_exec($curlObject);
		curl_close($curlObject);
		$cachefile = fopen($cache, 'w');
		fwrite($cachefile, $returnYahooWeather);
		fclose($cachefile);
		return $returnYahooWeather;
    	}
		
		/* Make Cache for optimization */
		
		$cache = dirname(__FILE__) . '/cache';
		if(filemtime($cache) < (time() - 20)) 
		{
			$localZipCode = $esoid; 
			$weatherXmlString = retrieveYahooWeather($localZipCode); // Itt tölti be a függvényt
		}
		else
		{
			$weatherXmlString = file_get_contents($cache);
		}
		
		$weatherXmlObject = new SimpleXMLElement($weatherXmlString);
		$Forecast = $weatherXmlObject->xpath("//yweather:forecast");
		$_datum=getdate();
		$max[0] = round(($Forecast[0]["high"]-32)*0.55556);
		$max[1] = round(($Forecast[1]["high"]-32)*0.55556);
		$max[2] = round(($Forecast[2]["high"]-32)*0.55556);
		$max[3] = round(($Forecast[3]["high"]-32)*0.55556);
		$max[4] = round(($Forecast[4]["high"]-32)*0.55556);

		$min[0] = round(($Forecast[0]["low"]-32)*0.55556);
		$min[1] = round(($Forecast[1]["low"]-32)*0.55556);
		$min[2] = round(($Forecast[2]["low"]-32)*0.55556);
		$min[3] = round(($Forecast[3]["low"]-32)*0.55556);
		$min[4] = round(($Forecast[4]["low"]-32)*0.55556);

		$ikon[0] = $Forecast[0]["code"];
		$ikon[1] = $Forecast[1]["code"];
		$ikon[2] = $Forecast[2]["code"];
		$ikon[3] = $Forecast[3]["code"];
		$ikon[4] = $Forecast[4]["code"];
		
		$ikon_text = array();
		
		$i=0;
		while ($i<5) 
		{
			if ($ikon[$i]==0) {$ikon[$i]="tornado.png"; $ikon_text[$i] = "Chance of Heavy Rain";}
			if ($ikon[$i]==1) {$ikon[$i]="tropusi.png"; $ikon_text[$i] = "";}
			if ($ikon[$i]==2) {$ikon[$i]="hurrikan.png"; $ikon_text[$i] = "Chance of Heavy Rain";}
			if ($ikon[$i]==3) {$ikon[$i]="ezivatar.png"; $ikon_text[$i] = "Thunderstorm";}
			if ($ikon[$i]==4) {$ikon[$i]="ezivatar.png"; $ikon_text[$i] = "Thunderstorm";}
			if ($ikon[$i]==5) {$ikon[$i]="havaseso.png"; $ikon_text[$i] = "Chance of Rain & Snow";}
			if ($ikon[$i]==6) {$ikon[$i]="onoseso.png"; $ikon_text[$i] = "Rainy";}
			if ($ikon[$i]==7) {$ikon[$i]="onoseso.png"; $ikon_text[$i] = "Rainy";}
			if ($ikon[$i]==8) {$ikon[$i]="onosszital.png"; $ikon_text[$i] = "Rainy";}// ónosszitálás
			if ($ikon[$i]==9) {$ikon[$i]="szitalas.png"; $ikon_text[$i] = "";}
			if ($ikon[$i]==10) {$ikon[$i]="onoseso.png"; $ikon_text[$i] = "Rainy";}  // fagyos eső
			if ($ikon[$i]==11) {$ikon[$i]="eso.png"; $ikon_text[$i] = "Chance of Rain";}
			if ($ikon[$i]==12) {$ikon[$i]="eso.png"; $ikon_text[$i] = "Chance of Rain";}
			if ($ikon[$i]==13) {$ikon[$i]="hozapor.png"; $ikon_text[$i] = "Chance of Snow";}
			if ($ikon[$i]==14) {$ikon[$i]="hozapor.png"; $ikon_text[$i] = "Chance of Snow";} //kevés hózápor
			if ($ikon[$i]==15) {$ikon[$i]="hovihar.png"; $ikon_text[$i] = "";}
			if ($ikon[$i]==16) {$ikon[$i]="havazas.png"; $ikon_text[$i] = "Chance of Snow";}
			if ($ikon[$i]==17) {$ikon[$i]="jeg.png"; $ikon_text[$i] = "Chance of Rain & Snow";}
			if ($ikon[$i]==18) {$ikon[$i]="onoseso.png"; $ikon_text[$i] = "Rainy";}
			if ($ikon[$i]==19) {$ikon[$i]="por.png"; $ikon_text[$i] = "Windy";}
			if ($ikon[$i]==20) {$ikon[$i]="kodos.png"; $ikon_text[$i] = "Windy";}
			if ($ikon[$i]==21) {$ikon[$i]="paras.png"; $ikon_text[$i] = "Windy";}
			if ($ikon[$i]==22) {$ikon[$i]="szmog.png"; $ikon_text[$i] = "Windy";}
			if ($ikon[$i]==23) {$ikon[$i]="szeles.png"; $ikon_text[$i] = "Windy";}
			if ($ikon[$i]==24) {$ikon[$i]="szeles.png"; $ikon_text[$i] = "Windy";}
			if ($ikon[$i]==25) {$ikon[$i]="hideg.png"; $ikon_text[$i] = "";}
			if ($ikon[$i]==26) {$ikon[$i]="felhos.png"; $ikon_text[$i] = "Cloudy";}
			if ($ikon[$i]==27) {$ikon[$i]="eefelhos.png"; $ikon_text[$i] = "Cloudy";}
			if ($ikon[$i]==28) {$ikon[$i]="nefelhos.png"; $ikon_text[$i] = "Cloudy";}
			if ($ikon[$i]==29) {$ikon[$i]="ekfelhos.png"; $ikon_text[$i] = "Cloudy";}
			if ($ikon[$i]==30) {$ikon[$i]="nkfelhos.png"; $ikon_text[$i] = "Partly Cloudy";}
			if ($ikon[$i]==31) {$ikon[$i]="ederult.png"; $ikon_text[$i] = "Partly Cloudy";}
			if ($ikon[$i]==32) {$ikon[$i]="napos.png"; $ikon_text[$i] = "Sunshine";}
			if ($ikon[$i]==33) {$ikon[$i]="derult.png"; $ikon_text[$i] = "Partly Cloudy";} //kevés felhő
			if ($ikon[$i]==34) {$ikon[$i]="derult.png"; $ikon_text[$i] = "Partly Cloudy";}  // kevés felhő
			if ($ikon[$i]==35) {$ikon[$i]="esojeg.png"; $ikon_text[$i] = "Chance of Rain & Snow";}
			if ($ikon[$i]==36) {$ikon[$i]="forro.png"; $ikon_text[$i] = "";}
			if ($ikon[$i]==37) {$ikon[$i]="zivatar.png"; $ikon_text[$i] = "Thunderstorm";}
			if ($ikon[$i]==38) {$ikon[$i]="ezivatar.png"; $ikon_text[$i] = "Thunderstorm";}
			if ($ikon[$i]==39) {$ikon[$i]="zapor.png"; $ikon_text[$i] = "Chance of Rain & Snow";}
			if ($ikon[$i]==40) {$ikon[$i]="zapor.png"; $ikon_text[$i] = "Chance of Rain & Snow";}
			if ($ikon[$i]==41) {$ikon[$i]="havazas.png"; $ikon_text[$i] = "Chance of Snow";}
			if ($ikon[$i]==42) {$ikon[$i]="hozapor.png"; $ikon_text[$i] = "Chance of Snow";}
			if ($ikon[$i]==43) {$ikon[$i]="havazas.png"; $ikon_text[$i] = "Chance of Snow";}
			if ($ikon[$i]==44) {$ikon[$i]="kfelhos.png"; $ikon_text[$i] = "Partly Cloudy";}
			if ($ikon[$i]==45) {$ikon[$i]="zivatar.png"; $ikon_text[$i] = "Thunderstorm";}
			if ($ikon[$i]==46) {$ikon[$i]="hozapor.png"; $ikon_text[$i] = "Chance of Snow";}
			if ($ikon[$i]==47) {$ikon[$i]="zivatar.png"; $ikon_text[$i] = "Thunderstorm";}
			if ($ikon[$i]==3200) {$ikon[$i]="nincsadat.png"; $ikon_text[$i] = "No Data";}
			++$i;
		}
		
		$_datum=getdate();
		$i=0;
		while ($i<12) 
		{
			$_name_of_month[$i]=$lan[1][$i];
			$_name_of_day[$i]=$lan[2][$i];
			++$i;
		}
		$i=0;
		while ($i<3) 
		{
			$minch[$i]=0;$maxch[$i]=0;$daych[$i]=0;
			
			if (strlen($min[$i])==1) {$minch[$i]=3;}
			if (strlen($min[$i])==3) {$minch[$i]=-5;}
			
			if (strlen($max[$i])==1) {$maxch[$i]=3;}
			if (strlen($max[$i])==3) {$maxch[$i]=-5;}
					
			$day[$i]=date("j",(mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"))));
			$dayname[$i]=date("l",(mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"))));
			$month[$i]=date("M",(mktime(0, 0, 0, date("m")  , date("d")+$i, date("Y"))));
			++$i;
		}
		
		/* Constuct the widget output */	
		$output = '';
		
		if(!empty($image)):
			if($is_template_path == true)
				$image_style = "background:url(" . plugins_url( plugin_basename(dirname(__FILE__)).'/img/'.$image ) . ") no-repeat scroll 0 0 transparent";
			else
				$image_style = "background:url(" . $image . ") no-repeat scroll 0 0 transparent";
		else:
			$image_style = "";
		endif;
		if ( $title )
			$output .= '<h3 class="title">' . $title . '</h3>';			
		$output .= '<div class="weather-container" style="'.$image_style.'">';		
			for($i=0;$i<3;$i++)
			{
				if($i == 0)
					$class= " current";
				else
					$class= "";
				$output .= '<div class="day-container">';			
				$output .= '<span class="day '.$class.'">'.$dayname[$i].'</span><br/>';
				$output .= '<img class="icon" src = "' . plugins_url( 'img/'.$ikon[$i] , __FILE__ ) . '"/><br/>';			
				$output .= '<span class="text">'.$ikon_text[$i].'</span><br/>';
				$output .= '<span>'.$max[$i].'&deg;</span> / ';
				$output .= '<span>'.$min[$i].'&deg;</span>';
				$output .= '</div>';
			}	
		$output .= '</div>';		
		echo $output;				
		
		/* After widget (defined by themes). */
		echo $after_widget;
	} 

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['esoid'] = strip_tags( $new_instance['esoid'] );
		$instance['name'] = strip_tags( $new_instance['name'] );
		$instance['image'] = strip_tags($new_instance['image']);
		$instance['is_template_path'] = false;
		if (isset($new_instance['is_template_path'])) $instance['is_template_path'] = true;
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 
		'title' => 'Weather', 
		'esoid' => 'INXX0157_f.xml',
		'name' => 'Surat',
		'image' => 'weather-forecast.jpg',
		'is_template_path' => true
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','title'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" type="text" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'esoid' ); ?>"><?php _e('EsoID: Find the nearest forecast site to your town! <br/> Find the ID here:<a href="http://esotanc.hu/wordpress-weather-widget" TARGET="_blank">EsoID</a>', 'esoid'); ?></label></br>
			<input id="<?php echo $this->get_field_id( 'esoid' ); ?>" name="<?php echo $this->get_field_name( 'esoid' ); ?>" value="<?php echo $instance['esoid']; ?>" type="text" class="widefat" />
		</p>
		<p>
			<p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('Enter your city name:', 'name'); ?></label>
			<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" type="text" class="widefat" />
		</p>
		</p>
		<p>
			<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e('Enter image full url:', 'rtPanel'); ?></label>
			<input id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php echo $instance['image']; ?>" type="text" class="widefat"/>
			<input id="<?php echo $this->get_field_id('is_template_path'); ?>" name="<?php echo $this->get_field_name('is_template_path'); ?>" class="checkbox" type="checkbox" <?php checked($instance['is_template_path'], true) ?>/>			
			<label for="<?php echo $this->get_field_id( 'is_template_path' ); ?>"><?php _e('Use Template Path for Image', 'rtPanel'); ?></label>
		</p>
		</p>
	<?php
	}
}
?>