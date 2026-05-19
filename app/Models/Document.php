<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'content',
        'owner_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->uuid)) {
                $document->uuid = Str::uuid()->toString();
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version_number', 'desc');
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'document_collaborators')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function getLatestVersionNumberAttribute()
    {
        return $this->versions()->max('version_number') ?? 0;
    }
}
