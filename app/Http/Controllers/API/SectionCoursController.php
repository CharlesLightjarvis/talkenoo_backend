<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionCoursRequest;
use App\Http\Requests\UpdateSectionCoursRequest;
use App\Http\Resources\SectionCoursResource;
use App\Models\SectionCours;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SectionCoursController extends Controller
{
    /**
     * Afficher la liste des sections.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = SectionCours::query()->with(['cours', 'contenus']);

        // Filtrer par cours si spécifié
        if ($request->has('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        // Filtrer par statut si spécifié
        if ($request->has('statut')) {
            $query->where('statut', $request->boolean('statut'));
        }

        $sections = $query->orderBy('ordre')->paginate($request->input('per_page', 15));

        return SectionCoursResource::collection($sections);
    }

    /**
     * Créer une nouvelle section.
     */
    public function store(StoreSectionCoursRequest $request): JsonResponse
    {
        $section = SectionCours::create($request->validated());
        $section->load(['cours', 'contenus']);

        return (new SectionCoursResource($section))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Afficher une section spécifique.
     */
    public function show(Request $request, $id): JsonResponse|SectionCoursResource
    {
        try {
            $section = SectionCours::with(['cours', 'contenus'])->findOrFail($id);
            return new SectionCoursResource($section);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Section non trouvée'
            ], 404);
        }
    }

    /**
     * Mettre à jour une section.
     */
    public function update(UpdateSectionCoursRequest $request, $id): JsonResponse|SectionCoursResource
    {
        try {
            $section = SectionCours::findOrFail($id);
            $section->update($request->validated());
            $section->load(['cours', 'contenus']);

            return new SectionCoursResource($section);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Section non trouvée'
            ], 404);
        }
    }

    /**
     * Supprimer une section.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $section = SectionCours::findOrFail($id);
            
            // Vérifier si la section a des contenus
            if ($section->contenus()->exists()) {
                return response()->json([
                    'message' => 'Impossible de supprimer cette section car elle contient des contenus'
                ], 422);
            }

            $section->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Section non trouvée'
            ], 404);
        }
    }

    /**
     * Réorganiser l'ordre des sections.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'sections' => ['required', 'array'],
            'sections.*.id' => ['required', 'exists:sections_cours,id'],
            'sections.*.ordre' => ['required', 'integer', 'min:1']
        ]);

        foreach ($request->sections as $sectionData) {
            SectionCours::where('id', $sectionData['id'])->update(['ordre' => $sectionData['ordre']]);
        }

        return response()->json(['message' => 'Ordre des sections mis à jour avec succès']);
    }
}
