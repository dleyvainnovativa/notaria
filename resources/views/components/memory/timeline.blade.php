<div class="offcanvas offcanvas-bottom custom-offcanvas text-bg-dark border border-dark"
    tabindex="-1"
    id="timelineBottomSheet"
    aria-labelledby="timelineBottomSheetLabel">

    <div class="offcanvas-header pt-4 px-4 container">
        <h5 class="offcanvas-title display" id="timelineBottomSheetLabel">
            Línea de Tiempo
        </h5>

        <button type="button"
            class="btn btn-dark ms-auto"
            data-bs-dismiss="offcanvas"
            aria-label="Close">
            <i class="fas fa-xmark fa-lg text-dark"></i>
        </button>
    </div>

    <div class="offcanvas-body px-0">
        <main class="container py-4 col-12 col-md-6">
            <div class="container">
                <div class="timeline-wrapper position-relative">

                    <!-- vertical line -->
                    <div class="timeline-line"></div>

                    <div class="d-flex flex-column gap-5">

                        @forelse($memorial->timelineEvents as $event)
                        <div class="timeline-item position-relative ps-5">

                            <div class="timeline-dot-wrapper">
                                <div class="timeline-dot-inner"></div>
                            </div>
                            <div class="card card-dark border border-dark text-dark">
                                <div class="card-body">
                                    <span class="timeline-year">
                                        {{ $event->event_date->format('Y') }}
                                    </span>
                                    <h3 class="fs-4 fw-bold mb-2">
                                        {{ $event->title }}
                                    </h3>
                                    @if($event->description)
                                    <p class="text-muted mb-0">
                                        {{ $event->description }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                        </div>
                        @empty
                        <p class="text-muted fst-italic">
                            Aún no se han agregado eventos a la línea de tiempo.
                        </p>
                        @endforelse

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>