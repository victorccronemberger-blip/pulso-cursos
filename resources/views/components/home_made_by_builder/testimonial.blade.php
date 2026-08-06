{{-- "O que falam os nossos alunos" — depoimentos estilo Toro --}}
@php
$allReviews = \App\Models\UserReview::orderBy('id', 'DESC')->get()->map(function($review) {
    $user = \App\Models\User::find($review->user_id);
    return [
        'id' => $review->id,
        'name' => $user ? $user->name : get_phrase('Aluno'),
        'role' => $user ? ucfirst($user->role) : 'Aluno',
        'message' => $review->review,
        'rating' => $review->rating ?? 5,
    ];
});
@endphp

<section class="toro-testimonials">
    <div class="container">
        <div class="toro-section-head">
            <h2>{{ get_phrase('O que falam os nossos alunos') }}</h2>
            <p>{{ get_phrase('Confira os depoimentos dos nossos alunos aprovados nas principais certificações do mercado financeiro!') }}</p>
        </div>

        @if ($allReviews->count() > 0)
        <div class="row g-4">
            @foreach ($allReviews as $review)
            <div class="col-lg-4 col-md-6">
                <div class="toro-testimonial-card">
                    <div class="toro-t-stars">
                        @for ($i = 1; $i <= 5; $i++)
                        <i class="fa fa-star {{ $i <= $review['rating'] ? '' : 'inactive' }}"></i>
                        @endfor
                    </div>
                    <p>"{{ $review['message'] }}"</p>
                    <div class="toro-t-author">
                        <div class="toro-t-avatar">{{ mb_substr($review['name'], 0, 1) }}</div>
                        <div>
                            <div class="toro-t-name">{{ $review['name'] }}</div>
                            <div class="toro-t-role">{{ $review['role'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="toro-testimonial-card">
                    <div class="toro-t-stars">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <p>"A metodologia é fantástica, o conteúdo é totalmente aderente à prova e o suporte pega a gente pela mão. Me senti preparado do início ao fim."</p>
                    <div class="toro-t-author">
                        <div class="toro-t-avatar">A</div>
                        <div>
                            <div class="toro-t-name">{{ get_phrase('Aluno aprovado') }}</div>
                            <div class="toro-t-role">CFP®</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="toro-testimonial-card">
                    <div class="toro-t-stars">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <p>"Estudei pelos simulados e pela apostila e passei de primeira. A dor passa, o certificado fica!"</p>
                    <div class="toro-t-author">
                        <div class="toro-t-avatar">B</div>
                        <div>
                            <div class="toro-t-name">{{ get_phrase('Aluno aprovado') }}</div>
                            <div class="toro-t-role">CPA-20</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="toro-testimonial-card">
                    <div class="toro-t-stars">
                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    </div>
                    <p>"Vocês sabem o que estão fazendo. Conteúdo extremamente aderente ao exame e professores que respondem rapidinho no grupo."</p>
                    <div class="toro-t-author">
                        <div class="toro-t-avatar">C</div>
                        <div>
                            <div class="toro-t-name">{{ get_phrase('Aluno aprovado') }}</div>
                            <div class="toro-t-role">CFA Level I</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
