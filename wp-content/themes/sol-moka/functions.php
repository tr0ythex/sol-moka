<?php
  function get_css_path() {
    echo get_template_directory_uri() . "/stylesheets";
  }
  function get_js_path() {
    echo get_template_directory_uri() . "/js";
  }
  function get_image_path() {
    echo get_template_directory_uri() . "/images";
  }
?>

<?php
// Добавляем отображение стилей в окне редактора страницы
function my_theme_add_editor_styles() {
    add_editor_style( 'custom_editor_styles.css' );
}
add_action( 'admin_init', 'my_theme_add_editor_styles' );
?>


<?php
// Cusomizer (Онлайн-редактор темы)
function sol_moka_customize_register( $wp_customize ) {
  class Sol_Moka_Customize_Textarea_Control extends WP_Customize_Control {
    public $type = 'textarea';
    public function render_content() {
    ?>
      <label>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
      </label>
    <?php
    }
  }
  
  $wp_customize->add_setting('tel_setting', array('default' => '8 (495) 994-15-86'));
  $wp_customize->add_setting('email_setting', array('default' => 'sol-moka@yandex.ru'));
  $wp_customize->add_setting('address_setting', array('default' => 'г. Солнечногорск, ул. Красная, 37 А'));
  $wp_customize->add_setting('working_hours_setting', array('default' => 'пн-пт с 9:00 до 16:00'));
  
  $wp_customize->add_control(new Sol_Moka_Customize_Textarea_Control($wp_customize, 'tel_setting', array(
    'label' => 'Тел. (факс)',
    'section' => 'content',
    'settings' => 'tel_setting',
  )));
  $wp_customize->add_control(new Sol_Moka_Customize_Textarea_Control($wp_customize, 'email_setting', array(
    'label' => 'Эл. почта',
    'section' => 'content',
    'settings' => 'email_setting',
  )));
  $wp_customize->add_control(new Sol_Moka_Customize_Textarea_Control($wp_customize, 'address_setting', array(
    'label' => 'Адрес',
    'section' => 'content',
    'settings' => 'address_setting',
  )));
  $wp_customize->add_control(new Sol_Moka_Customize_Textarea_Control($wp_customize, 'working_hours_setting', array(
    'label' => 'Часы работы',
    'section' => 'content',
    'settings' => 'working_hours_setting',
  )));
  $wp_customize->add_section('content' , array(
    'title' => __('Содержимое','Sol_Moka'),
  ));
}
add_action( 'customize_register', 'sol_moka_customize_register' );
?>