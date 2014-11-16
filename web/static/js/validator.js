/**
 * Fichier JS qui permet la gestion du validator
 */
$(document).ready(function() {

    var table = $('#validatorTable').DataTable( {
        paging: false
    } );
});

function alphanumeric(inputtxt)
{
    var letterNumber = /\d|\s/;
    if(inputtxt.match(letterNumber))
    {
        return true;
    }
    else
    {
        return false;
    }
}


alert(alphanumeric('Chemin des bsails 42'));

/**
 * ExtendedView permet d'expand la ligne de donnée, pour afficher les modifications dans la fenetre à coté
 * @param btn le bouton cliqué
 * @param id l'id de la validation
 */
function extendedView(btn, id) {


    $.ajax({

        url: Routing.generate('InterneGlobal_validator_get_extended_data', {id : id}),
        type: 'GET',
        success: function(data) {

            var l    = Object.keys(data[0]).length,
                text = '<table class="table table-bordered table-boutoned"><thead><tr><td><b>Champ</b></td>';

            if(l == 6) text += '<td><b>Ancienne valeur</b></td>';

            text += '<td><b>Nouvelle valeur</b></td><td><b>Modifié par</b></td><td><b>Date</b></td><td><b>Options</b></td></tr></thead><tbody>';

            for(var i = 0; i < data.length; i++) {

                text += '<tr><td>' + data[i].champ + '</td>';

                if(l == 6) text += '<td style="background-color:cornsilk;">'+ valParser(data[i].ancien) +'</td>';

                text += '<td style="background-color:palegoldenrod;">' + valParser(data[i].neuf) + '</td><td>' + data[i].user + '</td><td>' + data[i].date +
                        '</td><td><a style="margin-right:5px" onclick="requestModification(' + data[i].id + ', \'remove\')" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove"></span></a>' +
                        '<a onclick="requestModification(' + data[i].id + ', \'persist\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span></a></td>';
            }

            text += '</tbody></table>';
            $('#validator-detail-view').html(text);


        },
        error: function(xhr) {

            $("#error-dialog #error-content").attr("width", 1000);
            $("#error-dialog #error-content").attr("height", 800);
            $("#error-dialog #error-content").contents().find('html').html(xhr.responseText);
        }
    });


}



function requestValidation(id, action) {


    var url = "";

    if(action == 'remove')
        url = Routing.generate('InterneGlobal_validator_remove', {ids : id});
    else if(action == 'persist')
        url = Routing.generate('InterneGlobal_validator_persist', {ids : id});

    $.ajax({

        url: url,
        type: 'POST',
        success: function(data) {

            for(var i = 0; i < data.length; i++) {

                $('#valid' + data[i]).parent().parent().remove();
            }

        },
        error:function(xhr) {

            $("#error-dialog #error-content").attr("width", 1000);
            $("#error-dialog #error-content").attr("height", 800);
            $("#error-dialog #error-content").contents().find('html').html(xhr.responseText);
        }
    })
}

/**
 * permet de supprimer un paquet de validations d'un coup
 */
function removeMultipleValidations() {

    var ids = getChecked();

    if(ids == "")
        alert("Aucune modification en attente de validation n'a été cochée");

    else
        requestValidation(ids, "remove");
}

/**
 * permet de persister plusieurs validations d'un coup
 */
function persistMultipleValidations() {

    var ids = getChecked();
    if(ids == "")
        alert("Aucune modification en attente de validation n'a été cochée");

    else
        requestValidation(ids, "persist");
}

/**
 * la méthode getChecked va renvoyer la liste des checkbox checkées au sein des validations
 */
function getChecked() {

    var ids = '';
    var cb = $('.validation-cb:checked');

    $.each(cb, function( index, value ) {

        ids += $(value).attr("id").slice(5) + "-";
    });

    return ids.slice(0, - 1);
}

function valParser(valoo) {

    var val = '';

    if(isNaN(valoo)) //N'est pas un nombre
    {
        if (!isNaN(Date.parse(valoo))) { //Est une date

            var date = new Date(valoo);
            val = date.getDate() + "." + date.getMonth() + "." + date.getFullYear();
        }

        else {//Sinon c'est une chaine de caractère basique
            val = valoo;
        }
    }

    else {  //Est un nombre
        val = valoo;
    }

    return val;

}