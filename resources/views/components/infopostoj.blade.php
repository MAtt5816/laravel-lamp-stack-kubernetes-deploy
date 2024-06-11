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


@if (Session::has('details'))
<section class="alert">
    <h3>Postój</h3><a class="close" href ="{{Session::get('details')}}s"><i class="fa fa-close"></i></a>
    <hr>
    @if(Session::get('stop')->end_date != null)
        @if(Session::get('stop')->end_date > \Carbon\Carbon::now())
        <h4>Czas do końca: </h4><div>{{\Carbon\Carbon::now()->diff(Session::get('stop')->end_date)->format('%D dni %H h %I min %S s')}}</div><br>
        @else
        <h4>Czas upłynął: </h4><div>{{\Carbon\Carbon::now()->diff(Session::get('stop')->end_date)->format('%D dni %H h %I min %S s')}}</div><br>
        @endif
    @else
        <h4>Data rozpoczęcia: </h4><div>{{\Carbon\Carbon::parse(Session::get('stop')->start_date)->timezone('Europe/Warsaw')}}</div><br>
        @csrf
        <button class="button" type="submit" onClick="window.location='{{ url("/end_stop/".Session::get('stop')->id)}}'">Zakończ</button><br><br>
    @endif
</section>
@endif
