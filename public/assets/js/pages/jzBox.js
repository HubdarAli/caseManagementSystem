var jzBoxActual = null;

$.jzBoxMessage = function (actual, last) {
    return '' + actual + ' / ' + last;
};

$(document).on('keydown', function (event) {

    var jzBox = $('#jzBox');

    if (jzBox.css('display') == 'block') {

        var jzBoxLink = $('.jzBoxLink');

        switch (event.keyCode) {
            case 27:
                jzBoxActual = null;
                jzBox.slideToggle('fast');
                break;

            case 37:
                $.jzBoxMove('prev', jzBoxLink);
                break;

            case 39:
                $.jzBoxMove('next', jzBoxLink);
                break;
        }
    }
});

$('.jzBoxLink').click(function (event) {

    event.stopPropagation();
    event.preventDefault();

    var images = $('.jzBoxLink');
    jzBoxActual = this;

    $('#jzBoxTargetImg')
        .css('display', 'none')
        .attr('src', this.getAttribute('href'));

    $('#jzBoxLoading').css('display', 'block');


    $('#jzBoxTargetImg').one('load', function () {
        $('#jzBoxLoading').css('display', 'none');
        $(this).slideToggle('slow');
    });


    var text = this.getAttribute('title');
    var display = 'block';

    if (text == null) {
        text = '';
        display = 'none';
    }

    $('#jzBoxTitle').text(text).css('display', display);


    $('#jzBox').slideToggle('fast');

    var actualId;

    $.each(images, function (index) {
        if (jzBoxActual === images[index]) {
            actualId = index + 1;
        }
    });


    if (images.length == 1) {
        $('#jzBoxMoreItems').css('display', 'none');
    }

    $('#jzBoxCounter').text($.jzBoxMessage(actualId, images.length));
});

$.jzBoxMove = function (direction, allImages) {

    direction = (direction == 'next') ? 'next' : 'prev';

    var actualId;

    $.each(allImages, function (index) {
        if (allImages[index] === jzBoxActual) {
            actualId = index;
        }
    });


    var iterator;

    if (direction == 'next') {

        iterator = actualId + 1;

        if (actualId == allImages.length - 1) {
            iterator = 0;
        }


    } else if (direction == 'prev') {

        iterator = actualId - 1;

        if (actualId == 0) {
            iterator = allImages.length - 1;
        }
    }

    var newImage = allImages[iterator];

    $('#jzBoxTargetImg')
        .css('display', 'none')
        .attr('src', newImage.getAttribute('href'));

    $('#jzBoxLoading').css('display', 'block');


    $('#jzBoxTargetImg').one('load', function () {
        $('#jzBoxLoading').css('display', 'none');
        $(this).css('display', 'inline-block');
    });


    var text = newImage.getAttribute('title');
    var display = 'block';

    if (text == null) {
        text = '';
        display = 'none';
    }

    $('#jzBoxTitle').text(text).css('display', display);

    $('#jzBoxCounter').text($.jzBoxMessage(iterator + 1, allImages.length));

    jzBoxActual = newImage;
};

$('#jzBoxNext, #jzBoxNextBig').click(function() {
    $.jzBoxMove('next', $('.jzBoxLink'));
});

$('#jzBoxPrev, #jzBoxPrevBig').click(function() {
    $.jzBoxMove('prev', $('.jzBoxLink'));
});

$('#jzBoxClose').click(function() {
    $('#jzBox').slideToggle('fast');
});