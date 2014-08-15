/**
 * FichierJS du module Agenda
 * Permet la mise à jour de l'agenda à l'aide de requetes
 * AJAX
 * 
 * GH
 */
 
/**
 * Premier ajout du contenu
 */
 
$( document ).ready(function() {
	
	var date = new Date();
	refreshCalendar(date.getMonth()+1, date.getFullYear());
});


/**
 * La fonction refreshCalendar permet d'actualiser le calendrier
 * en fonction du mois et de l'année passé en paramètre
 */

function refreshCalendar(month, year) {
	
    
	$.ajax({
        
        url  : '/netBS/web/app_dev.php/interne/stamm/agenda/' + month + '/' + year,
        type : 'POST',
        data : '',
        dataType : 'json',
        success : function(data) {

            agendaSetButtons(month, year);
            agendaDaysInMonth(month, year);
            agendaDayOfWeek(month, year);
            agendaPutEvents(data, month, year);
            
        },
        error : function(data) {
			
			$('.ui-main-container').text(JSON.stringify(data));
        },
    });
    
    
}


/**
 * La méthode dayOfWeek s'occupe de placer les noms des jours de la semaine
 * à la bonne place
 */
function agendaDayOfWeek(month, year) {
	
	var date = new Date(year, month - 1, 1);
	var day  = date.getDay();
	
	for(i = 1; i < 8; i++) {
		
		if(day == 7) day = 0;
		
		var name = agendaGetDayName(day);
		$('#agenda-day-' + i).text(name);
		day++;
	}
}


/**
 * La méthode agendaSetButton permet d'actualiser les boutons de switch
 * entre les mois
 */
function agendaSetButtons(month, year) {
	
	var previousMonth	= (month === 1) ? 12 : month - 1,
		nextMonth		= (month === 12) ? 1 : month + 1,
		previousYear	= (month === 1) ? year - 1 : year,
		nextYear		= (month === 12) ? year + 1 : year;
	
	//bouton previous
	$('#agenda-previous-month').text(agendaIntToMonth(previousMonth));
	$('#agenda-previous-month').attr('onclick', 'refreshCalendar(' + previousMonth + ', ' + previousYear + ');');
	
	//bouton next
	$('#agenda-next-month').text(agendaIntToMonth(nextMonth));
	$('#agenda-next-month').attr('onclick', 'refreshCalendar(' + nextMonth + ', ' + nextYear + ');');
	
	//indicateur courant
	$('#agenda-current-month').text(agendaIntToMonth(month) + ' ' + year);
}




/**
 * La méthode daysInMonth s'occupe d'afficher ou cacher les cases de jours
 * de mois en trop.
 */
function agendaDaysInMonth(month, year) {
	

	//Première chose, on rétablit l'affichage par défaut des 4 dernières cellules
	for(j = 0; j < 4; j++) {
		
		var nb = 31 - j;
		$('#agenda' + nb).children().show();
	}
	
	
	//ensuite on fait disparaitre les cellules inutiles
	var nbr  = new Date(year, month, 0).getDate();
	var trop = 31 - nbr; //Les jours en trop par rapport à 31
	
	for(i = 0; i < trop; i++) {
		
		var nbx = 31 - i;
		$('#agenda' + nbx).children().hide();
	}
}



/**
 * La méthode putEvents récupère toutes les informations transmises par la base
 * de donnée, et les integre dans l'agenda
 */
