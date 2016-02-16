<?php
/*
Plugin Name: Oi Yandex.Maps for WordPress
Plugin URI: http://oiplug.com/plugin/oi-yandex-maps-for-wordpress/
Description: [<a target="_blank" href="https://wordpress.org/plugins/oi-yamaps/">English desc.</a>] Этот плагин просто вставляет Яндекс.Карты на страницы вашего сайта. Вы можете использовать шорткоды и произвольные поля, добавляя любое количество карт и меток на них.
Author: Alexei Isaenko
Version: 2.42
Author URI: http://www.sh14.ru
This plugin is Copyright 2012 Sh14.ru. All rights reserved.
*/

// Date: 25.04.2014 - make code as a single plugin from other big project
// Date: 20.05.2014 - Stretchy Icons support added  
// Date: 21.07.2014 - 2.0 release
// Date: 22.07.2014 - 2.1 fix html in placemark; center parametr added; curl enable check
// Date: 16.09.2014 - 2.2 fix error when coordinates used; added shortcode button; localization
// Date: 08.12.2014 - 2.3 fix showmap coordinates missing; map center; added custom image; placemarks;
// Date: 21.10.2015 - 2.4 fix notices; form view; shortcode making; plugin url;
// Date: 21.10.2015 - 2.41 fix нормальное отображение center при указании;
// Date: 21.10.2015 - 2.42 fix удален вывод координат перед картой;

require_once "include/init.php";

add_action('init', 'oi_yamaps');

