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
        echo '<a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '" class="nav-link prev-link" data-thumb="' . $prev_thumbnail_url . '" aria-label="Post précédent"></a>';
    }

    if ( !empty( $next_post ) ) {
        $next_thumbnail_url = get_content_first_image_url( $next_post->ID );
        echo '<a href="' . esc_url( get_permalink( $next_post->ID ) ) . '" class="nav-link next-link" data-thumb="' . $next_thumbnail_url . '" aria-label="Post suivant"></a>';
    }
}

function motaphoto_enqueue_styles() {
    // Enregistrement et enfilement de la feuille de style principale
    wp_enqueue_style('motaphoto-main-style', get_stylesheet_uri());
    
    // Enregistrement et enfilement de la feuille de style de la modale
    wp_enqueue_style('modal-style', get_template_directory_uri() . '/modal-style.css', array(), '1.0', 'all');


}
add_action('wp_enqueue_scripts', 'motaphoto_enqueue_styles');

function motaphoto_get_random_image_url() {
    $args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => -1, // Sélectionner toutes les images
    );

    $images = get_posts($args);
    if (!empty($images)) {
        $random_image = $images[array_rand($images)]; // Sélectionner une image aléatoirement
        return wp_get_attachment_url($random_image->ID);
    }

    return ''; // Retourner une chaîne vide si aucune image n'est trouvée
}

// Bouton charger plus

function motaphoto_scripts() {
    wp_enqueue_script('motaphoto-ajax', get_template_directory_uri() . '/motaphoto-ajax.js', array('jquery'), null, true);
    
    // Calculer le nombre total de pages
    $total_posts = wp_count_posts('photo')->publish;
    $max_num_pages = ceil($total_posts / 8); // Remplacer '8' par la valeur de posts_per_page si différent

    // Passer l'URL d'AJAX à motaphoto-ajax.js
    wp_localize_script('motaphoto-ajax', 'motaphoto_ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_more_posts'),
        'max_pages' => $max_num_pages, // Ajouter cette ligne
    ));
}

add_action('wp_enqueue_scripts', 'motaphoto_scripts');

// Gérer l'appel AJAX
add_action('wp_ajax_nopriv_load_more', 'motaphoto_load_more');
add_action('wp_ajax_load_more', 'motaphoto_load_more');

function motaphoto_load_more() {
    error_log('Valeur de page reçue: ' . print_r($_POST['page'], true));
    // error_log('AJAX Load More called');
    check_ajax_referer('load_more_posts', 'nonce');

    $total_posts = wp_count_posts('photo')->publish; // Nombre total de posts publiés de type 'photo'
    $posts_per_page = 8; // Le nombre de posts que tu veux afficher par page
    $max_num_pages = ceil($total_posts / $posts_per_page); // Calcul du nombre maximal de pages
    
    $paged = $_POST['page'] + 1;
    if($paged > $max_num_pages) {
        $paged = $max_num_pages; // S'assure que paged ne dépasse pas le nombre de pages disponible
    }
    error_log('Page actuelle après ajustement: ' . $paged);
    
    
    
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $paged,
        'meta_key' => 'reference',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    );

    // Vérifie que les posts avec le custom post type 'photo' existent bien.
    error_log('Arguments WP_Query: ' . print_r($args, true));

    $query = new WP_Query($args);
    error_log('Nombre de posts trouvés: ' . $query->found_posts);
    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();
        // Affiche un simple message pour chaque post
        // echo '<div>' . get_the_title() . '</div>';
    // Définition des variables à passer
    $photo_url = get_content_first_image_url(get_the_ID()); // Assure-toi que cette fonction fonctionne comme prévu
    $photo_alt = get_the_title();
    $photo_info_link = get_permalink();
    $photo_fullscreen_link = ''; // Ajuste selon ton implémentation
    $photo_reference = get_field('reference'); // Assure-toi d'avoir ACF activé et le champ configuré
    $categories = wp_get_post_terms(get_the_ID(), 'categorie', array("fields" => "names"));
    $photo_category = !empty($categories) ? implode(', ', $categories) : '';

    // Ajouter des lignes de débogage
    // error_log('Photo URL: ' . $photo_url);
    // error_log('Référence de la photo : ' . $photo_reference);
    // error_log('Catégorie(s) de la photo : ' . $photo_category);

    // Inclure le template en passant les variables
    get_template_part('template-parts/photo_block', null, array(
        'photo_url' => $photo_url,
        'photo_alt' => $photo_alt,
        'photo_info_link' => $photo_info_link,
        'photo_fullscreen_link' => $photo_fullscreen_link,
        'photo_reference' => $photo_reference,
        'photo_category' => $photo_category,
    ));
    endwhile;

    endif;
    // echo 'Ceci est un test pour voir si cette réponse atteint le JavaScript.';

    if($paged >= $max_num_pages) {
        // echo 'no more posts';
    }

    wp_die(); // Termine la requête AJAX proprement
}
