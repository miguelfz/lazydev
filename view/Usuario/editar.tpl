<form method="post" class="editar usuario">
    <h1>Editar Usuario</h1>
    <div>
        <label for="email">email</label>
        <input type=text name="email" id="email" value="{$usuario->email}"  required>
    </div>
    <div>
        <label for="senha">senha</label>
        <input type=password name="senha" id="senha" value="{$usuario->senha}"  required>
    </div>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>