<?php

declare(strict_types=1);

define('DOKU_INC', __DIR__ . '/');
define('DOKU_PLUGIN', sys_get_temp_dir() . '/dokuwiki-disableactionsbygroup-tests/');

@mkdir(DOKU_PLUGIN, 0777, true);

file_put_contents(
    DOKU_PLUGIN . 'action.php',
    <<<'PHP'
<?php
class DokuWiki_Action_Plugin
{
    protected $mockConf = [];

    public function getConf($key)
    {
        return $this->mockConf[$key] ?? '';
    }
}

class Doku_Event_Handler
{
}

class Doku_Event
{
    public $result = true;
}
PHP
);

require __DIR__ . '/../action.php';

class disableactionsbygroup_test_plugin extends action_plugin_disableactionsbygroup
{
    public function __construct(array $mockConf)
    {
        $this->mockConf = $mockConf;
    }

    public function apply(array $groups, string $base): string
    {
        global $conf;

        $conf = ['disableactions' => $base];
        $this->disablebygroupids($groups, $this->parse_action_list($base));
        return $conf['disableactions'];
    }
}

/**
 * Assert expected and actual values match.
 *
 * @param string $label Test case label.
 * @param string $expected Expected value.
 * @param string $actual Actual value.
 *
 * @return void
 */
function assert_same(string $label, string $expected, string $actual): void
{
    if ($expected === $actual) {
        echo '[PASS] ' . $label . PHP_EOL;
        return;
    }

    fwrite(
        STDERR,
        '[FAIL] ' . $label . ': expected "' . $expected . '" but got "' . $actual . '"' . PHP_EOL
    );
    exit(1);
}

$plugin = new disableactionsbygroup_test_plugin([
    'disableactionsbygroup' => 'admin:;user:media, edit;user2:diff;ALL:register;broken; :oops;staff:media,,source',
]);

assert_same(
    'empty rule preserves global actions',
    'edit,source',
    $plugin->apply(['admin'], 'edit,source')
);

assert_same(
    'first matching group adds actions to the baseline',
    'edit,media',
    $plugin->apply(['user'], 'edit')
);

assert_same(
    'ALL path is additive',
    'edit,register',
    $plugin->apply(['ALL'], 'edit')
);

assert_same(
    'unmatched users keep the original baseline',
    'edit',
    $plugin->apply(['guest'], 'edit')
);

assert_same(
    'whitespace is trimmed and duplicates are removed deterministically',
    'edit,media,source',
    $plugin->apply(['staff'], 'edit,media')
);

assert_same(
    'malformed entries do not affect a valid later match',
    'edit,diff',
    $plugin->apply(['user2'], 'edit')
);

echo 'All regression checks passed.' . PHP_EOL;
