/**
 * le verifikator va contr�ler les donn�es saisies dans les camps
 * de formulaire, et les analyser en fonction des r�gles qui ont �t�
 * plac�es dessus.
 *
 * pour faire fonctionner le verifikator, il faut que l'input
 * ait la classe .verifikator ainsi que les r�gles vis-�-vis du champ dans l'attribut
 * verifikator-regles
 * Les r�gles disponibles sont :
 * - nonull     : interdit un champ d'�tre nul
 * - int        : seulement un nombre
 * - noint      : aucun num�ro dans la chaine
 * - date       : verifie que la date soit au format YYYY-MM-DD
 * Pour avoir plusieurs r�gles, il suffit de les s�parer par un espace. Si le verifikator
 * n'est pas d'accord avec une valeur, il rend le background � lightred, et ajoute l'attribut
 * title avec une indication
 */

/**
 * le code suivant va appliquer le verifikator sur tous les champs d'un formulaire �quip� de
 * la classe .verifikaform, si le formulaire contient des erreurs, le script arr�te la soumission
 * et affiche les erreurs
 */
$('form.verifikaform').submit(function() {
    
    //On applique le verifikator sur chacun des champs
    var returned = true;
    
    $('.verifix').each(function( index ) {
        
        
        var verif = verifikator($(this));
        alert(verif);
        /*
        if (verifikator != true) {
            
            //On transforme le background du field
            $(this).css('background', 'lightred');
            
            var errors = '';
            
            //On chope les erreurs
            for(var i = 0; i < verifikator.length; i++) {
                
                errors += verifikator[i] + "\n";
            }
            
            //On ajoute le title
            $(this).attr("title", errors);
            returned = false;
        }
        */
        
    });
        
    return false;
});
/**
 * la m�thode verify v�rifie un champ
 * en lui appliquant les r�gles
 * return true si le champ est ok, un array contenant
 * les erreurs sinon
 */
function verifikator(obj) {
    
    var errors = [],
        wrong  = false,
        eCt    = 0;
    
    regles = $(obj).attr("verifik");
    text   = $(obj).val();
    
    //On applique chaque r�gle sur la valeur
    for(var i = 0; i < regles.length; i++) {
        
        if (regles[i] == 'nonull') {
            
            if (text == null || text == '' || text == ' ') {
                
                errors[eCt] = 'Ce champ ne peut �tre nul';
                eCt++;
                wrong = true;
            }
        }
        
        else if (regles[i] == 'int') {
            
            if (!$.isNumeric(text)) {
                
                errors[eCt] = 'Ce champ doit �tre un nombre';
                eCt++;
                wrong = true;
            }
        }
        
        else if (regles[i] == 'noint') {
            
            if ($.isNumeric(text)) {
                
                errors[eCt] = 'Ce champ ne doit pas �tre un nombre ou en contenir';
                eCt++;
                wrong = true;
            }
        }
        
        else if (regles[i] == 'date') {
            
            var date = text.split('-');
            
            if (date.length != 3 || !$.isNumeric(date[0]) || !$.isNumeric(date[1]) || !$.isNumeric(date[2]) || date[0].length != 4 || date[1].length != 2 || date[2].length != 2) {
                
                errors[eCt] = 'Ce champ doit �tre une date au format AAAA-MM-JJ';
                eCt++;
                wrong = true;
            }
        }
    }
    
    return (typeof errors !== 'undefined' && errors.length > 0) ? errors : true;
}
