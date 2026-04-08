<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $fillable = [
        'uuid',
        'title',
        'slug',
        'author',
        'description',
        'price_cents',
        'currency',
        'cover_path',
        'pdf_path',
        'preview_pdf_path',
        'is_active',
        'published_at',
        'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'meta' => 'array',
        'price_cents' => 'integer',
        'purchases_count' => 'integer',
        'reviews_count' => 'integer',
        'rating_avg' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Book $book): void {
            $book->uuid = $book->uuid ?: (string) Str::uuid();
            $book->slug = $book->slug ?: static::uniqueSlug($book->title);
        });
    }

    private static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function libraryItems(): HasMany
    {
        return $this->hasMany(LibraryItem::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (! $this->cover_path) {
            return null;
        }
        return asset('storage/'.$this->cover_path);
    }

    public function getDisplayPriceAttribute(): string
    {
        $amount = $this->price_cents / 100;
        return strtoupper($this->currency).' '.number_format($amount, 2);
    }
}
