// Fonction pour ouvrir la modale
function openModal(preFillReference = false) {
    var modal = document.getElementById('contactModal');
    if (modal) {
        modal.style.display = 'block';

        // Préremplir le champ your-subject avec la référence de la photo actuelle si requis
        if(preFillReference) {
            var photoRefField = document.querySelector('input[name="your-subject"]');
            var currentPhotoReference = document.querySelector('.post-reference').textContent.split(': ')[1];
            if (photoRefField && currentPhotoReference) {
                photoRefField.value = currentPhotoReference.trim(); // Nettoie les espaces blancs
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Événement pour ouvrir la modale lors du clic sur le lien "Contact" dans le menu
    var contactLinkMenu = document.querySelector('#menu-item-22 a');
    if (contactLinkMenu) {
        contactLinkMenu.addEventListener('click', function(event) {
            event.preventDefault();
            openModal(); // Ouverture de la modale sans préremplissage du champ
        });
    }

    // Événement pour ouvrir la modale avec préremplissage du champ your-subject depuis le post Photo
    var contactButtonPost = document.getElementById('openContactModal');
    if (contactButtonPost) {
        contactButtonPost.addEventListener('click', function(event) {
            event.preventDefault();
            openModal(true); // Ouverture de la modale avec préremplissage du champ
        });
    }

    // Fermeture de la modale avec le bouton close ou en cliquant en dehors
    var closeModalButton = document.querySelector('.close');
    if (closeModalButton) {
        closeModalButton.addEventListener('click', function() {
            var modal = document.getElementById('contactModal');
            modal.style.display = 'none';

            // Optionnel: Réinitialiser le champ your-subject quand la modale est fermée
            var photoRefField = document.querySelector('input[name="your-subject"]');
            if (photoRefField) {
                photoRefField.value = '';
            }
        });
    }
    window.addEventListener('click', function(event) {
        var modal = document.getElementById('contactModal');
        if (event.target == modal) {
            modal.style.display = 'none';
            // Optionnel: Réinitialiser le champ your-subject quand la modale est fermée
            var photoRefField = document.querySelector('input[name="your-subject"]');
            if (photoRefField) {
                photoRefField.value = '';
            }
        }
    });
});

jQuery(document).ready(function($) {
    $('.nav-link').hover(function() {
        var imgSrc = $(this).data('thumb');
        if(imgSrc) {
            var img = $('<img>', {
                src: imgSrc,
                class: 'nav-thumb',
                css: {
                    display: 'block', // Rendre l'image visible
                    opacity: 0 // Commencer avec une opacité de 0 pour l'animation
                }
            }).appendTo($(this));

            // Animation en fondu vers une opacité de 1
            img.animate({opacity: 1}, 300);
        }
    }, function() {
        $(this).find('.nav-thumb').fadeOut(300, function() {
            $(this).remove(); // Supprime l'image après l'animation
        });
    });
});

