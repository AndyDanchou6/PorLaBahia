<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" href="{{asset('styles/header.css')}}">
    <title>Document</title>
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/about">About Us</a></li>
        <li><a href="">Accommodations</a></li>
        <li><a href="">Amenities</a></li>
        <li><a href="">Restaurant Menu</a></li>
        <li><a href="">Contact Us</a></li>
    </ul>
    <button><i class="fa fa-book"></i> Book Now</button>
</header>
    <div class="lines">
        <div class="line1"></div>
        <div class="line2"></div>
    </div>  
</body>
</html>