<!DOCTYPE html>
<html ng-app="app">
<head>

    <title>SGT - Sistema Administrativo</title>

    <!-- bootstrap css -->
    <link rel="stylesheet" href="/assets/libs/bootstrap-4.1.1/css/bootstrap.min.css">


    <!-- toast -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/ng-toast/2.0.0/ngToast.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/ng-toast/2.0.0/ngToast-animations.min.css">
    
    <!-- application css -->
    <link href="/assets/css/app.css" rel="stylesheet" />

    <!-- fonts -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" href="/assets/img/ico.png" />

    <base href="/">

</head>
<body ng-controller="GlobalServicesCtrl as gl">

    <toast></toast>

    <!-- login -->
    <div ng-controller="Login.IndexController as vm" class="login-dashboard ng-hide" ng-hide="is_logged">

        <div class="login-box">

            <center><a href="/"><img src="/assets/img/logo_nec_white.png" class="img-fluid"></a></center>

            <h2 class="text-center font-size-16 text-uppercase margin-top-15"><i class="fa fa-lock" aria-hidden="true"></i> Acesso administrativo</h2>
            <form name="form" ng-submit="form.$valid && vm.login()" novalidate>
                <div class="form-group" ng-class="{ 'has-error': form.$submitted && form.username.$invalid }">
                    <label for="username">E-mail</label>
                    <input type="text" name="username" class="form-control" ng-model="vm.username" required />
                    <div ng-messages="form.$submitted && form.username.$error" class="help-block text-danger">
                        <div ng-message="required">O e-mail é obrigatório.</div>
                    </div>
                </div>
                <div class="form-group margin-top-10" ng-class="{ 'has-error': form.$submitted && form.password.$invalid }">
                    <label for="password">Senha</label>
                    <input type="password" name="password" class="form-control" ng-model="vm.password" required />
                    <div ng-messages="form.$submitted && form.password.$error" class="help-block text-danger">
                        <div ng-message="required">A senha é obrigatória.</div>
                    </div>
                </div>
                <div ng-if="vm.error" class="alert alert-danger margin-top-20">{{vm.error}}</div>
                <div class="form-group text-center margin-top-20">
                    <button class="btn btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</button>
                </div>
                
            </form>

        </div>

    </div>

    <!-- main app container -->
    <section class="ng-hide" ng-show="is_logged">
            
        <!-- sub header -->
        <div class="sub-header-mask"></div>

        <div class="sub-header">
            <div class="top-sub-header">
                <div class="logo">
                    <a href="/dashboard"><img src="/assets/img/logo.jpg"></a>
                </div>
                <ul class="sub-header-menu">
                    <li ng-if="logged_user.permission != 'cadastros'"><a href="/usuarios">Usuários</a></li>
                    <li ng-if="logged_user.permission != 'cadastros'"><a href="/empresas">Empresas</a></li>
                    <li ng-if="logged_user.permission != 'cadastros'"><a href="/configuracoes">Configurações</a></li>
                    <li class="has-dropdown">
                        <a href="#"><i class="icon-user icons"></i> {{ logged_user.username }} <i class="icon-arrow-down icons"></i></a>

                        <ul class="drop-sub-header-menu">
                            <li><a href="/usuarios/visualizar-usuario">Minha conta</a></li>
                            <li><a href="/usuarios/alterar-senha">Alterar senha</a></li>
                            <li><a href="#" ng-click="gl.logout()">Sair</a></li>
                        </ul>

                    </li>
                </ul>
            </div>


        </div>

        <!-- Context -->
        


        <!-- menu -->
        <div class="menu">

            <div class="box-menu" >
                <ul>
                    <li ng-class="{ active: $state.name == 'dashboard' }"><a href="/dashboard"><i class="icon-home"></i> Dashboard</a></li>
                    <li class="heading">
                        <h3 class="uppercase">Cadastros</h3>
                    </li>
                    <li ng-if="logged_user.permission != 'cadastros'" ng-class="{ active: $state.name == 'clientes' }"><a href="/clientes"><i class="icon-people icons"></i> Clientes</a></li>
                    <li ng-if="logged_user.permission != 'cadastros'" ng-class="{ active: $state.name == 'categorias' }"><a href="/categorias"><i class="icon-layers icons"></i> Categorias</a></li>
                    <li ng-class="{ active: $state.name == 'locais' }"><a href="/locais"><i class="icon-location-pin icons"></i> Locais</a></li>
                    <li ng-if="logged_user.permission != 'cadastros'" ng-class="{ active: $state.name == 'vendedores' }"><a href="/vendedores"><i class="icon-badge icons"></i> Vendedores</a></li>
                    <li ng-class="{ active: $state.name == 'veiculos' }"><a href="/veiculos"><i class="icon-rocket icons"></i> Veículos</a></li>
                    <li ng-class="{ active: $state.name == 'motoristas' }"><a href="/motoristas"><i class="icon-user icons"></i> Motoristas</a></li>

                    <li class="heading" ng-if="logged_user.permission != 'cadastros'">
                        <h3 class="uppercase">Comercial</h3>
                    </li>

                    <li ng-if="logged_user.permission != 'cadastros'" ng-class="{ active: $state.name == 'cotacoes' }"><a href="/cotacoes"><i class="icon-drawer icons"></i> Cotações</a></li>

                    <li ng-if="logged_user.permission != 'cadastros'" ng-class="{ active: $state.name == 'projetos' }"><a href="/projetos"><i class="icon-grid icons"></i> Projetos</a></li>

                    <li ng-if="logged_user.permission != 'cadastros'" ng-class="{ active: $state.name == 'autorizacao-de-servico' }"><a href="/autorizacao-de-servico"><i class="icon-briefcase icons"></i> Aut. de serviço</a></li>

                    <li ng-if="logged_user.permission != 'cadastros'" ng-class="{ active: $state.name == 'relatorios' }"><a href="/relatorios"><i class="icon-pie-chart icons"></i> Relatórios</a></li>
                    
                </ul>

            </div>            

        </div>

        <!-- content -->
        <div class="content">
            <div class="palco">
                <!-- <div class="alert alert-danger ng-hide" ng-show="is_error">{{ is_error_text }}</div> -->
                <div class="yAnimate" ui-view></div>
            </div>
        </div>

    </section>


    <!-- global loading -->
    <div class="global-loading ng-hide" ng-show="is_loading"><i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i></div>

    <!-- bootstrap -->
    <script src="/assets/libs/jquery/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="/assets/libs/bootstrap-4.1.1/js/bootstrap.min.js"></script>

    <!-- Twenn Max -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/TweenMax.min.js"></script>

    <!-- angular scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>
    <script src="/assets/libs/angular-locale-pt-br/angular-locale_pt-br.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.3/angular-animate.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-messages.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.18/angular-ui-router.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ngStorage/0.3.6/ngStorage.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-sanitize.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ng-toast/2.0.0/ngToast.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ngMask/3.1.1/ngMask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js"></script> 
    <script src="/assets/libs/rw-money-mask/rw-money-mask.min.js"></script>
    <script src="/assets/libs/ng-flow/dist/ng-flow-standalone.min.js"></script>

    <!-- application scripts -->
    <script src="/app/app-module.js"></script>
    <script src="/app/app.routes.js"></script>

    <!-- shared -->
    <script src="/app/shared/autentication/authentication.service.js"></script>
    <script src="/app/shared/services/services.controller.js"></script>
    <script src="/app/shared/services/services.factory.js"></script>
    <script src="/app/shared/modal/modal.controller.js"></script>

    <!-- components -->
    <script src="/app/components/home/index.controller.js"></script>
    <script src="/app/components/login/index.controller.js"></script>

    <!-- categorias -->
    <script src="/app/components/categorias/index.controller.js"></script>
    <script src="/app/components/categorias/visualizar.controller.js"></script>
    <script src="/app/components/categorias/gerencia.controller.js"></script>

    <!-- categorias -->
    <script src="/app/components/categorias/index.controller.js"></script>

    <!-- marcas -->
    <script src="/app/components/marcas/index.controller.js"></script>

    <!-- usuarios -->
    <script src="/app/components/usuarios/index.controller.js"></script>
    <script src="/app/components/usuarios/gerencia.controller.js"></script>
    <script src="/app/components/usuarios/visualizaController.js"></script>
    <script src="/app/components/usuarios/altera-senha.controller.js"></script>

    <!-- clientes -->
    <script src="/app/components/clientes/index.controller.js"></script>
    <script src="/app/components/clientes/visualizar.controller.js"></script>
    <script src="/app/components/clientes/importar.controller.js"></script>

    <!-- empresas -->
    <script src="/app/components/empresas/visualizar.controller.js"></script>
    <script src="/app/components/empresas/gerencia.controller.js"></script>
    <script src="/app/components/empresas/index.controller.js"></script>

    <!-- locais -->
    <script src="/app/components/locais/index.controller.js"></script>
    <script src="/app/components/locais/gerencia.controller.js"></script>
    <script src="/app/components/locais/visualizar.controller.js"></script>

    <!-- veiculos -->
    <script src="/app/components/veiculos/index.controller.js"></script>
    <script src="/app/components/veiculos/gerencia.controller.js"></script>
    <script src="/app/components/veiculos/visualizar.controller.js"></script>

    <!-- motoristas -->
    <script src="/app/components/motoristas/index.controller.js"></script>
    <script src="/app/components/motoristas/gerencia.controller.js"></script>
    <script src="/app/components/motoristas/visualizar.controller.js"></script>

    <!-- projetos -->
    <script src="/app/components/projetos/index.controller.js"></script>
    <script src="/app/components/projetos/visualizar.controller.js"></script>

    <!-- relatorios -->
    <script src="/app/components/relatorios/index.controller.js"></script>

    <!-- configuracoes -->
    <script src="/app/components/configuracoes/index.controller.js"></script>

    <!-- formas de pagamento -->
    <script src="/app/components/formas-de-pagamento/index.controller.js"></script>
    <script src="/app/components/formas-de-pagamento/gerencia.controller.js"></script>

    <!-- validades da proposta -->
    <script src="/app/components/validades-da-proposta/index.controller.js"></script>
    <script src="/app/components/validades-da-proposta/gerencia.controller.js"></script>

    <!-- tipos dos veiculos -->
    <script src="/app/components/tipos-dos-veiculos/index.controller.js"></script>
    <script src="/app/components/tipos-dos-veiculos/gerencia.controller.js"></script>

    <!-- equipamentos tipos comerciais -->
    <script src="/app/components/equipamentos-tipos-comerciais/index.controller.js"></script>
    <script src="/app/components/equipamentos-tipos-comerciais/gerencia.controller.js"></script>

    <!-- cotacoes textos padrões -->
    <script src="/app/components/cotacoes-textos-padroes/index.controller.js"></script>

    <!-- cfops -->
    <script src="/app/components/cfops/index.controller.js"></script>
    <script src="/app/components/cfops/gerencia.controller.js"></script>

    <!-- categorias taxas e licenças -->
    <script src="/app/components/categorias-de-taxas-e-licencas/index.controller.js"></script>
    <script src="/app/components/categorias-de-taxas-e-licencas/gerencia.controller.js"></script>

    <!-- tipos taxas e licenças -->
    <script src="/app/components/tipos-de-taxas-e-licencas/index.controller.js"></script>
    <script src="/app/components/tipos-de-taxas-e-licencas/gerencia.controller.js"></script>

    <!-- motivos do prazo de pagamento -->
    <script src="/app/components/motivos-do-prazo-de-pagamento/index.controller.js"></script>
    <script src="/app/components/motivos-do-prazo-de-pagamento/gerencia.controller.js"></script>

    <!-- condiçoes de pagamento -->
    <script src="/app/components/condicoes-de-pagamento/index.controller.js"></script>
    <script src="/app/components/condicoes-de-pagamento/gerencia.controller.js"></script>

    <!-- cotacoes -->
    <script src="/app/components/cotacoes/index.controller.js"></script>
    <script src="/app/components/cotacoes/gerencia.controller.js"></script>
    <script src="/app/components/cotacoes/visualizar.controller.js"></script>

    <!-- as -->
    <script src="/app/components/autorizacao-de-servico/index.controller.js"></script>
    <script src="/app/components/autorizacao-de-servico/gerencia.controller.js"></script>
    <script src="/app/components/autorizacao-de-servico/visualizar.controller.js"></script>

    <!-- vendedores -->
    <script src="/app/components/vendedores/index.controller.js"></script>
    <script src="/app/components/vendedores/gerencia.controller.js"></script>
    <script src="/app/components/vendedores/visualizar.controller.js"></script>

    <!-- scripts for fake backend -->

    <!-- <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-mocks.js"></script>
    <script src="/app/shared/fake-backend.js"></script>-->


</body>
</html