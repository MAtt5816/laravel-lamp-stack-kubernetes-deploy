<section class="include">
    <h3> Ustawienia konta </h3>
<form method="post">
@if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('success'))
            <div class="alert-success">
                @if(is_array(session('success')))
                    <ul>
                        @foreach (session('success') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ session('success') }}
                @endif
            </div>
            @endif

    @csrf
    @if(Session::has('token'))
        @switch(Session::get('user')->user_type)
            @case('driver')
                <div class="left"><label for="name" >Imię</label>
                <input type="hidden" name="user_id" value="{{Session::get('driver')->user_id}}">
                <input type="text" name="name" placeholder="Imię" pattern="[A-Za-ząćęłńóśźżŁŻŚŹĆÓ ]{1,20}$" title="Użyj max. 20 liter" value="{{Session::get('driver')->name}}" required></div>
                <div class="right"><label for="surname">Nazwisko</label>
                <input type="text" name="surname" placeholder="Nazwisko" pattern="[A-Za-ząćęłńóśźżŁŻŚŹĆÓ -]{1,25}$" title="Użyj max. 25 liter" value="{{Session::get('driver')->surname}}"  required></div>
                <div class="left"><label for="email">Email</label>
                <input type="email" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" maxlength="30" value="{{Session::get('driver')->email}}" required></div>
                <div class="right"><label for="phone">Numer telefonu</label>
                <input type="text" name="phone" placeholder="Numer telefonu" required="true" pattern="[0-9]{1,11}" value="{{Session::get('driver')->phone}}"></div>
                <div class="left"><label for="postal_code">Kod pocztowy</label>
                <input type="text" name="postal_code" placeholder="Kod pocztowy" maxlength="6" value="{{Session::get('driver')->postal_code}}" required></div>
                <div class="right"><label for="city">Miejscowość</label>
                <input type="text" name="city" placeholder="Miejscowość" pattern="[A-Za-ząćęłńóśźżŁŻŚŹĆÓ -]{1,30}$" title="Użyj max. 30 liter" value="{{Session::get('driver')->city}}" required></div>
                <div class="left"><label for="street">Ulica</label>
                <input type="text" name="street" placeholder="Ulica" pattern="[A-Za-ząćęłńóśźżŁŻŚŹĆÓ -]{1,25}$" title="Użyj max. 25 liter" value="{{Session::get('driver')->street}}" required></div>
                <div class="right"><label for="house_number">Numer domu</label>
                <input type="text" name="house_number" placeholder="Numer domu" pattern="[A-Za-z0-9 -.\/]{1,6}$" title="Użyj max. 6 liter lub cyfr" value="{{Session::get('driver')->house_number}}" required></div><br>
                <div><input type="submit" name="submit" value="Zapisz"></div>
            @break
            @case('operator')
                <div class="left"><label for="email">Email</label>
                <input type="hidden" name="user_id" value="{{Session::get('operator')->user_id}}">
                <input type="email" name="email" placeholder="Email" required="true" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" maxlength="25" value="{{Session::get('operator')->email}}"></div>
                <div class="right"><label for="phone">Numer telefonu</label>
                <input type="text" name="phone" placeholder="Numer telefonu" required="true" pattern="[0-9]{1,11}" value="{{Session::get('operator')->phone}}"></div>
                <div class="left"><label for="tin">NIP</label>
                <input type="text" name="tin" placeholder="NIP" maxlength="11" value="{{Session::get('operator')->tin}}" required="true"></div>
                <div class="btno"><input type="submit" name="submit" value="Zapisz"></div>
            @break
            @case('inspector')
                <p>Sekcja niedostępna dla kontrolerów</p>
            @break
        @endswitch
    @endif
            </form>
    </section>