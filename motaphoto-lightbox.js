class Lightbox {
    constructor(images) {
      this.images = images;
      this.currentIndex = 0;
      this.element = this.buildDOM();
      document.body.appendChild(this.element);
      this.img = this.element.querySelector('.lightbox__image');
      this.bindEvents();
      this.currentFilter = null; // Nouvelle propriété pour le filtre
    }
  
    buildDOM() {
      const dom = document.createElement('div');
      dom.classList.add('lightbox');
      dom.innerHTML = `
        <button class="lightbox__close">Fermer</button>
        <button class="lightbox__next">Suivant</button>
        <button class="lightbox__prev">Précédent</button>
        <div class="lightbox__container">
          <img src="" alt="" class="lightbox__image">
        </div>
      `;
      return dom;
    }
  
    bindEvents() {
        this.element.querySelector('.lightbox__close')?.addEventListener('click', () => {
          console.log('Bouton "Fermer" cliqué');
          this.close();
        });
      
        this.element.querySelector('.lightbox__next').addEventListener('click', () => this.next());
        this.element.querySelector('.lightbox__prev').addEventListener('click', () => this.prev());
      }
  
      open(imageUrl, imageAlt, imageCategory, imageReference, imageFilter) {
        console.log('Filtre actuel :', imageFilter);
        // Appliquez le filtre ici si un filtre est fourni
        let imagesToUse = this.images;
        if (imageFilter) {
        imagesToUse = imagesToUse.filter(image => image.filter === imageFilter);
  }
    
        // Trouvez l'index de l'image dans le tableau (filtré ou non)
        this.currentIndex = imagesToUse.findIndex(image => image.url === imageUrl);
    
        // Vérifiez si l'image a été trouvée avant de continuer
        if (this.currentIndex === -1) {
            console.error("Image non trouvée dans le tableau filtré.");
            return;
        }
    
        // Utilisez imagesToUse pour obtenir l'image actuelle
        let selectedImage = imagesToUse[this.currentIndex];
        this.img.src = selectedImage.url;
        this.img.alt = selectedImage.alt;

            // Supprimer les éléments existants pour la catégorie et la référence
    const container = this.element.querySelector('.lightbox__container');
    const existingCategoryElement = container.querySelector('#lightbox-category');
    const existingReferenceElement = container.querySelector('#lightbox-reference');
    
    if (existingCategoryElement) {
        container.removeChild(existingCategoryElement);
    }
    
    if (existingReferenceElement) {
        container.removeChild(existingReferenceElement);
    }

    // Créer de nouveaux éléments pour la catégorie et la référence
    const categoryElement = document.createElement('div');
    categoryElement.id = 'lightbox-category';
    categoryElement.textContent = imageCategory || '';

    const referenceElement = document.createElement('div');
    referenceElement.id = 'lightbox-reference';
    referenceElement.textContent = imageReference || '';

    // Ajouter les éléments au conteneur de la lightbox
    container.appendChild(categoryElement);
    container.appendChild(referenceElement);

        
        this.element.style.display = 'block';

    // Mettez à jour le filtre actuel
  this.currentFilter = imageFilter;
        }
    
    
  
    close() {
        console.log('Closing lightbox');
        this.element.style.display = 'none';
    }
  
    loadImage(url) {
      this.url = url;
      const loader = document.createElement('div');
      loader.classList.add('lightbox__loader');
      this.img.parentNode.insertBefore(loader, this.img);
      
      this.img.onload = () => {
        loader.remove();
      };
      this.img.src = url;
    }

    // Ajoutez cette méthode pour mettre à jour l'index actuel après la mise à jour des images
    updateCurrentIndex() {
        // Si une image est actuellement affichée, trouvez son index dans le nouveau tableau d'images filtrées
        if (this.img.src) {
            const currentImage = this.images.find(image => image.url === this.img.src);
            this.currentIndex = currentImage ? this.images.indexOf(currentImage) : 0;
        } else {
            // Sinon, réinitialisez l'index
            this.currentIndex = 0;
        }
    }

    // Mettez à jour la méthode updateImages pour inclure l'appel à updateCurrentIndex
    updateImages(newImages) {
        this.images = newImages;
        this.updateCurrentIndex();

        // Si la lightbox est ouverte, mettez à jour l'image affichée
        if (this.element.style.display === 'block' && this.images.length > 0) {
            this.open(this.images[this.currentIndex].url, this.images[this.currentIndex].alt);
        }
    }
  
    // Modifiez la logique de navigation pour qu'elle se base sur les images filtrées
    next() {
        let filteredImages = this.currentFilter ? this.images.filter(image => image.category === this.currentFilter) : this.images;
        this.currentIndex = (this.currentIndex + 1) % filteredImages.length;
        let image = filteredImages[this.currentIndex];
        this.open(image.url, image.alt, image.category, image.reference, this.currentFilter);
    }
    

    prev() {
        if (this.images.length > 0) {
            let filteredImages = this.currentFilter ? this.images.filter(image => image.category === this.currentFilter) : this.images;
            
            // Calculez le nouvel index de manière sécurisée pour éviter les valeurs négatives
            this.currentIndex = (this.currentIndex - 1 + filteredImages.length) % filteredImages.length;
            
            let image = filteredImages[this.currentIndex];
            this.open(image.url, image.alt, image.category, image.reference, this.currentFilter);
        }
    }
    
    
  }

// Lightbox : bouton charger plus
  
// Déclaration globale de la variable 'lightbox'
let lightbox;

