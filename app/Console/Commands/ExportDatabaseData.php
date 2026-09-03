<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportDatabaseData extends Command
{
    protected $signature = 'db:export-data';

    protected $description = 'Export CMS data';

    public function handle()
    {
        $tables = [
            'school_profiles',
            'site_settings',
            'homepage_sections',
            'hero_slides',
            'principal_messages',
            'staff',
            'featured_programs',
            'post_categories',
            'posts',
            'achievements',
            'agendas',
            'gallery_albums',
            'gallery_photos',
            'facilities',
            'ppdb_periods',
            'ppdb_requirements',
            'ppdb_steps',
            'contacts',
            'social_links',
        ];

        $export = [];

        foreach ($tables as $table) {
            $export[$table] = DB::table($table)->get();
        }

        File::put(
            database_path('cms_export.json'),
            json_encode($export, JSON_PRETTY_PRINT)
        );

        $this->info('Export selesai');
    }
}