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



});
