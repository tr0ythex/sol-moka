<?php get_header(); ?>

<?php if (is_front_page()) { ?>
  <div class="slogan permian-serif-typeface">
    <p class="phrase">Quod quisquis norit in hoc se exerceat*</p>
    <p class="clarification">*Пусть каждый занимается тем, в чём он разбирается</p>
  </div>
<?php } ?>
<?php 
  global $post;
  $post_slug=$post->post_name;
?>

<main id="<?php echo $post_slug; ?>" class="wrapper wrapper-760 helvetica-neue-cyr-light">
  <?php if (have_posts()) : while (have_posts()) : the_post();?>
  <?php the_content(); ?>
  <?php endwhile; endif; ?>
  
  <?php if (is_front_page()) { ?>
    <button class="more helvetica-neue-cyr-reg"><a href="/price">Подробнее</a></button>
  <?php } else if ($post_slug == "contacts") { ?>
    <div class="contacts_block fxbx_block fxbx_sp_between fxbx_wrap">
      <div class="contacts_item">
        Эл. почта: 
        <a href="mailto:sol-moka@yandex.ru">
          <?php echo get_theme_mod( 'email_setting', 'sol-moka@yandex.ru' ); ?>
        </a>
      </div>
      <div class="contacts_item">
        <?php echo get_theme_mod( 'address_setting', 'г. Солнечногорск, ул. Красная, 37 А' ); ?>
      </div>
      <div class="contacts_item">
        Телефон, факс: 
        <?php echo get_theme_mod( 'tel_setting', '8 (495) 994-15-86' ); ?>
      </div>
      <div class="contacts_item">
        Часы работы: 
        <?php echo get_theme_mod( 'working_hours_setting', 'пн-пт с 9:00 до 16:00' ); ?>
      </div>
    </div>
  <?php } ?>
</main>

<?php get_footer(); ?>