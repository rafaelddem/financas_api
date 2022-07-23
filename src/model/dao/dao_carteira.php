<?php

    namespace rafael\financas\model\dao;

    use \PDO;
    use rafael\financas\model\dao\DAOBase;
    use rafael\financas\model\entity\Carteira;

    class DAO_Carteira extends DAOBase
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function salvar(Carteira $carteira)
        {
            self::getPDO()->beginTransaction();
            
            $stmt = self::getPDO()->prepare("insert into tbfi_carteira (str_nome, chr_dono, chr_ativo) values (:nome, :dono, :ativo);");
            $nome = $carteira->getNome();
            $dono = $carteira->getDono();
            $ativo = $carteira->getAtivo();
            $stmt->bindParam(':nome', $nome,PDO::PARAM_STR);
            $stmt->bindParam(':dono', $dono,PDO::PARAM_INT);
            $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao salvar um objeto 'carteira'.", 4);
            }
            
            self::getPDO()->commit();
            return "Objeto 'Carteira' salvo com sucesso.";
        }

        public function atualizar(Carteira $carteira)
        {
            self::getPDO()->beginTransaction();
            
            $sql = "update tbfi_carteira set str_nome = :nome, chr_dono = :dono, chr_ativo = :ativo where int_codigo = :codigo;";
            $stmt = self::getPDO()->prepare($sql);
            $nome = $carteira->getNome();
            $dono = $carteira->getDono();
            $ativo = $carteira->getAtivo();
            $codigo = $carteira->getCodigo();
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':dono', $dono, PDO::PARAM_INT);
            $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao atualizar um objeto 'carteira'.", 5);
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
            $sql = "select * from tbfi_carteira";
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
            if (isset($colunas['dono'])) {
                $dono = $colunas['dono'];
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " chr_dono = :dono";
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
            if (isset($dono)) $stmt->bindParam(':dono', $dono,PDO::PARAM_INT);
            if (isset($ativo)) $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);
                
            if($stmt->execute()) {
                if($stmt->rowCount() > 0) {
                    $carteiras = array();
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                        $carteira = new Carteira($row->int_codigo, $row->str_nome, $row->chr_dono, boolval($row->chr_ativo));
                        array_push($carteiras, $carteira);
                    }
                } else {
                    self::getPDO()->rollback();
                    $retorno  = "Não há registro para os filtros pesquisados.";
                    return $retorno;
                }
            } else {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao tentar buscar o(s) objeto(s) de tipo 'Carteira'.", 6);
            }
            
            self::getPDO()->commit();
            return $carteiras;
        }
        
    }
    
?>