function oi_yamaps() // localization
{
	load_plugin_textdomain( 'oiyamaps', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' );
	add_action('admin_footer',  'oi_yamaps_thickbox');
	add_action('media_buttons','oi_yamaps_button',11);
}
// do something on plugin activation
register_activation_hook( __FILE__, 'oi_yamaps_activation' );
function oi_yamaps_defaults() // create table
{
	$defaults = array(
		'height' => '400px',
		'width' => '100%',
		'zoom' => '16',
		'placemark' => 'twirl#blueDotIcon',
		'author_link' => '1',
	);
	return $defaults;
}

function oi_yamaps_js() // подключение js
{
	wp_enqueue_script( 'oi_yamaps_admin', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery'), '2015-10-21', true );
}
add_action( 'admin_enqueue_scripts', 'oi_yamaps_js' ); // встраиваем скрипт в футер


function oi_yamaps_activation() // set default variables on plugin activation
{
	if( !get_option( OIYM_PREFIX.'options' ) ) // if we don't have any settengs
	{
		update_option( OIYM_PREFIX.'options' , oi_yamaps_defaults() );
	}
}

class Ya_map_connected // check, if maps packege is loaded
{
    public static $id = 0; // default value - packege not loaded yet
    public static $pid = 0; // default value - packege not loaded yet

    public function staticValue() {
        return self::$id; // return actual value
    }
    public function staticValue1() {
        return self::$pid; // return actual value
    }
}
function _isCurl()
{
    return function_exists('curl_version');
}

function curl_get_contents($url)
{
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($curl);
  curl_close($curl);
  return $data;
}

function coordinates( $address ) // get coordinates of a given address
{
	$address = urlencode( $address );
	$url = "https://geocode-maps.yandex.ru/1.x/?geocode=" . $address;
	$content = @file_get_contents( $url ); // получаем данные от Яндекса
	if( empty( $content ) ) // если произошла ошибка
	{
		if( _isCurl() )
		{
			$content = curl_get_contents( $url ); // используем для получения данных curl
		}else
		{
			return __('To show the map cURL must be enabled.', 'oiyamaps');
		}
	}
	preg_match( '/<pos>(.*?)<\/pos>/', $content, $point );
	return implode(',',array_reverse(split(' ',trim(strip_tags($point[1])))));
}
function showyamap( $atts, $content=null ) // show block with the map on a page
{
	$options = get_option( OIYM_PREFIX.'options' );
	foreach($options as $k=>$v) // get variables from DB
	{
		if($$k==''){$$k = $v;}
	}
	extract( shortcode_atts( array(
			'address'		=> '',
			'center'		=> '',
			'header'		=> '',
			'body'			=> '',
			'footer'		=> '',
			'hint'			=> '',
			'coordinates'	=> '',
			'height'		=> $height,
			'width'			=> $width,
			'zoom'			=> $zoom,
			'iconcontent'	=> '',
			'placemark'		=> $placemark,
			'iconimage'		=> $iconimage,
			'iconsize'		=> '',
			'iconoffset'	=> '',
			'iconrect'		=> '',
			'zoomcontrol'	=> 1,
			'typeselector'	=> 1,
			'maptools'		=> 1,
			'trafficcontrol'=> 1,
			'routeeditor'	=> 1,
		), $atts, 'showyamap' ) );
	$output = '';
	$placemarks = array();

	if( $zoomcontrol == 1 ||  $typeselector == 1 || $maptools == 1 || $trafficcontrol == 1 || $routeeditor == 1 )
	{
		if( $zoomcontrol == 1 ){$zoomcontrol = '.add("zoomControl")';}else{$zoomcontrol = '';}
		if( $typeselector == 1 ){$typeselector = '.add("typeSelector")';}else{$typeselector = '';}
		if( $maptools == 1 ){$maptools = '.add("mapTools")';}else{$maptools = '';}
		if( $trafficcontrol == 1 ){$trafficcontrol = '.add("trafficControl")';}else{$trafficcontrol = '';}
		if( $routeeditor == 1 ){$routeeditor = '.add("routeEditor")';}else{$routeeditor = '';}
		$controls = '
					myMap.controls' .
						$zoomcontrol .
						$typeselector.
						$maptools .
						$trafficcontrol .
						$routeeditor .
						';

		';
	}else
	{
		$controls = '';
	}

	foreach(oi_yamaps_defaults() as $k=>$v) // set empty variables from defaults
	{
		if($$k==''&&$k<>'author_link'){$$k = $v;}
	} 
	$id = Ya_map_connected::$id; // set id of map block
	if( $coordinates == '' ) // if coordinates not set...
	{
		if($address<>'') // if we have an address, then...
		{
			$coordinates = coordinates($address); // take coordinates
		}else // if we don't...
		{
			$latitude = get_post_meta( get_the_ID(), 'latitude', true ); // get latitude from post meta
			$longitude = get_post_meta( get_the_ID(), 'longitude', true ); // get longitude from post meta
			if($latitude&&$longitude) // if we have coordinates...
			{
				$coordinates = $latitude . ',' . $longitude; // split theme
			}else
			{
				$coordinates = '';
			}
		}
	}

	if( $coordinates <> '' )
	{
		$placemarks[] = array(
			'pid'			=> $id,
			'header'		=> $header,
			'body'			=> $body,
			'footer'		=> $footer,
			'hint'			=> $hint,
			'coordinates'	=> $coordinates,
			'iconcontent'	=> $iconcontent,
			'placemark'		=> $placemark,
			'iconimage'		=> $iconimage,
			'iconsize'		=> '',
			'iconoffset'	=> '',
			'iconrect'		=> '',
		);
	}

	// delete all not necessary simbols from $content
	$record = false; // shortcode not started flag
	$out7 = ''; // shortcode container
	for($i=0;$i<strlen($content);$i++) // going thru $content
	{
		if($content[$i]=='['){$record = true;} // shortcode started
		if($record==true){$out7 .= $content[$i];} // make shortcode string
		if($content[$i]==']') // shortcode ended
		{
			$record = false; // set flag
			$placemarks[] = json_decode( do_shortcode( $out7 ), true ); // add array of vars to $placemarks array
			$out7 = '';
		}
	}
	
	$center = trim($center);
	/*
	if( $center <> '' ) // if we have a center, then...
	{
		if( ! is_int( $center[0] ) ) // if it's not coordinates, then...
		{
			//$center = coordinates( $center ); // get coordinates
		}else
		{
			list($lat, $lon) = array_map( 'trim', explode( ',', $center ) );
			$center = $lon . ',' . $lat;
		}
	}
	*/
	
	if( !empty( $placemarks ) )
	{
		// make placemarks string, for adding to code
		$placemark_code = '';
		$lat = array();
		$lon = array();
		foreach( $placemarks as $k=>$v )
		{
			if( $v['placemark'] == '' ) // set placemark if it's not...
			{
				$v['placemark'] = $placemark;
			}
			if( $center == '' )
			{
				list($lat[],$lon[]) = explode(',', $v['coordinates'] );
			}
			$placemark_code .= placemark_code( $v );
		}
		if( $center == '' )
		{
			$center = io_ya_map_center( $lat, $lon ); // center betwin all placemarks
		}

		if($author_link==1)
			$author_link = '<a class="author_link" target="_blank" href="http://oiplug.com/">' . __('OYM', 'oi_ya_maps') . '</a>';

		$output .= 
		'<div id="YMaps_'.$id.'" class="YMaps" style="width:'.$width.';height:'.$height.'">'. $author_link .'</div>
		<script type="text/javascript">
			ymaps.ready(init);

			function init () {
				var myMap = new ymaps.Map("YMaps_'.$id.'", {
						center: ['.$center.'],
						zoom: '.$zoom.'
					});
					'.$controls.'
					'.$placemark_code.'
			}
		</script>
		';
		Ya_map_connected::$id++; // set new id
		if($id==0) // if no maps on a page...
		{
			return '<script type="text/javascript" src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU"></script>'.
				'<style>.YMaps {position: relative;} .YMaps .author_link {position: absolute;bottom: 9px; right:330px; z-index: 999;padding:0;display: table!important;line-height:12px;text-decoration:underline!important;white-space: nowrap!important;font-family: Verdana,serif!important;font-size: 10px!important;padding-left: 2px!important;color: #000!important;background-color: rgba(255, 255, 255, 0.7)!important;border:none;}</style>'.
			"\n".$output; // ...and show the map
		}else{return $output;} // show the map
	}
}
add_shortcode('showyamap', 'showyamap');

function io_ya_map_center( $lat, $lon )
{
	// searching center betwin all placemarks
	$la = 0;
	$lo = 0;
	for($i=0;$i < sizeof( $lat );$i++)
	{
		if( $la == 0 )
		{
			$la_min = (float) $lat[$i];
			$la_max = (float) $lat[$i];
			$lo_min = (float) $lon[$i];
			$lo_max = (float) $lon[$i];
		}
		$la = (float) $lat[$i];
		$lo = (float) $lon[$i];
		if( $la_min > $la ){$la_min = $la;}
		if( $la_max < $la ){$la_max = $la;}
		if( $lo_min > $lo ){$lo_min = $lo;}
		if( $lo_max < $lo ){$lo_max = $lo;}
		
	}
	$la = ( $la_min + $la_max ) / 2;
	$lo = ( $lo_min + $lo_max ) / 2;
	$center = $la . ',' . $lo;
	return $center;
}
function oi_ya_map_brackets( $s )
{
	return str_replace( ')', ']', str_replace( '(','[',$s ) );
}

function placemark_code( $atts )
{
extract( shortcode_atts( array(
		'pid'			=> '',
		'header'		=> '',
		'body'			=> '',
		'footer'		=> '',
		'hint'			=> '',
		'coordinates'	=> '',
		'iconcontent'	=> '',
		'placemark'		=> '',
		'iconimage'	=> '',
		'iconsize'	=> '',
		'iconoffset'	=> '',
		'iconrect'	=> '',
	), $atts ) );
	
	// if content for placemark given, make placemark stretch
	if($iconcontent<>''){$placemark = str_replace('Icon','StretchyIcon',str_replace('Dot','',$placemark));}

	if( $iconcontent ){$iconcontent = 'iconContent: "'.$iconcontent.'",';}
	if( $header ){$header = 'balloonContentHeader: "'.$header.'",';}
	if( $body ){$body = 'balloonContentBody: "'.$body.'",';}
	if( $footer ){$footer = 'balloonContentFooter: "'.$footer.'",';}
	if( $hint ){$hint = 'hintContent: "'.$hint.'"';}
	
	if( $iconimage ){$iconimage = 'iconImageHref: "'.$iconimage.'", ';}
	if( $iconsize ){$iconsize = 'iconImageSize: '.oi_ya_map_brackets( $iconsize ).', ';}
	if( $iconoffset ){$iconoffset = 'iconImageOffset: '.oi_ya_map_brackets( $iconoffset ).' ';}
	if( $iconrect ){$iconrect = 'iconImageClipRect: '.oi_ya_map_brackets( $iconrect ).' ';}
	if( $placemark && !$iconimage ){$placemark = 'preset: "'.$placemark.'"';}else{$placemark = '';}
	
	$output = '
				myPlacemark_'.$pid.' = new ymaps.Placemark(['.$coordinates.'], {'.
					$iconcontent.
					$header.
					$body.
					$footer.
					$hint.
				'},
				{'.
					$placemark.
					$iconimage.
					$iconsize.
					$iconoffset.
					$iconrect.
				'}
				);
				myMap.geoObjects.add(myPlacemark_'.$pid.');
	
	';
	return $output;

	
}

function placemark( $atts )
{
extract( shortcode_atts( array(
		'address'		=> '',
		'header'		=> '',
		'body'			=> '',
		'footer'		=> '',
		'hint'			=> '',
		'coordinates'	=> '',
		'iconcontent'	=> '',
		'placemark'		=> '',
		'iconimage'	=> '',
		'iconsize'	=> '',
		'iconoffset'	=> '',
		'iconrect'	=> '',
	), $atts ) );
	if( $coordinates == '' ) // get coordinates, if it's not set
	{
		$coordinates = coordinates( $address );
	}
	
	if( $coordinates )
	{
		Ya_map_connected::$pid++;
		$pid = Ya_map_connected::$pid;
		$placemark = array(
			'pid'			=> $pid,
			'header'		=> $header,
			'body'			=> $body,
			'footer'		=> $footer,
			'hint'			=> $hint,
			'coordinates'	=> $coordinates,
			'iconcontent'	=> $iconcontent,
			'placemark'		=> $placemark,
			'iconimage'	=> $iconimage,
			'iconsize'	=> $iconsize,
			'iconoffset'	=> $iconoffset,
			'iconrect'	=> $iconrect,
		);
		return json_encode( $placemark );
	}
}
add_shortcode('placemark', 'placemark');

function oi_yamaps_same_page( $url = null )
{
	//redirect is back to the current page
	// Default 
	$uri = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
	if( ! empty( $url ) )
	{
		if( strlen( $_SERVER['QUERY_STRING'] ) > 0 )
		{
			// make query with new params
			list( $uri ) = explode( '?', $uri ); // take part without query
			$atts = $_SERVER['QUERY_STRING'] . '&' . $url; // teke query with a new params
			parse_str( $atts, $atts ); // Parses the string into variables
			$i = 0;
			$url = '';
			foreach( $atts as $key=>$value ) // going through query  
			{
				if( $i > 0 )
				{
					$delimiter = '&';
				}else
				{
					$delimiter = '';
				}
				$url .= $delimiter . $key . '=' . $value; // make new query without duplicates
				$i++;
			}
			$uri .= '?' . $url;
		}else
		{
			$uri = str_replace( '?', '', $uri ) . '?' . $url;
		}
	}
	return $uri;
}
?>