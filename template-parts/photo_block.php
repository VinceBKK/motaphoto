<?php
// error_log('Le fichier photo_block.php est exécuté.');
// echo 'Le fichier photo_block.php est exécuté.';
// Accès aux variables passées à get_template_part
$photo_url = $args['photo_url'];
$photo_alt = $args['photo_alt'];
$photo_info_link = $args['photo_info_link'];
$photo_fullscreen_link = $args['photo_fullscreen_link'];
$photo_reference = $args['photo_reference']; // Nouveau
$photo_category = $args['photo_category']; // Nouveau
?>
<div class="photo-block">
    <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($photo_alt); ?>" width="564" height="495">
    <div class="photo-overlay">
        <div class="overlay-info"> <!-- Nouveau bloc pour afficher les informations -->  
            <span class="photo-reference"><?php echo $photo_reference; ?></span>
            <span class="photo-category"><?php echo $photo_category; ?></span>
        </div>
    <a href="<?php echo esc_url($photo_info_link); ?>" class="photo-info-icon">
        <img src="<?php echo get_template_directory_uri(); ?>/img/Icon_eye.png" alt="Voir les infos de la photo" />
    </a>
    <a href="<?php echo esc_url($photo_fullscreen_link); ?>" class="photo-fullscreen-link" data-lightbox="true" data-category="<?php echo esc_attr($photo_category); ?>" data-reference="<?php echo esc_attr($photo_reference); ?>">
    <img src="<?php echo get_template_directory_uri(); ?>/img/Icon_fullscreen.png" alt="Voir en plein écran" />
    </a>

    </div>
</div>

