<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0 fw-bold">Ajouter un burger</h1>
    </x-slot>

    <div class="app-section-card p-3 p-md-4" style="max-width: 850px;">
        <form method="POST" action="{{ route('burgers.store') }}" enctype="multipart/form-data">
            @csrf
            @include('burgers._form')
        </form>
    </div>
</x-app-layout>
