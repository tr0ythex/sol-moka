<?php
function oi_yamaps_button() // Добавляем кнопку редактирования шорткода на страницу редактирования поста в админке
{
?>
<a href="#" id="oi_yamaps_button" class="button" title="<?php _e("Yandex Map", "oiyamaps"); ?>"><?php _e("Yandex Map", "oiyamaps"); ?></a>
<?php
}

function oi_yamaps_thickbox() // окно редактирования шорткода
{
	$fields = array(
			'address'		=> array(
								'label'	=> __('Address','oiyamaps'),
								'value'	=> '',
								),
			'coordinates'	=> array(
								'label'	=> __('Coordinates','oiyamaps'),
								'value'	=> '',
								),
			'header'		=> array(
								'label'	=> __('Baloon header','oiyamaps'),
								'value'	=> '',
								),
			'body'			=> array(
								'label'	=> __('Baloon body content','oiyamaps'),
								'value'	=> '',
								),
			'footer'		=> array(
								'label'	=> __('Baloon footer','oiyamaps'),
								'value'	=> '',
								),
			'hint'			=> array(
								'label'	=> __('Placemark hint','oiyamaps'),
								'value'	=> '',
								),
			'iconcontent'	=> array(
								'label'	=> __('Plcamark label','oiyamaps'),
								'value'	=> '',
								),
			'placemark'		=> array(
								'label'	=> __('Plcamark type','oiyamaps'),
								'hint'	=> __('Default: ','oiyamaps'),
								'value'	=> '',
								),
			'center'		=> array(
								'label'	=> __('Map center','oiyamaps'),
								'hint'	=> __('It should be a coordinates, like 55.754736,37.620875','oiyamaps').'<br>'.__('By default center = coordinates','oiyamaps'),
								'value'	=> '',
								),
			'height'		=> array(
								'label'	=> __('Map height','oiyamaps'),
								'hint'	=> __('Default: ','oiyamaps'),
								'value'	=> '',
								),
			'width'			=> array(
								'label'	=> __('Map width','oiyamaps'),
								'hint'	=> __('Default: ','oiyamaps'),
								'value'	=> '',
								),
			'zoom'			=> array(
								'label'	=> __('Map zoom','oiyamaps'),
								'hint'	=> __('Default: ','oiyamaps'),
								'value'	=> '',
								),
	);
	$out = '';
	$out1 = '';
	$out2 = '';
	$out3 = '';
	$out4 = '';
	$options = get_option( OIYM_PREFIX.'options' );
	foreach($options as $k=>$v) // получаем опции из бд
	{
		if($$k==''){$$k = $v;} // формируем список переменных с нормальными именами
	}
	$i = 0;
	foreach($fields as $field=>$val)
	{
		$i++;
		//$out1 .= 'var '.$field.' = jQuery("#'.$field.'").val();'."\n"; // формируем список объявления переменных для JS
		//$out2 .= "if({$field}!=''){{$field}=' {$field}=\"' + {$field} +'\"';}else{{$field}='';}"; // формируем условия вывода параметров в шорткод
		//$out3 .= '+'.$field; // формируем строку параметров со значениями
		if( $val['hint'] ) // если есть подсказка, формируем ее внешний вид
		{
			$hint = '<p class="help-block description">'.$val['hint'].' '.$$field.'</p>';
		}else
		{
			$hint = '';
		}
		 // формируем таблицу с полями
		if($i % 2 <> 0 )
		{
			$out4 .= '<tr>';
		}
		$out4 .= 
			'<td><label for="'.$field.'">'.$val['label'].'</label></td>'.
			'<td><input name="'.$field.'" id="'.$field.'" value="" />'.
				$hint.
			'</td>';
		if( $i % 2 == 0 )
		{
			$out4 .= '</tr>';
		}
	}
	print $out;
	oiyamaps_add_map( array( 'form'=>$out4,) );
}


function get_cords() // вычисление и возврат координат
{
	print coordinates( $_POST['address'] ); // функция вычисления координат по предоставленному адресу
    wp_die(); // необходимо, для правильного возвращения результата
}
add_action('wp_ajax_' . 'get_cords', 'get_cords');
?>