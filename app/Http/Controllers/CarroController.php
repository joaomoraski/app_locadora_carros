<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarroCreateUpdateRequest;
use App\Http\Requests\CarroUpdateRequest;
use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')) { // verifica se existe o parametro de atributos_marca
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo; // se existe atribui a variavel
            // pega os modelos usando o with para pegar o id da marca e os atributos passados
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        } else {
            // se nao so pega tudo com todas as informacoes da marca
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if($request->has('filtro')) {
            $carroRepository->selectWithFilter($request->filtro);
        }

        if($request->has('atributos')) {
            // se tem oparametro atributos ele explode em , e seleciona apenas aqueles atributos
            $atributos = explode(',', $request->atributos);
            $carroRepository->selectFields($atributos);
        }

        return response()->json($carroRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CarroCreateUpdateRequest $request)
    {
        $validated = $request->validated();

        $carro = $this->carro->create([
            'modelo_id' => $validated['modelo_id'],
            'placa' => $validated['placa'],
            'disponivel' => $validated['disponivel'],
            'km' => $validated['km']
        ]);
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Carro $carro)
    {
        $carro = $this->carro->with('modelo')->find($carro->id);
        if ($carro === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }
        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CarroUpdateRequest $request, $id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);

        $validated = $request->validated();

        $carro->fill($validated);
        $carro->save();
        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Carro $carro)
    {
        $carro = $this->carro->find($carro->id);
        if ($carro === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        $carro->delete();
        return response()->json(['msg' => 'O carro foi removida com sucesso!'], 200);
    }
}
