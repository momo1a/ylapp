<?php
/**
 * RBAC权限配置文件
 */

/**
 * 角色表名
 */
$config['role_table'] = 'shs_system_privilege_role';

/**
 * 用户角色关系表
 */
$config['role_user_table'] = 'shs_system_privilege_role_user';

/**
 * 角色权限表
 */
$config['role_action_table'] = 'shs_system_privilege_role_action';

/**
 * 权限操作表
 */
$config['action_table'] = 'shs_system_privilege_action';

/**
 * 用户表
 */
$config['user_table'] = 'shs_user';

/**
 * 权限操作分类表
 * 
 * 更新时间 2014.1.21
 * @author 韦明磊<nicolaslei@163.com>
 */
$config['action_category_table'] = 'shs_system_privilege_action_category';