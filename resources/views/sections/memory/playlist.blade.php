<div class="tab-pane fade show h-100"
    id="tab-playlist"
    role="tabpanel"
    aria-labelledby="tab-playlist-btn"
    tabindex="0">

    <div class="container h-90 overflow-y-auto">
        <div class="container h-100 w-100">
            <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto h-100">

                <div class="row py-4 d-flex flex-column h-100">
                    <div class="col-12 pb-3">
                        <h1 class="subtitle display mb-0">Playlist</h1>
                    </div>

                    <div class="col-12 flex-grow-1">
                        @if($memorial->playlist)
                        <div class="h-100">
                            <iframe
                                style="border-radius:12px"
                                src="{{ $memorial->playlist }}"
                                width="100%"
                                height="100%"
                                frameborder="0"
                                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture">
                            </iframe>
                        </div>
                        @else
                        <div class="h-100 d-flex align-items-center justify-content-center text-muted text-center px-4">
                            <div>
                                <i class="fas fa-music fa-2x mb-3 opacity-50"></i>
                                <p class="mb-0">
                                    Aún no se ha agregado una playlist para este recuerdo
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>