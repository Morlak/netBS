/*
 * Selection/deséléction de toutes les créances
 */

function selectAllCreances(box)
{
    var table = $('#listeCreanceTable').dataTable();

    if(box.checked)
    {
        $('input.selectCreance', table.fnGetNodes()).each(function() {
            this.checked = true;

        });
    }
    else
    {
        $('input.selectCreance', table.fnGetNodes()).each(function() {
            this.checked = false;

        });
    }
}

/*
 * envoie en Ajax de la liste des créances à facturer
 */
function createFactureWithSelectedCreances(fromPage){

    var listeCreance = [];

    //on récupère la liste des créances cochée
    $('.selectCreance:checked').each(function() {
        listeCreance.push($(this).val());
    });

    var data = { fromPage: fromPage, listeCreance: listeCreance};

    $.ajax({
        type: "POST",
        url: Routing.generate('interne_facture_creance_facturation_ajax'),
        data: data,
        error: function(jqXHR, textStatus, errorThrown) { alert('erreur'); },
        success: function(htmlResponse) {


            if((fromPage == 'Membre')||(fromPage == 'Famille'))
            {
                //cherche le nouveau contenu
                $listeCreanceContent = $(htmlResponse).filter('#listeCreanceContent');

                //rempalce le nouveau contenu
                $('#listeCreanceContent').replaceWith($listeCreanceContent);

                //Redessine la table
                $('#listeCreanceTable').dataTable();


                //cherche le nouveau contenu
                $listeCreanceContent = $(htmlResponse).filter('#listeFactureContent');

                //rempalce le nouveau contenu
                $('#listeFactureContent').replaceWith($listeCreanceContent);

                //Redessine la table
                $('#listeFacturesTable').dataTable();
            }
            else if(fromPage == 'WaitingListe')
            {
                //nothing to do
            }




        }
    });
}