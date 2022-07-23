<?php

    namespace rafael\financas\model\bo;

    include_once '..\autoload.php';

    use \Exception;
    use rafael\financas\model\entity\TipoMovimento;
    use rafael\financas\model\dao\DAO_TipoMovimento;
    
    class BO_TipoMovimento {
        
        public function salvar(string $nome, int $tipo, int $indispensavel, string $descricao, bool $ativo)
        {
            try {
                $tipoMovimento = new TipoMovimento(0, $nome, $tipo, $indispensavel, $descricao, $ativo);
                $dao_tipoMovimento = new DAO_TipoMovimento();
                return $dao_tipoMovimento->salvar($tipoMovimento);
            } catch (Exception $e) {
                $retorno  = "Erro ao salvar um objeto 'Tipo de Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function atualizar(int $codigo, string $nome, int $tipo, int $indispensavel, string $descricao, bool $ativo)
        {
            try {
                $tipoMovimento = new TipoMovimento($codigo, $nome, $tipo, $indispensavel, $descricao, $ativo);
                $dao_tipoMovimento = new DAO_TipoMovimento();
                return $dao_tipoMovimento->atualizar($tipoMovimento);
            } catch (Exception $e) {
                $retorno  = "Erro ao atualizar um objeto 'Tipo de Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarPorFiltro(string $nome = null, int $tipo = null, int $indispensavel = null, string $descricao = null, bool $ativo = null)
        {
            try {
                $parametros = array("nome" => $nome, "tipo" => $tipo, "indispensavel" => $indispensavel, "descricao" => $descricao, "ativo" => $ativo);
                $dao_tipoMovimento = new DAO_TipoMovimento();
                return $dao_tipoMovimento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Tipo de Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarPorCodigo(int $codigo)
        {
            try {
                $parametros = array("codigo" => $codigo);
                $dao_tipoMovimento = new DAO_TipoMovimento();
                return $dao_tipoMovimento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Tipo de Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarAtivos()
        {
            try {
                $parametros = array("ativo" => true);
                $dao_tipoMovimento = new DAO_TipoMovimento();
                return $dao_tipoMovimento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Tipo de Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarInativos()
        {
            try {
                $parametros = array("ativo" => false);
                $dao_tipoMovimento = new DAO_TipoMovimento();
                return $dao_tipoMovimento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Tipo de Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
    }
    
?>