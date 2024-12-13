$(document).ready(function () {
    $.ajax({
        url: '/api/amenities', 
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                const amenitiesContainer = $('.amenities-boxes');

                amenitiesContainer.html('');

                data.forEach(function (amenity) {
                    const amenityBox = `
                        <div class="amenities-box">
                            <img src="${amenity.main_image}" alt="${amenity.amenity_name}">
                            <h3>${amenity.amenity_name}</h3>
                            <p>${amenity.description}</p>
                            <button class="readmore"> Read More</button>
                        </div>
                   `;

                    amenitiesContainer.append(amenityBox);
                });
                amenitiesContainer.slick({
                    infinite:true,
                    slidesToShow:3,
                    slidesToScroll:1,
                    dots:true,
                });
                $('.arrowLeft').click(function () {
                    amenitiesContainer.slick('slickPrev'); 
                });

                $('.arrowRight').click(function () {
                    amenitiesContainer.slick('slickNext');
                });
            }
        },
    });

    $.ajax({
        url:'/api/accommodations',
        method:'GET',
        dataType:'json',
        success: function(accommodation){
            if(accommodation.length > 0){
                const accommodationContainer = $('.other-houses');
                accommodationContainer.html('');

                accommodation.forEach(function(house){
                    const accommodationBox = `
                    <div class="house1">
                        <div class="house-context">
                            <img class="clickable" src="${house.main_image}" alt="${house.room_name}">
                            <h4>${house.room_name}</h4>
                            <h6> <i class="fa fa-bed"> </i> </h6>
                            <p>${house.description}</p>
                            <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
                        </div>
                    </div>
                        `;

                    accommodationContainer.append(accommodationBox);
                });
                const largeImage = document.getElementById('large-image');
                const titleElement = document.querySelector('.resort-houses-image h1');
                const descriptionElement = document.querySelector('.resort-houses-image p');
                const priceElement = document.querySelector('.resort-houses-image .price');
                const firstHouse = accommodation[0];
                
                largeImage.src = firstHouse.main_image;
                titleElement.textContent = firstHouse.room_name;
                descriptionElement.textContent = firstHouse.description;
                priceElement.textContent = firstHouse.price;
            }
        },
    });

    $(document).ready(function(){
        $('.choose-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
        });
        $('.red-arrowLeft').click(function(){
            $('.choose-slider').slick('slickPrev');
        })
        $('.red-arrowRight').click(function(){
            $('.choose-slider').slick('slickNext');
        })
    });

    const video = document.getElementById('video');
    const playPauseBtn = document.getElementById('playPauseBtn');

    playPauseBtn.addEventListener('click', () => {
        if (video.paused) {
            video.play();
        } else {
            video.pause();
        }
    });
});