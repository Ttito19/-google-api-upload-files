<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div style="display: flex;" class="container">
		<a href="formulario.php" style="margin: auto; width: 50%"><button type="button" class="btn btn-success" style="margin: auto; width: 100%"><b>Subir Archivo</b></button></a>
	</div>
<div style="display: flex;flex-direction: row;flex-wrap: wrap;justify-content: center;padding-top:40px;padding-bottom:40px;">

<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/classes/AuthDrive.php';
$objAuthDrive=new AuthDrive();

$folderId="1l031mniRYswoM8pejom6kgMoPqigIoV2";

$client = new Google_Client();
//$client->setAuthConfig('client_secret.json');
$client->addScope(Google_Service_Drive::DRIVE);

try {
	$client->setAccessToken($_SESSION['access_token']);
	$service = new Google_Service_Drive($client);

  // Print the names and IDs for up to 10 files.
  $pageToken=null;
	$optParams = array(
		'q' => " '".$folderId."' in parents",
		//'q' => "not name contains 'jwlibrary'",
	  //'pageSize' => 40,
	  'spaces' => 'drive',
        'pageToken' => $pageToken,
        'fields' => 'nextPageToken, files(id, name,mimeType,thumbnailLink,webViewLink)',

	);
	$results = $service->files->listFiles($optParams);

	if (count($results->getFiles()) == 0) {
	    print "No hay archivos encontrados.\n";
	} else {
	    foreach ($results->getFiles() as $file) {
	        ?>
	        		<div style="background-color:#9995;display: flex;flex-direction: column;margin: auto;width: 350px;box-shadow: 2px 2px 3px #999;border: 1px solid rgba(153, 153, 153, 0.3);justify-content: center;align-content: center;margin:10px;padding-bottom: 15px;">
	        			<div class="w-100 text-center">
									 <img src="<?=$file->getThumbnailLink()   ?>" alt=""> 
								

 

	        				<!-- <h6><?php echo $file->getMimeType()?></h6> -->
	        			</div>
	        			<div class="w-100 text-center mt-2">
								<?php echo $file->getName() ?><br>
								<a target="_blank" href="action/ver.php?id=<?php echo $file->getId() ?>">Ver</a><br>
								<a target="_blank" href="action/download.php?id=<?php echo $file->getId() ?>">Descargar</a>
								</div>
	        		</div>
	        	
	        <?php
	    }
	}

} catch (Exception $e) {
  echo $e->getMessage();
}
?>
</div>
</body>
</html>