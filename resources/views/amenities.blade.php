<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amenities | Por La Bahia</title>
    <link rel="stylesheet" href="/styles/amenities.css">
</head>
<body>
    <div class="background">
        <div class="overlay">
            @include('header')
            <main>
                <h2 class="amenity-title">Welcome to Por La Bahia</h2>
                <h1 class="amenity-subtitle"><span>Our Amenities:</span><br>Designed for your comfort<br>and enjoyment.</h1>

                <div class="firstAmenity">
                    <div class="firstAmenityImage">
                        <img src="/images/home-grid2.jpg" alt="">
                    </div>
                    <div class="firstAmenityDetails">
                        <h1>Accommodations</h1>
                        <i class="fa fa-bed"></i>
                        <p>Each accommodation package includes essential beddings and toiletries to ensure your comfort. For your convenience, we provide 2 bath towels, 1 roll of toilet paper, and 2 small body soaps with shampoo. Each bed comes with 1 bedsheet, 1 blanket, and 2 pillows for a restful stay.</p><p> Please note that additional floor foam beddings are not included for free, but you have the option to rent them if needed.</p>
                        <div class="container">
                            <button class="goToAccommodation">Go to Accommodations</button>
                            <div class="price"> P</div>
                        </div>                    
                    </div>
                </div>
                <div class="secondAmenity">
                    <div class="secondAmenityGrid">
                        <div class="amenityGrid gridImage1"><img src="/images/home-grid2.jpg" alt=""></div>
                        <div class="amenityGrid gridImage2"><img src="/images/home-grid2.jpg" alt=""></div>
                        <div class="amenityGrid gridImage3"><img src="/images/home-grid2.jpg" alt=""></div>
                    </div>
                    <div class="secondAmenityDetails">
                        <h1>Malie Batang Bronze</h1>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>