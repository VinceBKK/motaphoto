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

// Suppression de la navigation par défaut dans les posts single
function remove_default_post_navigation() {
    remove_action( 'the_post_navigation', 'twenty_twenty_one_the_post_navigation' );
}
add_action( 'after_setup_theme', 'remove_default_post_navigation' );

// Ajout de vos propres actions pour la navigation personnalisée
function get_content_first_image_url($postID) {
    $post = get_post($postID);
    preg_match_all('/<img .*src=["\']([^"\']+)/i', $post->post_content, $matches);
    if (isset($matches[1][0])) {
        return $matches[1][0];
    }
    return ''; // Retourne une chaîne vide si pas d'image
}

function motaphoto_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    if ( !empty( $prev_post ) ) {
        $prev_thumbnail_url = get_content_first_image_url( $prev_post->ID );
        echo '<a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '" class="nav-link prev-link" data-thumb="' . $prev_thumbnail_url . '">← Précédente</a>';
    }

    if ( !empty( $next_post ) ) {
        $next_thumbnail_url = get_content_first_image_url( $next_post->ID );
        echo '<a href="' . esc_url( get_permalink( $next_post->ID ) ) . '" class="nav-link next-link" data-thumb="' . $next_thumbnail_url . '">Suivante →</a>';
    }
}