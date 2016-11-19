
- Transformar el texto a su codificacion correcta y ver como hacerle a la hora
  de guardarlo para que respete esa codificacion.




Cambios a las librerias base:
- sa/vendor/vlucas/bulletphp/src/bullet/Request.php

    En la funcion url, para devolver la HTTP Request:

    // HTTP request                                                                       
    if (!isset($_GET['u']) || empty($_GET['u'])){                                         
        $requestUrl = $_SERVER['QUERY_STRING'];
    }else{
        $requestUrl = $this->get('u', '/');
    }

- sa/vendor/vlucas/bulletphp/src/bullet/Response.php

    En las variables globales:

    protected $_cache_disabled = false;

    Como metodo extra:

    /**
     * Disable the cache
     */
    public function disableCache()
    {
        if ($this->_cache_disabled == false){
            $this->header('Expires',         'Tue, 01 Jul 2001 06:00:00 GMT')
                ->header('Last-Modified',   gmdate("D, d M Y H:i:s") . ' GMT')
                ->header('Cache-Control',   'no-store, no-cache, must-revalidate')
                ->header('Cache-Control',   'post-check=0, pre-check=0')
                ->header('Pragma',          'no-cache');

            $this->_cache_disabled = true;
        }
        return $this;
    }
