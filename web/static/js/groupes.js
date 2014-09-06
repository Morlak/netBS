/**
 * Permet d'effectuer diverses opérations sur les groupes
 * en requête AJAX afin de les récupérer
 * Prend plusieurs paramètres obligatoires :
 * @param id l'id du groupe concerné
 * @param obj l'objet qui a transmis les données
 */
function retrieveAttributions(id, btn) {
	
	$(btn).css('background', 'yellow');
	
	//Première chose, récupérer les deux dates transmises
	var date1		= ($('#date1').val() === "") ? "empty" : $('#date1').val();
	var date2		= ($('#date2').val() === "") ? "empty" : $('#date2').val();
	var hierarchie		= $('#hierarchie').val();
	
	//Si l'id passée est null, ça veut dire qu'elle provient d'un select
	if(id === null)
		id = $('.groupeId').val();
	
	table = $("#attributionsTable").DataTable();

	table.clear().draw();
	
	//Une fois les deux dates récupérées, on exécute la requête ajax
	
	$.ajax({
        
        //url  : '/netBS/web/app_dev.php/interne/structure/ajax/attributions_from_groupes/' + id + '/' + hierarchie + '/' + date1 + '/' + date2,
	url : Routing.generate('InterneStructure_attributions_groupes_enfants', {id:id, hierarchie:hierarchie, date1:date1, date2:date2}),
        dataType : 'json',
        success : function(data) {

			$(btn).css('background', 'lightgreen');
			setTimeout( function(){
				$(btn).css('background','initial');
			},600);
            
            
            table = $("#attributionsTable").DataTable();


            //On nettoie la table des anciennes données
            table.clear();

            //Une fois toutes les données récupérées, on génère le tableau avec toutes les données
            for(i = 0; i < data.length; i++) {
				
				//On insère la table ou il faut
				table.row.add({
					
					"0":		'<input type="checkbox" id="membre_' + data[i].membreId + '"/>',
					"1":		data[i].membreNom,
					"2":		'<a href="/netBS/web/app_dev.php/interne/fichier/voir/membre/' + data[i].membreId + '">' + data[i].membrePrenom + '</a>',
					"3":		data[i].fonction,
					"4":		'<a href="/netBS/web/app_dev.php/interne/structure/groupe/' + data[i].groupeId + '">' + data[i].groupe + '</a></td>',
					"5":		data[i].dateDebut,
					"6":		data[i].dateFin
					
	
				}).draw();
				
            }
			
        },
        error : function(data) {
			
			$(btn).css('background', 'lightred');
			alert('erreur lors de la récupération');
        },
    });
    
    
}


/**
 * Permet de récupérer l'ensemble de la hierarchie, puis de l'afficher
 */
function retrieveHierarchie() {
	
	$.ajax({
		
		type: 'POST',
		//url: "/netBS/web/app_dev.php/interne/structure/ajax/full_hierarchie",
		url : Routing.generate('InterneStructure_full_hierarchie'),
		dataType : 'json',
		
		success : function(data) {
			
			//On crée un nouveau jstree
			$('#hierarchie').jstree('destroy');
			$("#hierarchie").bind(
				"select_node.jstree", function(event, data) {
					
					window.location.replace('/netBS/web/app_dev.php/interne/structure/groupe/' + data.node.li_attr.groupe_id);
				}
			);
			
			$('#hierarchie').jstree({
			    
				'core' : {
				
					'data' : data
				},
			});
				    
		},
		error : function(data) {
				
			alert("erreur lors de la récupération des données");
		},
	});
}

/**
 * Génère une liste html à partir des données transmises pour la hierarchie
 * complète
 */
 
function tree(data) {

	var html = "";
	
	if (typeof(data) == 'object') {
		
		if(!$.isEmptyObject(data)) {
			
			html += '<div class="hierarchie-groupe">';
			
			for (var i in data) {
				
						
					html += '<span class="hierarchie-element">';
					html += tree(data[i]);
					html += '</span>';
				
			}
			
			html += '</div>';
		}
		
	} else {
		
		
		var res = data.split('_');
		html += '<a href="/netBS/web/app_dev.php/interne/structure/groupe/' + res[0] + '">' + res[1] + '</a>';
	}
	
	return html;
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