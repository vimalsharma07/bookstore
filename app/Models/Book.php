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

    /** Recalculate cached rating_avg and reviews_count from approved reviews. */
    public function refreshReviewAggregates(): void
    {
        $stats = Review::query()
            ->where('book_id', $this->id)
            ->approved()
            ->selectRaw('COUNT(*) as c, AVG(rating) as a')
            ->first();

        $this->reviews_count = (int) ($stats->c ?? 0);
        $this->rating_avg = round((float) ($stats->a ?? 0), 2);
        $this->save();
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
        $path = ltrim((string) $this->cover_path, '/');
        if (str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }

    /** Full path to full book PDF (public uploads or legacy storage/app). */
    public function pdfAbsolutePath(): ?string
    {
        if (! $this->pdf_path) {
            return null;
        }
        if (str_starts_with($this->pdf_path, 'uploads/')) {
            return public_path($this->pdf_path);
        }

        return storage_path('app/'.$this->pdf_path);
    }

    /** Full path to preview PDF. */
    public function previewPdfAbsolutePath(): ?string
    {
        if (! $this->preview_pdf_path) {
            return null;
        }
        if (str_starts_with($this->preview_pdf_path, 'uploads/')) {
            return public_path($this->preview_pdf_path);
        }

        return public_path('storage/'.$this->preview_pdf_path);
    }

    public function getDisplayPriceAttribute(): string
    {
        $amount = $this->price_cents / 100;
        return strtoupper($this->currency).' '.number_format($amount, 2);
    }
}
