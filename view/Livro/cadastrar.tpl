<h1>Cadastrar Livro</h1>
<form method="post">
    <div>
        <label for="titulo">titulo</label>
        <input type=text name="titulo" id="titulo" value="{$livro->titulo}"  required>
    </div>
    <div>
        <label for="edicao">edicao</label>
        <input type=number name="edicao" id="edicao" value="{$livro->edicao}"  required>
    </div>
    <div>
        <label for="idioma">idioma</label>
        <input type=text name="idioma" id="idioma" value="{$livro->idioma}" >
    </div>
    <div>
        <label for="exemplares">exemplares</label>
        <input type=number name="exemplares" id="exemplares" value="{$livro->exemplares}" >
    </div>
    <div>
        <label for="paginas">paginas</label>
        <input type=number name="paginas" id="paginas" value="{$livro->paginas}" >
    </div>
    <div>
        <label for="codCategoria">categoria</label>
                <div>
            {html_radios required='required' name='codCategoria' options=$categorias selected=$livro->codCategoria separator='<br>'}
                </div>
    </div>
    <div>
        <label for="codEditora">editora</label>
                <div>
            {html_radios  name='codEditora' options=$editoras selected=$livro->codEditora separator='<br>'}
                </div>
    </div>
    <div>
        {if $livro->lido}
            {assign var=checked value='checked'}
        {else}
            {assign var=checked value=''}
        {/if}
        <input type="checkbox" name="lido" id="lido" {$checked}>
        <label for="lido">lido</label>
    </div>
    <div>
        <input type="submit" value="cadastrar">
    </div>
</form>