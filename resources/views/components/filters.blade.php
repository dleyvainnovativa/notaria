{{-- Offcanvas Filters --}}
<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasCategories" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header text-bg-primary">
        <h5 class="offcanvas-title fw-bolder" id="offcanvasRightLabel">Filtros</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="d-flex flex-wrap gap-3">
            <div id="category-filters">
                <label class="form-label text-muted small">Category</label>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary">All Services</button>
                    @foreach ($categories as $category)
                    <button type="button" class="btn btn-outline-primary">{{$category["name"]}}</button>
                    @endforeach
                </div>
            </div>
            <div id="price-filters">
                <label class="form-label text-muted small">Price Range</label>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary">All Prices</button>
                    <button type="button" class="btn btn-outline-primary">Under $40</button>
                    <button type="button" class="btn btn-outline-primary">$40 - $60</button>
                    <button type="button" class="btn btn-outline-primary">Over $60</button>
                </div>
            </div>
        </div>
        <div class="my-4 text-muted" id="service-count-display">
            <p class="text-muted">{{ count($services) }} services available</p>
        </div>
    </div>
    <div class="offcanvas-footer">
        <div class="p-3">
            <button class="btn btn-outline-primary w-100" data-bs-dismiss="offcanvas">Aplicar filtros</button>
        </div>

    </div>
</div>