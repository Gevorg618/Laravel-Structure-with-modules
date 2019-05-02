<?php

use Illuminate\Support\Debug\Dumper;

function ddc(...$args) {
  array_map(function ($x) {
      (new Dumper)->dump($x);
  }, $args);
}

function startMeasure($name, $label=null) {
  if(!class_exists('Debugbar')) {
    return;
  }
  Debugbar::startMeasure($name, $label);
}

function stopMeasure($name) {
  if(!class_exists('Debugbar')) {
    return;
  }

  Debugbar::stopMeasure($name);
}

function debugInfo(...$args) {
  if(!class_exists('Debugbar')) {
    return;
  }

  Debugbar::info($args);
}