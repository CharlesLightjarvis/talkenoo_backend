<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    use HasFactory;

    protected $table = 'cours';

    protected $fillable = [
        'niveau_id',
        'titre',
        'description',
        'ordre',
        'statut'
    ];

    protected $casts = [
        'niveau_id' => 'integer',
        'ordre' => 'integer',
        'statut' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'statut' => true
    ];

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(SectionCours::class, 'cours_id')->orderBy('ordre');
    }
}
