<section class="ver elenco">
    <h1>{$elenco->cod_filme}</h1>

    <div>atores: <a href="{PATH}/Atores/ver/{$elenco->getAtores()->cod_ator}">{$elenco->getAtores()->nome_ator}</a></div>
    <div>atores: <a href="{PATH}/Filmes/ver/{$elenco->getFilmes()->cod_filme}">{$elenco->getFilmes()->titulo_original}</a></div>
</section>