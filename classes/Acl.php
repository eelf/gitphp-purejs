<?php
/**
 * @author Evgeniy Makhrov <emakhrov@gmail.com>
 */

namespace GitPHP;

class Acl
{
    const CONF_PROJECT_ACCESS_GROUPS_KEY = 'project_access_groups';

    const CONF_ACCESS_GROUP_KEY = 'access_group';

    const GITOSIS_ADMIN_GROUP = 'gitosis-admin';

    /** @var Jira */
    protected $Jira;

    public function __construct($Jira)
    {
        $this->Jira = $Jira;
    }

    public function isGitosisAdmin(\GitPHP_User $User)
    {
        return $this->isGroupMemberCached(self::GITOSIS_ADMIN_GROUP, $User);
    }

    public function isCodeAccessAllowed(\GitPHP_User $User)
    {
        if (empty($User->getId())) {
            return false;
        }

        return $this->isGroupMemberCached(\GitPHP_Config::GetInstance()->GetValue(self::CONF_ACCESS_GROUP_KEY), $User);
    }

    public function isProjectAllowed($project, \GitPHP_User $User)
    {
        $project_access_groups = \GitPHP_Config::GetInstance()->GetValue(self::CONF_PROJECT_ACCESS_GROUPS_KEY);
        if (!is_array($project_access_groups) || empty($project_access_groups[$project])) {
            return true;
        }
        if (empty($User->getId())) {
            return false;
        }
        $groups = $project_access_groups[$project];

        if (!is_array($groups)) $groups = [$groups];

        foreach ($groups as $group_name) {
            if ($this->isGroupMemberCached($group_name, $User)) {
                return true;
            }
        }
        return false;
    }

    protected function isGroupMemberCached($group_name, \GitPHP_User $User)
    {
        $is_in_group = $User->isInGroup($group_name);
        if ($is_in_group === null) {
            $is_in_group = $this->Jira->isGroupMember($User->getId(), $group_name);
            $User->setInGroup($group_name, $is_in_group);
        }
        return $is_in_group;
    }
}
