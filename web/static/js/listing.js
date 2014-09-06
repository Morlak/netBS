/**
 * listing.js
 *
 * permet de créer des listes de membres à partir des datatables
 * présentes sur l'ensemble du netBS.
 *
 * Pour chaque .datatable-membre, le script va ajouter un bouton permettant
 * d'ajouter à la liste actuelle les membres qui y sont, et de stocker
 * la liste en sessionStorage
 */

var listingTable = $('#listing-table').DataTable({ paging: false });
var cbChecked = 0;

$(function() {
    
    //Première chose, ajouter le bouton d'ajout à la liste principale
    var wtoolbar = $('.datatable-membre').parent().parent().prev().children('.widget-toolbar'); //widget-toolbar
    
    //On ajoute aussi le bouton magique qui va permettre de cocher toutes les lignes d'une datatable
    $('.datatable-membre tr th:first-child').append('<input type="checkbox" onclick="listingToggleAll(this);" />');
    
    //On ajoute un bouton d'ajout à la liste
    var button = '<button onclick="addToList(this);">Ajouter à la liste</button>';
    $(button).appendTo(wtoolbar);
});

/**
 * on ajoute un event listener sur les checkbox de classe "listing-checkbox" pour avoir le nombre de checkbox
 * chequées à tout moment
 */
function countListingCheckbox() {
    
    var compteur = 0;
    
    $.each($('.listing-checkbox'), function(index, value) {
        
        if($(value).prop('checked'))
            compteur++;
    });
    
    cbChecked = compteur;
    $('#listing-remove-span').text('(' + compteur + ')');
};

/**
 * la méthode remove va supprimer les entrées du listing, puis regénerer la table du listing
 */
function listingRemove() {
    
    var ids = [];
    var c   = 0;
    
    $.each($('.listing-checkbox'), function(index, value) {
        
        /**
         * on récupère les checkbox pas cochées, on récupère leur id
         * puis on les stocke en sessionStorage. Comme ca, les cb qui étaient cochées
         * n'y sont plus
         */
        if(!$(value).prop('checked')) {
            
            var data = $(value).attr('name');
            var aper = data.split('_');
            var id   = aper[1];
            
            ids[c] = id;
            c++;
        }
    });
    
    //On stocke le nouvel array en session
    sessionStorage.setItem('listing', ids);
    
    //On regénère la table
    retrieveListing();
    
    //On met à jour le compteur
    var cbChecked = 0;
    $('#listing-remove-span').text('(0)');
}

/**
 * la méthode retrieveListing va récupérer la liste complète, à l'aide d'une requête ajax. va ensuite l'afficher
 * en POP-UP pour travailler dessus
 */
function retrieveListing() {
    
    //On récupère la liste
    var liste = sessionStorage.getItem('listing');
    
    //Si la liste est nulle, on affiche qu'il y a rien et on exit
    if (liste == null || liste == '') {
        
        //On affiche une alerte de liste vide
        $('#flash-container').html('<div class="row"><div class="alert alert-info alert-dismissible col-lg-6 col-lg-offset-3" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>La liste est vide</strong></div></div>');
        
        //Si la modal était ouverte, ca veut dire qu'on a vidé la liste, donc on ferme la modal
        $('#listing-modal').modal('hide');
        return 0;
    }
    
    //On supprime l'éventuelle premiere virgule
    if(liste.charAt(0) == ',') liste = liste.slice(1);
    
    //Sinon on récupère la liste en ajax
    $.ajax({
        
        //url:    '/netBS/web/app_dev.php/interne/fichier/listing/retrieve-liste',
        url: Routing.generate('InterneFichier_listing_retrieve_liste'),
        type:   'POST',
        data: { listing: liste },
        
        success: function(data) {
            
            listingTable.clear();
            
            for(var i = 0; i < data.length; i++) {
                
                listingTable.row.add({
                    
                    "0" : '<input type="checkbox" onclick="countListingCheckbox();" class="listing-checkbox" name="listingMembre_' + data[i].id + '" />',
                    "1" : data[i].prenom,
                    "2" : data[i].nom,
                    "3" : data[i].numeroBS,
                    "4" : data[i].fonction,
                    "5" : data[i].groupe
                }).draw();
            }
            
            //Apparition de la modal
            $('#listing-modal').modal();
            
        },
        
        error: function(data) {
            alert('erreur lors de la récupération de la liste, veuillez réessayer');
        }
    });
}

