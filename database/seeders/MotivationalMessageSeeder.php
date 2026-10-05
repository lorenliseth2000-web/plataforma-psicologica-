<?php

namespace Database\Seeders;

use App\Models\MotivationalMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MotivationalMessageSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/motivational_phrases.json');

        if (!File::exists($jsonPath)) {
            $this->command?->error("No se encontró el archivo {$jsonPath}");
            return;
        }

        $phrases = json_decode(File::get($jsonPath), true);

        if (!is_array($phrases) || count($phrases) === 0) {
            $this->command?->error("El archivo JSON está vacío o es inválido");
            return;
        }

        // Desactivar temporalmente foreign keys para truncar limpiamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_message_log')->truncate();
        DB::table('motivational_messages')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();
        $batch = [];

        foreach ($phrases as $index => $phrase) {
            $batch[] = [
                'sort_order'   => $index + 1,
                'category'     => 'autocuidado',
                'message'      => trim($phrase),
                'context_tags' => null,
                'active'       => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];

            if (count($batch) >= 100) {
                DB::table('motivational_messages')->insert($batch);
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            DB::table('motivational_messages')->insert($batch);
        }

        $total = DB::table('motivational_messages')->count();
        $this->command?->info("Se insertaron {$total} frases motivacionales con éxito.");
    }
}
