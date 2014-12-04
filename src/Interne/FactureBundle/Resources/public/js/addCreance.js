function addCreance(){
    var idMembre = $('#membre-id').val();
    //on récupère les valeur du formulaire
    var titre = $('#modal_form_creance_titre').val();
    var remarque = $('#modal_form_creance_remarque').val();
    var montant = $('#modal_form_creance_montant').val();

    var data = { idMembre: idMembre, titre: titre, remarque: remarque, montant: montant};

    $.ajax({
        type: "POST",
        url: Routing.generate('interne_facture_creance_add_ajax'),
        data: data,
        error: function(jqXHR, textStatus, errorThrown) { alert('erreur'); },
        success: function(htmlResponse) {

            //cherche le nouveau contenu
            $listeCreanceContent = $(htmlResponse).filter('#listeCreanceContent');

            //rempalce le nouveau contenu
            $('#listeCreanceContent').replaceWith($listeCreanceContent);

            //Redessine la table
            $('#listeCreanceTable').dataTable();

        }
    });
}


