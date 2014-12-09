jQuery(document).ready(function() {
    $('#modal-facture-searchForm').modal('show');
});

function sendSearch()
{
    var data = $('#searchFactureForm').serialize();
    $.ajax({
        type: "POST",
        url: Routing.generate('interne_facture_search_form'),
        data: data,
        error: function(jqXHR, textStatus, errorThrown) { alert('erreur'); },
        success: function(htmlResponse) {

            $('#searchTable').dataTable().append(htmlResponse);



            $('#modal-facture-searchForm').modal('hide');

        }
    });

}