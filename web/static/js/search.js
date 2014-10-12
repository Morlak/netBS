$('input[name=search]').change(function () {
    $.ajax({

        url: Routing.generate('InterneSearch_search_members'),
        type: 'POST',
        data: {
            value: $('input[name=search]').value()
        },
        dataType: 'json',

        success: function (data) {

            data.each(function (i) {
                $('#membersTable tr:last').after('<tr><td>' + data.prenom + '</td><td>' + data.nom + '</td></tr>');
            })

        },
        error: function (data) {

            alert("Une erreur est survenue lors de la recherche de membres");
        }
    });
});