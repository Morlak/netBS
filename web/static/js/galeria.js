/**
 * fichier javascript principal de la galerie
 * de la brigade de Sauvabelin
 *
 * @author Guillaume Hochet
 */

/**
 * première chose à faire, on charge des images aléatoires
 * pour afficher la page d'accueil, et l'agrémenter d'images
 */
$(document).ready(function() {
    
    //On récupère une liste d'images à afficher
    $.ajax({
        
        'url' : '/netBS/web/app_dev.php/galerie/first-call',
        'type' : 'GET',
        'datatype' : 'JSON',
        
        success : function(data) {
            
            //On met en place la grille de photos
            var html = '';
            
            for (i in data.images) {
                
                html += '<a data-title="' + data.images[i].album + '" data-lightbox="set-' + i + '" href="' +data.images[i].photo + '"><img src="' + data.images[i].thumbnail + '" /></a>';
            }
            
            $('#gallery').html(html);
            
            $('#gallery').justifiedGallery({
			
                margins: 3,
                randomize:true,
                lastRow: 'hide',
                rowHeight: 200,
                sizeRangeSuffixes: {'lt100' : '', 'lt240' : '', 'lt320' : '', 'lt500' : '', 'lt640' : '', 'lt1024' : ''}
            });
	    
	    //On remplit le bouton accueil
	    $('#homie').attr("onclick", "updateFromDroit(" + data.present.id + ");");
	    
	    //On remplit la barre de navigation
	    populateNav(data);
	    
	    //On fait apparaître la barre de navigation
	    $('#insiders-container').fadeToggle(200);
	    
	    
        },
        error : function() { alert('erreur lors de la récupération des images')}
    });
});

var c = 0;

/**
 * la fonction populateNav va s'occuper de mettre à jour le contenu de la barre
 * de navigation de manière autonome, à partir des données qu'elle reçoit
 */
function populateNav(data) {
    
    //On vide la barre de navigation
    $('.insider .container').empty();
    
    //On populate d'abord le bouton précédent
    if (data.parent.id != null) {
    
	$('#back .i-container').html(   '<button onclick="updateFromDroit(' + data.parent.id + ');">'
				    + '<span class="glyphicon glyphicon-chevron-left" style="' + toGradient(data.parent.color1, data.parent.color2) + '"></span>'
				    + '<span><span>' + data.parent.nom
				    + '</span></span></button>');
    }
    
    //On populate ensuite le bouton présent
    $('#present .i-container').html(   '<button onclick="updateFromDroit(' + data.present.id + ');">'
				    + '<span class="glyphicon glyphicon-refresh" style="' + toGradient(data.present.color1, data.present.color2) + '"></span>'
				    + '<span><span>' + data.present.nom
				    + '</span></span></button>');

    //On populate ensuite les boutons des enfants
    var enfants = '';
    for(i in data.enfants) {
	
	enfants += '<button onclick="updateFromDroit(' + data.enfants[i].id + ');">'
		+ '<span class="glyphicon glyphicon-chevron-right" style="' + toGradient(data.enfants[i].color1, data.enfants[i].color2) + '"></span>'
		+ '<span><span>' + data.enfants[i].nom
		+ '</span></span></button>';
    }
    
    $('#enfants .i-container').html(enfants);
    
    //Et finalement, on populate les dossiers et albums à l'aide de jsTree
    //$('#dossiers-et-albums').html(tree(data.dossiers));
    $('#dossiers-et-albums').jstree('destroy');
    $("#dossiers-et-albums").bind(
	"select_node.jstree", function(event, data) {
	    
	    var evt =  window.event || event;
            var button = evt.which || evt.button;
	    
            if( button != 1 && ( typeof button != "undefined")) return false;
	    
	    else {

		if (data.node.li_attr.type == 'album') {
		    
		    //On ouvre un album ! chouetos
		    populateGalerie(data.node.li_attr.album_id);
		}
	    }
	}
    );
    $('#dossiers-et-albums').jstree({
	
	'core' : {
	    
	    'data' : data.dossiers
	},
	
	"contextmenu" : {
	    items: customMenu
	},
	
	"plugins" : [ "contextmenu" ]
    });

}

/**
 * la méthode populate galerie va redessiner la galerie avec les nouvelles photos
 */
