<?php
namespace Lazydev\Model;

final class Livroautor extends \Lazydev\Core\Record{ 

    const TABLE = 'Livroautor'; # tabela de referência
    const PK = ['codLivro', 'codAutor']; # chave primária
}