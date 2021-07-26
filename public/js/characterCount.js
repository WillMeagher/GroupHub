updateCount();
$('.input').on("input", function() {
    updateCount();
});

function updateCount() {
    var characterCount = $('.input').val().length;
    $('#current-count').text(characterCount + ' / 1024');
    if (characterCount > 1024) {
        $('#current-count').addClass('text-danger');
    } else {
        $('#current-count').removeClass('text-danger');
    }
}