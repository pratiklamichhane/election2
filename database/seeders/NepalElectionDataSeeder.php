<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\ElectionParties;
use App\Models\Leader;
use App\Models\Party;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class NepalElectionDataSeeder extends Seeder
{
    public function run(): void
    {
        $electionRows = [
            [
                'key' => 'local_2079',
                'name' => 'Sthaniya Tah Nirbachan 2079',
                'description' => 'Nationwide local level election across metropolitan, municipal, and rural municipalities.',
                'date' => '2022-05-13',
            ],
            [
                'key' => 'pratinidhi_2079',
                'name' => 'Pratinidhi Sabha Nirbachan 2079',
                'description' => 'Federal House of Representatives election for selecting members of Pratinidhi Sabha.',
                'date' => '2022-11-20',
            ],
            [
                'key' => 'pradesh_2079',
                'name' => 'Pradesh Sabha Nirbachan 2079',
                'description' => 'Provincial assembly election held alongside federal parliamentary election.',
                'date' => '2022-11-20',
            ],
            [
                'key' => 'rashtriya_2080',
                'name' => 'Rashtriya Sabha Anshik Nirbachan 2080',
                'description' => 'Partial National Assembly election for upper house representation.',
                'date' => '2024-01-25',
            ],
        ];

        $partyRows = [
            [
                'key' => 'nc',
                'name' => 'Nepali Congress',
                'description' => 'Democratic center-left party with long leadership in Nepal democratic movements.',
                'logo_source' => 'assets/images/user1.png',
            ],
            [
                'key' => 'uml',
                'name' => 'CPN-UML',
                'description' => 'Major left party emphasizing governance, federal delivery, and infrastructure growth.',
                'logo_source' => 'assets/images/user2.png',
            ],
            [
                'key' => 'maoist',
                'name' => 'CPN (Maoist Centre)',
                'description' => 'Left force focused on inclusion, social justice, and post-conflict political transformation.',
                'logo_source' => 'assets/images/user3.png',
            ],
            [
                'key' => 'rsp',
                'name' => 'Rastriya Swatantra Party',
                'description' => 'Reform-oriented newer party prioritizing accountability, transparency, and public service delivery.',
                'logo_source' => 'assets/images/user4.png',
            ],
            [
                'key' => 'jspn',
                'name' => 'Janata Samajbadi Party Nepal',
                'description' => 'Party with strong Madhesh representation advocating inclusion and constitutional reform.',
                'logo_source' => 'assets/images/user5.png',
            ],
            [
                'key' => 'rpp',
                'name' => 'Rastriya Prajatantra Party',
                'description' => 'Conservative party stressing nationalism, cultural identity, and governance stability.',
                'logo_source' => 'assets/images/user.png',
            ],
        ];

        $leadersRows = [
            ['name' => 'Sher Bahadur Deuba', 'party_key' => 'nc', 'election_key' => 'pratinidhi_2079', 'logo_source' => 'assets/images/user1.png'],
            ['name' => 'K P Sharma Oli', 'party_key' => 'uml', 'election_key' => 'pratinidhi_2079', 'logo_source' => 'assets/images/user2.png'],
            ['name' => 'Pushpa Kamal Dahal Prachanda', 'party_key' => 'maoist', 'election_key' => 'pratinidhi_2079', 'logo_source' => 'assets/images/user3.png'],
            ['name' => 'Rabi Lamichhane', 'party_key' => 'rsp', 'election_key' => 'pratinidhi_2079', 'logo_source' => 'assets/images/user4.png'],
            ['name' => 'Upendra Yadav', 'party_key' => 'jspn', 'election_key' => 'pratinidhi_2079', 'logo_source' => 'assets/images/user5.png'],
            ['name' => 'Rajendra Lingden', 'party_key' => 'rpp', 'election_key' => 'pratinidhi_2079', 'logo_source' => 'assets/images/user.png'],

            ['name' => 'Gagan Kumar Thapa', 'party_key' => 'nc', 'election_key' => 'pradesh_2079', 'logo_source' => 'assets/images/user1.png'],
            ['name' => 'Shankar Pokharel', 'party_key' => 'uml', 'election_key' => 'pradesh_2079', 'logo_source' => 'assets/images/user2.png'],
            ['name' => 'Barsha Man Pun Ananta', 'party_key' => 'maoist', 'election_key' => 'pradesh_2079', 'logo_source' => 'assets/images/user3.png'],
            ['name' => 'Mukul Dhakal', 'party_key' => 'rsp', 'election_key' => 'pradesh_2079', 'logo_source' => 'assets/images/user4.png'],
            ['name' => 'Raj Kishor Yadav', 'party_key' => 'jspn', 'election_key' => 'pradesh_2079', 'logo_source' => 'assets/images/user5.png'],
            ['name' => 'Buddhiman Tamang', 'party_key' => 'rpp', 'election_key' => 'pradesh_2079', 'logo_source' => 'assets/images/user.png'],

            ['name' => 'Prakash Man Singh', 'party_key' => 'nc', 'election_key' => 'local_2079', 'logo_source' => 'assets/images/user1.png'],
            ['name' => 'Keshav Sthapit', 'party_key' => 'uml', 'election_key' => 'local_2079', 'logo_source' => 'assets/images/user2.png'],
            ['name' => 'Hitraj Pandey', 'party_key' => 'maoist', 'election_key' => 'local_2079', 'logo_source' => 'assets/images/user3.png'],
            ['name' => 'Nishan Dhakal', 'party_key' => 'rsp', 'election_key' => 'local_2079', 'logo_source' => 'assets/images/user4.png'],
            ['name' => 'Ram Sahay Prasad Yadav', 'party_key' => 'jspn', 'election_key' => 'local_2079', 'logo_source' => 'assets/images/user5.png'],
            ['name' => 'Deepak Bohara', 'party_key' => 'rpp', 'election_key' => 'local_2079', 'logo_source' => 'assets/images/user.png'],
        ];

        $elections = [];
        foreach ($electionRows as $row) {
            $elections[$row['key']] = Election::updateOrCreate(
                ['name' => $row['name']],
                [
                    'description' => $row['description'],
                    'date' => $row['date'],
                ]
            );
        }

        $parties = [];
        foreach ($partyRows as $row) {
            $logoPath = $this->copyLogoToStorage($row['logo_source'], 'logos/party-' . $row['key'] . '.png');

            $parties[$row['key']] = Party::updateOrCreate(
                ['name' => $row['name']],
                [
                    'description' => $row['description'],
                    'logo' => $logoPath,
                ]
            );
        }

        foreach ($elections as $election) {
            foreach ($parties as $party) {
                ElectionParties::updateOrCreate(
                    [
                        'election_id' => $election->id,
                        'party_id' => $party->id,
                    ],
                    []
                );
            }
        }

        foreach ($leadersRows as $row) {
            $logoPath = $this->copyLogoToStorage(
                $row['logo_source'],
                'logos/leader-' . $this->slugify($row['name']) . '.png'
            );

            Leader::updateOrCreate(
                [
                    'name' => $row['name'],
                    'party_id' => $parties[$row['party_key']]->id,
                    'election_id' => $elections[$row['election_key']]->id,
                ],
                [
                    'logo' => $logoPath,
                ]
            );
        }
    }

    private function copyLogoToStorage(string $publicAssetPath, string $targetPath): string
    {
        $sourcePath = public_path($publicAssetPath);

        if (file_exists($sourcePath) && !Storage::disk('public')->exists($targetPath)) {
            Storage::disk('public')->put($targetPath, file_get_contents($sourcePath));
        }

        return $targetPath;
    }

    private function slugify(string $value): string
    {
        $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($value));
        return trim($slug ?? '', '-') ?: 'leader';
    }
}
