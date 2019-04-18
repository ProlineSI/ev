<?php 
    if(isset($eticket)){
        if($eticket->confirmation == true){
            $qr = $eticket->qrImg;
            ?>
            <div class="center"><img src="<?=$qr->writeDataUri()?>" alt="tuQR"></div>
<?php   
        }else{
            if($eticket->type == 'cena'){?>
                <h1>Mi cumpleaños de 15 se realizará el día 15/05/19 a las 22hs ¿Deseas confirmar asistencia a Cena?</h1>
            <?php }else{?>
                <h1>Mi cumpleaños de 15 se realizará el día Sábado 15/05/19 a las 1:30hs ¿Deseas confirmar asistencia a Después de Cena?</h1>
                <?php }?>
            <button type= "button" id ="confirm-btn">Confirmar</button>
        <?php }
    ?>
        

<?php }else{
    echo 'Bienvenido a AccessGo, organiza tu evento de la manera más fácil y rápida';
}
?>

<script>
var token = <?= json_encode($this->request->param('_csrfToken')) ?>;
    $('#confirm-btn').on('click', function(){
        $.ajax({
            type: 'POST',
            url: baseUrl + 'invitados/confirmEticket',
            data: {
                "id": <?=  $eticket->id?>
            },
            beforeSend: function(xhr) { //Agregar esta línea cuando las peticiones post den error
                xhr.setRequestHeader('X-CSRF-Token', token);
            }
        })
        .done(function(data) {
            if ('errors' in data) {
                console.log(data);
                alertify.error(data['error']);
            } else {
                window.location.reload(false); 
                alertify.success(data['result']);
            }

        })
        .fail(function(data) {
            alertify.error(data);
        });
    });
</script>