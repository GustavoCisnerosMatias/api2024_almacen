
<?php
class pedidos_detalle_ctrl{
    public $M_Pedido_Detalle = null;
    public function __construct(){
        $this -> M_Pedido_Detalle = new m_pedidos_detalle();
        
    }

    public function listarPedidosDetalle($F3){
        $pedidosDet = $this->M_Pedido_Detalle->find();
        $items = array();
        foreach($pedidosDet as $ObjPedDet){
            $items[] = $ObjPedDet ->cast();
        }
        //armar el Json 
        echo json_encode(
            [
                'Mensaje' => count($items)>0? 'Operacion Exitosa ': 'No Hay registro para la consulta',
                'cantidad' => count($items),
                'data' => $items
            ]
        );

    }

    public function fun_retornaPedidosDetalleId($f3){
        $pedidosDet_id = $f3->get('POST.pedidosDet_id'); //debe tener este nombre al momento de enviar desde el cliente 
    //    echo 'id: '.$producto_id;
       $this->M_Pedido_Detalle->load(['id= ?',$pedidosDet_id ]);
       $items = array();
       $msg = "";
       
       if($this->M_Pedido_Detalle->loaded()>0){
        $msg = "Consulta Con Exito ";
        $items= $this->M_Pedido_Detalle->cast();


       }else{
        $msg = "El Pedido detalle con el id: ".$pedidosDet_id. " no Existe ";
       }
       echo json_encode(
        [
            'Mensaje' => $msg,
            'cantidad' => count($items),
            'data' => $items
        ]
    );
    }

    public function insertarPedidoDet($f3){
        $pedidosDet = new m_pedidos_detalle();
        $mensaje = "";
        $newid = 0;
        // $pedidosDet->load(['usuario=?',$f3->get('POST.usr_usuario')]);
        // if($usuario->loaded()>0){
        //     $mensaje = "EL USUARIO YA EXITE";
        // }else{
            $this->M_Pedido_Detalle->set('producto_id',$f3->get('POST.producto_id'));
            $this->M_Pedido_Detalle->set('pedido_id',$f3->get('POST.pedido_id'));
            $this->M_Pedido_Detalle->set('cantidad',$f3->get('POST.pedDet_cantidad'));
            $this->M_Pedido_Detalle->set('precio',$f3->get('POST.pedDet_precio'));
            $this->M_Pedido_Detalle->save();
            $mensaje = "Se registro Correctamentee";
            $newid = $this->M_Pedido_Detalle->get('id');
        // }
        echo json_encode(
            [
                'mensaje'=>$mensaje,
                'id'=>$newid
            ]
            );

    }
    public function updatePedidoDetSQL($f3){
        
        $pedidosDet = new m_pedidos_detalle();
        $mensaje = "";
        // $newid = 0;
        $pedidosDet->load(['id=?',$f3->get('POST.id')]);
        if($pedidosDet->loaded()==0){
            $mensaje = "EL PEDIDO DETALLE A MODIFICAR NO EXISTE";
        }else{
        $cadenaSQL = "";
        $cadenaSQL .= "UPDATE pedidos_detalle SET ";
        $cadenaSQL .= "producto_id = '" . $f3->get('POST.producto_id') . "', ";
        $cadenaSQL .= "pedido_id = '" . $f3->get('POST.pedido_id') . "', ";
        $cadenaSQL .= "cantidad = '" . $f3->get('POST.pedDet_cantidad') . "', ";
        $cadenaSQL .= "precio = '" . $f3->get('POST.pedDet_precio') . "' ";
        $cadenaSQL .= "WHERE id = '" . $f3->get('POST.id') . "';";
        $items = $f3->DB->exec($cadenaSQL);
        $mensaje = "SE MODIFICO CORRECTAMENTE";
  }
        echo json_encode(
            [
            'mensaje'=>$mensaje
            ]

        );
  
     }

     public function eliminarPedidoDet($f3){
        $newid=0;
        $pedidoDet = $f3->get('POST.id');
        $this->M_Pedido_Detalle->load(['id=?', $pedidoDet]);
        $mensaje="";
        if($this->M_Pedido_Detalle->loaded()>0){
            $this->M_Pedido_Detalle->erase();
            $mensaje="El PEDIDO DETALLE fue eliminado ";
            $newid = 1;

        }else{
            $mensaje ="El PEDIDO DETALLE no existe";
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