<?php

namespace Tests\Unit\Domain\CourseImports;

use App\Domain\CourseImports\QuestionBankNormalizer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuestionBankNormalizerTest extends TestCase
{
    #[Test]
    public function it_keeps_only_complete_multiple_choice_questions(): void
    {
        $questions = [
            [
                'enunciado' => '<p>Qual é a alternativa correta?</p>',
                'answers' => [
                    ['resposta' => 'A', 'correta' => 0],
                    ['resposta' => '<strong>B</strong>', 'correta' => 1],
                ],
            ],
            ['enunciado' => '', 'answers' => [['resposta' => 'placeholder', 'correta' => 0]]],
            ['enunciado' => 'Sem gabarito', 'answers' => [
                ['resposta' => 'A', 'correta' => 0],
                ['resposta' => 'B', 'correta' => 0],
            ]],
        ];

        $normalized = (new QuestionBankNormalizer)->normalize($questions);

        $this->assertSame([[
            'title' => 'Qual é a alternativa correta?',
            'options' => ['A', 'B'],
            'correct' => ['B'],
        ]], $normalized);
    }

    #[Test]
    public function it_removes_duplicate_and_blank_options(): void
    {
        $normalized = (new QuestionBankNormalizer)->normalize([[
            'enunciado' => 'Questão válida',
            'answers' => [
                ['resposta' => 'Opção 1', 'correta' => 1],
                ['resposta' => 'Opção 1', 'correta' => 1],
                ['resposta' => ' ', 'correta' => 0],
                ['resposta' => 'Opção 2', 'correta' => 0],
            ],
        ]]);

        $this->assertSame(['Opção 1', 'Opção 2'], $normalized[0]['options']);
        $this->assertSame(['Opção 1'], $normalized[0]['correct']);
    }
}
