<?php

    namespace rafael\financas\model\bo;

    use \Exception;
    use rafael\financas\model\entity\Carteira;
    use rafael\financas\model\dao\DAO_Carteira;
    
    class BO_Carteira
    {
        public function salvar(string $nome, int $dono, bool $ativo)
        {
            try {
                $carteira = new Carteira(0, $nome, $dono, $ativo);
                $dao_carteira = new DAO_Carteira();
                return $dao_carteira->salvar($carteira);
            } catch (Exception $e) {
                $retorno  = "Erro ao salvar um objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function atualizar(int $codigo, string $nome, int $dono, bool $ativo)
        {
            try {
                $carteira = new Carteira($codigo, $nome, $dono, $ativo);
                $dao_carteira = new DAO_Carteira();
                return $dao_carteira->atualizar($carteira);
            } catch (Exception $e) {
                $retorno  = "Erro ao atualizar um objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarPorFiltro(string $nome = null, int $dono = null, bool $ativo = null)
        {
            try {
                $parametros = array("nome" => $nome, "dono" => $dono, "ativo" => $ativo);
                $dao_carteira = new DAO_Carteira();
                return $dao_carteira->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarPorCodigo(int $codigo)
        {
            try {
                $parametros = array("codigo" => $codigo);
                $dao_carteira = new DAO_Carteira();
                return $dao_carteira->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarAtivos()
        {
            try {
                $parametros = array("ativo" => true);
                $dao_carteira = new DAO_Carteira();
                return $dao_carteira->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarInativos()
        {
            try {
                $parametros = array("ativo" => false);
                $dao_carteira = new DAO_Carteira();
                return $dao_carteira->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
    }
    
?>