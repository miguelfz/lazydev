<div class="ver filmes">
    <h1>{$filmes->titulo}</h1>

    <div>titulo_original: {$filmes->titulo_original}</div>
    <div>duracao: {$filmes->duracao}</div>
    <div>ano_lancamento: {$filmes->ano_lancamento}</div>
    <div>diretores: <a href="{PATH}/Diretores/ver/{$filmes->getDiretores()->cod_diretor}">{$filmes->getDiretores()->nome_diretor}</a></div>
    <div>generos: <a href="{PATH}/Generos/ver/{$filmes->getGeneros()->cod_genero}">{$filmes->getGeneros()->nome_genero}</a></div>
    
    {* lista de atores*}
    {include file='../Atores/lista.tpl'}
</div>