<?php

/**
 * [Get Path Root GetRootPath, USE ALWAYS TO REQUIRE FILES]
 *
 * @return string
 * 
 */
function GetRootPath():string {
    return "{$_SERVER['DOCUMENT_ROOT']}/../";
}