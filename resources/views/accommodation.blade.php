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

                    <div class="accommodationLists">
                        <div class="accommodation1Details">
                            <h1>Paulo House</h1>
                            <i class="fa fa-bed"> </i>
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            <hr>
                            <div class="price-book1">
                                <div class="accommodationPrice1">₱ </div>
                                <button class="accommodationBook1"> <img src="/images/Icon.svg" alt=""> Book This</button>
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
                            <div class="imageContainer"><img src="/images/porlabahia.jpg" alt=""></div>
                        </div>

                        <div class="accommodation2Image">
                            <div class="image2Container"><img src="/images/home-grid2.jpg" alt=""></div>
                        </div>
                        <div class="accommodation2Details">
                            <h1> Adrian House </h1>
                            <i class="fa fa-bed"> </i>
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            <hr>
                            <div class="price-book2">
                                <div class="accommodationPrice2">₱ </div>
                                <button class="accommodationBook2"> <img src="/images/Icon.svg" alt=""> Book This</button>
                            </div>
                            <img src="/images/circle-arrow-right-02.svg" alt="" class="leftArrow2">
                            <img src="/images/circle-arrow-right-01.svg" alt="" class="rightArrow2">
                            <div class="accommodationSlider2">
                                <div class="sliderImage2"> <img src="/images/porlabahia.jpg" alt=""> </div>
                                <div class="sliderImage2"> <img src="/images/image2.jpg" alt=""> </div>
                                <div class="sliderImage2"> <img src="/images/home-grid3.jpg" alt=""> </div>
                            </div>   
                        </div> 

                        <div class="accommodation3Details">
                            <h1> Dominic House </h1>
                            <i class="fa fa-bed"> </i>
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            <hr>
                            <div class="price-book3">
                                <div class="accommodationPrice3">₱ </div>
                                <button class="accommodationBook3"> <img src="/images/Icon.svg" alt=""> Book This</button>
                            </div>
                            <img src="/images/circle-arrow-right-02.svg" alt="" class="leftArrow3">
                            <img src="/images/circle-arrow-right-01.svg" alt="" class="rightArrow3">
                            <div class="accommodationSlider3">
                                <div class="sliderImage3"> <img src="/images/porlabahia.jpg" alt=""> </div>
                                <div class="sliderImage3"> <img src="/images/image2.jpg" alt=""> </div>
                                <div class="sliderImage3"> <img src="/images/home-grid3.jpg" alt=""> </div>
                            </div>   
                        </div> 
                        <div class="accommodation3Image">
                            <div class="image3Container"><img src="/images/home-grid3.jpg" alt=""></div>
                        </div>
 
                        <div class="accommodation4Image">
                            <div class="image4Container"><img src="/images/image1.jpg" alt=""></div>
                        </div>
                        <div class="accommodation4Details">
                            <h1> Paula Ella House </h1>
                            <i class="fa fa-bed"> </i>
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            <hr>
                            <div class="price-book4">
                                <div class="accommodationPrice4">₱ </div>
                                <button class="accommodationBook4"> <img src="/images/Icon.svg" alt=""> Book This</button>
                            </div>
                            <img src="/images/circle-arrow-right-02.svg" alt="" class="leftArrow4">
                            <img src="/images/circle-arrow-right-01.svg" alt="" class="rightArrow4">
                            <div class="accommodationSlider4">
                                <div class="sliderImage4"> <img src="/images/porlabahia.jpg" alt=""> </div>
                                <div class="sliderImage4"> <img src="/images/image2.jpg" alt=""> </div>
                                <div class="sliderImage4"> <img src="/images/home-grid3.jpg" alt=""> </div>
                            </div>   
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>