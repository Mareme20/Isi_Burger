<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0 fw-bold">Modifier le burger: {{ $burger->nom }}</h1>
    </x-slot>

    <div class="app-section-card p-3 p-md-4" style="max-width: 850px;">
        <form method="POST" action="{{ route('burgers.update', $burger) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('burgers._form', ['burger' => $burger])
        </form>
    </div>
</x-app-layout>
