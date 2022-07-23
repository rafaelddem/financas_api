<?php

namespace financas_api\model\dao;

use Exception;
use financas_api\exceptions\dao\DataNotExistException;
use financas_api\model\entity\Owner as EntityOwner;
use \PDO;

class Owner extends DataAccessObject
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert(EntityOwner $owner)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("insert into owner (name, active) values (:name, :active);");
            $name = $owner->getName();
            $active = $owner->getActive();
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while creating an \'owner\'. Please inform support');
            }

            self::getPDO()->commit();
            return '\'Owner\' successfully created';
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while creating an \'owner\'. Please inform support');
        }
    }

    public function update(EntityOwner $owner)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("update owner set active = :active where id = :id");
            $active = $owner->getActive();
            $id = $owner->getId();
            $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while updating an \'owner\'. Please inform support');
            }

            self::getPDO()->commit();
            return '\'Owner\' successfully updated';
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while updating an \'owner\'. Please inform support');
        }
    }

    public function delete(int $id)
    {
        self::getPDO()->beginTransaction();

        try {
            $stmt = self::getPDO()->prepare("delete from owner where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                self::getPDO()->rollback();
                throw new Exception('An error occurred while deleting an \'owner\'. Please inform support');
            }

            self::getPDO()->commit();
            return '\'Owner\' successfully deleted';
        } catch (\Throwable $th) {
            self::getPDO()->rollback();
            throw new Exception('An error occurred while deleting an \'owner\'. Please inform support');
        }
    }

    public function find(int $id)
    {
        try {
            $stmt = self::getPDO()->prepare("select * from owner where id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $owner = '';
            if ($stmt->execute()) {
                if($stmt->rowCount() > 0) {
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                        $owner = new EntityOwner($row->id, $row->name, boolval($row->active));
                    }
                } else {
                    throw new DataNotExistException('There are no data for this \'id\'', 12);
                }
            }

            return $owner;
        } catch (DataNotExistException $ex) {
            throw $ex;
        } catch (\Throwable $th) {
            throw new Exception('An error occurred while updating an \'owner\'. Please inform support');
        }
    }
    // public function pesquisar(array $colunas)
    // {
    //     self::getPDO()->beginTransaction();
        
    //     $temFiltro = false;
    //     $sql = "select * from tbfi_carteira";
    //     if (isset($colunas['codigo'])) {
    //         $codigo = $colunas['codigo'];
    //         $sql .= ($temFiltro) ? " and" : " where";
    //         $sql .= " int_codigo = :codigo";
    //         $temFiltro = true;
    //     }
    //     if (isset($colunas['nome'])) {
    //         $nome = "%".$colunas['nome']."%";
    //         $sql .= ($temFiltro) ? " and" : " where";
    //         $sql .= " str_nome like :nome";
    //         $temFiltro = true;
    //     }
    //     if (isset($colunas['dono'])) {
    //         $dono = $colunas['dono'];
    //         $sql .= ($temFiltro) ? " and" : " where";
    //         $sql .= " chr_dono = :dono";
    //         $temFiltro = true;
    //     }
    //     if (isset($colunas['ativo'])) {
    //         $ativo = $colunas['ativo'];
    //         $sql .= ($temFiltro) ? " and" : " where";
    //         $sql .= " chr_ativo = :ativo";
    //         $temFiltro = true;
    //     }
    //     $sql .= ";";
    //     $stmt = self::getPDO()->prepare($sql);
    //     if (isset($codigo)) $stmt->bindParam(':codigo', $codigo,PDO::PARAM_INT);
    //     if (isset($nome)) $stmt->bindParam(':nome', $nome,PDO::PARAM_STR);
    //     if (isset($dono)) $stmt->bindParam(':dono', $dono,PDO::PARAM_INT);
    //     if (isset($ativo)) $stmt->bindParam(':ativo', $ativo,PDO::PARAM_INT);
            
    //     if($stmt->execute()) {
    //         if($stmt->rowCount() > 0) {
    //             $carteiras = array();
    //             while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    //                 $carteira = new Carteira($row->int_codigo, $row->str_nome, $row->chr_dono, boolval($row->chr_ativo));
    //                 array_push($carteiras, $carteira);
    //             }
    //         } else {
    //             self::getPDO()->rollback();
    //             $retorno  = "Não há registro para os filtros pesquisados.";
    //             return $retorno;
    //         }
    //     } else {
    //         self::getPDO()->rollback();
    //         throw new Exception("Erro interno ao sistema, ao tentar buscar o(s) objeto(s) de tipo 'Carteira'.", 6);
    //     }
        
    //     self::getPDO()->commit();
    //     return $carteiras;
    // }
}
    
?>