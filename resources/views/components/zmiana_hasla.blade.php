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

            <section class="include">                 
                <h3>Zmiana hasła</h3>

                <form method="post" action="/change_password">
                    @csrf
                    <input type="password" name="old_password" placeholder="Stare hasło" required="true"><br>
                    <input type="password" name="new_password" placeholder="Nowe hasło" required="true"><br>
                    <input type="password" name="new_password_confirmation" placeholder="Powtórz hasło" required="true"><br>
                    <input type="submit" class="button" value="Zmień">
                    
                </form>

        </section>
