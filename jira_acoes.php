<?php
include_once ("jira_config.php");

//recebe e guarda os dados do form html
$data = array('fields' => array(
			'summary' 			=> $_POST['summary'],
			'description' 		=> $_POST['description'],
			"customfield_10200" => $_POST["chamadoid"],
			'project' 			=> array('key' => $_POST['project']),
			'issuetype' 		=> array('name' => $_POST['issuetype']),
			'priority' 			=> array('name' => $_POST['priority']),
			'reporter' 			=> array('name' => $_POST['reporter']),
			'assignee' 			=> array('name' => $_POST['assignee'])
));

$curl = curl_init();
$headers = array("Accept: application/json",
				"Authorization: Basic " .base64_encode(JIRA_AUTENTICACAO),
				"Content-Type: application/json");
 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_VERBOSE, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl, CURLOPT_URL, JIRA_URL."/rest/api/latest/issue/");
$result = curl_exec($curl);
$curl_error = curl_error($curl);

if ($curl_error) {
    echo "cURL Error: $curl_error";
} else {
    echo $result;
}

curl_close($curl);
?>