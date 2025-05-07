<?php
class CL_INTERFAZ19
{
    private $interfaz;

    public function mostrar($html_revistas)
    {
        $this->interfaz = file_get_contents('../HTML/form_19.html');
        $this->interfaz = str_replace("{{REVISTAS}}", $html_revistas, $this->interfaz);
        echo $this->interfaz;
    }
}
