$(document).ready(function () {
    UpdateTabelas();
});

function UpdateTabelas() {
    setTimeout( function () {
        auto_refresh;
    }, 1800000);
}

var auto_refresh = setInterval(
    function ()
    {
        $('#paginaInicial').load('index.php');
    }, 240000);