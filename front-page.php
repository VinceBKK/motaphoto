<?php
// Inclut le fichier header.php
get_header(); ?>

<section class="hero" style="background-image: url('<?php echo motaphoto_get_random_image_url(); ?>');">
    <!-- Contenu du hero ici, comme un titre ou un appel à l'action -->
    <h1>PHOTOGRAPHE EVENT</h1>
</section>

<main>

<!-- Filtres -->

<?php
// Affichage taxonomies
$taxonomy = [
    'categorie' => 'CATÉGORIES',
    'format' => 'FORMATS',
];

foreach ($taxonomy as $taxonomy_slug => $label) {
    $terms = get_terms($taxonomy_slug);
    if ($terms && !is_wp_error($terms)) {

        echo "<select id='$taxonomy_slug' class='custom-select taxonomy-select'>";

        echo "<option value=''>$label</option>";
        foreach ($terms as $term) {
            echo "<option value='$term->slug'>$term->name</option>";
        }
        echo "</select>";
    }
}
?>

<select id="order" class="custom-select taxonomy-select">
    <option value="">Trier par</option>
    <option value="DESC">Plus récentes</option>
    <option value="ASC">Plus anciennes</option>
</select>



<!-- Conteneur pour le catalogue de photos -->
    <div class="catalogue-section">
    <div class="photo-grid">
        <?php
        $args = array(
            'post_type' => 'photo', // Assurez-vous que c'est le bon post type
            'posts_per_page' => 8, // Limite à 8 photos
            'meta_key' => 'reference', // Le champ personnalisé ACF à trier
            'orderby' => 'meta_value', // Tri par valeur numérique du champ personnalisé
            'order' => 'ASC' // Les plus anciennes d'abord selon le champ 'reference'
        );
        $photo_query = new WP_Query($args);

        if($photo_query->have_posts()) :
            while($photo_query->have_posts()) : $photo_query->the_post();
                $photo_url = get_content_first_image_url(get_the_ID()); // Assurez-vous que cette fonction retourne correctement l'URL
                $photo_alt = get_the_title();
                $photo_info_link = get_permalink();
                $photo_fullscreen_link = ''; // Définissez ceci selon votre implémentation de lightbox
                $photo_reference = get_field('reference'); // Utilisez ACF pour obtenir la référence
                $categories = wp_get_post_terms(get_the_ID(), 'categorie', array("fields" => "names"));
                $photo_category = !empty($categories) ? implode(', ', $categories) : '';

                // Passer les variables au template
                get_template_part('template-parts/photo_block', null, array(
                    'photo_url' => $photo_url,
                    'photo_alt' => $photo_alt,
                    'photo_info_link' => $photo_info_link,
                    'photo_fullscreen_link' => $photo_fullscreen_link,
                    'photo_reference' => $photo_reference,
                    'photo_category' => $photo_category,
                ));
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
        <div id="load_more_container">
        <button id="load_more">Charger plus</button>
</div>

    </div>
</main>

<?php
// Inclut le fichier footer.php
get_footer(); ?>
