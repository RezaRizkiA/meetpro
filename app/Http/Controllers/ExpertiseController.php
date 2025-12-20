<?php

namespace App\Http\Controllers;

use App\Services\ExpertiseService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpertiseController extends Controller
{
    protected $service;

    public function __construct(ExpertiseService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Ambil semua data (Categories, SubCats, Skills)
        $data = $this->service->getAllData();

        return Inertia::render('Administrator/Expertise/Index', [
            'categories' => $data['categories'],
            'subCategories' => $data['sub_categories'],
            'skills' => $data['skills'],
        ]);
    }

    // --- ACTIONS CATEGORY ---
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $this->service->createCategory($request->only('name', 'icon')); // icon opsional
        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $this->service->updateCategory($id, $request->only('name', 'icon'));
        return back()->with('success', 'Category updated.');
    }

    public function destroyCategory($id)
    {
        // Tips: Cek dulu apakah punya sub-category agar tidak error constraint
        $this->service->deleteCategory($id);
        return back()->with('success', 'Category deleted.');
    }

    // --- ACTIONS SUB CATEGORY ---
    public function storeSubCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);
        $this->service->createSubCategory($request->all());
        return back()->with('success', 'Sub-Category added.');
    }

    public function updateSubCategory(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255'
        ]);
        $this->service->updateSubCategory($id, $request->all());
        return back()->with('success', 'Sub-Category updated.');
    }

    public function destroySubCategory($id)
    {
        $this->service->deleteSubCategory($id);
        return back()->with('success', 'Sub-Category deleted.');
    }

    // --- ACTIONS SKILL ---
    public function storeSkill(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255'
        ]);
        $this->service->createSkill($request->all());
        return back()->with('success', 'Skill added.');
    }

    public function updateSkill(Request $request, $id)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255'
        ]);
        $this->service->updateSkill($id, $request->all());
        return back()->with('success', 'Skill updated.');
    }

    public function destroySkill($id)
    {
        $this->service->deleteSkill($id);
        return back()->with('success', 'Skill deleted.');
    }
}
