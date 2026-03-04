<div class="mb-3">
    <label for="nom" class="form-label fw-semibold">Nom du burger</label>
    <input id="nom" name="nom" type="text" value="{{ old('nom', $burger->nom ?? '') }}" required autofocus class="form-control">
    @if($errors->get('nom'))<div class="text-danger small mt-1">{{ $errors->first('nom') }}</div>@endif
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="prix" class="form-label fw-semibold">Prix (FCFA)</label>
        <input id="prix" name="prix" type="number" step="0.01" value="{{ old('prix', $burger->prix ?? '') }}" required class="form-control">
        @if($errors->get('prix'))<div class="text-danger small mt-1">{{ $errors->first('prix') }}</div>@endif
    </div>
    <div class="col-md-6">
        <label for="stock" class="form-label fw-semibold">Stock</label>
        <input id="stock" name="stock" type="number" value="{{ old('stock', $burger->stock ?? 0) }}" required class="form-control">
        @if($errors->get('stock'))<div class="text-danger small mt-1">{{ $errors->first('stock') }}</div>@endif
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label fw-semibold">Description</label>
    <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $burger->description ?? '') }}</textarea>
    @if($errors->get('description'))<div class="text-danger small mt-1">{{ $errors->first('description') }}</div>@endif
</div>

<div class="mb-3">
    <label for="category_id" class="form-label fw-semibold">Categorie</label>
    <select id="category_id" name="category_id" class="form-select" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $burger->category_id ?? '') == $category->id)>
                {{ $category->nom }}
            </option>
        @endforeach
    </select>
    @if($errors->get('category_id'))<div class="text-danger small mt-1">{{ $errors->first('category_id') }}</div>@endif
</div>

<div class="mb-3">
    <label for="image" class="form-label fw-semibold">Image</label>
    <input id="image" name="image" type="file" class="form-control">
    @if($errors->get('image'))<div class="text-danger small mt-1">{{ $errors->first('image') }}</div>@endif

    @if(isset($burger) && $burger->image)
        <div class="mt-2">
            <small class="text-secondary d-block mb-1">Image actuelle :</small>
            <img src="{{ asset('storage/'.$burger->image) }}" class="rounded border" style="width: 120px; height: 120px; object-fit: cover;" alt="Image burger actuelle">
        </div>
    @endif
</div>

<div>
    <button class="auth-btn">{{ isset($burger) ? 'Mettre a jour' : 'Enregistrer' }}</button>
</div>
