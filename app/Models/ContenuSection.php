<?php

namespace App\Models;

use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContenuSection extends Model
{
    use HasFactory;

    protected $table = 'contenus_sections';

    protected $fillable = [
        'section_id',
        'titre',
        'description',
        'contenu',
        'type_contenu',
        'url_contenu',
        'duree',
        'ordre',
        'statut'
    ];

    protected $casts = [
        'section_id' => 'integer',
        'type_contenu' => ContentType::class,
        'duree' => 'integer',
        'ordre' => 'integer',
        'statut' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'statut' => true,
        'duree' => 0
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(SectionCours::class, 'section_id');
    }
}
