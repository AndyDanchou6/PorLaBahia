$(document).ready(function () {
    const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 600) {
              header.classList.add('scrolled'); 
            } else {
              header.classList.remove('scrolled');
            }
    });
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
                            <img src="/storage/${amenity.main_image}" alt="${amenity.amenity_name}">
                            <h3>${amenity.amenity_name}</h3>
                            <p>${amenity.description}</p>
                            <button class="amenities-readmore"> Read More</button>
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
                    <div class="house1" 
                            src="/storage/${house.main_image}"
                            data-room_name="${house.room_name}" 
                            data-description="${house.description}" 
                            data-weekday_price="${house.weekday_price}">
                        <img src="/storage/${house.main_image}" 
                        <h4>${house.room_name}</h4>
                        <h6> <i class="fa fa-bed"> </i> 2</h6>
                        <p>${house.description}</p>
                        <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
                    </div>
                        `;
                    accommodationContainer.append(accommodationBox);
                });
                accommodationContainer.slick({
                    infinite: true,
                    slidesToScroll:1,
                    slidesToShow:3,
                })
                $('.arrowL').click(function (){
                    accommodationContainer.slick('slickPrev');
                });
                $('.arrowR').click(function (){
                    accommodationContainer.slick('slickNext');
                });
                const largeImage = document.getElementById('large-image');
                const titleElement = document.querySelector('.resort-houses-image h1');
                const descriptionElement = document.querySelector('.resort-houses-image p');
                const priceElement = document.querySelector('.resort-houses-image .price');
                const firstHouse = accommodation[0];
                
                largeImage.src = `/storage/${firstHouse.main_image}`;
                titleElement.textContent = firstHouse.room_name;
                descriptionElement.textContent = firstHouse.description;
                priceElement.textContent = `₱ ${firstHouse.weekday_price}`;

                $('.other-houses').on('click', '.house1', function() {
                    const clickedImage = $(this);
                    const newImageSrc = clickedImage.attr('src');
                    const newRoomName = clickedImage.data('room_name');
                    const newDescription = clickedImage.data('description');
                    const newPrice = clickedImage.data('weekday_price');
    
                    largeImage.src = newImageSrc;
                    titleElement.textContent = newRoomName;
                    descriptionElement.textContent = newDescription;
                    priceElement.textContent = `₱ ${newPrice}`;
                });
            }else{
                $('.other-houses').html('<i><p>Please add accommodation/s.</p></i>');
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