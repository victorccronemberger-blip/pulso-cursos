<?php

namespace App\Support;

final class QuizProgress
{
    public static function calculate(iterable $submissions, int $questionCount, int $retake, int $totalMark, int $passMark): array
    {
        $attempts = is_countable($submissions) ? count($submissions) : iterator_count($submissions);
        $bestScore = null;

        foreach ($submissions as $submission) {
            $correct = self::answerCount(self::value($submission, 'correct_answer'));
            $wrong = self::answerCount(self::value($submission, 'wrong_answer'));
            $answered = $questionCount > 0 ? $questionCount : $correct + $wrong;
            $score = $answered > 0 ? (int) round(($correct / $answered) * 100) : 0;
            $bestScore = $bestScore === null ? $score : max($bestScore, $score);
        }

        $passPercentage = $totalMark > 0 ? (int) round(($passMark / $totalMark) * 100) : 0;
        $remainingAttempts = max($retake - $attempts, 0);
        $status = 'not_started';

        if ($attempts > 0 && $bestScore >= $passPercentage) {
            $status = 'passed';
        } elseif ($attempts > 0 && $remainingAttempts > 0) {
            $status = 'in_progress';
        } elseif ($attempts > 0) {
            $status = 'finished';
        }

        return [
            'attempt_count' => $attempts,
            'remaining_attempts' => $remainingAttempts,
            'best_score' => $bestScore,
            'pass_percentage' => $passPercentage,
            'question_count' => $questionCount,
            'status_key' => $status,
        ];
    }

    private static function answerCount(mixed $value): int
    {
        $decoded = is_string($value) ? json_decode($value, true) : $value;
        return is_array($decoded) ? count($decoded) : 0;
    }

    private static function value(mixed $submission, string $key): mixed
    {
        return is_array($submission) ? ($submission[$key] ?? null) : ($submission->{$key} ?? null);
    }
}
