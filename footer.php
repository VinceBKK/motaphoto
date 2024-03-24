<footer class="site__footer">
    <div class="container">
        <?php wp_nav_menu( array( 'theme_location' => 'footer' ) ); ?>
        <div class="copyright-text">
            TOUS DROITS RÉSERVÉS
        </div>
    </div>
</footer>

<?php get_template_part('template-parts/modal-contact'); ?>

<!-- Lightbox container -->
<div id="lightbox" class="lightbox" style="display:none;">
    <button id="lightbox-close" class="lightbox__close">Fermer</button>
    <button id="lightbox-next" class="lightbox__next">Suivante</button>
    <button id="lightbox-prev" class="lightbox__prev">Précédente</button>    
    <div id="lightbox-category"></div>
    <div id="lightbox-reference"></div>
    <div class="lightbox__container">
        <img src="" alt="" id="lightbox-image" class="lightbox__image">    
    </div>    
</div>





<?php
// Appel à la fonction wp_footer() pour les scripts en pied de page
wp_footer();
?>