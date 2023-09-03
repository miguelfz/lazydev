<h1>Configurando {$modelName}</h1>
<form method="post">
    <table>
        <tr>
            <th>Campo</td>
            <th>Finalidade</th>
        </tr>
        {foreach $model as $m}
            <tr>
                <td><label for="">{$m->Field}</label></td>
                <td>
                    <select name="finalidade" id="finalidade">
                        <option value="text">linha de texto geral</option>
                        <option value="html">texto longo com HTML</option>
                        <option value="number">número</option>
                        <option value="now">agora (data e hora atual)</option>
                        <option value="date">data</option>
                        <option value="datetime">data e hora</option>
                        <option value="time">hora minuto e segundo</option>
                        <option value="password">senha</option>
                        <option value="checkbox">Checkbox (boolean)</option>
                        <option value="image">imagem com upload (não blob)</option>
                        <option value="file">arquivo com upload (não blob)</option>
                    </select>
                </td>
            </tr>
        {foreachelse}
            <p>Nenhum campo encontrado.</p>
        {/foreach}
    </table>
    <div>
        <input type="submit" value="salvar">
    </div>
</form>