<?php
// Inclut le fichier header.php
get_header(); ?>


<section class="hero">
    <div id="tsparticles"></div>
    <div class="content">
        <h1>Découvrez Krabi autrement !</h1>
        <p>
        Toute l'équipe de Krabi Dôme s'investit pleinement afin de vous proposer des expériences uniques et mémorables. Avec nous, vous avez l'assurance d'une journée époustouflante. Prêt à découvrir des endroits magiques et inoubliables ? C'est parti !</p>
        <button id="openContactModal" class="contact-button">Contactez-nous</button>
    </div>

    <div class="swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="<?php echo get_template_directory_uri() . '/img/sunset-tour-bioluminescent-plankton-krabi-guide-francophone.webp'; ?>" alt="Tour guide francophone plancton bioluminescent et sunset tour Krabi" />
                <div class="cost dark-text">55€ par personne</div>
                <div class="overlay">
                    <h2>Sunset & Plancton bioluminescent</h2>
                    <p>
                    Des teintes de feu du crépuscule à la lueur du plancton, une expérience à couper le souffle vous attend.
                    </p>
                    <div class="ratings">
                        <div class="stars">
                            <ion-icon class="star" name="star"></ion-icon>
                            <ion-icon class="star" name="star"></ion-icon>
                            <ion-icon class="star" name="star"></ion-icon>
                            <ion-icon class="star" name="star"></ion-icon>
                            <ion-icon class="star" name="star-half-outline"></ion-icon>
                        </div>
                        <span>138 reviews</span>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
            <img src="<?php echo get_template_directory_uri() . '/img/snorkeling-tour-krabi-guide-francophone.webp'; ?>" alt="Tour snorkeling Krabi guide francophone" />
            <div class="cost dark-text">35€ par personne</div>
            <div class="overlay">
              <h2>Tour Snorkeling</h2>
              <p>
              Explorez les merveilles sous-marines des spots secrets d'Ao Nang et savourez un pique-nique sur une plage paradisiaque.
              </p>
              <div class="ratings">
                <div class="stars">
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                </div>
                <span>217 reviews</span>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
          <img src="<?php echo get_template_directory_uri() . '/img/4-island-tour-krabi-guide-francophone.webp'; ?>" alt="Tour 4 îles Krabi guide francophone" />
            <div class="cost dark-text">4 personnes : 195€</div>
            <div class="overlay">
              <h2>Tour 4 îles</h2>
              <p>
              Îles paradisiaques, plages de sable blanc et eaux turquoise, un circuit de rêve pour les amoureux de la nature.
              </p>
              <div class="ratings">
                <div class="stars">
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star-half-outline"></ion-icon>
                </div>
                <span>1152 reviews</span>
              </div>
            </div>
          </div>
          <div class="swiper-slide">
          <img src="<?php echo get_template_directory_uri() . '/img/hong-island-tour-krabi-guide-francophone.webp'; ?>" alt="Tour Hong island guide francophone Krabi" />
            <div class="cost">4 personnes : 195€</div>
            <div class="overlay">
              <h2>Tour Hong island</h2>
              <p>
              Koh Hong dévoilera ses trésors sous-marins et sa lagune naturelle envoûtante lors de cette excursion de rêve.
              </p>
              <div class="ratings">
                <div class="stars">
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star"></ion-icon>
                  <ion-icon class="star" name="star-outline"></ion-icon>
                </div>
                <span>619 reviews</span>
              </div>
            </div>
          </div>
        </div>
    </div>
</section>

<main>
<div class="page-container">
<!-- Filtres -->

<div class="filters">

<div class="filters-form">

<div class="taxo">
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

        echo "<option class='custom-option' value=''>$label</option>";
        foreach ($terms as $term) {
            echo "<option class='custom-option' value='$term->slug'>$term->name</option>";
        }
        echo "</select>";
    }
}
?>
</div>

<!--
<div class="filter2">
<select id="order" class="custom-select taxonomy-select">
    <option value="">TRIER PAR</option>
    <option value="DESC">Plus récentes</option>
    <option value="ASC">Plus anciennes</option>
</select>
</div>
-->
    </div>

</div>

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
                $photo_fullscreen_link = $photo_url;
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
</div>
</main>

<?php
// Inclut le fichier footer.php
get_footer(); ?>
