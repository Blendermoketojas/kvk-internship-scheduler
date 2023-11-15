<html>

<head>
    <title>KVK E-Dienynas</title>
    <link rel="stylesheet" href="{{ asset('css/MainPage.scss') }}">

</head>

<body>
    <div class="header">
        <img class="logo" id='layoutLogo'src="{{ asset('images/KVKlogo.png') }}" alt="KVK Logo">

        <button class="layoutBtn">Profilio informacija</button>
        <button class="layoutBtn">Mano kalendorius</button>
        <button class="layoutBtn">Dokumentai</button>
        <button class="layoutBtn">Mano rezultatai</button>
        <button class="layoutBtn">Mokymosi medžiaga</button>
        <button class="layoutBtn">Pokalbiai</button>
        <button class="layoutBtn">Pagalba!</button>

        <div class="userInfo">
            <img class='userIcon' src="{{ asset('images/UserIcon.png') }}" alt="UserIcon">
            <h1 class="userName">{{ $user->name }}</h1>
    <h1>{{ $user->first_name }}</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class='logoutBtn' type="submit"><img class="LogoutIcon" src="{{ asset('images/LogoutIcon.png') }}" alt="Logout Icon"></button>
            </form>
        </div>
    </div>  
    </div>


<div>
@yield('ProfileInfo')
@yield('Calendar')


</div>




</body>

</html>
