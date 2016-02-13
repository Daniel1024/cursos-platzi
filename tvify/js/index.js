$(function () {
    loadCSS('https://fonts.googleapis.com/css?family=Roboto');
    //loadCSS('https://fonts.googleapis.com/css?family=Pacifico');
    /*
    var a = $('<a>', {
        href: 'http://platzi.com',
        target: '_blank',
        html: 'Ir a Platzi'
    })
    $('#app-body').append(a);
    */
    //var header = $('#app-header h1');
    //mostrar(header[0]);
    //$('#app-header').find('h1')

    /**
    *Submit search form
    */
    $('#app-body')
        .find('form')
        .submit(function(ev) {
            ev.preventDefault();
            var $busqueda = $(this)
                .find('input[type="text"]')
                .val();
            alert('Se ha buscado: ' + $busqueda);
    });

    var template = '<article class="tv-show">' +
                    '<div class="left">' +
                        '<img src=":img:" alt=":imgalt:">' +
                    '</div>' +
                    '<div class="left info">' +
                        '<h1>:name:</h1>' +
                        '<p>:summary:</p>' +
                    '</div>' +
                '</article>';

    $.ajax({
        url: 'http://api.tvmaze.com/shows',
        success: function (shows, textStatus, xhr) {
            var $tvShowsContainer = $('.tv-shows');
            shows.forEach(function (show) {
                var article = template
                    .replace(':name:', show.name)
                    .replace(':img:', show.image.medium)
                    .replace(':summary:', show.summary)
                    .replace(':imgalt:', show.name + ' Logo');

                $tvShowsContainer.append($(article));
            })
        }
    });







    function loadCSS(url) {
        var elem = document.createElement('link');
        elem.rel = 'stylesheet';
        elem.href = url;
        document.head.appendChild(elem);
    }
    function mostrar(argument) {
        console.log(argument);
    }
})