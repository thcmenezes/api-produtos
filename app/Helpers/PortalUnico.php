<?php

namespace App\Helpers;

class PortalUnico {

    /**
     *  Token gerado e fornecido pelo Portal Unico para uso em requisições
     * 
     * @var string
     */
    protected $_token;

    /**
     *  CSRF Token
     *  
     * @var string
     */
    protected $_csrfToken;

    /**
     * Caminho do certificado
     * 
     * * @var string
     */
    protected $_certificado;

    /**
     * Senha do certificado
     * 
     * * @var string
     */
    protected $_senha;

    /**
     * Host de acesso para o Portal Unico
     * 
     * * @var string
     */
    protected $_host;

    /**
     * Cookie para manutenção de autenticação com o portal
     * 
     * * @var string
     */
    protected $_cookie;

    public function __construct($certificado, $senha, $ambiente = 1) {        

        $this->_host = $ambiente == 1
        ? "https://portalunico.siscomex.gov.br"
        : "https://val.portalunico.siscomex.gov.br";

        $this->setCertificado($certificado);
        $this->setSenha($senha);

    }

    public function setCertificado(string $certificado){

        $this->_certificado = $certificado;

    }

    public function setSenha(string $senha){

        $this->_senha = $senha;

    }

    // public function setCookie() {
    //     if(!empty($this->_cookie))
    //         unlink($this->_cookie);
    //     $cookieNomeArquivo = uniqid(). ".txt";
    //     $this->_cookie = storage_path("cookies/" . $cookieNomeArquivo);
    // }

    public function login() {
        
        $url = $this->_host . "/portal/api/autenticar";
        $header = "role-type: impexp";
        $curlService = new \Ixudra\Curl\CurlService();

        $resposta = $curlService
            ->to($url)
            ->withHeader($header)
            ->withOption('SSLCERT', $this->_certificado)
            ->withOption('SSLCERTPASSWD', $this->_senha)
            ->withOption('SSL_VERIFYHOST', false)
            ->withOption('SSL_VERIFYPEER', false)
            ->withResponseHeaders()
            ->returnResponseObject()
            ->post();

        if($resposta->status != 200) {
            $mensagem = "Não foi possível se autenticar ao Portal Único. Tente novamente.";
            throw new \Exception($mensagem, $resposta->status);
        }

        $headerResposta = $resposta->headers;
        $this->_token = $headerResposta['Set-Token'];
        $this->_csrfToken = $headerResposta['X-CSRF-Token'];

    }

    public function downloadNcms() {

        // Url do webservice
		$url = $this->_host . "/cadatributos/api/ext/atributo-ncm/download/json";
        
		// Confere se está logado
		if (!$this->_token) {
			$this->login();
		}

		// Headers necessários para a requisição
		$headers = [
			"Authorization: {$this->_token}",
			"X-CSRF-Token: {$this->_csrfToken}",
		];

        // Corpo da requisição, que pede a data do arquivo de NCMs que deseja baixar
        $dataRequest = [
            'data' => "2021-09-22"
        ];

        $curlService = new \Ixudra\Curl\CurlService();

        // Requisição que baixa o arquivo do Portal Unico para uma pasta do sistema
        $resposta = $curlService
            ->to($url)
            ->withHeaders($headers)
            ->withData($dataRequest)
            ->download(storage_path('app/ncms.zip'));

        return $resposta;

    }

}