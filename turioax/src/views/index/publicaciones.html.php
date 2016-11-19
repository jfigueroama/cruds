<?php
if (!function_exists('formatear_publicacion')){
    function formatear_publicacion($pub){
        // Titulo
        $tex = '<b>'.h($pub->titulo).'.</b> ';

        // Autores
        $pautores = $pub->pups;
        $aautores = $pub->puas;
        $autores  = array();

        $nautores = array_merge($pautores, $aautores);
        usort($nautores, function($a, $b){
            return $a->posicion > $b->posicion;
        });

        foreach ($nautores as $nau){
            if ($nau->profesor_id){
                $autores[] = $nau->profesor->nombreCompleto();
            }else{
                $autores[] = $nau->alumno->nombreCompleto();
            }
        }

        $aut = implode($autores, ', ');
        if (!empty($pub->otros_autores))
            $aut .= ', '.$pub->otros_autores;
        unset($autores);
        unset($pautores);
        unset($aautores);
        unset($nautores);


        $tex .= '<i>'.h($aut).'.</i>';

        ///////////
        $otros = array();

        $otros[] = !empty($pub->editor)         ? 'Eds. '.h($pub->editor) : '';
        $otros[] = !empty($pub->jornal)         ? h($pub->journal) : '';
        $otros[] = !empty($pub->titulo_libro)   ? h($pub->titulo_libro) : '';
        $otros[] = !empty($pub->capitulo)       ? 'Cap&iacute;tulo '.h($pub->capitulo) : '';
        $otros[] = !empty($pub->edicion)        ? h($pub->edicion).' Ed.' : '';
        $otros[] = !empty($pub->institucion)    ? h($pub->institucion) : '';
        $otros[] = !empty($pub->volumen)        ? 'Vol. '.h($pub->volumen) : '';
        $otros[] = !empty($pub->numero)         ? 'Num. '.h($pub->numero) : '';
        $otros[] = !empty($pub->paginas)        ? 'Pag.  '.h($pub->paginas) : '';
        $otros[] = !empty($pub->editorial)      ? h($pub->editorial) : '';
        $otros[] = !empty($pub->isbn)           ? 'ISBN: '.h($pub->isbn) : '';
        $otros[] = !empty($pub->issn)           ? 'ISSN: '.h($pub->extra) : '';
        $otros[] = !empty($pub->doi)            ? 'DOI '.h($pub->extra) : '';
        $otros[] = !empty($pub->extra)          ? h($pub->extra) : '';
        $otros[] = !empty($pub->url)            ? '<a href=\''.$pub->url.'\'>'.h($pub->url).'</a>' : '';
        $otros[] = !empty($pub->estado)         ? '<i>'.h($pub->estado).'</i>' : '';

        $otros = array_filter($otros, function($ele){
            if (!empty($ele))
                return true;
            else
                return false;
        });
        $oo = implode($otros, '');

        if (count($otros) > 1){
            if (!empty($oo))
                $oo = ' '.implode($otros, ', ');
        }else if (count($otros) > 0){
            $oo = ' '.$otros[0];
        }

        unset($otros);

        $tex .= $oo;

        //////////////
        $tex    .= ' <b>'.$pub->anio.'</b>';


        return $tex;
    }
}
////////////////////////////////////////////////////////////////

if (count($publicaciones) > 0){
    echo '<ul>'; 
    foreach ($publicaciones as $pub){
        echo '<li>'.formatear_publicacion($pub).'</li>';
    }
    echo '</ul>';
}else{
    echo "<i>No hay publicaciones capturadas.</i>";
}
?>
