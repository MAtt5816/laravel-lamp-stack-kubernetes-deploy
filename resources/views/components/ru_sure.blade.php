<script>
    $(document).ready(function(){
        $(".delete").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#ru_sure').show();
            var link = $(e.target).attr('href');
            $('#delete_button').attr('onClick', "window.location='"+ link +"'");
        });
        $(".close").click(function() {
            location.reload();
        });
    });
</script>

<section class="alert" id="ru_sure">
    <h3>Alert</h3><a class="close" href ="#"><i class="fa fa-close"></i></a>
    <hr>
    <h4>Czy na pewno chcesz usunąć ten element?</h4>
    @csrf
    <br><button class="button" type="submit" id="delete_button" onClick="">Usuń</button><br><br>
</section>
