<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model 
{

    public $timestamps = false;
    protected $fillable = [ 
        'descricao', 'cpf_cnpj', 'ativo', 'seq', 
        'codigo', 'denominacao', 'modalidade',
        'versao', 'ncm_id'
    ];
    protected $appends = ['links'];

    /**
     * Formata o valor de ativo, devolvido pelo banco, para 
     * retornar sempre em booleano
    */
    public function getAtivoAttribute(bool $ativo) 
    {
        return $ativo;
    }

    /**
     * Relacionamentos
     */
    public function ncm() 
    {
        return $this->hasOne('App/Ncm');
    }

    /**
     * Funções
     */ 
    public function getLinksAttribute() 
    {
        return [
            "ncm" => "/api/ncms/" . $this->ncm_id
        ];
    }

}