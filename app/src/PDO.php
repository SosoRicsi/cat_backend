<?php

namespace App\src;

use App\Config\Database;
use PDO as GlobalPDO;
use PDOStatement;

class PDO
{
	private static ?self $instance = null;
	protected GlobalPDO $pdo;

	private function __construct()
	{
		$dsn = sprintf(
			'mysql:host=%s;dbname=%s;charset=%s',
			Database::host(),
			Database::database(),
			Database::charset()
		);

		$this->pdo = new GlobalPDO($dsn, Database::user(), Database::password(), [
			GlobalPDO::ATTR_ERRMODE => GlobalPDO::ERRMODE_EXCEPTION,
			GlobalPDO::ATTR_DEFAULT_FETCH_MODE => GlobalPDO::FETCH_ASSOC,
		]);
	}

	public static function instance(): self
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function raw(): GlobalPDO
	{
		return $this->pdo;
	}

	public function query(string $sql): PDOStatement
	{
		return $this->pdo->query($sql);
	}

	public function prepare(string $sql): PDOStatement
	{
		return $this->pdo->prepare($sql);
	}

	public function select(string $sql, array $params = []): array
	{
		$stmt = $this->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll();
	}

	public function execute(string $sql, array $params = []): bool
	{
		$stmt = $this->prepare($sql);
		return $stmt->execute($params);
	}
}
