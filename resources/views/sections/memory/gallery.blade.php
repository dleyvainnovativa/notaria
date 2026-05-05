<div class="tab-pane fade show h-100"
    id="tab-gallery"
    role="tabpanel"
    aria-labelledby="tab-gallery-btn"
    tabindex="0">
    <nav id="scroll-navbar-gallery"
        class="navbar w-100 navbar-dark bg-dark sticky-top shadow-sm d-none transition py-3 scroll-navbar">
        <div class="container">
            <div class="container">
                <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto">
                    <div class="row">
                        <div class="col-4 my-auto">
                            <a
                                class="d-flex align-items-center gap-2 text-muted text-decoration-none fw-bold">
                                Galería
                            </a>
                        </div>
                        <div class="col-4 mx-auto text-center my-auto">
                            <img onclick="toggleIcon(this)" src="{{asset('img/icon.png')}}" width="50" alt="">
                        </div>
                        <div class="col-4 ms-auto text-end my-auto">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#infoBottomSheet" aria-controls="infoBottomSheet"><i class="fas fa-circle-info"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container h-90 overflow-y-auto">
        <div class="container h-100 w-100">
            <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto h-100">
                <div class="row py-4">
                    <div class="col-12 pb-3" id="gallery-trigger">
                        <div class="row">
                            <div class="col-auto my-auto">
                                <h1 class="subtitle display mb-0">Galería</h1>

                            </div>
                            <div class="col-auto ms-auto my-auto">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#infoBottomSheet" aria-controls="infoBottomSheet"><i class="fas fa-circle-info"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-4">
                        <div class="row g-4 grid-gallery">
                            @forelse ($memorial->mediaItems as $item)
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 grid-item">
                                <div class="card card-image rounded border border-dark">

                                    <img

                                        data-bp="{{route('home')}}/{{ $item->url }}"
                                        class="card-img-top rounded bp-img"
                                        src="{{route('home')}}/{{ $item->url }}"
                                        alt="{{ $item->caption ?? 'Imagen del recuerdo' }}">

                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-4">
                                Aún no hay imágenes en este recuerdo
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>