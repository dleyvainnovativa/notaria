<div class="tab-pane fade show h-100"
    id="tab-messages"
    role="tabpanel"
    aria-labelledby="tab-messages-btn"
    tabindex="0">
    <nav id="scroll-navbar-messages"
        class="navbar w-100 navbar-dark bg-dark sticky-top shadow-sm d-none transition py-3 scroll-navbar">
        <div class="container">
            <div class="container">
                <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto">
                    <div class="row">
                        <div class="col-4 my-auto">
                            <a
                                class="d-flex align-items-center gap-2 text-muted text-decoration-none fw-bold">
                                Mensajes
                            </a>
                        </div>
                        <div class="col-4 mx-auto text-center my-auto">
                            <img onclick="toggleIcon(this)" src="{{asset('img/icon.png')}}" width="50" alt="">
                        </div>
                        <!-- <div class="col-4 mx-auto text-center my-auto justify-content-center justify-item-center align-items-center">
                            <div class="mx-auto icon-flip" onclick="toggleIcon(this)">
                                <img class="icon icon-light my-auto" src="{{ asset('img/icon.png') }}" width="50" alt="">
                            </div>
                        </div> -->
                        <div class="col-4 ms-auto text-end my-auto">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#messageBottomSheet" aria-controls="messageBottomSheet"><i class="fas fa-add"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container h-90 overflow-y-auto">
        <div class="container h-100 w-100">
            <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto h-100 py-4">

                {{-- Header --}}
                <div class="pb-3" id="messages-trigger">
                    <div class="row">
                        <div class="col-auto my-auto">
                            <h1 class="subtitle display mb-0">Mensajes</h1>
                        </div>

                        <div class="col-auto ms-auto my-auto">
                            <button
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#messageBottomSheet"
                                aria-controls="messageBottomSheet">
                                <i class="fas fa-add"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="row pb-5 g-4">

                    @forelse($memorial->tributes->where('is_approved', true) as $tribute)

                    <div class="col-12">
                        <div class="card card-dark border border-dark shadow-sm">
                            <div class="card-body p-4 text-dark">
                                <div class="row g-2">

                                    <div class="col-12">
                                        <p class="mb-2 fst-italic">
                                            “{{ $tribute->message }}”
                                        </p>
                                    </div>

                                    <div class="col-auto">
                                        <span class="fw-semibold">
                                            {{ $tribute->author_name }}
                                        </span>
                                    </div>

                                    <div class="col-auto ms-auto">
                                        <span class="text-muted fw-light">
                                            {{ $tribute->created_at->translatedFormat('F Y') }}
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <div class="col-12">
                        <div class="text-center text-muted fst-italic py-4">
                            Aún no hay mensajes publicados.
                        </div>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

</div>