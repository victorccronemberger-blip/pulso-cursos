<?php

namespace Tests\Unit\Support;

use App\Support\QuizProgress;
use PHPUnit\Framework\TestCase;

class QuizProgressTest extends TestCase
{
    public function test_it_marks_an_unattempted_quiz_as_not_started(): void
    {
        $progress = QuizProgress::calculate([], 18, 3, 18, 13);

        $this->assertSame('not_started', $progress['status_key']);
        $this->assertSame(3, $progress['remaining_attempts']);
        $this->assertNull($progress['best_score']);
    }

    public function test_it_keeps_the_best_score_and_marks_a_pass(): void
    {
        $progress = QuizProgress::calculate([
            ['correct_answer' => json_encode(range(1, 9)), 'wrong_answer' => json_encode(range(10, 18))],
            ['correct_answer' => json_encode(range(1, 15)), 'wrong_answer' => json_encode(range(16, 18))],
        ], 18, 3, 18, 13);

        $this->assertSame('passed', $progress['status_key']);
        $this->assertSame(83, $progress['best_score']);
        $this->assertSame(1, $progress['remaining_attempts']);
    }

    public function test_it_marks_an_exhausted_failed_quiz_as_finished(): void
    {
        $attempt = ['correct_answer' => json_encode(range(1, 6)), 'wrong_answer' => json_encode(range(7, 18))];
        $progress = QuizProgress::calculate([$attempt, $attempt], 18, 2, 18, 13);

        $this->assertSame('finished', $progress['status_key']);
        $this->assertSame(0, $progress['remaining_attempts']);
    }
}
