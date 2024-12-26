$(document).ready(function () {
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
            if (accommodation.length > 0) {
                accommodation.forEach((acc, index) => {
                    const accommodationText = $(`.accommodation${index + 1}Details`);
                    accommodationText.html('');

                    const accommodationDetails = `
                        <div class=" accommodation accommodation${index + 1}Details" style="display: block;">
                            <h1>${acc.room_name}</h1>
                            <i class="fa fa-bed"></i>
                            <p>${acc.description}</p>
                            <hr>
                            <div class="price-book${index + 1}">

                                <div class="accommodationPrice${index + 1}">₱ ${acc.weekday_price}</div>
                                <button class="accommodationBook${index + 1}"><img src="/images/icon.svg" alt="">Book This</button>
                            </div>
                            <div class="accommodationSlider${index + 1}">
                                <img src="/images/circle-arrow-right-02.svg" alt="" class="leftArrow${index + 1}">
                                <div class="sliderImage${index + 1}">
                                    <img src="" alt="">
                                </div>
                                <img src="/images/circle-arrow-right-01.svg" alt="" class="rightArrow${index + 1}">
                            </div>
                        </div>
                    `;
                    accommodationText.append(accommodationDetails);


                    const sliderImagesContainer = $(`.accommodationSlider${index + 1}`); 
                        sliderImagesContainer.html(''); 

                        if (acc.galleries && acc.galleries.length > 0) {
                            const matchingGalleries = acc.galleries.filter(
                                (gallery) => gallery.galleries_id == acc.id
                            );
                            if (matchingGalleries.length > 0) {
                                matchingGalleries.forEach((gallery) => {
                                    sliderImagesContainer.append(

                                        `<div class="sliderImage${index + 1}">
                                            <img src="/storage/${gallery.image}" alt="Accommodation Image">
                                        </div>`

                                    );
                                }); 
                                sliderImagesContainer.slick({
                                    slidesToShow: 3,
                                    slidesToScroll: 1, 
                                    infinite: true,
                                    prevArrow: `<img src="/images/circle-arrow-right-02.svg" class="leftArrow${index + 1}" alt="Previous">`,
                                    nextArrow: `<img src="/images/circle-arrow-right-01.svg" class="rightArrow${index + 1}" alt="Next">`
                                });

                                if (sliderImagesContainer.find('.sliderImage' + (index + 1)).length < 3) {
                                    $(`.leftArrow${index + 1}, .rightArrow${index + 1}`).hide();
                                } else {
                                    $(`.leftArrow${index + 1}, .rightArrow${index + 1}`).show();
                                }
                            }

                        }

                    const accommodationList = $(`.accommodation${index + 1}Image`);
                    accommodationList.html('');
                    const accommodationImageHtml = `

                        <div class="accommodation accommodation${index + 1}Image" style="display: block;">
                            <div class="image${index + 1}Container"><img src="/storage/${acc.main_image}" alt=""></div>
                        </div>
                    `;
                    accommodationList.append(accommodationImageHtml);
                    accommodationText.show();
                    accommodationList.show();
                    
                    $(document).on('click', `.sliderImage${index+1} img`, function () {
                        const selectedImageSrc = $(this).attr('src');
                        $(`.accommodation${index+1}Image`).html(`<div class="image${index+1}Container"><img src="${selectedImageSrc}" alt=""></div>`);
                        $(`.sliderImage${index+1} img`).removeClass('active');
                        $(this).addClass('active');
                    });
                });
            } else {
                $('.accommodationLists').html('<i>No accommodation at the moment.</i>').css({
                    'display': 'flex',
                    'justify-content': 'center',

                });
            } else {
                $(".accommodationLists")
                    .html("<i>No accommodation at the moment.</i>")
                    .css({
                        display: "flex",
                        "justify-content": "center",
                    });
            }
        },
    });
});
