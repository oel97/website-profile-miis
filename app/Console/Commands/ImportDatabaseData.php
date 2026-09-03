<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportDatabaseData extends Command
{
    protected $signature = 'db:import-data';

    protected $description = 'Import CMS data from JSON export';

    public function handle()
    {
        $path = database_path('cms_export.json');

        if (!File::exists($path)) {
            $this->error('File cms_export.json tidak ditemukan');
            return;
        }

        $data = json_decode(File::get($path), true);

        if (!$data) {
            $this->error('Data JSON kosong atau format tidak valid');
            return;
        }

        // Matikan foreign key sementara
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF;');
        }


        /*
        |--------------------------------------------------------------------------
        | Urutan import
        |--------------------------------------------------------------------------
        | Child table masuk lebih dulu agar relasi aman
        |--------------------------------------------------------------------------
        */

        $order = [
            // dependent tables
            'posts',
            'gallery_photos',
            'post_categories',
            'gallery_albums',

            // main CMS tables
            'school_profiles',
            'site_settings',
            'homepage_sections',
            'hero_slides',
            'principal_messages',
            'staff',
            'featured_programs',
            'achievements',
            'agendas',
            'facilities',
            'contacts',
            'social_links',
            'pages',

            // PPDB
            'ppdb_requirements',
            'ppdb_steps',
            'ppdb_periods',

            // system
            'users',
        ];


        foreach ($order as $table) {

            if (!isset($data[$table])) {
                continue;
            }

            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->warn("Tabel {$table} tidak ditemukan");
                continue;
            }


            DB::table($table)->truncate();


            foreach ($data[$table] as $row) {
                DB::table($table)->insert($row);
            }


            $this->info(
                "Imported {$table}: " . count($data[$table])
            );
        }


        // Import tabel yang belum masuk urutan
        foreach ($data as $table => $rows) {

            if (in_array($table, $order)) {
                continue;
            }

            if (!DB::getSchemaBuilder()->hasTable($table)) {
                continue;
            }


            DB::table($table)->truncate();


            foreach ($rows as $row) {
                DB::table($table)->insert($row);
            }


            $this->info(
                "Imported {$table}: " . count($rows)
            );
        }


        // Aktifkan kembali foreign key

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=ON;');
        }


        $this->info('Import selesai');
    }
}