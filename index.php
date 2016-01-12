<?php

@ini_set('display_errors', 'on');
require implode(DIRECTORY_SEPARATOR, [
    'vendor', 'autoload.php'
]);

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser as YamlParser;

function generate_random_string()
{
    return md5(rand());
}

function generate_complex_array(array $nodesPerLayer=array(5,5,5,5))
{
    $layer = [];
    $n = array_shift($nodesPerLayer);
    for ($i = 0; $i < $n; ++$i) {
        $layer[generate_random_string()] = empty($nodesPerLayer) ?
            generate_random_string() :
            generate_complex_array($nodesPerLayer)
        ;
    }
    return $layer;
}

function measureTime(callable $cb)
{
    $tStart = microtime(true);
    $cb();
    return (microtime(true) - $tStart) * 1000;
}

function benchmark(array $input)
{
    $json = json_encode($input, JSON_PRETTY_PRINT);
    $yaml = Yaml::dump($input, 100);

    $tJson = measureTime(function () use ($json) {
        json_decode($json, true);
    });

    $yamlParser = new YamlParser;
    $tYaml = measureTime(function () use ($yaml, $yamlParser) {
        $yamlParser->parse($yaml);
    });

    return [
        'json' => $tJson,
        'yaml' => $tYaml
    ];
}

function benchmark_many($iterations=10)
{
    $res = [
        'json' => 0,
        'yaml' => 0
    ];

    for ($i = 0; $i < $iterations; ++$i) {
        $stats = benchmark(generate_complex_array());
        $res['json'] += $stats['json'];
        $res['yaml'] += $stats['yaml'];
    }

    $res['json'] /= $iterations;
    $res['yaml'] /= $iterations;

    return $res;
}

$stats = benchmark_many();

$yaml = round($stats['yaml'], 2);
$json = round($stats['json'], 2);

echo "Yaml: {$yaml}ms average\n";
echo "Json: {$json}ms average\n";
