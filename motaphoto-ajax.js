// Filtres

jQuery(document).ready(function($) {
    var page = 1; // Réinitialisez cette variable globalement

    function loadContentAfterFilterChange() {
        var filterData = {
            'categorie': $('#categorie').val(),
            'format': $('#format').val(),
            'order': $('#order').val(),
            'page': page // Envoyez la page actuelle à WordPress, si nécessaire
        };

        var action = areFiltersDefault() ? 'load_initial_photos' : 'filter_photos';

        $.ajax({
            url: motaphoto_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: action,
                nonce: motaphoto_ajax_params.nonce,
                filter: filterData
            },
            success: function(result) {
            // Créez un nouveau tableau avec les nouveaux liens d'images filtrées
            const newLinks = $(result).find('a[data-lightbox="true"]');
            const newImages = newLinks.map(function() {
            return {
            url: $(this).attr('href'),
            alt: $(this).find('img').attr('alt'),
            category: $(this).data('category'),
            reference: $(this).data('reference'),
            filter: $(this).data('filter')
            };
  }).get();

  // Mettez à jour le contenu de .photo-grid
                
                $('.photo-grid').html(result);

                // Mettez à jour les images de la lightbox avec les nouvelles images filtrées
                updateLightboxImages(newImages);

                if(areFiltersDefault()) {
                    // Assurez-vous que le bouton "Charger plus" est visible si les filtres sont réinitialisés
                    $('#load_more').show();
                } else {
                    $('#load_more').hide();
                }

                // Réinitialiser la page à 1 si les filtres sont enlevés ou ajustés
                page = 1;
            
                // Mise à jour de la Lightbox avec les nouveaux éléments après filtrage
                if(window.updateLightboxImagesWithFilteredResults) {
                    window.updateLightboxImagesWithFilteredResults();
                }
            
        }
        });
    }

    // Fonction pour vérifier si tous les filtres sont par défaut
    function areFiltersDefault() {
        return $('#categorie').val() === '' && $('#format').val() === '' && $('#order').val() === '';
    }

    $('.taxonomy-select').change(loadContentAfterFilterChange);
});

// Catalogue
jQuery(function($) {
    var page = 1; // Page actuelle
    $(document).off('click', '#load_more').on('click', '#load_more', function(e) {
        e.preventDefault();
        page++;
        console.log("Page actuelle:", page, "Max Pages:", motaphoto_ajax_params.max_pages);
        
        // Assurez-vous que cet appel AJAX est unique et bien configuré
        $.ajax({
            url: motaphoto_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more',
                page: page,
                nonce: motaphoto_ajax_params.nonce
            },
            success: function(response) {
                console.log('Réponse AJAX reçue:', response); // Ajoutez ceci pour déboguer la réponse
                if(response.trim() === 'no more posts') {
                    $('#load_more').hide();
                } else {
                    $('.photo-grid').append(response);
                    
                    // Ici, vous pouvez inspecter les éléments ajoutés au DOM si nécessaire
                    console.log("Nouveaux éléments ajoutés au DOM:", $(".photo-grid").children().last());
                    
// Mise à jour de la lightbox avec les nouveaux éléments
if (typeof window.updateGalleryAndEvents === 'function') {
    window.updateGalleryAndEvents();
} else {
    console.error('La fonction updateGalleryAndEvents n\'est pas définie.');
}
                    // Cache le bouton s'il n'y a plus de pages
                    if(page >= motaphoto_ajax_params.max_pages) {
                        $('#load_more').hide();
                    }
                }
            
                
            }
        });
    });
});

