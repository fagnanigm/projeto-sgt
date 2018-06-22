<?php

    include("app.php");
    
    protectPage();
    
    include(required('header'));

    include(required('menu'));

?>

<div class="bg-overlay">

    <section class="text-center home-section">

        <div class="container">
            <h1 class="jumbotron-heading">Sistema de Gestão de Transporte</h1>
            <p class="lead text-muted color-white">Emissor de CTe, MDFe e muito mais! </p>
            <p><a class="btn btn-primary color-white my-2 my-sm-0" href="/registros-cte.php" role="button"> Comece Agora Mesmo </a></p>
        </div>
        
    </section>          

</div>

<?php include(required('footer')); ?>