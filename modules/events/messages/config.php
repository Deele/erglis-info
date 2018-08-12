<?php
/**
 * Used to generate messages for this module
 *
 * Example:
 * ```
 * yii message/extract ./modules/events/messages/config.php
 * ```
 */
return [
    'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR . '..',
    'languages' => ['en'],
    'sort' => true,
    'markUnused' => true,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/migrations',
    ],
    'format' => 'php',
    'messagePath' => __DIR__,
    'overwrite' => true,
    'ignoreCategories' => [
        'yii',
        'app.common',
    ],
];
