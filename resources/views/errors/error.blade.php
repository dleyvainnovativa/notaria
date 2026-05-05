@extends('main')

@section('content')

<section class="text-bg-dark pt-4 pb-5">
    <div class="container text-center pb-5">
        <div class="row g-4 py-4">
            <div class="col-lg-12 col-12 col-md-12 mx-auto">
                <div class="row g-2 text-center">
                    <div class="col-12">
                        <!-- <h3 class="feature-icon bg-success-subtle d-inline-flex align-items-center justify-content-center"> -->
                        <h2 class="">
                            <i class="far fa-circle-xmark text-muted fa-2xl"></i>
                        </h2>
                    </div>
                    <div class="col-12">
                        <h1 class="title">{{$title}}</h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12 col-md-12 mx-auto text-center">
                <div class="card card-dark border border-dark text-dark">
                    <div class="card-body p-4">
                        <h3 class="fw-bold"> {{$subtitle}}</h3>
                        <p class=" text-muted">
                            {{$message}}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 col-12 mx-auto">
                <div class="row g-2">
                    <div class="col-lg-6 col-12 col-md-6 mx-auto text-start">
                        <a class="btn btn-outline-primary w-100 btn-lg" href="{{route('home')}}">
                            Regresar a Home
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection