@if (isset($barcode_html) && $barcode_html)
    <div class="barcode">
        {!! $barcode_html !!}
    </div>
@else
    <p>Barcode belum tersedia.</p>
@endif
