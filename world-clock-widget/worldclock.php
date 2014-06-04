<?php
/*
Plugin Name: World Clock
Version: 1.0.0
Plugin URI: http://wordpress.org/extend/plugins/world-clock-widget/
Description: Adds a multi-timezone clock widget to the sidebar.
Author: Xander Tan
Author URI: http://craeser.wordpress.com/
*/
class WorldClock
{

	// This is the timezone array

	var $timezones = array();

	// This is the plugin URL

	var $pluginurl = '';
	public static $registerName = 'World Clock Widget';
	public static $version = '1.0.0';
	public static $functControl = 'controlWidget';
	public static $functDisplay = 'displayWidget';
	public static $width = 420;
	public static $height = 300;

	var $fileTimezone = '/worldclock.xml';
	var $fileDateJs = '/date.js';
	var $fileWorldClockJs = '/worldclock.js';
	var $fileControlJs = '/worldclock_control.js';
	var $keyOption = 'world_clock_widget';
	var $keyVersion = 'wc-version';
	var $keyTitle = 'wc-title';
	var $keyDateFmt = 'wc-dateformat';
	var $keyTimeFmt = 'wc-timeformat';
	var $keyTextAlg = 'wc-textalign';
	var $keyHorizon = 'wc-horizontal';
	var $keyTimeSrc = 'wc-timesource';
	var $keyClocks = 'wc-clocks';
	var $keyEditTitle = 'worldclock_widget_title_edit';
	var $keyEditDateFmt = 'worldclock_dateformat_edit';
	var $keyEditTimeFmt = 'worldclock_timeformat_edit';
	var $keyEditTextAlg = 'worldclock_textalign_edit';
	var $keyEditHorizon = 'worldclock_horizontal_edit';
	var $keyEditTimeSrc = 'worldclock_timesource_edit';
	var $keyEditCityId = 'worldclock_id_edit';
	var $keyEditCity = 'worldclock_city_edit';
	var $keyEditTz = 'worldclock_tz_edit';
	var $keyEditDst = 'worldclock_dst_edit';
	var $keyTzDisplay = 'display';
	var $keyTzOffset = 'offset';
	var $keyTzOffsetD = 'offset_d';
	var $keyTzDaylight = 'daylight';
	var $keyTzDstStart = 'dst_start';
	var $keyTzDstEnd = 'dst_end';
	var $defTitle = 'World Clock';
	var $defDateFmt = 'ddd, MMM d, yyyy';
	var $defTimeFmt = 'h:mm:ss tt';
	var $defTextAlg = 'left';
	var $defHorizon = 'no';
	var $defTimeSrc = 'client';
	var $msgNoClock = 'No clock configured.';
	/** Initialization: Static init callback **/
	function init()
	{

		// check for the required plugin functions. This will prevent fatal
		// errors occuring when you deactivate the dynamic-sidebar plugin.

		if (!function_exists('register_sidebar_widget')) return;
		$widget = new WorldClock();

		// This registers our widget so it appears with the other available
		// widgets and can be dragged and dropped into any active sidebars.

		register_sidebar_widget(WorldClock::$registerName, array(
			$widget,
			WorldClock::$functDisplay
		));

		// This registers our optional widget control form.

		register_widget_control(WorldClock::$registerName, array(
			$widget,
			WorldClock::$functControl
		) , WorldClock::$width, WorldClock::$height);
	}

	/** Constructor: When this class instance is created, the __construct function is run first **/
	function __construct()
	{

		// Set the global plugin URL variable
		// patch by aikson (aikson@users.sourceforge.net)
		// support WP < 2.6

		if (!function_exists('plugins_url')) {
			$this->pluginurl = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
		}
		else {
			$this->pluginurl = plugins_url(plugin_basename(dirname(__FILE__)));
		}

		// Initialize global settings

		$options = get_option($this->keyOption);

		// Initialize array of clocks

		if (!is_array($options)) {
			$options = array(
				'wc-clocks' => array()
			);
		}

		// Commit initialization

		update_option($this->keyOption, $options);

		// Initialize global settings

		$this->constructVar($this->keyVersion, WorldClock::$version);
		$this->constructVar($this->keyTitle, $this->defTitle);
		$this->constructVar($this->keyDateFmt, $this->defDateFmt);
		$this->constructVar($this->keyTimeFmt, $this->defTimeFmt);
		$this->constructVar($this->keyTextAlg, $this->defTextAlg);
		$this->constructVar($this->keyHorizon, $this->defHorizon);
		$this->constructVar($this->keyTimeSrc, $this->defTimeSrc);

		// Load global timezone data

		$this->constructTimezoneData();
	}

