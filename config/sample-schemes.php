<?php

/*
|--------------------------------------------------------------------------
| Sample certification schemes
|--------------------------------------------------------------------------
| There is no Skema model yet, so the public portal shows this list. It is SAMPLE
| content: generic occupation names, always shown with a visible "Contoh" label.
| The home hero (field tags) and the scheme list both read from here, so they
| cannot drift apart. Replace it with the real, licensed list from the CMS later.
*/

return [
    'schemes' => [
        ['name' => 'Teknisi Jaringan Komputer',           'field' => 'TIK'],
        ['name' => 'Pemrogram Junior',                    'field' => 'TIK'],
        ['name' => 'Administrasi Perkantoran',            'field' => 'Perkantoran'],
        ['name' => 'Asisten Administrasi',                'field' => 'Perkantoran'],
        ['name' => 'Pelaksana Lapangan Pekerjaan Gedung', 'field' => 'Konstruksi'],
        ['name' => 'Juru Gambar Konstruksi',              'field' => 'Konstruksi'],
        ['name' => 'Pramusaji',                           'field' => 'Perhotelan'],
        ['name' => 'Resepsionis Hotel',                   'field' => 'Perhotelan'],
    ],
];
