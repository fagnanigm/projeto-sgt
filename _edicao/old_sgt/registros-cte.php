<?php

    include("app.php");
    
    protectPage();

    $query = "SELECT * FROM sgt_cte ORDER BY create_time";
    $ctes = sqlsrv_query($connection, $query, array()) or die (mssql_get_last_message());
    
    include(required('header'));

    include(required('menu'));

?>  

<div class="loading-global ng-hide"><p>Carregando...</p></div>

<div class="container marketing">

    <div class="row mt-3">
        <div class="col-md-6">
            <h1 class="Titulos">Conhecimento de Transporte Eletrônico </h1>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn btn-info btn-sm" href="/cadastrar-cte.php">Novo CTE</a>
        </div>
    </div>

    <div class="card">
        
        <div class="card-body"> 

            <!--Tabela de Conhecimentos-->
            <table class="table table-sm table-hover table-cte">
                <thead class="thead-light">
                    <tr>
                        <th width="60" style="font-size: 11px;">Nº CTE</th>
                        <th width="220">Tomador</th>
                        <th width="220">Destinatário</th>
                        <th width="415">Dados Sefaz</th>
                        <th width="100">Status</th>
                        <th scope="col">Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 

                        while ($row = sqlsrv_fetch_array($ctes, SQLSRV_FETCH_ASSOC)) {  

                            $dados = json_decode($row['content']);
                            $dados_acbr = json_decode($row['return_acbr']);

                            echo '<tr>';
                                echo '<th>'.($row['cod_cte'] == 0 ? '' : $row['cod_cte']).'</th>';
                                echo '<td>'.cut_string($dados->informacoes_gerais->tomador->nome,30).'</td>'; 
                                echo '<td>'.cut_string($dados->informacoes_gerais->destinatario->nome,25).'</td>'; 
                                echo '<td>'.($row['return_acbr'] == '0' ? '' : 
                                    '<strong>Chave</strong>: '.$dados_acbr->cte->ChCTe.'<br />'.'<strong>Protocolo</strong>: '.$dados_acbr->cte->NProt
                                ).'</td>'; 
                                echo '<td>'.($row['return_acbr'] == '0' ? 'CTE Não emitido' : $dados_acbr->cte->XMotivo ).'</td>';
                                echo '<td>'.date('d/m/Y H:i',$row['create_time']).'</td>';
                                echo '<td>';

                                    echo '<div class="btn-group" role="group">';
                                        echo '<button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                            echo 'Ações';
                                        echo '</button>';
                                        
                                        echo '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                                            echo ($row['return_acbr'] == '0' ? 
                                                '<a href="/cadastrar-cte.php?id='.$row['id'].'" class="dropdown-item">Editar</a>' : 
                                                '<a href="/cadastrar-cte.php?id='.$row['id'].'" class="dropdown-item">Visualizar</a>' );

                                            echo ($row['return_acbr'] == '0' ? 
                                                '<a href="" class="dropdown-item pre-emitir-cte-btn" data-id="'.$row['id'].'" data-cte=\''.$row['content'].'\'>Emitir</a>':
                                                '<a href="javascript:void(0)" class="dropdown-item">Imprimir</a>' );
                                        echo '</div>';
                                    echo '</div>';

                                echo '</td>';

                            echo '</tr>';                        
                        } 

                    ?>                        
                    
                </tbody>
            </table>

        </div>
    </div>


    <!-- Modal -->
  <div class="modal fade" id="num-cte-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Defina o número do CTE</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            
            <label>Número do CTE</label>
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="global_numero_cte">
            <div class="input-group-append">
              <button class="btn btn-primary emitir-cte-btn" type="button" data-id="" data-cte="">Emitir</button>
            </div>
          </div>

          <div class="alert alert-danger ng-hide cte-error">

          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

</div><!-- /.container -->

<?php include(required('footer')); ?>

