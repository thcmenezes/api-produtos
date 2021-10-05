<?php

namespace App\Helpers;

use ZipArchive;
use \App\Helpers\PortalUnico;
use \App\Ncm;

class NcmsCapturar {

    public function capturar() {        
        
        $this->downloadJsonNcms();
        $ncms = $this->extrairNcms();
        $sucesso = $this->gravarNcms($ncms);

        return $sucesso;
        
    }

    /**
     * Com um certificado autorizado, acessa a API do Portal Único 
     * e faz a captura do JSON contendo os dados das NCMs atualizadas
     */
    public function downloadJsonNcms() {

        //SENHA DO CERTIFICADO: 69134821
        $senhaCertificado = "69134821";

        $portal = new PortalUnico(storage_path('certificado/Certificado.pem'), $senhaCertificado, 0);
        $portal->downloadNcms(); 

    }

    /**
     * Os dados da NCM vem do Portal Único vem em json zipado
     * Método para descompactar o JSON 
     */
    public function extrairNcms(string $path = null) {
        
        // Abre o arquivo zip que é baixado do Portal Único
        $zip = new ZipArchive;
        $zip->open((!is_null($path) ? $path : storage_path('app/ncms.zip')));
    
        // Descompacta o arquivo do zip (um json) e pega o nome dele
        $extrairNcms = $zip->extractTo(storage_path('app/'));
        $nomeArquivoJson = $zip->getNameIndex(0);
        
        // Caso a extração tenha sido feita com sucesso
        if($extrairNcms) {
            
            $jsonNcms = $this->ajustarExtracao($nomeArquivoJson);
            
            return $jsonNcms;

        } else {

            return false;

        }

    }

    /**
     * Método para extrair os dados das NCMs do arquivo JSON 
     */
    public function ajustarExtracao(string $nomeArquivoJson) {

        // Lê o conteúdo do json e converte para array
        $jsonNcms = file_get_contents(storage_path('app/' . $nomeArquivoJson));
        $jsonNcms = json_decode($jsonNcms, true);
        
        // Apaga o arquivo JSON
        unlink(storage_path('app/' . $nomeArquivoJson));

        return $jsonNcms;

    }

    /*
    * Grava as NCMs a partir dos dados do JSON
    */
    public function gravarNcms($jsonNcms) {

        $sucesso = true;

        // Itera o array para gravar os dados das NCMs
        foreach($jsonNcms as $ncms) {
    
            foreach($ncms as $ncm) {
                $codigoNcm = $ncm['codigoNcm'];
                
                $sucesso = $sucesso !== false
                && Ncm::firstOrCreate(['codigo' => $codigoNcm]);
            }

        }

        return $sucesso;

    }

}
