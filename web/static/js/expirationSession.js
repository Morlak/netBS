

/**
 * Méthode qui met la session en veille automatiquement après un certain laps
 * de termps écoulé
 */
var sessionTime 	= 20 * 60, //nombre de secondes que dure la session
	sessionCounter;

var sessionExpired 	= false,
	panelIsOn		= false;

//On met en place les différents events qui analysent l'activité
document.onclick = function() {
	sessionCounter = 0;
};
document.onmousemove = function() {
	sessionCounter = 0;
};
document.onkeypress = function() {
	sessionCounter = 0;
};

//Analyse de l'activité
window.setInterval(CheckIdleTime, 1000);

function CheckIdleTime() {

	sessionCounter++;

	if (sessionCounter >= sessionTime && !panelIsOn) {
		sessionExpired = true;
		sessionIsExpired();
	}
}

//On teste la valeur de la session
function sessionIsExpired() {

	//Le panneau de session n'est pas affiché, on le met
	if(!panelIsOn)
	{
		//La session a expiré, on met en place l'écran de session expirée
		setPanel();
		panelIsOn = true;
	}
}

//On met en place l'affichage du timer
window.setInterval(function() {

	//Seulement si la session est expirée
	if(sessionExpired) {

		var today	= new Date(),
			h		= today.getHours(),
			m		= checkTime(today.getMinutes()),
			s		= checkTime(today.getSeconds());

		$('#session-element').text(h + ':' + m + ':' + s);
	}
}, 1000);



function checkTime(i) {

	if (i<10)
		i = "0" + i;
	
	return i;
}

//Fonction appelée lorsque l'utilisateur souhaite se reconnecter
function sessionRecuperation() {
	
	//Petite animation pour changer l'apparence du bouton
	$('#session-button button').css('color', 'transparent');
	$('#session-button button').css('cursor', 'default');
	$('#session-button button').attr('onclick', ' ');
	
	setTimeout(function() {
		$('#session-button button').empty();
	}, 200);
	
	//hauteur
	setTimeout(function() {
		$('#session-button button').css('height', '10px');
		$('#session-button button').css('width', '0px');
	}, 400);
	
	//Barre de chargement
	setTimeout(function() {
		
		$('#session-button button').css('background', 'rgba(255,255,255,0.5)');
		$('#session-button button').animate({
			width: "150px"
		}, 5000);
		
	}, 600);
	
	//Une fois la reconnexion effectuée, on ré-affiche le poste de travail
	setTimeout(function() {
		
		sessionExpired = false;
		panelIsOn	   = false;
		$('#session-panel').fadeToggle(200);
	}, 5700);
	
	setTimeout(function() {
		$('#session-panel').remove();
	}, 5900);

}

/**
 * Méthode qui peut être appelée par l'utilisateur pour mettre sa session
 * en veille
 */
function modeVeille() {
	
	setPanel();
	sessionExpired = true;
	panelIsOn	   = true;
}

/**
 * Affiche le panneau de session expirée
 */
function setPanel() {
	
	var panel =   '<div id="session-panel">'
				+ '<div id="session-element"></div>'
				+ '<div id="session-infos">Votre session est en veille</div>'
				+ '<div id="session-button">'
				+ '<button onclick="sessionRecuperation();">reconnexion</button>'
				+ '</div>'
				+ '</div>';

	$(panel).appendTo('body');

	//On met en place l'affichage de l'heure

	$('#session-panel').css('display', 'none');
	$('#session-panel').fadeToggle();
}
 