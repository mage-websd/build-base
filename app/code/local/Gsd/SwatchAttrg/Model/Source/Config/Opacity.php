<?php

class Gsd_SwatchAttrg_Model_Source_Config_Opacity {

    public function toOptionArray() {
        $result = array();
        for ($i = 0; $i <= 1; $i+=0.1) {
            $result[] = array(
                'value' => ($i * 100),
                'label' => ($i * 100) . '%'
            );
        }
        return $result;
    }

}
