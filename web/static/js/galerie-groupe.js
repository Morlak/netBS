/**
 * gère l'affichage des dossiers dans la partie
 * administration de la galerie d'un groupe
 */

$(document).ready(function() {
    
    //On récupère l'ensemble de l'arbre au format JSON
    var id = $('#droit-id').val();
    
    $.ajax({
        
        type : 'POST',
        //url  : '/netBS/web/app_dev.php/interne/global/galerie/retrieveTree/' + id,
        url  : Routing.generate('InterneGlobal_galerie_retrieve_tree', {id:id}),
        dataType : 'json',
        success: function(data) {
            
	    $('#dossiers-n-shit').jstree({
            
                "core" : {
                    "check_callback" : true,
		    'data' : data
                },
                "contextmenu" : {
		    items: customMenu
		},
		
                "plugins" : [ "contextmenu" ]
            });
        },
        error: function(data) {alert('Erreur lors de la récupération des dossiers')}
    });
});


/**
 * génère le context menu pour chaque node
 */
function customMenu(node) {
    
    var items = {
    
	"create" : {
	    "label" : "Créer",
	    "separator_before" : false,
	    "separator_after" : false,
	    "action" : false,
	    "icon" : "/netBS/web/static/images/galerie/add.png",
	    "submenu" : {
		
		"create_folder" : {
		    "separator_before" : false,
		    "separator_after" : false,
		    "label" : "Dossier",
		    "icon" : "/netBS/web/static/images/galerie/folder.png",
		    action: function(data) {
			var inst = $.jstree.reference(data.reference),
			    obj = inst.get_node(data.reference),
			    dossier_id = obj.li_attr.dossier_id;
			     
			
			//On ouvre la modal bootstrap créer un dossier
			//en modifiant le champ de groupe parent par celui choisit
			$('#externe_galeriebundle_dossier_parent option[value=' + dossier_id + ']').attr("selected", "selected");
			$('#ajouter-dossier-modal').modal();
			
		    }
		},
		"create_album" : {
		     "separator_before" : false,
		    "separator_after" : false,
		    "label" : "Album",
		    "icon" : "/netBS/web/static/images/galerie/album.png",
		    action: function(data) {
	
			var inst = $.jstree.reference(data.reference),
			    obj = inst.get_node(data.reference),
			    dossier_id = obj.li_attr.dossier_id;
			
			//On ouvre la modal bootstrap créer un dossier
			//en modifiant le champ de groupe parent par celui choisit
			$('#externe_galeriebundle_album_dossier option[value=' + dossier_id + ']').attr("selected", "selected");
			$('#ajouter-album-modal').modal();
		    }
		}
	    }
	},
	
	"add_photos" : {
	    
	    "label" : "Ajouter des photos",
	    "separator_before" : false,
	    "separator_after" : false,
	    "action" : function() {
		
		var id 	= node.li_attr.album_id;
		var nom = node.text;
		
		$('#album-name').text(nom);
		$('#album-id').val(id);
		$('#ajouter-photos-modal').modal();
		
	    },
	    "icon" : "/netBS/web/static/images/galerie/picture_add.png",
	},
	
	"gerer_photos" : {
	    
	    "label" : "Gérer les photos",
	    "separator_before" : false,
	    "separator_after" : false,
	    "action" : false,
	    "icon" : "/netBS/web/static/images/galerie/gerer_photos.png",
	},
	
	 "move" : {
	    
	     "label" : "Déplacer",
	    "separator_before" : true,
	    "separator_after" : false,
	    "action" : false,
	    "icon" : "/netBS/web/static/images/galerie/move.png",
	},
	
	"remove" : {
	    
	     "label" : "Supprimer",
	    "separator_before" : false,
	    "separator_after" : false,
	    "action" : false,
	    "icon" : "/netBS/web/static/images/galerie/remove.png",
	}
    }
    
    if (node.li_attr.type == "album") {
	
	delete items.create;
    }
    
    if (node.li_attr.type == "dossier") {
	
	delete items.add_photos;
	delete items.gerer_photos;
    }

    return items;
}

/**
 * permet de changer les couleurs du groupe en ajax yolo
 */
function updateColors() {
    
    //Récupération des deux couleurs
    var color1 = $('#groupe-color1').val(),
	color2 = $('#groupe-color2').val(),
	id     = $('#droit-id').val();
	
    color1a = (color1 == '' || color1 == null) ? color1a = 'FFFFFF' : color1a = color1;
    color2a = (color2 == '' || color2 == null) ? color2a = 'FFFFFF' : color2a = color2;
	
    $.ajax({
	
	//url : '/netBS/web/app_dev.php/interne/global/galerie/update-colors/' + id + '/' + color1a + '/' + color2a,
	url : Routing.generate('InterneGlobal_galerie_update_colors', {id:id, color1:color1a, color2:color2a}),
	type : 'GET',
	
	success: function() { alert('Couleurs modifiées');},
	error: function() { alert('Erreur lors de la modification') }
    })
}

/**
 * soumet le nouvel album, une fois celui-ci ajouté, la réponse au format JSON va
 * permettre de faire apparaître la modale d'ajout de photos
 */

function submitAlbum() {
    
    $("#ajouter-album-form").submit(function(e)
    {
	var postData = $(this).serializeArray();
	var formURL = $(this).attr("action");
	$.ajax(
	{
	    url : formURL,
	    type: "POST",
	    data : postData,
	    success:function(data) 
	    {
		$('#album-name').text(data.nom);
		$('#album-id').val(data.id);
		$('#ajouter-album-modal').modal('toggle');
		$('#ajouter-photos-modal').modal();
	    },
	    error: function(data) 
	    {
		alert(data);
	    }
	});
	e.preventDefault(); //STOP default action
    });
    
    $("#ajouter-album-form").submit(); //Submit  the FORM

}


/**
 * méthode pour la dropzone
 */
    
Dropzone.options.photosForm = {
    
    paramName: "photos",
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 1, //plus ne fonctionne pas
    maxFiles: 200,
    maxFilesize: 2.5,
    clickable: true,
    addRemoveLinks: true,
    acceptedFiles: '.jpg',
    // The setting up of the dropzone
    init: function () {
	
	var submitButton = document.querySelector("#submitPhotos")
	myDropzone = this;
	
	 submitButton.addEventListener("click", function() {
	    myDropzone.processQueue();
	});
	
	this.on("addedfile", function() {
	    $('#submitPhotos').show();
	});
	
	this.on("processing", function(file) {
	    this.options.url = "/netBS/web/app_dev.php/interne/global/galerie/add-photos/" + $('#album-id').val();
	    this.options.autoProcessQueue = true;
	});
	
	this.on("complete", function (file) {
	    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
		
		var total   = $('.dz-preview').length;
		var errors  = $('.dz-error').length;
		var success = $('.dz-success').length;
		
		if (success != 0) {
		    //code
		}
		alert('Album mis à jour. Cliquez sur Ok pour actualiser');
		//location.reload();
	    }
	});
    }
}