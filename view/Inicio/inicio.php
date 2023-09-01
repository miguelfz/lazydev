<h2>Olá </h2>

<?php
foreach($municipios as $m){
    echo "<p><a href=\"".PATH."/Inicio/verImoveis/$m->id\">$m->nome</a></p>";
}
echo $municipios->getNav();
?>