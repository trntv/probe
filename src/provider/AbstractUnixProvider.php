<?php

namespace probe\provider;

/**
 * Class AbstractNixProvider
 * @author Eugene Terentev <eugene@terentev.net>
 */
abstract class AbstractUnixProvider extends AbstractProvider
{
    protected $cpuInfo;
    protected $memInfo;
    protected $sysctlInfo;

    /**
     * @param int $interval
     * @return array
     */
    public function getCpuUsage($interval = 1)
    {
        $stat = function() {
            $stat = file_get_contents('/proc/stat');
            $stat = explode("\n", $stat);
            $result = [];
            foreach ($stat as $v) {
                $v = explode(' ', $v);
                if (isset($v[0])
                    && strpos(strtolower($v[0]), 'cpu') === 0
                    && preg_match('/cpu[\d]/sim', $v[0])
                ) {
                    $result[] = array_slice($v, 1, 4);
                }

            }
            return $result;
        };
        $stat1 = $stat();
        usleep($interval * 1000000);
        $stat2 = $stat();
        $usage = [];
        for ($i = 0; $i < $this->getCpuCores(); $i++) {
            $total = array_sum($stat2[$i]) - array_sum($stat1[$i]);
            $idle = $stat2[$i][3] - $stat1[$i][3];
            $usage[$i] = $total !== 0 ? ($total - $idle) / $total : 0;
        }
        return $usage;
    }

    /**
     * @return array
     */
    public function getSysctlInfo()
    {
        if (null === $this->sysctlInfo) {
            $data = explode(PHP_EOL, shell_exec('sysctl -A'));
            $this->sysctlInfo = [];
            foreach ($data as $line) {
                $line = explode(':', $line);
                if (isset($line[0], $line[1])) {
                    $this->sysctlInfo[$line[0]] = trim($line[1]);
                }
            }
        }
        return $this->sysctlInfo;
    }

    /**
     * @return array|null
     */
    public function getMemInfo()
    {
        if (null === $this->memInfo) {
            $data = explode("\n", file_get_contents('/proc/meminfo'));
            $this->memInfo = [];
            foreach ($data as $line) {
                $line = explode(':', $line);
                if (isset($line[0], $line[1])) {
                    $this->memInfo[$line[0]] = trim($line[1]);
                }
            }
        }
        return $this->memInfo;
    }
}
