<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerGQBspH3\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerGQBspH3/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerGQBspH3.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerGQBspH3\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerGQBspH3\App_KernelDevDebugContainer([
    'container.build_hash' => 'GQBspH3',
    'container.build_id' => '2376c7e0',
    'container.build_time' => 1612353390,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerGQBspH3');
