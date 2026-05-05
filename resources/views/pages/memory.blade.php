@extends('memory')

@section('content')
<div class="tab-content h-100" id="memorial-tabContent">
    @include("sections.memory.info")
    @include("sections.memory.life")
    @include("sections.memory.messages")
    @include("sections.memory.gallery")
    @include("sections.memory.playlist")
    @include("sections.memory.partners")
</div>

@include("sections.memory.navigation")


@endsection