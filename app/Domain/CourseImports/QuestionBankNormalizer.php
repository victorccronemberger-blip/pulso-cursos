<?php

namespace App\Domain\CourseImports;

class QuestionBankNormalizer
{
    /**
     * @return array<int, array{title: string, options: array<int, string>, correct: array<int, string>}>
     */
    public function normalize(array $questions): array
    {
        $valid = [];

        foreach ($questions as $question) {
            if (! is_array($question)) {
                continue;
            }

            $title = $this->plainText((string) ($question['enunciado'] ?? ''));
            $answers = is_array($question['answers'] ?? null) ? $question['answers'] : [];
            $options = [];
            $correct = [];

            foreach ($answers as $answer) {
                if (! is_array($answer)) {
                    continue;
                }

                $value = $this->plainText((string) ($answer['resposta'] ?? ''));
                if ($value === '') {
                    continue;
                }

                $options[] = $value;
                if ((int) ($answer['correta'] ?? 0) === 1) {
                    $correct[] = $value;
                }
            }

            $options = array_values(array_unique($options));
            $correct = array_values(array_unique(array_intersect($correct, $options)));
            if ($title === '' || count($options) < 2 || $correct === []) {
                continue;
            }

            $valid[] = compact('title', 'options', 'correct');
        }

        return $valid;
    }

    private function plainText(string $value): string
    {
        $value = preg_replace('/<\s*\/?(?:p|div|li|ul|ol|br|h[1-6]|tr|td|th)[^>]*>/iu', ' ', $value);

        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    }
}
