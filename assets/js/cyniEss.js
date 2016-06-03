var page_top = true;

window.onscroll = function() {
    if (document.body.scrollTop > 50 && page_top)
    {
        page_top = false;
        spinDial();
    }
    else if (document.body.scrollTop < 50 && !page_top)
    {
        page_top = true;
        resetDial();
    }
};

function spinDial() {
    $('#dial').animate({rotate: '180deg'}, 900);
}

function resetDial() {
    $('#dial').animate({rotate: '0deg'}, 900);
}

function toTheTop() {
    $('html, body').animate({ scrollTop: 0}, 'slow');
    page_top = true;
    resetDial();
}

$(window).resize(function() {
    didResize = true;
});
