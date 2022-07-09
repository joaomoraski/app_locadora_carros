<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $locacoesRepository = new LocacaoRepository($this->locacao);

        if($request->has('atributos_cliente')) { // verifica se existe o parametro de atributos_marca
            $atributos_cliente = 'cliente:id,'.$request->atributos_cliente; // se existe atribui a variavel
            // pega os modelos usando o with para pegar o id da marca e os atributos passados
            $locacoesRepository->selectAtributosRegistrosRelacionados($atributos_cliente);
        } else {
            // se nao so pega tudo com todas as informacoes da marca
            $locacoesRepository->selectAtributosRegistrosRelacionados('cliente');
        }

        if($request->has('atributos_carro')) { // verifica se existe o parametro de atributos_marca
            $atributos_carro = 'carro:id,'.$request->atributos_carro; // se existe atribui a variavel
            // pega os modelos usando o with para pegar o id da marca e os atributos passados
            $locacoesRepository->selectAtributosRegistrosRelacionados($atributos_carro);
        } else {
            // se nao so pega tudo com todas as informacoes da marca
            $locacoesRepository->selectAtributosRegistrosRelacionados('carro');
        }

        if($request->has('filtro')) {
            $locacoesRepository->selectWithFilter($request->filtro);
        }

        if($request->has('atributos')) {
            // se tem oparametro atributos ele explode em , e seleciona apenas aqueles atributos
            $atributos = explode(',', $request->atributos);
            $locacoesRepository->selectFields($atributos);
        }

        return response()->json($locacoesRepository->getResult(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create($request->all());
        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Locacao $locacao)
    {
        $locacao = $this->locacao->with('carro')->with('cliente')->find($locacao->id);
        if ($locacao === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }
        return response()->json($locacao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);

        $locacao->fill($request->all());
        $locacao->save();
        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Locacao $locacao)
    {
        $locacao = $this->locacao->find($locacao->id);
        if ($locacao === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        $locacao->delete();
        return response()->json(['msg' => 'A locacao foi removida com sucesso!'], 200);
    }
}
