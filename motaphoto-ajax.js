jQuery(function($) {
    var page = 1; // Page actuelle
    $('#load_more').on('click', function(e) {
        e.preventDefault();
        var maxPages = motaphoto_ajax_params.max_pages; // Déplacer cette ligne à l'intérieur de la fonction
        if(page >= maxPages) {
            $('#load_more').hide(); // Cacher le bouton si on est à la dernière page
            return;
        }
        page++;
        console.log("Page actuelle:", page, "Max Pages:", maxPages);
        $.ajax({
            url: motaphoto_ajax_params.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more',
                page: page,
                nonce: motaphoto_ajax_params.nonce
            },
            success: function(response) {
                if(response.trim() === 'no more posts') {
                    $('#load_more').hide();
                } else {
                    $('.photo-grid').append(response);
                    if(page >= maxPages) { // Assurez-vous que maxPages est correctement défini et mis à jour
                        $('#load_more').hide();
                    }
                }
            }
            
        });
    });
});
