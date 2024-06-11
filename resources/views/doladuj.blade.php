<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width" />
         <title>Our-parking -rezerwuj miejsca parkingowe, zgłoś parking</title>
        <link rel="stylesheet" href="CSS/forms.css" type="text/css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body>
    <script>
            $(document).ready(function(){
                $("#value5").click(function() {
                    $("input[type='radio']").prop( "disabled", true);
                    $("input[type='radio']").prop("hidden", true);
                    $("label").hide();
                    $("#value6").prop("disabled", false);
                    $(".other").prop("hidden", false);
                });
            });
        </script>
        <br><br><section class="container">
                <aside class="body_form">
                    <a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    <h1>Doładuj portfel</h1>
                <form method="post" action="/pay">
                    @csrf
                <hr>
                <aside class="cash">
                <input type="radio" id="value1" name="money" value="10">
                <label for="value1"><span>10 zł</span></label>
                <input type="radio" id="value2" name="money" value="25">
                <label for="value2"><span>25 zł</span></label>
                <input type="radio" id="value3" name="money" value="50">
                <label for="value3"><span>50 zł</span></label>
                <input type="radio" id="value4" name="money" value="100">
                <label for="value4"><span>100 zł</span></label>
                <input type="radio" id="value5" name="money" value="inna">
                <label for="value5"><span>inna</span></label>

                <div class="other" hidden><span>Kwota: </span><input id="value6" type="number" name="money" disabled min="0" step="0.01"/><span>zł</span></div>
                        </aside>
                </aside>
                <hr><input type="submit" class="button" value="Doładuj">
               <input type="reset" class="button" value="Wyszyść"></form>
                </section>
    </body>
</html>
