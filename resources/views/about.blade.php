<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Por La Bahia </title>
    <link rel="stylesheet" href="{{asset('styles/about.css')}}">
    <link rel="stylesheet" href="{{asset('styles/header.css')}}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="/javascript/about.js"></script>
</head>

<body>
    <div class="background">
        <div class="overlay">
            @include('header')
            <main>
                <div class="section1">
                    <!-- <h2 class="about-title">Welcome to Por La Bahia</h2>
                    <h1 class="about-paragraph"><span>About Us:</span> Get to <br>Know the Heart<br>Behind What We Do.</h1> -->
                </div>
                <div class="error">

                </div>

                <img src="/images/circle-arrow-right-02.svg" alt="" class="arrowLeft">
                <img src="/images/circle-arrow-right-01.svg" alt="" class="arrowRight">
                <div class="about-image-slider">
                    <div class="about-item"><img src=" " alt=""></div>
                </div>
                <div class="history">
                    <div class="historydiv">
                        <img src="/images/image1.jpg" alt="">
                    </div>
                    <div class="know-more-text">
                        <!-- <div>
                            <h1 class="know-more-title">Know More About Us</h1>
                            <p>Lorem Ipsum is simply dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged
                                looking at its layout. <br><br> The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
                        </div> -->
                    </div>
                    <div class="flex-images">
                        <!-- <div><img src="/images/home-09.svg" alt="">
                            <h3>Accommodations</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                        </div>
                        <div><img src="/images/wifi-square.svg" alt="">
                            <h3>Enjoy Free WiFi</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                        </div>
                        <div><img src="/images/car-parking-02.svg" alt="">
                            <h3>Parking Space</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                        </div>
                        <div><img src="/images/pool.svg" alt="">
                            <h3>Relaxing Pool</h3>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                        </div> -->
                    </div>
                    <div class="history-grid">
                        <div class="history-grid-image">
                            <img src="/images/about-image.jpg" alt="">
                        </div>
                        <div class="history-porla">
                            <div class="section4data">
                                <!-- <h1>History of Por La Bahia</h1>
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                            <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into but also the leap into but also the leap into when an unknown printer took a galley of type and scrambled.</p> -->
                            </div>

                            <div class="history-operation">
                                <!-- <div class="history-operation-title">
                                    <img src="/images/i.svg" alt="">
                                    <h6>How long has this resort been in operation?</h6>
                                </div>
                                <p>Lorem Ipsum has been the industry's standard dummy text ever dummy text ever dummy text ever dummy text ever dummy text when an unknown printer took a galley of type and scrambled.</p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>


</html>