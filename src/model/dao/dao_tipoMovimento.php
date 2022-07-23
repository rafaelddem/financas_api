<?php

    namespace rafael\financas\model\dao;

    use \PDO;
    use rafael\financas\model\dao\DAOBase;
    use rafael\financas\model\entity\TipoMovimento;

    class DAO_TipoMovimento extends DAOBase
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function salvar(TipoMovimento $tipoMovimento)
        {
            self::getPDO()->beginTransaction();
            
            $stmt = self::getPDO()->prepare("insert into tbfi_tipoMovimento (str_nome, chr_tipo, int_indispensavel, str_descricao, chr_ativo) values (:nome, :tipo, :indispensavel, :descricao, :ativo);");
            $nome = $tipoMovimento->getNome();
            $tipo = $tipoMovimento->getTipo();
            $indispensavel = $tipoMovimento->getIndispensavel();
            $descricao = $tipoMovimento->getDescricao();
            $descricao = (empty($descricao)) ? null : $descricao;
            $ativo = $tipoMovimento->getAtivo();
            $stmt->bindParam(':nome', $nome,PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo,PDO::PARAM_INT);
            $stmt->bindParam(':indispensavel', $indispensavel,PDO::PARAM_INT);
            $stmt->bindParam(':descricao', $descricao,PDO::PARAM_STR);
            $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao salvar um objeto 'Tipo de Movimento'.", 12);
            }
            
            self::getPDO()->commit();
            return "Objeto 'Tipo de Movimento' salvo com sucesso.";
        }
        
        public function atualizar(TipoMovimento $tipoMovimento)
        {
            self::getPDO()->beginTransaction();
            
            $sql = "update tbfi_tipoMovimento set str_nome = :nome, chr_tipo = :tipo, int_indispensavel = :indispensavel, str_descricao = :descricao, chr_ativo = :ativo where int_codigo = :codigo;";
            $nome = $tipoMovimento->getNome();
            $tipo = $tipoMovimento->getTipo();
            $indispensavel = $tipoMovimento->getIndispensavel();
            $descricao = $tipoMovimento->getDescricao();
            $descricao = (empty($descricao)) ? null : $descricao;
            $ativo = $tipoMovimento->getAtivo();
            $codigo = $tipoMovimento->getCodigo();
            $stmt = self::getPDO()->prepare($sql);
            $stmt->bindParam(':nome', $nome,PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo,PDO::PARAM_INT);
            $stmt->bindParam(':indispensavel', $indispensavel,PDO::PARAM_INT);
            $stmt->bindParam(':descricao', $descricao,PDO::PARAM_INT);
            $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);
            $stmt->bindParam(':codigo', $codigo,PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao atualizar um objeto 'Tipo de Movimento'.", 13);
            }
            $count = $stmt->rowCount();
            self::getPDO()->commit();
            $retorno  = "O comando de atualização foi executado com sucesso";
            $retorno .= ($count == 0) ? ", porém nenhum registro foi alterado." : ".";
            return $retorno;
        }
        
        public function pesquisar(array $colunas)
        {
            self::getPDO()->beginTransaction();
            
            $temFiltro = false;
            $sql = "select * from tbfi_tipoMovimento";
            if (isset($colunas['codigo'])) {
                $codigo = $colunas['codigo'];
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " int_codigo = :codigo";
                $temFiltro = true;
            }
            if (isset($colunas['nome'])) {
                $nome = "%".$colunas['nome']."%";
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " str_nome like :nome";
                $temFiltro = true;
            }
            if (isset($colunas['tipo'])) {
                $tipo = $colunas['tipo'];
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " chr_tipo = :tipo";
                $temFiltro = true;
            }
            if (isset($colunas['indispensavel'])) {
                $indispensavel = $colunas['indispensavel'];
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " int_indispensavel = :indispensavel";
                $temFiltro = true;
            }
            if (isset($colunas['descricao'])) {
                $descricao = $colunas['descricao'];
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " str_descricao like :descricao";
                $temFiltro = true;
            }
            if (isset($colunas['ativo'])) {
                $ativo = $colunas['ativo'];
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " chr_ativo = :ativo";
                $temFiltro = true;
            }
            $sql .= ";";
            $stmt = self::getPDO()->prepare($sql);
            if (isset($codigo)) $stmt->bindParam(':codigo', $codigo,PDO::PARAM_INT);
            if (isset($nome)) $stmt->bindParam(':nome', $nome,PDO::PARAM_STR);
            if (isset($tipo)) $stmt->bindParam(':tipo', $tipo,PDO::PARAM_INT);
            if (isset($indispensavel)) $stmt->bindParam(':indispensavel', $indispensavel,PDO::PARAM_INT);
            if (isset($descricao)) $stmt->bindParam(':descricao', $descricao."%",PDO::PARAM_STR);
            if (isset($ativo)) $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);

            if($stmt->execute()) {
                if($stmt->rowCount() > 0) {
                    $tiposMovimento = array();
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                        $descricao = empty($row->str_descricao) ? "" : $row->str_descricao;
                        $tipoMovimento = new TipoMovimento($row->int_codigo, $row->str_nome, $row->chr_tipo, $row->int_indispensavel, $descricao, boolval($row->chr_ativo));
                        array_push($tiposMovimento, $tipoMovimento);
                    }
                } else {
                    self::getPDO()->rollback();
                    $retorno  = "Não há registro para os filtros pesquisados.";
                    return $retorno;
                }
            } else {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao tentar buscar o(s) objeto(s) de tipo 'Tipo de Movimento'.", 14);
            }
            
            self::getPDO()->commit();
            return $tiposMovimento;
        }
        
    }
    
?>