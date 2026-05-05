<nav class="navbar w-100 card-dark fixed-bottom border-top border-dark backdrop-blur h-10">
    <div class="container d-flex justify-content-center">
        <ul class="nav nav-pills gap-1" id="memorial-tabs" role="tablist">

            @php
            $tabs = [
            ['id' => 'photo', 'icon' => 'fa-user', 'label' => 'Info'],
            ['id' => 'life', 'icon' => 'fa-file-lines', 'label' => 'Su Vida'],
            ['id' => 'messages', 'icon' => 'fa-message', 'label' => 'Mensajes'],
            ['id' => 'gallery', 'icon' => 'fa-image', 'label' => 'Galería'],
            ['id' => 'playlist', 'icon' => 'fa-music', 'label' => 'Playlist'],
            ['id' => 'partners', 'icon' => 'fa-handshake', 'label' => 'Anunciantes'],
            ];
            @endphp

            @foreach ($tabs as $tab)
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link btn-dark btn-lg {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $tab['id'] }}-btn"
                    data-bs-toggle="pill"
                    data-bs-target="#tab-{{ $tab['id'] }}"
                    type="button"
                    role="tab"
                    aria-controls="tab-{{ $tab['id'] }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    <i class="fa-solid {{ $tab['icon'] }}"></i>
                    <small class="nav-label d-none d-lg-block">{{ $tab['label'] }}</small>
                </button>
            </li>
            @endforeach

        </ul>
    </div>
</nav>