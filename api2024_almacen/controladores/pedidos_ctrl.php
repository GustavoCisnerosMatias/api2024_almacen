<?php
class pedidos_ctrl{
    public $M_Pedido = null;
    public function __construct(){
        $this -> M_Pedido = new m_pedidos();
        
    }
    public function setUbi($f3){    
        $pedido_id=$f3->get('POST.pedido_id');    
        $longitude=$f3->get('POST.longitude');      
        $latitude=$f3->get('POST.latitude');    

        $cadenaSql=" ";
        $cadenaSql=$cadenaSql." INSERT INTO `ubicacion_pedido`(`pedido_id`, `longitude`, `latitude`) ";
        $cadenaSql=$cadenaSql." values (";
        $cadenaSql=$cadenaSql." '".$pedido_id."' , ";
        $cadenaSql=$cadenaSql." '".$longitude."' , ";
        $cadenaSql=$cadenaSql." '".$latitude."' ) ";



        $items=$f3->DB->exec($cadenaSql);

        echo json_encode(
            [
                'mensaje'=>  'Se registro Correctamente'       

            ]
        );
    }

    public function listarPedidos($F3){
        $pedidos = $this->M_Pedido->find();
        $items = array();
        foreach($pedidos as $ObjPed){
            $items[] = $ObjPed ->cast();
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

    public function fun_retornaPedidosId($f3){
        $pedidos_id = $f3->get('POST.pedidos_id'); //debe tener este nombre al momento de enviar desde el cliente 
    //    echo 'id: '.$producto_id;
       $this->M_Pedido->load(['id= ?',$pedidos_id ]);
       $items = array();
       $msg = "";
       
       if($this->M_Pedido->loaded()>0){
        $msg = "Consulta Con Exito ";
        $items= $this->M_Pedido->cast();


       }else{
        $msg = "El Pedidos con el id: ".$pedidos_id. " no Existe ";
       }
       echo json_encode(
        [
            'Mensaje' => $msg,
            'cantidad' => count($items),
            'data' => $items
        ]
    );
    }

    public function insertarPedido($f3){
        $pedido = new m_pedidos();
        $mensaje = "";
        $newid = 0;
        // $pedido->load(['usuario=?',$f3->get('POST.')]);
        // if($pedido->loaded()>0){
        //     $mensaje = "EL PEDIDO YA EXITE";
        // }else{
            $hora_actual = date('Y-m-d H:i:s');
            $this->M_Pedido->set('cliente_id',$f3->get('POST.cliente_id'));
            $this->M_Pedido->set('fecha',$hora_actual);
            $this->M_Pedido->set('usuario_id',$f3->get('POST.usuario_id'));
            $this->M_Pedido->set('estado',$f3->get('POST.ped_estado'));
            $this->M_Pedido->save();
            $mensaje = "Se registro Correctamente el Pedido";
            $newid = $this->M_Pedido->get('id');
        // }
        echo json_encode(
            [
                'mensaje'=>$mensaje,
                'id'=>$newid
            ]
            );
    }

    public function updatePedidoSQL($f3){
        
        $pedido = new m_pedidos();
        $mensaje = "";
        // $newid = 0;
        $pedido->load(['id=?',$f3->get('POST.id')]);
        if($pedido->loaded()==0){
            $mensaje = "EL PEDIDO A MODIFICAR NO EXISTE";
        }else{
            $hora_actual = date('Y-m-d H:i:s');
        $cadenaSQL = "";
        $cadenaSQL .= "UPDATE pedidos SET ";
        $cadenaSQL .= "cliente_id = '" . $f3->get('POST.cliente_id') . "', ";
        $cadenaSQL .= "fecha = '" . $hora_actual . "', ";
        $cadenaSQL .= "usuario_id = '" . $f3->get('POST.usuario_id') . "', ";
        $cadenaSQL .= "estado = '" . $f3->get('POST.ped_estado') . "' ";
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

     public function eliminarPedido($f3){
        $newid=0;
        $pedido = $f3->get('POST.id');
        $this->M_Pedido->load(['id=?', $pedido]);
        $mensaje="";
        if($this->M_Pedido->loaded()>0){
            $this->M_Pedido->erase();
            $mensaje="El PEDIDO fue eliminado ";
            $newid = 1;

        }else{
            $mensaje ="El PEDIDO no existe";
            $newid = 0;

        }
        echo json_encode(
            [
                'mensaje' =>$mensaje,
                'id'=>$newid

            ]

        );
    }

    public function getUbi($f3){
        $cadenaSQL = " SELECT id, pedido_id, longitude, latitude FROM ubicacion_pedido;";
        $items = $f3->DB->exec($cadenaSQL);
        $mensaje = "SE MODIFICO CORRECTAMENTE";
  
        echo json_encode(
            [
            'mensaje'=>$items
            ]

        );
    }

    

}
?>