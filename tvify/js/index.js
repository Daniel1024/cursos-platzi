$(function () {
    function loadCSS(url) {
        var elem = document.createElement('link');
        elem.rel = 'stylesheet';
        elem.href = url;
        document.head.appendChild(elem);
    }
    function mostrar(argument) {
        console.log(argument);
    }
    loadCSS('https://fonts.googleapis.com/css?family=Roboto');

    var $tvShowsContainer = $('.tv-shows');

    function renderShows(shows) {
        mostrar($tvShowsContainer);
        $tvShowsContainer.hide().find('.loader').remove();
        //mostrar(shows);
        shows.forEach(function (show) {
            var article = template
                .replace(':name:', show.name)
                .replace(':img:', (show.image == null) ? 'http://placehold.it/210x295/000000/ffffff' : show.image.medium)
                .replace(':summary:', show.summary)
                .replace(':imgalt:', show.name + ' Logo');
            $tvShowsContainer.append($(article));
        });
        $tvShowsContainer.show('slow');
    }
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
            //alert('Se ha buscado: ' + $busqueda);
            $.ajax({
                url: 'http://api.tvmaze.com/search/shows',
                data: { q: $busqueda },
                success: function (res, textStatus, xhr) {
                    var shows = res.map(function (el) {
                        return el.show;
                    });
                    $tvShowsContainer.find('.tv-show').remove();
                    renderShows(shows);
                }
            });
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

    if (!localStorage.shows) {
        $.ajax('http://api.tvmaze.com/shows')
            .then(function (shows) {
                localStorage.shows = JSON.stringify(shows);
                renderShows(shows);
            });
    } else {
        renderShows(JSON.parse(localStorage.shows));
    }








})