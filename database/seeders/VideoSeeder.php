<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $videos = [
            [
                'title' => 'Belajar Laravel 11 | 1. Intro',
                'description' => 'WPU Video 1',
                'url' => 'https://youtu.be/T1TR-RGf2Pw?si=4WisHNOLRarevQ1b',
            ],
            [
                'title' => 'Belajar Laravel 11 | 2. Instalasi & Konfigurasi',
                'description' => 'WPU Video 2',
                'url' => 'https://youtu.be/nW60yGRoUrs?si=wvh6698XoUT5T0u7',
            ],
            [
                'title' => 'Belajar Laravel 11 | 3. Struktur Folder Laravel',
                'description' => 'WPU Video 3',
                'url' => 'https://youtu.be/x55ndgkD2QI?si=p7E9jCf1m5WrHZ2U',
            ],
        ];

        foreach ($videos as $video) {
            Video::create($video);
        }
    }
}
