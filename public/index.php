<?php

// =================================================================================================
//                                      Public Entry Point
// =================================================================================================

// --- 1. Load Configuration & Core Bootstrap ---
// This file will initialize everything the application needs to run.
require_once '../src/bootstrap.php';


// --- 2. Instantiate Core Application Class ---
// The App class will handle the routing.
$app = new Core\App();
