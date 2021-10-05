<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected $classe;

    public function index(Request $request) {
        

        return $this->classe::paginate($request->page);

    }

    public function store(Request $request)
    {

        $data = $request->all();    
        
        return response()->json(
            $this->classe::create($data), 
            201
        );

    }

    public function update(Request $request, int $id)
    {

        $data = $request->all();
    
        $recurso = $this->classe::find($id);

        if(is_null($recurso)) {
            return response()->json([
                'erro' => "Recurso não consta em nossos registros."   
            ], 404);
        }

        $recurso->fill($data)->save();

        return response()->json($recurso, 201);        

    }

    public function destroy(int $id) 
    {

        $qtdRecursosRemovidos = $this->classe::destroy($id);
 
        if($qtdRecursosRemovidos === 0) {
            return response()->json([
                'erro' => 'Recurso não consta em nossos registros.'
            ], 404);
        }

        return response()->json('', 204);

    }

}