	function constructVar($key, $value)
	{
		$options = get_option($this->keyOption);
		if (isset($options[$key])) return;
		$options[$key] = $value;
		update_option($this->keyOption, $options);
	}

	function constructTimezoneData()
	{
		$doc = new DOMDocument();
		$doc->load($this->pluginurl . $this->fileTimezone);
		$wcindex = 0;
		$worldclocks = $doc->getElementsByTagName('worldclock');
		foreach($worldclocks as $worldclock) {
			$display = $worldclock->getElementsByTagName('display');
			$offset = $worldclock->getElementsByTagName('offset');
			$offsetdisplay = $worldclock->getElementsByTagName('offsetdisplay');
			$dst = $worldclock->getElementsByTagName('dst');
			$dststart = $worldclock->getElementsByTagName('dststart');
			$dstend = $worldclock->getElementsByTagName('dstend');
			$dststart_time = '';
			$dstend_time = '';

			// Configure DST setting

			$dst_bool = ($dst->item(0)->nodeValue == 'true') ? true : false;
			if ($dst_bool) {
				$dststart_str = $dststart->item(0)->nodeValue;
				$dstend_str = $dstend->item(0)->nodeValue;
				$dststart_time = $this->constructDstTime($dststart_str);
				$dstend_time = $this->constructDstTime($dstend_str);
			}

			// Load timezone setting into global array

			$this->timezones[$wcindex][$this->keyTzDisplay] = $display->item(0)->nodeValue;
			$this->timezones[$wcindex][$this->keyTzOffset] = $offset->item(0)->nodeValue;
			$this->timezones[$wcindex][$this->keyTzOffsetD] = $offsetdisplay->item(0)->nodeValue;
			$this->timezones[$wcindex][$this->keyTzDaylight] = $dst_bool;
			$this->timezones[$wcindex][$this->keyTzDstStart] = $dststart_time;
			$this->timezones[$wcindex][$this->keyTzDstEnd] = $dstend_time;
			$wcindex++;
		}
	}

	function constructDstTime($dst_str)
	{
		$dst_arr = explode(",", $dst_str);
		return mktime($dststart_arr[0], $dststart_arr[1], $dststart_arr[2], $dststart_arr[3], $dststart_arr[4], $dststart_arr[5]);
	}

	/** Display Widget: Below this line is the display functions for public view **/
	function displayWidget($args)
	{

		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.

		extract($args);

		// Get our options

		$options = get_option($this->keyOption);
		echo $before_widget;
		echo $before_title . $options[$this->keyTitle] . $after_title;
		if (!isset($options[$this->keyClocks])) {
			echo $this->msgNoClock;
		}
		else {
			$wc_clocks = $options[$this->keyClocks];
			$wc_textalign = $options[$this->keyTextAlg];
			$wc_horizontal = ($options[$this->keyHorizon] == 'yes') ? true : false;
			$this->displayClocks($wc_clocks, $wc_textalign, $wc_horizontal);
			$this->displayJavascript($options);
		}

		echo $after_widget;
	}

	function displayClocks($wc_clocks, $wc_align = 'left', $wc_horizontal = false)
	{
		echo '<br/><div style="text-align:' . $wc_align . ';">';
		for ($i = 0; $i < count($wc_clocks); ++$i) {
			if ($wc_horizontal) echo '<div style="float:left;width:200px;">';
			echo '<strong>' . $wc_clocks[$i]['city'] . '</strong><div id="city' . $i . '"></div><br/>';
			if ($wc_horizontal) echo '</div>';
		}

		echo '</div>';
	}

