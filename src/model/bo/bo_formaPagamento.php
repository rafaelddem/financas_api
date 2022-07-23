<?php

    namespace rafael\financas\model\bo;

    include_once '..\autoload.php';

    use \Exception;
    use rafael\financas\model\entity\FormaPagamento;
    use rafael\financas\model\dao\DAO_FormaPagamento;
    
    class BO_FormaPagamento
    {
        public function salvar(string $nome, bool $ativo)
        {
            try {
                $formaPagamento = new FormaPagamento(0, $nome, $ativo);
                $dao_formaPagamento = new DAO_FormaPagamento();
                return $dao_formaPagamento->salvar($formaPagamento);
            } catch (Exception $e) {
                $retorno  = "Erro ao salvar um objeto 'Forma de Pagamento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function atualizar(int $codigo, string $nome, bool $ativo)
        {
            try {
                $formaPagamento = new FormaPagamento($codigo, $nome, $ativo);
                $dao_formaPagamento = new DAO_FormaPagamento();
                return $dao_formaPagamento->atualizar($formaPagamento);
            } catch (Exception $e) {
                $retorno  = "Erro ao atualizar um objeto 'Forma de Pagamento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarPorFiltro(string $nome = null, bool $ativo = null)
        {
            try {
                $parametros = array("nome" => $nome, "ativo" => $ativo);
                $dao_formaPagamento = new DAO_FormaPagamento();
                return $dao_formaPagamento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Forma de Pagamento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarPorCodigo(int $codigo)
        {
            try {
                $parametros = array("codigo" => $codigo);
                $dao_formaPagamento = new DAO_FormaPagamento();
                return $dao_formaPagamento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Forma de Pagamento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarAtivos()
        {
            try {
                $parametros = array("ativo" => true);
                $dao_formaPagamento = new DAO_FormaPagamento();
                return $dao_formaPagamento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Forma de Pagamento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function buscarInativos()
        {
            try {
                $parametros = array("ativo" => false);
                $dao_formaPagamento = new DAO_FormaPagamento();
                return $dao_formaPagamento->pesquisar($parametros);
            } catch (Exception $e) {
                $retorno  = "Erro ao buscar o(s) objeto(s) 'Forma de Pagamento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
    }
    
?>