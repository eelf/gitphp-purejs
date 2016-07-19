<?php

namespace GitPHP;

class Git {
    private
        $bin,
        $dir;

    public function __construct($bin, $dir) {
        $this->bin = $bin;
        $this->dir = $dir;
    }

    public function getBranchHeads() {
        $args = [
            "for-each-ref",
            "--sort=-committerdate",
            "--format=\"%(refname)\"",
            "--count=27",
            "--",
            "refs/heads/",
        ];
        return $this->gitDiredExec($args);
    }

    public function getMasterCommits() {
        $args = [
            "rev-list",
            "--max-count=17",
            "HEAD"
        ];
        return $this->gitDiredExec($args);
    }

    public function readRefList() {
        $args = [
            'show-ref',
            '--heads',
            '--tags',
            '--dereference',
        ];
        list ($lines, $err) = $this->gitDiredExec($args);
        if ($err) return [];

        $tags = $heads = [];

        foreach ($lines as $line) {
            if (preg_match('/^(?P<hash>[0-9a-fA-F]{40}) refs\/(?P<type>tags|heads)\/(?P<name>[^^]+)(?P<ref>\^{})?$/', $line, $m)) {
                $key = 'refs/' . $m['type'] . '/' . $m['name'];
                if ($m['type'] == 'tags') {
                    if (!empty($regs['ref']) && $regs['ref'] == '^{}') {
                        $tags[$key] = $m['hash'];
                    } else {
                        $tags[$key] = $m['hash'];
                    }
                } else if ($m['type'] == 'heads') {
                    $heads[$key] = $m['hash'];
                }
            }
        }
        return [$tags, $heads];
    }

    public function batchReadData(array $hashes) {
        $outfile = tempnam('/tmp', 'objlist');
        $hashlistfile = tempnam('/tmp', 'objlist');
        file_put_contents($hashlistfile, implode("\n", $hashes));

        $this->gitDiredExec(['cat-file', '--batch'], ' < ' . escapeshellarg($hashlistfile) . ' > ' . escapeshellarg($outfile));
        unlink($hashlistfile);
        $fp = fopen($outfile, 'r');
        unlink($outfile);

        $types = $contents = [];
        while (!feof($fp)) {
            $ln = rtrim(fgets($fp));
            if (!$ln) continue;
            list ($hash, $type, $n) = explode(" ", rtrim($ln));
            $contents[$hash] = fread($fp, $n);
            $types[$hash] = $type;
        }

        return ['contents' => $contents, 'types' => $types];
    }

    public function gitDiredExec(array $args, $redir = '') {
        $args = array_merge(["--git-dir=$this->dir"], $args);
        $cmd = $this->bin . ' ' . implode(' ', $args) . ($redir ? " $redir" : '');
        exec($cmd, $out, $ret);
        if ($ret) {
            return [null, "ret:$ret out:" . implode("\n", $out)];
        } else {
            return [$out, null];
        }
    }
}
