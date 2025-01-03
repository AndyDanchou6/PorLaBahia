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
                <div class="amenityWrap">
                    <div class="amenityMainImage">
                    <img src="/storage/${amenity.main_image}" alt="">
                    </div>
                    <div class="amenityGalleries" id="${amenity.id}">
                     
                    </div>
                    </div>
                    <div class="amenityDetails">
                        <h1>${amenity.amenity_name}</h1>
                        
                        <p>${amenity.description}</p>
                    </div>
                </div>
                
                <div class="lightbox">
                    <div class="lightbox-content">
                        <button class="close-btn">&times;</button>
                        <button class="prevButton">&#8249;</button>
                        <img src="" alt="" class="imageLightbox">
                        <button class="nextButton">&#8250;</button>
                    </div>
                </div>`;
                amenityContainer.append(amenityDiv);
                
                const galleriesImage = amenity.galleries;
                const galleryCont = $(`#${amenity.id}`);
                var allImages = [];

                galleriesImage.forEach((images, index) => {
                    if(images.galleries_id == amenity.id){
                        if(index == 0){
                            const imageOne = `
                            <div class="amenityGrid gridImage1" data->
                                <img src="/storage/${images.image}" alt="">
                            </div>`;
                            galleryCont.append(imageOne);
                        }else if(index == 1){
                            const imageTwo = `
                            <div class="amenityGrid gridImage2">
                                <img src="/storage/${images.image}" alt="">
                            </div>`;
                            galleryCont.append(imageTwo);
                        }else if (index >= 2) {
                            const extraCount = galleriesImage.length - 2;
                            const extraImages = `
                            <div class="amenityGrid gridImage2 extraImages">
                                <p class="extraCount">+${extraCount}</p>
                            </div>`;
                            galleryCont.append(extraImages);
                        }
                        allImages.push(images.image);
                    }
                });
                const lightbox = $(".lightbox");
                const lightboxImage = $(".imageLightbox");
                const prevButton = $(".prevButton");
                const nextButton = $(".nextButton");

                galleryCont.on("click", ".amenityGrid", function () {
                    const clickedImageSrc = $(this).find("img").attr("src"); 
                    lightboxImage.attr("src", clickedImageSrc); 
                    lightbox.fadeIn();

                    var currentIndex = 1;

                    if (clickedImageSrc) {
                        var currentImageUrl = clickedImageSrc.slice(9, clickedImageSrc.length);
                        currentIndex = allImages.indexOf(currentImageUrl);
                    }
                    prevButton.click(function(){
                        if(currentIndex == 0){
                            currentIndex = allImages.length - 1;
                        }else{
                            currentIndex--;
                        }
                        lightboxImage.attr("src", allImages[currentIndex]);
                        console.log(currentIndex);
                    });
                    nextButton.click(function(){
                        if(currentIndex == allImages.length - 1){
                            currentIndex = 0;
                        } else {
                            currentIndex++;
                        }
                        lightboxImage.attr("src", allImages[currentIndex]);
                        console.log(currentIndex);
                    }); 
                });

                galleryCont.on("click", ".extraImages", function(){
                    if(allImages.length >= 2){
                        lightboxImage.attr("src", allImages[1]);
                        lightbox.fadeIn();
                    }   
                });
                        
                $(".close-btn").click(function() {
                    lightbox.fadeOut(); 
                });                
  
            });
        }
    })
})