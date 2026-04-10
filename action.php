<?php

/**
 * DokuWiki Plugin disableactionsbygroup (Action Component)
 *
 * @license GPL-2.0-only
 */

if (!defined('DOKU_INC')) {
    die();
}

if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
}

require_once DOKU_PLUGIN . 'action.php';

class action_plugin_disableactionsbygroup extends DokuWiki_Action_Plugin
{
    /**
     * Register the plugin hooks.
     *
     * @param Doku_Event_Handler $controller The event controller.
     *
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('AUTH_LOGIN_CHECK', 'AFTER', $this, 'handle_post_login');
    }

    /**
     * Apply group-based action restrictions after login checks.
     *
     * @param Doku_Event $event The login event.
     * @param mixed      $param Unused hook parameter.
     *
     * @return void
     */
    public function handle_post_login(Doku_Event &$event, $param)
    {
        global $USERINFO;

        if (!$event->result) {
            $this->disablebygroupids(['ALL']);
            return;
        }

        $groupids = [];
        if (isset($USERINFO['grps']) && is_array($USERINFO['grps'])) {
            $groupids = $USERINFO['grps'];
        }

        $this->disablebygroupids($groupids);
    }

    /**
     * Set disabled actions when the current user belongs to a configured group.
     *
     * The first matching group wins.
     *
     * @param array $groupids The groups of the current user.
     *
     * @return void
     */
    protected function disablebygroupids($groupids)
    {
        global $conf;

        $actionsbygroup = explode(';', (string) $this->getConf('disableactionsbygroup'));
        foreach ($actionsbygroup as $groupandactions) {
            if ($groupandactions === '') {
                continue;
            }

            $parts = explode(':', $groupandactions, 2);
            if (count($parts) !== 2) {
                continue;
            }

            [$group, $action] = $parts;

            foreach ((array) $groupids as $membergroup) {
                if ($membergroup == $group) {
                    $conf['disableactions'] = $action;
                    break 2;
                }
            }
        }
    }
}

// vim:ts=4:sw=4:et:
