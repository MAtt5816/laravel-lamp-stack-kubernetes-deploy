<nav>
            @if (Session::has('token') && Session::has('user'))
            <input type="checkbox" id="click">
                <label for="click" class="menu-btn">
                    <i class="fa fa-bars"></i></label>
                    <ul>
                    @switch (Session::get('user')->user_type)
                        @case("operator")
                            <li><a href="/add_parking">Dodaj parking</a></li>
                            <li><a href="/add_inspector">Dodaj kontrolera</a></li>
                            <li><a href="/parkings">Moje parkingi</a></li>
                            <li><a href="/inspectors">Kontrolerzy</a></li>
                            @break
                        @case("driver")
                            <li><a href="/vehicle">Dodaj pojazd</a></li>
                            <li><a href="/reservation">Rezerwacja</a></li>
                            <li><a href="/stop">Postój</a></li>
                            <li><a href="/vehicles">Moje pojazdy</a></li>
                            <li><a href="/reservations">Rezerwacje</a></li>
                            <li><a href="/stops">Postoje</a></li>
                            @break
                        @case("inspector")
                            <li><a href="/verify">Weryfikator</a></li>
                            @break
                        @case("admin")
                            <li><a href="#">...</a></li>
                            <li><a href="#">...</a></li>
                            <li><a href="#">...</a></li>
                            <li><a href="#">Ustawienia</a></li>
                            @break
                        @default
                            <li><a href="#">####</a></li>
                    @endswitch
                @endif
                </ul>
                <div class="logo">
                    <img src="img/logo.png" alt="parking logo"/>
                    
                </div>
                
            <aside class="new">
            @if (Session::has('token'))
            <div class="nav">
            @if (Session::get('user')->user_type == 'driver')
                <aside class="money"><span><b>{{Session::get('balance')}} zł</b></span>
                <a href="/topup"><button class="add_cash">Dodaj</button></a></aside>
                @else
                <aside class="money" style="visibility: hidden"><span>$0</span>
                <button class="add_cash"><i class="fa fa-usd" aria-hidden="true"></i></button></aside>
            @endif
                        <input type="checkbox" id="uclick">
                <label for="uclick" class="drop-btn">
                    <i class="fa fa-user"></i></label>
                    <ul>
                    <li><div class="user">{{Session::get('user')->login}}</div></li>
                    <li><a href="/settings"><i class="fa fa-cog"></i> Ustawienia</a></li>
                    <!-- <li><a href="/change_password"><i class="fa fa-lock"></i> Zmiana hasła</a></li> -->
                    <li><a href="/logout"><i class="fa fa-sign-out"></i> Wyloguj</a></li></div>

            @else
            <a class="button" href="/signup">Zarejestruj</a>
                    <a class="button" href="/login">Zaloguj</a>
            @endif
           </aside>        
            </nav>