function populateGalerie(id) {
    
    //On fait disparaître la galerie
    $('#gallery').fadeToggle(200);
    
    
    $.ajax({
	
	url: '/netBS/web/app_dev.php/galerie/get-pictures/' + id,
	type: 'GET',
	datatype: 'json',
	success: function(data) {
	    
	    $('#gallery').empty();
	    $('#gallery').toggle();
	    
	    //Si la section avait la classe overflowed, on la vire
	    if ($('section').hasClass('overflowed'))
		$('section').removeClass('overflowed');
	    
	    //On reremplit la galerie
	    var html = '';
            
            for (i in data.images) {
                
                html += '<a data-title="' + data.images[i].album + '" data-lightbox="set" href="' +data.images[i].photo + '"><img src="' + data.images[i].thumbnail + '" /></a>';
            }
            
            $('#gallery').html(html);
	    $('#gallery').justifiedGallery({
			
                margins: 3,
                randomize:true,
                lastRow: 'hide',
                rowHeight: 200,
                sizeRangeSuffixes: {'lt100' : '', 'lt240' : '', 'lt320' : '', 'lt500' : '', 'lt640' : '', 'lt1024' : ''}
            });
	    
	},
	error: function() { alert('Erreur lors de la mise à jour de la galerie'); }
    });
}
/**
 * la méthode updateFromDroit va mettre à jour le contenu de la barre de navigation
 * en fonction de l'action de l'utilisateur
 */
function updateFromDroit(id) {
    
    //On cache la barre de navigation
    $('#insiders-container').fadeToggle(200);
	
    $.ajax({
	
	url: '/netBS/web/app_dev.php/galerie/update-droit/' + id,
	type: 'GET',
	'datatype' : 'JSON',
	success: function(data) {
	    
	    populateNav(data);
	    //On fait réapparaitre la navigation
	    $('#insiders-container').fadeToggle(200);
	},
	error: function() {alert('Un problème inconnu est survenu. Nous faisons notre possible pour le résoudre, veuillez réessayer plus tard')}
    });
}

/**
 * la fonction toGradient génère le code css d'un gradient
 * en fonction des deux couleurs. Si une manque, c'est un fond uni qu'on applique
 */
function toGradient(color1, color2) {
    
    var uni = null;
    
    if ((color1 == null || color1 == '') && (color2 == null || color2 == ''))
	uni = 'aef2be';
	
    else if ((color1 != null || color1 != '') && (color2 == null || color2 == ''))
	uni = color1;
    
    else if ((color1 == null || color1 == '') && (color2 != null || color2 != '')) 
	uni = color2;
    
    if (uni != null) {
	return 'background-color:#' + uni;
    }
    
    //On génère le gradient
    html    = 'background:' + color1 + ';'
	    + 'background: -moz-linear-gradient(-45deg, #' + color1 + ' 30%, #' + color2 + ' 80%);'
	    + 'background: -webkit-linear-gradient(-45deg, #' + color1 + ' 30%,#' + color2 + ' 80%);'
	    + 'background: -o-linear-gradient(-45deg, #' + color1 + ' 30%,#' + color2 + ' 80%);'
	    + 'background: -ms-linear-gradient(-45deg, #' + color1 + ' 30%,#' + color2 + ' 80%);'
	    + 'background: linear-gradient(135deg, #' + color1 + ' 30%,#' + color2 + ' 80%);';
    
    return html;
    
}

/**
 * cette fonction permet de créer le contextmenu de la selection d'un
 * album ou dossier
 */
function customMenu(node) {
    
    var items = {
	
	"ddl_zip" : {
	    
	    "label" : "Télécharger",
	    "separator_before" : false,
	    "separator_after" : false,
	    "action" : function() {
		
		var id 	= node.li_attr.album_id;
		var nom = node.text;
		
		//On réalise la procédure de téléchargement en ajax
		$.ajax({
		    
		    url: '/netBS/web/app_dev.php/galerie/zip-album/' + id,
		    type: 'POST',
		    datatype: 'json',
		    success: function() {},
		    error: function() {alert("Le zip n'a malheureusement pu être préparé");}
		});
		
	    },
	    "icon" : "/netBS/web/static/images/galerie/zip.png",
	}
    }

    return items;
}