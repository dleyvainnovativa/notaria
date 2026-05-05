<div class="tab-pane fade show h-100"
    id="tab-life"
    role="tabpanel"
    aria-labelledby="tab-life-btn"
    tabindex="0">


    <nav id="scroll-navbar"
        class="navbar w-100 navbar-dark bg-dark sticky-top shadow-sm d-none transition py-3 scroll-navbar">
        <div class="container">
            <div class="container">
                <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto">
                    <div class="row">
                        <div class="col-4 my-auto">
                            <a
                                class="d-flex align-items-center gap-2 text-muted text-decoration-none fw-bold">
                                Su Vida
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
                            <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#timelineBottomSheet" aria-controls="timelineBottomSheet"><i class="fas fa-timeline"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>


    <div class="container h-90 overflow-y-auto">
        <div class="container h-100 w-100">
            <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto h-100">

                <div class="row py-4 g-2">
                    <div class="col-12 pb-2" id="back-trigger">
                        <div class="row">
                            <div class="col-auto my-auto">
                                <h1 class="subtitle display mb-0">Su Vida</h1>

                            </div>
                            <div class="col-auto ms-auto my-auto">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#timelineBottomSheet" aria-controls="timelineBottomSheet"><i class="fas fa-timeline"></i></button>

                            </div>
                        </div>
                    </div>
                    @if($memorial->paragraphs->count())
                    @foreach($memorial->paragraphs as $paragraph)
                    <div class="col-12">
                        <p>{{ $paragraph->content }}</p>
                    </div>
                    @endforeach
                    @else
                    <div class="col-12">
                        <p class="text-muted fst-italic">
                            Aún no se ha agregado la historia de su vida.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>