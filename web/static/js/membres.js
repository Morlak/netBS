/**
 * membre.js gère toute la gestion sur la page d'un membre, comme
 * l'ajout suppression d'attributions et distinctions, les adresses...
 */

/**
 * cette partie va permettre de changer de manière dynamique l'affichage
 * de la modale d'ajout d'attributions en fonction des choix dans le chosen
 * multiple de groupe
 */

var saved = [];

$('#attributions-membre-fonctions-dynamics').chosen().change(function() {
    
    /*
     * on va comparer les valeurs qui étaient sauvées avant le précedent changement
     * avec les valeurs actuellement enregistrées pour voir les changements
     */
    var fncts = $('#attributions-membre-fonctions-dynamics').val();
    if (fncts == null)
        fncts = [];
    
    //On compare la longueur
    if (saved.length > fncts.length) {
        
        //On a supprimé un élément, on regarde lequel, et on supprimme son DOM
        for(var i = 0; i < saved.length; i++) {
            
            if ($.inArray(saved[i], fncts) == -1) {
                
                var datax   = saved[i].split('__');
                var id      = datax[0];
                var nom     = datax[1];
                
                //Voila l'élément qu'on a supprimé, on le supprime donc de la modale
                $('#membre-attributions-custom-dynamics-' + id).remove();
            }
        }
    }

    else if (saved.length  < fncts.length) {
        
        //On a rajouté un élément, on regarde lequel
        for(var i = 0; i < fncts.length; i++) {

            if ($.inArray(fncts[i], saved) == -1) {
                
                var datax   = fncts[i].split('__');
                var id      = datax[0];
                var nom     = datax[1];
                
                //On a trouvé l'élément, on génère de l'html
                var html =  '<fieldset class="form-element membre-dynamics-fieldset" id="membre-attributions-custom-dynamics-' + id + '"><div class="row">' +
                            '<div class="col-sm-3"><label>' + nom + ' à</label></div>' +
                            '<div class="col-sm-9" id="attributions-membre-groupes-container-' + id + '">' +
                            '</div></div>' +
                            //début
                            '<div class="row">' +
                            '<div class="col-sm-3"><label>Date de Début</label></div>' +
                            '<div class="col-sm-9"><input type="text" class="datepicker attributions-membre-debut" id="attributions-membre-debut-dynamics-' + id + '" />' +
                            '</div></div>' +
                            //fin
                            '<div class="row">' +
                            '<div class="col-sm-3"><label>Date de fin (facultatif)</label></div>' +
                            '<div class="col-sm-9"><input type="text" class="datepicker attributions-membre-fin" id="attributions-membre-fin-dynamics-' + id + '" />' +
                            '</div></div></fieldset>';
                
                //On insère au bon endroit
                $('#attributions-membre-fieldset-dynamics').append(html);
                
                //On copie la liste de groupe
                $('.attributions-membre-groupes-prototype').clone().appendTo('#attributions-membre-groupes-container-' + id);
                
                //On fournit un ID au select concerné
                $('#attributions-membre-groupes-container-' + id).find('.attributions-membre-groupes-prototype').attr("id", "attributions-membre-groupe-dynamics-" + id);
                
                //Et enfin on le chosen
                $("#attributions-membre-groupe-dynamics-" + id).chosen({width: "97%"});
                
                //On datepicker les éléments à datepicker
                $('.datepicker').datepicker({ 
	
                        dateFormat: "yy-mm-dd", 
                        changeYear: true,
                        yearRange : "1990:" + new Date().getFullYear() 
                });

            }
        }
    }
    
    //Et finalement on sauve les nouvelles données
    saved = fncts;
    
});

/**
 * cette méthode va permettre d'ajouter une ou plueisuers attributions à un membre
 * en récupérant les données de la modale
 */
