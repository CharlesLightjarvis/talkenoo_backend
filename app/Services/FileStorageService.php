<?php

namespace App\Services;

use App\Enums\ContentType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService
{
    /**
     * Stocke un fichier et retourne son chemin
     */
    public function storeFile(UploadedFile $file, ContentType $type): array
    {
        $fileName = $this->generateFileName($file);
        $path = $this->getStoragePath($type);
        
        // Stocke le fichier
        $filePath = $file->storeAs($path, $fileName, 'public');
        
        return [
            'url' => Storage::url($filePath),
            'path' => $filePath,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];
    }

    /**
     * Supprime un fichier
     */
    public function deleteFile(string $path): bool
    {
        if (Storage::exists($path)) {
            return Storage::delete($path);
        }
        return false;
    }

    /**
     * Génère un nom de fichier unique
     */
    protected function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Retourne le chemin de stockage en fonction du type
     */
    protected function getStoragePath(ContentType $type): string
    {
        return match($type) {
            ContentType::IMAGE => 'contenus/images',
            ContentType::VIDEO => 'contenus/videos',
            ContentType::AUDIO => 'contenus/audios',
            ContentType::PDF => 'contenus/pdfs',
            default => 'contenus/autres'
        };
    }

    /**
     * Vérifie si le fichier est valide pour le type de contenu
     */
    public function validateFile(UploadedFile $file, ContentType $type): bool
    {
        $mimeTypes = $this->getAllowedMimeTypes($type);
        return in_array($file->getMimeType(), $mimeTypes);
    }

    /**
     * Retourne les types MIME autorisés pour chaque type de contenu
     */
    protected function getAllowedMimeTypes(ContentType $type): array
    {
        return match($type) {
            ContentType::IMAGE => [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ],
            ContentType::VIDEO => [
                'video/mp4',
                'video/mpeg',
                'video/quicktime',
                'video/webm'
            ],
            ContentType::AUDIO => [
                'audio/mpeg',
                'audio/wav',
                'audio/ogg',
                'audio/mp3'
            ],
            ContentType::PDF => [
                'application/pdf'
            ],
            default => []
        };
    }
}
