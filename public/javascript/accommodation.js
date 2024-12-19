$(document).ready(function () {
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if(window.scrollY > 500){
            header.classList.add('scrolled');
        }else{
            header.classList.remove('scrolled');
        }
    });
    $.ajax({
        url: 'api/accommodations',
        method: 'GET',
        success: function(accommodation){
            if(accommodation.length > 0){
                const firstAccommodation = accommodation[0];
                const accommodationList = $('.accommodation1Image');
                accommodationList.html('');
                
                    const accommodationOne = `
                    <div class="accommodation1Image">
                        <div class="imageContainer"><img src="/storage/${firstAccommodation.main_image}" alt=""></div>
                    </div> `;
                accommodationList.append(accommodationOne);
            }
        }
    });
});