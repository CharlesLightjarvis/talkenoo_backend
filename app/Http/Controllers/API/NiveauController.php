<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNiveauRequest;
use App\Http\Requests\UpdateNiveauRequest;
use App\Http\Resources\NiveauResource;
use App\Models\Niveau;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NiveauController extends Controller
{
    /**
     * Afficher la liste des niveaux.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Niveau::query();

        // Charger les cours si demandé
        if ($request->boolean('with_cours')) {
            $query->with(['cours' => function ($query) {
                $query->orderBy('ordre');
            }]);
        }

        $niveaux = $query->orderBy('ordre')->paginate($request->input('per_page', 15));

        return NiveauResource::collection($niveaux);
    }

    /**
     * Créer un nouveau niveau.
     */
    public function store(StoreNiveauRequest $request): JsonResponse
    {
        $niveau = Niveau::create($request->validated());

        return (new NiveauResource($niveau))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Afficher un niveau spécifique.
     */
    public function show(Request $request, Niveau $niveau): NiveauResource
    {
        if ($request->boolean('with_cours')) {
            $niveau->load(['cours' => function ($query) {
                $query->orderBy('ordre');
            }]);
        }

        return new NiveauResource($niveau);
    }

    /**
     * Mettre à jour un niveau.
     */
    public function update(UpdateNiveauRequest $request, Niveau $niveau): NiveauResource
    {
        $niveau->update($request->validated());

        return new NiveauResource($niveau);
    }

    /**
     * Supprimer un niveau.
     */
    public function destroy(Niveau $niveau): JsonResponse
    {
        // Vérifier si le niveau a des cours
        if ($niveau->cours()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer ce niveau car il contient des cours'
            ], 422);
        }

        $niveau->delete();

        return response()->json(null, 204);
    }

    /**
     * Réorganiser l'ordre des niveaux.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'niveaux' => ['required', 'array'],
            'niveaux.*.id' => ['required', 'exists:niveaux,id'],
            'niveaux.*.ordre' => ['required', 'integer', 'min:1']
        ]);

        foreach ($request->niveaux as $niveauData) {
            Niveau::where('id', $niveauData['id'])->update(['ordre' => $niveauData['ordre']]);
        }

        return response()->json(['message' => 'Ordre des niveaux mis à jour avec succès']);
    }
}
