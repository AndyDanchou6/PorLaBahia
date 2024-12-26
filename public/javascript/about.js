$(document).ready(function () {
   const header = document.getElementById('header');
        window.addEventListener('scroll', () => {
        if(window.scrollY > 700) {
            header.classList.add('scrolled');
        }else{
            header.classList.remove('scrolled');
        }
   });
   $.ajax({
    url: '/api/amenities',
    method: 'GET',
    dataType: 'json',
    success: function (amenities) {
        if(amenities.length > 0){
            const amenitiesAbout = $('.about-image-slider');
            amenitiesAbout.html('');

            amenities.forEach(function (amenity) {
                const amenityAbout = `
                <div class="about-item" display="block">
                    <img src="/storage/${amenity.main_image}" alt="${amenity.amenity_name}">
                </div>
                `;
                amenitiesAbout.append(amenityAbout)
            });
            amenitiesAbout.slick({
                infinite: true,
                slidesToShow:4, 
                slidesToScroll:1,
                dots: true,
            });
            if(amenities.length <= 4){
                $('.arrowLeft, .arrowRight').hide();
            }else{
                $('.arrowLeft, .arrowRight').show();
            }
            $('.arrowLeft').click(function () {
                amenitiesAbout.slick('slickPrev');
            });
            $('.arrowRight').click(function (){
                amenitiesAbout.slick('slickNext');
            });
            }else{
                $('.arrowLeft, .arrowRight').hide();
            }
        },
   });
})