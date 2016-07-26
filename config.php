<?php

$host = @gethostname();
$projects = [
    'project1' => '/git/repositories/project1',
    'project2' => '/git/repositories/project2',
];


return [
    'jira_url' => 'http://crowd.jira.com/crowd/rest/',
    'app_auth' => 'qwe123===',
    'projects' => $projects,
];
