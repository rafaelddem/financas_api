<?php

    namespace rafael\financas\model\bo;

    include_once '..\autoload.php';

    use \Exception;
	use \DateTime;
    use rafael\financas\model\entity\Carteira;
    use rafael\financas\model\entity\TipoMovimento;
    use rafael\financas\model\entity\FormaPagamento;
    use rafael\financas\model\entity\Movimento;
    use rafael\financas\model\dao\DAO_Movimento;
    
    class BO_Movimento
    {
        public function salvar(int $parcela, TipoMovimento $tipoMovimento, DateTime $dataMovimento, DateTime $dataPagamento, 
                                float $valorInicial, float $desconto, float $tributacao, float $juros, float $arredondamento, float $valorFinal, 
                                FormaPagamento $formaPagamento, Carteira $carteiraOrigem, Carteira $carteiraDestino, int $indispensavel, 
                                string $descricao, array $parcelas)
        {
            try {
                $movimento = new Movimento(0, $parcela, $tipoMovimento, $dataMovimento, $dataPagamento, $valorInicial, $desconto, $tributacao, $juros, $arredondamento, $valorFinal, $formaPagamento, $carteiraOrigem, $carteiraDestino, $indispensavel, $descricao);
                $dao_movimento = new DAO_Movimento();
                return $dao_movimento->salvarMovimentoParcelaUnica($movimento);
            } catch (Exception $e) {
                $retorno  = "Erro ao salvar um objeto 'Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function salvar2(int $tipoMovimento, array $parcelas, inf $formaPagamento, int $indispensavel, string $descricao)
        {
            try {
                $movimentos = array();
                foreach ($parcelas as $parcela) {
                    $movimento = new Movimento(0, $parcela['codigo'], $tipoMovimento, $parcela['dataMovimento'], $parcela['dataPagamento'], $parcela['valorInicial'], $parcela['desconto'], $parcela['tributacao'], $parcela['juros'], $parcela['arredondamento'], $parcela['valorFinal'], $parcela['formaPagamento'], $parcela['carteiraOrigem'], $parcela['carteiraDestino'], $indispensavel, $descricao);
                    array_push($movimentos, $movimento);
                }
                $dao_movimento = new DAO_Movimento();
                return $dao_movimento->salvar($movimentos);
            } catch (Exception $e) {
                $retorno  = "Erro ao salvar um objeto 'Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
        
        public function salvar3(int $parcela = 1, TipoMovimento $tipoMovimento, array $parcelas, int $indispensavel = 0, string $descricao = null)
        {
            try {
                $movimentos = array();
                foreach ($parcelas as $parcela) {
                    $movimento = new Movimento(0, $parcela['codigo'], $tipoMovimento, $parcela['dataMovimento'], $parcela['dataPagamento'], $parcela['valorInicial'], $parcela['desconto'], $parcela['tributacao'], $parcela['juros'], $parcela['arredondamento'], $parcela['valorFinal'], $parcela['formaPagamento'], $parcela['carteiraOrigem'], $parcela['carteiraDestino'], $indispensavel, $descricao);
                    array_push($movimentos, $movimento);
                }
                $dao_movimento = new DAO_Movimento();
                return $dao_movimento->salvar($movimentos);
            } catch (Exception $e) {
                $retorno  = "Erro ao salvar um objeto 'Movimento' (Código do erro: ".$e->getCode().").<br>";
                $retorno .= $e->getMessage();
                return $retorno;
            }
        }
    }
    
?>