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
            response.forEach((amenity) => {
                const amenityDiv = `
                <div class="otherAmenityContainer">
                <div class="amenityMainImage"><img src="/storage/${amenity.main_image}" alt=""></div>
                </div>`;
                console.log(amenity);
            })


            // let amenityContainer = $('.otherAmenity');
            // let amenityDetails = $('.otherAmenityDetails');
            // amenityContainer.html('');
            // amenityDetails.html('');

            // response.forEach((amenity) => {
            //     console.log(amenity);
                // const amenityDiv = `
                //         <div class="amenityMainImage"><img src="/storage/${amenity.main_image}" alt=""></div>
                //         <div class="amenityGalleries">
                //             <div class="amenityGrid gridImage1"><img src="/" alt=""></div>
                //             <div class="amenityGrid gridImage2"><img src="/" alt=""></div>
                //         </div>
                // `;
            //     amenityContainer.append(amenityDiv);
                
                // const amenityDetailsContainer =`
                //     <h1>${amenity.amenity_name}</h1>
                //         <div class="icons"> 
                //         <i class="fa fa-table"> </i>
                //         <i class="fa fa-decoration"> </i>
                //     </div>
                //     <p>${amenity.description}</p>
                //     `;
                // amenityDetails.append(amenityDetailsContainer);

                // const galleriesAmenity = amenity.galleries;
                // if(galleriesAmenity.length > 0){
                //     galleriesAmenity.forEach((images, index)=>{
                //         if(index > 2){
                //             let itemClass = 'amenityGrid';
                //             if(index == 0) itemClass += 'gridImage1';
                //             if(index == 1) itemClass += 'gridImage2';

                //             const imageGalleryContainer =`
                //             <div class="${itemClass} ">
                //                 <img src="/storage/${images.image}" alt="">
                //             </div>
                //             `;
                //             amenityGallery.append(imageGalleries);
                //         }
                //         console.log(galleriesAmenity);
                //     })
                // }
    //     });
        }
    })
})