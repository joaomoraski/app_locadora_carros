<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarcaCreateUpdateRequest;
use App\Models\Marca;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MarcaController extends Controller
{

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(MarcaCreateUpdateRequest $request)
    {
        $validated = $request->validated();

        $marca = $this->marca->create($validated);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $marca = $this->marca->find($id);
        if ($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Integer
     * @return JsonResponse
     */
    public function update(MarcaCreateUpdateRequest $request, $id): JsonResponse
    {
        $marca = $this->marca->find($id);
        if ($marca === null) return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);

        $validated = $request->validated();
        $marca->update($validated);
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $marca = $this->marca->find($id);
        if ($marca === null){
            return response()->json(['erro' => 'Recurso pesquisado nao existe'], 404);
        }
        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
