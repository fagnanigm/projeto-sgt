<?php

    include("app.php");
    
    protectPage();

    $motorista = new Motorista();

    $ListaMotoristas = $motorista->ListarMotorista();
    
    include(required('header'));

    include(required('menu'));

?>  
  
<div class="container mt-3">

    <div class="row">
        <div class="col-md-6">
            <h1 class="Titulos">Motoristas</h1>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#cadastro-modal">Novo motorista</button>
        </div>
    </div>

        
    <div class="card">
        
        <div class="card-body"> 

            <table class="table table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 

                    foreach ($ListaMotoristas as &$Motorista) {
                        echo '<tr>';
                            echo '<th scope="row">'.$Motorista['Codigo_Motorista'].'</th>';
                            echo '<td>'.$Motorista['Nome'].'</td>';
                            echo '<td>'.$Motorista['CPF'].'</td>';
                        echo '</tr>';
                    }
                
                    ?>

                </tbody>
            </table>

        </div>

    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="cadastro-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <form name="CadastroMotorista" action="../Functions/Cadastrar_Motorista.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Motorista</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">          
             
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputCPF">CPF</label>
                                <input type="text" class="form-control" id="inputCPF" name="CPF" placeholder="Somente Numero" required>
                            </div>
                        </div>
                        <div class="col-md-8">

                            <div class="form-group">
                                <label for="inputNome">Nome</label>
                                <input type="text" class="form-control" id="inputNome" name="Nome" required>
                            </div>
                        </div>                                

                    </div>

                </div>
                        
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>

            </form>

        </div>
    </div>
</div>
     

<?php include(required('footer')); ?>