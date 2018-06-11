<p class="h7">Editing Listing: {{ $listing->name }}</p>
<section id="hey-tabs" class="nav">
    <div id="ello-tab" class="tab-content">        
        <a href="{{ route('edit-listing-p1', [Request::segment(5)]) }}" class="h8" id="listingz">Basic Info</a>
        <a href="{{ route('edit-listing-p2', [Request::segment(5)]) }}" class="h8" id="trips">Amenities</a>
        <a href="{{ route('edit-listing-p3', [Request::segment(5)]) }}" class="h8" id="previous">Rules & Policies</a>
        <a href="{{ route('edit-listing-p4', [Request::segment(5)]) }}" class="h8" id="pricing">Pricing</a>
        <a href="{{ route('edit-listing-p5', [Request::segment(5)]) }}" class="h8" id="reviews">Directions</a>
        <a href="{{ route('edit-listing-p6', [Request::segment(5)]) }}" class="h8" id="support">Photos</a>
        <a href="{{ route('edit-listing-p7', [Request::segment(5)]) }}" class="h8" id="reviews">What's close by?</a>
    </div>
</section>