@extends('Front.layouts.master')
@section('title','Home')
@section('PageContent')

<!-- BEGIN: slider Section -->
<section class="sliderSection">
<div id="owl-carousel-slider" class="owl-carousel owl-theme">
@foreach($carousals as $sals)
    <div class="item">
        <img alt="slider" class="m-hidden" src="{{$sals->imageurl}}">
        <img alt="slider" class="d-hidden" src="{{$sals->image_sm}}">
    </div>
@endforeach
    <!-- <div class="item">
        <img alt="slider" class="m-hidden" src="{{asset('/lokal')}}/images/slider/slider.png">
        <img alt="slider" class="d-hidden" src="{{asset('/lokal')}}/images/slider/slider-one.png">
    </div>
    <div class="item">
        <img alt="slider" class="m-hidden" src="{{asset('/lokal')}}/images/slider/slider.png">
        <img alt="slider" class="d-hidden" src="{{asset('/lokal')}}/images/slider/slider-one.png">
    </div>
    <div class="item">
        <img alt="slider" class="m-hidden" src="{{asset('/lokal')}}/images/slider/slider.png">
        <img alt="slider" class="d-hidden" src="{{asset('/lokal')}}/images/slider/slider-one.png">
    </div> -->
</div>
</section>
<!-- END: slider Section -->

<!-- BEGIN: Top Collections Section desktop -->
<section class="topCollectionsSection">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="section-header">
                <h1>Top Collections</h1>
                <p>Curated picks of all our favorites</p>
            </div>
        </div>
    </div>
    <div class="m-hidden">
        <div class="row">
		@foreach($topcollections as $topc)
            <div class="col-lg-{{$topc->grid}}">
                <div class="collection-item">
                    <a href="#">
                        <img alt="women" src="{{$topc->imageurl}}">
                        <div class="overlay">
                            <h1>{{$topc->name}}</h1>
                            <p>{{$topc->description}}</p>
                        </div>
                    </a>
                </div>
            </div>
		@endforeach
        </div>
    </div>
    <div class="d-hidden">
        <div class="row">
            <div class="col-md-12">
                <div class="topCollection-mobile">
                    <div class="topCollectionRow">
                        <div class="products-item">
                            <div class="products-img">
                                <img alt="slider" src="{{asset('/lokal')}}/images/slider/women-one.png">
                            </div>
                            <div class="products-header">
                                <h2>Women’s clothing</h2>
                                <p>Collection of women clothing made in Kuwait.</p>
                            </div>
                        </div>
                        <div class="products-item">
                            <div class="products-img">
                                <img alt="slider" src="{{asset('/lokal')}}/images/slider/menu-one.png">
                            </div>
                            <div class="products-header">
                                <h2>Men’s clothing</h2>
                                <p>Collection of Men’s clothing made in Kuwait.</p>
                            </div>
                        </div>
                        <div class="products-item">
                            <div class="products-img">
                                <img alt="slider" src="{{asset('/lokal')}}/images/slider/kids-one.jpg">
                            </div>
                            <div class="products-header">
                                <h2>Shop for Kid’s</h2>
                                <p>Clothing, accessories, toys and much more...</p>
                            </div>
                        </div>
                        <div class="products-item">
                            <div class="products-img">
                                <img alt="slider" src="{{asset('/lokal')}}/images/slider/shop-accessories.jpg">
                            </div>
                            <div class="products-header">
                                <h2>Shop for accessories</h2>
                                <p>Wide range of accessories collection made in Kuwait.</p>
                            </div>
                        </div>
                        <div class="products-item">
                            <div class="products-img">
                                <img alt="slider" src="{{asset('/lokal')}}/images/slider/shop-brands.jpg">
                            </div>
                            <div class="products-header">
                                <h2>Shop for accessories</h2>
                                <p>Wide range of accessories collection made in Kuwait.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- END: Top Collections Section -->



