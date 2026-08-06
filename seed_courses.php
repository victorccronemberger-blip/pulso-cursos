<?php
// Seed: categorias + cursos estilo Toro (certificações financeiras)
$pdo = new PDO(
    "mysql:host=212.85.6.130;port=3306;dbname=u291739043_pulsefire;charset=utf8mb4",
    "u291739043_pulsefire",
    "@Exbom512758",
    [PDO::ATTR_TIMEOUT => 15]
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$now = date('Y-m-d H:i:s');

$cursos = [
    [
        'cat' => 'CFP', 'title' => 'Preparatório CFP® Certificação de Planejador Financeiro',
        'slug' => 'preparatorio-cfp-certificacao-planejador-financeiro',
        'price' => 1497, 'promo' => 1197, 'nivel' => 'advanced',
        'desc' => 'O preparatório mais completo para a certificação CFP® (Certified Financial Planner). Aulas objetivas, simulados no estilo da prova oficial e plano de estudos estruturado.',
        'features' => ['Curso completo + simulados no formato oficial', 'Acesso por 12 meses', 'Suporte de tutores certificados'],
    ],
    [
        'cat' => 'CFA', 'title' => 'Preparatório CFA® Level 1 - Charterholder',
        'slug' => 'preparatorio-cfa-level-1-charterholder',
        'price' => 3890, 'promo' => 3190, 'nivel' => 'advanced',
        'desc' => 'Preparação completa para o CFA® Level 1: todos os tópicos do curriculum oficial, questões comentadas e simulados cronometrados.',
        'features' => ['Todo o curriculum oficial do Level 1', '2.000+ questões comentadas', 'Simulados cronometrados'],
    ],
    [
        'cat' => 'CPA', 'title' => 'CPA-20 Certificação ANBIMA de Profissionais de Investimento',
        'slug' => 'preparatorio-cpa-20-certificacao-anbima',
        'price' => 497, 'promo' => 347, 'nivel' => 'intermediate',
        'desc' => 'Aprovação garantida na CPA-20: teoria objetiva, mapa mental da prova e simulados ilimitados com questões no estilo ANBIMA.',
        'features' => ['Teoria objetiva focada na prova', 'Simulados ilimitados', 'Mapa mental da prova'],
    ],
    [
        'cat' => 'C-PRO', 'title' => 'Preparatório C-PRO Certificação Profissional de Agentes Autônomos',
        'slug' => 'preparatorio-c-pro-certificacao-agentes-autonomos',
        'price' => 997, 'promo' => 697, 'nivel' => 'intermediate',
        'desc' => 'A certificação obrigatória para Agentes Autônomos de Investimento (AAI). Conteúdo completo, simulados e aulas diretas ao ponto.',
        'features' => ['Conteúdo completo da grade C-PRO', 'Simulados no formato da prova', 'Material de apoio em PDF'],
    ],
    [
        'cat' => 'CFG', 'title' => 'Preparatório CFG Certificação de Fundamentos de Gestão de Risco',
        'slug' => 'preparatorio-cfg-fundamentos-gestao-risco',
        'price' => 2490, 'promo' => 1990, 'nivel' => 'advanced',
        'desc' => 'Domine os fundamentos de gestão de risco com o preparatório CFG: teoria aprofundada, casos práticos e simulados comentados.',
        'features' => ['Teoria aprofundada de gestão de risco', 'Casos práticos do mercado', 'Simulados comentados'],
    ],
    [
        'cat' => 'CNPI', 'title' => 'Preparatório CNPI Certificação Nacional do Profissional de Investimento',
        'slug' => 'preparatorio-cnpi-profissional-investimento',
        'price' => 697, 'promo' => 497, 'nivel' => 'beginner',
        'desc' => 'A trilha completa para a CNPI: análise fundamentalista e técnica, economia e simulados no formato da prova oficial.',
        'features' => ['Análise fundamentalista e técnica', 'Simulados no formato oficial', 'Acesso por 12 meses'],
    ],
    [
        'cat' => 'ANCORD', 'title' => 'Preparatório ANCORD Agentes Autônomos de Investimento',
        'slug' => 'preparatorio-ancord-agentes-autonomos',
        'price' => 897, 'promo' => 597, 'nivel' => 'intermediate',
        'desc' => 'O preparatório mais completo para a prova da ANCORD: todos os módulos exigidos, simulados ilimitados e plantão de dúvidas.',
        'features' => ['Todos os módulos da prova ANCORD', 'Simulados ilimitados', 'Plantão de dúvidas ao vivo'],
    ],
];

$catIds = [];

// Thumbnails SVG
$svgDir = __DIR__ . '/public/uploads/course-thumbnail';
if (!is_dir($svgDir)) {
    mkdir($svgDir, 0777, true);
}

foreach ($cursos as $i => $c) {
    // Categoria
    $cat = strtoupper($c['cat']);
    $stmt = $pdo->prepare('SELECT id FROM categories WHERE title = ?');
    $stmt->execute([$cat]);
    $catId = $stmt->fetchColumn();
    if (!$catId) {
        $pdo->prepare('INSERT INTO categories (parent_id, title, slug, sort, status, created_at, updated_at) VALUES (0, ?, ?, ?, 1, ?, ?)')
            ->execute([$cat, strtolower($cat), $i + 1, $now, $now]);
        $catId = $pdo->lastInsertId();
        echo "categoria criada: $cat (id=$catId)\n";
    } else {
        echo "categoria existe: $cat (id=$catId)\n";
    }
    $catIds[$cat] = $catId;

    // SVG thumbnail
    $svgFile = "uploads/course-thumbnail/{$c['slug']}.svg";
    $svgPath = __DIR__ . '/public/' . $svgFile;
    $label   = $cat === 'CFP' ? 'CFP\u00AE' : $cat;
    $sub     = 'Preparat\u00F3rio completo';
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="205" viewBox="0 0 400 205">'
        . '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
        . '<stop offset="0" stop-color="#000F9E"/><stop offset="1" stop-color="#5768FF"/></linearGradient></defs>'
        . '<rect width="400" height="205" fill="url(#g)"/>'
        . '<circle cx="340" cy="30" r="90" fill="#ffffff" opacity="0.08"/>'
        . '<circle cx="60" cy="200" r="70" fill="#ffffff" opacity="0.08"/>'
        . '<rect x="24" y="18" width="2" height="60" fill="#40C351"/>'
        . '<text x="34" y="52" font-family="Arial, sans-serif" font-size="30" font-weight="bold" fill="#ffffff">' . $label . '</text>'
        . '<text x="34" y="78" font-family="Arial, sans-serif" font-size="15" fill="#ffffff" opacity="0.9">' . $sub . '</text>'
        . '<text x="34" y="180" font-family="Arial, sans-serif" font-size="13" fill="#40C351">Curso Completo + Simulados</text>'
        . '</svg>';
    file_put_contents($svgPath, $svg);
    echo "svg criado: $svgFile\n";

    // Curso
    $slug = $c['slug'];
    $stmt = $pdo->prepare('SELECT id FROM courses WHERE slug = ?');
    $stmt->execute([$slug]);
    $courseId = $stmt->fetchColumn();
    if (!$courseId) {
        $pdo->prepare('INSERT INTO courses
            (title, slug, short_description, user_id, category_id, course_type, status, level, language, is_paid, is_best, price, discounted_price, discount_flag, thumbnail, instructor_ids, average_rating, created_at, updated_at)
            VALUES (?, ?, ?, 4, ?, ?, ?, ?, ?, 1, ?, ?, ?, 1, ?, ?, 0, ?, ?)')
            ->execute([
                $c['title'], $slug, $c['desc'], $catIds[$cat], 'general', 'active', $c['nivel'], 'portuguese',
                $c['price'], ($i < 3 ? 1 : 0), $c['promo'], $svgFile, json_encode([4]), $now, $now,
            ]);
        $courseId = $pdo->lastInsertId();
        echo "curso criado: {$c['title']} (id=$courseId)\n";
    } else {
        echo "curso existe: {$c['title']} (id=$courseId)\n";
    }

    // Features
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM course_features WHERE course_id = ?');
    $stmt->execute([$courseId]);
    if ($stmt->fetchColumn() == 0) {
        foreach ($c['features'] as $f) {
            $pdo->prepare('INSERT INTO course_features (course_id, title, created_at, updated_at) VALUES (?, ?, ?, ?)')
                ->execute([$courseId, $f, $now, $now]);
        }
        echo "  + " . count($c['features']) . " features\n";
    }
}

echo "FIM\n";
