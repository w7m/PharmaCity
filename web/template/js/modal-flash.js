$(document).ready(function () {
    $('.modal-message-flash').modal('show');
    setTimeout(
        function () {
            $('.modal-message-flash').modal('hide');
        }, 4000);
});