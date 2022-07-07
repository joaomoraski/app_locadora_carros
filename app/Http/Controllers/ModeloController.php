<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if($request->has('atributos_marca')) { // verifica se existe o parametro de atributos_marca
            $atributos_marca = $request->atributos_marca; // se existe atribui a variavel
            // pega os modelos usando o with para pegar o id da marca e os atributos passados
            $modelos = $this->modelo->with("marca:id,{$atributos_marca}");
        } else {
            // se nao so pega tudo com todas as informacoes da marca
            $modelos = $this->modelo->with('marca');
        }

        if($request->has('filtro')) {
            // se tiver filtro no parametro  ?filtro=
            // explode em : para separar e passa para o where
            // ex: id:>:5
            // id > 5
            $filtros = explode(';', $request->filtro);
            foreach ($filtros as $key => $filter) {
                $fil = explode(':', $filter);
                $modelos = $modelos->where($fil[0], $fil[1], $fil[2]);
            }

        }

        if($request->has('atributos')) {
            // se tem oparametro atributos ele explor em , e seleciona apenas aqueles atributos

            $atributos = explode(',', $request->atributos);
            $modelos = $modelos->select($atributos)->get();
        } else {
            $modelos = $modelos->get();
        }

        return response()->json($modelos, 200);
        // all cria um objeto de consulta e executando ela pro get e dai retorna uma collection
        // se usa o get ele retorna a collection mas deixa modificar a consulta
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Modelo $modelo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Modelo $modelo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($modelo->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($modelo->rules());
        }

        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();

//        $modelo->update([
//            'marca_id' => $request->marca_id,
//            'nome' => $request->nome,
//            'imagem' => $imagem_urn,
//            'numero_portas' => $request->numero_portas,
//            'lugares' => $request->lugares,
//            'air_bag' => $request->air_bag,
//            'abs' => $request->abs
//        ]);
        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Modelo $modelo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
