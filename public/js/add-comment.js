$(document).on('click', 'form#comment-form input[type="submit"]', function(evt){
    evt.preventDefault();

    var $form = $('form#comment-form');
    var data = $form.serializeArray();
    var jsonData = {};
    data.forEach(function(elm) {
        jsonData[elm.name] = elm.value;
    });

    console.log(jsonData);

    $('.error').children('span').html('');
    $.ajax({
        url: '/post/add-comment',
        data: jsonData,
        method: 'POST',
        success: function (data) {
            try {
                // data is JSON
                var json = JSON.parse(data);
                if (typeof json.response !== 'undefined') {
                    console.log(typeof json.response);
                    if (json.status === 'error') {
                        $('.error').children('span').html(json.response);
                    }
                }
            } catch (e) {
                $form[0].reset();
                // data is HTML
                if($('.comments .card').length === 0) {
                    $('.comments').html(data)
                } else {
                    $('.comments').prepend(data)
                }
            }
        },
        error: function (xhr) {
            console.log('Oops! Something went wrong...');
            console.log(xhr);
        }
    });
});