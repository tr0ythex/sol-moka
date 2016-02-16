// ���������� ���� ���������� �����
jQuery("#oi_yamaps_button").bind('click', function(){
	jQuery("#insert_oi_yamaps").show();
});

// ��������� ���� ���������� �����
function close_oi_yamaps()
{
	jQuery("#insert_oi_yamaps form")[0].reset();
	jQuery("#insert_oi_yamaps").hide();
	return false;
}

function get_cords( address ) // ��������� ��������� �� ���������� ������
{
	var data = {
		'action': 'get_cords',	// ���������� php �������
		'address': address, // ������������ ��������
	};

	jQuery.post(ajaxurl, data, function(response) {
		//console.log( response );
		jQuery("#insert_oi_yamaps [name=coordinates]").val( response ); // �������� ���������� ��������� �� ������� php � ������ �����
	});
}

// ��������� ��������� ��� ��������� ������
jQuery("#insert_oi_yamaps [name=address]").bind("change",function(){ 
	var address = jQuery( this ).val();
	get_cords( address );
});

// ���� ������� ��������������� ����������
jQuery("#coordinates").bind("change",function(){
	jQuery("#insert_oi_yamaps [name=address]").val(''); // ������� ������ ������
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
	for (var i = 0, l = data.length; i < l; i++) // ������ �� ������� ���������� �� �����
	{
		if( data[i].value != '' ) // ���� �������� �� ������
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