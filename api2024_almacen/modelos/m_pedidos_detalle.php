
<?php
class m_pedidos_detalle extends \DB\SQL\Mapper {
    public function __construct() {
        parent:: __construct(\Base::instance()->get('DB'), 'pedidos_detalle');
    }
}
?>
