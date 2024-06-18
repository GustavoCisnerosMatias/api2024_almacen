<?php
class m_clientes extends \DB\SQL\Mapper {
    public function __construct() {
        parent:: __construct(\Base::instance()->get('DB'), 'clientes');
    }
}
?>