	function displayJavascript($options)
	{
		$wc_clocks = $options[$this->keyClocks];
		$urlDateJs = $this->pluginurl . $this->fileDateJs;
		$urlWorldClockJs = $this->pluginurl . $this->fileWorldClockJs;
		echo '<script type="text/javascript" src="' . $urlDateJs . '"></script>';
		echo '<script type="text/javascript" src="' . $urlWorldClockJs . '"></script>';
		echo '<script type="text/javascript">';
		echo 'var dateformat="' . $options[$this->keyDateFmt] . '";';
		echo 'var timeformat="' . $options[$this->keyTimeFmt] . '";';
		echo 'var serverclock=' . (($options[$this->keyTimeSrc] == 'server') ? 'true' : 'false') . ';';
		echo 'var gmttime=[' . gmdate('Y') . ',' . (gmdate('n') - 1) . ',' . gmdate('j') . ',' . gmdate('G') . ',' . gmdate('i') . ',' . gmdate('s') . '];';
		echo 'function worldClockWidget() {';
		for ($i = 0; $i < count($wc_clocks); ++$i) {
			$dst = 'false';
			if ($this->timezones[$wc_clocks[$i]['tz']]['daylight'] == true) {
				if ($wc_clocks[$i]['dst'] == true && $this->timezones[$wc_clocks[$i]['tz']]['dst_start'] < time() && $this->timezones[$wc_clocks[$i]['tz']]['dst_end'] > time()) {
					$dst = 'true';
				}
			}

			echo 'document.getElementById("city' . $i . '").innerHTML=worldClock(' . $this->timezones[$wc_clocks[$i]['tz']]['offset'] . ',' . $dst . ',dateformat,timeformat,serverclock);';
		}

		echo 'gmttime[5] += 1;';
		echo 'setTimeout("worldClockWidget()",1000);';
		echo '}';
		echo 'window.onload=worldClockWidget;';
		echo '</script>';
	}

	/** Control Widget: Below this line is the control functions for admin **/
	function controlWidget()
	{

		// Save global option variables

		$this->controlSaveGlobal($this->keyEditTitle, $this->keyTitle);
		$this->controlSaveGlobal($this->keyEditDateFmt, $this->keyDateFmt);
		$this->controlSaveGlobal($this->keyEditTimeFmt, $this->keyTimeFmt);
		$this->controlSaveGlobal($this->keyEditTextAlg, $this->keyTextAlg);
		$this->controlSaveGlobal($this->keyEditHorizon, $this->keyHorizon);
		$this->controlSaveGlobal($this->keyEditTimeSrc, $this->keyTimeSrc);

		// Save clock functions

		$this->controlSaveNewClock(); // Save new clock
		$this->controlEditExistingClock(); // Edit existing clock
		$this->controlRemoveExistingClock(); // Remove existing clock

		// Retrieve option variables

		$options = get_option($this->keyOption);
		$wc_clocks = $options[$this->keyClocks];
		$wc_title = $options[$this->keyTitle];
		$wc_dateformat = $options[$this->keyDateFmt];
		$wc_timeformat = $options[$this->keyTimeFmt];
		$wc_textalign = $options[$this->keyTextAlg];
		$wc_horizontal = $options[$this->keyHorizon];
		$wc_timesource = $options[$this->keyTimeSrc];
		$globalTitle = $this->controlGlobalTitle($wc_title);
		$globalDateTimeFormat = $this->controlGlobalDateTimeFormat($wc_dateformat, $wc_timeformat);
		$globalTextAlign = $this->controlGlobalTextAlign($wc_textalign);
		$globalHorizontal = $this->controlGlobalHorizontal($wc_horizontal);
		$globalTimeSource = $this->controlGlobalTimeSource($wc_timesource);
		$existingClocks = $this->controlExistingClocks($wc_clocks);
		$editClockCityId = $this->controlEditCityId();
		$editClockCity = $this->controlEditCity();
		$editClockTimezone = $this->controlEditTimezone();
		$editClockDst = $this->controlEditDst();
		$newClockCity = $this->controlNewCity();
		$newClockTimezone = $this->controlNewTimezone();
		$newClockDst = $this->controlNewDst();
		$urlControlJs = $this->pluginurl . $this->fileControlJs;
		$globalContent = $globalTitle . $globalDateTimeFormat . $globalTextAlign . $globalHorizontal . $globalTimeSource;
		$existingClocksContent = $existingClocks;
		$editClockContent = $editClockCityId . $editClockCity . $editClockTimezone . $editClockDst;
		$newClockContent = $newClockCity . $newClockTimezone . $newClockDst;

		// Print the widget control

		echo '<script type="text/javascript" src="' . $urlControlJs . '"></script>';
		echo $this->getFieldset('Global Settings', $globalContent);
		echo $this->getFieldset('Existing Clocks', $existingClocksContent);
		echo $this->getDiv($this->getFieldset('Edit Clock', $editClockContent) , '', 'worldclock_edit', 'display:none;');
		echo $this->getFieldset('New Clock', $newClockContent);
	}

