// Sélectionner tous les boutons qui ont la classe "isProduct"
const buttons = document.querySelectorAll('.isProduct');

// Parcourir tous les boutons
buttons.forEach(function(button) {
  // Récupérer le product_slug
  const productSlug = button.getAttribute('product_slug');
  const cId = button.getAttribute('c_id');

  fetch('/account/api/product/' + productSlug)
    .then(response => response.json())
    .then(product => {
      // Mettre à jour la valeur du bouton avec le nom du produit
      button.innerHTML = product.name + ' <small>' + button.getAttribute('dose') + button.getAttribute('unit') + '</small>';
      button.className = button.className + '{{ printRequest ? \'' + cId + '\' in c_id_array ? \'exist\' : \'d-none\' }}';

    });
});