function addAttributionsToMembre() {
    
    //Première chose, on récupère les différentes données
    var membreId        = $('#membre-id').val();
    var data            = [];

    //On récupère les données dynamiques
    $.each($('.membre-dynamics-fieldset'), function(i,v) {
        
        var id = $(v).attr("id").split('-');
            id = id[4];
        
        data.push({
            
            fonction: id,
            groupe  : $('#attributions-membre-groupe-dynamics-' + id).val(),
            debut   : $('#attributions-membre-debut-dynamics-' + id).val(),
            fin     : $('#attributions-membre-fin-dynamics-' + id).val()
        });
    });
    
    $.ajax({
        
        url     : Routing.generate('InterneStructure_add_attributions', {id : membreId}),
        type    : 'POST',
        data    : {donnees: JSON.parse(JSON.stringify(data))},
        success : function(data) {
            
            alert('Attributions ajoutées, la page va s\'actualiser');
            location.reload();
        },
        error   : function(data) {
            
            $('body').html(JSON.stringify(data));
            //alert("Erreur lors de l'ajout des attributions, vérifiez que les champs de date soit totalement vide (même pas un espace), ou qu'ils contiennent une date");
        }
    });    
}












/**
 * la méthode modifyAttribution permet de modifier une attribution existante.
 * Cette fonction a pour rôle de mettre les champs de la fonction en modifiable
 */
function modifyAttribution(btn, id) {
    
    /**
     * première chose, on vérifie que la ligne soit en cours de modification ou pas. Pour cela,
     * on vérifie si le bouton à la classe active
     */
    var tr  = $(btn).parent().parent();
    var tds = $(tr).find('td');
    
    if ($(btn).hasClass('active')) {
        
        $(btn).removeClass('active');
        
        //On supprimme le bouton de validation
        $(btn).next().remove();
        
        //On recache tous les éléments de modification
        $.each(tds, function(i, v) {
            
            //On affiche les valeurs finales
            if (i != 4) {
                $(v).children('.modifikator-val').hide();
                $(v).children('.final-val').show();
            }
        
        });
    }
    
    else {
        
        $(btn).addClass('active');
        $(btn).after('<a style="margin-left:4px;" onclick="validateAttributionModified(this, ' + id + ')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-ok"></span></a>');
        
        //On va cacher les données comprises dans chaque td, puis afficher des options pour chaque donnée
        
        $.each(tds, function(i, v) {
            
            //On cache les valeurs finales
            if (i != 4)
                $(v).children('.final-val').hide();
                
            //Et on affiche les modificateurs à la place
            if (i != 4)
                $(v).children('.modifikator-val').show();
                
            //Enfin, on chosen les deux selects
            if (i == 0 || i == 1)
                $(v).children('.modifikator-val').find('select').chosen({width: "80%"});
        
        });
        
    }
}


/**
 * la method validateAttributionModified va permettre de modifier les données d'une attribution
 * avec les nouvelles données passées
 */
function validateAttributionModified(btn, id) {
    
    //On récupère l'ensemble des données à envoyer
    var tr  = $(btn).parent().parent(),
        tds = $(tr).find('td');
        
    var data = [];
    
    $.each(tds, function(i, v) {
        
        if (i != 4) {
            
            //On sauvegarde les données
            var container = $(v).children('.modifikator-val');
            data[i] = $(container).children().val();
        }
    });
    
    //Une fois les données récupérées, on envoie en AJAX
    $.ajax({
        
        url: Routing.generate('InterneStructure_attribution_modify'),
        type: 'POST',
        data: {id: id, donnees: data},
        success: function(data) {
            
            //On modifie les valeurs finales par les valeurs qui ont été modifiées
            $.each(tds, function(i,v) {
                
                if (i == 0) 
                    $(v).find('.final-val').text(data.fonction); //fonction
                    
                else if (i == 1) {
                    
                    $(v).find('.final-val').attr("href", Routing.generate('InterneStructure_voir_groupe', {id : data.groupeId}));
                    $(v).find('.final-val').text(data.groupeN);
                }
                
                else if (i == 2)
                    $(v).find('.final-val').text(data.debut);
                    
                else if (i == 3)
                    $(v).find('.final-val').text(data.fin);
            });
            
            //On cache les données et on alert
            modifyAttribution($(btn).prev(), id);
            $('#flash-container').html('<div class="row"><div class="alert alert-success alert-dismissible col-lg-6 col-lg-offset-3" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><strong>Attribution modifée</strong></div></div>');
        },
        error: function(data) {
            
            //alert("Erreur lors de la modification, vérifiez que les champs des dates soient soit remplis d'une date YYYY-MM-DD, soit totalement vide (même pas d'espaces)");
            $('body').html(JSON.stringify(data));
        }
    });
}
