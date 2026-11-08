<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $product->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id ?? '') == $c->id)>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Foto Produk</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if(isset($product) && $product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="mt-2 rounded" width="100">
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Normal <span class="text-danger">*</span></label>
        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', $product->price ?? '') }}" required min="0" step="100">
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Promo</label>
        <input type="number" name="promo_price" class="form-control @error('promo_price') is-invalid @enderror"
               value="{{ old('promo_price', $product->promo_price ?? '') }}" min="0" step="100">
        @error('promo_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Stok <span class="text-danger">*</span></label>
        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
               value="{{ old('stock', $product->stock ?? 0) }}" required min="0">
        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label">Satuan</label>
        <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit ?? 'karton') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Isi per Karton</label>
        <input type="number" name="quantity_per_carton" class="form-control"
               value="{{ old('quantity_per_carton', $product->quantity_per_carton ?? 24) }}" required min="1">
    </div>
    <div class="col-md-3">
        <label class="form-label">Status Promo</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_promo" value="1"
                   id="isPromo" @checked(old('is_promo', $product->is_promo ?? false))>
            <label class="form-check-label" for="isPromo">Aktifkan Promo</label>
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Mulai Promo</label>
        <input type="date" name="promo_start" class="form-control"
               value="{{ old('promo_start', isset($product) && $product->promo_start ? $product->promo_start->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Selesai Promo</label>
        <input type="date" name="promo_end" class="form-control"
               value="{{ old('promo_end', isset($product) && $product->promo_end ? $product->promo_end->format('Y-m-d') : '') }}">
    </div>
</div>