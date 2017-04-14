$(document).ready(function(){
    setTimeout(function(){
        //All demo scripts go here
        Lobibox.notify('info', {
            img: _full_usr_photo,
            sound: true,
            position: 'top right',
            delay: 15000,
            showClass: 'fadeInDown',
            title: 'Bem-Vindo. ['+ _usr_login_status+']',
            msg: 'Esperamos que sua experiência seja a melhor possível.<br><i>Equipe de <b>Suporte</i></b>.'
        });
    }, 1000);
    
    $(document).on('submit', 'form', function(ev){
        ev.preventDefault();
    });
});