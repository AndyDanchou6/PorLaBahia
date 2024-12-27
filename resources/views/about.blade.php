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
                    <h2 class="about-title">Welcome to Por La Bahia</h2>
                     <h1 class="about-paragraph"><span>About Us:</span> Get to <br>Know the Heart<br>Behind What We Do.</h1> 
                </div>
                <img src="/images/circle-arrow-right-02.svg" alt="" class="arrowLeft">
                <img src="/images/circle-arrow-right-01.svg" alt="" class="arrowRight">
                <div class="about-image-slider">
                    <div class="about-item"><img src=" " alt=""></div>
                </div>
                <div class="history">
                    <div class="historydiv">
                        <img src=" " alt="">
                    </div>
                    <div class="know-more-text">
                        <div>
                            <p> </p>
                        </div>
                    </div>
                    <div class="flex-images">
                        <div>
                            <h3></h3>
                        </div>
                    </div>
                    <div class="history-grid">
                        <div class="history-grid-image">
                            <img src=" " alt="">
                        </div>
                        <div class="history-porla">
                            <h1>ha  </h1>
                            <p>klsda </p>
                        <div class="history-operation">
                            <div class="history-operation-title">
                            <img src="/images/i.svg" alt="">
                            <h6> </h6>
                            </div>
                            <p> </p>
                        </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>