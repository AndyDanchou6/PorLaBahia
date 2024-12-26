<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('styles/header.css')}}">
    <title>Document</title>
</head>
<body>
    <header id="header">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
    <ul>
        <li><a href="/home" class="nav-link">Home</a></li>
        <li><a href="/about" class="nav-link">About Us</a></li>
        <li><a href="/accommodation" class="nav-link">Accommodations</a></li>
        <li><a href="#" class="nav-link">Amenities</a></li>
        <li><a href="#" class="nav-link">Restaurant Menu</a></li>
        <li><a href="#" class="nav-link">Contact Us</a></li>
    </ul>
    <button class="booknow"><img src="{{asset('/images/icon.svg')}}" alt="" class="buttonImage"> Book Now</button>
</header> 
</body>
</html>