<?php

    include("app.php");
    
    protectPage();

    $veiculo = new Veiculo();

    $ListaVeiculos = $veiculo->ListarVeiculo();
    
    include(required('header'));

    include(required('menu'));

?>  
  
<div class="container mt-3">

    <div class="row">
        <div class="col-md-6">
            <h1 class="Titulos">Veículos</h1>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#cadastro-modal">Novo veículo</button>
        </div>
    </div>

    <div class="card">

        <div class="card-body text-dark"> 

            <table class="table table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Placa</th>
                        <th scope="col">Renavam</th>
                        <th scope="col">Tipo de Veiculo</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    
                    foreach ($ListaVeiculos as &$Veiculo) {
                        echo '<tr>';
                            echo '<th scope="row">'.$Veiculo['Codigo_Veiculo'].'</th>';
                            echo '<td>'.$Veiculo['Placa'].'</td>';
                            echo '<td>'.$Veiculo['Renavam'].'</td>';
                            echo '<td>'.$Veiculo['Tipo_Veiculo_text'].'</td>';
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

            <form name="CadastroVeiculo" action="../Functions/Cadastrar_Veiculo.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Veículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">     

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputPlaca">Placa</label>
                                <input type="text" class="form-control" id="inputPlaca" name="Placa"  required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputRenavam">Renavam</label>
                                <input type="text" class="form-control" id="inputRenavam" name="Renavam" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputUF_Veiculo">UF</label>
                                <input type="text" class="form-control" id="inputUF_Veiculo" name="UF_Veiculo" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputTipo_Veiculo">Tipo de Veiculo</label>
                                <select id="inputTipo_Veiculo" class="form-control" name="Tipo_Veiculo" required>                                               
                                    <option value = "0" selected >Tração</option>
                                    <option value = "1">Torque</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputTipo_Carroceria">Tipo de Carroceria</label>
                                <select id="inputTipo_Carroceria" class="form-control" name="Tipo_Carroceria" required>                                               
                                    <option value = "0" selected >Não-Aplicável</option>
                                    <option value = "1">Aberta</option>
                                    <option value = "2">Fechada</option>
                                    <option value = "3">Granelera</option>
                                    <option value = "4">Porta Container</option>
                                    <option value = "5">Sider</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputTipo_Rodado">Tipo Rodado</label>
                                <select id="inputTipo_Rodado" class="form-control" name="Tipo_Rodado" required>                                               
                                    <option value = "0" selected >Não-Aplicável</option>
                                    <option value = "1">Truck</option>
                                    <option value = "2">Toco</option>
                                    <option value = "3">Cavalo Mecânico</option>
                                    <option value = "1">VAN</option>
                                    <option value = "2">Utilitário</option>
                                    <option value = "3">Outros</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputTara_Kg">Tara (kg)</label>
                                <input type="text" class="form-control" id="inputTara_Kg" name="Tara_Kg"  required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputCapacidade_Kg">Capacidade (Kg)</label>
                                <input type="text" class="form-control" id="inputCapacidade_Kg" name="Capacidade_Kg" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputCapacidade_M3">Capacidade (M³)</label>
                                <input type="text" class="form-control" id="inputCapacidade_M3" name="Capacidade_M3" required>
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