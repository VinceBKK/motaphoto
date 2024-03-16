<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('photo-single-layout'); ?>>

<!-- Conteneur pour métadonnées et image -->

<div class="photo-content">

    <div class="photo-info-container">
        <header class="entry-header">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

            <div class="photo-meta">
                <div class="post-reference">RÉFÉRENCE : <?php the_field('reference'); ?></div>
                <div class="post-categories">CATÉGORIE : 
                    <?php 
                    $categories = wp_get_post_terms( get_the_ID(), 'categorie', array('fields' => 'names') );
                    echo !empty($categories) ? esc_html(implode(', ', $categories)) : '';
                    ?>
                </div>
                <div class="post-formats">FORMAT : 
                    <?php 
                    $formats = wp_get_post_terms( get_the_ID(), 'format', array('fields' => 'names') );
                    echo !empty($formats) ? esc_html(implode(', ', $formats)) : '';
                    ?>
                </div>
                <div class="post-type">TYPE : <?php the_field('type'); ?></div>
                <div class="post-year">ANNÉE : <?php echo get_the_date( 'Y' ); ?></div>
            </div>
        </header>
    </div>

	<div class="entry-content">
        <?php the_content(); ?>
    </div>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="photo-image-container">
            <div class="photo-thumbnail-container">
                <?php the_post_thumbnail('full', ['class' => 'photo-thumbnail']); ?>
            </div>
        </div>
    <?php endif; ?>

	</div> <!-- Fin du conteneur pour métadonnées et image -->

	<!-- Début du nouveau bloc pour les interactions -->
<div class="photo-interaction-container">
    <div class="interaction-left">
        <p class="interest-text">Cette photo vous intéresse ?</p>
        <button id="openContactModal" class="contact-button">Contact</button>
    </div>

    <div class="photo-navigation">
        <!-- Appel de la fonction de navigation personnalisée -->
        <?php motaphoto_post_navigation(); ?>
    </div>
</div>
<!-- Fin du nouveau bloc pour les interactions -->

<!-- Conteneur pour photos apparentées -->

<div class="related-photos">
    <h2>VOUS AIMEREZ AUSSI</h2>
    <?php
// WP_Query pour récupérer des photos apparentées
$related_category = wp_get_post_terms($post->ID, 'categorie', array('fields' => 'ids'));
$args = array(
    'post_type' => 'photo', // Remplacez par le bon type de post si nécessaire
    'posts_per_page' => 2,
    'post__not_in' => array($post->ID),
    'tax_query' => array(
        array(
            'taxonomy' => 'categorie',
            'field' => 'term_id',
            'terms' => $related_category,
        ),
    ),
);

$related_posts = new WP_Query($args);

if($related_posts->have_posts()) :
    while($related_posts->have_posts()) : $related_posts->the_post();
        $photo_url = get_content_first_image_url(get_the_ID());
        $photo_alt = get_the_title();
        $photo_info_link = get_permalink();
        // Utilisation du champ ACF 'reference'
        $photo_reference = get_field('reference', get_the_ID());
        
        // Récupérer les noms des catégories
        $terms = wp_get_post_terms(get_the_ID(), 'categorie', array("fields" => "names"));
        $photo_category = !empty($terms) ? implode(', ', $terms) : '';

        $photo_fullscreen_link = $photo_url;
        
        get_template_part('template-parts/photo_block', null, array(
            'photo_url' => $photo_url,
            'photo_alt' => $photo_alt,
            'photo_info_link' => $photo_info_link,
            'photo_fullscreen_link' => $photo_fullscreen_link,
            'photo_reference' => $photo_reference,
            'photo_category' => $photo_category
        ));
    endwhile;
    wp_reset_postdata();
endif;

?>
</div>

</article><!-- #post-<?php the_ID(); ?> -->
