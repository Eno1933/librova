<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fiksi' => ['Novel', 'Cerpen', 'Fantasi', 'Sci-Fi'],
            'Non-Fiksi' => ['Biografi', 'Sejarah', 'Sains', 'Filsafat'],
            'Bisnis & Ekonomi' => ['Manajemen', 'Marketing', 'Keuangan'],
            'Teknologi' => ['Pemrograman', 'AI', 'Cybersecurity'],
            'Self-Development' => ['Motivasi', 'Produktivitas', 'Psikologi'],
            'Pendidikan' => ['Buku Teks', 'Referensi Akademik'],
            'Seni & Budaya' => ['Desain', 'Musik', 'Sastra'],
            'Anak & Remaja' => ['Buku Anak', 'Komik', 'YA Fiction'],
        ];

        foreach ($categories as $parent => $children) {
            $parentCategory = Category::create([
                'name' => $parent,
                'slug' => \Str::slug($parent),
                'description' => "Kategori {$parent}",
                'icon' => '📚',
            ]);

            foreach ($children as $child) {
                Category::create([
                    'name' => $child,
                    'slug' => \Str::slug($child),
                    'parent_id' => $parentCategory->id,
                    'description' => "Sub-kategori {$child} di bawah {$parent}",
                    'icon' => '📖',
                ]);
            }
        }
    }
}