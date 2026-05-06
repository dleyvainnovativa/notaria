<nav class="navbar navbar-expand-lg main-navbar px-4 sticky-top bg-dark  border-bottom border-top border-dark card-dark ">
    <button class="btn btn-dark card-dark border-0" id="sidebarToggle"><i class="fas fa-bars text-dark"></i></button>

    <!-- <div class="d-none d-sm-block ms-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted"><i class="fa-solid fa-house"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div> -->

    <div class="ms-auto d-flex align-items-center gap-3">
        <a id="enable-notifications-btn" href="{{route('payment')}}" class="btn btn-outline-primary position-relative" title="Compra ahora">
            <i class="fas fa-coins me-2"></i>100
        </a>
        <a class="position-relative ms-auto">
            <div class="theme-switch ">
                <input type="checkbox" id="themeToggle">
                <label for="themeToggle" class=" btn btn-primary">
                    <i id="themeIcon" class="fas fa-moon"></i>
                </label>
            </div>
        </a>
        <div class="dropdown profile">
            <a class="d-flex align-items-center dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                <div class="btn btn-outline-primary">
                    <i class="fa-regular fa-user"></i>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end mt-3 p-0 overflow-hidden card-dark border border-dark text-dark" style="min-width: 260px;">

                <!-- Profile Header -->
                <li class="px-3 py-3 border-bottom border-dark">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <div class="fw-bold">{{ session('user_name') }}</div>
                            <small class="text-muted">{{ session('user_email') }}</small>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center py-3 text-dark" href="#">
                        <i class="fas fa-user text-primary me-3"></i> Mi perfil
                    </a>
                </li>

                <li class="border-top border-dark">
                    <a class="dropdown-item text-danger d-flex align-items-center py-3 text-dark" href="{{route('logout')}}">
                        <i class="fas fa-sign-out-alt text-primary me-3"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
{{-- Add this somewhere prominent, like at the top of your content section --}}
<div id="notification-alert" class="alert alert-warning d-none" role="alert">
    <strong>Notifications are blocked.</strong> To receive new booking alerts, please enable notifications for this site in your browser settings.
</div>