	/** Shared Control Functions **/
	function getFieldset($legend, $content)
	{
		$open = '<fieldset style="border:1px solid #DDD;margin:3px;padding:10px;">';
		$title = '<legend style="font-weight:bold;font-size:12px;">' . $legend . '</legend>';
		$close = '</fieldset>';
		return $open . $title . $content . $close;
	}

	function getLabel($for, $label)
	{
		return '<label for="' . $for . '">' . $label . '</label>';
	}

	function getInput($name, $class, $value)
	{
		return '<input name="' . $name . '" class="' . $class . '" type="text" value="' . $value . '" />';
	}

	function getRadio($name, $value, $checked, $dValue)
	{
		return '<input type="radio" name="' . $name . '" value="' . $value . '" ' . (($checked) ? 'checked' : '') . '> ' . $dValue;
	}

	function getCheckbox($id, $name, $value)
	{
		return '<input type="checkbox" class="checkbox" id="' . $id . '" name="' . $name . '" value="' . $value . '" />';
	}

	function getLink($href, $name)
	{
		return '<a href="' . $href . '">' . $name . '</a>';
	}

	function getDiv($content, $id = '', $class = '', $style = '')
	{
		return '<div id="' . $id . '" class="' . $class . '" style="' . $style . '">' . $content . '</div>';
	}

	function controlFunctCity($keyCity)
	{
		$label = $this->getLabel($keyCity, 'City (for widget display):');
		$input = $this->getInput($keyCity, $keyCity, '');
		return '<div style="margin:5px;">' . $label . '<br/>' . $input . '</div>';
	}

	function controlFunctTimezone($keyTz)
	{
		$tz = '<div style="margin:5px;">';
		$tz.= $this->getLabel($keyTz, 'Timezone:');
		$tz.= '<select name="' . $keyTz . '" class="' . $keyTz . '">';
		$tz.= '<option value="-1" selected></option>';
		for ($i = 0; $i < count($this->timezones); ++$i) {
			$tz.= '<option value="' . $i . '">(GMT' . $this->timezones[$i]['offset_d'] . ') ' . $this->timezones[$i]['display'] . '</option>';
		}

		$tz.= '</select>';
		$tz.= '</div>';
		return $tz;
	}

	function controlFunctDst($keyDst)
	{
		$dst = '<div style="margin:5px;">';
		$dst.= '<input name="' . $keyDst . '" class="' . $keyDst . '" type="checkbox" /> ';
		$dst.= 'Daylight Saving Time [<a href="http://en.wikipedia.org/wiki/Daylight_saving_time" target="_blank">?</a>]';
		$dst.= '</div>';
		return $dst;
	}

	/** Control Data Access Functions **/
	function controlSaveGlobal($postKey, $optionKey)
	{
		$options = get_option($this->keyOption);
		if (isset($_POST[$postKey]) && $_POST[$postKey] != '') {
			$value = $_POST[$postKey];
			$_POST[$postKey] = '';
			$options[$optionKey] = $value;
		}

		update_option($this->keyOption, $options);
	}

