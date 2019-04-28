<?php

function gravatar_url($email) {
    $email = md5($email);

    return "//gravatar.com/avatar/$email?".http_build_query([
        's' => 60,
        'd' => '//s3.amazonaws.com/laracasts/images/default-square-avatar.jpg'
    ]);
}

function df($object = null) {
    $bt = debug_backtrace();

    $caller = array_shift($bt);

    echo <<<EOT
<div>
n:{$caller['line']} - {$caller['file']}    
</div>
EOT;

    dd($object);
}