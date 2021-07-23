updateCount();
$('#description').on("input", function() {
    updateCount();
});

function updateCount() {
    var characterCount = $('#description').val().length;
    $('#current-count').text(characterCount + ' / 1024');
    $('#current-count').removeClass('text-danger');
    if (characterCount > 1024) {
        $('#current-count').addClass('text-danger');
    }
}