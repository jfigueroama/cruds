<?php


function ctl_login(){
    if (isset($_POST['correo'])){
        $ok = login($_POST['correo'], $_POST['password']);
        if ($ok){
            redirect('/');
        }
    }

    return render('sesion'.DS.'login');
}

function ctl_logout(){
    logout();
    redirect('/sesion/login');
}


get('/sesion/login', 'ctl_login');
post('/sesion/login', 'ctl_login');

get('/sesion/logout', 'ctl_logout');

get('/sesion/vars', function(){
    header('Content-Type: text/plain');

    echo "SESSION:\n\n";
    print_r($_SESSION);

    echo "\n\nSERVER\n\n";
    print_r($_SERVER);

});

get('/sesion/clear', function(){
    header('Content-Type: text/plain');

    session_destroy();

    echo "SESSION:\n\n";
    print_r($_SESSION);

    echo "\n\nSERVER\n\n";
    print_r($_SERVER);

});

?>
