function login() {
    $("#login-button").click(function (event) {
        event.preventDefault();
        $('form').fadeOut(500);
        $('.wrapper').addClass('form-success');
        var width = 1;
        var id = setInterval(frame, 10);

        function frame() {
            if (width >= 100) {
                clearInterval(id);
            } else {
                width++;
                if (width < 50)
                    $('H1').html('Enviando consulta');
                if (width == 50)
                    $('H1').html('Autentificando');
                if (width == 90)
                    $('H1').html('Bienbenido <span class="ion-happy"></span>');
                if (width == 100)
                    window.location.assign("./solicitudes");
            }
        }
    });
}