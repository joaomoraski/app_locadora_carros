<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if($request->has('filtro')) {
            $clienteRepository->selectWithFilter($request->filtro);
        }

        if($request->has('atributos')) {
            // se tem oparametro atributos ele explode em , e seleciona apenas aqueles atributos
            $atributos = explode(',', $request->atributos);
            $clienteRepository->selectFields($atributos);
        }

        return response()->json($clienteRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
//        $validated = $request->validated();
        $request->validate($this->cliente->rules());

        $cliente = $this->cliente->create([
            'nome' => $request->nome,
        ]);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Cliente $cliente)
    {
        $cliente = $this->cliente->with('modelo')->find($cliente->id);
        if ($cliente === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }
        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);

//        $validated = $request->validated();

        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Cliente $cliente)
    {
        $cliente = $this->cliente->find($cliente->id);
        if ($cliente === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        $cliente->delete();
        return response()->json(['msg' => 'O cliente foi removida com sucesso!'], 200);
    }
}
