<?php

namespace App\Http\Controllers\API;

use App\Enums\ContentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContenuSectionRequest;
use App\Http\Requests\UpdateContenuSectionRequest;
use App\Http\Resources\ContenuSectionResource;
use App\Models\ContenuSection;
use App\Services\FileStorageService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ContenuSectionController extends Controller
{
    public function __construct(
        protected FileStorageService $fileStorage
    ) {}

    /**
     * Affiche la liste des contenus.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ContenuSection::query()->with('section');

        // Filtrer par section si spécifié
        if ($request->has('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        // Filtrer par type de contenu si spécifié
        if ($request->has('type_contenu')) {
            $query->where('type_contenu', $request->type_contenu);
        }

        // Filtrer par statut si spécifié
        if ($request->has('statut')) {
            $query->where('statut', $request->boolean('statut'));
        }

        $contenus = $query->orderBy('ordre')->paginate($request->input('per_page', 15));

        return ContenuSectionResource::collection($contenus);
    }

    /**
     * Crée un nouveau contenu avec upload de fichier si nécessaire.
     */
    public function store(StoreContenuSectionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Gestion du fichier média si présent
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $type = ContentType::from($validated['type_contenu']);

            // Valide le type de fichier
            if (!$this->fileStorage->validateFile($file, $type)) {
                return response()->json([
                    'message' => 'Type de fichier non valide pour ce type de contenu'
                ], 422);
            }

            try {
                $result = $this->fileStorage->storeFile($file, $type);
                $validated['url_contenu'] = $result['url'];
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Erreur lors de l\'upload du fichier',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Création du contenu
        $contenu = ContenuSection::create($validated);

        return response()->json([
            'message' => 'Contenu créé avec succès',
            'data' => new ContenuSectionResource($contenu)
        ], 201);
    }

    /**
     * Affiche un contenu spécifique.
     */
    public function show(Request $request, $id): JsonResponse|ContenuSectionResource
    {
        try {
            $contenu = ContenuSection::with('section')->findOrFail($id);
            return new ContenuSectionResource($contenu);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Contenu non trouvé'
            ], 404);
        }
    }

    /**
     * Met à jour un contenu existant.
     */
    public function update(UpdateContenuSectionRequest $request, $id): JsonResponse
    {
        try {
            $contenu = ContenuSection::findOrFail($id);
            $validated = $request->validated();

            // Gestion du fichier média si présent
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $type = ContentType::from($validated['type_contenu'] ?? $contenu->type_contenu);

                // Valide le type de fichier
                if (!$this->fileStorage->validateFile($file, $type)) {
                    return response()->json([
                        'message' => 'Type de fichier non valide pour ce type de contenu'
                    ], 422);
                }

                try {
                    // Supprime l'ancien fichier si existant
                    if ($contenu->url_contenu) {
                        $this->fileStorage->deleteFile(str_replace('/storage/', '', $contenu->url_contenu));
                    }

                    $result = $this->fileStorage->storeFile($file, $type);
                    $validated['url_contenu'] = $result['url'];
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Erreur lors de l\'upload du fichier',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }

            $contenu->update($validated);

            return response()->json([
                'message' => 'Contenu mis à jour avec succès',
                'data' => new ContenuSectionResource($contenu)
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Contenu non trouvé'
            ], 404);
        }
    }

    /**
     * Supprime un contenu.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $contenu = ContenuSection::findOrFail($id);
            // Supprime le fichier associé si existant
            if ($contenu->url_contenu) {
                $this->fileStorage->deleteFile(str_replace('/storage/', '', $contenu->url_contenu));
            }

            $contenu->delete();

            return response()->json([
                'message' => 'Contenu supprimé avec succès'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Contenu non trouvé'
            ], 404);
        }
    }

    /**
     * Réorganise l'ordre des contenus.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'contenus' => ['required', 'array'],
            'contenus.*.id' => ['required', 'exists:contenus_sections,id'],
            'contenus.*.ordre' => ['required', 'integer', 'min:1']
        ]);

        foreach ($request->contenus as $contenuData) {
            ContenuSection::where('id', $contenuData['id'])->update(['ordre' => $contenuData['ordre']]);
        }

        return response()->json([
            'message' => 'Ordre des contenus mis à jour avec succès'
        ]);
    }
}
