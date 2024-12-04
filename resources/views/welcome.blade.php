<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
    <link rel="stylesheet" href="{{asset('styles/welcome.css')}}">
    <title>Por La Bahia</title>
</head>
<body>
    <div class="container">
            <div class="background">
    <div class="overlay"></div>
    @include('header')
    <div class="lines">
        <div class="line1"></div>
        <div class="line2"></div>
    </div>
    <main>
        <h2 class="title">Welcome to Por La Bahia</h2>
        <h1 class="tagline">Your <span>Home</span> by <br> the Bay.</h1>
        <button class="explore"><i class="fa fa-home"></i>  Explore Houses</button>
    </main>
    </div>
    </div>

    <section class="date-section">
        <div class="choose-date">
            <i class="fa fa-calendar"> Check-in</i> 
            <i class="fa fa-calendar"> Check-Out</i>
            <i class="fa fa-user"> Guests</i>
        </div>
        <div class="check-availability"> <i class="fa fa-check"> Check Availability</i></div>
    </section>

    <section class="section2">
        <div class="layout">
            <div class="about-text">
                <h1>Por La Bahia</h1>
                <h4>ABOUT US</h4>
                <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. <br> Why do we use it?
                     it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
            <div class="small-nav">
                <div> <i class="fa fa-home"></i> Accommodations </div>
                <div> <i class="fa fa-wifi"></i> Enjoy Free Wifi</div>
                <div> <i class="fa fa-car"></i> Parking Space </div>
                <div> <i class="fa fa-tint"></i> Relaxing Pool </div>
            </div>
            <button class="readmore"><i class="fa fa-book"></i> Read More</button>
            </div>

            <div class="grid-images">
            <div class="item item1"><img src="{{asset("/images/image1.jpg")}}" alt=""></div>
            <div class="item item2"><img src="{{asset("/images/image2.jpg")}}" alt=""></div>
            <div class="item item3"><img src="{{asset("/images/image3.jpg")}}" alt=""></div>
            </div>
        </div>
    </section>

    <section class="resort-facilities">
        <div class="resort">
            <div class="circles circle"></div>
            <div class="thinline line3"></div>
            <h1>Resort Facilities</h1>
            <div class="circles circle1"></div>
            <div class="thinline line4"></div>
            
            <h5>OUR AMENITIES</h5>
            <div class="amenities-boxes">
                <div class="amenities-box"> 
                    <img src="/images/image1.jpg" alt="">
                    <h3>Parking Space</h3>
                    <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus. </p>
                    <button><i class="fa fa-book"></i> Read More</button>
                </div>
                <div class="amenities-box">
                    <img src="/images/image2.jpg" alt="">
                    <h3>Accommodation</h3>
                        <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.</p>
                    <button><i class="fa fa-book"></i> Read More</button>
                 </div>
                <div class="amenities-box"> 
                    <img src="/images/image3.jpg" alt="">
                    <h3>Exclusive Event Venue</h3>
                        <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.</p>
                    <button><i class="fa fa-book"></i> Read More</button>
                
                </div>
            </div>
        </div>
    </section>
    <section class="resort-houses">
        <div class="resort-houses-text">
            <div class="circles circle2"></div>
                <div class="thinline line5"></div>
                    <h1>Resort Houses</h1>
                <div class="circles circle3"></div>
            <div class="thinline line6"></div>
            <h3>OUR ACCOMMODATIONS</h3>
            <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.
            Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.
            Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.
            </p>
            <h2>OTHER HOUSES</h2>
            <div class="other-houses">
                <div class="house1"> </div>
                <div class="house1"> </div>
                <div class="house1"> </div>
            </div>
        </div>

        <div class="resort-houses-images">

        </div>
    </section>
   
</body>
</html>