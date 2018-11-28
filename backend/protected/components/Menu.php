<?php

/**
 * Created by PhpStorm.
 * User: druphliu@gamil.com
 * Date: 15-7-29
 * Time: 下午10:40
 */
class Menu extends CWidget
{
    public $menus;

    private static function GetUserMenu()
    {
        $menuList = $menu = $groups = $actions = array();
        $controllerId = Yii::app()->controller->id;
        $actionId = Yii::app()->controller->action->id;
        $role_id = isset(Yii::app()->user->role_id) ? Yii::app()->user->role_id : 0;
        $db = Yii::app()->db;
        $sql = "select * from {{role_nav}} where is_effect=1 order by sort asc";
        $navs = $db->createCommand($sql)->queryAll();
        foreach ($navs as $n) {
            $sql = "select * from {{role_nav_group}} where nav_id=" . $n['id'].' order by sort asc';
            $nav_group = $db->createCommand($sql)->queryAll();
            foreach ($nav_group as $k => $v) {
                $sql = "select role_node.`action` as a,role_module.`module` as m,role_node.id as nid,role_node.name as name from {{role_action}} as role_node left join
                {{role_module}} as role_module on role_module.id = role_node.module_id
                 where role_node.is_effect = 1  and role_module.is_effect = 1 and
                  role_node.group_id = " . $v['id'] . " order by role_node.id asc";
                $nav_group[$k]['nodes'] = $db->createCommand($sql)->queryAll();
            }
            $n['group'] = $nav_group;
            $menu[] = $n;
        }
        $sql = "select * from {{role_access}} as role left join {{role_action}} as action on role.action_id=action.id LEFT JOIN {{role_module}} as module on role.module_id=module.id and role.role_id=$role_id";
        $access = $db->createCommand($sql)->queryAll();
        foreach ($menu as $k => $m) {
            if ($m['group']) {
                foreach ($m['group'] as $kg => $group) {
                    if ($group['nodes']) {
                        foreach ($group['nodes'] as $kn => $node) {
                            foreach ($access as $a) {
                                unset($group['nodes']);
                                unset($m['group']);
                                if (!isset($menuList[$k]['group'][$kg]['nodes'][$kn]) && $node['a'] == $a['action'] && $node['m'] == $a['module']) {
                                    if (!isset($menuList[$k]))
                                        $menuList[$k] = $m;
                                    if (!isset($menuList[$k]['group'][$kg]))
                                        $menuList[$k]['group'][$kg] = $group;
                                    $menuList[$k]['group'][$kg]['nodes'][$kn] = $node;
                                }

                            }
                            //active status
                            if($node['m'] == $controllerId){
                                $menuList[$k]['active'] = true;
                                $menuList[$k]['group'][$kg]['active'] = true;
                                $menuList[$k]['group'][$kg]['nodes']['active'] = true;
                            }
                            if ($node['m'] == $controllerId && $node['a'] == $actionId) {
                                $menuList[$k]['group'][$kg]['nodes'][$kn]['active'] = true;
                            }
                        }
                    }
                }
            }

        }

        return $menuList;
    }
    private static function GetBorrowerMenu()
    {
        $actionId = Yii::app()->controller->action->id;
        $infoListActive = in_array($actionId, array('info', 'trade')) ? 1 : 0;
        $projectListActive = in_array($actionId, array('project')) ? 1 : 0;
        $menuList =
            array(
                array(
                    'id' => 1,
                    'name' => '我的账户',
                    'icons' => 'icon-user',
                    'active' => $infoListActive,
                    'group' => array(
                        array(
                            'name' => '账户详情',
                            'nav_id' => 1,
                            'icon' => '',
                            'nodes' => array(
                                array('a' => 'info', 'm' => 'yeeBorrower', 'name' => '账户详情')
                            )
                        ),
                    )
                ),
            );
        return $menuList;
    }

    public function run()
    {
        if(Yii::app()->user->getState('role_id')!=0)
            $this->menus = self::GetUserMenu();
        else
            $this->menus= self::GetBorrowerMenu();

        $this->render('menu');
    }
} 