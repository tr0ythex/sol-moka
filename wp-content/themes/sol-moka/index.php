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
  <?php } ?>
</main>

<?php get_footer(); ?>