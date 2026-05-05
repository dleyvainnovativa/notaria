@section('title', 'Crea Memorial')

@extends('action')

@section('content')

<nav id="scroll-navbar"
    class="navbar navbar-dark bg-dark fixed-top shadow-sm d-none transition py-3">
    <div class="container">
        <div class="container">
            <div class="col-12 col-md-8 col-lg-8 col-xl-7 mx-auto">
                <div class="row">
                    <!-- <div class="col-4 my-auto">
                        <a href="{{route('home')}}"
                            class="d-flex align-items-center gap-2 text-muted text-decoration-none fw-bold">
                            <i class="fa-solid fa-arrow-left"></i>
                            Regresar
                        </a>
                    </div> -->
                    <div class="col-4 my-auto">
                        <a href="{{route('admin')}}"
                            class="d-flex align-items-center gap-2 text-muted text-decoration-none fw-bold">
                            <i class="fa-solid fa-arrow-left"></i>
                            Regresar
                        </a>
                    </div>
                    <div class="col-4 text-center my-auto mx-auto icon-flip">
                        <img class="icon icon-light my-auto" id="themeToggle" src="{{ asset('img/icon.png') }}" width="50" alt="">
                    </div>
                    <!-- <div class="col-4 mx-auto text-center my-auto">
                        <img src="{{asset('img/icon.png')}}" width="50" alt="">

                    </div> -->
                    <div class="col-4 ms-auto text-end my-auto">
                        <small class="text-muted fw-light">
                            Crear Memorial
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


<div class="text-bg-dark container py-5">
    <form class="needs-validation" novalidate id="payment-form">
        <div class="row g-4">
            @include("sections.book.welcome")
            @include("sections.book.form")
            @include("sections.book.summary")
            @include("sections.book.payment")
        </div>
    </form>
</div>

@vite(["resources/js/payment.js"])

@endsection