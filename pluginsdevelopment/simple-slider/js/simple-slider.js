for (var item in owlc_opts) {
    if (!isNaN(owlc_opts[item])){
        owlc_opts[item] = parseInt(owlc_opts[item]);
    }
}
jQuery('.owl-carousel.simple-slider').owlCarousel(owlc_opts);