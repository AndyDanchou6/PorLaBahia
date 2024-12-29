$(document).ready(function () {
    $(document).ajaxStart(function(){
        $('#preloader').fadeIn();
    });
    $(document).ajaxStop(function(){
        $('#preloader').fadeOut();
    });
    const header = document.getElementById("header");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 500) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    });

    $.ajax({
        url: 'api/accommodations',
        method: 'GET',
        success: function (accommodation) {
            let accommodationContainer = $('.accommodationLists');
            accommodationContainer.html(''); 

            accommodation.forEach((data) => {
                const sliderId = `${data.id}`;
                const accommodationDetails = `
                <div class="accWrapper">
                    <div class="accommodationDetails">
                        <h1>${data.room_name}</h1>
                        <div class="icon">
                        <i class="fa fa-bed"> </i>
                        <p> 2 Bedroom</p>
                        </div>
                        <p>${data.description}</p>
                        <hr>
                        <div class="price-book">
                            <div class="accommodationPrice"> 
                                <div><h5>Weekdays Price:</h5>
                                <p>₱${data.weekday_price}</p></div>
                                <div><h5>Weekends Price:</h5>
                                <p>₱${data.weekend_price}</p></div>
                            </div>
                            <button class="accommodationBook"> 
                                <img src="/images/icon.svg" alt=""> Book This
                            </button>
                        </div>
                        <div class="accommodationSlider" id="${sliderId}">
                        </div>
                    </div>
                    <div class="accommodationImage">
                        <div class="imageContainer">
                            <img src="/storage/${data.main_image}" alt="" class="accImage">
                        </div>
                    </div>
                </div>
                `;
                accommodationContainer.append(accommodationDetails);

                const currentSlider = $(`#${sliderId}`); 
                const imageGallery = data.galleries;

                imageGallery.forEach((image) => {
                    if (image.galleries_id == data.id) { 
                        currentSlider.append(`
                            <div class="sliderImage">
                                <img src="/storage/${image.image}" alt="Gallery Image">
                            </div>
                            
                        `);
                    }
                });
                currentSlider.slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    prevArrow: '<img src="/images/circle-arrow-right-02.svg" alt="" class="leftArrow">',
                    nextArrow: '<img src="/images/circle-arrow-right-01.svg" alt="" class="rightArrow">',
                });
                if(imageGallery.length <= 3){
                    currentSlider.find('.leftArrow, .rightArrow').hide();
                }else{
                    currentSlider.find('.leftArrow, .rightArrow').show();
                }
                const accImageContainer = $('.accommodationImage');
                
                currentSlider.on('click', '.sliderImage', function(){
                    const clickedImage = $(this).find('img');
                    const imageGallerySrc = clickedImage.attr('src');
                    const parentWrapper = $(`#${sliderId}`).closest('.accWrapper');
                    parentWrapper.find('.accommodationImage .imageContainer img').attr('src', imageGallerySrc);
                    
                    $('.sliderImage img').removeClass('active');
                    clickedImage.addClass('active');
                })
            });
        },
    });
});