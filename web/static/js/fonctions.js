/**
 * fonctions.js permet de lier jqueryUi avec
 * l'ensemble de l'interface, sans avoir à déclarer toujours les 
 * fonctions dans les pages.
 * 
 * fonctions.js comporte aussi les fonctions utiles sur l'ensemble des pages
 * du netBS
 */
 
 /**
  * ajax loader
  * a chaque exécution d'une requête ajax, on affiche le panel
  * loading
  */

var $ajaxPanel = $('#ajax-panel').hide();
$(document).ajaxStart(function () {
	
	$ajaxPanel.show();
})
.ajaxStop(function () {

	$ajaxPanel.hide();
});
 

/**
 * Fonctions d'UI
 * ##################################
 * Les fonctions d'UI sont des méthodes plus complètes et personnalisées qui 
 * performent des actions spécifiques sur la page
 * 
 * afficher/cacher les options datatables
 */
$('.datatables-options').click(function() {
	
	$(this).parent().parent().next().find('.dataTables_length').slideToggle(200);
	$(this).parent().parent().next().find('.dataTables_filter').slideToggle(200);
});

/**
 * afficher/cacher les sous-menus du menu principal
 */
$('#navBar > ul > li > a').click(function() {
			
	var ssmenu = $(this).next();
	
	if ($(ssmenu).hasClass('active')) {
		
		$(this).parent().removeClass('active');
		$(ssmenu).removeClass('active');
	}
	
	else {
		
		$(this).parent().addClass('active');
		$(ssmenu).addClass('active');
	}
		
});
 

/**
 * Affiche ou cache la barre d'options du widget, dans laquelle on peut mettre
 * n'importe quoi
 */
$('.widget-options-button').click(function() {
	
	$(this).parent().parent().next().find('.widget-options').slideToggle(200);
});
 

/**
 * Fonctions générales et widgets
 * #####################################
 * 
 * 
 * datepicker
 * .datepicker
 * .datepicker-naissance
 */

$('.datepicker').datepicker({ 
	
	dateFormat: "yy-mm-dd", 
	changeYear: true, 
	yearRange : "1990:" + new Date().getFullYear() 
});



/**
 * chosen
 */
$('.chosen').chosen({no_results_text: "Aucun résultat", width: "300px"});

$('.chosen-multiple').prop('multiple', true);
$('.chosen-multiple').chosen({no_results_text: "Aucun résultat", width: "300px"});

/**
 * datatables
 */
$('.datatable').dataTable();