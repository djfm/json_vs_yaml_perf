# Yaml vs Json decoding speed in PHP

At PrestaShop we were wondering if Yaml was still much slower than Json, because the benchmarks we found are a [bit old](http://www.pauldix.net/2008/08/serializing-dat.html).

It seems that, unfortunately, Yaml parsers did not improve a lot performance-wise.

The following measures the time taken to decode an array with 625 leaves 4 levels deep.

The yaml parser used is that of [symfony](http://symfony.com/doc/current/components/yaml/introduction.html).

Here are the results:

```
Yaml: 58.57ms average
Json: 0.48ms average
```

Same test with the yaml_parse function from the PECL extension:

```
Yaml: 1.42ms average
Json: 0.49ms average
```
