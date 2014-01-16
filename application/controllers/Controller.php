<?php

abstract class Controller
{
    protected $app;

    public function __construct()
    {
        $this->app = App::object();
    }