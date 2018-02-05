$(document).ajaxSend(function (event, request, settings) {
    $('#indicator').removeClass('hidden');
});

$(document).ajaxComplete(function (event, request, settings) {
    $('#indicator').addClass('hidden');
});
$(document).ajaxError(function (event, request, settings) {
    $('#indicator').addClass('hidden');
});