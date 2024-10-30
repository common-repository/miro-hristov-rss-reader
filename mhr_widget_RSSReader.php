<?php
/*
Plugin Name: Miro Hristov RSS Reader Widget
Plugin URI: http://host-ed.net/blog/wordpress-plugins
Description: RSS Widget Reader by Miro Hristov -Host-ed.net Developer
Version: 1.1
Author: Miro Hristov
Author URI: http://host-ed.net/
License: GPL2


Installing
1. Copy mhr_widget_rss_reader to your plugins folder /wp-content/plugins/
2. Activate it through the plugin management screen.
3. Go to Themes->Sidebar Widgets and drag and drop the widget to wherever you want to show it.
*/

/* Version check */


define(MHR_RSS_READER_URL_RSS_DEFAULT, 'http://www.host-ed.net/blog/feed');
define(MHR_RSS_READER_URL_DEFAULT, 'http://www.host-ed.net/');
define(MHR_RSS_READER_TITLE, 'Host-ed.net Web Hosting Blog News');
define(MHR_RSS_MAX_SHOWN_ITEMS, 3);
define(MHR_RSS_DESCRIPTION_COUNT_CHARS, 1000);
define(MHR_RSS_DATEFORMAT, 'd-M-Y');
define(MHR_RSS_FEEDS_DELIMETER, ';');

function mhr_RSS_widget_ShowRss($args)
{
	//@ini_set('allow_url_fopen', 1);	
	if( file_exists( ABSPATH . WPINC . '/rss.php') ) {
		require_once(ABSPATH . WPINC . '/rss.php');		
	} else {
		require_once(ABSPATH . WPINC . '/rss-functions.php');
	}
	
	$options = get_option('mhr_RSS_widget');
	//DEFAULT SETTINGS
	if( $options == false ) {
		$options[ 'mhr_RSS_widget_url_title' ] = MHR_RSS_READER_TITLE;
		$options[ 'mhr_RSS_widget_RSS_url' ] = MHR_RSS_READER_URL_RSS_DEFAULT;
		$options[ 'mhr_RSS_widget_RSS_showsponsoredlink' ] = true;		
		$options[ 'mhr_RSS_widget_RSS_description_count_chars' ] = MHR_RSS_DESCRIPTION_COUNT_CHARS;
		$options[ 'mhr_RSS_widget_RSS_dateformat' ] = MHR_RSS_DATEFORMAT;
		$options[ 'mhr_RSS_widget_RSS_count_items' ] = MHR_RSS_MAX_SHOWN_ITEMS;
	}
	$RSSurl = $options[ 'mhr_RSS_widget_RSS_url' ];
	$RSSurl = trim($RSSurl);
	$text  = trim($RSSurl, MHR_RSS_FEEDS_DELIMETER);
	$RSSurl_arr = explode(MHR_RSS_FEEDS_DELIMETER, $text);	
	$RSSurl = $RSSurl_arr[array_rand($RSSurl_arr)];
	if(empty($RSSurl))
		$RSSurl = MHR_RSS_READER_URL_RSS_DEFAULT;
	$messages = fetch_rss($RSSurl);
	$title = $options[ 'mhr_RSS_widget_url_title' ];
	
	$messages_count = count($messages->items);
	if($messages_count != 0){
		$articles = '<ul>';		
		for($i=0; $i<$options['mhr_RSS_widget_RSS_count_items'] && $i<$messages_count; $i++)
		{			
			$output .= '<li>';
			if(!empty($options[ 'mhr_RSS_widget_RSS_dateformat' ]))
				$output .= '<span class="micro_head">['.date($options[ 'mhr_RSS_widget_RSS_dateformat' ], strtotime($messages->items[$i]['pubdate'])).'] ';
			$output .= '<a href="'.$messages->items[$i]['link'].'">'.$messages->items[$i]['title'].'</a></span><br>';						
			if($options[ 'mhr_RSS_widget_RSS_description_count_chars' ] > 0)
				$output .= '<span class="micro_body">'.substr($messages->items[$i]['description'], 0, $options[ 'mhr_RSS_widget_RSS_description_count_chars' ]).'...</span><br>';
			$output .= '</li>';
		}
		$articles .= '</ul>';
	}
	
	extract($args);	
	?>
	<?php echo $before_widget; ?>
	<?php echo $before_title . $title . $after_title; ?>	
	<?php echo $output; ?>
	<?php if($options['mhr_RSS_widget_RSS_showsponsoredlink']):?>
	<br />
	<a href="http://www.host-ed.net" title="Quality Web Hosting Provider - Host-ed.net">Quality Web Hosting Provider - Host-ed.net</a>
	<?php endif;?>
	
	<?php echo $after_widget; ?>
	<?php	
}


