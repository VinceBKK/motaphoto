class Lightbox {
    constructor(images) {
      this.images = images;
      this.currentIndex = 0;
      this.element = this.buildDOM();
      document.body.appendChild(this.element);
      this.img = this.element.querySelector('.lightbox__image');
      this.bindEvents();
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
  
    open(imageUrl, imageAlt, imageCategory, imageReference) {
        console.log('Opening lightbox with image:', imageUrl);
        this.currentIndex = this.images.findIndex(image => image.url === imageUrl);
        this.img.src = imageUrl;
        this.img.alt = imageAlt;

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
  
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        let nextImage = this.images[this.currentIndex];
        this.open(nextImage.url, nextImage.alt, nextImage.category, nextImage.reference);
    }

    prev() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        let prevImage = this.images[this.currentIndex];
        this.open(prevImage.url, prevImage.alt, prevImage.category, prevImage.reference);
    }
    
  }

  
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
        reference: link.dataset.reference
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
  