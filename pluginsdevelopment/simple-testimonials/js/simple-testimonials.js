
for (var item in owlc_opts) {
    if (!isNaN(owlc_opts[item])){
        owlc_opts[item] = parseInt(owlc_opts[item]);
    }
}
console.debug(owlc_opts);
owlc_opts['animateIn'] = 'fadeOut';
console.debug(owlc_opts);
jQuery('.owl-carousel.simple-testimonials').owlCarousel(owlc_opts);


jQuery(function(){

function normalizeHeights(items) {
    var heights = [];
    items.each(function () {
        heights.push(jQuery(this).outerHeight());
    });
    tallest = Math.max.apply(null, heights);
    items.each(function () {
        jQuery(this).css('min-height', tallest + 'px');
    });   
}

var $items = jQuery('.owl-carousel .item.testimonial .text');
normalizeHeights($items);

});