<!-- BEGIN: usp Section -->
<section class="uspSection">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="uspInner">
                <h2>Lorem ipsum dolor sit amet</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Neque elementum, feugiat amet, hac
                    vel, duis.</p>
            </div>
        </div>
    </div>
</div>
</section>
<!-- END: usp Section -->

<!-- BEGIN: product Section -->
<section class="productSection">
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="product-item">
                <div class="product-images">
                    <div class="product-img-left">
                        <img alt="arrivals" src="{{asset('/lokal')}}/images/new-arrivals.png">
                    </div>
                    <div class="product-img-right">
                        <div class="pimg-top">
                            <img alt="arrivals" src="{{asset('/lokal')}}/images/new-arrivals-1.png">
                        </div>
                        <div class="pimg-bottom">
                            <img alt="arrivals" src="{{asset('/lokal')}}/images/new-arrivals-2.png">
                        </div>
                    </div>
                </div>
                <div class="product-header">
                    <h2>New Arrivals</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="product-item">
                <div class="product-images">
                    <div class="product-img-left">
                        <img alt="arrivals" src="{{asset('/lokal')}}/images/our-personal-favorites.png">
                    </div>
                    <div class="product-img-right">
                        <div class="pimg-top">
                            <img alt="arrivals" src="{{asset('/lokal')}}/images/our-personal-favorites-1.png">
                        </div>
                        <div class="pimg-bottom">
                            <img alt="arrivals" src="{{asset('/lokal')}}/images/our-personal-favorites-2.png">
                        </div>
                    </div>
                </div>
                <div class="product-header">
                    <h2>Our Personal Favorites</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="product-item">
                <div class="product-images">
                    <div class="product-img-left">
                        <img alt="arrivals" src="{{asset('/lokal')}}/images/sale-discounted-products.png">
                    </div>
                    <div class="product-img-right">
                        <div class="pimg-top">
                            <img alt="arrivals" src="{{asset('/lokal')}}/images/sale-discounted-products-1.png">
                        </div>
                        <div class="pimg-bottom">
                            <img alt="arrivals" src="{{asset('/lokal')}}/images/sale-discounted-products-2.png">
                        </div>
                    </div>
                </div>
                <div class="product-header">
                    <h2>Sale & Discounted Products</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- END: product Section -->

<!-- BEGIN: favorites Section -->
<section class="favoritesSection">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="favorites-slider" class="owl-carousel owl-theme">
			@foreach($usbanners as $usban)
                <div class="item">
                    <div class="favorites-item">
                        <div class="favorites-info m-hidden">
                            <h2>{{$usban->name}}</h2>
                            <P>{{$usban->description}}</P>
                            <button class="btn btn-primary" type="button">Shop now</button>
                        </div>
                        <img alt="slider" class="m-hidden" src="{{$usban->imageurl}}">
                        <img alt="slider" class="d-hidden" src="{{$usban->image_sm}}">
                    </div>
                </div>
            @endforeach
            </div>
            <div class="favorites-info d-hidden">
                <h2>Shop your favorites</h2>
                <P>Lorem ipsum dolor sit amet, consectetur adipiscing.</P>
                <button class="btn btn-primary" type="button">Shop now</button>
            </div>
        </div>
    </div>
</div>
</section>
<!-- END: favorites Section -->

