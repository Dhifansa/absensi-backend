<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        DB::table('admins')->insert([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data Peserta
        $peserta = [
            ['nic' => '147', 'divisi' => 'Sekretaris'],
            ['nic' => '62',  'divisi' => 'Sekretaris'],
            ['nic' => '3',   'divisi' => 'Bendahara'],
            ['nic' => '76',  'divisi' => 'Bendahara'],
            ['nic' => '68',  'divisi' => 'ITC'],
            ['nic' => '107', 'divisi' => 'ITC'],
            ['nic' => '66',  'divisi' => 'ITC'],
            ['nic' => '64',  'divisi' => 'ITC'],
            ['nic' => '37',  'divisi' => 'ITC'],
            ['nic' => '131', 'divisi' => 'PR'],
            ['nic' => '6',   'divisi' => 'PR'],
            ['nic' => '63',  'divisi' => 'PR'],
            ['nic' => '13',  'divisi' => 'PR'],
            ['nic' => '73',  'divisi' => 'PR'],
            ['nic' => '33',  'divisi' => 'Kosinus'],
            ['nic' => '103', 'divisi' => 'Kosinus'],
            ['nic' => '120', 'divisi' => 'Kosinus'],
            ['nic' => '63',  'divisi' => 'Kosinus'],
            ['nic' => '142', 'divisi' => 'Kosinus'],
            ['nic' => '74',  'divisi' => 'Keamanan'],
            ['nic' => '44',  'divisi' => 'Keamanan'],
            ['nic' => '148', 'divisi' => 'Keamanan'],
            ['nic' => '53',  'divisi' => 'Keamanan'],
            ['nic' => '95',  'divisi' => 'Properti'],
            ['nic' => '125', 'divisi' => 'Properti'],
            ['nic' => '17',  'divisi' => 'Properti'],
            ['nic' => '9',   'divisi' => 'Properti'],
            ['nic' => '23',  'divisi' => 'Properti'],
            ['nic' => '71',  'divisi' => 'Properti'],
            ['nic' => '49',  'divisi' => 'Sponsor'],
            ['nic' => '98',  'divisi' => 'Sponsor'],
            ['nic' => '25',  'divisi' => 'Sponsor'],
            ['nic' => '83',  'divisi' => 'Sponsor'],
            ['nic' => '89',  'divisi' => 'Sponsor'],
            ['nic' => '67',  'divisi' => 'Acara'],
            ['nic' => '96',  'divisi' => 'Acara'],
            ['nic' => '102', 'divisi' => 'Acara'],
            ['nic' => '75',  'divisi' => 'Acara'],
            ['nic' => '118', 'divisi' => 'Acara'],
            ['nic' => '111', 'divisi' => 'Acara'],
            ['nic' => '140', 'divisi' => 'Acara'],
            ['nic' => '48',  'divisi' => 'Acara'],
            ['nic' => '126', 'divisi' => 'Acara'],
            ['nic' => '9',   'divisi' => 'Acara'],
            ['nic' => '119', 'divisi' => 'Acara'],
            ['nic' => '77',  'divisi' => 'Acara'],
            ['nic' => '97',  'divisi' => 'Acara'],
            ['nic' => '34',  'divisi' => 'Acara'],
            ['nic' => '10',  'divisi' => 'Acara'],
            ['nic' => '69',  'divisi' => 'Acara'],
            ['nic' => '10',  'divisi' => 'Ketupel'],
            ['nic' => '119', 'divisi' => 'Waketupel'],
        ];

        $now = now();
        $rows = array_map(fn($p) => array_merge($p, [
            'created_at' => $now,
            'updated_at' => $now,
        ]), $peserta);

        DB::table('peserta')->insert($rows);
    }
}
