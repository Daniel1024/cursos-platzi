(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var loadCSS =  function(url) {
            var elem = document.createElement('link');
            elem.rel = 'stylesheet';
            elem.href = url;
            document.head.appendChild(elem);
        }
module.exports = loadCSS;
},{}],2:[function(require,module,exports){
var onScroll = function () {
    if (window.scrollY >= 234) {
        headerElem.classList.toggle('.header--light');
    } else {
        headerElem.classList.toggle('.header--light');
    };
}

module.exports = onScroll;
},{}],3:[function(require,module,exports){
(function() {
    var loadCSS = require('./lib/loadCSS');
    var onScroll = require('./lib/onScroll');

    document.addEventListener('DOMContentLoaded', onDOMLoad);
    var headerElem = document.querySelector('.header');
    headerElem.addEventListener('scroll', onScroll);

    function onDOMLoad () {
        var btnMenu = document.getElementById('btnMenu');
        var navbarMenu = document.getElementById('navbarMenu');

        btnMenu.addEventListener('click', onClickMenu);

        loadCSS('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css');
        loadCSS('https://fonts.googleapis.com/css?family=Lato|Montserrat:400,700');

        function onClickMenu () {
            navbarMenu.classList.toggle('header-menu-list--show');
        }
    }
}());
},{"./lib/loadCSS":1,"./lib/onScroll":2}]},{},[3]);
