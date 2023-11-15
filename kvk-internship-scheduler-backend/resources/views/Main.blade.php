<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>KVK E-Dienynas</title>
    <link rel="stylesheet" href="{{ asset('css/MainPage.scss') }}">
    <script src="{{ asset('js/Functions.js') }}"></script>
</head>

<body>
    <div class="header">
        <img class="logo" src="{{ asset('images/KVKlogo.png') }}" alt="KVK Logo">
        <div class="userInfo">
            <img class='userIcon' src="{{ asset('images/UserIcon.png') }}" alt="UserIcon">
            <h1 class="userName">{{ $user->name }}</h1>
            {{-- partempt is db --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class='logoutBtn' type="submit"><img class="LogoutIcon" src="{{ asset('images/LogoutIcon.png') }}" alt="Logout Icon"></button>
            </form>
        </div>
    </div>  
    </div>
    <h1 class="mainText">Pasirinkite norimą puslapį:</h1>
    <a  href='{{ route("profileInfo") }}'><button>Profilio informacija</button></a>
    <button>Mano kalendorius</button>
    <button>Dokumentai</button>
    <button>Mano rezultatai</button>
    <button>Mokymosi medžiaga</button>
    <button>Pokalbiai</button>
    <button>Pagalba!</button>




</body>

</html>
