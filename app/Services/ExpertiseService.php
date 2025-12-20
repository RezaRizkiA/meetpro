<?php

namespace App\Services;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Skill;

class ExpertiseService
{
    // --- READ DATA (Untuk ditampilkan di Index) ---
    public function getAllData()
    {
        return [
            'categories' => Category::withCount('subCategories')->latest()->get(),

            // Sub Category butuh info Category parent-nya
            'sub_categories' => SubCategory::with('category')->withCount('skills')->latest()->get(),

            // Skill butuh info Sub Category parent-nya
            'skills' => Skill::with('subCategory.category')->latest()->paginate(15) // Pagination untuk skill karena bisa banyak
        ];
    }

    // --- CATEGORY ACTIONS ---
    public function createCategory($data)
    {
        return Category::create($data);
    }
    public function updateCategory($id, $data)
    {
        $item = Category::findOrFail($id);
        $item->update($data);
        return $item;
    }
    public function deleteCategory($id)
    {
        return Category::destroy($id);
    }

    // --- SUB CATEGORY ACTIONS ---
    public function createSubCategory($data)
    {
        return SubCategory::create($data);
    }
    public function updateSubCategory($id, $data)
    {
        $item = SubCategory::findOrFail($id);
        $item->update($data);
        return $item;
    }
    public function deleteSubCategory($id)
    {
        return SubCategory::destroy($id);
    }

    // --- SKILL ACTIONS ---
    public function createSkill($data)
    {
        return Skill::create($data);
    }
    public function updateSkill($id, $data)
    {
        $item = Skill::findOrFail($id);
        $item->update($data);
        return $item;
    }
    public function deleteSkill($id)
    {
        return Skill::destroy($id);
    }
}
