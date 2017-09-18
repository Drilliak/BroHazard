$(document).ready(function () {
    let $vote = $('#vote');
    let $comments = $('#comments');
    let editTemp = {};

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

    function deleteComment(action, form) {
        $.post(action)
            .done(function (data, textStatus, jqXHR) {
                form.closest('.comment').remove();
                console.log(data);
                let $count = $('#count');
                $count.text(parseInt($count.text() - 1));
                let $plurial = $('#plurial');
                if (parseInt($count.text()) < 2) {
                    $plurial.text('');
                } else {
                    $plurial.text('s');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.error(errorThrown);
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

    $comments.on('click', '.btn-delete-comment', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        let action = form.attr('action');
        deleteComment(action, form);
    });

    $comments.on('click', '.btn-comment-edit', function (e) {
        e.preventDefault();
        let $commentBody = $(this).closest('.comment-body');
        let $commentContent = $commentBody.find('.comment-content');
        let id = $commentBody.attr('id').split('-')[1];
        editTemp[id] = $commentBody.html();
        $('#buttons-comment').remove();
        let commentTextArea = `
            <div class="comment-edit-textarea" id="edit-comment-${id}">
            <textarea class="form-control">${$commentContent.text().trim()}</textarea>
            <div class="pull-right">
               <a href="" class="btn link-edit-comment link-edit-save">Enregistrer</a>
               <a href="" class="btn link-edit-comment link-edit-cancel">Annuler</a>
            </div>
            </div>
            `;
        $commentContent.replaceWith(commentTextArea);
    });

    $comments.on('click', '.link-edit-cancel', function (e) {
        e.preventDefault();
        let $commentBody = $(this).closest('.comment-body');
        let $commentTextArea = $commentBody.find('.comment-edit-textarea');
        let id = parseInt($commentTextArea.attr('id').split('-')[2]);
        $commentBody.html(editTemp[id]);
    });

    $comments.on('click', '.link-edit-save', function (e) {
        e.preventDefault();
        let $commentBody = $(this).closest('.comment-body');
        let $commentTextArea = $commentBody.find('.comment-edit-textarea');
        let id = parseInt($commentTextArea.attr('id').split('-')[2]);
        let $editTemp = $(editTemp[id]);
        let form = $editTemp.find('.edit-comment');
        let action = form.attr('action');
        let $commentEditTextArea = $(this).parent().parent();
        let text = $commentEditTextArea.find('textarea').val();

        $.post(
            action,
            {
                text: text
            }
        ).done(function (data, textStatus, jqXHR) {
            $editTemp.closest('.comment-content').text(text);
            $commentBody.html($editTemp);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error(errorThrown);
        });
    });

});