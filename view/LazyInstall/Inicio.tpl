<h1>Bem vindo ao Instalador do LazyDev</h1>
<p>O instalador do <em>LazyDev</em> permite a geração automática dos models, controllers e views a partir da estrutura
    de sua base de dados, facilitando o início do desenvolvimento de seu sistema.</p>

<h5>Antes de instalar, leia estas recomendações:</h5>
<ul>
    <li>Verifique atentamente o arquivo /config.php;</li>
    <li>Cada tabela do seu BD deve ter uma chave primária de coluna única;</li>
    <li>Verifique as chaves estrangeiras - Eventuais mapeamentos serão gerados a partir delas;</li>
    <li>Verifique as permissões de escrita nos diretórios;</li>
    <li>Importante: Desabilite o modo <em>debug</em> após a instalação.</li>
</ul>

<hr>
<h3>Tabelas</h3>
<div class="">
    {foreach $tables as $t}
        <div style="padding:10px; border-bottom:1px dotted #CCC;">
            <a href="{PATH}/LazyInstall/model/{$t->name}"><span>&#10003;</span> {$t->name}</a>
        </div>
    {foreachelse}
        <p>Nenhuma tabela encontrada. Verifique o arquivo /config</p>
    {/foreach}
</div>
<hr>