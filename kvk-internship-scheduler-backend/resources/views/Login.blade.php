<html>
<header>
    <link rel="stylesheet" href="{{ asset('css/Login.scss') }}">
    <title>KVK E-Dienynas</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('KVKIcon.ico') }}">
</header>

<body>
    <div class="header">
        <img src="{{ asset('images/KVKlogo.png') }}" alt="KVK Logo">
       
    </div>

    <div class="mainLogin">
        <h1>Įveskite prisijungimo duomenis</h1>

        <form method="POST" action='{{ route('login') }}'>
        @csrf
            <div>
                <label for="text">Prisijungimo vardas</label>
                <input name='username' type="text">
            </div>
            <div>
                <label for="password">Slaptažodis</label>
                <input name='password' type="password">
            </div>
            <a href="">Pamiršai slaptažodį?</a>
            <button>Prisijungti</button>
        </form>
    </div>

    <footer>
        <div class="footerText">
            <h2>©2023</h2>
            <a class="footerA" href="https://www.kvk.lt/">Klaipėdos valstybinė kolegija</a>
        </div>
        <img class="footerLogo" src="{{ asset('images/KVKlogo.png') }}" alt="KVK Logo">

    </footer>



</body>

</html>
