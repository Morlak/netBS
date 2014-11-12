/**
 * Fichier JS qui permet la gestion du validator
 */
$(document).ready(function() {

    var table = $('#validatorTable').DataTable( {
        paging: false
    } );
});


function extendedView(id) {

    $.ajax({

        url: Routing.generate('InterneGlobal_validator_get_extended_data', {id : id}),
        type: 'GET',
        success: function(data) {

            data = JSON.parse(data);
            var text = '';

            if(Object.prototype.toString.call(data) === '[object Array]') {

                for(var i = 0; i < data.length; i++) {

                    text += recursiveTableau(data[i]);
                }
            }

            else
                text += recursiveTableau(data);


            $('#validatorExtendedData-content').html(text);
            $('#validatorExtendedData-modal').modal('show');
        },
        error: function(xhr) {

            $("#error-dialog #error-content").attr("width", 1000);
            $("#error-dialog #error-content").attr("height", 800);
            $("#error-dialog #error-content").contents().find('html').html(xhr.responseText);
        }
    });
}



/**
 * la méthode removeValidations prend en paramètre soit un nombre, soit un array de nombres.
 * Elle envoie une requête au serveur pour supprimer les validations
 */
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



function recursiveTableau(data) {

    var text = '<table class="table table-bordered"><thead><td><b>Champ</b></td><td><b>Donnée</b></td></thead><tbody>', data = data, val = '';

    for(var key in data)  {

        if(isNaN(data[key])) //N'est pas un nombre
        {
            if (!isNaN(Date.parse(data[key]))) { //Est une date

                var date = new Date(data[key]);
                val = date.getDay() + "." + date.getMonth() + "." + date.getFullYear();
            }

            else {//Sinon c'est une chaine de caractère basique
                val = data[key];
            }
        }

        else if(typeof data[key] === 'object') {

            val = recursiveTableau(data[key]);
        }

        else {  //Est un nombre
            val = data[key];
        }

        text += '<tr><td>' + key + '</td><td>' + val + '</td></tr>';

    }

    text += '</tbody></table>';

    return text;
}