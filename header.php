<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">

</head>
<body <?php body_class(); ?>>
    
<?php wp_body_open(); ?>
    
<header class="site__header">
  <div class="header_container">
    <div class="logo">
    <a href="<?php echo home_url( '/' ); ?>">
      <img src="<?php echo get_template_directory_uri(); ?>/img/logo.svg" alt="Logo">
    </a>
    </div>

    <button class="hamburger-button" aria-label="Toggle navigation">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <button class="close-button">
    <!-- Icône croix ici -->
    <span></span>
    <span></span>
    </button>

  <nav class="nav-links-container">
  <?php 
    wp_nav_menu( 
        array( 
            'theme_location' => 'main', 
            //'container' => 'ul', // afin d'éviter d'avoir une div autour 
            'menu_class' => 'site__header__menu', // ma classe personnalisée 
        ) 
    ); 
  ?>
  </nav>
  </div>

<script>
  // JavaScript pour le menu hamburger
  document.addEventListener('DOMContentLoaded', function() {
  var menuButton = document.querySelector('.hamburger-button');
  var closeButton = document.querySelector('.close-button');
  var navContainer = document.querySelector('.nav-links-container');

  menuButton.addEventListener('click', function() {
    navContainer.style.right = '0px';
    closeButton.style.display = 'flex';
  });

  closeButton.addEventListener('click', function() {
    navContainer.style.right = '-100%';
    this.style.display = 'none';
  });
});

</script>

</header>