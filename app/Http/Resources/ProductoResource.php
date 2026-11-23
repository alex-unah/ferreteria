<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transforma el recurso en un array estructurado y seguro.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nombre'         => $this->nombre,
            'descripcion'    => $this->descripcion ?? '',
            // Formateo del precio en Lempiras para la interfaz de usuario
            'precio'         => (float) $this->precio,
            'precio_hnl'     => 'L. ' . number_format($this->precio, 2),
            'stock' => (int) $this->stock,
            'disponibilidad' => $this->stock > 0 ? 'En Existencia' : 'Agotado',
            'fecha_registro' => $this->created_at ? $this->created_at->format('d/m/Y H:i A') : null,
            
            // Ocultamos intencionalmente costo_compra y campos sensibles del proveedor
        ];
    }
}