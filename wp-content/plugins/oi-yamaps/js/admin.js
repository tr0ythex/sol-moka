// отображаем окно добавления метки
jQuery("#oi_yamaps_button").bind('click', function(){
	jQuery("#insert_oi_yamaps").show();
});

// закрываем окно добавления метки
function close_oi_yamaps()
{
	jQuery("#insert_oi_yamaps form")[0].reset();
	jQuery("#insert_oi_yamaps").hide();
	return false;
}

function get_cords( address ) // получение координат по указанному адресу
{
	var data = {
		'action': 'get_cords',	// вызываемая php функция
		'address': address, // передаваемый параметр
	};

	jQuery.post(ajaxurl, data, function(response) {
		//console.log( response );
		jQuery("#insert_oi_yamaps [name=coordinates]").val( response ); // помещаем полученный результат от функции php в нужное место
	});
}

// получение координат при изменении адреса
jQuery("#insert_oi_yamaps [name=address]").bind("change",function(){ 
	var address = jQuery( this ).val();
	get_cords( address );
});

// если введены непосредственно координаты
jQuery("#coordinates").bind("change",function(){
	jQuery("#insert_oi_yamaps [name=address]").val(''); // очищаем строку адреса
});

function oi_yamaps_tabs( obj )
{
	jQuery(".shortcode_yamaps_block .media-menu-item").removeClass( "active" );
	jQuery( obj ).addClass( "active" );
	jQuery( ".media-frame-content-box" ).hide();
	jQuery( ".media-frame-content-box." + jQuery( obj ).data("block") ).show();
}

jQuery(".shortcode_yamaps_block .media-menu-item").bind('click', function(){
	oi_yamaps_tabs( this );
});

function insert_oi_yamaps( attr ){
	var data = jQuery("#insert_oi_yamaps form").serializeArray();
	var obj = new Array();
	var shortcode = new Array();
	shortcode['map'] = '';
	shortcode['placemark'] = '';
	for (var i = 0, l = data.length; i < l; i++) // проход по массиву собранному из формы
	{
		if( data[i].value != '' ) // если значение не пустое
		{
			if( jQuery.inArray( data[i].name, [ 'center','height','width','zoom', ] ) >= 0 )
			{
				shortcode['map'] += ' ' + data[i].name + '="' + data[i].value + '"';
			}else
			{
				shortcode['placemark'] += ' ' + data[i].name + '="' + data[i].value + '"';
			}
			obj[data[i].name] = data[i].value;
		}
	}
	shortcode['map'] = '[showyamap' + shortcode['map'] + ']';
	shortcode['placemark'] = '[placemark' + shortcode['placemark'] + '/]';
	shortcode['map'] = shortcode['map'] + "\n" + shortcode['placemark'] + "\n" + '[/showyamap]';
	//console.log(shortcode);
	window.send_to_editor(shortcode[attr]);
	close_oi_yamaps();
}