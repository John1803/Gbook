(function($) {
    $(document).ready(function() {
        var $form = $('.create-post'),
            $table = $('.records_list');

        $($form).on('submit', function(event) {
            event.preventDefault();

            var values = {};

            $.each( $form.serializeArray(), function(i, field) {
                values[field.name] = field.value;
            });

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: values,
                success: function(data) {
                    if (data['success'] === true) {
                        clearFormMessages($form);

                        $($table).find('tbody').html(data['html']);
                        $($form).trigger("reset");
                        $form.find('button:submit').attr('disabled', true);

                        addMessage($($form).parent(), data['messages']['success'], 'success');
                        createTimerSend(60);
                    } else {
                        clearFormMessages($form);
                        if (data.errors) {
                            addErrorForm(data.errors);
                        }
                        if (data['messages'] && data['messages']['error']) {
                            addMessage($($form).parent(), data['messages']['error'], 'danger');
                        }
                    }
                }
            });
        });

        $($table).find('a').on('click', function(event) {
            event.preventDefault();

            var href = $(this).attr('href'),
                posting = $.post(href);

            posting.done(function( data ) {
                $($table).find('tbody').html(data['html']);
            });
        });


    });

    var clearFormMessages = function($form) {
        $('.container > div.alert').remove();

        $($form).find('span.help-block').remove();
        $($form).find('div').removeClass('has-error');
    };

    var addMessage = function($container, message, type) {
        var htmlClass =  '"alert alert-' + type + '"';
        $('<div class=' + htmlClass +'>' +
            '<button class="close" data-dismiss="alert" type="button">×</button>' +
            message +
        '</div>').prependTo($container);
    };

    var addErrorForm = function(errors) {
        $.each(errors, function(key, value) {
            var $input = $('#guestbookbundle_post_' + key),
                $parentInput = $($input).parent();

            $parentInput.addClass('has-error');

            $('<span class="help-block">' +
                '<ul class="list-unstyled">' +
                    '<li>' +
                        //'<span class="glyphicon glyphicon-exclamation-sign"></span>' +
                        value +
                    '</li>' +
                '</ul>' +
            '</span>').appendTo($parentInput);
        });
    };

    var createTimerSend = function(seconds) {
        var secondsLeft = seconds,
            $nextPostInfo = $(".next-post-info"),
            $countdown = $("#countdown"),

            timer = setInterval(function () {
                secondsLeft = secondsLeft - 1;
                // format countdown string + set tag value
                $($nextPostInfo).show();
                $($countdown).html(secondsLeft + "s");
            }, 1000);

        setTimeout(function() {
            $('button:submit').attr('disabled', false);
            clearInterval(timer);
            $($nextPostInfo).hide();
        }, seconds * 1000);
    }
})(jQuery);