<!-- BEGIN: Browse products Section -->
<section class="browseProductsSection">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Browse products</h2>
        </div>
    </div>
    <div class="row">
        <div id="browseProductsSection-slider" class="owl-carousel owl-theme">
            <div class="item">
                <div class="browseproduct-item">
                    <div class="browseproduct-img">
                        <span class="like-icon"><img src="{{asset('/lokal')}}/images/heart.png"></span>
                        <img alt="slider" src="{{asset('/lokal')}}/images/product1.png">
                    </div>
                    <div class="browseproduct-header">
                        <h2>Hooded sweatshirt dress</h2>
                        <p>KWD 6.990</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="browseproduct-item">
                    <div class="browseproduct-img">
                        <span class="like-icon"><img src="{{asset('/lokal')}}/images/heart.png"></span>
                        <img alt="slider" src="{{asset('/lokal')}}/images/product2.png">
                    </div>
                    <div class="browseproduct-header">
                        <h2>Hooded sweatshirt dress</h2>
                        <p>KWD 6.990</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="browseproduct-item">
                    <div class="browseproduct-img">
                        <span class="like-icon"><img src="{{asset('/lokal')}}/images/heart.png"></span>
                        <img alt="slider" src="{{asset('/lokal')}}/images/product3.png">
                    </div>
                    <div class="browseproduct-header">
                        <h2>Hooded sweatshirt dress</h2>
                        <p>KWD 6.990</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="browseproduct-item">
                    <div class="browseproduct-img">
                        <span class="like-icon"><img src="{{asset('/lokal')}}/images/heart.png"></span>
                        <img alt="slider" src="{{asset('/lokal')}}/images/product4.png">
                    </div>
                    <div class="browseproduct-header">
                        <h2>Hooded sweatshirt dress</h2>
                        <p>KWD 6.990</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="browseproduct-item">
                    <div class="browseproduct-img">
                        <span class="like-icon"><img src="{{asset('/lokal')}}/images/heart.png"></span>
                        <img alt="slider" src="{{asset('/lokal')}}/images/product4.png">
                    </div>
                    <div class="browseproduct-header">
                        <h2>Hooded sweatshirt dress</h2>
                        <p>KWD 6.990</p>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="browseproduct-item">
                    <div class="browseproduct-img">
                        <span class="like-icon"><img src="{{asset('/lokal')}}/images/heart.png"></span>
                        <img alt="slider" src="{{asset('/lokal')}}/images/product3.png">
                    </div>
                    <div class="browseproduct-header">
                        <h2>Hooded sweatshirt dress</h2>
                        <p>KWD 6.990</p>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</section>
<!-- END: Browse products Section -->

<!-- BEGIN: Subscribe to our newsletter -->
<section class="subscribeSection">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="subscribe-block">
                <div class="email-banner">
                    <img alt="email" src="{{asset('/lokal')}}/images/email-banner3.png">
                </div>
                <div class="subscribe-info">
                    <div class="subscribe-info-block">
                        <h2>Subscribe to our newsletter</h2>
                        <p>Promotions, new products and sales. Directly to your inbox.</p>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Enter your email">
                        </div>
                        <div class="form-group-action">
                            <button class="btn btn-secondary" type="button">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- END: Subscribe to our newsletter -->

<!-- BEGIN: Products Section -->
<section class="productsSection">
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="products-item">
                <div class="products-img">
                    <img alt="slider" class="m-hidden" src="{{asset('/lokal')}}/images/join-lokal.png">
                    <img alt="slider" class="d-hidden" src="{{asset('/lokal')}}/images/m-join-lokal.png">
                </div>
                <div class="products-header">
                    <h2>Join Lokal & Sell Fast! </h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Amet elementum at molestie
                        fringilla eu placerat.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="products-item">
                <div class="products-img">
                    <img alt="slider" class="m-hidden" src="{{asset('/lokal')}}/images/about-us.png">
                    <img alt="slider" class="d-hidden" src="{{asset('/lokal')}}/images/m-about-us.png">
                </div>
                <div class="products-header">
                    <h2>Know more about Lokal</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Amet elementum at molestie
                        fringilla eu placerat.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="products-item">
                <div class="products-img">
                    <img alt="slider" class="m-hidden" src="{{asset('/lokal')}}/images/contact.png">
                    <img alt="slider" class="d-hidden" src="{{asset('/lokal')}}/images/m-contact.png">
                </div>
                <div class="products-header">
                    <h2>Get in touch with us</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Amet elementum at molestie
                        fringilla eu placerat.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- END: Products Section -->

@endsection