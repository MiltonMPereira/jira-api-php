<?php
include_once ("jira_config.php");
	
	//pega os projetos do jira e define configs curl
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, JIRA_URL.'/rest/api/latest/project');
	curl_setopt($curl, CURLOPT_ENCODING, '');
	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json",
												"Authorization: Basic " .base64_encode(JIRA_AUTENTICACAO),
												"Content-Type: application/json"));
	$arrProjetos['projects'] = array('key' => ['key']);
	$json_arr['fields'] = $arrProjetos;
	$json_string = json_encode ($json_arr);
	curl_setopt($curl, CURLOPT_POSTFIELDS,$json_string);
	$proj = curl_exec($curl);
	$projeto = json_decode($proj, true);
	
	//pega todos os usuarios
	curl_setopt($curl, CURLOPT_URL, JIRA_URL.'/rest/api/latest/group/member?groupname=jira-software-users');
	$arrUsuarios['usuarios'] = array('name' => ['name']);
	$json_arr['fields'] = $arrUsuarios;
	$json_string = json_encode ($json_arr);
	$usu = curl_exec($curl);
	$usuario = json_decode($usu, true);
	
	//pega as prioridades das tarefas
	curl_setopt($curl, CURLOPT_URL, JIRA_URL.'/rest/api/latest/priority');
	$arrPrioridade['priority'] = array('name' => ['name']);
	$json_arr['fields'] = $arrPrioridade;
	$json_string = json_encode ($json_arr);
	$priority = curl_exec($curl);
	$prioridade = json_decode($priority, true);
	curl_close($curl);
?>
<html>
<head>
<meta charset="utf-8">
<script src="../static/js/jquery.js"></script>
</head>
<body>
<div id="dados">
	<h1>Criar Tarefa</h1>
	<form id="create-form">
	
		Título da Tarefa:	<input type="text" name="summary" id="summary" value=""/><br /><br />
		Descrição da Tarefa:<input type="text" name="description" id="description" value="" /><br /><br />
		
		Tipo Tarefa:<select name="issuetype" id="issuetype" value=""/>
			<option value="Problema">Problema</option>
			<option value="Melhoria">Melhoria</option>
			<option value="Tarefa">Tarefa</option>
			<option value="Parametrização">Parametrização</option>
		</select><br /><br />
		
		Prioridade:<select name="priority" id="priority" value=""/>
		<?php
			foreach($prioridade as $nome) {
				echo '<option value="' . $nome["name"] . '">' . $nome["name"] . '</option>';
		}
		?>
		</select><br /><br />
		
		Projeto: <select name="project" id="project" value=""/>
		<?php
			foreach($projeto as $nome) {
				echo '<option value="' . $nome["key"] . '">' . $nome["name"] . '</option>';
		}
		?>
		</select><br /><br />
		
		Solicitante: <select name="reporter" id="reporter" value=""/>
		<?php
			foreach($usuario["values"] as $nome) {
				echo '<option value="' . $nome["name"] . '">' . $nome["displayName"] . '</option>';
		}
		?>
		</select><br /><br />
		
		Responsável:<select name="assignee" id="assignee" value=""/>
					<option value=""></option>
		<?php
			foreach($usuario["values"] as $nome) {
				echo '<option value="' . $nome["name"] . '">' . $nome["displayName"] . '</option>';
		}
		?>
		</select><br /><br />
		
		Chamado ID:<input type="text" name="chamadoid" id="chamadoid" value=""/><br /><br />
		
		<input type="button" id="button" value="Criar"/><br /><br />
	</form>
</div>
<script>
//passa dados do html por post para o php que cria tarefas
$('#button').click(function() {
	 $.ajax({
	   type: "POST",
	   dataType: "html",
	   url: "jira_action.php",
	   data: $('#create-form').serialize(),
	   success: function(data){
	   var obj = JSON.parse(data);
		  console.log(obj.key);
	   }
	});
});
</script>
</body>
</html>