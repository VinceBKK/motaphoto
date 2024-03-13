// Fonction pour ouvrir la modale
function openModal() {
    console.log("Fonction openModal appelée"); // Ajout pour le débogage
    var modal = document.getElementById('contactModal');
    console.log(modal); // Ajout pour le débogage
    if (modal) {
        console.log("Ouverture de la modale"); // Message déjà présent
        modal.style.display = 'block';
    } else {
        console.log("Élément modal non trouvé"); // Ajout pour le débogage
    }
}

// Fonction pour fermer la modale
function closeModalFunc() {
    var modal = document.getElementById('contactModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Événement pour ouvrir la modale lors du clic sur le lien "Contact" dans le menu
    var contactLink = document.querySelector('#menu-item-22 a');
    if (contactLink) {
        contactLink.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du lien
            console.log("Clic détecté !");
            // Affiche la modale de contact
            openModal();
        });
    }
    
    // Événement pour fermer la modale en cliquant sur le bouton de fermeture
    var closeModal = document.querySelector('.close');
    if (closeModal) {
        closeModal.addEventListener('click', closeModalFunc);
    }
    
    // Événement pour fermer la modale en cliquant en dehors de celle-ci
    window.addEventListener('click', function(event) {
        var modal = document.getElementById('contactModal');
        if (event.target == modal) {
            closeModalFunc();
        }
    });
});