function agendaPutEvents(data, month, year) {
	
	/**
	 * Première chose, on nettoie les éventuels events déjà présent
	 * depuis une page du calendrier précédente
	 */
	$('.agenda-event').remove();
	
	/**
	 * Ensuite on teste qu'il y ait bien du contenu à renvoyer, c'est-à-dire
	 * un retour SQL, sinon on fait rien
	 */
	
	var daysInMonth = new Date(year, month, 0).getDate();
	
	if(JSON.stringify(data.listeEvents) != '[]') {
		
		var nbr  = new Date(2014, 3, 0).getData;
		var data = data.listeEvents;
		
		/**
		 * On entre ensuite chaque evenement dans l'agenda au bon endroit
		 * à l'aide d'une grosse boucle
		 */
		
		jQuery.each(data, function(i, val) {
		
			/**
			 * On récupère la date de l'événement en supprimant toutes les
			 * données inutiles
			 */
			var debut = data[i].debut.date.substring(0,10);
				dDay  = debut.split('-');
				dTime = data[i].debut.date.substring(11,19);
				dTime = dTime.split(':');
			
			var fin   = data[i].fin.date.substring(0,10);
				fDay  = fin.split('-');
				fTime = data[i].fin.date.substring(11,19);
				fTime = fTime.split(':');
			
			/**
			 * Il faut vérifier si l'évènement commence durant le mois courant, ou si il a commencé
			 * un mois précédent.
			 */
			var manyMonth = (parseInt(fDay[1]) - parseInt(dDay[1])) + 1; //Nombre de mois sur lesquels s'étend l'event
			var thisMonth = (month == parseInt(dDay[1])) ? true : false; //Nous dit si l'evenement commence ce mois
			var duree,
				jDebut;
			
			//Evenement commence ce mois et s'étend sur le mois courant
			if(thisMonth && manyMonth == 1) {
				
				jDebut = parseInt(dDay[2]);
				duree  = parseInt(fDay[2]) - parseInt(dDay[2]) + 1;
			}
			
			//Evenement commence ce mois mais termine un mois plus tard
			else if(thisMonth && manyMonth > 1) {
				
				jDebut = parseInt(dDay[2]);
				duree  = daysInMonth - parseInt(dDay[2]) + 1;
			}
			
			//Evenement commence un mois plus tot mais fini ce mois
			else if(!thisMonth && manyMonth == 2) {
				
				jDebut = 1;
				duree  = parseInt(fDay[2]);
			}
			
			//Tout le mois est compris dans l'evenement
			else {
				
				jDebut = 1;
				duree  = daysInMonth;
			}
			

			//On génère la div event en lui passant un identifiant unique basé sur un nombre aléatoire
			var id  = Math.floor((Math.random() * 100000000) + 1);
			var div = '<div id="' + id + '" class="agenda-event event-categorie-'+ data[i].categorie + '">' + data[i].nom + '<span class="agenda-infobulle">'+ 'Du ' + dDay[2] + '/' + dDay[1] + ' à ' + dTime[0] + ':'+ dTime[1] + ' <br/> Au ' + fDay[2] + '/' + fDay[1] + ' à ' + fTime[0] + ':' + fTime[1] + '</span></div>';						
			
			//On l'insère dans le calendrier
			for(j = jDebut; j < (duree + jDebut); j++) {
				
				$(div).appendTo('#agenda-content-' + j);
			}

		});
		
		
	}

}


/**
 * la fonction intToMonth convertit un int en mois lettre
 */
 
function agendaIntToMonth(m) {
	
	var mois;
	
	switch(m)
	{
		case 1:
			mois = 'Janvier';
			break;
		case 2:
			mois = 'Fevrier';
			break;
		case 3:
			mois = 'Mars';
			break;
		case 4:
			mois = 'Avril';
			break;
		case 5:
			mois = 'Mai';
			break;
		case 6:
			mois = 'Juin';
			break;
		case 7:
			mois = 'Juillet';
			break;
		case 8:
			mois = 'Aout';
			break;
		case 9:
			mois = 'Septembre';
			break;
		case 10:
			mois = 'Octobre';
			break;
		case 11:
			mois = 'Novembre';
			break;
		case 12:
			mois = 'Décembre';
			break;
	}
	
	return mois;
}

/**
 * La fonction agendaGetDayName retourne le nom
 * du jour en toute lettre pour le numero passé
 */
 
function agendaGetDayName(id) {
	
	var jour;
	
	switch(id) {
		
		case 0:
			jour = 'Dimanche';
			break;
		case 1:
			jour = 'Lundi';
			break;
		case 2:
			jour = 'Mardi';
			break;
		case 3:
			jour = 'Mercredi';
			break;
		case 4:
			jour = 'Jeudi';
			break;
		case 5:
			jour = 'Vendredi';
			break;
		case 6:
			jour = 'Samedi';
			break;
	}
	
	return jour;
}