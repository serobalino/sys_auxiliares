<?php

namespace App\Http\Controllers;

use App\Http\Requests\Files\DownloadRemoteRequest;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Stnvh\Partial\Zip as Partial;
use ZanySoft\Zip\Zip;
use ZipArchive;

class ConsultaCatastrosController extends Controller
{
    public function listaLink()
    {
        $aux = [];
        try {
            $url = file_get_contents('https://www.sri.gob.ec/web/guest/catastros');
            $doc = new DOMDocument();
            $doc->encoding = 'utf-8';
            libxml_use_internal_errors(true);
            $doc->loadHTML(utf8_decode($url));
            $xpath = new DomXPath($doc);
            $elementos = $xpath->query("//div[@class='journal-content-article']//div");
            $nivel = $elementos->item(0);
            $i = 0;
            $titulo = $xpath->query(".//h3/strong", $nivel);

            if (!is_null($elementos) && ($titulo->length)) {
                do {
                    $subnivel = [];
                    $links = $xpath->query(".//div", $nivel)->item($i);
                    $listaLinks = $xpath->query(".//a/@href", $links);
                    for ($j = 0; $j < $listaLinks->length; $j++) {
                        $subnivel[] = [
                            'href' => $listaLinks->item($j)->nodeValue,
                            'name' => pathinfo($listaLinks->item($j)->nodeValue, PATHINFO_FILENAME),
                            'ext' => pathinfo($listaLinks->item($j)->nodeValue, PATHINFO_EXTENSION),
                        ];
                    }
                    $aux[] = [
                        'title' => $titulo->item($i)->nodeValue,
                        'items' => $subnivel
                    ];
                    $i++;
                } while ($i < $titulo->length);
            } else {
                throw new \Exception('objeto sin elementos');
            }
            return response()->json(['val' => true, 'data' => $aux]);
        } catch (\Exception $e) {
            return response()->json(['val' => false, 'error' => $e->getMessage()]);
        }
    }


    public function downloadFile(DownloadRemoteRequest $datos)
    {
        $local = $this->copyFile($datos);
//        return $this->openExcel($datos,$local);
//        return $local;
        $archivos = $this->zipFile($local);
        $data = [];
        foreach ($archivos as $item){
//            return response()->streamDownload(function() use ($item){
//                echo $this->readFile($item['file']);
//            },"datos");
            $data[]=$this->readFile($item['file']);
        }
        return $data;
    }

    private function readFile($path)
    {
        $type = mime_content_type($path);
        switch ($type){
            case "text/plain":
                $data = utf8_decode(file_get_contents($path));
                $data = str_replace("\r", "", $data);
                $data = explode("\n",$data);
                $labels = explode("\t",$data[0]);
                array_splice($data, 0, 1);
                foreach ($data as $linea){
                    $i=0;
                    $item=explode("\t",$linea);
                    foreach ($labels as $keys){
                        $aux[mb_strtolower($keys)]=@$item[$i];
                        $i++;
                    }
                    $nueva[]=$aux;
                }
                break;
        }
        return $nueva;
    }

    private function copyFile($obj){
        $app = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR).config("app.name");
        if (!is_dir($app)){
            mkdir($app);
        }
        $hash = md5($obj->name);
        $path = $app.DIRECTORY_SEPARATOR.$hash;
        $file = "original.$obj->ext";
        $filePath = $path.DIRECTORY_SEPARATOR.$file;
        if (!is_dir($path)){
            mkdir($path);
        }
        if(!file_exists($filePath)){
            copy($obj->href, $filePath);
        }
        return $filePath;
    }

    private function openExcel($obj,$path)
    {
        $inputFileName = "Prueba.$obj->ext";

        /**  Identify the type of $inputFileName  **/
        $spreadsheet = new Spreadsheet();

        $inputFileType = "Xlsx";
        $inputFileName = $path;

        /**  Create a new Reader of the type defined in $inputFileType  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);
    }

    private function zipFile($path){
        $zip = Zip::open($path);
        $filePath = dirname($path);
        $destination = $filePath.DIRECTORY_SEPARATOR."uncompress";
        $zip->extract($destination);
        $lista = [];
        if ($handle = opendir($destination)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $lista[] = [
                        'file'  =>  $destination.DIRECTORY_SEPARATOR.$entry,
//                        'ext'   =>  pathinfo($entry, PATHINFO_EXTENSION)
                    ];
                }
            }
            closedir($handle);
        }
        return $lista;
    }
}
