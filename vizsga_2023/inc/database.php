<?php
class DB
{
    private static $pdo;

    private static function connect()
    {
        $dsn = 'mysql:host=localhost;dbname=vizsga_2023';
        $username = 'root';
        $password = '';
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        self::$pdo = new PDO($dsn, $username, $password, $options);
    }

    public static function query($sql, $params = array())
    {
        self::connect();
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