	function controlSaveNewClock()
	{
		if (!(isset($_POST['tz']) && $_POST['tz'] != - 1 && isset($_POST['city']) && $_POST['city'] != '')) return;

		// Create a new clock

		$wc_clock = array();
		$wc_clock['tz'] = $_POST['tz'];
		$wc_clock['city'] = $_POST['city'];
		$wc_clock['dst'] = ($_POST['dst'] == 'on') ? true : false;

		// Save the new clock into the array of clocks

		$options = get_option($this->keyOption);
		$wc_clocks = $options[$this->keyClocks];
		$wc_clocks[] = $wc_clock;
		$options[$this->keyClocks] = $wc_clocks;
		update_option($this->keyOption, $options);

		// Clean post variables

		unset($_POST['tz']);
		unset($_POST['city']);
	}

	function controlEditExistingClock()
	{
		if (!(isset($_POST['worldclock_id_edit']) && $_POST['worldclock_id_edit'] != - 1 && isset($_POST['worldclock_tz_edit']) && $_POST['worldclock_tz_edit'] != - 1 && isset($_POST['worldclock_city_edit']) && $_POST['worldclock_city_edit'] != '')) return;

		// Update and save the existing clock into the array of clocks

		$options = get_option($this->keyOption);
		$wc_clocks = $options[$this->keyClocks];
		$wc_clocks[$_POST['worldclock_id_edit']]['tz'] = $_POST['worldclock_tz_edit'];
		$wc_clocks[$_POST['worldclock_id_edit']]['city'] = $_POST['worldclock_city_edit'];
		$wc_clocks[$_POST['worldclock_id_edit']]['dst'] = $_POST['worldclock_dst_edit'];
		$options[$this->keyClocks] = $wc_clocks;
		update_option($this->keyOption, $options);

		// Clean post variables

		unset($_POST['worldclock_id_edit']);
		unset($_POST['worldclock_tz_edit']);
		unset($_POST['worldclock_city_edit']);
	}

	function controlRemoveExistingClock()
	{
		if (!isset($_POST['rm'])) return;
		$rm = $_POST['rm'];
		unset($_POST['rm']);
		if (!(is_array($rm) && count($rm) > 0)) return;
		$rm_flip = array_flip($rm);
		$new_wc_clocks = array();
		$options = get_option($this->keyOption);
		$wc_clocks = $options[$this->keyClocks];
		$number_of_clocks = count($wc_clocks);
		for ($i = 0; $i < $number_of_clocks; ++$i) {
			if (array_key_exists($i, $rm_flip)) {
				echo $i;
				continue;
			}

			$new_wc_clocks[] = $wc_clocks[$i];
		}

		$options[$this->keyClocks] = $new_wc_clocks;
		update_option($this->keyOption, $options);
	}

	/** Control Global Functions **/
	function controlGlobalTitle($wc_title)
	{
		$label = $this->getLabel($this->keyEditTitle, 'Widget Title:');
		$input = $this->getInput($this->keyEditTitle, $this->keyEditTitle, $wc_title);
		return '<div style="float:left;margin:5px;width:180px;">' . $label . '<br/>' . $input . '</div>';
	}

	function controlGlobalDateTimeFormat($wc_dateformat, $wc_timeformat)
	{
		$labelDate = $this->getLabel($this->keyEditDateFmt, 'Date Format:');
		$labelTime = $this->getLabel($this->keyEditTimeFmt, 'Time Format:');
		$inputDate = $this->getInput($this->keyEditDateFmt, $this->keyEditDateFmt, $wc_dateformat);
		$inputTime = $this->getInput($this->keyEditTimeFmt, $this->keyEditTimeFmt, $wc_timeformat);
		$fmtInfo = ' [<a href="http://blog.stevenlevithan.com/archives/date-time-format" target=_blank>?</a>]';
		return '<div style="float:left;margin:5px;width:180px;"><div>' . $labelDate . $fmtInfo . '<br/>' . $inputDate . '</div>' . '<div>' . $labelTime . $fmtInfo . '<br/>' . $inputTime . '</div></div>';
	}

