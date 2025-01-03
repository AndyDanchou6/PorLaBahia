$(document).ready(function () {
    $.ajax({
        url: "api/testimonials",
        method: "GET",
        success: function (testimonials) {
            if (testimonials.length > 0) {
                const clientTestimonials = $(".testimonialSlider");
                clientTestimonials.html("");

                testimonials.forEach(function (testimonial) {
                    const profileImage = testimonial.profile_image
                        ? `/storage/${testimonial.profile_image}`
                        : `/images/profileIcon.jpg`;

                    const testimonialBox = `
                    <div class="testimonialSlider">
                        <div class="testimonial-container">
                            <div class="profile-name">
                                <div class="profileimgContainer">
                                <img src="${profileImage}" alt="" id="profile_image">
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
                    infinite: true,
                    slidesToScroll: 1,
                    slidesToShow: 3,
                    arrows: false,
                });
                if (testimonials.length <= 3) {
                    $(".redArrowLeft, .redArrowRight").hide();
                } else {
                    $(".redArrowLeft, .redArrowRight").show();
                }
                $(".redArrowLeft").click(function () {
                    clientTestimonials.slick("slickPrev");
                });
                $(".redArrowRight").click(function () {
                    clientTestimonials.slick("slickNext");
                });
            } else {
                $(".testimonialSlider")
                    .html("<i>No testimonials at the moment.</i>")
                    .css({
                        display: "flex",
                        "justify-content": "center",
                        "align-items": "start",
                    });
                $(".redArrowLeft, .redArrowRight").hide();
            }
        },
    });
});
