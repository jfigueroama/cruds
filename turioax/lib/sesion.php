<?php
function mfdvar($name, $value, $eol, $boundary){
    return
        '--' . $boundary .$eol.
        'Content-Disposition: form-data; name="'.$name.'" '.$eol.$eol.
        $value.$eol;
}

/* Hace login en el isstema como si fuera un usuario
 *
 */
function cweb_login($destination, $user, $password, $domain){
    $eol = "\r\n";
    $boundary=md5(time());

    $head = '';
    $data = '';

    $data .= mfdvar('.cgifields', 'httpcompress', $eol, $boundary);
    $data .= mfdvar('.cgifields', 'autologin', $eol, $boundary);

    $data .= mfdvar('loginname', $user, $eol, $boundary);
    $data .= mfdvar('password', $password, $eol, $boundary);
    $data .= mfdvar('logindomain', $domain, $eol, $boundary);
    $data .= mfdvar('loginbutton', 'Acceder', $eol, $boundary);
    $data .= mfdvar('httpcompress', '1', $eol, $boundary);
    $data .= '--' . $boundary . '--';

    $content = $head . $data;

    $params = array('http' =>
        array( 'method' => 'POST',
            'header' => 'Content-Type: multipart/form-data; '.
                'boundary=' . $boundary.$eol.
                'Content-Length: '. strval(strlen($data)).$eol,
            'content' => $data));

    $ctx = stream_context_create($params);
    $response = file_get_contents(CA_CWEB_LOGIN, false, $ctx);

    $rlogout = file_get_contents(CA_CWEB_LOGOUT, false);
    // Busca la palabra "session" en la respuesta del openwebmail
    if (preg_match('/session/i', $response)){
        // TODO NO SIRVE!!!
        return true;
    }else{
        return false;
    }
}

function sshlogin($host, $user, $pass){
  $res = false;
  $connection = ssh2_connect($host, 22);
  
  if (ssh2_auth_password($connection, $user, $pass)) {
    $res = true;
  }

  return $res;
}

////////////////////////////////////////////


function ftplogin($host, $user, $pass){
    $conn = ftp_connect($host) or die("Couldn't connect to $host");
    $res = false;

    if (@ftp_login($conn, $user, $pass) ){
       $res = true;
     }
    ftp_close($conn);
    return $res;
    //throw new Exception('No se pudo conectar al servidor de sesi&oacute;n');
}

function login($user = "", $pass){
    $res = false;

    $datos = explode('@', $user);
    $us = $datos[0];
    //echo "$host|$datos|$pass";

    if (!CA_LOGIN_USE_FILE){
      switch (CA_LOGIN_USE){
      case 'ssh':
        $host = CA_SSH_HOST;
        if (sshlogin($host, $us, $pass))
          $res = true;
        break;

      case 'ftp':
        $host = CA_FTP_HOST;
        if (ftplogin($host, $us, $pass))
          $res = true;

        break;

      case 'cweb':
        $host = CA_CWEB_HOST;
        die("HACER LOGIN POR FTP o SSH");
        if (cweb_login(CA_CWEB_URL, $user, $pass, $host)){
          $res = true;
        }
        break;
      }
    }else{
        $json = file_get_contents(CA_LOGIN_FILE);
        $jusers = json_decode($json);
        foreach ($jusers as $juser){
            if ($user == $juser->correo){
                $res = true;
                break;
            }
        }
    }

    if ($res){
        if (count($datos)< 2){  // login corto sin @mixteco.utm.mx
            $user .= '@'.CA_LOGIN_DOMAIN;
        }

        $clase = null;
        $id    = null;
        $prs = Personal::find('all', array('conditions'=> array('correo = ?', $user)));
        if (count($prs) < 1){
            $prs = Profesor::find('all', array('conditions'=> array('correo=?', $user)));
            if (count($prs)> 0){
                $clase = Profesor;
                $pr = $prs[0];
                $id = $pr->id;
            }
        }else{
            $clase = Personal;
            $pr = $prs[0];
            $id = $pr->id;
        }


        current_user(array(
            'correo' => $user,
            'clase' => $clase,
            'id' => $id
        //    'cargos' => $cargos
        ));
    }
    return $res;
}

function logout(){
    unset($_SESSION['user']);
    unset($_SESSION['filters']);
//    unset($_SESSION['user_type']);
//    unset($_SESSION['correo']);
//    unset($_SESSION['user_name']);
}

function current_user($us = null){
    if (!$us){
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }else{
        $_SESSION['user'] = $us;
        return $us;
    }
}

function is_logged(){
    $res = false;
    if (current_user()){
        $res = true;
    }
    return $res;
}

////////////////////////////////////////////
/*
    function login($user){
        $_SESSION['uid'] = $user->id;
        return $user;
    }


    function logout(){
        unset($_SESSION['uid']);
    }
 */


function usuario_actual(){
    $us = null;
    if (is_logged()){
//        $us = Profesor::find($_SESSION['uid']);
    }
    return $us;
}


function is_admin(){
    $res = false;
    $us  = usuario_actual();
/*
    if ($us &&
        $us->admin &&
        $us->admin->rol === Admin::$ROL_ADMIN)
        $res = true;
*/
    return $res;
}

?>