	function controlGlobalTextAlign($wc_textalign)
	{
		$label = $this->getLabel($this->keyEditTextAlg, 'Text Alignment:');
		$radioLeft = $this->getRadio($this->keyEditTextAlg, 'left', ($wc_textalign == 'left') , 'Left');
		$radioCenter = $this->getRadio($this->keyEditTextAlg, 'center', ($wc_textalign == 'center') , 'Center');
		$radioRight = $this->getRadio($this->keyEditTextAlg, 'right', ($wc_textalign == 'right') , 'Right');
		return '<div style="float:left;margin:5px;width:180px;">' . $label . '<br/>' . $radioLeft . ' ' . $radioCenter . ' ' . $radioRight . '</div>';
	}

	function controlGlobalHorizontal($wc_horizontal)
	{
		$label = $this->getLabel($this->keyEditHorizon, 'Activate Horizontal Format:');
		$radioNo = $this->getRadio($this->keyEditHorizon, 'no', ($wc_horizontal == 'no') , 'No');
		$radioYes = $this->getRadio($this->keyEditHorizon, 'yes', ($wc_horizontal == 'yes') , 'Yes');
		return '<div style="float:left;margin:5px;width:180px;">' . $label . '<br/>' . $radioNo . ' ' . $radioYes . '</div>';
	}

	function controlGlobalTimeSource($wc_timesource)
	{
		$label = $this->getLabel($this->keyEditTimeSrc, 'Source of time:');
		$radioClient = $this->getRadio($this->keyEditTimeSrc, 'client', ($wc_timesource == 'client') , 'Client Time');
		$radioServer = $this->getRadio($this->keyEditTimeSrc, 'server', ($wc_timesource == 'server') , 'Server Time');
		return '<div style="float:left;margin:5px;width:180px;">' . $label . '<br/>' . $radioClient . ' ' . $radioServer . '</div>';
	}

	/** Control Existing Clocks **/
	function controlExistingClocks($wc_clocks)
	{
		$clocks = '<div>';
		if (count($wc_clocks) < 1) {
			$clocks.= 'No clock defined.';
		}
		else {
			for ($i = 0; $i < count($wc_clocks); ++$i) {
				$href = 'javascript:worldclock_editClock(' . $i . ',\'' . $wc_clocks[$i]['city'] . '\',' . $wc_clocks[$i]['tz'] . ',' . (($wc_clocks[$i]['dst']) ? 'true' : 'false') . ')';
				$name = $wc_clocks[$i]['city'] . ' (GMT' . $this->timezones[$wc_clocks[$i]['tz']]['offset_d'] . ')';
				$clocks.= '<div style="margin:5px;">';
				$clocks.= $this->getCheckbox('rm[]', 'rm[]', $i);
				$clocks.= ' Remove ';
				$clocks.= $this->getLink($href, $name);
				$clocks.= '</div>';
			}
		}

		$clocks.= '</div>';
		return $clocks;
	}

	/** Control Edit Clocks **/
	function controlEditCityId()
	{
		$name = $this->keyEditCityId;
		$class = $this->keyEditCityId;
		return '<input name="' . $name . '" class="' . $class . '" type="hidden" value="-1" />';
	}

	function controlEditCity()
	{
		return $this->controlFunctCity($this->keyEditCity);
	}

	function controlEditTimezone()
	{
		return $this->controlFunctTimezone($this->keyEditTz);
	}

	function controlEditDst()
	{
		return $this->controlFunctDst($this->keyEditDst);
	}

	/** Control New Clock **/
	function controlNewCity()
	{
		return $this->controlFunctCity('city');
	}

	function controlNewTimezone()
	{
		return $this->controlFunctTimezone('tz');
	}

	function controlNewDst()
	{
		return $this->controlFunctDst('dst');
	}
}

// Run our code later in case this loads prior to any required plugins.

add_action('widgets_init', array(
	'WorldClock',
	'init'
)); ?>