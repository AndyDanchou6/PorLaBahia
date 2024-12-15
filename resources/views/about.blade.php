<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('styles/about.css')}}">
    <title>About | Por La Bahia </title>
</head>
<body>
    <div class="container">
        <div class="background">
            <div class="overlay"></div>
                @include('header')
            <main>
                <h2 class="about-title">Welcome to Por La Bahia</h2>
                <h1 class="about-paragraph"><span>About Us:</span> Get to <br>Know the Heart<br>Behind What We Do.</h1>
            </main>
            <div class="about-image-slider">
                <div>
                    <img src="/images/image1.jpg" alt="">
                </div>
                <div>
                    <img src="/images/image2.jpg" alt="">
                </div>
                <div>
                    <img src="/images/image3.jpg" alt="">
                </div>
                <div>
                    <img src="/images/image1.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
    <section class="know-more">
        <div class="history">
            <div class="historydiv">
            <img src="/images/image1.jpg" alt="">
            </div>
            <div class="history-text">
                <div>
                <h1 class="know-more-title">Know More About Us</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged 
                    looking at its layout. <br><br> The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
                </div>
            </div>
            <div class="flex-images">
                <div>
                    <i class="fa fa-home"></i>
                    <h3>Accommodations</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                </div>
                <div>
                    <i class="fa fa-wifi"></i>
                    <h3>Enjoy Free WiFi</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                </div>
                <div>
                    <i class="fa fa-car"></i>
                    <h3>Parking Space</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                </div>
                <div>
                    <i class="fa fa-book"></i>
                    <h3>Relaxing Pool</h3>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into.</p>
                </div>
            </div>
            <div class="history-grid">
                <div class="history-grid-image">
                    <img src="/images/image1.jpg" alt="">
                </div>
            </div>
            <div class="history-porla">
                <h1>History of Por La Bahia</h1>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
               <div class="history-operation">
                    <h6>How long has this resort been in operation?</h6>
                    <p>Lorem Ipsum has been the industry's standard dummy text ever dummy text ever dummy text ever dummy text ever dummy text .</p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>