<?php
require_once __DIR__ . "/../config.php";

session_start();

class AuthDrive
{
    public function AuthDrive()
    {
        $this->client = new Google_Client();
        $this->client->setClientId($this->idCliente);
        $this->client->setClientSecret($this->secretCliente);
        $this->client->setRedirectUri($this->redirectUri);
        $this->client->addScope([Google_Service_Drive::DRIVE_FILE, Google_Service_Gmail::GMAIL_COMPOSE]);
        if (isset($_SESSION['access_token'])) {
            if ($this->client->isAccessTokenExpired()) {
                /*setcookie("DOCUMENT_TOKEN", $this->getAccessToken(), time()+3300,"/",  "estadisticafmec.uni.edu.pe", 0, true);
                echo "<script>window.location.reload()</script>";*/ }
            $this->client->setAccessToken($_SESSION['access_token']);
        } else {
            $_SESSION['access_token']= $this->getAccessToken();
            $this->client->setAccessToken($this->getAccessToken());
        }
    }

    private function getAccessToken()
    {
        $postdata = http_build_query(
            array(
                'client_secret' =>  $this->secretCliente,
                'grant_type' => 'refresh_token',
                'client_id' => $this->idCliente,
                'refresh_token' =>  REFRESHTOKEN
            )
        );
        $opts = array(
            'http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents('https://www.googleapis.com/oauth2/v4/token', false, $context);
        return $result;
    }
public function enviarEmail($email, $ccemail, $strSubject, $html)
{
    $service = new Google_Service_Gmail($this->client);
    try {
        date_default_timezone_set('America/Bogota');
        $strRawMessage = "From: UNI SIFIM <oeraaefim@uni.edu.pe>\r\n";
        $strRawMessage .= "To: $email\r\n";
        $strRawMessage .= "CC: $ccemail\r\n";
        $strRawMessage .= "Subject: =?utf-8?B?" . base64_encode($strSubject . " " . date("h:i:s a")) . "?=\r\n";
        $strRawMessage .= "MIME-Version: 1.0\r\n";
        $strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
        $strRawMessage .= "Content-Transfer-Encoding: base64" . "\r\n\r\n";
        $strRawMessage .= $html . "\r\n";
        // The message needs to be encoded in Base64URL
        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($mime);
        $service->users_messages->send("me", $msg);
        return true;
    } catch (Exception $e) {
        return "An error occurred: " . $e->getMessage();
    }
}

public function ver($id, $atac = null)
{
    try {
        $service = new Google_Service_Drive($this->client);
        // Print the names and IDs for up to 10 files.
        $fileId = $id;
        $response = $service->files->get($fileId, array(
            'alt' => 'media'
        ));
        $type = $service->files->get($fileId);
        header("Content-Type: " . $type->getMimeType());
        if ($atac == "attachment") {
            $dis = "attachment";
        } else {
            $dis = "inline";
        }
        header('Content-Disposition: ' . $dis . '; filename="' . $type->getName() );
        $content = $response->getBody()->getContents();
        return $content;
    } catch (Exception $e) {
        return "Error " . $e->getMessage();
    }
}

public function download($id, $atac = null)
{
    try {
        $service = new Google_Service_Drive($this->client);
        // Print the names and IDs for up to 10 files.
        $fileId = $id;
        $response = $service->files->get($fileId, array(
            'alt' => 'media'
        ));
        $type = $service->files->get($fileId);
        header("Content-Type: " . $type->getMimeType());
        if ($atac == "attachment") {
            $dis = "attachment";
        } else {
            $dis = "download";
        }
        header('Content-Disposition: ' . $dis . '; filename="' . $type->getName() );
        $content = $response->getBody()->getContents();
        return $content;
    } catch (Exception $e) {
        return "Error " . $e->getMessage();
    }
}
public function subirPdf($folderId, $archivo)
{
    try {
        $service = new Google_Service_Drive($this->client);

        $tmpnombre = $archivo['tmp_name'];
        $nombre = $archivo['name'];
        $tipo = $archivo['type'];

        move_uploaded_file($tmpnombre, $_SERVER["DOCUMENT_ROOT"] . '/app/view/examenes/files/' . $nombre);

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $nombre,
            'parents' => array($folderId)
        ));

        $content = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/app/view/examenes/files/' . $nombre);

        $file = $service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => $tipo,
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));

        unlink($_SERVER["DOCUMENT_ROOT"] . '/app/view/examenes/files/' . $nombre);

        return $file->id;
    } catch (Execption $e) {
        return $e->getMessage();
    }
}
public function updateFile($fileId, $archivo)
{
    try {
        $service = new Google_Service_Drive($this->client);
        $emptyFile = new Google_Service_Drive_DriveFile($this->client);
        $tmpnombre = $archivo['tmp_name'];
        $nombre = $archivo['name'];
        $tipo = $archivo['type'];

        move_uploaded_file($tmpnombre, $_SERVER["DOCUMENT_ROOT"] . '/app/view/comunicados/files/' . $nombre);
        $content = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/app/view/comunicados/files/' . $nombre);
        $service->files->update($fileId, $emptyFile, array(
            'data' => $content,
            'mimeType' => $tipo,
            'uploadType' => 'multipart'
        ));
        unlink($_SERVER["DOCUMENT_ROOT"] . '/app/view/comunicados/files/' . $nombre);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
}
public function SubirArchivo($folderId,$archivo)
{
    try {
        $service = new Google_Service_Drive($this->client);

        $tmpnombre = $archivo['tmp_name'];
        $nombre = $archivo['name'];
        $tipo = $archivo['type'];
        
        move_uploaded_file($tmpnombre, __DIR__ . '/../' . $nombre);

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $nombre,
            'parents' => array($folderId)
        ));

        $content = file_get_contents(__DIR__ . '/../' . $nombre);

        $file = $service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => $tipo,
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));

        unlink(__DIR__ . '/../' . $nombre);

        return $file->id;
    } catch (Execption $e) {
        return $e->getMessage();
    }
}
public function obtenerId($folderId, $name)
{
    try {
        $service = new Google_Service_Drive($this->client);
        // Print the names and IDs for up to 10 files.
        $pageToken = null;
        $optParams = array(
            'q' => " name = '" . $name . "' ",
            'spaces' => 'drive',
            'pageToken' => $pageToken,
            'fields' => 'nextPageToken, files(id, name,mimeType)',
        );
        $arr = ["resp" => null];
        $results = $service->files->listFiles($optParams);
        if (count($results->getFiles()) == 0) {
            $arr["resp"] = "not found";
        } else {
            foreach ($results->getFiles() as $file) {
                $arr['resp'] = $file->getId();
            }
        }
        return json_encode($arr);
    } catch (Exception $e) {
        return "Error " . $e->getMessage();
    }
}
private $idCliente = IDCLIENTE;
private $secretCliente = SECRETCLIENTE;
private $redirectUri = "https://developers.google.com/oauthplayground";
private $client;
public $token;
}
