<?php

namespace App\Http\Controllers;

use App\Http\Requests\Files\DownloadRemoteRequest;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Stnvh\Partial\Zip as Partial;
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

    public function listaServicios()
    {
        $aux = [];
        try {
            $url = file_get_contents('https://www.sri.gob.ec/web/guest/catastros');
            $doc = new DOMDocument('1.0', 'UTF-8');
            libxml_use_internal_errors(true);
            $doc->loadHTML($url);
            $xpath = new DomXPath($doc);
//            $elementos = $xpath->query("//div[@class='ul-download']//div/p/span/a/@href");
            $elementos = $xpath->query("//*[@id='ui-accordion-1-header-0']");
            $i = 1;
            if (!is_null($elementos) && count($elementos)) {
                foreach ($elementos as $element) {
                    $aux[] = [
                        'id' => $i,
                        'href' => $element->nodeValue,
                        'name' => pathinfo($element->nodeValue, PATHINFO_FILENAME),
                        'ext' => pathinfo($element->nodeValue, PATHINFO_EXTENSION)
                    ];
                    $i++;
                }
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
        return $this->openExcel($datos,$local);

    }

    private function copyFile($obj){
        $filename = "$obj->name.$obj->ext";
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
//        copy($obj->href, $tempImage);
        return $tempImage;
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

    private function zipFile(){
        $link = "http://www.sri.gob.ec/DocumentosAlfrescoPortlet/descargar/4019b098-693f-4f5a-980e-008029d74205/AZUAY.zip";
        $p = new Partial($link);
        $list = dd($p->find('AZUAY.txt'));
//        $temp_file = tempnam(sys_get_temp_dir(), 'zipSri');
//        if(!copy($link,$temp_file)){
//            $errors= error_get_last();
//            echo "COPY ERROR: ".$errors['type'];
//            echo "<br />\n".$errors['message'];
//        }else{
//            echo "File copied from remote!";
//            $zip = new ZipArchive;
//            $res = $zip->open($temp_file);
//            if ($res === TRUE) {
//                $zip->extractTo('/myzips/extract_path/');
//                $zip->close();
//                echo 'woot!';
//            } else {
//                echo 'doh!';
//            }
//        }
//        $archivo = file_get_contents( $link );
//        $zip = new ZipArchive;
//        $res = $zip->open($archivo);
//        if ($res === TRUE) {
//            $zip->extractTo('/myzips/extract_path/');
//            $zip->close();
//            echo 'woot!';
//        } else {
//            echo 'doh!';
//        }
    }
}
