<form method="post" class="editar livro">
    <h1>Editar Livro</h1>
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
        <select name="codCategoria" id="codCategoria">
            {html_options options=$categorias selected=$livro->codCategoria}
        </select>
    </div>
    <div>
        <label for="codEditora">editora</label>
        <select name="codEditora" id="codEditora">
            <option></option>
            {html_options options=$editoras selected=$livro->codEditora}
        </select>
    </div>
    <div>
        {if $livro->lido}
            {assign var=checked value='checked'}
        {else}
            {assign var=checked value=''}
        {/if}
        <input value="1" type="checkbox" name="lido" id="lido" {$checked}>
        <label for="lido">lido</label>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>