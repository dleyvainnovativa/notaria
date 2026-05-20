@extends('admin.main')

@section('content')
@vite(["resources/css/admin/steps.css"])
<main class="">
    <div class="tab-pane fade show active" id="main-pane-calendar" role="tabpanel" aria-labelledby="main-tab-calendar">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto">
            <ul class="nav nav-pills d-flex px-0 " id="stepperTabs" role="tablist" style="gap:0;">
                <li class="nav-item flex-fill text-center calendar-options active" role="presentation">
                    <button class="nav-link text-center active  mx-auto" id="tab-info" data-bs-toggle="pill" data-bs-target="#pane-info" type="button" aria-selected="true" role="tab">
                        <div class="rounded-circle bg-primary-subtle border border-primary text-secondary d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-file-upload" aria-hidden="true"></i>
                        </div>
                        <small class="d-md-block d-none mt-2 text-muted" id="main_name">Subir documento</small>
                    </button>
                </li>
                <li class="nav-item flex-fill text-center calendar-options" role="presentation">
                    <button class="nav-link text-center  mx-auto" id="tab-rules" data-bs-toggle="pill" data-bs-target="#pane-rules" type="button" aria-selected="false" tabindex="-1" role="tab">
                        <div class="rounded-circle bg-primary-subtle border border-primary text-secondary d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-gear" aria-hidden="true"></i>
                        </div>
                        <small class="d-md-block d-none mt-2 text-muted">Reglas y Formato</small>
                    </button>
                </li>
                <li class="nav-item flex-fill text-center calendar-options" role="presentation">
                    <button class="nav-link text-center  mx-auto" id="tab-calendar" data-bs-toggle="pill" data-bs-target="#pane-calendar" type="button" aria-selected="false" tabindex="-1" role="tab">
                        <div class="rounded-circle bg-primary-subtle border border-primary text-secondary d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-info-circle" aria-hidden="true"></i>
                        </div>
                        <small class="d-md-block d-none mt-2 text-muted">Resultado</small>
                    </button>
                </li>
            </ul>
        </div>
        <div class=" pt-4">
            <div class="row g-4">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6 mx-auto pt-md-5">
                    <div class="tab-content" id="stepperTabsContent">
                        @include("admin.sections.declaranot.upload")
                        @include("admin.sections.declaranot.loading")
                        @include("admin.sections.declaranot.result")
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection