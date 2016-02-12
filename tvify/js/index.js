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