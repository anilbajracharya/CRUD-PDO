<?php

class DAO {

    /**
     *Connting to database
     * @var type 
     */
    public static $conn;

    function __construct() {
        try {
            $conn = new PDO('mysql:host=' . host . ';dbname=' . db . '', user, pass);
            return self::$conn = $conn;
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    /**
     * Inserting database to C
     * @param String $table
     * @param String $values
     */
    public function Create($table, $values) {
        $sql = NULL;
        $sql_key = NULL;
        $sql_val = NULL;

        $conn = self::$conn;
        $sql = "INSERT INTO $table";
        $count = count($values);
        $i = 0;
  
        foreach ($values as $key => $val) {
            $i++;
            if ($i == 1) {
                $sql_key.="(`" . $key . "`";
            } elseif ($i == $count) {
                $sql_key.=",`" . $key . "`)";
            } else {
                $sql_key.=",`" . $key . "`";
            }

            if ($i == 1) {
                $sql_val .=" VALUES(?";
            } elseif ($i == $count) {
                $sql_val .=",?)";
            } else {
                $sql_val .=",?";
            }
            //
            $array_bind[] = $val;
        }

        $sql.=$sql_key . " " . $sql_val;


        $q = $conn->prepare($sql);
        $q->execute($array_bind);
        if ($q->errorCode() != 0000) {
            print_r($q->errorInfo());
        }
    }
/**
 * getting data from database with like clause
 * @param String $table
 * @param String $where
 * @return type
 * 
 */
    public function ReadLike($table, $where = NULL) {
        $sql = NULL;
        $conn = self::$conn;
//		$data = $conn->query('SELECT * FROM tbl_ecosystem WHERE name = ' . $conn->quote($name));
        if (isset($where)) {
            $i = 0;
            foreach ($where as $key => $val) {
                $i++;
                if ($i == 1) {
                    $sql .=" WHERE  " . $key . "  LIKE '%" . $val . "%'";
                } else {
                    $sql .= " AND  " . $key . " LIKE '%" . $val . "%'";
                }
            }
        }
        if (debug) {
            echo $sql_final = 'SELECT * FROM ' . $table . " " . $sql;
        } else {
            $sql_final = 'SELECT * FROM ' . $table . " " . $sql;
        }

        $stmt = $conn->prepare($sql_final);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
/**
 * Getting data fom database with like and sum clause
 * @param String $table
 * @param String $where
 * @param String $sum
 * @param String $group
 * @param String $not_sum
 * @return type
 */
    public function ReadLikeSum($table, $where = NULL, $sum = NULL, $group = NULL, $not_sum = NULL) {
        //SELECT sum(total)as total, sum(facebook) as facebook FROM `tbl_ecosystem` where date like '%2015-06%' 
        $sql = NULL;
        $conn = self::$conn;
//		$data = $conn->query('SELECT * FROM tbl_ecosystem WHERE name = ' . $conn->quote($name));
        if (isset($where)) {
            $i = 0;
            foreach ($where as $key => $val) {
                $i++;
                if ($i == 1) {
                    $sql .=" WHERE  " . $key . "  LIKE '%" . $val . "%'";
                } else {
                    $sql .= " AND  " . $key . " LIKE '%" . $val . "%'";
                }
            }
        }

        if (isset($sum)) {
            $i = 0;
            $count = count($sum);

            foreach ($sum as $li) {
                $i++;

                //sum(total)as total, sum(facebook) as facebook
                if ($i == 1) {

                    $like .=" SUM(" . $li . ") as " . $li . ",";
                } elseif ($i == $count) {
                    $like .=" SUM(" . $li . ") as " . $li . "";
                } else {
                    $like .=" SUM(" . $li . ") as " . $li . ",";
                }
            }
        }

        $groupby.=" group by `" . $group . "`";
        if (debug) {
            echo $sql_final = 'SELECT ' . $like . $not_sum . ' FROM ' . $table . " " . $sql . $groupby;
        } else {
            $sql_final = 'SELECT ' . $like . $not_sum . ' FROM ' . $table . " " . $sql . $groupby;
        }

        $stmt = $conn->prepare($sql_final);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
/**
 * Getting data from database R
 * @param String $table
 * @param Array $where
 * @return type
 */
    public function Read($table, $where = NULL) {
        $sql = NULL;
        $conn = self::$conn;
//		$data = $conn->query('SELECT * FROM tbl_ecosystem WHERE name = ' . $conn->quote($name));
        if (isset($where)) {
            $i = 0;
            foreach ($where as $key => $val) {
                $i++;
                if ($i == 1) {
                    $sql .=" WHERE " . $key . " = " . self::$conn->quote($val);
                } else {
                    $sql .= " AND " . $key . " = " . self::$conn->quote($val);
                }
            }
        }
        if (debug) {
            echo $sql_final = 'SELECT * FROM ' . $table . " " . $sql;
        } else {
            $sql_final = 'SELECT * FROM ' . $table . " " . $sql;
        }

        $stmt = $conn->prepare($sql_final);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
/**
 * Updating data from database U
 * @param String $table
 * @param Array $values
 * @param Array $where
 */
    public function update($table, $values, $where) {
        $sql = NULL;
        $sql_key = NULL;
        $sql_val = NULL;
        $conn = self::$conn;
        $count = count($values);
        $i = 0;
        foreach ($values as $key => $val) {
            $i++;
            $sql = "UPDATE " . $table;
            if ($i == 1) {
                $sql_key.="SET `" . $key . "`=?";
            } elseif ($i == $count) {
                $sql_key.=",`" . $key . "`=?";
            } else {
                $sql_key.=",`" . $key . "`=?";
            }
            $array_bind[] = $val;
        }
        $i = 0;
        foreach ($where as $key => $val) {
            $i++;
            if ($i == 1) {
                $sql_where.=" WHERE `" . $key . "`=?";
            } elseif ($i == $count) {
                $sql_where.=" AND `" . $key . "`=?";
            } else {
                $sql_where.=" AND `" . $key . "`=?";
            }
            $array_bind[] = $val;
        }
		$sql .=" " . $sql_key . " " . $sql_where;
        $q = $conn->prepare($sql);
        $q->execute($array_bind);

        if ($q->errorCode() != 0000) {
            print_r($q->errorInfo());
        }
    }
/**
 * Deleteing data from database
 * @param String $table
 * @param String $where
 */
    public function delete($table, $where) {
        $sql = NULL;
        $sql_where = NULL;
        $sql_val = NULL;
        $conn = self::$conn;
        $i = 0;
        $sql = "DELETE FROM $table";
        foreach ($where as $key => $val) {
            $i++;
            if ($i == 1) {
                $sql_where.=" WHERE `" . $key . "`=?";
            } elseif ($i == $count) {
                $sql_where.="AND `" . $key . "`=?";
            } else {
                $sql_where.="AND `" . $key . "`=?";
            }
            $array_bind[] = $val;
        }
        $sql.=$sql_where;

        $q = $conn->prepare($sql);
        $q->execute($array_bind);
        if ($q->errorCode() != 0000) {
            print_r($q->errorInfo());
        }
        if (debug) {
            echo '<pre>';
            print_r($q->bindValue());
            print_r($q->errorCode());
            print_r($q->errorInfo());
            echo '</pre>';
        }
    }
/**
 * Truncating table
 * @param type $tblname
 */
    function truncateTable($tblname) {
        $conn = self::$conn;
        $query = $conn->exec("TRUNCATE TABLE $tblname");
    }
/**
 * Getting distinct database
 * @param String $tblname
 * @param Array $field
 * @return type
 */
    function distinct($tblname, $field) {
        $conn = self::$conn;
        $sql_final = 'SELECT distinct(`' . $field . '`) FROM ' . $tblname . "";
        $stmt = $conn->prepare($sql_final);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>