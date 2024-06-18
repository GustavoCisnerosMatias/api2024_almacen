<?php
class m_pedidos extends \DB\SQL\Mapper {
    public function __construct() {
        parent:: __construct(\Base::instance()->get('DB'), 'pedidos');
    }
}
?>