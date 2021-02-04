<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Stnvh\Partial\Zip;

class ConsultaCatastrosController extends Controller
{
    public function listaLink(){
        $aux = [];
        try {
            $url = file_get_contents('https://www.sri.gob.ec/web/guest/catastros');
            $doc = new DOMDocument('1.0', 'UTF-8');
            libxml_use_internal_errors(true);
            $doc->loadHTML($url);
            $xpath = new DomXPath($doc);
            $elementos = $xpath->query("//div[@class='ul-download']//div/p/span/a/@href");
            $i = 1;
            if (!is_null($elementos) && count($elementos)) {
                foreach ($elementos as $element) {
                    $aux[]=[
                        'id'=>$i,
                        'href' => $element->nodeValue,
                        'name'=> pathinfo($element->nodeValue, PATHINFO_FILENAME),
                        'ext'=> pathinfo($element->nodeValue, PATHINFO_EXTENSION)
                    ];
                    $i++;
                }
            }else{
                throw new \Exception('objeto sin elementos');
            }
            return response()->json(['val'=>true,'data'=>$aux]);
        }catch (\Exception $e){
            return response()->json(['val'=>false,'error'=>$e->getMessage()]);
        }
    }

    public function abrirZip(Request $datos){
        $zip = new Zip;
        $zip = $zip->open('http://www.sri.gob.ec/DocumentosAlfrescoPortlet/descargar/793e4768-6de4-462c-ad30-4b5544aacccb/CATASTRO%20EXPORTADORES%20HABITUALES_REBAJA%203%20PUNTOS%20IR%20EJERCICIO%20FISCAL%202019_VF.xlsx');
    }
}
