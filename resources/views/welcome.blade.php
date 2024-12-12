<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">  
        <title>Por La Bahia</title>
        <link rel="stylesheet" href="{{asset('styles/welcome.css')}}">
        <link rel="stylesheet" href="{{asset('styles/header.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
                integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
                crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

        @vite('resources/js/app.js')
        <script src="/javascript/welcome.js"></script>
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
            <a href="#"><i class="fa fa-calendar"></i> Check-in</a>
            <a href="#"><i class="fa fa-calendar"></i> Check-Out</a>
            <a href="#"><i class="fa fa-user"></i> Guests</a>
        </div>
        <div class="check-availability">Check Availability</div>
    </section>

    <section class="about-layout">
            <div class="about-text">
                <h1>Por La Bahia</h1>
                <h4>ABOUT US</h4>
                <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Why do we use it?
                     it look like readable English sometimes on purpose (injected humour and the like).</p>
                <p> Lorem Ipsum is simply dummy text of Lorem Ipsum passages (injected humour and the like).</p>
                
                <div class="small-nav">
                <div> <i class="fa fa-home"></i> Accommodations </div>
                <div> <i class="fa fa-wifi"></i> Enjoy Free Wifi</div>
                <div> <i class="fa fa-car"></i> Parking Space </div>
                <div> <i class="fa fa-tint"></i> Relaxing Pool </div>
            </div>
            <button class="readmore"><img src="/images/book.svg" alt="" class="readmoreImage"> Read More</button>
            </div>

            <div class="grid-images">
                <div class="item item1"><img src="{{asset("/images/image1.jpg")}}" alt=""></div>
                <div class="item item2"><img src="{{asset("/images/image2.jpg")}}" alt=""></div>
                <div class="item item3"><img src="{{asset("/images/image3.jpg")}}" alt=""></div>
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
                Lorem Ipsum is simply dummy text and more recently with desktop publishing software like Aldus.
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
                        <div class="house-context">
                            <img src="/images/image1.jpg" alt="House Image 1">
                            <h4>Dominic House</h4>
                            <h6> <i class="fa fa-bed"> </i> 2</h6>
                            <p>Lorem Ipsum is simply dummy publishing Aldus dummy publishing software like Aldus.</p>
                            <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
                        </div>
                    </div>
                    <div class="house1">
                        <div class="house-context">
                            <img src="/images/image1.jpg" alt="House Image 1">
                            <h4>Dominic House</h4>
                            <h6> <i class="fa fa-bed"> </i> 2</h6>
                            <p>Lorem Ipsum is simply dummy publishing Aldus dummy publishing software like Aldus.</p>
                            <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
                        </div>
                    </div>
                    <div class="house1">
                        <div class="house-context">
                            <img src="/images/image1.jpg" alt="House Image 1">
                            <h4>Dominic House</h4>
                            <h6> <i class="fa fa-bed"> </i> 2</h6>
                            <p>Lorem Ipsum is simply dummy publishing Aldus dummy publishing software like Aldus.</p>
                            <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
                        </div>
                    </div>
                </div>
            </div>       
        </div>
        <div class="resort-houses-image">
            <img src="/images/image1.jpg" alt="">
                <h1>Paulo House</h1>
                <i class="fa fa-bed"> </i>
                <p>Lorem Ipsum is simply dummy text of Lorem Ipsum passages, and more recently recently with desktop publishing software like Aldus and more recently with desktop publishing software like Aldus.</p>
                <hr>
            <div class="buttons">
                <div class="price"><i class="fa fa-money"></i> 5,500.00</div>
                <button class="book-this"><img src="/images/Icon.svg" alt="" class="bookImage"> Book This</button>
            </div>
        </div>
    </section>
   <section class="video">
        <div class="video-title">
            <img src="/images/lineLeft.svg" alt="" class="lineLeft">
                <h1>Quick Video</h1>
            <img src="/images/lineRight.svg" alt="" class="lineRight">
        </div>
        <h2>LET US TAKE YOU TO A QUICK TOUR!</h2>
   </section>

   <section class="choose-us">
    <div class="bg-overlay">
        <div class="choose-us-title">
            <img src="/images/redLineLeft.svg" alt="" class="lineLeft">
                <h1>Why Choose Us</h1>
            <img src="/images/redLineRight.svg" alt="" class="lineRight">
        </div>
            <h3>ESCAPE, RELAX AND REDISCOVER COMFORT.</h3>
            <div class="choose-slider">

            <img src="/images/redArrowLeft.svg" alt="" class="red-arrowLeft">
                <div> 
                    <h4>Exceptional Homes</h4>
                    <p>Lorem Ipsum passages, and more recently with desktop publishing software like Aldus</p>
                </div>
                <div> 
                    <h4>Redefining Hospitality</h4>
                    <p>Lorem Ipsum passages, and more recently with desktop publishing software like Aldus</p>
                </div>
                <div> 
                    <h4>Exceptional Homes</h4>
                    <p>Lorem Ipsum passages, and more recently with desktop publishing software like Aldus</p>
                </div>
                <img src="/images/redArrowRight.svg" alt="" class="red-arrowRight">
            </div>
            
         <h5>OUR CLIENTS TESTIMONIALS</h5>
            <div class="testimonial">
                <div class="testimonial-container">
                    <div class="profile-name">
                        <img src="/images/image1.jpg" alt="">
                        <h6 class="name">Tonet Magparoc</h6>
                    </div>
                    <p>Tonet ngaubs ingon anin taa Lorem Ipsum passages, and more recently with desktop publishing software Lorem Ipsum passages, a publishing software</p>
                </div>
                <div class="testimonial-container">
                    <div class="profile-name">
                        <img src="/images/image1.jpg" alt="">
                        <h6 class="name">Tonet Magparoc</h6>
                    </div>
                    <p>Tonet ngaubs ingon anin taa Lorem Ipsum passages, and more recently with desktop publishing software Lorem Ipsum passages, a publishing software</p>
                </div>
                <div class="testimonial-container">
                    <div class="profile-name">
                        <img src="/images/image1.jpg" alt="">
                        <h6 class="name">Tonet Magparoc</h6>
                    </div>
                    <p>Tonet ngaubs ingon anin taa Lorem Ipsum passages, and more recently with desktop publishing software Lorem Ipsum passages, a publishing software</p>
                </div>
            </div>
            </div>
   </section>

   <section class="location">
        <div class="location-image">
            <img src="/images/location.jpg" alt="">
            <div class="location-box">
                <i class="fa fa-map-marker"></i>
                <h6> Santo Nino, Malitbog, Southern Leyte, Philippines</h6>
            </div>
        </div>
        <div class="get-in-touch">
            <div class="get-in-touch-title">
                <!-- <img src="/images/lineLeft.svg" alt="" class="getLineLeft"> -->
                    <h1> Get in Touch</h1>
                <!-- <img src="/images/lineRight.svg" alt=""> -->
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
                <div class="input-container">
                    <i class="fa fa-commenting"></i>
                    <input type="text" id="idea" name="idea" placeholder="What's on your mind?"><br>
                </div>
                <button class="submit"><i class="fa fa-paper-plane"></i> SUBMIT</button>
           </form>
           
        </div>
   </section>
   <section class="footer">
        @include('footer')  
   </section> 
</body>
</html>
  