<?php
  function get_css_path() {
    echo get_template_directory_uri() . "/stylesheets/";
  }
  function get_js_path() {
    echo get_template_directory_uri() . "/js/";
  }
  function get_image_path() {
    echo get_template_directory_uri() . "/images/";
  }
?>