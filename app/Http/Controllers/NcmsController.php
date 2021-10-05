<?php

namespace App\Http\Controllers;

use App\Helpers\NcmsCapturar;
use App\Ncm;

class NcmsController extends BaseController
{

    public function __construct()
    {
        $this->classe = Ncm::class;
    }

    /**
     * Faz o download das NCMs a partir da API do site 
     * do Portal Unico e grava as mesmas no banco de dados
     */
    public function capturarPortalUnico() {

        $portal = new \App\Helpers\PortalUnico(storage_path('certificado/Certificado.pem'), '69134821', 0);
        $portal->downloadNcms(); 

        // Download das NCMs da API do Portal Único
        $capturaNcm = new NcmsCapturar;   
        $sucesso = $capturaNcm->capturar();

        return $sucesso;

    }

}
