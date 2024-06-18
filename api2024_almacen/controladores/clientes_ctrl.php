<?php
class clientes_ctrl{
    public $M_Cliente = null;
    public function __construct(){
        $this -> M_Cliente = new m_clientes();
        
    }

    public function listarClientes($F3){
        $clientes = $this->M_Cliente->find();
        $items = array();
        foreach($clientes as $ObjCli){
            $items[] = $ObjCli ->cast();
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

    public function fun_retornaClienteId($f3){
        $clientes_id = $f3->get('POST.clientes_id'); //debe tener este nombre al momento de enviar desde el cliente 
    //    echo 'id: '.$producto_id;
       $this->M_Cliente->load(['id= ?',$clientes_id ]);
       $items = array();
       $msg = "";
       
       if($this->M_Cliente->loaded()>0){
        $msg = "Consulta Con Exito ";
        $items= $this->M_Cliente->cast();


       }else{
        $msg = "El Cliente con el id: ".$clientes_id. " no Existe ";
       }
       echo json_encode(
        [
            'Mensaje' => $msg,
            'cantidad' => count($items),
            'data' => $items
        ]
    );
    }
    //insertar mediantes sentencias SQL 
    public function insertarClienteSQL($f3){
       // echo  $f3->get('POST.cli_ci');
        $cadenaSQL="";
        $cadenaSQL= $cadenaSQL. "insert into clientes "; 
        $cadenaSQL= $cadenaSQL. "(identificacion, nombre, telefono,correo,direccion,pais,ciudad) "; 
        $cadenaSQL= $cadenaSQL. "values( "; 
        $cadenaSQL= $cadenaSQL. " ' " . $f3->get('POST.cli_ci'). "',"; 
        $cadenaSQL= $cadenaSQL. " ' " . $f3->get('POST.cli_nombres'). "',"; 
        $cadenaSQL= $cadenaSQL. " ' " . $f3->get('POST.cli_telefono'). "',"; 
        $cadenaSQL= $cadenaSQL. " ' " . $f3->get('POST.cli_correo'). "',"; 
        $cadenaSQL= $cadenaSQL. " ' " . $f3->get('POST.cli_direccion'). "',"; 
        $cadenaSQL= $cadenaSQL. " ' " . $f3->get('POST.cli_pais'). "',"; 
        $cadenaSQL= $cadenaSQL. " ' " . $f3->get('POST.cli_ciudad'). "')"; 

        //echo $cadenaSQL;
        $items = $f3->DB->exec($cadenaSQL);
        echo json_encode(
            [
                'mensaje' => "Datos Insertados"
            ]

        );


    }

    public function insertarCliente($f3){
        $cliente = new m_clientes();
        $mensaje = "";
        $newid = 0;
        $cliente->load(['identificacion=?',$f3->get('POST.cli_ci')]);
        if($cliente->loaded()>0){
            $mensaje = "LA CEDULA YA EXITE";
        }else{
            $this->M_Cliente->set('identificacion',$f3->get('POST.cli_ci'));
            $this->M_Cliente->set('nombre',$f3->get('POST.cli_nombres'));
            $this->M_Cliente->set('telefono',$f3->get('POST.cli_telefono'));
            $this->M_Cliente->set('correo',$f3->get('POST.cli_correo'));
            $this->M_Cliente->set('direccion',$f3->get('POST.cli_direccion'));
            $this->M_Cliente->set('pais',$f3->get('POST.cli_pais'));
            $this->M_Cliente->set('ciudad',$f3->get('POST.cli_ciudad'));
            $this->M_Cliente->save();
            $mensaje = "Se registro Correctamentee";
            $newid = $this->M_Cliente->get('id');
        }
        echo json_encode(
            [
                'mensaje'=>$mensaje,
                'id'=>$newid
            ]
            );

    }
    public function updateCliente($f3){
        $cliente = new m_clientes();
        $mensaje = "";
        $newid = 0;
        $cliente->load(['identificacion=?',$f3->get('POST.cli_ci')]);
        if($cliente->loaded()==0){
            $mensaje = "LA PERSONA A MODIFICAR NO EXISTE";
        }else{
            $this->M_Cliente->set('identificacion',$f3->get('POST.cli_ci'));
            $this->M_Cliente->set('nombre',$f3->get('POST.cli_nombres'));
            $this->M_Cliente->set('telefono',$f3->get('POST.cli_telefono'));
            $this->M_Cliente->set('correo',$f3->get('POST.cli_correo'));
            $this->M_Cliente->set('direccion',$f3->get('POST.cli_direccion'));
            $this->M_Cliente->set('pais',$f3->get('POST.cli_pais'));
            $this->M_Cliente->set('ciudad',$f3->get('POST.cli_ciudad'));
            $this->M_Cliente->save();
            $mensaje = "Se MODIFICO Correctamentee";
            $newid = $this->M_Cliente->get('id');
        }
        echo json_encode(
            [
                'mensaje'=>$mensaje,
                'id'=>$newid
            ]
            );

    }

    public function updateClienteSQL($f3){
        
        $cliente = new m_clientes();
        $mensaje = "";
        // $newid = 0;
        $cliente->load([$f3->get('POST.cli_ci')]);
        if($cliente->loaded()==0){
            $mensaje = "LA PERSONA A MODIFICAR NO EXISTE";
        }else{
        $cadenaSQL = "";
        $cadenaSQL .= "UPDATE clientes SET ";
        $cadenaSQL .= "identificacion = '" . $f3->get('POST.cli_iden') . "', ";
        $cadenaSQL .= "nombre = '" . $f3->get('POST.cli_nombres') . "', ";
        $cadenaSQL .= "telefono = '" . $f3->get('POST.cli_telefono') . "', ";
        $cadenaSQL .= "correo = '" . $f3->get('POST.cli_correo') . "', ";
        $cadenaSQL .= "direccion = '" . $f3->get('POST.cli_direccion') . "', ";
        $cadenaSQL .= "pais = '" . $f3->get('POST.cli_pais') . "', ";
        $cadenaSQL .= "ciudad = '" . $f3->get('POST.cli_ciudad') . "' ";
        $cadenaSQL .= "WHERE id = '" . $f3->get('POST.cli_ci') . "';";
        $items = $f3->DB->exec($cadenaSQL);
        $mensaje = "SE MODIFICO CORRECTAMENTE";
  }
        echo json_encode(
            [
            'mensaje'=>$mensaje
            ]

        );
  
     }
    public function eliminarCliente($f3){
        $newid=0;
        $cliente_ced = $f3->get('POST.cli_ci');
        $this->M_Cliente->load(['identificacion=?', $cliente_ced]);
        $mensaje="";
        if($this->M_Cliente->loaded()>0){
            $this->M_Cliente->erase();
            $mensaje="El CLiente fue eliminado ";
            $newid = 1;

        }else{
            $mensaje ="El cliente no existe";
            $newid = 1;

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