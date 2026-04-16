<?php

/**
 * DokuWiki Plugin disableactionsbygroup (Action Component)
 *
 * @license GPL-2.0-only
 * @author  Andreas Hansson
 */

if (!defined('DOKU_INC')) {
    die();
}

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
        global $conf;
        global $USERINFO;

        $coreDisabledActions = $this->parse_action_list((string) ($conf['disableactions'] ?? ''));

        if (!$event->result) {
            $this->disablebygroupids(['ALL'], $coreDisabledActions);
            return;
        }

        $groupids = [];
        if (isset($USERINFO['grps']) && is_array($USERINFO['grps'])) {
            $groupids = $USERINFO['grps'];
        }

        $this->disablebygroupids($groupids, $coreDisabledActions);
    }

    /**
     * Add disabled actions when the current user belongs to a configured group.
     *
     * The first matching group chooses the extra restrictions to merge with the
     * global DokuWiki disableactions baseline.
     *
     * @param array $groupids            The groups of the current user.
     * @param array $coreDisabledActions The globally disabled DokuWiki actions.
     *
     * @return void
     */
    protected function disablebygroupids($groupids, $coreDisabledActions)
    {
        global $conf;

        $matchedActions = $this->find_matching_group_actions((array) $groupids);
        if ($matchedActions === null) {
            return;
        }

        $mergedActions = array_merge(
            $coreDisabledActions,
            $this->parse_action_list($matchedActions)
        );

        $conf['disableactions'] = implode(',', array_values(array_unique($mergedActions)));
    }

    /**
     * Return the configured action list for the first matching group.
     *
     * @param array $groupids The groups of the current user.
     *
     * @return string|null
     */
    protected function find_matching_group_actions($groupids)
    {
        $actionsbygroup = explode(';', (string) $this->getConf('disableactionsbygroup'));
        foreach ($actionsbygroup as $groupandactions) {
            if ($groupandactions === '') {
                continue;
            }

            $parts = explode(':', $groupandactions, 2);
            if (count($parts) !== 2) {
                continue;
            }

            [$group, $actions] = $parts;
            $group = trim($group);
            if ($group === '') {
                continue;
            }

            foreach ((array) $groupids as $membergroup) {
                if ((string) $membergroup === $group) {
                    return $actions;
                }
            }
        }

        return null;
    }

    /**
     * Parse a comma-separated action list into normalized action names.
     *
     * @param string $actions The raw action list.
     *
     * @return array
     */
    protected function parse_action_list($actions)
    {
        $normalized = [];
        foreach (explode(',', $actions) as $action) {
            $action = trim($action);
            if ($action === '') {
                continue;
            }

            $normalized[] = $action;
        }

        return $normalized;
    }
}

// vim:ts=4:sw=4:et:
