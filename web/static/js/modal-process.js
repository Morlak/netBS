/**
 * modal-process.js
 * fichier contenant tout les méchanismes js que l'on retrouvera dans
 * les modales, commes celles d'ajout d'attributions/distinctions
 */

/**
 * partie attributions modale
 * gère l'ajout multiple d'attributions au changement de la valeur
 * du select
 */
    
var attributions = [];
$('#interne_structurebundle_attributiontype_groupe').change(function() {

    $('#interne_structurebundle_attributiontype_groupe :selected').each(function(i, selected){ 
        attributions[i] = $(selected).text();
        
        //Une fois avoir récupéré les attributions se trouvant dans le select multiple,
        //on génère l'ensemble des divs d'ajout
    });
})