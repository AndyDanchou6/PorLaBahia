$(document).ready(function (){
    $.ajax({
        url: 'api/testimonials',
        method: 'GET',
        success: function(testimonials){
            if(testimonials.length > 0){
                const clientTestimonials = $('.testimonial');
                clientTestimonials.html('');

                testimonials.forEach(function (testimonial) {
                    const testimonialBox = ` 
                    <div class="testimonial-container">
                        <div class="profile-name">
                            <img src="/storage/${testimonial.profile_image}" alt="">
                            <h6 class="name">${testimonial.guest.first_name} ${testimonial.guest.last_name} </h6>
                        </div>
                        <p>${testimonial.comment}</p>
                    </div>
                    `;
                    clientTestimonials.append(testimonialBox);
                })
            }
        }
    })
})