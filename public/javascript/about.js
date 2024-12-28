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
        let aboutPage = $('.historydiv');
        let knowMore = $('.know-more-title');
        let iconDiv = $('.flex-images');
        let historyContainer = $('.history-grid');
        let historyOperation = $('.history-operation');
        aboutPage.html('');
        knowMore.html('');
        iconDiv.html('');
        historyContainer.html('');
        historyOperation.html('');

        const aboutData = response.data;
        aboutData.forEach((item) => {
        if(item.page == "about" && item.section == 2 && item.is_published ==1){
            const historyImage = ` 
            <div class="historydiv">
                <img src="/storage/${item.background_image}" alt="">
            </div>`;
            aboutPage.append(historyImage);

            const knowMoreText = ` 
            <div class="know-more-title">
                <h1>${item.title}</h1>
                <p>${item.value}</p>
            </div>`;
            knowMore.append(knowMoreText);
        }else{

        }

        if(item.page == "about" && item.section == 3 && item.is_published ==1){
            const icons = item.icons;
            icons.forEach((icon) =>{
                const iconContainer =`
                <div><img src="/storage/${icon.image}" alt="">
                    <h3>${icon.icon_name}</h3>
                    <p>${icon.description}</p>
                </div>`;
                iconDiv.append(iconContainer);
            })
        }

        if(item.page == "about" && item.section == 4 && item.is_published ==1){
            const historyContent =`
            <div class="history-grid-image">
                <img src="/storage/${item.background_image}" alt="">
            </div>
            <div class="history-porla">
                <h1>${item.title}</h1>
                <p>${item.value}</p>
            </div>
            `;
            historyContainer.append(historyContent);
        }

        if(item.page == "about" && item.section == 5 && item.is_published ==1){
            const historyOp =`
            <div class="history-operation">
            <div class="history-operation-title">
                <img src="/images/i.svg" alt="">
                <h6>${item.title}</h6>
            </div>
                <p>${item.value}</p>
            </div>
            </div>`;
            $('.history-porla').append(historyOp);
            }
        })
        if (aboutData.length == 0) {
            $('.history').html('<i>No about us details at the moment.</i>').css({
                'display':'flex',
                'justify-content': 'center',
            });
        }
        }
    });
});
