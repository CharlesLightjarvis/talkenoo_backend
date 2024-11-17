<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCoursRequest;
use App\Http\Requests\UpdateCoursRequest;
use App\Http\Resources\CoursResource;
use App\Models\Cours;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CoursController extends Controller
{
    /**
     * Afficher la liste des cours.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Cours::query()->with(['niveau', 'sections.contenus']);

        // Filtrer par niveau si spécifié
        if ($request->has('niveau_id')) {
            $query->where('niveau_id', $request->niveau_id);
        }

        // Filtrer par statut si spécifié
        if ($request->has('statut')) {
            $query->where('statut', $request->boolean('statut'));
        }

        $cours = $query->orderBy('ordre')->paginate($request->input('per_page', 15));

        return CoursResource::collection($cours);
    }

    /**
     * Créer un nouveau cours.
     */
    public function store(StoreCoursRequest $request): JsonResponse
    {
        $cours = Cours::create($request->validated());
        $cours->load(['niveau', 'sections.contenus']);

        return (new CoursResource($cours))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Afficher un cours spécifique.
     */
    public function show(Request $request, $id): JsonResponse|CoursResource
    {
        try {
            $cours = Cours::with(['niveau', 'sections.contenus'])->findOrFail($id);
            return new CoursResource($cours);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Cours non trouvé'
            ], 404);
        }
    }

    /**
     * Mettre à jour un cours.
     */
    public function update(UpdateCoursRequest $request, $id): JsonResponse|CoursResource
    {
        try {
            $cours = Cours::findOrFail($id);
            $cours->update($request->validated());
            $cours->load(['niveau', 'sections.contenus']);

            return new CoursResource($cours);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Cours non trouvé'
            ], 404);
        }
    }

    /**
     * Supprimer un cours.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $cours = Cours::findOrFail($id);
            $cours->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Cours non trouvé'
            ], 404);
        }
    }

    /**
     * Réorganiser l'ordre des cours.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'cours' => ['required', 'array'],
            'cours.*.id' => ['required', 'exists:cours,id'],
            'cours.*.ordre' => ['required', 'integer', 'min:1']
        ]);

        foreach ($request->cours as $coursData) {
            Cours::where('id', $coursData['id'])->update(['ordre' => $coursData['ordre']]);
        }

        return response()->json(['message' => 'Ordre des cours mis à jour avec succès']);
    }
}
