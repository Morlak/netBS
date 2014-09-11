/**
 * Le système modificator permet de modifier par ajax le contenu d'une span
 * elle-même contenue dans une div de classe "data"
 *
 * le nom de la span fournit l'attribut à modifier
 */

$(document).ready(function () {

    //On rend les span éditables
    //$('.editable').prop('contenteditable', true);
});

//Pour tous les toggle-editable, on leur ajoute un id aléatoire,
//Afin d'afficher un joli label en forme de toggle
$('.toggle-editable').each(function (i, obj) {

    var id = Math.floor((Math.random() * 1000000) + 1);

    $(obj).attr('id', id);
    $(obj).after('<label for="' + id + '" class="small-toggle"></label>');
});

//Et enfin pour les date-editable, lorsqu'on focus dessus, on fait apparaître l'input
//hidden qui est devant la span, et qui a la classe datepicker, puis on fout le focus dessus.
$('.date-editable').click(function () {

    //On affiche l'input et on lui file la valeur actuelle
    var datum = $(this).text().split('.');
    $(this).next().val(datum[2] + '-' + datum[1] + '-' + datum[0]);
    $(this).next().attr("type", "text");

    $(this).next().focus();
    $(this).hide();
});

$('.date-editable-input').change(function () { //On a inséré une nouvelle date pour le datepicker

    //On va insérer la nouvelle date dans le contenteditable d'avant
    datum = $(this).val().split('-');
    $(this).prev().text(datum[2] + '.' + datum[1] + '.' + datum[0]);

    //On recache l'input
    $(this).attr("type", "hidden");
    $(this).prev().addClass('editable-working');
    $(this).prev().show();

    //Et on appelle le modifikator dessus
    modifikator($(this).prev().attr("name"), $(this).prev().text(), $(this).prev());
});


//A chaque changement sur un toggle-editable également

$('.toggle-editable').change(function () {

    //Appel de l'ajax
    $(this).prop('disabled', true);
    modifikator($(this).attr("name"), $(this).prop('checked'), $(this));
});


//A chaque changement sur une span, on appelle le modifikator
var inputOriginalValue;

$('form.ajaxedit input').focusin(function () {

    inputOriginalValue = $(this).val();
});

$("form.ajaxedit input").focusout(function () {

    if ($(this).val() !== inputOriginalValue) {

        //Appel de l'ajax
        /* Remplace les _ par des . pour le chemin de l'entité */
        modifikator($(this).attr("id").replace(/_/g, "."), $(this).val(), $(this));
    }
});


function modifikator(entity, content, obj) {

    $(obj).parent().addClass("has-warning");

    $.ajax({
        url: Routing.generate('InterneGlobal_modifikator', {'entity': entity, 'id': $(obj).attr('entity-id')}),
        type: 'POST',
        data: {
            value: content
        },
        dataType: 'json',

        success: function (data) {
            $(obj).parent().removeClass("has-warning");
            $(obj).parent().addClass("has-success");

            setTimeout(function () {
                $(obj).parent().removeClass("has-success");
            }, 600);
        },

        error: function (xhr, err) {
            $(obj).parent().removeClass("has-warning");
            $(obj).parent().addClass("has-error");


            $("#error-dialog").dialog({
                width:'auto',
                height: 'auto',
                resizable: false,
                position: [20,20],
                modal: true,
                buttons: {
                    Ok: function () {
                        $(this).dialog("close");
                    }
                }
            });


            $("#error-dialog #error-content").attr("width", 1000);
            $("#error-dialog #error-content").attr("height", 800);
            $("#error-dialog #error-content").contents().find('html').html(xhr.responseText);

            setTimeout(function () {
                $(obj).parent().removeClass("has-error");
            }, 6000);
        }
    });
}

function urlencode(str) {

    str = (str + '')
        .toString();

    return encodeURIComponent(str)
        .replace(/!/g, '%21')
        .replace(/'/g, '%27')
        .replace(/\(/g, '%28')
        .replace(/\)/g, '%29')
        .replace(/\*/g, '%2A')
        .replace(/%20/g, '+');
}