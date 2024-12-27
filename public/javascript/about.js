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
            $('.arrowLeft').click(function (){
                amenitiesAbout.slick('slickPrev');
            });
            $('.arrowRight').click(function (){
                amenitiesAbout.slick('slickNext');
            });
        }else{
            $('.arrowLeft, .arrowRight').hide();
        }
    }
});

$.ajax({
    url: "/api/cms/about",
    method: "GET",
    dataType: "json",
    success: function (response) {
        const getData = response.data;
        let section1 = $(".section1");
        let section2 = $(".know-more-text");
        let section22 = $(".historydiv");
        let section3 = $(".flex-images");
        let section4 = $(".history-grid");
        let section5 = $(".history-porla");

        section2.html("");
        section22.html("");
        section3.html("");
        section4.html("");
        section5.html("");

        let hasSection2Content = false;
        let hasSection3Content = false;
        let hasSection4Content = false;
        let hasSection5Content = false;

        getData.forEach(function (item) {
            section1.html("");
            if (item.section == 1) {
                hasSection1Content = true;
                const section1Content = `
                    <h2 class="about-title">Welcome to Por La Bahia</h2>
                    <h1 class="about-paragraph"><span>About Us: </span>${item.title}</h1>
                `;
                section1.append(section1Content);

                if (item.background_image) {
                    $(".background").css(
                        "background-image",
                        `url(/storage/${item.background_image})`
                    );
                }
            }

            if (item.section == 2 && item.is_published == 1 && item.page == "about") {
                hasSection2Content = true;
                const section2Content = `
                    <div>
                        <h1 class="know-more-title">${item.title}</h1>
                        <p>${item.value}</p>
                    </div>
                `;
                section2.append(section2Content);

                if (item.background_image) {
                    const imageContainer = `
                        <div class="historydiv">
                            <img src="/storage/${item.background_image}" alt="">
                        </div>
                    `;
                    section22.append(imageContainer);
                }
            }
            if (item.page == "about" && item.section == 3) {
                hasSection3Content = true;
                const icons = item.icons;
                icons.forEach(function (icon) {
                    const iconDisplay = `
                        <div>
                            <img src="${icon.image}" alt="">
                            <h3>${icon.icon_name}</h3>
                            <p>${icon.description}</p>
                        </div>
                    `;
                    section3.append(iconDisplay);
                });
            }
            if (item.page == "about" && item.section == 4) {
                hasSection4Content = true;
                const section4Image = `
                    <div class="history-grid-image">
                        <img src="/storage/${item.background_image}" alt="">
                    </div>
                    <div class="history-porla">
                        <h1>${item.title}</h1>
                        <p>${item.value}</p>
                    </div>
                `;
                section4.append(section4Image);
            }
            if (item.page == "about" && item.section == 5) {
                hasSection5Content = true;
                const section5Display = `
                    <div class="history-operation">
                        <div class="history-operation-title">
                            <img src="/images/i.svg" alt="">
                            <h6>${item.title}</h6>
                        </div>
                        <p>${item.value}</p>
                    </div>
                `;
                section5.append(section5Display);
            }
        });
        if (!hasSection2Content) {
            section2.html("<i>No history at the moment.</i>");
        }
        if (!hasSection3Content) {
            section3.html("<i>No features section at the moment.</i>");
        }
        if (!hasSection4Content) {
            section4.html("<i>No history at the moment.</i>");
        }
    },
});
});
