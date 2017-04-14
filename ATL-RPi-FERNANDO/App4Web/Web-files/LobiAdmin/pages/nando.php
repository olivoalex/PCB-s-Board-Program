<!--Author      : @arboshiki-->
<!--Changes By  : @fviana-->
<div class="error-page error-404">
    <h1 class="error-page-code animated pulse"><i class="fa fa-warning"></i> Erro 404</h1>
    <h1 class="error-page-text">Pagina não encontrada</h1>
    <p class="error-page-subtext">A Pagina que você tentou abrir não foi encontrada. Estamos trabalhando para resolver esse inconveniente.</p>
    <p class="error-page-subtext">Tente novamente daqui uns minutos ou <a href="javascript:void(0)"> entre em contato com o seu Adiministrador</a>.</p>
    <ul class="error-page-actions">
        <li>
            <a href="javascript:void(0)" data-func="go-back" class="btn btn-primary btn-outline">
                <i class="fa fa-arrow-left"></i>
                Voltar</a>
        </li>
        <li>
            <a href="#dashboard.phtml" class="btn btn-primary btn-outline">
                <i class="fa fa-home"></i>
                Dashboard</a>
        </li>
        <li>
            <a href="javascript:void(0)" class="btn btn-primary btn-outline">
                <i class="fa fa-envelope-o"></i>
                Contact
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="btn btn-primary btn-outline">
                <i class="fa fa-bug"></i>
                Report
            </a>
        </li>
    </ul>
		
</div>

<script>
    $('.error-page [data-func="go-back"]').click(function(ev){
        window.history.back();
    });
</script>