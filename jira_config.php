<?php

if (ambiente-de-producao)
{
	$url = "http://192.168...";
	$autenticacao = "login:senha";
}
else // ambiente teste
{ 
	$url = "http://192.168...";
	$autenticacao = "login:senha";
}
define ("JIRA_URL", $url);
define ("JIRA_AUTENTICACAO", $autenticacao);
?>