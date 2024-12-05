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
        <div class="check-availability"> <i class="fa fa-check-circle-o"> Check Availability</i></div>
    </section>

    <section class="about-layout">
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
    </section>

    <section class="resort-facilities">
        <div class="resort">
            <div class="circles circle"></div>
            <div class="thinline line3"></div>
            <h1>Resort Facilities</h1>
            <div class="circles circle1"></div>
            <div class="thinline line4"></div>
            
            <h5>OUR AMENITIES</h5>
            <i class="fa fa-chevron-circle-left"></i>
            <i class="fa fa-chevron-circle-right"></i>
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
            <div class="resort-houses-box">
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
            </div>

            <div class="other-houses-container">
                <h2>OTHER HOUSES</h2>
                <i class="fa fa-arrow-circle-o-left"></i>
                <i class="fa fa-arrow-circle-o-right"></i>
                <div class="other-houses">
                    <div class="house1">
                        <img src="/images/image1.jpg" alt="House Image 1">
                        <h4>Dominic House</h4>
                        <i class="fa fa-bed"> 2</i>
                        <p>Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.</p>
                    <button><i class="fa fa-check-circle-o"></i> Check It</button>
                    </div>
                    <div class="house1"> 
                        <img src="/images/image2.jpg" alt="House Image 1">
                        <h4>Paula Ella House</h4>
                        <i class="fa fa-bed"> 2</i>
                        <p>Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.</p>
                    <button><i class="fa fa-check-circle-o"></i> Check It</button>
                    </div>
                    <div class="house1">
                        <img src="/images/image3.jpg" alt="House Image 1">
                        <h4>Paulo House</h4>
                        <i class="fa fa-bed"> 2</i>
                        <p>Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus.</p>
                    <button><i class="fa fa-check-circle-o"></i> Check It</button>
                    </div>
                </div>
            </div>       
        </div>
        <div class="resort-houses-image">
            <img src="/images/image1.jpg" alt="">
                <h1>Paulo House</h1>
                <i class="fa fa-bed"> </i>
                <i class="fa fa-users"> </i>
                <p>Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus and more recently with desktop publishing software like Aldus.</p>
                <p>Lorem passages, and more recently publishing software like Aldus and more recently with desktop publishing software like Aldusand more recently with desktop publishing software like Aldusand more recently with desktop publishing software like Aldus  .</p>
                <hr>
            <div class="buttons">
                <button class="price"><i class="fa fa-money-bill-wave"> </i> 5,500.00</button>
                <button class="book-this"><i class="fa fa-book"> </i> Book This</button>
            </div>
        </div>
    </section>
   <section class="video">
        <div class="circles circle4"></div>
            <div class="thinline line8"></div>
                <h1>Quick Video</h1>
            <div class="circles circle5"></div>
        <div class="thinline line7"></div>
        <h2>LET US TAKE YOU TO A QUICK TOUR!</h2>
   </section>

   <section class="choose-us">
            <div class="circles circle6"></div>
                <div class="thinline line9"></div>
                    <h1>Why Choose Us</h1>
                <div class="circles circle7"></div>
            <div class="thinline line10"></div>
            <h3>ESCAPE, RELAX AND REDISCOVER COMFORT.</h3>
            <div class="choose-slider">
                <div> </div>
                <div> </div>
                <div> </div>
            </div>
   </section>
</body>
</html>