<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amenities | Por La Bahia</title>
    <link rel="stylesheet" href="{{asset('styles/amenities.css')}}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    
</head>
<body>
    <div class="background">
        <div class="overlay">
            @include('header')
            <main>
                <h2 class="amenity-title">Welcome to Por La Bahia</h2>
                <h1 class="amenity-subtitle"><span>Our Amenities:</span><br>Designed for your comfort<br>and enjoyment.</h1>

                <div class="firstAcc">
                    <div class="firstAccImage">
                        <img src="" alt="">
                    </div>
                    <div class="firstAccDetails">
                        <h1>Accommodations</h1>
                        <i class="fa fa-bed"></i>
                        <p>Each accommodation package includes essential beddings and toiletries to ensure your comfort. For your convenience, we provide 2 bath towels, 1 roll of toilet paper, and 2 small body soaps with shampoo. Each bed comes with 1 bedsheet, 1 blanket, and 2 pillows for a restful stay.</p><p> Please note that additional floor foam beddings are not included for free, but you have the option to rent them if needed.</p>
                        <div class="container">
                            <button class="goToAccommodation"> <a href="/accommodation">Go to Accommodations</a></button>
                        </div>                    
                    </div>
                </div>
                <div class="otherAmenity">
                    <div class="otherAmenityContainer">
                        <div class="amenityMainImage"><img src="/images/home-grid1.jpg" alt=""></div>
                        <div class="amenityGalleries">
                            <div class="amenityGrid gridImage1"><img src="/" alt=""></div>
                            <div class="amenityGrid gridImage2"><img src="/" alt=""></div>
                        </div>
                    </div>
                    <div class="otherAmenityDetails">
                        <h1> </h1>
                        <div class="icons"> 
                            <i class="fa fa-table"> </i>
                            <i class="fa fa-decoration"> </i>
                        </div>
                        <p> </p>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="/javascript/amenities.js"></script>
</body>
</html>