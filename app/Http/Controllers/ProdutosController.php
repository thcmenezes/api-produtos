<?php

namespace App\Http\Controllers;

use App\Produto;
use App\Ncm;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{

    public function index(int $produtoId = null)
    {
        /*
        * Caso na requisição seja informado o ID do produto
        * que será pesquisado, pesquisa somente ele.
        * Caso não informe, será retornado todos os produtos
        */ 
        if(!is_null($produtoId)) {
            $produtos = Produto::find($produtoId);
        } else {
            $produtos = Produto::paginate()->all();
        }
        
        if(is_null($produtos)) {
            return response()->json('', 204);
        }

        return $produtos;

    }

    public function store(Request $request)
    {

        $data = $request->all();    
        
        /**
         * Para efetuar a gravação do produto, é necessário,
         * primeiramente obter o ID da NCM a partir do  código
         *  informação pelo usuário
         */
        $ncmId = (new Ncm)->getIdByCodigo($data['ncm']);
        $data['ncm_id'] = $ncmId;

        $produto = Produto::create($data);

        return response()->json($produto, 201);

    }

    public function update(Request $request, int $produtoId)
    {

        $data = $request->all();
    
        $produto = Produto::find($produtoId);

        if(is_null($produto)) {
            return response()->json([
                'erro' => "Produto não consta em nossos registros."   
            ], 404);
        }

        /**
         * Para efetuar a autalização do produto, é necessário,
         * primeiramente obter o ID da NCM a partir do  código
         *  informação pelo usuário
         */
        if(isset($data['ncm'])) {
            $ncmId = (new Ncm)->getIdByCodigo($data['ncm']);
            $dados['ncm_id'] = $ncmId;
        }
        
        $produto->fill($data)->save();

        return response()->json($produto, 201);        

    }

    public function destroy(int $produtoId) 
    {

        $qtdProdutosRemovidos = Produto::destroy($produtoId);
 
        if($qtdProdutosRemovidos === 0) {
            return response()->json([
                'erro' => 'Produto não consta em nossos registros.'
            ], 404);
        }

        return response()->json('', 204);

    }

}
