<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Московская областная коллегия адвокатов | Солнечногорский филиал</title>
    <link href="<?php get_css_path(); ?>/style.css" rel="stylesheet" media="all" type="text/css" />
  </head>
  <body>
    <header class="helvetica-neue-cyr-light wrapper">
      <div class="h_left logo vertical-align">
        <a href="/">
          <img src="<?php get_image_path(); ?>/logo_sol_moka.png">
        </a>
      </div>
      <div class="h_right contacts vertical-align">
        <p class="tel">
          Тел. (факс):
          <?php echo get_theme_mod( 'tel_setting', '8 (495) 994-15-86' ); ?>
        </p>
        <p class="email">
          <?php echo get_theme_mod( 'email_setting', 'sol-moka@yandex.ru' ); ?>
        </p>
        <p class="address">
          <?php echo get_theme_mod( 'address_setting', 'г. Солнечногорск, ул. Красная, 37 А' ); ?>
        </p>
      </div>
      <div class="h_center site_title vertical-align">
        <p>Солнечногорский филиал</p>
        <p>Московской&nbsp;областной коллегии&nbsp;адвокатов</p>
      </div>
    </header>
    <?php
      wp_nav_menu(array(
          'menu' => 'Top',
          'container' => 'nav',
          'menu_class' => 'fxbx_block fxbx_sp_between fxbx_wrap helvetica-neue-cyr-reg wrapper'
      ));
    ?>