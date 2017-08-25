import '@css/base.css';

setTimeout(function () {
    $('.alert').slideUp(500, function () {
        $(this).remove();
    });
}, 5000)