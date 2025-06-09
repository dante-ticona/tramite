<?php
namespace App\Http\Services;


enum DataTypeEnum: string
{
    //'integer', 'boolean', 'text', 'float', 'timestamp'
    case INTEGER = "integer";
    case BOOLEAN = "boolean";
    case TEXT = "text";
    case FLOAT = "float";
    case TIMESTAMP = "timestamp";
    case JSON = "json";
}

