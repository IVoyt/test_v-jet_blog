$(document).ready(function(){
    var $flexSlider = $('.flexslider');

    fixFlexsliderHeight();
    function fixFlexsliderHeight() {
        // Set fixed height based on the tallest slide
        var sliderHeight = 0;
        $flexSlider.each(function(i, elm){
            $(this).find('.slides > li').each(function(){
                var slideHeight = $(this).height();
                if (sliderHeight < slideHeight) {
                    sliderHeight = slideHeight;
                }
            });
            $(this).find('.slide').css({'height' : sliderHeight});
            $(this).find('.slide .item > a').css({'height' : sliderHeight});
        });
        return sliderHeight;
    }

    $flexSlider.flexslider({
        animation: "slide",
        easing: "swing",
        direction: "vertical",
        slideshowSpeed: 5000,
        directionNav: false,
        touch: true,
    });

});