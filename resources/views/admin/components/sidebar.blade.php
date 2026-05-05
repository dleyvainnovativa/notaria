<div id="sidebar-wrapper" class="border border-dark card-dark sidebar safe-area">
    <div class="sidebar-heading text-primary">
        <img class="appLogo" src="{{asset('img/logo2.png')}}" width="160" alt="">
    </div>

    <div class="d-flex flex-column vh-100">
        <div class="list-group list-group-flush overflow-auto flex-grow-1 pb-5 mb-5">

            <div class="sidebar-section-label text-muted fw-bold display">Inicio</div>
            <a href="{{ route('admin.memorial.dashboard', $memorial_slug) }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorial.dashboard') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-house start-icon"></i>
                    Dashboard
                </small>
            </a>
            <a href="{{ route('admin.memorials') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorials') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-id-card  start-icon"></i>
                    Memoriales
                </small>
            </a>

            <div class="sidebar-section-label text-muted fw-bold">Memorial</div>
            @if ($memorial->canEditModule($user, 'info'))
            <a href="{{ route('admin.memorial.info', $memorial_slug) }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorial.info') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-id-card start-icon"></i>
                    Información
                </small>
            </a>
            @endif
            @if ($memorial->canEditModule($user, 'life'))
            <a href="{{ route('admin.memorial.life', $memorial_slug) }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorial.life') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-heart  start-icon"></i>
                    Vida
                </small>
            </a>
            @endif
            @if ($memorial->canEditModule($user, 'timeline'))
            <a href="{{ route('admin.memorial.timeline', $memorial_slug) }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorial.timeline') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-timeline  start-icon"></i>
                    Línea de Tiempo
                </small>
            </a>
            @endif
            @if ($memorial->canEditModule($user, 'gallery'))
            <a href="{{ route('admin.memorial.gallery', $memorial_slug) }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorial.gallery') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-images  start-icon"></i>
                    Galería
                </small>
            </a>
            @endif

            <!-- <div class="sidebar-section-label text-muted fw-bold">Mensajes</div> -->
            @if ($memorial->canEditModule($user, 'messages'))
            <a href="{{ route('admin.memorial.messages', $memorial_slug) }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorial.messages') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-envelope-open-text  start-icon"></i>
                    Mensajes
                </small>
            </a>
            @endif
            <div class="sidebar-section-label text-muted fw-bold">Finanzas</div>

            <a href="{{route('admin.payments')}}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.payments') || request()->routeIs('admin.invoice')  ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-credit-card  start-icon"></i>
                    Pagos
                </small>
            </a>
            @if ($memorial->user_id === $user->id)
            <div class="sidebar-section-label text-muted fw-bold">Invitaciones</div>

            <a href="{{route('admin.memorial.invitations', $memorial_slug)}}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.memorial.invitations') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-envelope  start-icon"></i>
                    Invitaciones
                </small>
            </a>
            @endif
        </div>

        <div class="sidebar-bottom-btn sticky-bottom mt-auto card-dark">
            <div class="safe-area mt-auto border-top border-dark">
                <div class="row g-2 p-3">
                    <div class="col-12">
                        <a href="{{route('logout')}}" class="btn btn-primary w-100">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                            Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>