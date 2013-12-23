<?php
/**
 * PdoSql
 *
 * @category  PdoSql
 * @package   PdoSql
 * @author    Salimane Adjao Moustapha <me@salimane.com>
 */

/**
 * PdoSql
 *
 * This abstract class provides a php interface
 * to all PDO database connections and functions.
 *
 * the array $servers should be in the format:
 * $config = array(
 *    'driver' => 'mysql',
 *    'host' => '127.0.0.1',
 *    'port' => 3306,
 *    'username' => 'username',
 *    'password' => 'password',
 *    'dbname' => 'dbname',
 *    'pconnect' => 0,
 *    'charset' => 'utf8',
 *    );
 *
 * @category PdoSql
 * @package  PdoSql
 * @author    Salimane Adjao Moustapha <me@salimane.com>
 */
class Core_Db
{
    /**
     * queries to be run
     * @var int
     * @access public
     */
    public static $queries = 0;
    /**
     * db connection config paramters
     * @var array
     * @access public
     */
    public $options = array();

    /**
     * query to execute
     * @var string
     * @access public
    */
    public $sql;

    /**
     * instance of PDO, current connection
     * @var ressource
     * @access private
     */
    private $dbh;

    /**
     * error info
     * @var array
     * @access public
     */
    public $error;

    /**
     * error number
     * @var int
     * @access public
     */
    public $errno;

    /**
     * Some methods not declared to be handled by magic __call function
     * @var array
     * @access private
     */
    private static $_undeclared_method = array(
                    'beginTransaction' => 1,
                    'commit' => 1,
                    'errorCode' => 1,
                    'errorInfo' => 1,
                    'getAttribute' => 1,
                    'lastInsertId' => 1,
                    'quote' => 1,
                    'rollBack' => 1,
                    'setAttribute' => 1
    );

    /**
     * Transaction methods
     * @var array
     * @access private
    */
    private static $_trans_method = array(
                    'beginTransaction' => 1,
                    'commit' => 1,
                    'lastInsertId' => 1,
                    'rollBack' => 1,
    );

    /**
     * Creates an instance of PdoSql
     *
     * @param array $config The current config
     *
     * @return PdoSql
    */
    public function __construct($config = array())
    {
        $this->config = $config;
        $this->options = &$this->config;
    }

    /**
     * Magic method to handle all non declared function requests
     *
     * @param string $name The name of the method called.
     * @param array  $args Array of supplied arguments to the method.
     *
     * @return mixed Return value from Redis::__call() based on the command.
     */
    public function __call($method, $args)
    {
        if (isset(self::$_undeclared_method[$method])) {
            if (isset(self::$_trans_method[$method])) {
                return $this->dbh()->$method();
            }
            if (isset($args[0])) {
                return isset($args[1]) ? $this->dbh()->$method($args[0], $args[1]) : $this->dbh()->$method($args[0]);
            } else {
                return $this->dbh()->$method();
            }
        }
    }

    /**
     * Singleton for PDO connections
     *
     * @param array $config The current config
     *
     * @return PdoSql
     */
    public static function getInstance($config = array())
    {
        static $instance;
        if(empty($config)){
            global $dbConfig;
            $config = $dbConfig;
        }
        $key = json_encode($config);
        if (!isset($instance[$key]) || $instance[$key] === null) {
            $instance[$key] = new Core_Db($config);
        }

        return $instance[$key];
    }

    /**
     * Connect to databases using PDO
     *
     * @param array $options The current config
     *
     * @return PDO
     */
    public function connect($options = array())
    {
        try {
            $dbh = new PDO(
                            $options['driver'].':host='.$options['host'].';port='.$options['port'].';dbname='.$options['dbname'].';charset='.$options['charset'],
                            $options['username'],
                            $options['password'],
                            array(
                                            PDO::ATTR_PERSISTENT => ($options['pconnect'] ? true : false),
                                            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                                            PDO::ATTR_EMULATE_PREPARES => false,
                                            PDO::ATTR_TIMEOUT => 1,
                            )
            );
        } catch (PDOException $e) {
            try {
                $dbh = new PDO(
                                $options['driver'].':host='.$options['host'].';port='.$options['port'].';dbname='.$options['dbname'].';charset='.$options['charset'],
                                $options['username'],
                                $options['password'],
                                array(
                                                PDO::ATTR_PERSISTENT => ($options['pconnect'] ? true : false),
                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                                                PDO::ATTR_EMULATE_PREPARES => false,
                                                PDO::ATTR_TIMEOUT => 1,
                                )
                );
            } catch (PDOException $e) {
                $this->errno = $e->getCode();
                $this->error = $e->getMessage();
                error_log("PdoSql cannot connect to: " . $options['host'] . ':' . $options['dbname'] . ' msg: [' . $this->errno . ' ' . $this->error, 0);

                //return false;
                die();
            }
        }
        if ($options['driver'] == 'mysql') {
            $dbh->exec("SET character_set_connection='".$options['charset']
                            ."',character_set_results='".$options['charset']
                            ."',character_set_client=binary,sql_mode=''");
        }

        return $dbh;
    }

    public function exec($statement)
    {
        if ($this->dbh($statement)) {
            $result = @$this->dbh->exec($this->sql);
            if (!$result) {
                $this->errno = $this->errno();
                $this->error = $this->error();
                error_log("PdoSql: error while executing the query. [" . $this->errno . '] ' . json_encode($this->error) . ' ' . $this->sql, 0);
            }

            return $result;
        }

        return false;
    }

