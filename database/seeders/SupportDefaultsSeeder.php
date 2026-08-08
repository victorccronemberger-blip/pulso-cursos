<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach ([
            ['title' => 'Novo', 'color' => '#2563EB', 'default_view' => 1],
            ['title' => 'Em atendimento', 'color' => '#D97706', 'default_view' => 0],
            ['title' => 'Aguardando aluno', 'color' => '#7C3AED', 'default_view' => 0],
            ['title' => 'Resolvido', 'color' => '#059669', 'default_view' => 0],
        ] as $status) {
            DB::table('ticket_status')->updateOrInsert(
                ['title' => $status['title']],
                $status + ['icon' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        foreach (['Normal', 'Alta', 'Urgente'] as $priority) {
            DB::table('ticket_priorities')->updateOrInsert(
                ['title' => $priority],
                ['status' => 1, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        foreach (['Acesso e login', 'Pagamentos', 'Cursos e conteúdos', 'Certificados', 'Problema técnico', 'Outros'] as $category) {
            DB::table('ticket_categories')->updateOrInsert(
                ['title' => $category],
                ['status' => 1, 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
