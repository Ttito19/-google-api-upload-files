<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../classes/AuthDrive.php';

$objAuthDrive=new AuthDrive();


try{
		foreach($_FILES['file']['tmp_name'] as $key => $tmp_name)
		{
			$file_name = $key.$_FILES['file']['name'][$key]; // nombre
			$file_size =$_FILES['file']['size'][$key]; // tamaño -> no se esta usando
			$file_tmp =$_FILES['file']['tmp_name'][$key]; // no se que es 
			$file_type=$_FILES['file']['type'][$key];  // tipo 
			
			$archivo = array( "tmp_name" => $file_tmp,
                "name" => $file_name,
				"type" => $file_type );
				
			echo "ID: ".$objAuthDrive->subirArchivo("1l031mniRYswoM8pejom6kgMoPqigIoV2",$archivo);
			echo "<br>";
		}
	?>
	<script>
	setTimeout(() => {
		location.assign('<?= HTTP; ?>');
	}, 1500);
	</script>
	<?php
}catch(Exception $e){
	echo $e->getMessage();
}
?>