function mhr_RSS_widget_Admin()
{
	$options = $newoptions = get_option('mhr_RSS_widget');	
	//default settings
	if( $options == false ) {
		$newoptions[ 'mhr_RSS_widget_url_title' ] = 'Host-ed.net Web Hosting News';
		$newoptions[ 'mhr_RSS_widget_RSS_url' ] = MHR_RSS_READER_URL_RSS_DEFAULT;
		$newoptions['mhr_RSS_widget_RSS_showsponsoredlink'] = true;
		$newoptions['mhr_RSS_widget_RSS_description_count_chars'] = MHR_RSS_DESCRIPTION_COUNT_CHARS;
		$newoptions['mhr_RSS_widget_RSS_dateformat'] = MHR_RSS_DATEFORMAT;
		$newoptions['mhr_RSS_widget_RSS_count_items'] = MHR_RSS_MAX_SHOWN_ITEMS;		
	}
	if ( $_POST["mhr_RSS_widget-submit"] ) {
		$newoptions['mhr_RSS_widget_url_title'] = strip_tags(stripslashes($_POST["mhr_RSS_widget_url_title"]));
		$newoptions['mhr_RSS_widget_RSS_url'] = strip_tags(stripslashes($_POST["mhr_RSS_widget_RSS_url"]));
		$newoptions['mhr_RSS_widget_RSS_showsponsoredlink'] = $_POST["mhr_RSS_widget_RSS_showsponsoredlink"];
		$newoptions['mhr_RSS_widget_RSS_description_count_chars'] = (int)$_POST["mhr_RSS_widget_RSS_description_count_chars"];
		$newoptions['mhr_RSS_widget_RSS_dateformat'] = $_POST["mhr_RSS_widget_RSS_dateformat"];
		$newoptions['mhr_RSS_widget_RSS_count_items'] = (int)$_POST["mhr_RSS_widget_RSS_count_items"];
		if($newoptions['mhr_RSS_widget_RSS_description_count_chars'] < 0 || $newoptions['mhr_RSS_widget_RSS_description_count_chars'] > 10000)
			$newoptions['mhr_RSS_widget_RSS_description_count_chars'] = MHR_RSS_DESCRIPTION_COUNT_CHARS;			
		
		if($newoptions['mhr_RSS_widget_RSS_count_items'] < 0 || $newoptions['mhr_RSS_widget_RSS_count_items'] > 10000)
			$newoptions['mhr_RSS_widget_RSS_count_items'] = MHR_RSS_MAX_SHOWN_ITEMS;			
	}	
		
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('mhr_RSS_widget', $options);		
	}
	$mhr_RSS_widget_url_title = wp_specialchars($options['mhr_RSS_widget_url_title']);
	$mhr_RSS_widget_RSS_url = wp_specialchars($options['mhr_RSS_widget_RSS_url']);	
	$mhr_RSS_widget_RSS_showsponsoredlink = $options['mhr_RSS_widget_RSS_showsponsoredlink'];
	$mhr_RSS_widget_RSS_description_count_chars = $options['mhr_RSS_widget_RSS_description_count_chars'];	
	$mhr_RSS_widget_RSS_dateformat = $options['mhr_RSS_widget_RSS_dateformat'];
	$mhr_RSS_widget_RSS_count_items = $options['mhr_RSS_widget_RSS_count_items'];
	
	?>
	<p><label for="mhr_RSS_widget_url_title"><?php _e('Title:'); ?> <input style="width: 350px;" id="mhr_RSS_widget_url_title" name="mhr_RSS_widget_url_title" type="text" value="<?php echo $mhr_RSS_widget_url_title; ?>" /></label></p>
	<p><label for="mhr_RSS_widget_RSS_url"><?php _e('Feed RSS URLs:'); ?> <textarea cols="45" rows="5" id="mhr_RSS_widget_RSS_url" name="mhr_RSS_widget_RSS_url"><?php echo $mhr_RSS_widget_RSS_url; ?></textarea>
		<br />
		<em>Use multiple RSS URLs with ;(semi-collon) separated - for instance http://www.blog1/feed;http://www.blog2/feed;http://www.blog1/feed;...RSS widget will choose random RSS feed and will show items. If the RSS you enter is not valid there will not be shown feeds()</em>
	</p>
	
	<p><label for="mhr_RSS_widget_RSS_description_count_chars"><?php _e('Description Count Chars:'); ?> <input  id="mhr_RSS_widget_RSS_description_count_chars" name="mhr_RSS_widget_RSS_description_count_chars" size="4" maxlength="4" type="text" value="<?php echo $mhr_RSS_widget_RSS_description_count_chars?>" /></label></p>
	<p><label for="mhr_RSS_widget_RSS_count_items"><?php _e('Count Items To Show:'); ?> <input  id="mhr_RSS_widget_RSS_count_items" name="mhr_RSS_widget_RSS_count_items" size="4" maxlength="4" type="text" value="<?php echo $mhr_RSS_widget_RSS_count_items?>" /></label></p>
	<p><label for="mhr_RSS_widget_RSS_dateformat"><?php _e('Date Format:'); ?> <input  id="mhr_RSS_widget_RSS_dateformat" name="mhr_RSS_widget_RSS_dateformat" type="text" value="<?php echo $mhr_RSS_widget_RSS_dateformat?>" /></label>
	<br />
		<em>*If you want the date to not show left blank</em>
	</p>	
	<p><label for="mhr_RSS_widget_RSS_showsponsoredlink"><?php _e('Show sponsored link:'); ?> <input  id="mhr_RSS_widget_RSS_showsponsoredlink" name="mhr_RSS_widget_RSS_showsponsoredlink" type="checkbox" <?php if($mhr_RSS_widget_RSS_showsponsoredlink) echo 'checked' ?> /></label>
		<br />
		<em>Please, if you like this widget left checked</em>
	</p>
	<p align='left'>
	* If you do not set Feed URL the system will set default - http://www.host-ed.net/blog/feed/<br />
	<br clear='all'></p>
	<input type="hidden" id="mhr_RSS_widget-submit" name="mhr_RSS_widget-submit" value="1" />	
	<?php
}

function mhr_RSS_widget_Init()
{
  register_sidebar_widget(__('Miro Hristov RSS Reader Widget'), 'mhr_RSS_widget_ShowRss');
  register_widget_control(__('Miro Hristov RSS Reader Widget'), 'mhr_RSS_widget_Admin', 500, 250);
}
add_action("plugins_loaded", "mhr_RSS_widget_Init");


?>