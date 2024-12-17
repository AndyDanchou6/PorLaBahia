<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accommodations | Por La Bahia</title>
    <link rel="stylesheet" href="{{asset('/styles/accommodation.css')}}">
    <script src="/javascript/accommodation.js"></script>
</head>
<body>
    <div class="background">
        <div class="overlay">
            @include('header')
            <main>
                <h2 class="accommodation-title">Welcome to Por La Bahia</h2>
                <h1 class="accommodation-subtitle"><span>Our Accommodations:</span><br>Where comfort meets<br>convenience.</h1>

                <div class="accommodationContainer">
                    <div class="accommodationTitle">
                        <img src="/images/lineLeft.svg" alt="" class=" ">
                        <h1>Our Resort Houses</h1>
                        <img src="/images/lineRight.svg" alt="" class=" ">
                    </div>
                    <h3>COMFORT, STYLE, AND SERENITY.</h3>

                    <div class="accommodation1">
                        <div class="accommodation1Text">
                            <h1>Paulo House</h1>
                            <i class="fa fa-bed"> </i>
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            <hr>
                            <div class="priceB">
                                <div class="accommodationPrice">₱ </div>
                                <button class="accommodationBook">Book This</button>
                            </div>
                            <img src="/images/circle-arrow-right-02.svg" alt="" class="leftArrow">
                            <img src="/images/circle-arrow-right-01.svg" alt="" class="rightArrow">
                            <div class="accommodationSlider">
                                <div class="sliderImage"> <img src="/images/image1.jpg" alt=""> </div>
                                <div class="sliderImage"> <img src="/images/image2.jpg" alt=""> </div>
                                <div class="sliderImage"> <img src="/images/image3.jpg" alt=""> </div>
                            </div>
                        </div>
                        <div class="accommodation1Image">
                            <div class="imageContainer"><img src="/images/home-grid1.jpg" alt=""></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>