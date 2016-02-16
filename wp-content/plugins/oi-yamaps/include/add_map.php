<?php
function oiyamaps_add_map( $atts )
{
	extract(shortcode_atts( array(
			'form' => '',
			//'' => '',
	), $atts ));
?>
<div class="shortcode_yamaps_block" id="insert_oi_yamaps" style="display:none;">
	<div class="media-modal wp-core-ui">
			<button type="button" class="button-link media-modal-close" onclick="close_oi_yamaps();">
				<span class="media-modal-icon">
					<span class="screen-reader-text">Закрыть окно параметров файла</span>
				</span>
			</button>
			<div class="media-modal-content">
			<div class="media-frame mode-select wp-core-ui hide-menu">
				<div class="media-frame-title">
					<h1>Вставить карту<span class="dashicons dashicons-arrow-down"></span></h1>
				</div>
				<div class="media-frame-router">
					<div class="media-router">
						<a href="#" class="media-menu-item active" data-block="mark">Настройки карты</a>
						<?php /* ?><a href="#" class="media-menu-item" data-block="option">Добавление меток</a><?php */ ?>
					</div>
				</div>
				<div class="media-frame-content">
					<div class="media-frame-content-box mark">
						<div class="map-toolbar">
							<form>
								<table><?php print $form; ?></table>
							</form>
						</div>
					</div>
					<?php /* ?>
					<div id="YMaps_0" class="YMaps" style=""><a class="author_link" href="http://oiplug.com/">OYM</a></div>
					<div class="shortcode_text"></div>
					<?php */ ?>
				</div>
					
				<div class="media-frame-toolbar">
					<div class="media-toolbar">
						<div class="media-toolbar-primary search-form">
							<input type="button" class="button media-button button-primary button-large media-button-insert" value="<?php _e( 'Insert map', '' ); ?>" onclick="insert_oi_yamaps('map');"/>
							<input type="button" class="button media-button button-primary button-large media-button-insert" value="<?php _e( 'Insert placemark', '' ); ?>" onclick="insert_oi_yamaps('placemark');"/>
							<a class="button media-button button-large media-button-insert button-cancel" href="#" onclick="close_oi_yamaps();"><?php _e("Cancel"); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="media-modal-backdrop"></div>
</div>
<style>
.map-toolbar {
	margin: 0 15px;
}
</style>
<?php
}
?>