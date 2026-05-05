<style>
    .content-fill {
        background-image: url("{{ $memorial->profile_image_url ? route('home') . $memorial->profile_image_url : asset('img/default-profile.jpg') }}");
    }
</style>


<div class="tab-pane fade show active h-100"
    id="tab-photo"
    role="tabpanel"
    aria-labelledby="tab-photo-btn"
    tabindex="0">
    <nav id="scroll-navbar-info"
        class="navbar w-100 navbar-dark bg-dark sticky-top shadow-sm transition py-3 scroll-navbar">
        <div class="container">
            <div class="container">
                <div class="col-12 col-md-10 col-lg-10 col-xl-5 mx-auto">
                    <div class="row">
                        <div class="col-4 my-auto">
                            <a
                                class="d-flex align-items-center gap-2 text-muted text-decoration-none fw-bold">
                                Info
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
                            <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#infoBottomSheet" aria-controls="infoBottomSheet"><i class="fas fa-circle-info"></i></button>
                            <button class="btn btn-primary btn-sm" onclick="sharePage()"><i class="fas fa-share-nodes"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="h-90">
        <div class="container h-100">
            <div class="container h-100">
                <div class="d-flex flex-column h-100 col-xl-5 col-12 mx-auto">
                    <div class="row g-0 opacity-0">
                        <div class="col">
                            <nav
                                class="d-flex h-100 w-100 navbar-dark bg-dark shadow-sm transition py-3 scroll-navbar">
                                <div class="container">
                                    <div class="container">
                                        <div class="mx-auto">
                                            <div class="row">
                                                <div class="col-4 my-auto">
                                                    <a
                                                        class="d-flex align-items-center gap-2 text-muted text-decoration-none fw-bold">
                                                        Info
                                                    </a>
                                                </div>

                                                <div class="col-4 ms-auto text-end my-auto">
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="offcanvas"
                                                        data-bs-target="#infoBottomSheet" aria-controls="infoBottomSheet">
                                                        <i class="fas fa-circle-info" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    <div class="row content-fill g-0 my-2 rounded">
                    </div>

                    <div class="row g-0 pt-2 pb-4">
                        <div class="col">
                            <div class="text-dark text-start">
                                <h1 class="title display mb-0">
                                    {{ $memorial->deceased_name }}
                                </h1>

                                @if($memorial->biography)
                                <p class="mb-1">
                                    {{ Str::limit($memorial->biography, 120) }}
                                </p>
                                @endif

                                <small class="text-muted fw-light">
                                    {{ optional($memorial->birth_date)->format('Y') }}
                                    –
                                    {{ optional($memorial->death_date)->format('Y') }}
                                </small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sharePage() {
        if (navigator.share) {
            navigator.share({
                    title: document.title,
                    text: "Comparte mi página dedicatoria a sus seres queridos",
                    url: window.location.href,
                })
                .catch(err => console.log('Share cancelled', err));
        } else {
            alert('Sharing not supported on this browser');
        }
    }
</script>