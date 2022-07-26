<?php

    namespace rafael\financas\model\dao;

    use \PDO;
    use \Exception;
    use rafael\financas\model\dao\DAOBase;
    use rafael\financas\model\entity\Movimento;

    class DAO_Movimento extends DAOBase
    {
        public function __construct()
        {
            parent::__construct();
        }
        
        private function pesquisarCodigoUltimoMovimento()
        {
            $codigoUltimoMovimento = 0;
            $stmt = self::getPDO()->prepare("select max(int_codigo) as ultimoCodigo from tbfi_movimento;");
            if($stmt->execute()) {
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $codigo = $row->ultimoCodigo;
                    $codigoUltimoMovimento = (isset($codigo) and $codigo > 0) ? $codigo : $codigoUltimoMovimento;
                }
            }
            return $codigoUltimoMovimento;
        }

        public function salvar(array $movimentos)
        {
            self::getPDO()->beginTransaction();

            try {
                if (count($movimentos) == 1) {
                    $sucesso = self::salvarMovimentoUnico($movimentos[0], 0);
                } elseif (count($movimentos) > 1) {
                    $codigoMovimento = self::pesquisarCodigoUltimoMovimento() + 1;
                    foreach ($movimentos as $movimento) {
                        $sucesso = self::salvarMovimentoUnico($movimento, $codigoMovimento);
                        if (!$sucesso)
                            throw new Exception("Erro interno ao sistema, ao salvar um objeto 'Movimento'.", 20);
                    }
                } else {
                    throw new Exception("Erro interno ao sistema, ao salvar um objeto 'Movimento'.", 21);
                }
            } catch (Exception $e) {
                self::getPDO()->rollback();
                throw $e;
            }

            self::getPDO()->commit();
            return "Objeto 'Movimento' salvo com sucesso.";
        }

        private function salvarMovimentoUnico(Movimento $movimento, int $codigo)
        {
            $sql  = "insert into financas.tbfi_movimento (";
            $sql .= ($codigo > 0) ? "int_codigo, " : "";
            $sql .= "int_parcela, int_tipoMovimento, dat_dataMovimento, dat_dataPagamento, dub_valorInicial, dub_desconto, dub_tributacao, dub_juros, dub_arredondamento, dub_valorFinal, int_formaPagamento, int_carteiraOrigem, int_carteiraDestino, int_indispensavel, str_descricao";
            $sql .= ") values (";
            $sql .= ($codigo > 0) ? "$codigo, " : "";
            $sql .= ":parcela, :tipoMovimento, :dataMovimento, :dataPagamento, :valorInicial, :desconto, :tributacao, :juros, :arredondamento, :valorFinal, :formaPagamento, :carteiraOrigem, :carteiraDestino, :indispensavel, :descricao);";
            $stmt = self::getPDO()->prepare($sql);
            $parcela = $movimento->getParcela();
            $tipoMovimento = $movimento->getCodigoTipoMovimento();
            $dataMovimento = date_format($movimento->getDataMovimento(), 'Y-m-d');
            $dataPagamento = date_format($movimento->getDataPagamento(), 'Y-m-d');
            $valorInicial = $movimento->getValorInicial();
            $desconto = $movimento->getDesconto();
            $tributacao = $movimento->getTributacao();
            $juros = $movimento->getJuros();
            $arredondamento = $movimento->getArredondamento();
            $valorFinal = $movimento->getValorFinal();
            $formaPagamento = $movimento->getCodigoFormaPagamento();
            $carteiraOrigem = $movimento->getCodigoCarteiraOrigem();
            $carteiraDestino = $movimento->getCodigoCarteiraDestino();
            $indispensavel = $movimento->getIndispensavel();
            $descricao = $movimento->getDescricao();
            $stmt->bindParam(':parcela', $parcela,PDO::PARAM_INT);
            $stmt->bindParam(':tipoMovimento', $tipoMovimento,PDO::PARAM_INT);
            $stmt->bindParam(':dataMovimento', $dataMovimento,PDO::PARAM_STR);
            $stmt->bindParam(':dataPagamento', $dataPagamento,PDO::PARAM_STR);
            $stmt->bindParam(':valorInicial', $valorInicial,PDO::PARAM_STR);
            $stmt->bindParam(':desconto', $desconto,PDO::PARAM_STR);
            $stmt->bindParam(':tributacao', $tributacao,PDO::PARAM_STR);
            $stmt->bindParam(':juros', $juros,PDO::PARAM_STR);
            $stmt->bindParam(':arredondamento', $arredondamento,PDO::PARAM_STR);
            $stmt->bindParam(':valorFinal', $valorFinal,PDO::PARAM_STR);
            $stmt->bindParam(':formaPagamento', $formaPagamento,PDO::PARAM_INT);
            $stmt->bindParam(':carteiraOrigem', $carteiraOrigem,PDO::PARAM_INT);
            $stmt->bindParam(':carteiraDestino', $carteiraDestino,PDO::PARAM_INT);
            $stmt->bindParam(':indispensavel', $indispensavel,PDO::PARAM_INT);
            $stmt->bindParam(':descricao', $descricao,PDO::PARAM_STR);
            
            return $stmt->execute();
        }
/*
        public function atualizar(Carteira $carteira)
        {
            self::getPDO()->beginTransaction();
            
            $sql = "update tbfi_carteira set str_nome = :nome, chr_tipo = :tipo, chr_dono = :dono, chr_ativo = :ativo where int_codigo = :codigo;";
            $stmt = self::getPDO()->prepare($sql);
            $nome = $carteira->getNome();
            $tipo = $carteira->getTipo();
            $dono = $carteira->getDono();
            $ativo = $carteira->getAtivo();
            $codigo = $carteira->getCodigo();
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            $stmt->bindParam(':dono', $dono, PDO::PARAM_INT);
            $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao atualizar um objeto 'carteira', necessário informar ao responsável pelo sistema.", 10);
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
            if (isset($colunas['tipo'])) {
                $tipo = $colunas['tipo'];
                $sql .= ($temFiltro) ? " and" : " where";
                $sql .= " chr_tipo = :tipo";
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
            if (isset($tipo)) $stmt->bindParam(':tipo', $tipo,PDO::PARAM_INT);
            if (isset($dono)) $stmt->bindParam(':dono', $dono,PDO::PARAM_INT);
            if (isset($ativo)) $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);
                
            if($stmt->execute()) {
                if($stmt->rowCount() > 0) {
                    $carteiras = array();
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                        $carteira = new Carteira($row->int_codigo, $row->str_nome, $row->chr_tipo, $row->chr_dono, boolval($row->chr_ativo));
                        array_push($carteiras, $carteira);
                    }
                } else {
                    self::getPDO()->rollback();
                    $retorno  = "Não há registro para os filtros pesquisados.";
                    return $retorno;
                }
            } else {
                self::getPDO()->rollback();
                throw new Exception("Erro interno ao sistema, ao tentar buscar o(s) objeto(s) de tipo 'Carteira', necessário informar ao responsável pelo sistema.", 12);
            }
            
            self::getPDO()->commit();
            return $carteiras;
        }
*/
    }
    
?>