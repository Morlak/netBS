/**
 * adder.JS
 * le système permettant de faire des ajouts de masse sur les listes
 * de membres.
 */

/**
 * en premier lieu, on affiche le bouton adder sur les tables de membres
 * afin d'offrir la possibilité de faire des modifications en masse
 */

$(function() {
    
    //Première chose, ajouter le bouton d'ajout à la liste principale
    var wtoolbar = $('.datatable-membre').parent().parent().prev().children('.widget-toolbar'); //widget-toolbar
    
    //On ajoute un bouton d'ajout à la liste
    var button = '<div class="btn-group">' +
                    '<button type="button" data-toggle="dropdown">' +
                      'Modifications en masse<span class="caret"></span>' +
                    '</button>' +
                    '<ul class="dropdown-menu dropdown-menu-right" role="menu">' +
                      '<li><a onclick="adder(this, \'attributions\');" href="#">Attributions</a></li>' +
                      '<li><a onclick="adder(this, \'distinctions\');" href="#">Distinctions</a></li>' +
                      '<li><a onclick="adder(this, \'modifications\');" href="#">Modifications</a></li>' +
                    '</ul>' +
                  '</div>';
                  
    $(button).appendTo(wtoolbar);
    
    //Mais également dans la modale du listing, pour offrir l'adder sur le listing
    var buttonListing = '<div class="btn-group pull-right">' +
                    '<button class="btn btn-primary" data-toggle="dropdown">' +
                      'Modifications en masse <span class="caret"></span>' +
                    '</button>' +
                    '<ul class="dropdown-menu dropdown-menu-right" role="menu">' +
                      '<li><a onclick="adderListing(\'attributions\');" href="#">Attributions</a></li>' +
                      '<li><a onclick="adderListing(\'distinctions\');" href="#">Distinctions</a></li>' +
                      '<li><a onclick="adderListing(\'modifications\');" href="#">Modifications</a></li>' +
                    '</ul>' +
                  '</div>';
    
    $(buttonListing).appendTo('#listing-options');
});


/**
 * la méthode adder est le coeur du système. Elle va récupérer la liste des membres
 * sur lesquels travailler, puis afficher la modale correspondante à l'action souhaitée
 */
function adder(btn, type) {
    
    //On récupère la table correspondante au bouton
    var table = $(btn).parent().parent().parent().parent().parent().next().children('.dataTables_wrapper').children('.datatable-membre');
    
    var cbIds = $('td input:checkbox:checked', table);
    
    //On ne récupère que les ids des checkbox qui sont cochées
    var ids = [];
    
    $.each(cbIds, function(index, value) {
        
        var txt = $(value).attr("id").split("_");
        ids[index] = txt[1];
    });
    
    //Tout d'abord, si les ids sont vides, on annule TOUT
    if(ids.length == 0) {
        $('#flash-container').html('<div class="row"><div class="alert alert-info alert-dismissible col-lg-6 col-lg-offset-3" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Aucun membre sélectionné</strong></div></div>');
        return 0;
    }
    
    //Ensuite on trie les doublons
    var tempids = [];
    
    $.each(ids, function(i, el){
        if($.inArray(el, tempids) === -1) tempids.push(el);
    });
    
    ids = tempids;
    
    /**
     * une fois les IDs obtenus, on exécute chaque méthode spécifique d'adder. Par exemple,
     * adderAttributions
     */
    
    if (type == 'attributions')
        adderAttributions(ids);
        
    else if (type == 'distinctions')
        adderDistinctions(ids);
}

/**
 * la méthode adderListing fait la même chose qu'adder, mais récupère ses IDs de
 * la table de listing
 */
function adderListing(type) {
    
    //On ferme la modal de listing
    $('#listing-modal').modal('hide');
    
    /**
     * on a juste à récuperer les IDs vu que la gestion des doublons est faite par
     * le listing
     */
    
    var ids = [];
    var ixx = 0;
    
    $.each($('.listing-checkbox'), function(index, value) {
        
        var idcb = $(value).attr("name");
        var id = idcb.split('_');
        
        ids[ixx] = id[1];
        ixx++;
        
    });
    
    if (type == 'attributions')
        adderAttributions(ids);
        
    else if (type == 'distinctions')
        adderDistinctions(ids);
}

/**
 * l'adder attributions
 */
