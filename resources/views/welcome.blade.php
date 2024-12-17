<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <title>Por La Bahia</title>
        <link rel="stylesheet" href="{{asset('styles/welcome.css')}}">
        <link rel="stylesheet" href="{{asset('styles/header.css')}}">
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
                integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
                crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
        <script src="/javascript/welcome.js"></script>
    </head>
<body>
    <div class="background">
        <div class="overlay">
            @include('header')
            <main>
                <h2 class="title">Welcome to Por La Bahia</h2>
                <h1 class="tagline">Your <span>Home</span> by <br> the Bay.</h1>
                <button class="explore"><i class="fa fa-home"></i>  Explore Houses</button>
                <div class="date-section">
                <div class="choose-date">
                    <a href="#"><i class="fa fa-calendar"></i> Check-in</a>
                    <a href="#"><i class="fa fa-calendar"></i> Check-Out</a>
                    <a href="#"><i class="fa fa-user"></i> Guests</a>
                </div>
                <div class="check-availability">Check Availability</div>
                </div>
            </main>
        </div>
    </div>
    <section class="about-layout">
        <div class="about-text">
            <h1>Por La Bahia</h1>
            <h4>ABOUT US</h4>
            <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Why do we use it?
                it look like readable English sometimes on purpose (injected humour and the like).</p>
            <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages (injected humour and the like).</p>

            <div class="small-nav">
                <div> <img src="/images/home-09.svg" alt="" > Accommodations </div>
                <div> <img src="/images/wifi-square.svg" alt="" > Enjoy Free Wifi</div>
                <div> <img src="/images/car-parking-02.svg" alt="" > Parking Space </div>
                <div> <img src="/images/pool.svg" alt="" > Relaxing Pool </div>
            </div>
            <button class="readmore"><img src="/images/book.svg" alt="" class="readmoreImage"> Read More</button>
        </div>
        <div class="grid-images">
            <div class="item item1"><img src="{{asset("/images/edited.jpg")}}" alt=""></div>
            <div class="item item2"><img src="{{asset("/images/home-grid4.jpg")}}" alt=""></div>
            <div class="item item3"><img src="{{asset("/images/home-grid3.jpg")}}" alt=""></div>
        </div>
    </section>

    <section class="resort-facilities">
        <div class="resort">
            <div class="facilities">
                <img src="/images/lineLeft.svg" alt="" class="lineLeft">
                <h1>Resort Facilities</h1>
                <img src="/images/lineRight.svg" alt="" class="lineRight">
            </div>

            <img src="/images/circle-arrow-right-02.svg" alt="" class="arrowLeft">
            <img src="/images/circle-arrow-right-01.svg" alt="" class="arrowRight">
            <h5>OUR AMENITIES</h5>
            <div class="amenities-boxes">
                <div class="amenities-box">
                    <img src=" " alt="">
                    <h3> </h3>
                    <p> </p>
                    <button class="amenities-readmore"> Read More</button>
                </div>
            </div>
        </div>
    </section>
    <section class="resort-houses">
        <div class="resort-houses-text">
            <div class="resort-houses-box">
                <div class="resort-houses-title">
                    <img src="/images/lineLeft.svg" alt="" class="lineLeft">
                    <h1>Resort Houses</h1>
                    <img src="/images/lineRight.svg" alt="" class="lineRight">
                </div>
                <h3>OUR ACCOMMODATIONS</h3>
                <p> Lorem Ipsum is simply dummy passages, and more recently with desktop publishing software like Aldus.
                    Lorem Ipsum is simply dummy text and more recently with desktop publishing software like Aldus. Lorem Ipsum is simply dummy text and more recently with desktop publishing.
                </p>
                <p> Lorem Ipsum is simply dummy passages, and more recently with desktop publishing software like Aldus.
                    Lorem Ipsum is simply dummy text and more recently with desktop publishing.
                </p>
            </div>
            <div class="other-houses-container">
                <div class="other-houses-arrows">
                    <h2>OTHER HOUSES</h2>
                    <div class="arrows">
                        <img src="/images/circle-arrow-right-02.svg" alt="" class="arrowL">
                        <img src="/images/circle-arrow-right-01.svg" alt="" class="arrowR">
                    </div>
                </div>
                <div class="other-houses">
                    <div class="house1">
                        <img src=" " alt="">
                        <h4> </h4>
                        <h6> <i class="fa fa-bed"> </i> 2</h6>
                        <p> </p>
                        <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="resort-houses-image">
            <img id="large-image" src=" " alt="">
                <h1> </h1>
                <h6> <i class="fa fa-bed"> </i> 2</h6>
                <p> </p>
                <hr>
                <div class="buttons">
                    <div class="price"> ₱</div>
                    <button class="book-this"><img src="/images/Icon.svg" alt="" class="bookImage"> Book This</button>
                </div>
        </div>
    </section>
   <section class="quick-video">
        <div class="video-title">
            <img src="/images/lineLeft.svg" alt="" class="lineLeft">
            <h1>Quick Video</h1>
            <img src="/images/lineRight.svg" alt="" class="lineRight">
        </div>
        <h2>LET US TAKE YOU TO A QUICK TOUR!</h2>

        <div class="video-container">
            <video id="video" src="/video/PorLaBahia.mp4" width="500" height="300"></video>
            <button id="playPauseBtn" class="play-pause-btn"> <i class="fa fa-youtube-play"></i> </button>
        </div>
   </section>

   <section class="choose-us"> 
    <img src="/images/Rectangle 97.svg" alt="" class="testimonial-bg">
    <img src="/images/10.svg" alt="" class="choose-bg coconut">
    <img src="/images/11.svg" alt="" class="choose-bg board">
        <div class="choose-us-title">
            <img src="/images/redLineLeft.svg" alt="" class="lineLeft">
                <h1>Why Choose Us</h1>
                <img src="/images/redLineRight.svg" alt="" class="lineRight">
            </div>
            <h3>ESCAPE, RELAX AND REDISCOVER COMFORT.</h3>
            <img src="/images/redArrowLeft.svg" alt="" class="red-arrowLeft">
            <img src="/images/redArrowRight.svg" alt="" class="red-arrowRight">
            
            <div class="choose-slider">
                <div class="choose-container"> 
                    <h4>Exclusive Amenities for Your Comfort</h4>
                    <p>Enjoy access to private pools, videoke nights, and exceptional catering services.</p>
                </div>
                <div class="choose-container"> 
                    <h4>Ideal Location for Every Occasion</h4>
                    <p>Whether it's a family getaway, a romantic escape, or a celebration, our resort offers the perfect backdrop for your special moments.</p>
                </div>
                <div class="choose-container"> 
                    <h4>Memories Made by the Waves</h4>
                    <p>Every stay is an opportunity to create cherished moments with loved ones.</p>
                </div>
                <div class="choose-container"> 
                    <h4>A Getaway That Feels Like Home</h4>
                    <p>
                    Experience the warmth and comfort of home with the added luxury of breathtaking coastal views.</p>
                </div>
            </div>

            <h5>OUR CLIENTS TESTIMONIALS</h5>
            <div class="testimonial">
                <div class="testimonial-container">
                    <div class="profile-name">
                        <img src="/images/image1.jpg" alt="">
                        <h6 class="name">Tonet Pacumbaba</h6>
                    </div>
                    <p>Tonet ngaubs ingon anin taa Lorem Ipsum passages, and more recently with desktop publishing software Lorem Ipsum passages, a publishing software</p>
                </div>
                <div class="testimonial-container">
                    <div class="profile-name">
                        <img src="/images/image1.jpg" alt="">
                        <h6 class="name">Rico Baroto</h6>
                    </div>
                    <p>Tonet ngaubs ingon anin taa Lorem Ipsum passages, and more recently with desktop publishing software Lorem Ipsum passages, a publishing software</p>
                </div>
                <div class="testimonial-container">
                    <div class="profile-name">
                        <img src="/images/image1.jpg" alt="">
                        <h6 class="name">Malie Batang Bronze</h6>
                    </div>
                    <p>Tonet ngaubs ingon anin taa Lorem Ipsum passages, and more recently with desktop publishing software Lorem Ipsum passages, a publishing software</p>
                </div>
            </div>
   </section>

    <section class="location">
        <div class="location-image">
            <img src="/images/Rectangle 98.svg" alt="">
            <img src="/images/Vector.svg" alt="" class="vector">
            <div class="location-box">
                <img src="/images/maps-search.svg" alt="" class="map">
                <div class="address">
                    <h1>OUR LOCATION</h1>
                    <h6> Santo Niño, Malitbog, Southern Leyte, Philippines</h6>
                </div>
            </div>
        </div>
        <div class="get-in-touch">
            <div class="get-in-touch-title">
                <img src="/images/lineLeft.svg" alt="" class="getLineLeft">
                    <h1> Get in Touch</h1>
                <img src="/images/lineRight.svg" alt="" class="getLineRight">
            </div> 
            
        <h4>CONNECT TO US IF YOU HAVE PROBLEM</h4>
            <form action="">
                <div class="input-container">
                    <i class="fa fa-user"></i>
                    <input type="text" id="contact_name" name="contact_name" placeholder="Contact Name">
                </div>
                <div class="input-container">
                    <i class="fa fa-road"></i>
                    <input type="text" id="street" name="street" placeholder="Street">
                </div>
                <div class="input-container city">
                    <i class="fa fa-institution"></i>
                    <input type="text" id="city" name="city" placeholder="City">
                </div>
                <div class="input-container zipcode">
                    <i class="fa fa-building"></i>
                    <input type="number" id="zipcode" name="zipcode" placeholder="Zipcode">
                </div>
                <div class="input-container">
                    <i class="fa fa-phone"></i>
                    <input type="number" id="contact_number" name="contact_number" placeholder="Contact Number">
                </div>
                <div class="input-container idea">
                    <i class="fa fa-commenting"></i>
                    <textarea id="idea" name="idea" rows="4" cols="50" placeholder="What's on your mind?"></textarea>
                </div>
                <button class="submit"><i class="fa fa-paper-plane"></i> SUBMIT</button>
            </form>

        </div>
   </section>
</body>

</html>
