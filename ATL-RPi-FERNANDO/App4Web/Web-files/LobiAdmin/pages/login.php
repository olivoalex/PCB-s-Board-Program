<!--Author      : @arboshiki-->
<!--Changes By  : @fviana-->
<!-- 1. Mesclando com o PHP -->
<?php
  
   // Date in the past
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache");

   // Garantir que o session ja esta Started
   if ( ! isset($_SESSION) ) {
	   session_start();
   }
   
   // Verifica se variaveis estao na sessão, se nao estiver tem que direcionar para uma pagina de erro
   if ( ! isset($_SESSION['LOGGED']) ) {
	  $_SESSION['LOGGED'] = "N";   
   }
   
   // trabalhando com parametros recebidos   
   $_emp_id  = (isset($_SESSION['emp_id']) ? $_SESSION['emp_id'] : 123) * 3;  
   $_usr_id  = (isset($_SESSION['usr_id']) ? $_SESSION['usr_id'] : 456) * 2;
   
   $_SESSION["emp_id"] = $_emp_id;
   $_SESSION["usr_id"] = $_usr_id;
   
?>
<div id="teste">

	<!-- PRIMEIRA LINHA -->
    <div class="row">	
	    <!-- PRIMEIRA COLUNA -->        
        <div class="col-xs-12 col-lg-12">
		
            <div class="panel panel-light">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>Controle de Acesso</h4>
                    </div>
                </div>
                <div class="panel-body">
					<div class="callout callout-danger">
					   <h3>Login</h3>
					   <p>Não identificamos seu Login. Favor, Tente se identificar para continuarmos com nossas atividades.</p>
	   		 	       <p>Equipe de Suporte</p>
					   <p><i class='fa fa-phone'></i>&nbsp;+55 11 2998-0998</p>					   
					</div>

			        <form id="frmTeste" class="form-horizontal">
						<div class="panel-body">
							<fieldset>
								<legend>Horizontal form</legend>
								<div class="form-group">
									<label class="control-label col-sm-4">Username</label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<input type="text" name="username" class="form-control"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Email address</label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
											<input type="email" name="email" class="form-control"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Password</label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-key"></i></span>
											<input type="password" name="pass" class="form-control"/>
										</div>
									</div>
								</div>
								<div class="form-group form-group-sm">
									<label class="control-label col-sm-4">Confirm password</label>
									<div class="col-sm-8">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-key"></i></span>
											<input type="password" class="form-control" name="pass_confirmation"/>
										</div>
									</div>
								</div>
								<div class="form-group form-group-xs">
									<label class="control-label col-sm-4">Url</label>
									<div class="col-sm-8">
										<input type="text" name="url" class="form-control"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Image</label>
									<div class="col-sm-8">
										<input type="file" name="file"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Enter comment</label>
									<div class="col-sm-8">
										<textarea rows="4" class="form-control" name="comment"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Genre</label>
									<div class="col-sm-8">
										<select class="form-control" name="genre" data-bv-field="genre">
											<option value="">Choose a genre</option>
											<option value="action">Action</option>
											<option value="comedy">Comedy</option>
											<option value="horror">Horror</option>
											<option value="romance">Romance</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Select Gender</label>
									<div class="col-sm-8">
										<div class="radio-inline">
											<label>
												<input type="radio" name="gender" value="male"> Male
											</label>
										</div>
										<div class="radio-inline">
											<label>
												<input type="radio" name="gender" value="female"> Female
											</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-4">Browser</label>
									<div class="col-sm-8">
										<div class="row">
											<div class="col-xxs-12 col-xs-6">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="browsers[]" value="chrome"> Google Chrome
													</label>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox" name="browsers[]" value="firefox"> Firefox
													</label>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox" name="browsers[]" value="ie"> IE
													</label>
												</div>
											</div>
											<div class="col-xxs-12 col-xs-6">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="browsers[]" value="safari"> Safari
													</label>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox" name="browsers[]" value="opera"> Opera
													</label>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox" name="browsers[]" value="other"> Other
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="panel-footer text-right">
							<button type="reset" class="btn btn-default btn-pretty">Reset</button>
							<button class="btn btn-info btn-pretty">Submit</button>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	
    <script type="text/javascript">
	
		// Carregando scripts para validar formulario
        LobiAdmin.loadScript('js/plugin/jquery-validation/jquery.validate.min.js', function(){
            LobiAdmin.loadScript('js/plugin/jquery-validation/additional-methods.min.js', initPage)
        });
        
        function initPage(){
            
			initValidationDefaults();
            $('.panel').lobiPanel({
                editTitle: false,
                reload: false,
                unpin: false,
                sortable: true
            });
			
            $('#frmTeste').validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 12
                    },
                    email:{
                        required: true
                    },
                    pass_confirmation:{
                        required: true,
                        minlength: 5,
                        equalTo: "#frmTeste [name=pass]"
                    },
                    pass: {
                        required: true,
                        minlength: 5
                    },
                    file: {
                        required: true
                    },
                    url: {
                        required: true,
                        url: true
                    },
                    gender: {
                        required: true
                    },
                    'browsers[]': {
                        required: true
                    },
                    comment: {
                        required: true
                    },
                    genre: {
                        required: true
                    }
                },
				
                messages: {
                    username: {
                        required: "Usuário Obrigatório, favor informar.",
                        minlength: "Usar ao menos 3 caracteres para identificar um usuário."
                    },
                    email:{
                        required: "Informe um endereço de email para entrarmos em contato caso seja necessário."
                    },
                    pass: {
                        required: "Favor informar uma Senha contendo no mínimo 5 caracteres.",
                        minlength: "Senha precisa ter no mínimo 5 caracteres."
                    },					
                    pass_confirmation:{
                        required: "Favor confirmar a Senha digitarda anteriormente.",
                        minlength: "Senha precisa ter no mínimo 5 caracteres.",
                        equalTo: "Favor informar a mesma Senha digitada anteriormente."
                    },
                    file: {
                        required: "Selecione uma Foto para o seu avatar."
                    },
                    url: {
                        required: "Informe o seu Domínio (url)."
                    },
                    gender: {
                        required: "Informe o seu Sexo."
                    },
                    'browsers[]': {
                        required: "Indique para nós, quais browsers vc esta acostumado a usar."
                    },
                    comment: {
                        required: "Deixe um comentario."
                    },
                    genre: {
                        required: "Informe seu gênero de filme que mais gosta."
                    }
                },
				
                invalidHandler: function(event, validator) {
                    // 'this' refers to the form
                    var errors = validator.numberOfInvalids();
                    if (errors) {
                       var message = errors == 1
                                   ? 'Apenas um erro foi encontrado, revise o formulario e assim que corrigir, favor enviar novamente.<br>Equipe de Suporte.'
                                   : 'Foram encontrados ' + errors + ' campos com error.<br>Revise o formulario e assim que corrigir, favor enviar novamente.<br>Equipe de Suporte.';
				       Lobibox.alert('warning', {
                          msg: message,
						  callback: function(lobibox, type){
                             $('input[name=username]').focus();							   
                          }
                       });																			   
                    }
                },
				
				submitHandler: function( form ){
				   var dados = $( form ).serialize();
				   //alert("DAdos: "+dados);		
				   
				   $.ajax({
					  type: "POST",
					  url: "http://11.12.13.30/erp4web/App/JSON/processa.php",
				      data: dados,
					  success: function( data )
					  {
						 var dados = JSON.parse(data);
				 		 if ( dados['OK'] == true ) {
							Lobibox.alert('success', {
                                msg: "Seus dados foram registrados.<br>Aguarde nosso contato para liberar seu acesso.<br>Obrigado,<br>Equipe de Suporte.",
								callback: function (lobibox, type) {
							       // Fazendo um redirecionamento para o formulario de empresa
							       location.hash = 'empresa.php';
								}
                            });							
						 } else {
							Lobibox.alert('error', {
                                msg: "Algo aconteceu com seus dados, Verifique e envie novamente.<br>Obrigado,<br>Equipe de Suporte.",
								callback: function(lobibox, type){
                                   $('input[name=username]').focus();							   
                                }
                            });											
						 };
					  }
				   });
				   return false;
			   }
								
            });
			
			//
			// Quanto nao inportar a form usar um default q atende todas as forms do documento
			//
			//$.validator.setDefaults({
		    //  submitHandler: function() {
			//  alert("submitted!");
		    //  }
	        //});

        }
    </script>	
</div>