<section>
     <aside class="banner">
         
         <img class="logo" src="img/logo.png" alt="parking logo"/>
         <aside class="first">Our-parking.com</aside>
         <aside class="two">Zarządzaj parkingami,<br>rezerwuj miejsca parkingowe <br><span>i nie tylko...</span></aside>
         <img class="iphone" src="img/iphone.png" alt="iphone" height="auto" width="280"/>
         <aside class="download">Pobierz Our-parking</aside>
         <img class="appstore" src="img/appstore.png" alt="appstore" height="auto" width="150"/> 
     </aside>
     <aside class="home">
         <br><div class="element"><h1>Jesteśmy już w <b>
         @if (Session::has('parkings'))
         {{count(Session::get('parkings'))}}
         @else
            0
        @endif
         </b> miejscach.To dopiero początek</h1><div><br>
         <hr>
         <aside class="homeMap">
         <div id="map">
            
         </div>
        </aside>
     </aside>     
 </section>

