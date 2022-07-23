<?php

    namespace rafael\financas\model\dao;

    use \PDO;
    use rafael\financas\model\dao\DAOBase;
    use rafael\financas\model\entity\FormaPagamento;
    
    class DAO_FormaPagamento extends DAOBase
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function salvar(FormaPagamento $formaPagamento)
        {
            self::getPDO()->beginTransaction();
            
            $stmt = self::getPDO()->prepare("insert into tbfi_formaPagamento (str_nome, chr_ativo) values (:nome, :ativo);");
            $nome = $formaPagamento->getNome();
            $ativo = $formaPagamento->getAtivo();
            $stmt->bindParam(':nome', $nome,PDO::PARAM_STR);
            $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao salvar um objeto 'Forma de Pagamento'.", 17);
            }
                
            self::getPDO()->commit();
            return "Objeto 'Forma de Pagamento' salvo com sucesso.";
        }
        
        public function atualizar(FormaPagamento $formaPagamento)
        {
            self::getPDO()->beginTransaction();
            
            $sql = "update tbfi_formaPagamento set str_nome = :nome, chr_ativo = :ativo where int_codigo = :codigo;";
            $stmt = self::getPDO()->prepare($sql);
            $nome = $formaPagamento->getNome();
            $ativo = $formaPagamento->getAtivo();
            $codigo = $formaPagamento->getCodigo();
            $stmt->bindParam(':nome', $nome,PDO::PARAM_STR);
            $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);
            $stmt->bindParam(':codigo', $codigo,PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao atualizar um objeto 'Forma de Pagamento'.", 18);
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
            $sql = "select * from tbfi_formaPagamento";
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
            if (isset($ativo)) $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);
            
            if($stmt->execute()) {
                if($stmt->rowCount() > 0) {
                    $formasPagamento = array();
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                        $formaPagamento = new FormaPagamento($row->int_codigo, $row->str_nome, boolval($row->chr_ativo));
                        array_push($formasPagamento, $formaPagamento);
                    }
                } else {
                    self::getPDO()->rollback();
                    $retorno  = "Não há registro para os filtros pesquisados.";
                    return $retorno;
                }
            } else {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao tentar buscar o(s) objeto(s) de tipo 'Forma de Pagamento'.", 19);
            }
            
            self::getPDO()->commit();
            return $formasPagamento;
        }
        
    }
    
?>