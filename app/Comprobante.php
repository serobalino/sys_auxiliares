<?php

namespace App;

use App\Catalogos\Comprobante as Label;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Comprobante extends Model
{
    protected $primaryKey   =   "id_co";
    public $incrementing    =   false;

    protected $with         =   ["tipo"];

    protected $appends      =   ['valor'];

    protected $casts        =   [
        "comprobante"   =>  "object"
    ];

    public function tipo(){
        return $this->hasOne(Label::class,"id_tc","id_tc");
    }

    public function getValorAttribute()
    {
        $valor          =   0;
        $comprobante    =  json_decode($this->attributes['comprobante']);

        switch ($this->attributes['id_tc']) {
            case 1://factura
                $valor=$comprobante->info->importeTotal;
                break;
            case 2://nota de venta
                break;
            case 3://liquidacion de compra
                break;
            case 4://nota de credito
                $valor=$comprobante->info->valorModificacion*-1;
                break;
            case 5://nota de debito
                $valor=$comprobante->info->valorTotal;
                break;
            case 6://guia de remision
                break;
            case 7://comprobante de retencion
                $valor=0;
                if(@is_array($comprobante->impuestos->impuesto))
                    foreach ($comprobante->impuestos->impuesto  as $item)
                        $valor+=$item->valorRetenido;
                else
                    $valor+=@$comprobante->impuestos->impuesto->valorRetenido;

                break;
            case 8://entradas a espectaculos
                break;
            default:
                break;
        }
        return $valor;
    }

}
