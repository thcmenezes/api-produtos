<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ncm extends Model 
{

    protected $fillable = [ 
        'id', 'descricao', 'codigo'
    ];
    
    // Define que na resposta só serão exibidos dez NCMs por página
    protected $perPage = 10;

    /**
     * Relacionamentos
     */
    public function produtos() 
    {
        return $this->belongsToMany('App/Produto');
    }

    /**
     * Funções
     */ 
    
    /**
     * A partir do codigo da NCM informado, recuperar 
     * o ID correspondente à registro no banco de dados
     */
    public function getIdByCodigo(string $ncmCodigo) 
    {

        $ncm = $this::where('codigo', $ncmCodigo)->first();
        $ncmId = $ncm ? $ncm->id : 0;

        return $ncmId;

    }
    
}