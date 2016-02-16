    <footer class="helvetica-neue-cyr-light">
      <div class="wrapper">
        <?php
          wp_nav_menu(array(
            'menu' => 'Bottom',
            'container' => 'nav',
            'menu_class' => 'vertical-align'
          ));
        ?>
        <div class="f_right">
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
      </div>
    </footer>
  </body>
</html>