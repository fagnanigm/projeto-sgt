<?php

    include("app.php");
    
    protectPage();

    $natureza = new Natureza();

    $ListaNaturezas = $natureza->ListarNatureza();
    
    include(required('header'));

    include(required('menu'));

?>    
  
<div class="container mt-3">

    <div class="row">
        <div class="col-md-6">
            <h1 class="Titulos">Naturezas</h1>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#cadastro-modal">Nova natureza</button>
        </div>
    </div>

    <div class="card">
                  
        <div class="card-body text-dark"> 

            <table class="table table-sm table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Finalidade</th>
                    </tr>
                </thead>
                <tbody>

                <?php 
                foreach ($ListaNaturezas as &$Natureza) {
                    echo '<tr>';
                        echo '<th scope="row">'.$Natureza['Codigo_Natureza'].'</th>';
                        echo '<td>'.$Natureza['Descricao_Natureza'].'</td>';
                        echo '<td>'.$Natureza['finalidade_text'].'</td>';
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

            <form name="CadastroNatureza" action="../Functions/Cadastrar_Natureza.php" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Naturezas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">          

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="inputDescricao_Natureza">Nome</label>
                                <input type="text" class="form-control" id="inputDescricao_Natureza" name="Descricao_Natureza" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputFinalidade">Finalidade</label>
                                <select id="inputFinalidade" class="form-control" name="Finalidade" required>                                               
                                    <option value="0" selected >Normal</option>
                                    <option value="1">Complementar</option>
                                    <option value="2">Ajuste</option>
                                    <option value="3">Devolucao</option>
                                </select>
                            </div>
                        </div>   
                    </div>                               
                       
                    <div class="form-group">
                        <label for="inputCFOP_Dentro_Estado">CFOP dentro do Estado</label>
                        <select id="inputCFOP_Dentro_Estado" class="form-control" name="CFOP_Dentro_Estado" required>                           
                            <?php 
                                /*
                                Include_once("../API_Omie/Functions/ListarCFOPOmie.php"); 
                                $ListaCFOP = Listar_Cadastros_CFOP_Omie();

                                foreach ($ListaCFOP as &$CFOP) {
                                echo '<option value = "'.$CFOP['Codigo'].'">'.$CFOP['Codigo'].' - '.$CFOP['Descricao'].'</option>';
                                }
                                */
                            ?>                        
                        </select>
                    </div>
                        
                    <div class="form-group">
                        <label for="inputCFOP_Fora_Estado">CFOP fora do Estado</label>
                        <select id="inputCFOP_Fora_Estado" class="form-control" name="CFOP_Fora_Estado" required>
                        <?php 
                        /*
                        foreach ($ListaCFOP as &$CFOP) {
                        echo '<option value = "'.$CFOP['Codigo'].'">'.$CFOP['Codigo'].' - '.$CFOP['Descricao'].'</option>';
                        }*/
                        ?>
                        </select>
                    </div>
                          
                    <div class="form-group">
                        <div class="form-check">
                            <input class="" type="checkbox" id="inputCalculo_Automatico_Tributos" name="Calculo_Automatico_Tributos">
                            <label class="form-check-label" for="inputCalculo_Automatico_Tributos">
                                Cálculo automático do valor aproximado dos tributos usando a tabela IBPT
                            </label>
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