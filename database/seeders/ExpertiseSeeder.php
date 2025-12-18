<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Skill;

class ExpertiseSeeder extends Seeder
{
    public function run(): void
    {
        // Data Hirarki (Sama persis dengan data lama Anda)
        $data = [
            'Business Management' => [
                'Strategic Management' => ['Strategic planning', 'Visioning & goal setting', 'Business model analysis', 'Risk management', 'Performance monitoring'],
                'Marketing & Branding' => ['Market analysis', 'Digital marketing', 'Brand positioning', 'Customer segmentation', 'Marketing campaign planning'],
                'Sales & Business Development' => ['Negotiation & closing', 'Prospecting & lead conversion', 'Sales funnel management', 'Relationship management', 'Key account handling'],
                'Financial & Accounting' => ['Financial reporting', 'Budgeting & forecasting', 'Cost control', 'Cash flow management', 'Financial statement analysis'],
                'Purchasing & Procurement' => ['Vendor management', 'Supply chain coordination', 'Contract negotiation', 'Purchase planning', 'Cost efficiency'],
                'Operations & Logistics' => ['Workflow optimization', 'Inventory control', 'Quality assurance', 'Production scheduling', 'Distribution planning'],
                'Human Resource' => ['Talent acquisition', 'Employee development', 'Performance appraisal', 'Industrial relations', 'Compensation & benefits'],
                'Leadership & Teamwork' => ['Team motivation', 'Conflict resolution', 'Delegation & supervision', 'Coaching & mentoring', 'Change management'],
                'Project Management' => ['Project scoping', 'Timeline & resource planning', 'Budget control', 'Risk & issue tracking', 'Agile/Scrum methodology'],
                'Customer Service' => ['Complaint handling', 'Service excellence', 'Customer journey mapping', 'CRM system usage', 'Empathy-based communication'],
                'Legal & Compliance' => ['Contract review', 'Regulatory compliance', 'Ethics & governance', 'Risk mitigation', 'Licensing & permits']
            ],
            'Psychological' => [
                'Psychological Fundamental' => ['Psychological model (CBT, Psychodynamic, Humanistic, dsb.)', 'Mental health', 'Code of ethics', 'Confidentiality', 'Social sensitivity', 'Trauma', 'Phobia', 'Disorder'],
                'Counseling' => ['Listening', 'Empathic', 'Questioning', 'Clarification', 'Summarization', 'Goal-setting'],
                'Assessment & Diagnosis' => ['Mental status', 'Psychological tests', 'Case formulation', 'Risk assessment (self-harm, suicide, abuse)', 'Differential diagnosis (for psychologists/psychiatrists)'],
                'Intervention' => ['Cognitive Behavioral Therapy (CBT)', 'Acceptance & Commitment Therapy (ACT)', 'Person-Centered Therapy (PCT)', 'Narrative therapy', 'Solution-Focused Brief Therapy (SFBT)', 'Psychoeducation'],
                'Specialized Therapy' => ['Hypnotherapy', 'Clinical Hypnotherapy', 'Neuro-Linguistic Programming (NLP)', 'Mindfulness-Based Cognitive Therapy (MBCT)', 'Eye Movement Desensitization and Reprocessing (EMDR)', 'Art therapy', 'Play therapy'],
                'Communication & Relational' => ['Building connection', 'Verbal communication', 'Non-verbal communication', 'Managing transference', 'Boundary setting', 'Feedback delivery']
            ]
        ];

        DB::transaction(function () use ($data) {

            // Loop Level 1: Categories
            foreach ($data as $categoryName => $subCategories) {

                // 1. Create/Find Category
                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($categoryName)], // Cek berdasarkan slug unik
                    [
                        'name' => $categoryName,
                        'icon' => null // Bisa diisi default jika mau
                    ]
                );

                // Loop Level 2: SubCategories
                foreach ($subCategories as $subName => $skills) {

                    // 2. Create/Find SubCategory (Linked to Category)
                    $subCategory = SubCategory::firstOrCreate(
                        [
                            'category_id' => $category->id,
                            'slug' => Str::slug($subName)
                        ],
                        [
                            'name' => $subName
                        ]
                    );

                    // Loop Level 3: Skills
                    foreach ($skills as $skillName) {

                        // 3. Create/Find Skill (Linked to SubCategory)
                        Skill::firstOrCreate(
                            [
                                'sub_category_id' => $subCategory->id,
                                'name' => $skillName
                            ]
                        );
                    }
                }
            }
        });
    }
}