function adderAttributions(ids) {
    
    //Première chose, on stocke l'ensemble des IDs dans un input pour avoir un backup,
    //utilisé notamment lorsqu'on termine des attributions
    $('#adder-attributions-ids').val(ids);
            
    /**
     * requête ajax OKLM pour récupérer les informations nécessaires à l'affichage
     * de la modale attributions, qui est regénerée à chaque fois
     */
    $.ajax({
            
        url: Routing.generate('InterneGlobal_adder_get_basic_data_attributions'),
        type: 'POST',
        data: {ids:ids},
        
        success: function(data) {
            
            //On vide le container
            $('#adder-add-attributions-membres-groupes').empty();
            
            //On génère les options
            var fonctions = '';
            
            for (var i = 0; i < data.fonctions.length; i++) 
                fonctions += '<option value="' + data.fonctions[i].id + '">' + data.fonctions[i].nom + '</>';
            
            
            $('#adder-add-attributions-fonctions').html(fonctions);
            $('#adder-add-attributions-fonctions').chosen({width: "98%"}); 
            $('#adder-add-attributions-fonctions').trigger('chosen:updated');
            
            //On génère le select des groupes
            var groupes = '';
            
            for (var i = 0; i < data.groupes.length; i++) 
                groupes += '<option value="' + data.groupes[i].id + '">' + data.groupes[i].nom + '</>';
            
            
            /**
             * ensuite, on affiche pour chaque membre, un groupe à choisir, car chaque membre n'aura pas la même
             * attribution
             */
            
            for(var i = 0; i < data.membres.length; i++) {
                
                membresGroupes =    '<div class="row">' +
				    '<div class="col-sm-3"><label>' + data.membres[i].prenom + ' ' + data.membres[i].nom + '</label></div>' +
				    '<div class="col-sm-9">' +
				    '<select class="adder-add-attributions-select" id="groupeMembre_' + data.membres[i].id + '">' + groupes + '</select>' +
				    '</div>' +
                                    '</div>';
                                    
                $('#adder-add-attributions-membres-groupes').append(membresGroupes);
                $('#groupeMembre_' + data.membres[i].id).chosen({width: "98%"}); 
                $('#groupeMembre_' + data.membres[i].id).trigger('chosen:updated');
                
            }
            
            /**
             * ensuite on s'occupe de l'onglet terminer des attributions. On génère tout d'abord
             * la liste des fonctions disponibles
             */
            var endFonctions = '';
            
            for (var i = 0; i < data.terminaison.fonctions.length; i++)
                endFonctions += '<option value="' + data.terminaison.fonctions[i].id + '">' + data.terminaison.fonctions[i].nom + '</>';
                
            $('#adder-end-attributions-parameter-fonction').html(endFonctions);
            $('#adder-end-attributions-parameter-fonction').chosen({width: "98%"});
            $('#adder-end-attributions-parameter-fonction').trigger('chosen:updated');
            
            //Puis la liste des groupes disponibles
            
            var endGroupes = '';
            for (var i = 0; i < data.terminaison.groupes.length; i++)
                endGroupes += '<option value="' + data.terminaison.groupes[i].id + '">' + data.terminaison.groupes[i].nom + '</>';
                
            $('#adder-end-attributions-parameter-groupe').html(endGroupes);
            $('#adder-end-attributions-parameter-groupe').chosen({width: "98%"});
            $('#adder-end-attributions-parameter-groupe').trigger('chosen:updated');
            
            
            //Puis les dates de début
            
            var endDateDebut = '';
            for (var i = 0; i < data.terminaison.debuts.length; i++) {
                
                var datum = data.terminaison.debuts[i].split('-');
                
                endDateDebut += '<option value="' + datum[0] + '-' + datum[1] + '-' + datum[2] + '">' + datum[2] + '.' + datum[1] + '.' + datum[0] + '</>';
            }
                
            $('#adder-end-attributions-parameter-debut').html(endDateDebut);
            $('#adder-end-attributions-parameter-debut').chosen({width: "98%"});
            $('#adder-end-attributions-parameter-debut').trigger('chosen:updated');
            
            
            //On affiche la modal
            $('#modal-adder-attributions').modal();
            
        },
        
        error: function(data) {
            alert('erreur lors de la récupération des données');
        }
    });
}

/**
 * adderAddAttributions
 * Va ajouter une masse d'attributions au membres qui ont subit l'adder
 */
