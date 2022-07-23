<?php

namespace financas_api\model\bo;

use Exception;
use financas_api\exceptions\bo\ValueNotAcceptException;
use financas_api\model\dao\Owner as DaoOwner;
use financas_api\model\entity\Owner as EntityOwner;

class Owner
{
    public function create($name = '', $active)
    {
        $active = ($active == "true" OR $active == 1);

        $owner = new EntityOwner(0, $name, $active);
        $dao = new DaoOwner();
        return $dao->insert($owner);
    }

    // public function update(int $id, bool $active)
    // {
    //     $owner = self::findEntity($id);

    //     $active = ($active == "true" OR $active == 1);
    //     $owner->setActive($active);
        
    //     $dao = new DaoOwner();
    //     return $dao->update($owner);
    // }

    public function update(int $id, array $params)
    {
        if (count($params) < 1) {
            throw new ValueNotAcceptException('Parameters must be informed for the update', 0201001);
            
        }
        $owner = self::findEntity($id);

        $hasUpdate = false;
        if (isset($params['active'])) {
            $active = $params['active'] === 1;
            $owner->setActive($active);
            $hasUpdate = true;
        }
        
        if ($hasUpdate) {
            $dao = new DaoOwner();
            return $dao->update($owner);
        }

        return ['' => ''];
    }

    public function delete(int $id)
    {
        $dao = new DaoOwner();
        return $dao->delete($id);
    }

    public function find(int $id)
    {
        $owner = self::findEntity($id);
        return $owner->entityToJson();
    }

    public function findEntity(int $id)
    {
        $dao = new DaoOwner();
        return $dao->find($id);
    }
        
        // public function atualizar(int $codigo, string $nome, int $dono, bool $ativo)
        // {
        //     try {
        //         $carteira = new Carteira($codigo, $nome, $dono, $ativo);
        //         $dao_carteira = new DAO_Carteira();
        //         return $dao_carteira->atualizar($carteira);
        //     } catch (Exception $e) {
        //         $retorno  = "Erro ao atualizar um objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
        //         $retorno .= $e->getMessage();
        //         return $retorno;
        //     }
        // }
        
        // public function buscarPorFiltro(string $nome = null, int $dono = null, bool $ativo = null)
        // {
        //     try {
        //         $parametros = array("nome" => $nome, "dono" => $dono, "ativo" => $ativo);
        //         $dao_carteira = new DAO_Carteira();
        //         return $dao_carteira->pesquisar($parametros);
        //     } catch (Exception $e) {
        //         $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
        //         $retorno .= $e->getMessage();
        //         return $retorno;
        //     }
        // }
        
        // public function buscarPorCodigo(int $codigo)
        // {
        //     try {
        //         $parametros = array("codigo" => $codigo);
        //         $dao_carteira = new DAO_Carteira();
        //         return $dao_carteira->pesquisar($parametros);
        //     } catch (Exception $e) {
        //         $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
        //         $retorno .= $e->getMessage();
        //         return $retorno;
        //     }
        // }
        
        // public function buscarAtivos()
        // {
        //     try {
        //         $parametros = array("ativo" => true);
        //         $dao_carteira = new DAO_Carteira();
        //         return $dao_carteira->pesquisar($parametros);
        //     } catch (Exception $e) {
        //         $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
        //         $retorno .= $e->getMessage();
        //         return $retorno;
        //     }
        // }
        
        // public function buscarInativos()
        // {
        //     try {
        //         $parametros = array("ativo" => false);
        //         $dao_carteira = new DAO_Carteira();
        //         return $dao_carteira->pesquisar($parametros);
        //     } catch (Exception $e) {
        //         $retorno  = "Erro ao buscar o objeto 'Carteira' (Código do erro: ".$e->getCode().").<br>";
        //         $retorno .= $e->getMessage();
        //         return $retorno;
        //     }
        // }
        
    }
    
?>