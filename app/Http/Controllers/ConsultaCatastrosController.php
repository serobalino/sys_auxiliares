<?php

namespace App\Http\Controllers;

use App\Http\Requests\Files\DownloadRemoteRequest;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Smalot\PdfParser\Parser;
use ZanySoft\Zip\Zip;
use Spatie\PdfToText\Pdf;

class ConsultaCatastrosController extends Controller
{
    private static function toTexto($dat)
    {
        if (is_string($dat)) {
//            $dat = utf8_encode ($dat);
//            $dat = str_replace("+"," ",$dat);
//            $dat = iconv("UTF-8", 'ISO-8859-1//TRANSLIT', $dat);
//            $dat = urldecode($dat);
            return ($dat);
        } else {
            return $dat;
        }
    }

    public function readResponse(Request $data)
    {
        $app = sys_get_temp_dir() . DIRECTORY_SEPARATOR . config("app.name");
        $folder = $app.DIRECTORY_SEPARATOR.$data->key.DIRECTORY_SEPARATOR."response";
        if(is_dir($folder)){
            $file = $folder.DIRECTORY_SEPARATOR.$data->file;
            if(file_exists($file)){
                $string = file_get_contents($file);
                $json_a = json_decode($string, true);
                return response()->json($json_a);
            }else{
                return [];
            }
        }else{
            return [];
        }
    }

    public function listaLink()
    {
        $aux = [];
        try {
            $url = file_get_contents('https://www.sri.gob.ec/web/guest/catastros');
            $doc = new DOMDocument();
            $doc->encoding = 'UTF-8';
            libxml_use_internal_errors(true);
            $doc->loadHTML(($url));
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
                            'name' => $this->toTexto(pathinfo($listaLinks->item($j)->nodeValue, PATHINFO_FILENAME)),
                            'ext' => pathinfo($listaLinks->item($j)->nodeValue, PATHINFO_EXTENSION),
                        ];
                    }
                    $aux[] = [
                        'title' => utf8_decode($titulo->item($i)->nodeValue),
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
        $archivos = $this->typeFile($datos, $local);
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

    private function typeFile($obj, string $path)
    {
        switch ($obj->ext) {
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
                    'key' => pathinfo(dirname($publicDesti), PATHINFO_FILENAME),
                    'file' => $archivo,
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
        $nueva = [];
        switch ($type) {
            case "text/plain":
                $data = file_get_contents($path);
                $data = str_replace("\r", "", $data);
                $data = explode("\n", $data);
                $labels = explode("\t", $data[0]);
                $obj = count($labels);
                array_splice($data, 0, 1);
                foreach ($data as $linea) {
                    $i = 0;
                    $item = explode("\t", $linea);
                    foreach ($labels as $keys) {
                        if ($obj > 1) {
                            $aux[mb_strtolower($keys)] = utf8_encode(@$item[$i]);
                        } else {
                            $aux = utf8_encode(@$item[$i]);
                        }
                        $i++;
                    }
                    $nueva[] = $aux;
                }
                break;
            case "application/vnd.ms-excel":
                $nueva = $this->openExcelFiles($path,"Xls");
                break;
            case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                $nueva = $this->openExcelFiles($path,"Xlsx");
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

    private function openExcelFiles($path, $tipo)
    {
        $inputFileType = $tipo;
        $inputFileName = $path;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $reader->setLoadAllSheets();
        $reader->load($inputFileName);

//        $hojas = 1;
//        $blocco = [];
//        for ($i = 0; $i < $hojas; $i++) {
//            $sheet = $hojas->getSheet($i);
//            $blocco[]= $sheet->toArray(null, true, true, true);
//        }
        dd($reader);
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

    private function pdfFile(string $path)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($path);
        $filePath = dirname($path);
        $destination = $filePath . DIRECTORY_SEPARATOR . "parsed";
        if (!is_dir($destination)) {
            mkdir($destination);
        }
        $lista = [];
        $archivo = "pdf.txt";
        $completePath = $destination . DIRECTORY_SEPARATOR . $archivo;
        $texto = $pdf->getText();

        file_put_contents($completePath, $texto);
        $lista[] = $completePath;

        return [];
    }
}
