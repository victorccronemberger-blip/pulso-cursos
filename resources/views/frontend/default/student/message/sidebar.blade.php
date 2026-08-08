<div class="msg-sidebar">

    {{-- Search --}}
    <div class="msg-search-wrap">
        <div class="search-box position-relative">
            <form action="" class="Esearch_entry">
                @csrf
                <div class="msg-search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass msg-search-icon"></i>
                    <input type="text"
                        name="user_email"
                        id="search_student"
                        class="form-control msg-search-input"
                        placeholder="Buscar por e-mail...">
                </div>
            </form>
            <ul class="msg-search-dropdown" id="msg-search-list">
                <li>Digite o e-mail do contato.</li>
            </ul>
        </div>
    </div>

    {{-- Unified Contact List --}}
    <div class="msg-contacts-wrap">
        @php
        $my_id = auth()->user()->id;

        $my_threads = App\Models\MessageThread::where('contact_one', $my_id)
        ->orWhere('contact_two', $my_id)
        ->orderBy('updated_at', 'desc')
        ->get();

        $threaded_user_ids = $my_threads->map(function($t) use ($my_id) {
        return $t->contact_one == $my_id ? $t->contact_two : $t->contact_one;
        })->toArray();

        $new_contacts = App\Models\User::whereIn('role', ['admin', 'instructor'])
        ->where('id', '!=', $my_id)
        ->whereNotIn('id', $threaded_user_ids)
        ->orderBy('name')
        ->get();
        @endphp

        @if ($my_threads->isEmpty() && $new_contacts->isEmpty())
        <div class="msg-empty">
            <i class="fa-regular fa-comment-dots"></i>
            <p>Nenhum contato disponível.</p>
        </div>
        @else

        {{-- Existing threads --}}
        @foreach ($my_threads as $thread)
        @php
        $last_message = $thread->messages()->orderBy('id', 'desc')->firstOrNew();
        $is_active = $thread->code == request()->query('inbox');
        $contact = $thread->user;
        $roleLabel = $contact->role === 'admin' ? 'Administrador' : 'Instrutor';
        @endphp

        <a href="{{ route('message', ['inbox' => $thread->code, 'instructor' => $contact->id]) }}"
            class="msg-contact {{ $is_active ? 'active' : '' }}">

            <div class="msg-contact-avatar-wrap">
                @if (!empty($contact->photo))
                    <img src="{{ get_image($contact->photo) }}" alt="{{ $contact->name }}" class="msg-contact-avatar">
                @else
                    <span class="msg-contact-avatar-fallback">{{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}</span>
                @endif
                <span class="msg-contact-online"></span>
            </div>

            <div class="msg-contact-body">
                <div class="msg-contact-top">
                    <h6 class="msg-contact-name">{{ $contact->name }}</h6>
                    <span class="msg-contact-role-badge {{ $contact->role }}">
                        {{ $roleLabel }}
                    </span>
                </div>
                <div class="msg-contact-bottom">
                    <p class="msg-contact-preview">
                        {{ $last_message->message ?? 'Nenhuma mensagem ainda.' }}
                    </p>
                    @if ($last_message->created_at)
                        <span class="msg-contact-time">{{ timeAgo($last_message->created_at) }}</span>
                    @endif
                </div>
            </div>

        </a>
        @endforeach

        {{-- New contacts (no thread yet) --}}
        @if ($new_contacts->isNotEmpty())
        @if ($my_threads->isNotEmpty())
        <div class="msg-contacts-divider">
            <span>Outros contatos</span>
        </div>
        @endif

        @foreach ($new_contacts as $contact)
        @php $roleLabel = $contact->role === 'admin' ? 'Administrador' : 'Instrutor'; @endphp
        <a href="{{ route('message.inbox', $contact->id) }}"
            class="msg-contact">

            <div class="msg-contact-avatar-wrap">
                @if (!empty($contact->photo))
                    <img src="{{ get_image($contact->photo) }}" alt="{{ $contact->name }}" class="msg-contact-avatar">
                @else
                    <span class="msg-contact-avatar-fallback">{{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}</span>
                @endif
            </div>

            <div class="msg-contact-body">
                <div class="msg-contact-top">
                    <h6 class="msg-contact-name">{{ $contact->name }}</h6>
                    <span class="msg-contact-role-badge {{ $contact->role }}">
                        {{ $roleLabel }}
                    </span>
                </div>
                <div class="msg-contact-bottom">
                    <p class="msg-contact-preview">Iniciar uma conversa</p>
                </div>
            </div>

        </a>
        @endforeach
        @endif

        @endif
    </div>

</div>
