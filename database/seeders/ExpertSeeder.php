<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Expert;
use App\Models\Expertise;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $positions      = ['Software Engineer', 'Backend Developer', 'Frontend Developer', 'System Analyst', 'DevOps Engineer', 'Data Analyst', 'IT Consultant', 'Project Manager'];
        $companies      = ['Tokopedia', 'Gojek', 'Bukalapak', 'Traveloka', 'Shopee', 'OVO', 'Ruangguru', 'Zenius', 'Telkom Indonesia', 'Bank Mandiri', 'BRI'];
        $certifications = ['AWS Certified Developer', 'Google Cloud Engineer', 'Laravel Professional', 'Scrum Master', 'Microsoft Azure Associate', 'PMP', 'ITIL 4 Foundation'];
        $expertiseSummaries = [
            'Keahlian dalam pengembangan backend dan sistem API.',
            'Ahli dalam manajemen proyek dan DevOps.',
            'Berpengalaman dalam frontend development dan UI/UX.',
            'Spesialis dalam keamanan siber dan cloud architecture.',
            'Fokus pada data analytics dan machine learning.',
            'Ahli dalam pengembangan aplikasi Laravel dan RESTful API.',
            'Pakar dalam digital transformation dan integrasi sistem.',
        ];
        $types = ['Counselor', 'Psychologist', 'Coach', 'Trainer', 'Consultant'];

        // hanya leaf (level 3): parent_id ≠ null
        $leafExpertises = Expertise::whereNotNull('parent_id')->get();

        DB::transaction(function () use ($leafExpertises, $faker, $positions, $companies, $certifications, $expertiseSummaries, $types) {

            foreach ($leafExpertises as $leaf) {
                // buat ≥2 expert per leaf; ubah jika ingin >2
                $targetPerLeaf = 2;

                // hitung yang sudah ada (idempotent)
                $existingCount = Expert::whereJsonContains('expertise_id', (string) $leaf->id)->count();
                $need = max(0, $targetPerLeaf - $existingCount);
                if ($need === 0) continue;

                for ($i = 0; $i < $need; $i++) {
                    // email unik dan stabil per leaf + urutan
                    $email = Str::slug($leaf->name) . "-{$leaf->id}-" . Str::random(5) . '@seed.local';
                    if ($email === 'kastaraparama.idn@gmail.com') continue;

                    // User (idempotent by email)
                    $name = $faker->name();
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name'     => $name,
                            'phone'    => '628' . $faker->numerify('##########'),
                            'slug'     => Str::slug($name) . '-' . Str::lower(Str::random(6)),
                            'password' => Hash::make('password'),
                            'gender'   => $faker->randomElement(['L', 'P']),
                            'address'  => $faker->address,
                            'roles'    => ['user', 'expert'],
                        ]
                    );

                    // Expert (idempotent by user_id)
                    Expert::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'biography'    => '<p>' . $faker->paragraph(5) . '</p>',
                            'experiences'  => [
                                [
                                    'position'    => $faker->randomElement($positions),
                                    'company'     => $faker->randomElement($companies),
                                    'years'       => (string) $faker->numberBetween(2015, 2024),
                                    'description' => $faker->paragraph(3),
                                ],
                                [
                                    'position'    => 'IT Consultant',
                                    'company'     => 'Freelance',
                                    'years'       => (string) $faker->numberBetween(2017, 2025),
                                    'description' => $faker->paragraph(2),
                                ],
                            ],
                            'licenses'     => [
                                [
                                    'certification' => $faker->randomElement($certifications),
                                    'attachment'    => null,
                                ],
                            ],
                            'gallerys'     => [],
                            'socials'      => [],
                            'background'   => null,
                            'expertise'    => $faker->randomElement($expertiseSummaries),
                            // simpan leaf id sesuai struktur (array of string)
                            'expertise_id' => [(string) $leaf->id],
                            'price'        => $faker->numberBetween(5, 20) * 10000,
                            'type'         => $faker->randomElements($types, $faker->numberBetween(1, 3)),
                        ]
                    );

                    // Jika pakai pivot many-to-many (experts <-> expertises), aktifkan:
                    // $expert = Expert::where('user_id', $user->id)->first();
                    // $expert->expertises()->syncWithoutDetaching([$leaf->id]);
                }
            }
        });
    }
}
