$('select').on('change', function () {
    let form = $(this).closest('form');
    let url = form.attr('action');
    let formSerialized = form.serialize();

    $.post(url, formSerialized, function (response) {
        console.log(response);
    }, 'JSON');
});

$('#form_search').on('input', function () {
    let form = $(this).closest('form');
    let url = form.attr('action');
    let formSerialized = form.serialize();
    $.post(url, formSerialized, function (response) {
        console.log(response);
    }, 'JSON');
});
