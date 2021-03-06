@extends("master")
@section("title", $product->name)
@section("content")
    <div class="row">
        <div class="col-12 col-lg-5 mx-auto">
            <div id="slider">
                @foreach($product->pictures as $picture)
                    <div>
                        <img data-zoom="{{$picture->getPath()}}" class="img-fluid img-product"
                             src="{{$picture->getPath()}}" alt="{{$product->name}} picture">
                    </div>
                @endforeach
            </div>
            <div class="mx-auto text-center mt-2" id="thumbnails">
                @foreach($product->pictures as $picture)
                    <img class="img-thumbnail img-thumbnail-product" src="{{$picture->getPath()}}"
                         alt="{{$product->name}} picture">
                @endforeach
            </div>
        </div>
        <div class="col-12 col-lg-7" id="product_details_container">
            <h1 class="display-4">{{$product->name}}</h1>
            <p class="product-detail">{{$product->description}}</p>
            <h1 class="display-3">${{number_format($product->price, 2)}} </h1>
            <h3 class="text-muted">{{$product->stock}} {{__("messages.available_count")}}</h3>
            @if($inCart)
                <h4 class="text-primary">
                    <i class="fa fa-check"></i>&nbsp;{{__('messages.already_added_to_cart')}}
                </h4>
                <a class="btn btn-success" href="{{route("view_cart")}}">{{__("messages.see_cart")}}</a>
            @else
                <form method="post" action="{{route("add_product_to_cart", ["product"=>$product])}}">
                    @csrf
                    <div class="input-group mb-3 add-to-cart-form">
                        <input name="quantity" value="1" min="1" max="{{$product->stock}}" type="number"
                               class="form-control"
                               placeholder="{{__("messages.quantity")}}">
                        <div class="input-group-append" style="z-index: 0">
                            <button class="btn btn-success" type="submit">{{__("messages.add_to_cart")}}&nbsp;<i
                                    class="fa fa-cart-plus"></i></button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // This is for the carousel/slider
            tns({
                container: "#slider",
                items: 1,
                autoplay: true,
                autoplayButton: false,
                autoplayButtonOutput: false,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                navAsThumbnails: true,
                autoWidth: true,
                controls: false,
                nav: true,
                navContainer: "#thumbnails",
                center: true,
                speed: 1000,
            });
            // And this is for the image zoom
            const $images = document.querySelectorAll(".img-product"),
                paneContainer = document.querySelector("#product_details_container");
            $images.forEach($image => {
                new Drift($image, {
                    paneContainer: paneContainer,
                    handleTouch: false,
                });
            });
        });
    </script>

@endsection
