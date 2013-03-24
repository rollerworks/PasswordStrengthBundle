<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\Blacklist;

/**
 * Sqlite Blacklist Provider.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 * @author Fabien Potencier
 */
class SqliteProvider extends PdoProvider
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $db = $this->initDb();

        if ($db instanceof \SQLite3) {
            return $this->fetch($db, 'SELECT passwd FROM rollerworks_passdbl');
        }

        return $this->exec($db, 'SELECT passwd FROM rollerworks_passdbl');
    }

    /**
     * {@inheritdoc}
     */
    public function close($db)
    {
        if ($db instanceof \SQLite3) {
            $db->close();
            $this->db = null;
        }
    }

    /**
     * @throws \RuntimeException When neither of SQLite3 or PDO_SQLite extension is enabled
     */
    protected function initDb()
    {
        if (null === $this->db || $this->db instanceof \SQLite3) {
            if (0 !== strpos($this->dsn, 'sqlite')) {
                throw new \RuntimeException(sprintf('Please check your configuration. You are trying to use Sqlite with an invalid dsn "%s". The expected format is "sqlite:/path/to/the/db/file".', $this->dsn));
            }
            if (class_exists('SQLite3')) {
                $db = new \SQLite3(substr($this->dsn, 7, strlen($this->dsn)), \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE);
                if (method_exists($db, 'busyTimeout')) {
                    // busyTimeout only exists for PHP >= 5.3.3
                    $db->busyTimeout(1000);
                }
            } elseif (class_exists('PDO') && in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
                $db = new \PDO($this->dsn);
            } else {
                throw new \RuntimeException('You need to enable either the SQLite3 or PDO_SQLite extension for the profiler to run properly.');
            }

            $db->exec('PRAGMA temp_store=MEMORY; PRAGMA journal_mode=MEMORY;');
            $db->exec('CREATE TABLE IF NOT EXISTS rollerworks_passdbl (passwd STRING, created_at INTEGER)');
            $db->exec('CREATE UNIQUE INDEX IF NOT EXISTS passwd_idx ON rollerworks_passdbl (passwd)');

            $this->db = $db;
        }

        return $this->db;
    }

    protected function exec($db, $query, array $args = array())
    {
        if ($db instanceof \SQLite3) {
            $stmt = $this->prepareStatement($db, $query);
            foreach ($args as $arg => $val) {
                $stmt->bindValue($arg, $val, is_int($val) ? \SQLITE3_INTEGER : \SQLITE3_TEXT);
            }

            $res = $stmt->execute();
            if (false === $res) {
                throw new \RuntimeException(sprintf('Error executing SQLite query "%s"', $query));
            }
            $res->finalize();
        } else {
            parent::exec($db, $query, $args);
        }
    }

    protected function fetch($db, $query, array $args = array())
    {
        $return = array();

        if ($db instanceof \SQLite3) {
            $stmt = $this->prepareStatement($db, $query, true);
            foreach ($args as $arg => $val) {
                $stmt->bindValue($arg, $val, is_int($val) ? \SQLITE3_INTEGER : \SQLITE3_TEXT);
            }
            $res = $stmt->execute();
            while ($row = $res->fetchArray(\SQLITE3_ASSOC)) {
                $return[] = $row;
            }
            $res->finalize();
            $stmt->close();
        } else {
            $return = parent::fetch($db, $query, $args);
        }

        return $return;
    }
}
