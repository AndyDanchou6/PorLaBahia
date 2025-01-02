$(document).ready(function () {
    $(document).ajaxStart(function(){
        $('#preloader').fadeIn();
    });
    $(document).ajaxStop(function(){
        $('#preloader').fadeOut();
    });
    const header = document.getElementById('header');
    window.addEventListener("scroll", () => {
        if(window.scrollY > 500) {
            header.classList.add("scrolled");
        }else{
            header.classList.remove("scrolled");
        }
    })

    $.ajax({
        url: 'api/accommodations',
        method: 'GET',
        success: function(data){
            const accommodation = data[0];
            // console.log(accommodation);
            let firstAccommodation = $('.firstAccImage');
            let firstAccDetails = $('.firstAccDetails');
            firstAccommodation.html('');
            firstAccDetails.html('');

            const accImage =`
            <img src="/storage/${accommodation.main_image}" alt="">
            `;
            firstAccommodation.append(accImage);

            const accDetails =`
            <h1>${accommodation.room_name}</h1>
            <i class="fa fa-bed"></i>
            <p>${accommodation.description}</p>
            <div class="container">
                <button class="goToAccommodation"> <a href="/accommodation">Go to Accommodations</a></button>
            </div>`;
            firstAccDetails.append(accDetails);
        }
    })

    $.ajax({
        url: 'api/amenities',
        method: 'GET',
        success: function(response){
            
            let amenityContainer = $('.amenityContainer');
            amenityContainer.html('');
            
            response.forEach((amenity) => {
                const amenityDiv = `
                <div class="amenityContainer">
                <div class="amenityWrap">
                    <div class="amenityMainImage">
                    <img src="/storage/${amenity.main_image}" alt="">
                    </div>
                    <div class="amenityGalleries" id="${amenity.id}">
                    </div>
                    </div>
                    <div class="amenityDetails">
                        <h1>${amenity.amenity_name}</h1>
                        <div class="icons"> 
                            <i class="fa fa-table"> </i>
                            <i class="fa fa-decoration"> </i>
                        </div>
                        <p>${amenity.description}</p>
                    </div>
                </div>
                </div>
                
                <div class="lightbox">
                    <div class="lightbox-content">
                        <button class="close-btn">X</button>
                        <img src="" alt="" class="imageLightbox">
                    </div>
                </div>`;
                amenityContainer.append(amenityDiv);
                
                const galleriesImage = amenity.galleries;
                const galleryCont = $(`#${amenity.id}`);

                galleriesImage.forEach((images, index) => {
                    if(images.galleries_id == amenity.id){
                        if(index == 0){
                            const imageOne = `
                            <div class="amenityGrid gridImage1" data->
                                <img src="/storage/${images.image}" alt="">
                            </div>`;
                            galleryCont.append(imageOne);
                        }
                        if(index == 1){
                            const imageTwo = `
                            <div class="amenityGrid gridImage2">
                                <img src="/storage/${images.image}" alt="">
                            </div>`;
                            galleryCont.append(imageTwo);
                        }
                        const extraCount = galleriesImage.length - 2;
                        if(index >= 2){
                            const extraImages = `
                            <div class="amenityGrid gridImage2 extraImages">
                                <p class="extraCount">+${extraCount}</p>
                            </div>`;
                            galleryCont.append(extraImages);
                        }

                        const lightbox = $(".lightbox");
                        const lightboxImage = $(".imageLightbox");

                        galleryCont.on("click", ".amenityGrid", function () {
                            const clickedImageSrc = $(this).find("img").attr("src"); 
                            lightboxImage.attr("src", clickedImageSrc); 
                            lightbox.fadeIn(); 
                        });
                        galleryCont.on("click", ".extraImages", function () {
                            const clickedContainer = $(this).closest(".amenityGalleries");
                            const allImages = [];
    
                            clickedContainer.find("img").each(function () {
                                allImages.push($(this).attr("src"));
                            }); 
                        
                            if (allImages.length >= 2) {
                                lightboxImage.attr("src", allImages[1]);
                                lightbox.fadeIn();
                            }
                        });
                        $(".close-btn").click(function() {
                            lightbox.fadeOut(); 
                        }); 
                        
                    }
                });
            });
        }
    })
})