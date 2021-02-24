<?php

namespace App\Http\Controllers;

use App\Http\Requests\Files\DownloadRemoteRequest;
use DOMDocument;
use DOMXPath;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Smalot\PdfParser\Parser;
use ZanySoft\Zip\Zip;
use Spatie\PdfToText\Pdf;

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
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300);
        $local = $this->copyFile($datos);
//        return $this->openExcel($datos,$local);
//        return $local;
        $archivos = $this->typeFile($datos,$local);
        $this->limpiarCacheResponse($local);
        $data = [];
        foreach ($archivos as $item) {
//            return response()->streamDownload(function() use ($item){
//                echo $this->readFile($item['file']);
//            },"datos");
            $data[] = $this->writeResponse($this->readFile($item), $item);
        }
        return $data;
//        return $archivos;
    }

    private function limpiarCacheResponse(string $path)
    {
        $filePath = dirname($path);
        $destination = $filePath . DIRECTORY_SEPARATOR . "response";
        $files = glob($destination . DIRECTORY_SEPARATOR . "*");
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private function typeFile($obj,string $path)
    {
        switch ($obj->ext){
            case "zip":
                return $this->zipFile($path);
            case "pdf":
                return $this->pdfFile($path);
            default:
                return [];
        }
    }

    private function convert($size)
    {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    private function writeResponse(array $data, string $path)
    {
        $filePath = dirname($path, 2);
        $app = sys_get_temp_dir() . DIRECTORY_SEPARATOR . config("app.name");
        $destination = $filePath . DIRECTORY_SEPARATOR . "response";
        $publicDesti = str_replace($app, '', $destination);
        $files = glob($destination . DIRECTORY_SEPARATOR . "*");
        natsort($files);
        $lastFile = pathinfo(end($files), PATHINFO_FILENAME);
        if (!is_dir($destination)) {
            mkdir($destination);
        }
        $size = count($data);
        $parte = 10000;
        $desde = 0;
        $records = [];
        $nameFiles = $lastFile ? (int)$lastFile + $parte : 0;
        while (($desde - $parte) < $size) {
            $lista = array_slice($data, $desde, $parte, false);
            $archivo = "$nameFiles.json";
            $nrmRegistros = count($lista);
            if ($nrmRegistros) {
                $fp = fopen($destination . DIRECTORY_SEPARATOR . $archivo, 'w');
                fwrite($fp, json_encode($lista));
                fclose($fp);
                $records[] = [
                    'from' => $desde,
                    'lenght' => $nrmRegistros,
                    'size' => $this->convert(filesize($destination . DIRECTORY_SEPARATOR . $archivo)),
                    'path' => $publicDesti . DIRECTORY_SEPARATOR . $archivo,
                ];
                unset($lista);
            }
            $desde = $desde + $parte;
            $nameFiles = $nameFiles + $parte;
        }
        return $records;
    }

    private function readFile(string $path)
    {
        $type = mime_content_type($path);
        switch ($type) {
            case "text/plain":
                $data = file_get_contents($path);
                $data = str_replace("\r", "", $data);
                $data = explode("\n", $data);
                $labels = explode("\t", $data[0]);
                array_splice($data, 0, 1);
                foreach ($data as $linea) {
                    $i = 0;
                    $item = explode("\t", $linea);
                    foreach ($labels as $keys) {
                        if(strlen($keys)<20){
                            $aux[mb_strtolower($keys)] = utf8_encode(@$item[$i]);
                        }else{
                            $aux = utf8_encode(@$item[$i]);
                        }
                        $i++;
                    }
                    $nueva[] = $aux;
                }
                break;
        }
        return $nueva;
    }

    private function copyFile($obj)
    {
        $app = sys_get_temp_dir() . DIRECTORY_SEPARATOR . config("app.name");
        if (!is_dir($app)) {
            mkdir($app);
        }
        $hash = md5($obj->name);
        $path = $app . DIRECTORY_SEPARATOR . $hash;
        $file = "original.$obj->ext";
        $filePath = $path . DIRECTORY_SEPARATOR . $file;
        if (!is_dir($path)) {
            mkdir($path);
        }
        if (!file_exists($filePath)) {
            copy($obj->href, $filePath);
        }
        return $filePath;
    }

    private function openExcel($obj, $path)
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

    private function zipFile($path)
    {
        $zip = Zip::open($path);
        $filePath = dirname($path);
        $destination = $filePath . DIRECTORY_SEPARATOR . "uncompress";
        $zip->extract($destination);
        $lista = [];
        if ($handle = opendir($destination)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $lista[] = $destination . DIRECTORY_SEPARATOR . $entry;
                }
            }
            closedir($handle);
        }
        return $lista;
    }

    private function pdfFile(string $path){
        $parser = new Parser();
        $pdf    = $parser->parseFile($path);
        $filePath = dirname($path);
        $destination = $filePath . DIRECTORY_SEPARATOR . "parsed";
        if (!is_dir($destination)) {
            mkdir($destination);
        }
        $lista = [];
        $archivo = "pdf.txt";
        $completePath = $destination . DIRECTORY_SEPARATOR . $archivo;
        $texto = $pdf->getText();

        file_put_contents($completePath,$texto);
        $lista[]=$completePath;

        return $lista;
    }
}
