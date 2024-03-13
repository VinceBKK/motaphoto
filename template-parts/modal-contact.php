<div id="contactModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <!-- Utilisation d'une image pour l'en-tête de la modale -->
      <img src="<?php echo get_template_directory_uri(); ?>/img/contact-header.svg" alt="Contact Header">
    </div>
      <span class="close">&times;</span>
    <!-- Utiliser la fonction do_shortcode() pour interpréter le shortcode -->
    <?php echo do_shortcode('[contact-form-7 id="540fbda" title="Contact"]'); ?>
  </div>
</div>
