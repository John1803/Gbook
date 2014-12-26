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
            //debugger;
            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: values,
                success: function(data) {
                    if (data['success'] === true) {
                        console.log(data);
                        $($table).find('tbody').html(data['html']);

                        $($form).trigger("reset");
                        $($form).find('div').removeClass('has-error');
                        $($form).find('span.help-block').remove();

                        $form.find('button:submit').attr('disabled', true);

                        var seconds = 60,
                            secondsLeft = 60,
                            $nextPostInfo = $(".next-post-info"),
                            $countdown = $("#countdown"),

                            timer = setInterval(function () {
                                secondsLeft = secondsLeft - 1;
                                // format countdown string + set tag value
                                $($nextPostInfo).show();
                                $($countdown).html(secondsLeft + "s");
                            }, 1000);

                        setTimeout(function() {
                            $form.find('button:submit').attr('disabled', false);
                            clearInterval(timer);
                            $($nextPostInfo).hide();
                        }, seconds * 1000);

                        $('#flash-messages').flashNotification('addSuccess', data['messages']['success']);

                    } else {

                        $($form).find('span.help-block').remove();
                        $($form).find('div').removeClass('has-error');

                        $.each(data.errors, function(key, value) {
                            var $input = $('#guestbookbundle_post_' + key),
                                $parentInput = $($input).parent();

                            $parentInput.addClass('has-error');

                            $('<span class="help-block">' +
                                '<ul class="list-unstyled">' +
                                    '<li>' +
                                        '<span class="glyphicon glyphicon-exclamation-sign"></span>' +
                                        value +
                                    '</li>' +
                                '</ul>' +
                            '</span>').appendTo($parentInput);
                        });
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
        })
    });
})(jQuery);
