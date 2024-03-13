<footer class="site__footer">
    <div class="container">
        <?php wp_nav_menu( array( 'theme_location' => 'footer' ) ); ?>
        <div class="copyright-text">
            TOUS DROITS RÉSERVÉS
        </div>
    </div>
</footer>

<?php get_template_part('template-parts/modal-contact'); ?>

<?php
// Appel à la fonction wp_footer() pour les scripts en pied de page
wp_footer();
?>