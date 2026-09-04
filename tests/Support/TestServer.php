<?php

declare(strict_types=1);

namespace FFB\Tests\Support;

final class TestServer
{
    /** @var resource|null */
    private static $process = null;
    private static string $baseUrl = 'http://127.0.0.1:8765/';

    public static function ensureRunning(): void
    {
        $configured = getenv('FFB_TEST_BASE_URL') ?: self::$baseUrl;
        self::$baseUrl = rtrim($configured, '/') . '/';

        if (self::isReachable()) {
            return;
        }

        $root = dirname(__DIR__, 2);
        $router = $root . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'router.php';
        $host = parse_url(self::$baseUrl, PHP_URL_HOST) ?: '127.0.0.1';
        $port = (int)(parse_url(self::$baseUrl, PHP_URL_PORT) ?: 8765);

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', sys_get_temp_dir() . '/ffb_test_server_stdout.log', 'a'],
            2 => ['file', sys_get_temp_dir() . '/ffb_test_server_stderr.log', 'a'],
        ];

        $cmd = [
            PHP_BINARY,
            '-S',
            $host . ':' . $port,
            $router,
        ];

        self::$process = proc_open($cmd, $descriptors, $pipes, $root);
        if (!is_resource(self::$process)) {
            throw new \RuntimeException('Failed to start PHP built-in server');
        }

        $deadline = microtime(true) + 10.0;
        while (microtime(true) < $deadline) {
            if (self::isReachable()) {
                register_shutdown_function([self::class, 'stop']);
                return;
            }
            usleep(100000);
        }

        self::stop();
        throw new \RuntimeException('PHP test server did not become reachable at ' . self::$baseUrl);
    }

    public static function baseUrl(): string
    {
        return self::$baseUrl;
    }

    public static function stop(): void
    {
        if (is_resource(self::$process)) {
            $status = proc_get_status(self::$process);
            if (!empty($status['pid'])) {
                if (DIRECTORY_SEPARATOR === '\\') {
                    exec('taskkill /PID ' . (int)$status['pid'] . ' /F /T 2>NUL');
                } else {
                    exec('kill ' . (int)$status['pid'] . ' 2>/dev/null');
                }
            }
            proc_close(self::$process);
            self::$process = null;
        }
    }

    private static function isReachable(): bool
    {
        $host = parse_url(self::$baseUrl, PHP_URL_HOST) ?: '127.0.0.1';
        $port = (int)(parse_url(self::$baseUrl, PHP_URL_PORT) ?: 8765);
        $errno = 0;
        $errstr = '';
        $fp = @fsockopen($host, $port, $errno, $errstr, 0.5);
        if ($fp) {
            fclose($fp);
            return true;
        }
        return false;
    }
}
