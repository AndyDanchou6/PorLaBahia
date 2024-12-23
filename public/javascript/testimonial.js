$(document).ready(function (){
    $.ajax({
        url: 'api/testimonials',
        method: 'GET',
        success: function(testimonials){
            if(testimonials.length > 0){
                const clientTestimonials = $('.testimonialSlider');
                clientTestimonials.html('');

                testimonials.forEach(function (testimonial) {
                    const testimonialBox = `
                    <div class="testimonialSlider">
                        <div class="testimonial-container">
                            <div class="profile-name">
                                <div class="profileimgContainer">
                                <img src="/storage/${testimonial.profile_image}" alt="">
                                </div>
                                <h6 class="name">${testimonial.guest.first_name} ${testimonial.guest.last_name} </h6>
                            </div>
                            <p>\"${testimonial.comment}\"</p>
                        </div>
                    </div>
                    `;
                    clientTestimonials.append(testimonialBox);
                });
                clientTestimonials.slick({
                    infinite:true,
                    slidesToScroll:1,
                    slidesToShow:3,
                    arrows: false,
                })
                $('.redArrowLeft').click(function (){
                    clientTestimonials.slick('slickPrev');
                });
                $('.redArrowRight').click(function (){
                    clientTestimonials.slick('slickNext');
                });
            }else{
                $('.testimonialSlider').html('<i>No testimonials at the moment.</i>');
            }
        }
    })
})