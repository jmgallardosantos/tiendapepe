<?php

namespace App\Generico;

use App\Models\Articulo;
use ValueError;

class Carrito
{
    /**
     * @var Linea[] $lineas
     */
    private array $lineas;
    private float $total_carrito;



    public function __construct()
    {
        $this->lineas = [];
        $this->total_carrito = 0;

    }

    public function insertar($id)
    {
        if (!($articulo = Articulo::find($id))) {
            throw new ValueError('El artículo no existe.');
        }

        if (isset($this->lineas[$id])) {
            $this->lineas[$id]->incrCantidad();
        } else {
            $this->lineas[$id] = new Linea($articulo);
        }
    }




    public function eliminar($id)
    {
        if (!isset($this->lineas[$id])) {
            throw new ValueError('Artículo inexistente en el carrito.');
        }

        $this->lineas[$id]->decrCantidad();
        if ($this->lineas[$id]->getCantidad() == 0) {
            unset($this->lineas[$id]);
        }
    }

    public function sumar($id)
    {
        if (!isset($this->lineas[$id])) {
            throw new ValueError('Artículo inexistente en el carrito.');
        }

        $this->lineas[$id]->incrCantidad();

    }



    public function quitar($id)
    {
        if ($this->lineas[$id]) {
            unset($this->lineas[$id]);
        }
    }

    public function vacio(): bool
    {
        return empty($this->lineas);
    }

    public function getLineas(): array
    {
        return $this->lineas;
    }

    public static function carrito()
    {
        if (session()->missing('carrito')) {
            session()->put('carrito', new static());
        }

        return session('carrito');
    }
    public function recalcularTotal()
    {
        $this->total_carrito = 0;
        foreach ($this->lineas as $linea) {
            $this->total_carrito += $linea->getTotalArticulo();
        }
    }


    public function getTotalCarrito()
    {
        return $this->total_carrito;
    }

//////////




    public function vaciar($id)
    {
        if (!isset($this->lineas[$id])) {
            throw new ValueError('Artículo inexistente en el carrito.');
        }
        if ($this->lineas[$id]->getCantidad() > 0) {
            unset($this->lineas[$id]);
        }
    }



}
