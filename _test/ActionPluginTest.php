<?php

namespace dokuwiki\plugin\disableactionsbygroup\test;

use DokuWikiTest;

require_once __DIR__ . '/../action.php';

/**
 * Test wrapper that exposes selected protected plugin methods.
 */
class TestableActionPlugin extends \action_plugin_disableactionsbygroup
{
    /**
     * Mock plugin configuration used by tests.
     *
     * @var array<string,string>
     */
    protected array $mockConfig = [];

    /**
     * Set test configuration for the plugin.
     *
     * @param array<string,string> $config Plugin configuration values.
     *
     * @return void
     */
    public function setTestConfig(array $config): void
    {
        $this->mockConfig = $config;
    }

    /**
     * Return a mocked configuration value.
     *
     * @param string $setting Configuration key.
     * @param mixed  $notset Fallback value when the key is missing.
     *
     * @return mixed
     */
    public function getConf($setting, $notset = false)
    {
        return $this->mockConfig[$setting] ?? $notset;
    }

    /**
     * Apply the plugin restriction logic and return effective disableactions.
     *
     * @param array<int,string> $groups User groups to evaluate.
     * @param string            $base   Global DokuWiki disableactions value.
     *
     * @return string
     */
    public function applyForGroups(array $groups, string $base): string
    {
        global $conf;

        $conf['disableactions'] = $base;
        $this->disablebygroupids($groups, $this->parse_action_list($base));
        return $conf['disableactions'];
    }
}

/**
 * Integration-style unit tests for additive disableactions behavior.
 *
 * @group plugin_disableactionsbygroup
 * @group plugins
 */
class ActionPluginTest extends DokuWikiTest
{
    /**
     * Enable the plugin inside the DokuWiki test environment.
     *
     * @var array<int,string>
     */
    protected $pluginsEnabled = ['disableactionsbygroup'];

    /**
     * Create a configured plugin instance for tests.
     *
     * @return TestableActionPlugin
     */
    protected function newPlugin(): TestableActionPlugin
    {
        $plugin = new TestableActionPlugin();
        $plugin->setTestConfig([
            'disableactionsbygroup' => 'admin:;user:media, edit;user2:diff;ALL:register;broken; :oops;staff:media,,source',
        ]);

        return $plugin;
    }

    /**
     * Empty group rules must preserve globally disabled actions.
     *
     * @return void
     */
    public function testEmptyRulePreservesGlobalActions(): void
    {
        $plugin = $this->newPlugin();

        $this->assertSame('edit,source', $plugin->applyForGroups(['admin'], 'edit,source'));
    }

    /**
     * First matching groups add actions to the global baseline.
     *
     * @return void
     */
    public function testFirstMatchingGroupAddsActionsToBaseline(): void
    {
        $plugin = $this->newPlugin();

        $this->assertSame('edit,media', $plugin->applyForGroups(['user'], 'edit'));
    }

    /**
     * Failed-login ALL handling must remain additive.
     *
     * @return void
     */
    public function testAllRuleIsAdditive(): void
    {
        $plugin = $this->newPlugin();

        $this->assertSame('edit,register', $plugin->applyForGroups(['ALL'], 'edit'));
    }

    /**
     * Users without a matching rule keep the original baseline.
     *
     * @return void
     */
    public function testUnmatchedUsersKeepOriginalBaseline(): void
    {
        $plugin = $this->newPlugin();

        $this->assertSame('edit', $plugin->applyForGroups(['guest'], 'edit'));
    }

    /**
     * Whitespace is trimmed and duplicates are removed in stable order.
     *
     * @return void
     */
    public function testWhitespaceIsTrimmedAndDuplicatesAreRemoved(): void
    {
        $plugin = $this->newPlugin();

        $this->assertSame('edit,media,source', $plugin->applyForGroups(['staff'], 'edit,media'));
    }

    /**
     * Malformed entries must not block later valid matches.
     *
     * @return void
     */
    public function testMalformedEntriesDoNotAffectLaterValidMatches(): void
    {
        $plugin = $this->newPlugin();

        $this->assertSame('edit,diff', $plugin->applyForGroups(['user2'], 'edit'));
    }
}
