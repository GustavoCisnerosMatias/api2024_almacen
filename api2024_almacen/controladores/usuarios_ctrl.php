<?php
class usuarios_ctrl{
    public $M_Usuario = null;
    public function __construct(){
        $this -> M_Usuario = new m_usuarios();
        
    }

    public function listarUsuario($F3){
        $usuarios = $this->M_Usuario->find();
        $items = array();
        foreach($usuarios as $ObjUsu){
            $items[] = $ObjUsu ->cast();
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

    public function fun_retornaUsuariosId($f3){
        $usuario_id = $f3->get('POST.usuario_id'); //debe tener este nombre al momento de enviar desde el cliente 
    //    echo 'id: '.$producto_id;
       $this->M_Usuario->load(['id= ?',$usuario_id ]);
       $items = array();
       $msg = "";
       
       if($this->M_Usuario->loaded()>0){
        $msg = "Consulta Con Exito ";
        $items= $this->M_Usuario->cast();


       }else{
        $msg = "El Usuario con el id: ".$usuario_id. " no Existe ";
       }
       echo json_encode(
        [
            'Mensaje' => $msg,
            'cantidad' => count($items),
            'data' => $items
        ]
    );
    }

    public function insertarUsuario($f3){
        $usuario = new m_usuarios();
        $mensaje = "";
        $newid = 0;
        $usuario->load([$f3->get('POST.id')]);
        if($usuario->loaded()==0){
            $mensaje = "EL USUARIO YA EXITE";
        }else{
            $this->M_Usuario->set('usuario',$f3->get('POST.usr_usuario'));
            $this->M_Usuario->set('clave',$f3->get('POST.usr_clave'));
            $this->M_Usuario->set('nombre',$f3->get('POST.usr_nombre'));
            $this->M_Usuario->set('telefono',$f3->get('POST.usr_telefono'));
            $this->M_Usuario->set('correo',$f3->get('POST.usr_correo'));
            $this->M_Usuario->set('activo',$f3->get('POST.usr_activo'));
            $this->M_Usuario->save();
            $mensaje = "Se registro Correctamentee";
            $newid = $this->M_Usuario->get('id');
        }
        echo json_encode(
            [
                'mensaje'=>$mensaje,
                'id'=>$newid
            ]
            );

    }

    public function updateUsuarioSQL($f3){
        
        $usuario = new m_usuarios();
        $mensaje = "";
        // $newid = 0;
        $usuario->load([$f3->get('POST.id')]);
        if($usuario->loaded()==0){
            $mensaje = "EL USUARIO A MODIFICAR NO EXISTE";
        }else{
        $cadenaSQL = "";
        $cadenaSQL .= "UPDATE usuarios SET ";
        $cadenaSQL .= "usuario = '" . $f3->get('POST.usr_usuario') . "', ";
        $cadenaSQL .= "clave = '" . $f3->get('POST.usr_clave') . "', ";
        $cadenaSQL .= "nombre = '" . $f3->get('POST.usr_nombre') . "', ";
        $cadenaSQL .= "telefono = '" . $f3->get('POST.usr_telefono') . "', ";
        $cadenaSQL .= "correo = '" . $f3->get('POST.usr_correo') . "', ";
        $cadenaSQL .= "activo = '" . $f3->get('POST.usr_activo') . "' ";
        $cadenaSQL .= "WHERE id = '" . $f3->get('POST.id') . "';";
        $items = $f3->DB->exec($cadenaSQL);
        $mensaje = "SE MODIFICO CORRECTAMENTE EL USUARIO";
  }
        echo json_encode(
            [
            'mensaje'=>$mensaje
            ]

        );
  
     }
     public function eliminarUsuario($f3){
        $newid=0;
        $usuario = $f3->get('POST.usr_usuario');
        $this->M_Usuario->load(['usuario=?', $usuario]);
        $mensaje="";
        if($this->M_Usuario->loaded()>0){
            $this->M_Usuario->erase();
            $mensaje="El USUARIO fue eliminado ";
            $newid = 1;

        }else{
            $mensaje ="El USUARIO no existe";
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