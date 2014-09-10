/**
 * Le système modificator permet de modifier par ajax le contenu d'une span
 * elle-même contenue dans une div de classe "data"
 *
 * le nom de la span fournit l'attribut à modifier
 */

$(document).ready(function() {
	
	//On rend les span éditables
	$('.editable').prop('contenteditable', true);
});

//Pour tous les toggle-editable, on leur ajoute un id aléatoire,
//Afin d'afficher un joli label en forme de toggle
$('.toggle-editable').each( function(i, obj) {
	
	var id = Math.floor((Math.random() * 1000000) + 1);
	
	$(obj).attr('id', id);
	$(obj).after('<label for="' + id + '" class="small-toggle"></label>');
});


//A chaque changement sur une span, on appelle le modifikator
var contentX;

$('.editable').focusin(function() {
	
	contentX = $(this).text();
});

$('.editable').focusout(function() {
	
	if($(this).text() !== contentX) {
		
		//Appel de l'ajax
		$(this).prop('contenteditable', false);
		$(this).addClass('editable-working');
		modifikator($(this).attr("name"), $(this).text(), $(this));
	}
});




//A chaque changement sur un toggle-editable également

$('.toggle-editable').change(function() {
	
	//Appel de l'ajax
	$(this).prop('disabled', true);
	modifikator($(this).attr("name"), $(this).prop('checked'), $(this));
});



function modifikator(entity, content, obj) {
	
	//Si le contenu est vide, on envoie un mot clé nullcontent
	if(content === '') content = 'NULL_CONTENT';
	
	$.ajax({
        
        url  : '/netBS/web/app_dev.php/interne/global/modifikator/' + urlencode(entity) + '/' + urlencode(content),
        type : 'POST',
        data : '',
        dataType : 'json',
        success : function(data) {

		$(obj).removeClass('editable-working');
		$(obj).addClass('editable-success');
		$(obj).prop('contenteditable', true);
		$(obj).removeAttr('disabled');
		
		setTimeout( function(){
			$(obj).removeClass('editable-success');
		},600);
	
	},
	error : function(data) {
	
		console.log(JSON.stringify(data));
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