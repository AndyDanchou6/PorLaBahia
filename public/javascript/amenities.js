$(document).ready(function () {
    const header = document.getElementById('header');
    window.addEventListener("scroll", () => {
        if(window.scrollY > 500) {
            header.classList.add("scrolled");
        }else{
            header.classList.remove("scrolled");
        }
    })
    $.ajax({
        url: 'api/amenities',
        method: 'GET',
        success: function(response){
            if(response.length > 0){
                const data = response[0];
                    const firstAmenity = $('.firstAmenity');
                    firstAmenity.html('');

                    const firstAmenityHTML = `
                    <div class="firstAmenityImage">
                        <img src="/storage/${data.main_image}" alt="">
                    </div>
                    <div class="firstAmenityDetails">
                        <h1>${data.amenity_name}</h1>
                        <i class="fa fa-bed"></i>
                        <p>${data.description} </p>
                        <div class="container">
                            <button class="goToAccommodation" ><a href="/accommodation">Go to Accommodations</a></button>
                        </div>                    
                        </div>
                    `;
                firstAmenity.append(firstAmenityHTML);

                const otherAmenity = $('.otherAmenity');
                otherAmenity.html('');

                response.slice(1).forEach((amenity) => {
                    let galleryHTML = '';

                    if (amenity.galleries && amenity.galleries.length > 0) {
                        amenity.galleries.forEach((image)=>{
                            galleryHTML += `
                                <div class="amenityGrid">
                                    <img src="/storage/${image.image}" alt="Gallery Image">
                                </div>
                            `;
                            console.log(image.image);
                        });
                        $('.amenityGalleries').append(galleryHTML);
                    } else {
                        galleryHTML = '<p>No additional images available.</p>';
                    }

                    const otherAmenitiesHTML = `
                        <div class="otherAmenity">
                            <div class="otherAmenityContainer">
                                <div class="amenityMainImage">
                                    <img src="/storage/${amenity.main_image}" alt="">
                                </div>
                                <div class="amenityGalleries">
                                    ${galleryHTML} <!-- Insert gallery dynamically -->
                                </div>
                            </div>
                            <div class="otherAmenityDetails">
                                <h1>${amenity.amenity_name}</h1>
                                <p>${amenity.description}</p>
                            </div>
                        </div>
                    `;

                    otherAmenity.append(otherAmenitiesHTML);
                });
            }else{
                firstAmenity.html('<i> No amenities at the moment. </i>');
                otherAmenity.html('<i> No additional amenities at the moment. </i>');
            }
        }
    })
})