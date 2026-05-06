<div id="sidebar-wrapper" class="border border-dark card-dark sidebar safe-area">
    <div class="sidebar-heading text-primary">
        <img class="appLogo" src="{{asset('img/logo.png')}}" width="160" alt="">
    </div>

    <div class="d-flex flex-column vh-100 pb-5">
        <div class="list-group list-group-flush overflow-auto flex-grow-1 pb-5 mb-5">


            <!-- Memorial -->
            <div class="sidebar-section-label text-muted fw-bold">Inicio</div>

            <a href="{{route('admin')}}" class="list-group-item list-group-item-action {{ request()->routeIs('admin') ? 'active' : '' }} {{ request()->routeIs('admin.memorials') ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-house  start-icon"></i>
                    Home
                </small>
            </a>
            <!-- Finance -->
            <div class="sidebar-section-label text-muted fw-bold">Módulos</div>

            <a href="{{route('admin.document')}}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.document') || request()->routeIs('admin.invoice')  ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-file-contract  start-icon"></i>
                    Derechos Reales
                </small>
            </a>
            <div class="sidebar-section-label text-muted fw-bold">Finanzas</div>

            <a href="{{route('admin.payments')}}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.payments') || request()->routeIs('admin.invoice')  ? 'active' : '' }}">
                <small class="">
                    <i class="fa-solid fa-credit-card  start-icon"></i>
                    Pagos
                </small>
            </a>
        </div>

        <!-- Bottom -->
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