<?php
// Inclut le fichier header.php de ton thème
get_header();

// Commence la boucle WordPress
if (have_posts()) : 
    echo '<div class="photo-grid">'; // Conteneur pour tes photos
    while (have_posts()) : the_post();
        
        // Ici tu récupères les informations de la photo comme tu l'as fait précédemment
        $photo_url = get_content_first_image_url(get_the_ID());
        $photo_alt = get_the_title();
        $photo_info_link = get_permalink();
        $photo_fullscreen_link = ''; // A définir selon ton implémentation
        $photo_reference = get_field('reference');
        $photo_category = ''; // Récupère les catégories si nécessaire

        // Inclus le template photo_block.php
        get_template_part('template-parts/photo_block', null, array(
            'photo_url' => $photo_url,
            'photo_alt' => $photo_alt,
            'photo_info_link' => $photo_info_link,
            'photo_fullscreen_link' => $photo_fullscreen_link,
            'photo_reference' => $photo_reference,
            'photo_category' => $photo_category,
        ));

    endwhile;
    echo '</div>'; // Fin du conteneur photo-grid

    // Tu peux inclure la pagination ici si tu le souhaites
    the_posts_pagination();

else : 
    echo '<p>Aucune photo à afficher.</p>'; 
endif;

// Inclut le fichier footer.php de ton thème
get_footer();
?>
