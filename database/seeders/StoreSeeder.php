<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Self‑Help', 'slug' => 'self-help', 'sort_order' => 1],
            ['name' => 'Business', 'slug' => 'business', 'sort_order' => 2],
            ['name' => 'Fiction', 'slug' => 'fiction', 'sort_order' => 3],
            ['name' => 'Technology', 'slug' => 'technology', 'sort_order' => 4],
            ['name' => 'Design', 'slug' => 'design', 'sort_order' => 5],
            ['name' => 'Health', 'slug' => 'health', 'sort_order' => 6],
            ['name' => 'Productivity', 'slug' => 'productivity', 'sort_order' => 7],
            ['name' => 'Mindfulness', 'slug' => 'mindfulness', 'sort_order' => 8],
        ])->map(function ($c) {
            return Category::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'name' => $c['name'],
                    'description' => null,
                    'sort_order' => $c['sort_order'],
                ]
            );
        });

        $books = [
            [
                'title' => 'The Warm Page',
                'author' => 'Leena Hart',
                'price_cents' => 899,
                'currency' => 'USD',
                'description' => "A calm, practical guide to building a daily reading habit.\n\nIncludes simple routines, cozy prompts, and a gentle plan for busy days.",
                'categories' => ['productivity', 'mindfulness', 'self-help'],
                'rating_avg' => 4.6,
                'reviews_count' => 128,
                'purchases_count' => 940,
            ],
            [
                'title' => 'Focused Sundays',
                'author' => 'M. K. Rowe',
                'price_cents' => 1099,
                'currency' => 'USD',
                'description' => "A short playbook for weekly planning that actually sticks.\n\nLight templates, warm tone, and real-world examples.",
                'categories' => ['productivity', 'business'],
                'rating_avg' => 4.4,
                'reviews_count' => 76,
                'purchases_count' => 520,
            ],
            [
                'title' => 'Pastel Interfaces',
                'author' => 'Nora Bloom',
                'price_cents' => 1599,
                'currency' => 'USD',
                'description' => "Design principles for soft UI systems that still feel modern.\n\nCards, typography, spacing, and accessible color recipes.",
                'categories' => ['design', 'technology'],
                'rating_avg' => 4.7,
                'reviews_count' => 212,
                'purchases_count' => 1200,
            ],
            [
                'title' => 'Tiny Habits, Big Calm',
                'author' => 'Aisha Noor',
                'price_cents' => 799,
                'currency' => 'USD',
                'description' => "Micro-habits you can start in under two minutes.\n\nA warm approach to consistency without burnout.",
                'categories' => ['self-help', 'health', 'mindfulness'],
                'rating_avg' => 4.3,
                'reviews_count' => 64,
                'purchases_count' => 430,
            ],
            [
                'title' => 'The Beige Library (Short Stories)',
                'author' => 'Samir Vale',
                'price_cents' => 1299,
                'currency' => 'USD',
                'description' => "A set of comforting short stories set in quiet cities and small bookstores.\n\nPerfect for evening reading.",
                'categories' => ['fiction'],
                'rating_avg' => 4.5,
                'reviews_count' => 91,
                'purchases_count' => 610,
            ],
            [
                'title' => 'Laravel for Readers',
                'author' => 'Devon Page',
                'price_cents' => 1999,
                'currency' => 'USD',
                'description' => "Build clean, reader-friendly web apps with Laravel.\n\nIncludes practical patterns, routing, models, and simple admin tools.",
                'categories' => ['technology', 'business'],
                'rating_avg' => 4.2,
                'reviews_count' => 33,
                'purchases_count' => 210,
            ],
            [
                'title' => 'Quiet Money',
                'author' => 'H. Rivera',
                'price_cents' => 1499,
                'currency' => 'USD',
                'description' => "A soft-spoken guide to budgeting, saving, and building financial confidence.\n\nNo shame, no hype—just clarity.",
                'categories' => ['business', 'self-help'],
                'rating_avg' => 4.1,
                'reviews_count' => 52,
                'purchases_count' => 340,
            ],
            [
                'title' => 'Breathing Space',
                'author' => 'Mina Ali',
                'price_cents' => 699,
                'currency' => 'USD',
                'description' => "A gentle introduction to mindfulness for people who can’t sit still.\n\nShort exercises, easy language, and day-to-day calm.",
                'categories' => ['mindfulness', 'health'],
                'rating_avg' => 4.8,
                'reviews_count' => 301,
                'purchases_count' => 1800,
            ],
        ];

        foreach ($books as $b) {
            $baseSlug = Str::slug($b['title']);
            $slug = $baseSlug;
            $i = 2;
            while (Book::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$i}";
                $i++;
            }

            $book = Book::updateOrCreate(
                ['title' => $b['title'], 'author' => $b['author']],
                [
                    'uuid' => (string) Str::uuid(),
                    'slug' => $slug,
                    'description' => $b['description'],
                    'price_cents' => $b['price_cents'],
                    'currency' => $b['currency'],
                    // You can replace these by uploading real PDFs in admin.
                    'pdf_path' => 'uploads/books/demo.pdf',
                    'preview_pdf_path' => null,
                    'cover_path' => null,
                    'is_active' => true,
                    'published_at' => now()->subDays(rand(1, 365)),
                    'rating_avg' => $b['rating_avg'],
                    'reviews_count' => $b['reviews_count'],
                    'purchases_count' => $b['purchases_count'],
                ]
            );

            $categoryIds = $categories
                ->filter(fn (Category $c) => in_array($c->slug, $b['categories'], true))
                ->pluck('id')
                ->all();

            $book->categories()->sync($categoryIds);
        }
    }
}

