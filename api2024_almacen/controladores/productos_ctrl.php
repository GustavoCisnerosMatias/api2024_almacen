<?php
class productos_ctrl{
    public $M_Producto  = null;
    public function __construct(){
        $this -> M_Producto = new m_productos();
        
    }
    public function listarSql($f3){
        $cadenaSql = "";
        $cadenaSql = $cadenaSql." select * ";
        $cadenaSql = $cadenaSql."  FROM productos ";
        // $cadenaSql = $cadenaSql." where estado = 'A' ";

        $items = $f3->DB->exec($cadenaSql);
        echo json_encode(
            [
                'Mensaje' => count($items)>0? 'Operacion Exitosa ': 'No Hay regustro para la consulta',
                'cantidad' => count($items),
                'data' => $items
            ]
        );
    }//fin listar Sql

    public function listarProductos($F3){
        $productos = $this->M_Producto->find();
        $items = array();
        foreach($productos as $Objprod){
            $items[] = $Objprod ->cast();
        }
        //armar el Json 
        echo json_encode(
            [
                'Mensaje' => count($items)>0? 'Operacion Exitosa ': 'No Hay regustro para la consulta',
                'cantidad' => count($items),
                'data' => $items
            ]
        );

    }//fin
    public function fun_retornaProductoxid($f3){
        $producto_id = $f3->get('POST.producto_id'); //debe tener este nombre al momento de enviar desde el cliente 
    //    echo 'id: '.$producto_id;
       $this->M_Producto->load(['id= ?',$producto_id ]);
       $items = array();
       $msg = "";
       
       if($this->M_Producto->loaded()>0){
        $msg = "Consulta Con Exito ";
        $items= $this->M_Producto->cast();


       }else{
        $msg = "El producto con el id: ".$producto_id. " no Existe ";
       }
       echo json_encode(
        [
            'Mensaje' => $msg,
            'cantidad' => count($items),
            'data' => $items
        ]
    );
    }
    public function fun_retornaProductoxidSql($f3){
        $producto_id = $f3->get('POST.producto_id'); //debe tener este nombre al momento de enviar desde el cliente 
        $cadenaSql = "";
        $cadenaSql = $cadenaSql." select * ";
        $cadenaSql = $cadenaSql."  FROM productos  ";
        $cadenaSql = $cadenaSql." where id = ". $producto_id . ";";

        $items = $f3->DB->exec($cadenaSql);
        echo json_encode(
            [
                'Mensaje' => count($items)>0? 'Operacion Exitosa ': 'No Hay regustro para la consulta',
                'cantidad' => count($items),
                'data' => $items
            ]
        );
      
 }
 public function listarProductosNombre($f3) {
    // Corrige el nombre de la variable a $F3
    $producto_Nom = $f3->get('POST.producto_nom');
    
    // Corrige la consulta con un placeholder adecuado
    $productos = $this->M_Producto->find(['nombre LIKE ?', '%' . $producto_Nom . '%']);
    
    $items = array();
    foreach ($productos as $Objprod) {
        $items[] = $Objprod->cast();
    }
    
    // Armar el JSON
    echo json_encode([
        'Mensaje' => count($items) > 0 ? 'Operacion Exitosa' : 'No Hay registro para la consulta',
        'cantidad' => count($items),
        'data' => $items
    ]);
} // fin


public function insertarProducto($f3){
    $producto = new m_productos();
    $mensaje = "";
    $newid = 0;
    $producto->load([$f3->get('POST.id')]);
    if($producto->loaded()==0){
        $mensaje = "EL PRODUCTO YA EXISTE";
    }else{
        $this->M_Producto->set('codigo',$f3->get('POST.prod_cod'));
        $this->M_Producto->set('nombre',$f3->get('POST.prod_nombre'));
        $this->M_Producto->set('stock',$f3->get('POST.prod_stock'));
        $this->M_Producto->set('precio',$f3->get('POST.prod_precio'));
        $this->M_Producto->set('activo',$f3->get('POST.prod_activo'));
        $this->M_Producto->set('imagen',$f3->get('POST.prod_imagen'));
        
        $this->M_Producto->save();
        $mensaje = "Se registro Correctamentee";
        $newid = $this->M_Producto->get('id');
    }
    echo json_encode(
        [
            'mensaje'=>$mensaje,
            'id'=>$newid
        ]
        );
}

public function updateProductoSQL($f3){
        
    $producto = new m_productos();
    $mensaje = "";
    // $newid = 0;
    $producto->load([$f3->get('POST.id')]);
    if($producto->loaded()==0){
        $mensaje = "EL PRODUCTO A MODIFICAR NO EXISTE";
    }else{
    $cadenaSQL = "";
    $cadenaSQL .= "UPDATE productos SET ";
    $cadenaSQL .= "codigo = '" . $f3->get('POST.prod_cod') . "', ";
    $cadenaSQL .= "nombre = '" . $f3->get('POST.prod_nombre') . "', ";
    $cadenaSQL .= "stock = '" . $f3->get('POST.prod_stock') . "', ";
    $cadenaSQL .= "precio = '" . $f3->get('POST.prod_precio') . "', ";
    $cadenaSQL .= "activo = '" . $f3->get('POST.prod_activo') . "', ";
    $cadenaSQL .= "imagen = '" . $f3->get('POST.prod_imagen') . "' ";
    $cadenaSQL .= "WHERE id = '" . $f3->get('POST.id') . "';";
    $items = $f3->DB->exec($cadenaSQL);
    $mensaje = "SE MODIFICO CORRECTAMENTE EL PRODUCTO";
}
    echo json_encode(
        [
        'mensaje'=>$mensaje
        ]

    );

 }

 public function eliminarProducto($f3){
    $newid=0;
    $producto_cod = $f3->get('POST.prod_cod');
    $this->M_Producto->load(['codigo=?', $producto_cod]);
    $mensaje="";
    if($this->M_Producto->loaded()>0){
        $this->M_Producto->erase();
        $mensaje="El Producto fue eliminado ";
        $newid = 1;

    }else{
        $mensaje ="El Producto no existe";
        $newid = 0;

    }
    echo json_encode(
        [
            'mensaje' =>$mensaje,
            'id'=>$newid

        ]

    );
}


}

?>


 