<?php
class m_productos extends \DB\SQL\Mapper {
    public function __construct() {
        parent:: __construct(\Base::instance()->get('DB'), 'productos');
    }
}
?>