// Fonction pour initialiser les liens de la lightbox
function initLightboxLinks() {
    const lightboxLinks = document.querySelectorAll('a[data-lightbox="true"]:not(.lightbox-initialized)');
    lightboxLinks.forEach(link => {
        console.log('Lien lightbox:', link.href); // Imprime l'URL pour vérifier
        link.classList.add('lightbox-initialized');
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const imageUrl = this.href;
            const imageAlt = this.querySelector('img').alt;
            const imageCategory = this.dataset.category;
            const imageReference = this.dataset.reference;
            lightbox.open(imageUrl, imageAlt, imageCategory, imageReference);
        });
    });
}

// Fonction pour mettre à jour la galerie avec de nouvelles images et initialiser les liens
function updateGalleryWithNewImages() {
    const newLinks = document.querySelectorAll('a[data-lightbox="true"]:not(.lightbox-initialized)');
    const newImages = Array.from(newLinks).map(link => ({
        url: link.href,
        alt: link.querySelector('img').alt,
        category: link.dataset.category,
        reference: link.dataset.reference
    }));

    // Concaténer les nouvelles images au tableau existant de la lightbox
    lightbox.images = [...lightbox.images, ...newImages];
    initLightboxLinks(); // Initialiser les nouveaux liens
}

// Initialise la lightbox une fois que le DOM est prêt
document.addEventListener('DOMContentLoaded', function () {
    const photoGrid = document.querySelector('.photo-grid');
    const lightboxLinks = document.querySelectorAll('a[data-lightbox="true"]');

    // Création d'un tableau des URLs des images pour la navigation dans la lightbox
    const gallery = Array.from(lightboxLinks).map(link => ({
        url: link.href,
        alt: link.querySelector('img').alt,
        category: link.dataset.category,
        reference: link.dataset.reference,
        filter: link.dataset.filter // Ajoutez cette ligne pour inclure l'attribut data-filter
    }));

    // Tri du tableau 'gallery' par le numéro de référence
    gallery.sort((a, b) => {
        const numberA = parseInt(a.reference.match(/\d+/), 10);
        const numberB = parseInt(b.reference.match(/\d+/), 10);
        return numberA - numberB;
    });

    // Instanciation de la Lightbox avec les images de la galerie
    lightbox = new Lightbox(gallery);

    // Initialiser les liens existants de la lightbox
    initLightboxLinks();
});

// Fonction globale pour mettre à jour la galerie et les gestionnaires d'événements
window.updateGalleryAndEvents = updateGalleryWithNewImages;

// Attachement du gestionnaire d'événements pour le bouton "Charger plus" en dehors de l'événement 'DOMContentLoaded'
const loadMoreButton = document.getElementById('load_more');
if (loadMoreButton) {
    loadMoreButton.removeEventListener('click', window.updateGalleryAndEvents);
    loadMoreButton.addEventListener('click', function() {
        // Assurez-vous d'appeler la fonction qui charge plus d'images ici
        // Par exemple : loadMoreImages();
        window.setTimeout(() => { // Assurez-vous que cela s'exécute après le chargement des images
            window.updateGalleryAndEvents();
        }, 100); // Le délai peut varier selon le temps de chargement
    });
}

// Lightbox filtres

// Réinitialisation et mise à jour de la lightbox après filtrage
    

function updateLightboxImages(newImages) {
    if (lightbox && typeof lightbox.updateImages === 'function') {
      lightbox.updateImages(newImages);
    }
  }

(function($) {
    // Utilisez cette fonction pour lier les événements click après le chargement de la page ou après le chargement AJAX
    function bindLightboxEvents() {
        $('a[data-lightbox="true"]').off('click').on('click', function(event) {
            event.preventDefault();
            console.log('Lightbox link clicked');
            // Ici, votre logique d'ouverture de lightbox...
            var imageUrl = $(this).attr('href');
            var imageAlt = $(this).find('img').attr('alt');
            var imageCategory = $(this).data('category');
            var imageReference = $(this).data('reference');
            var imageFilter = $(this).data('filter'); // Récupérez le filtre depuis l'attribut data-filter

            // Ajoutez un filtre ici si nécessaire, par exemple :
        var imageFilter = $(this).data('filter'); // Vous devrez ajouter data-filter aux liens

        if (lightbox && typeof lightbox.open === 'function') {
            lightbox.open(imageUrl, imageAlt, imageCategory, imageReference, imageFilter);
        }
        });
    }

    
    window.updateLightboxImagesWithFilteredResults = function() {
        var filteredImages = $('.photo-grid a[data-lightbox="true"]').map(function() {
            return {
                url: $(this).attr('href'),
                alt: $(this).find('img').attr('alt'),
                category: $(this).data('category'),
                reference: $(this).data('reference'),
                filter: $(this).data('filter')
                // Ajoutez ici d'autres propriétés si nécessaire
            };
        }).get();
    
        if (window.lightbox && typeof window.lightbox.updateImages === 'function') {
            window.lightbox.updateImages(filteredImages);
        }
    
        // Réinitialisez également les événements si nécessaire
        if (typeof bindLightboxEvents === 'function') {
            bindLightboxEvents();
        }
    }
       

    // Attachez l'événement 'ajaxComplete' pour relier les événements de la lightbox après un chargement AJAX
    $(document).ajaxComplete(function(event, xhr, settings) {
        // Cette condition vérifie si l'URL de la requête AJAX contient l'action de filtrage spécifique
        if(settings.url.includes('action=filter_photos')) {
            updateLightboxImagesWithFilteredResults(); // Mettez à jour les images de la lightbox avec celles qui correspondent au filtre
        }
        bindLightboxEvents(); // Reliez les événements de la lightbox après chaque requête AJAX, filtrée ou non
    });

    // Lier les événements de la lightbox au chargement initial de la page
    $(document).ready(function() {
        bindLightboxEvents(); // Ceci lie les événements dès le chargement de la page
    });

})(jQuery);