    public function run_execute($sql, $data = array(), $bind = false)
    {
        $db = $this->prepare($sql);
        if (!$db) {
            return false;
        }

        if ($bind) {
            $this->_bindValue($db, $data);
            $result = @$db->execute();
        } else {
            $result = @$db->execute($data);
        }

        if (!$result) {
            $this->errno = $db->errorCode();
            $this->error = $db->errorInfo();
            error_log("PdoSql: error while executing the query. [" . $this->errno . '] ' . json_encode($this->error) . ' ' . $sql, 0);

            return false;
        }

        return $db;
    }

    public function prepare($statement, $driver_options = array())
    {
        if ($this->dbh($statement)) {
            $result = @$this->dbh->prepare($this->sql, $driver_options);
            if (!$result) {
                $this->errno = $this->errno();
                $this->error = $this->error();
                error_log("PdoSql: error while preparing the query. [" . $this->errno . '] ' . json_encode($this->error) . ' ' . $this->sql, 0);
            }

            return $result;
        }

        return false;
    }

    public function query($statement)
    {
        if ($this->dbh($statement)) {
            $result = @$this->dbh->query($this->sql);
            if (!$result) {
                $this->errno = $this->errno();
                $this->error = $this->error();
                error_log("PdoSql: error while preparing the query. [" . $this->errno . '] ' . json_encode($this->error) . ' ' . $this->sql, 0);
            }

            return $result;
        }

        return false;
    }

    public function get($sql, $data = array(), $fetch_style = PDO::FETCH_ASSOC)
    {
        $db = $this->run_execute($sql, $data);
        if ($db !== false) {
            return $db->fetch($fetch_style);
        }

        return false;
    }

    public function select($sql, $data = array(), $fetch_style = PDO::FETCH_ASSOC)
    {
        $db = $this->run_execute($sql, $data);
        if ($db !== false) {
            return $db->fetchAll($fetch_style);
        }

        return false;
    }

    public function insert($sql, $data = array())
    {
        $db = $this->run_execute($sql, $data, true);
        if ($db !== false) {
            $insertid = $this->dbh->lastInsertId();

            return $insertid ? $insertid : true;
        } else {
            return false;
        }
    }

    public function update($sql, $data = array())
    {
        $db = $this->run_execute($sql, $data, true);
        if ($db !== false) {
            $rowcount = $db->rowCount();

            return $rowcount !== 0 ? $rowcount : true;
        } else {
            return false;
        }
    }

    public function replace($sql, $data = array())
    {
        return $this->update($sql, $data);
    }

    public function delete($sql, $data = array())
    {
        $db = $this->run_execute($sql, $data);
        if ($db !== false) {
            $rowcount = $db->rowCount();

            return $rowcount ? $rowcount : true;
        }

        return false;
    }

    public function limit($sql, $limit = 0, $offset = 0, $data = array(), $fetch_style = PDO::FETCH_ASSOC)
    {
        if ($limit > 0) {
            $sql .= $offset > 0 ? " LIMIT $offset, $limit" : " LIMIT $limit";
        }

        return $this->select($sql, $data, $fetch_style);
    }

    public function page($sql, $page = 1, $size = 20, $data = array(), $fetch_style = PDO::FETCH_ASSOC)
    {
        $page = isset($page) ? max(intval($page), 1) : 1;
        $size = max(intval($size), 1);
        $offset = ($page-1)*$size;

        return $this->limit($sql, $size, $offset, $data, $fetch_style);
    }

    public function select_db($dbname)
    {
        return $this->exec("USE $dbname");
    }

    public function list_fields($table, $field = null)
    {
        $sql = "SHOW COLUMNS FROM `$table`";
        if ($field) {
            $sql .= " LIKE '$field'";
        }

        return $this->query($sql);
    }

    public function list_tables($dbname = null)
    {
        $tables = array();
        $sql = $dbname ? "SHOW TABLES FROM `$dbname`" : "SHOW TABLES";
        $result = $this->query($sql);
        foreach ($result as $r) {
            $tables[]	= array_pop($r);
        }

        return $tables;
    }

    public function list_dbs()
    {
        $dbs = array();
        $result = $this->query("SHOW DATABASES");
        foreach ($result as $r) {
            foreach ($r as $db) {
                $dbs[] = $db;
            }
        }

        return $dbs;
    }

    public function get_primary($table)
    {
        $primary = array();
        $result = $this->query("SHOW COLUMNS FROM `$table`");
        foreach ($result as $r) {
            if ($r['Key'] == 'PRI') {
                $primary[] = $r['Field'];
            }
        }

        return count($primary) == 1 ? $primary[0] : (empty($primary) ? null : $primary);
    }

    public function get_var($var = null)
    {
        $variables = array();
        $sql = $var === null ? '' : " LIKE '$var'";
        $result = $this->query("SHOW VARIABLES $sql");
        foreach ($result as $r) {
            if ($var !== null && isset($r['Value'])) {
                return $r['Value'];
            }
            $variables[$r['Variable_name']] = $r['Value'];
        }

        return $variables;
    }

    public function version()
    {
        $db = $this->query("SELECT version()");

        return $db ? $db->fetchColumn(0) : false;
    }

    public function errno()
    {
        return $this->errno === null ? $this->errorCode() : $this->errno;
    }

    public function error()
    {
        if ($this->error === null) {
            return $this->errorInfo();
        } else {
            $this->error['sql'] = $this->sql;

            return $this->error;
        }
    }

    private function dbh($sql = null)
    {
        $this->sql = $sql;
        if ($sql !== null) {
            self::$queries++;
        }

        if ($this->dbh === null) {
            $this->dbh = $this->connect($this->config);
        }

        return $this->dbh;
    }

    private function _bindValue(& $db, $data)
    {
        if (!is_array($data)) {
            return false;
        }
        foreach ($data as $k => $v) {
            $k = is_numeric($k) ? $k + 1 : ':' . $k;
            $db->bindValue($k, $v);
        }

        return true;
    }
}