function adderAddAttributions() {
    
    //en premier lieu on récupère les valeurs
    var fonction        = $('#adder-add-attributions-fonctions').val(),
        membresGroupes  = $('.adder-add-attributions-select'),
        debut           = $('#adder-add-attributions-debut').val(),
        fin             = $('#adder-add-attributions-fin').val();
        
    /**
     * ensuite on traite les données des membres
     * en récupérant l'ID du membre, ainsi que le groupe auquel il est lié
     */
    var dataMembres = [];
    var i = 0;
    
    $.each(membresGroupes, function(index, value) {
        
        var id = $(value).attr("id");
            id = id.split("_");
        
        var membre = {id : id[1], groupe : $(value).val()};
        dataMembres[i] = membre;
        
        i++;
        
    });
    
    //Une fois avoir récupéré toutes les données, on envoie en AJAX au serveur
    
    $.ajax({
        
        url: Routing.generate('InterneGlobal_adder_add_attributions'),
        data: {fonction : fonction, debut: debut, fin:fin, membres : dataMembres},
        type: 'POST',
        
        success: function() {
            
            alert("Masse ajoutée");
        },
        
        error: function(data) {
            
            alert(" Erreur lors de l'ajout de masse");
        }
        
    });
}

/**
 * cette méthode va permettre de terminer des attributions
 * On récupère les infos sur les attributions, et c'est parti
 */
function adderEndAttributions() {
    
    //On récupère les ids concernées, on les avait backup dans un input hidden
    var ids = $('#adder-attributions-ids').val() + '';
    
    //On récupère les données
    var parameter = $('#adder-end-attributions-parametre-select').val(),
        data      = $('#adder-end-attributions-parameter-' + parameter).val(),
        end       = $('#adder-end-attributions-end').val();
        
        
    //Une fois les données chopées, on balance l'ajax a l'ancienne
    $.ajax({
        
        url: Routing.generate('InterneGlobal_adder_end_attributions'),
        type: 'POST',
        data: {parametre:parameter, donnee: data, ids : ids, fin: end},
        
        success: function() {
            alert('Les attributions ont bien été terminées');
        },
        
        error: function(data) {
            alert('Erreur lors de la finition des attributions');
        }
        
    });
}

/**
 * cette petite méthode sert simplement à afficher, cacher les paramètres pour terminer
 * une attribution, et éviter que l'utilisateur n'en sélectionne plusieurs
 */
function updateAdderEndAttributions() {
    
    var val = $('#adder-end-attributions-parametre-select').val();
    
    //On cache les trois paramètres
    $('.adder-end-attributions-parametre-fieldset').hide();
    
    //On affiche le bon
    $('#adder-end-attributions-parametre-' + val).show();
}

/**
 * ADDER distinctions
 *
 * adderDistinctions va permettre d'afficher la modale pour ajouter ou supprimer en masse
 * des distinctions.
 */
function adderDistinctions(ids) {
   
    //Première chose, on stocke l'ensemble des IDs dans un input pour avoir un backup,
    //utilisé notamment lorsqu'on supprime des distinctions
    $('#adder-distinctions-ids').val(ids);
            
    /**
     * requête ajax OKLM pour récupérer les informations nécessaires à l'affichage
     * de la modale de distinctions
     */
    $.ajax({
            
        url: Routing.generate('InterneGlobal_adder_get_basic_data_distinctions'),
        type: 'POST',
        data: {ids:ids},
        
        success: function(data) {
            
            //On génère la liste des distionctions
            var liste = '';
            
            for(var i = 0; i < data.length; i++) {
                
                liste += '<option value="' + data[i].id + '">' + data[i].nom + '</>';
            }
            
            //On insère la liste et on la chosen
            $('#adder-add-distinctions-select').html(liste);
            $('#adder-add-distinctions-select').chosen({width: "98%"});
            $('#adder-add-distinctions-select').trigger('chosen:updated');
            
            //On affiche la modal
            $('#modal-adder-distinctions').modal('show');
        },
        
        error: function(data) {
            alert('erreur lors de la récupération des données');
        }
    });
}

/**
 * la méthode adderAddDistinctions va permettre d'ajouter plusieurs distinctions
 * à plusieurs membres.
 */
function adderAddDistinctions() {
    
    //On récupère les valeurs
    var distinctions = $('#adder-add-distinctions-select').val(),
        datum        = $('#adder-add-distinctions-date').val(),
        ids          = $('#adder-distinctions-ids').val() + '';
        
    //Ptite requete ajax OKLM
    $.ajax({
        
        url: Routing.generate('InterneGlobal_adder_add_distinctions'),
        type: 'POST',
        data: {distinctions:distinctions, obtention:datum, membres:ids},
        
        success: function(data) {
            
            alert('Distinctions ajoutées. N\'oubliez pas d\'actualier');
        },
        error: function(data) {
            
            alert("Erreur lors de l'ajoue des distinctions");
        }
    });

}