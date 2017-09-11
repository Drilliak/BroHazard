$(document).ready(function () {
    let $vote = $('#vote');

    function vote(action, value) {
        $.post(action)
            .done(function (data, textStatus, jqXHR) {
                $('#like-count').text(data.like_count);
                $('#dislike-count').text(data.dislike_count);
                $vote.removeClass('is-liked is-disliked');

                if (data.success) {
                    if (value === 1) {
                        $vote.addClass('is-liked');
                    } else {
                        $vote.addClass('is-disliked');
                    }
                }


                let dislikes = parseInt(data.dislike_count);
                let likes = parseInt(data.like_count);
                let percentage = (dislikes + likes) === 0 ? 100 : Math.round((likes / (likes + dislikes)) * 100);

                $('.vote-progress').css('width', percentage + '%');

            }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        });
    }


    $('.vote-like', $vote).click(function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        let action = form.attr('action');

        vote(action, 1);

    });
    $('.vote-dislike', $vote).click(function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        let action = form.attr('action');

        vote(action, -1);
    });

    let $comments = $('#comments');

    $('.btn-delete-comment', $comments).click(function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        let action = form.attr('action');

        $.post(action)
            .done(function (data, textStatus, jqXHR) {
                form.closest('.comment').remove();
                console.log(data);
                let $count = $('#count');
                $count.text(parseInt($count.text() - 1));
                let $plurial = $('#plurial');
                if (parseInt($count.text()) < 2){
                    $plurial.text('');
                } else {
                    $plurial.text('s');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            });

    });


});