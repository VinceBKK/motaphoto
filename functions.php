<?php 

// Ajouter la prise en charge des images mises en avant
add_theme_support( 'post-thumbnails' );

// Ajouter automatiquement le titre du site dans l'en-tête du site
add_theme_support( 'title-tag' );


// Ajout menus
register_nav_menus( array(
	'main' => 'Menu Principal',
	'footer' => 'Bas de page',
) );

// Modale contact
function enqueue_modal_scripts() {
	// Charger le fichier de script JS
	wp_enqueue_script('modal-script', get_template_directory_uri() . '/scripts.js', array('jquery'), '1.0', true);
  
	// Charger les styles CSS pour la modale
	wp_enqueue_style('modal-style', get_template_directory_uri() . '/modal-style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_modal_scripts');
