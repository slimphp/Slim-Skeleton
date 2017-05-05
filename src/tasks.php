<?php
// CLI Tasks

$app->get('/hello', function () {
  return "Hello!\n";
})->setName('This command output "Hello!"');
