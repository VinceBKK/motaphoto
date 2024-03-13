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

// Metadonnees single post

function motaphoto_entry_meta_footer() {
    // Affiche l'année de publication
    $year = get_the_date( 'Y' ); // Récupère l'année de publication du post
    echo '<div class="post-year">Année de publication: ' . $year . '</div>';

    // Affiche les catégories du post
    if ( 'post' === get_post_type() ) {
        $categories_list = get_the_category_list( ', ' );
        if ( $categories_list ) {
            echo '<div class="post-categories">Catégories: ' . $categories_list . '</div>';
        }
    }

    // Affiche les tags du post
    $tags_list = get_the_tag_list( '', ', ' );
    if ( $tags_list ) {
        echo '<div class="post-tags">Tags: ' . $tags_list . '</div>';
    }

    // Affiche un lien d'édition pour les utilisateurs autorisés
    edit_post_link( __( 'Edit', 'motaphoto' ), '<span class="edit-link">', '</span>' );
}
