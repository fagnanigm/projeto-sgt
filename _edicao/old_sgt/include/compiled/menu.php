<nav class="navbar navbar-expand-md navbar-light fixed-top LogoNec">

    <a class="navbar-brand" href="/"><img src="/assets/img/logo.png"></a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">

        <ul class="navbar-nav mr-auto">         
            <?php if (is_logged()){ ?>
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Transporte </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/registros-cte.php">CTE <span class="sr-only">(current)</span> </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Cadastros </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/registros-natureza.php">Natureza</a>
                        <a class="dropdown-item" href="/registros-motorista.php">Motorista</a>
                        <a class="dropdown-item" href="/registros-veiculo.php">Veiculo</a>
                    </div>            
                </li>
            <?php } ?>
        </ul>


        <ul class="header-info">
            <?php if (is_logged()){ ?>
                <li><small>Logado como:</small><i class="fas fa-user"></i> <?php echo $logged_admin['Nome_Cliente']; ?></li>
                <li class="has-submenu">
                    <small>Empresa:</small>
                    <i class="fas fa-building"></i> <?php echo $business_context; ?> <i class="fas fa-sort-down margin-left-15"></i>
                    <div class="submenu">

                        <ul>
                            <li><a href="/include/model/context/set-context.php?context=Cruz de Malta" class="link-business">Cruz de Malta</a></li>
                            <li><a href="/include/model/context/set-context.php?context=NEC Brasil" class="link-business">NEC Brasil</a></li>
                            <li><a href="/include/model/context/set-context.php?context=XPTO Brasil" class="link-business">XPTO Brasil</a></li>
                            <li><a href="" class="btn btn-outline-success btn-sm">Cadastrar empresa</a></li>
                        </ul>

                    </div>
                </li>
                <li><a class="btn btn-outline-info my-2 my-sm-0" href="/include/model/user/get-logout.php" role="button">Sair</a></li>
            <?php }else{ ?>
                <li><a style="width: 8rem;" class="btn btn-outline-info my-2 my-sm-0" href="/login.php" role="button">Login</a></li>
            <?php } ?>
        </ul>

    </div>
</nav>

<div class="header-mask"></div>

<div class="container">
    <div class="alert alert-warning mt-3">
        Sistema operando em ambiente de homologação.
    </div>
</div>