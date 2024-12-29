<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accommodations | Por La Bahia</title>
    <link rel="stylesheet" href="{{asset('/styles/accommodation.css')}}">
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
                <h2 class="accommodation-title">Welcome to Por La Bahia</h2>
                <h1 class="accommodation-subtitle"><span>Our Accommodations:</span><br>Where comfort meets<br>convenience.</h1>

                <div class="accommodationContainer">
                    <div class="accommodationTitle">
                        <img src="/images/lineLeft.svg" alt="" class=" ">
                        <h1>Our Resort Houses</h1>
                        <img src="/images/lineRight.svg" alt="" class=" ">
                    </div>
                    <h3>COMFORT, STYLE, AND SERENITY.</h3>

                    <div class="accommodationLists">
                        <div class="accWrapper">
                            <div class=" accommodationDetails">
                                <h1> </h1>
                                <i class="fa fa-bed"> </i>
                                <p> </p>
                                <hr>
                                <div class="price-book">
                                    <div class="accommodationPrice"> 
                                        <div>
                                        <h5>Weekdays Price:</h5>
                                        <p></p>
                                        </div>
                                        <div>
                                        <h5>Weekends Price:</h5>
                                        <p> </p>
                                        </div>
                                    </div>
                                    <button class="accommodationBook"> <img src="/images/icon.svg" alt=""> Book This</button>
                                </div>
                                <div class="accommodationSlider">
                                    <img src="/images/circle-arrow-right-02.svg" alt="" class="leftArrow">
                                        <div class="sliderImage"> <img src="" alt=""> </div>
                                    <img src="/images/circle-arrow-right-01.svg" alt="" class="rightArrow">
                                </div>
                            </div>
                            <div class="accommodationImage">
                                <div class="imageContainer">
                                    <img src=" " alt="" class="accImage">
                                </div>
                            </div>
                        </div>
                    </div> 
            </main>
        </div>
    </div>
    <script src="/javascript/accommodation.js"></script>
</body>
</html>