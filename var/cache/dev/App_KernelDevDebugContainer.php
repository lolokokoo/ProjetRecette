<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerRN9sD27\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerRN9sD27/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerRN9sD27.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerRN9sD27\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerRN9sD27\App_KernelDevDebugContainer([
    'container.build_hash' => 'RN9sD27',
    'container.build_id' => '8b272763',
    'container.build_time' => 1677055201,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerRN9sD27');
