$(document).ready(function () {
    $(document).ajaxStart(function () {
        $("#preloader").fadeIn();
    });
    $(document).ajaxStop(function () {
        $("#preloader").fadeOut();
    });
    const header = document.getElementById("header");
    window.addEventListener("scroll", () => {
        if (window.scrollY > 600) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    });
    $.ajax({
        url: "/api/amenities",
        method: "GET",
        dataType: "json",
        success: function (data) {
            if (data.length > 0) {
                const amenitiesContainer = $(".amenities-boxes");
                amenitiesContainer.html("");
                data.forEach(function (amenity) {
                    const amenityBox = `
                        <div class="amenities-box">
                            <img src="/storage/${amenity.main_image}" alt="${amenity.amenity_name}">
                            <h3>${amenity.amenity_name}</h3>
                            <p>${amenity.description}</p>
                            <button class="amenities-readmore" > Read More</button>
                        </div>
                   `;
                    amenitiesContainer.append(amenityBox);
                });
                amenitiesContainer.slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    dots: true,
                    arrows: false,
                });
                if (data.length <= 3) {
                    $(".arrowLeft, .arrowRight").hide();
                } else {
                    $(".arrowLeft, .arrowRight").show();
                }
                $(".arrowLeft").click(function () {
                    amenitiesContainer.slick("slickPrev");
                });
                $(".arrowRight").click(function () {
                    amenitiesContainer.slick("slickNext");
                });
            } else {
                $(".amenities-boxes").html(
                    "<p><i> No amenities at the moment.</i></p>"
                );
                $(".arrowLeft, .arrowRight").hide();
            }
        },
    });

    // $.ajax({
    //     url: "/api/accommodations",
    //     method: "GET",
    //     dataType: "json",
    //     success: function (accommodation) {
    //         if (accommodation.length > 0) {
    //             const accommodationContainer = $(".other-houses");
    //             accommodationContainer.html("");

    //             accommodation.forEach(function (house) {
    //                 const accommodationBox = `
    //                 <div class="house1"
    //                         src="/storage/${house.main_image}"
    //                         data-room_name="${house.room_name}"
    //                         data-description="${house.description}"
    //                         data-weekday_price="${house.weekday_price}">
    //                     <img src="/storage/${house.main_image}"
    //                     <h4>${house.room_name}</h4>
    //                     <h6> <i class="fa fa-bed"> </i> 2</h6>
    //                     <p>${house.description}</p>
    //                     <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
    //                 </div>
    //                     `;
    //                 accommodationContainer.append(accommodationBox);
    //             });
    //             accommodationContainer.slick({
    //                 infinite: true,
    //                 slidesToScroll: 1,
    //                 slidesToShow: 3,
    //                 arrows: false,
    //             });
    //             if (accommodation.length <= 3) {
    //                 $(".arrowL, .arrowR").hide();
    //             } else {
    //                 $(".arrowL, .arrowR").show();
    //             }
    //             $(".arrowL").click(function () {
    //                 accommodationContainer.slick("slickPrev");
    //             });
    //             $(".arrowR").click(function () {
    //                 accommodationContainer.slick("slickNext");
    //             });
    //             const largeImage = document.getElementById("large-image");
    //             const titleElement = document.querySelector(
    //                 ".resort-houses-image h1"
    //             );
    //             const descriptionElement = document.querySelector(
    //                 ".resort-houses-image p"
    //             );
    //             const priceElement = document.querySelector(
    //                 ".resort-houses-image .price"
    //             );
    //             const firstHouse = accommodation[0];

    //             largeImage.src = `/storage/${firstHouse.main_image}`;
    //             titleElement.textContent = firstHouse.room_name;
    //             descriptionElement.textContent = firstHouse.description;
    //             priceElement.textContent = `₱ ${firstHouse.weekday_price}`;
    //             $(".house1:first").addClass("active");

    //             $(".other-houses").on("click", ".house1", function () {
    //                 const clickedImage = $(this);
    //                 const newImageSrc = clickedImage.attr("src");
    //                 const newRoomName = clickedImage.data("room_name");
    //                 const newDescription = clickedImage.data("description");
    //                 const newPrice = clickedImage.data("weekday_price");

    //                 largeImage.src = newImageSrc;
    //                 titleElement.textContent = newRoomName;
    //                 descriptionElement.textContent = newDescription;
    //                 priceElement.textContent = `₱ ${newPrice}`;

    //                 $(".house1").removeClass("active");
    //                 clickedImage.addClass("active");
    //             });
    //         } else {
    //             $(".other-houses").html(
    //                 "<i><p>No accommodation at the moment.</p></i>"
    //             );
    //             $(".resort-houses-image").html(
    //                 "<i>Once accommodation clicked, it will display here.</i>"
    //             );
    //             $(".arrowL, .arrowR").hide();
    //         }
    //     },
    // });

    $.ajax({
        url: "/api/accommodations",
        method: "GET",
        dataType: "json",
        success: function (accommodation) {
            if (accommodation.length > 0) {
                const accommodationContainer = $(".other-houses");
                accommodationContainer.html("");

                accommodation.forEach(function (house) {
                    let dataValue = house.description;
                    const parser = new DOMParser();
                    const parsedDocument = parser.parseFromString(
                        dataValue,
                        "text/html"
                    );
                    const dataValueDescription =
                        parsedDocument.body.textContent || "";

                    const accommodationBox = `
                <div class="house1" 
                        src="/storage/${house.main_image}"
                        data-room_name="${house.room_name}" 
                        data-description="${dataValueDescription}" 
                        data-weekday_price="${house.weekday_price}">
                    <img src="/storage/${house.main_image}" alt="${house.room_name}">
                    <h4>${house.room_name}</h4>
                    <h6> <i class="fa fa-bed"> </i> 2</h6>
                    <p>${dataValueDescription}</p>
                    <button class="checkItButton"><i class="fa fa-check-circle-o"></i> Check It</button>
                </div>
                `;
                    accommodationContainer.append(accommodationBox);
                });

                accommodationContainer.slick({
                    infinite: true,
                    slidesToScroll: 1,
                    slidesToShow: 3,
                    arrows: false,
                });
                if (accommodation.length <= 3) {
                    $(".arrowL, .arrowR").hide();
                } else {
                    $(".arrowL, .arrowR").show();
                }
                $(".arrowL").click(function () {
                    accommodationContainer.slick("slickPrev");
                });
                $(".arrowR").click(function () {
                    accommodationContainer.slick("slickNext");
                });

                const largeImage = document.getElementById("large-image");
                const titleElement = document.querySelector(
                    ".resort-houses-image h1"
                );
                const descriptionElement = document.querySelector(
                    ".resort-houses-image p"
                );
                const priceElement = document.querySelector(
                    ".resort-houses-image .price"
                );
                const firstHouse = accommodation[0];

                const parser = new DOMParser();
                const parsedFirstDocument = parser.parseFromString(
                    firstHouse.description,
                    "text/html"
                );
                const sanitizedFirstDescription =
                    parsedFirstDocument.body.textContent || "";

                largeImage.src = `/storage/${firstHouse.main_image}`;
                titleElement.textContent = firstHouse.room_name;
                descriptionElement.textContent = sanitizedFirstDescription;
                priceElement.textContent = `₱ ${firstHouse.weekday_price}`;
                $(".house1:first").addClass("active");

                $(".other-houses").on("click", ".house1", function () {
                    const clickedImage = $(this);
                    const newImageSrc = clickedImage.attr("src");
                    const newRoomName = clickedImage.data("room_name");
                    const newDescription = clickedImage.data("description");
                    const newPrice = clickedImage.data("weekday_price");

                    largeImage.src = newImageSrc;
                    titleElement.textContent = newRoomName;
                    descriptionElement.textContent = newDescription;
                    priceElement.textContent = `₱ ${newPrice}`;

                    $(".house1").removeClass("active");
                    clickedImage.addClass("active");
                });
            } else {
                $(".other-houses").html(
                    "<i><p>No accommodation at the moment.</p></i>"
                );
                $(".resort-houses-image").html(
                    "<i>Once accommodation clicked, it will display here.</i>"
                );
                $(".arrowL, .arrowR").hide();
            }
        },
    });

    const video = document.getElementById("video");
    const playPauseBtn = document.getElementById("playPauseBtn");
    playPauseBtn.addEventListener("click", () => {
        const icon = playPauseBtn.querySelector(".fa-youtube-play");
        if (video.paused) {
            video.play();
            icon.style.display = "none";
        } else {
            video.pause();
            icon.style.display = "inline";
        }
    });
    $(".submit").on("click", function (event) {
        event.preventDefault();
        const contact_name = $("#contact_name").val();
        const street = $("#street").val();
        const city = $("#city").val();
        const zip_code = $("#zip_code").val();
        const contact_no = $("#contact_no").val();
        const email = $("#email").val();
        const message = $("#message").val();

        $.ajax({
            url: "api/submitForm",
            type: "POST",
            data: {
                contact_name: contact_name,
                street: street,
                city: city,
                zip_code: zip_code,
                contact_no: contact_no,
                email: email,
                message: message,
            },
            success: function (response) {
                console.log(response);
                $("#responseMessage")
                    .removeClass("error")
                    .html("Your message has been successfully sent!")
                    .css("display", "block");
                $(
                    "#contact_name, #street, #city, #zip_code, #contact_no, #email, #message"
                ).val("");
            },
            error: function () {
                $("#responseMessage")
                    .addClass("error")
                    .html("Please fill out the form first.")
                    .css("display", "block");
            },
        });
    });

    function updateAboutSection() {
        $.ajax({
            url: "api/getHomeContents",
            method: "GET",
            dataType: "json",
            success: function (data) {
                const aboutSection = data.find((item) => item.page == "home");
                // console.log(data);
                if (aboutSection) {
                    let aboutHTML = `
                <h1>Por La Bahia</h1>
                <h4>${aboutSection.title.toUpperCase()}</h4>
                <p>${aboutSection.value}</p>
                <div class="icon-name">
            `;

                    if (aboutSection.icons && aboutSection.icons.length > 0) {
                        aboutSection.icons.forEach(function (icon) {
                            if (icon.image && icon.icon_name) {
                                aboutHTML += `
                            <div>
                                <img src="/storage/${icon.image}" alt="${icon.icon_name}">
                                ${icon.icon_name}
                            </div>
                        `;
                            }
                        });
                    }
                    aboutHTML += `
                </div>
                <button class="readmore">
                    <img src="/images/book.svg" alt="Read More" class="readmoreImage"> 
                    <a href="/about">Read More</a>
                </button>
            `;

                    const aboutTextDiv = $(".about-text");
                    if (aboutTextDiv.length) {
                        aboutTextDiv.html(aboutHTML);
                    }
                } else {
                    $(".about-text")
                        .html("<i> No About Us section at the moment.</i>")
                        .css({
                            display: "flex",
                            "justify-content": "center",
                            "align-items": "center",
                        });
                }

                const getAccommodation = data.find(
                    (context) => context.page == "home" && context.section == 3
                );
                if (getAccommodation) {
                    let accommodationContent = `
                    <div class="resort-houses-title">
                        <img src="/images/lineLeft.svg" alt="" class="lineLeft">
                        <h1>Resort Houses</h1>
                        <img src="/images/lineRight.svg" alt="" class="lineRight">
                    </div>
                    <h3>OUR ACCOMMODATIONS</h3>
                    <p>${getAccommodation.value}</p>
                </div>
            `;
                    const accommodationSection = $("#accommodationSection");
                    if (accommodationSection) {
                        accommodationSection.html(accommodationContent);
                    }
                }
            },
        });
    }
    updateAboutSection();

    $.ajax({
        url: "/api/getFeaturedImages",
        method: "GET",
        dataType: "json",
        success: function (data) {
            const gridContainer = $(".grid-images");
            gridContainer.html("");

            if (data.length > 0) {
                data.forEach(function (image, index) {
                    if (index == 0) {
                        const featuredImageOne = `
                        <div class="item item1">
                            <img src="/storage/${image.image}" alt="">
                        </div>`;
                        gridContainer.append(featuredImageOne);
                    }
                    if (index == 1) {
                        const featuredImageTwo = `
                        <div class="item item2">
                            <img src="/storage/${image.image}" alt="">
                        </div>`;
                        gridContainer.append(featuredImageTwo);
                    }
                    if (index == 2) {
                        const featuredImageThree = `
                        <div class="item item3">
                            <img src="/storage/${image.image}" alt="">
                        </div>`;
                        gridContainer.append(featuredImageThree);
                    }
                    const extraCount = data.length - 3;
                    if (index >= 3) {
                        const extraFeaturedImages = `
                        <div class="item item3 extraImages">
                            <p class="extraCount">+${extraCount}</p>
                        </div>`;
                        gridContainer.append(extraFeaturedImages);
                    }

                    const lightboxCont = $(".lightboxContainer");
                    const lightboxFeaturedImage = $(".lightboxFeaturedImage");
                    const featuredImages = $(".grid-images");

                    featuredImages.on("click", ".item", function () {
                        const featuredImageSRC = $(this)
                            .find("img")
                            .attr("src");
                        lightboxFeaturedImage.attr("src", featuredImageSRC);
                        lightboxCont.fadeIn();
                    });
                    featuredImages.on("click", ".extraImages", function() {
                        const clickedContainer = $(this).closest(".grid-images");
                        const allImages = [];

                        clickedContainer.find("img").each(function () {
                            allImages.push($(this).attr("src"));
                        });

                        if (allImages.length >= 3) {
                            lightboxFeaturedImage.attr("src", allImages[2]);
                            lightboxCont.fadeIn();
                        }
                    });
                    $(".close-btn").click(function () {
                        lightboxCont.fadeOut();
                    });
                });
            } else {
                gridContainer
                    .html("<i>No featured images at the moment.</i>")
                    .css({
                        display: "flex",
                        "justify-content": "center",
                        "align-items": "center",
                    });
            }
        },
    });
});
