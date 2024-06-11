<section class="include">
    <h3>Usuń konto</h3>
    @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

    <aside class="text"><span class="red"> Uwaga!</span> Usunięcie konta jest trwałe i nie można go cofnąć.</aside>
    <br><form method="post" action="/delete_account">@csrf<button class="submit"> Usuń konto </button></form>
    
</section>
