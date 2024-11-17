<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionCours extends Model
{
    use HasFactory;

    protected $table = 'sections_cours';

    protected $fillable = [
        'cours_id',
        'titre',
        'description',
        'ordre',
        'statut',
        'duree_estimee'
    ];

    protected $casts = [
        'cours_id' => 'integer',
        'ordre' => 'integer',
        'statut' => 'boolean',
        'duree_estimee' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'statut' => true,
        'duree_estimee' => 0
    ];

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function contenus(): HasMany
    {
        return $this->hasMany(ContenuSection::class, 'section_id')->orderBy('ordre');
    }
}
