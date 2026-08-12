<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosens = [
            'Asep Ridwan Lubis, S Mat., M.B.A',
            'Dea Aryandhana Mulyana Haris, S.AB., M.E., RIFA.',
            'Dr. Aas Nurasyiah, M.Si.',
            'Dr. Dra. Heraeni Tanuatmodjo, MM',
            'Dr. Fitranty Adirestuty, S.Pd., M.Si.',
            'Dr. Hilda Monoarfa, S.E., M.Si.',
            'Dr. Imas Purnamasari, M.M',
            'Dr. Juliana, M.E., Sy.',
            'Dr. Masharyono, S.Pd., M.Si.',
            'Dr. Neni Sri Wulandari, S.Pd., M.Si.',
            'Dr. Yana Rohmana, S.Pd., M.Si.',
            'Firmansyah, S.Pd., M.E.Sy.',
            'Mumuh Muhammad, S.E., M.A.',
            'Prof. Dr. A. Jajang W. Mahri, M.Si.',
            'Rumaisah Azizah Al-Adawiyah, S.E., M.Sc.',
            'Suci Aprilliani Utami, S.Pd., M.E., Sy.',
            'Syaiful Muhammad Irsyad, B.BA., M.Sc.',
            'Tia Yuliawati, S.Pd., M.M'
        ];

        foreach ($dosens as $namaDosen) {
            // Generate a simple email based on the first name
            $cleanName = preg_replace('/(Dr\.|Prof\.|M\.Si\.|S\.Pd\.|M\.E\.Sy\.|B\.B\.A\.|M\.Sc\.|,)/i', '', $namaDosen);
            $cleanName = trim(preg_replace('/\s+/', ' ', $cleanName));
            $firstName = strtolower(explode(' ', $cleanName)[0]);
            
            // Ensure email uniqueness
            $email = $firstName . rand(10, 99) . '@ieki.com';

            $user = \App\Models\User::create([
                'name' => $namaDosen,
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'dosen',
            ]);

            \App\Models\Dosen::create([
                'user_id' => $user->id,
                'nama' => $namaDosen,
            ]);
        }
    }
}
