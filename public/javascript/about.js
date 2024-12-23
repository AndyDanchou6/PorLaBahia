$(document).ready(function () {
    const header = document.getElementById("header");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 700) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    });
    $.ajax({
        url: "/api/amenities",
        method: "GET",
        dataType: "json",
        success: function (amenities) {
            if (amenities.length > 0) {
                const amenitiesAbout = $(".about-image-slider");
                amenitiesAbout.html("");

                amenities.forEach(function (amenity) {
                    const amenityAbout = `
                <div class="about-item">
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
            if(accommodation.length <= 3){
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
                $('.about-image-slider').html('<i><p style="color:white">No amenities at the moment.</p></i>');     
                $('.arrowLeft, .arrowRight').hide();
            }
        },
    });

    $.ajax({
        url: "/api/cms/about",
        method: "GET",
        dataType: "json",
        success: function (response) {
            // console.log(about.data);
            const getData = response.data;

            getData.forEach(function (item) {
                if (item.section == 1) {
                    let section1Welcome = $(".section1");

                    const section1Content = `
                         <h2 class="about-title">${item.title}</h2>
                         <h1 class="about-paragraph"><span>About Us: </span>${item.value}</h1>
                    `;

                    section1Welcome.append(section1Content);
                }

                if (item.section == 2) {
                    const section2 = $(".know-more-text");

                    const section2Content = `
                        <div>
                            <h1 class="know-more-title">${item.title}</h1>
                            <p>${item.value}</p>
                        </div>
                    `;

                    section2.append(section2Content);
                }

                if (item.section == 3) {
                    const section3 = $(".flex-images");

                    section3.html("");

                    const icons = item.icons;

                    icons.forEach(function (icon) {
                        const iconDisplay = `
                        <div><img src="${icon.image}" alt="">
                            <h3>${icon.icon_name}</h3>
                            <p>${icon.description}</p>
                        </div>
                        `;

                        section3.append(iconDisplay);
                    });
                }

                if (item.section == 4) {
                    const section4 = $(".section4data");
                    section4.html("");

                    const section4Display = `
                        <h1>${item.title}</h1>
                        <p>${item.value}</p>
                    `;

                    section4.append(section4Display);
                }

                if (item.section == 5) {
                    const section5 = $(".history-operation");
                    section5.html("");

                    const section5Display = `
                    <div class="history-operation-title">
                        <img src="/images/i.svg" alt="">
                        <h6>${item.title}</h6>
                    </div>
                         <p>${item.value}</p>
                    `;

                    section5.append(section5Display);
                }
            });
        },
    });
});