/**
 * la méthode listingToggleAll va permettre de cocher toutes les lignes d'une datatable
 */
function listingToggleAll(cb) {
    
    var table = $(cb).parent().parent().parent().parent();
    var cbs = $('td:first-child input:checkbox', table);
    var etat = $(cb).prop('checked');
    
    //On met ensuite l'état de la cb à toutes les cb
    $.each(cbs, function(index, value) {
        
        $(value).prop('checked', etat);
    });
}

/**
 * la méthode addToList va rechercher la table liée au bouton cliqué,
 * récupérer l'ensemble des ids qui composent la table, et les ajouter au sessionStorage
 */
function addToList(btn) {
    
    //On trouve la table
    var table = $(btn).parent().parent().next().children('.dataTables_wrapper').children('.datatable-membre');
    
    //On récupère l'ensemble des ids de la table, en passant par les checkbox de selection
    var cbIds = $('td input:checkbox:checked', table);
    
    //On ne récupère que les ids des checkbox qui sont cochées
    var ids = [];
    
    $.each(cbIds, function(index, value) {
        
        var txt = $(value).attr("id").split("_");
        ids[index] = txt[1];
    });
    
    //Une fois avoir récupéré l'ensemble des ids qui composaient la table, on envoie
    //pour analyse et stockage
    
    persistList(ids);
}

/**
 * la méthode persistList va persister les ids stockés qui lui sont passés en paramètre.
 * Mais avant, elle vérifie que ces Ids ne soient pas déjà stockés, pour éviter les doublons
 */
function persistList(ids) {
    
    /**
     * tout d'abord, on supprimme les doublons parmis les ids transmis, si il y
     * en a
     */
    var tempids = [];
    
    $.each(ids, function(i, el){
        if($.inArray(el, tempids) === -1) tempids.push(el);
    });
    
    ids = tempids; //On reremplace
    
    //On récupère la liste existante
    var liste = sessionStorage.getItem('listing');
    
    if (liste != null) liste = liste.split(',');
    if (liste != null) {
        
        //Si la liste n'est pas nulle, on va supprimer les éventuels doublons
        for(var i = 0; i < ids.length; i++) {
            
            for(var j = 0; j < liste.length; j++) {
                
                if(ids[i] == liste[j]) {
                    
                    ids[i] = null;
                }
            }
        }
    }
    
    //Une fois les doublons éliminés, on ajoute les éléments de ids à la liste, et
    //on réorganise l'index de l'array
    
    var finale = [];
    var compteur = 0;
    
    if (liste != null) {
        
        for(var i = 0; i < liste.length; i++) {
            
            if (liste[i] !== ',') {
                finale[compteur] = liste[i];
                compteur++;
            }
        }
    }
    
    for(var i = 0; i < ids.length; i++) {
        
        if (ids[i] != null) {
            
            finale[compteur] = ids[i];
            compteur++;
        }
    }
    
    //On réorganise en fonction de la valeur de l'id
    finale.sort();
    
    //On stocke la nouvelle liste en effacant la précédente
    sessionStorage.removeItem("listing");
    sessionStorage.setItem("listing", finale);
}

/**
 * la méthode export va permettre d'exporter une liste de membres
 * En fonction des paramètres passés, la fonction définira côté serveur
 * si c'est en PDF ou en excel qu'il faut l'exporter
 */
function exportListing(type) {
    
    //On sauve la liste actuelle
    saveListing();
    
    //On récupère la liste
    var liste = sessionStorage.getItem('listing');
    
    //On supprime l'éventuelle premiere virgule
    if(liste.charAt(0) == ',') liste = liste.slice(1);
    
    //On télécharge le fichier
    location.href = Routing.generate('InterneFichier_custom_listing_export', {type:type, ids:liste});
}

/**
 * la méthode suivante permet de sauvegarder la liste en conservant
 * l'ordre
 */
function saveListing() {
    
    var ids = [];
    var amount = $('.listing-checkbox');
    var ixx = 0;
    
    $.each($('.listing-checkbox'), function(index, value) {
        
        var idcb = $(value).attr("name");
        var id = idcb.split('_');
        
        ids[ixx] = id[1];
        ixx++;
        
    });
    
    sessionStorage.setItem('listing', ids);
}