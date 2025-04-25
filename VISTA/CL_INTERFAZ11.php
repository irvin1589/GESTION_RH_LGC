<?php
class CL_INTERFAZ11
{
    public function getInterfaz(): string
    {
        // Antes: __DIR__ . '/form_11.html'
        // Ahora apuntamos a ../HTML/form_11.html desde VISTA/
        $ruta = __DIR__ . '/../HTML/form_11.html';
        if (!file_exists($ruta)) {
            throw new \Exception("No se encuentra el archivo de interfaz: $ruta");
        }
        return file_get_contents($ruta);
    }

    public function mostrar(): void
    {
        echo $this->getInterfaz();
    }
}
